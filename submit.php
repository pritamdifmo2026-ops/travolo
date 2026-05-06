<?php
include 'includes/db.php';
include 'includes/auth.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// MOBILE & EMAIL VALIDATION HELPER
function isValidPhone($p)
{
    return preg_match('/^[6-9]\d{9}$/', $p); // Standard 10-digit Indian Mobile Validation
}

function isValidEmail($e)
{
    return filter_var($e, FILTER_VALIDATE_EMAIL);
}

// GUEST BOOKING HELPER: Ensures a user session exists (creates user if new)
function ensureUserSession($conn, $name, $email, $phone)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // 1. Check if user already exists by email or phone
    $stmt = $conn->prepare("SELECT id, name, email, phone FROM users WHERE email = ? OR phone = ?");
    $stmt->bind_param("ss", $email, $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $uid = $user['id'];
    } else {
        // 2. Create a new "Guest" user
        $stmt_ins = $conn->prepare("INSERT INTO users (name, email, phone) VALUES (?, ?, ?)");
        $stmt_ins->bind_param("sss", $name, $email, $phone);
        $stmt_ins->execute();
        $uid = $conn->insert_id;
    }

    // 3. Set Session
    $_SESSION['user_id'] = $uid;
    $_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_phone'] = $phone;

    return $uid;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['form_type'] ?? '';

    // Check search limit for booking/search actions if not logged in
    if (in_array($type, ['flight', 'flight_search', 'hotel', 'cab'])) {
        if (!check_search_limit(false)) {
            $prev_phone = $_POST['phone'] ?? $_POST['mobile'] ?? '';
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Login required for second search',
                'redirect' => 'login-user.php' . ($prev_phone ? "?phone=" . urlencode($prev_phone) : "")
            ]);
            exit;
        }
        increment_search_count();
    }

    $response = ['status' => 'error', 'message' => 'An unknown error occurred.'];


    if ($type == "contact") {


        $name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : '';
        $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
        $phone = isset($_POST['number']) ? $conn->real_escape_string($_POST['number']) : '';
        $message = isset($_POST['message']) ? $conn->real_escape_string($_POST['message']) : '';

        $website = $conn->real_escape_string($_POST['website'] ?? '');

        if (empty($name) || empty($email) || empty($phone) || empty($message)) {
            $response = ['status' => 'error', 'message' => 'All fields are required.'];
        } elseif (!isValidEmail($email)) {
            $response = ['status' => 'error', 'message' => 'Invalid email address format.'];
        } elseif (!isValidPhone($phone)) {
            $response = ['status' => 'error', 'message' => 'Enter a valid 10-digit mobile number.'];
        } else {

            //  email send function
            sendEmail($name, $email, $phone, $message, $website);

            $sql = "INSERT INTO contact_messages (name, email, phone, website, message) VALUES ('$name', '$email', '$phone', '$website', '$message')";
            if ($conn->query($sql) === TRUE) {
                $response = ['status' => 'success', 'message' => 'Message Sent successfully!'];
            } else {
                $response = ['status' => 'error', 'message' => 'Error: ' . $conn->error];
            }
        }
    } else if ($type == "flight") {
        $from = isset($_POST['from']) ? $conn->real_escape_string($_POST['from']) : '';
        $to = isset($_POST['to']) ? $conn->real_escape_string($_POST['to']) : '';
        $depart_date = isset($_POST['depart_date']) ? $conn->real_escape_string($_POST['depart_date']) : '';
        $phone = $conn->real_escape_string($_POST['mobile'] ?? $_POST['phone'] ?? '');
        if (empty($phone))
            $phone = $_SESSION['user_phone'] ?? '';

        if (empty($from) || empty($to) || empty($depart_date) || empty($phone)) {
            $response = ['status' => 'error', 'message' => 'Required fields (From, To, Date, Mobile) are missing.'];
        } elseif (!isValidPhone($phone)) {
            $response = ['status' => 'error', 'message' => 'Invalid mobile number. 10 digits required.'];
        } elseif ($from === $to) {
            $response = ['status' => 'error', 'message' => 'Origin and destination cannot be the same.'];
        } else {
            $trip_type = $conn->real_escape_string($_POST['tripType'] ?? 'One Way');
            $return_date = $conn->real_escape_string($_POST['return_date'] ?? '');
            $adults = intval($_POST['adults'] ?? 1);
            $children = intval($_POST['children'] ?? 0);
            $infants = intval($_POST['infants'] ?? 0);
            $tclass = $conn->real_escape_string($_POST['travel_class'] ?? 'Economy');

            $email = $_POST['email'] ?? $_SESSION['user_email'] ?? '';
            $user_name = $_POST['name'] ?? $_SESSION['user_name'] ?? 'User';

            // Ensure we have a user session (Guest Booking Logic)
            $uid = ensureUserSession($conn, $user_name, $email, $phone);

            $sql = "INSERT INTO flights (user_id, user_name, trip_type, from_city, to_city, depart_date, return_date, adults, children, infants, travel_class, phone, email) 
                    VALUES ($uid, '$user_name', '$trip_type', '$from', '$to', '$depart_date', '$return_date', $adults, $children, $infants, '$tclass', '$phone', '$email')";
            if ($conn->query($sql) === TRUE) {
                $response = [
                    'status' => 'success',
                    'message' => 'Flight Booking Request Sent! Redirecting to your dashboard...',
                    'redirect' => 'user-dashboard.php'
                ];
            } else {
                $response = ['status' => 'error', 'message' => 'Error: ' . $conn->error];
            }
        }
    } else if ($type == "flight_search") {
        $from = isset($_POST['from']) ? $conn->real_escape_string($_POST['from']) : '';
        $to = isset($_POST['to']) ? $conn->real_escape_string($_POST['to']) : '';
        $mobile = isset($_POST['mobile']) ? $conn->real_escape_string($_POST['mobile']) : '';
        if (empty($mobile))
            $mobile = $_SESSION['user_phone'] ?? '';

        if (empty($from) || empty($to) || empty($mobile)) {
            $response = ['status' => 'error', 'message' => 'Please provide origin, destination and mobile.'];
        } elseif (!isValidPhone($mobile)) {
            $response = ['status' => 'error', 'message' => '10-digit mobile number required for search.'];
        } else {
            $depart_date = $conn->real_escape_string($_POST['depart_date'] ?? '');
            $trip_type = $conn->real_escape_string($_POST['tripType'] ?? 'One Way');
            $adults = intval($_POST['adults'] ?? 1);
            $children = intval($_POST['children'] ?? 0);
            $infants = intval($_POST['infants'] ?? 0);
            $tclass = $conn->real_escape_string($_POST['travel_class'] ?? 'Economy');
            $uid = $_SESSION['user_id'] ?? 0;
            $email = $_SESSION['user_email'] ?? $_POST['email'] ?? '';

            $sql = "INSERT INTO flight_searches (user_id, from_city, to_city, depart_date, trip_type, adults, children, infants, travel_class, mobile, email) 
                    VALUES ($uid, '$from', '$to', '$depart_date', '$trip_type', $adults, $children, $infants, '$tclass', '$mobile', '$email')";
            if ($conn->query($sql) === TRUE) {
                $response = ['status' => 'success', 'message' => 'Search Logged'];
            } else {
                $response = ['status' => 'error', 'message' => 'Error: ' . $conn->error];
            }
        }
    } else if ($type == "hotel") {
        $check_in = isset($_POST['check_in']) ? $conn->real_escape_string($_POST['check_in']) : '';
        $phone = isset($_POST['phone']) ? $conn->real_escape_string($_POST['phone']) : '';
        if (empty($phone))
            $phone = $_SESSION['user_phone'] ?? '';
        $user_name = isset($_POST['name']) && !empty($_POST['name']) ? $conn->real_escape_string($_POST['name']) : ($_SESSION['user_name'] ?? '');
        $email = isset($_POST['email']) && !empty($_POST['email']) ? $conn->real_escape_string($_POST['email']) : ($_SESSION['user_email'] ?? '');

        if (empty($check_in) || empty($phone) || (empty($user_name) && empty($_SESSION['user_name']))) {
            $response = ['status' => 'error', 'message' => 'Check-in date, Name, and Mobile are required.'];
        } elseif (!isValidPhone($phone)) {
            $response = ['status' => 'error', 'message' => 'Enter a valid 10-digit mobile number.'];
        } elseif (!empty($email) && !isValidEmail($email)) {
            $response = ['status' => 'error', 'message' => 'Invalid email format.'];
        } else {
            if (empty($user_name))
                $user_name = 'Customer'; // Final fallback
            $check_out = $conn->real_escape_string($_POST['check_out'] ?? '');
            $search = $conn->real_escape_string($_POST['search'] ?? '');
            $room_type = $conn->real_escape_string($_POST['room_type'] ?? '');
            $guests = $conn->real_escape_string($_POST['guests'] ?? '');
            $accomm = $conn->real_escape_string($_POST['accommodations'] ?? '');
            $price = intval($_POST['price'] ?? 0);
            $hotel_id = intval($_POST['hotel_id'] ?? 0);
            $status = $conn->real_escape_string($_POST['status'] ?? 'Checked');
            $b_type = $conn->real_escape_string($_POST['booking_type'] ?? 'Check');

            // Ensure we have a user session (Guest Booking Logic)
            $uid = ensureUserSession($conn, $user_name, $email, $phone);

            $sql = "INSERT INTO hotels (user_id, check_in, check_out, hotel_search, accommodations, room_type, guests, price, phone, hotel_id, status, user_name, email, booking_type, booking_status) 
                    VALUES ($uid, '$check_in', '$check_out', '$search', '$accomm', '$room_type', '$guests', $price, '$phone', $hotel_id, '$status', '$user_name', '$email', '$b_type', 'Requested')";

            if ($conn->query($sql) === TRUE) {
                $msg = ($b_type == 'Booking') ? "Booking Query Sent Successfully! Redirecting to dashboard..." : "Hotel Availability Checked!";
                $response = [
                    'status' => 'success',
                    'message' => $msg,
                    'redirect' => ($b_type == 'Booking') ? 'user-dashboard.php' : ''
                ];
            } else {
                error_log("SQL Error in hotel search: " . $conn->error);
                $response = ['status' => 'error', 'message' => 'Database Error: ' . $conn->error];
            }
        }
    } else if ($type == "cab") {
        $from = isset($_POST['from']) ? $conn->real_escape_string($_POST['from']) : '';
        $pickup_date = isset($_POST['pickup_date']) ? $conn->real_escape_string($_POST['pickup_date']) : '';
        $phone = $conn->real_escape_string($_POST['mobile'] ?? $_POST['phone'] ?? '');
        if (empty($phone))
            $phone = $_SESSION['user_phone'] ?? '';

        if (empty($from) || empty($pickup_date) || empty($phone)) {
            $response = ['status' => 'error', 'message' => 'Source, Date, and Mobile number are mandatory.'];
        } elseif (!isValidPhone($phone)) {
            $response = ['status' => 'error', 'message' => 'Enter a valid 10-digit mobile number.'];
        } else {
            $trip_type = $conn->real_escape_string($_POST['tripType'] ?? '');
            $pickup = $conn->real_escape_string($_POST['pickup'] ?? '');
            $to = $conn->real_escape_string($_POST['to'] ?? '');
            $pickup_time = $conn->real_escape_string($_POST['pickup_time'] ?? '');
            $return_date = $conn->real_escape_string($_POST['return_date'] ?? '');
            $return_time = $conn->real_escape_string($_POST['return_time'] ?? '');
            $hours = $conn->real_escape_string($_POST['hours'] ?? '');

            if ($from === $to && $from !== '' && $trip_type !== 'Hourly') {
                $response = ['status' => 'error', 'message' => 'Pickup and drop cities cannot be the same.'];
            } else {
                $uid = $_SESSION['user_id'] ?? 0;
                $email = $_POST['email'] ?? $_SESSION['user_email'] ?? '';
                $user_name = $_POST['name'] ?? $_SESSION['user_name'] ?? 'User';

                // Ensure we have a user session (Guest Booking Logic)
                $uid = ensureUserSession($conn, $user_name, $email, $phone);

                $pick_addr = $conn->real_escape_string($_GET['pickup_address'] ?? '');
                $drop_addr = $conn->real_escape_string($_GET['dropoff_address'] ?? '');

                $sql = "INSERT INTO cabs (user_id, user_name, trip_type, pickup_type, from_city, to_city, pickup_date, pickup_time, return_date, return_time, hours, phone, email, pickup_address, dropoff_address) 
                        VALUES ($uid, '$user_name', '$trip_type', '$pickup', '$from', '$to', '$pickup_date', '$pickup_time', '$return_date', '$return_time', '$hours', '$phone', '$email', '$pick_addr', '$drop_addr')";

                if ($conn->query($sql) === TRUE) {
                    $response = [
                        'status' => 'success',
                        'message' => 'Cab Booking Request Sent! Redirecting to dashboard...',
                        'redirect' => 'user-dashboard.php'
                    ];
                } else {
                    $response = ['status' => 'error', 'message' => 'Error: ' . $conn->error];
                }
            }
        }
    } elseif ($type == "update_booking" || (isset($_POST['action']) && $_POST['action'] == "update_booking")) {
        $id = (int) ($_POST['id'] ?? 0);
        $booking_type = $_POST['type'] ?? '';
        $phone = $conn->real_escape_string($_POST['phone'] ?? '');
        $uid = $_SESSION['user_id'] ?? 0;

        if (!$id || !$booking_type || !$uid) {
            $response = ['status' => 'error', 'message' => 'Invalid request data.'];
        } else {
            $table = "";
            $sets = ["phone = '$phone'"];

            if ($booking_type == "Flight") {
                $table = "flights";
                $from = $conn->real_escape_string($_POST['from_city'] ?? '');
                $to = $conn->real_escape_string($_POST['to_city'] ?? '');
                $depart = $conn->real_escape_string($_POST['depart_date'] ?? '');
                $return = $conn->real_escape_string($_POST['return_date'] ?? '');
                $sets[] = "from_city = '$from'";
                $sets[] = "to_city = '$to'";
                $sets[] = "depart_date = '$depart'";
                if ($return)
                    $sets[] = "return_date = '$return'";
            } elseif ($booking_type == "Hotel") {
                $table = "hotels";
                $h_search = $conn->real_escape_string($_POST['hotel_search'] ?? '');
                $checkin = $conn->real_escape_string($_POST['check_in'] ?? '');
                $checkout = $conn->real_escape_string($_POST['check_out'] ?? '');
                $room_type = $conn->real_escape_string($_POST['room_type'] ?? '');
                $guests = $conn->real_escape_string($_POST['guests'] ?? '');
                $price = intval($_POST['price'] ?? 0);

                $sets[] = "hotel_search = '$h_search'";
                $sets[] = "check_in = '$checkin'";
                $sets[] = "check_out = '$checkout'";
                $sets[] = "room_type = '$room_type'";
                $sets[] = "guests = '$guests'";
                if ($price > 0)
                    $sets[] = "price = $price";
            } elseif ($booking_type == "Cab") {
                $table = "cabs";
                $from = $conn->real_escape_string($_POST['from_city'] ?? '');
                $to = $conn->real_escape_string($_POST['to_city'] ?? '');
                $date = $conn->real_escape_string($_POST['pickup_date'] ?? '');
                $time = $conn->real_escape_string($_POST['pickup_time'] ?? '');
                $trip = $conn->real_escape_string($_POST['trip_type'] ?? '');
                $sets[] = "from_city = '$from'";
                $sets[] = "to_city = '$to'";
                $sets[] = "pickup_date = '$date'";
                $sets[] = "pickup_time = '$time'";
                if (!empty($trip)) {
                    $sets[] = "trip_type = '$trip'";
                }
            }

            if ($table) {
                $sql = "UPDATE $table SET " . implode(", ", $sets) . " WHERE id = $id AND user_id = $uid AND (booking_status = 'Requested' OR booking_status = 'Pending' OR booking_status = '')";
                if ($conn->query($sql) === TRUE) {
                    $response = ['status' => 'success', 'message' => 'Booking updated successfully!'];
                } else {
                    $response = ['status' => 'error', 'message' => 'Update failed: ' . $conn->error];
                }
            } else {
                $response = ['status' => 'error', 'message' => 'Invalid booking type.'];
            }
        }
    }

    // Return JSON response for AJAX
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// === GET REQUESTS HANDLER (FOR QUICK BOOKING CONFIRMATION) ===
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action']) && $_GET['action'] === 'book_cab') {
    $cab_id = intval($_GET['cab_id'] ?? 0);
    $from = $conn->real_escape_string($_GET['from'] ?? '');
    $to = $conn->real_escape_string($_GET['to'] ?? '');
    $date = $conn->real_escape_string($_GET['date'] ?? '');
    $time = $conn->real_escape_string($_GET['time'] ?? '');
    $trip = $conn->real_escape_string($_GET['tripType'] ?? '');
    $pickup = $conn->real_escape_string($_GET['pickup'] ?? 'One Way');
    $mobile = $conn->real_escape_string($_GET['mobile'] ?? '');
    if (empty($mobile))
        $mobile = $_SESSION['user_phone'] ?? '';
    $uid = $_SESSION['user_id'] ?? 0;
    $email = $_GET['email'] ?? $_SESSION['user_email'] ?? '';
    $user_name = $conn->real_escape_string($_GET['name'] ?? $_SESSION['user_name'] ?? 'User');
    $payment_type = $conn->real_escape_string($_GET['payment_type'] ?? 'full');
    $coupon_code = $conn->real_escape_string($_GET['coupon_code'] ?? '');

    // Ensure we have a user session (Guest Booking Logic)
    $uid = ensureUserSession($conn, $user_name, $email, $mobile);

    $pick_addr = $conn->real_escape_string($_GET['pickup_address'] ?? '');
    $drop_addr = $conn->real_escape_string($_GET['dropoff_address'] ?? '');
    $hours = $conn->real_escape_string($_GET['hours'] ?? '');

    // Fetch Base Price and Calculate Total
    $price_col = 'base_price';
    if ($trip === 'Hourly')
        $price_col = 'hourly_price';
    elseif ($trip === 'Airport Transfer' || $to === 'Airport' || $from === 'Airport')
        $price_col = 'airport_price';
    elseif ($trip === 'Outstation')
        $price_col = 'outstation_price';

    $cabs_res = $conn->query("SELECT $price_col as display_price FROM cab_inventory WHERE id = $cab_id LIMIT 1");
    if (!$cabs_res) {
        die("Database Error: " . $conn->error . " (Column: $price_col). Please check if cab_inventory table exists.");
    }
    $cab_data = $cabs_res->fetch_assoc();
    $total_fare = $cab_data['display_price'] ?? 0;

    if ($trip === 'Hourly' && !empty($hours)) {
        $selected_hours = intval($hours);
        if ($selected_hours > 0) {
            $price_per_hour = $total_fare / 8;
            $total_fare = round($price_per_hour * $selected_hours);
        }
    }

    // Calculate Paid Amount
    $paid_amount = $total_fare;
    $payment_status = 'Fully Paid';

    if ($payment_type === 'partial') {
        $paid_amount = round($total_fare * 0.25);
        $payment_status = 'Partially Paid';
    }

    $sql = "INSERT INTO cabs (user_id, user_name, cab_id, trip_type, pickup_type, from_city, to_city, pickup_date, pickup_time, phone, email, pickup_address, dropoff_address, payment_type, coupon_code, total_fare, paid_amount, payment_status, hours) 
            VALUES ($uid, '$user_name', $cab_id, '$trip', '$pickup', '$from', '$to', '$date', '$time', '$mobile', '$email', '$pick_addr', '$drop_addr', '$payment_type', '$coupon_code', $total_fare, $paid_amount, '$payment_status', '$hours')";

    $success = false;
    if ($conn->query($sql) === TRUE) {
        $success = true;
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Booking Confirmation | TravoLo</title>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <style>
            body {
                font-family: 'Outfit', sans-serif;
                background: #f4f7f6;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
                margin: 0;
            }
        </style>
    </head>

    <body>
        <script>
            <?php if ($success): ?>
                Swal.fire({
                    title: 'Booking Confirmed!',
                    text: 'Your cab booking request has been sent successfully. Redirecting to your dashboard...',
                    icon: 'success',
                    confirmButtonColor: '#00a79d',
                    confirmButtonText: 'View Dashboard',
                    timer: 3000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = 'user-dashboard.php';
                });
            <?php else: ?>
                Swal.fire({
                    title: 'Booking Failed',
                    text: 'Error: <?php echo $conn->error; ?>',
                    icon: 'error',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Try Again'
                }).then(() => {
                    window.history.back();
                });
            <?php endif; ?>
        </script>
    </body>

    </html>
    <?php
    exit;
}

function sendEmail($from_name, $from_email, $mobile, $message, $website)
{
    // email send code

    $mail = new PHPMailer(true);

    try {
        // SMTP config
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'rg515642@gmail.com'; // sender email
        $mail->Password = 'cfka yyiv segn rwqi';    // app password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Sender & Reply-To
        $mail->setFrom('rg515642@gmail.com', 'Travolo Website');
        $mail->addReplyTo($from_email, $from_name);

        // Receiver
        $mail->addAddress('rg515642@gmail.com');

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'New Contact Inquiry from ' . $from_name;

        $mail->Body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e1e1e1; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05);'>
            <div style='background: linear-gradient(135deg, #00a79d 0%, #007a72 100%); padding: 30px; text-align: center;'>
                <h1 style='color: white; margin: 0; font-size: 24px; text-transform: uppercase; letter-spacing: 2px;'>New Message</h1>
            </div>
            <div style='padding: 30px; background-color: #ffffff;'>
                <p style='font-size: 16px; color: #555; line-height: 1.6;'>You have received a new inquiry from the contact form on your website.</p>
                <div style='background-color: #f9f9f9; padding: 20px; border-left: 4px solid #00a79d; border-radius: 4px; margin: 20px 0;'>
                    <table style='width: 100%; border-collapse: collapse;'>
                        <tr>
                            <td style='padding: 8px 0; color: #888; width: 100px;'><strong>Name:</strong></td>
                            <td style='padding: 8px 0; color: #333;'>$from_name</td>
                        </tr>
                        <tr>
                            <td style='padding: 8px 0; color: #888;'><strong>Email:</strong></td>
                            <td style='padding: 8px 0; color: #333;'>$from_email</td>
                        </tr>
                        <tr>
                            <td style='padding: 8px 0; color: #888;'><strong>Mobile:</strong></td>
                            <td style='padding: 8px 0; color: #333;'>$mobile</td>
                        </tr>
                        
                        <tr>
                            <td style='padding: 8px 0; color: #888;'><strong>Website:</strong></td>
                            <td style='padding: 8px 0; color: #333;'>$website</td>
                        </tr>
                    </table>
                </div>
                <div style='margin-top: 25px;'>
                    <h3 style='color: #00a79d; margin-bottom: 10px;'>Message:</h3>
                    <div style='background: #f1f1f1; padding: 15px; border-radius: 8px; color: #444; font-style: italic; border: 1px solid #eee;'>
                        " . nl2br(htmlspecialchars($message)) . "
                    </div>
                </div>
            </div>
            <div style='background-color: #f4f4f4; padding: 15px; text-align: center; font-size: 12px; color: #999;'>
                &copy; " . date('Y') . " TravoLo Website. All rights reserved.
            </div>
        </div>";

        // Send
        $mail->send();
        // echo "Email sent successfully";
        // return true;
    } catch (Exception $e) {
        // echo "Error: " . $mail->ErrorInfo;
        // return false;
    }

    // end email send code

}
?>