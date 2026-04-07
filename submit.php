<?php
include 'db.php';
include 'auth.php';

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
    
    // MOBILE & EMAIL VALIDATION HELPER
    function isValidPhone($p) {
        return preg_match('/^[6-9]\d{9}$/', $p); // Standard 10-digit Indian Mobile Validation
    }
    
    function isValidEmail($e) {
        return filter_var($e, FILTER_VALIDATE_EMAIL);
    }

    if ($type == "contact") {
        $name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : '';
        $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
        $phone = isset($_POST['number']) ? $conn->real_escape_string($_POST['number']) : '';
        $message = isset($_POST['message']) ? $conn->real_escape_string($_POST['message']) : '';
        
        if (empty($name) || empty($email) || empty($phone) || empty($message)) {
            $response = ['status' => 'error', 'message' => 'All fields are required.'];
        } elseif (!isValidEmail($email)) {
            $response = ['status' => 'error', 'message' => 'Invalid email address format.'];
        } elseif (!isValidPhone($phone)) {
            $response = ['status' => 'error', 'message' => 'Enter a valid 10-digit mobile number.'];
        } else {
            $website = $conn->real_escape_string($_POST['website'] ?? '');
            $sql = "INSERT INTO contact_messages (name, email, phone, website, message) VALUES ('$name', '$email', '$phone', '$website', '$message')";
            if ($conn->query($sql) === TRUE) {
                $response = ['status' => 'success', 'message' => 'Message Sent successfully!'];
            } else {
                $response = ['status' => 'error', 'message' => 'Error: ' . $conn->error];
            }
        }
    }
    else if ($type == "flight") {
        $from = isset($_POST['from']) ? $conn->real_escape_string($_POST['from']) : '';
        $to = isset($_POST['to']) ? $conn->real_escape_string($_POST['to']) : '';
        $depart_date = isset($_POST['depart_date']) ? $conn->real_escape_string($_POST['depart_date']) : '';
        $phone = $conn->real_escape_string($_POST['mobile'] ?? $_POST['phone'] ?? '');
        
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
            
            $sql = "INSERT INTO flights (trip_type, from_city, to_city, depart_date, return_date, adults, children, infants, travel_class, phone) VALUES ('$trip_type', '$from', '$to', '$depart_date', '$return_date', $adults, $children, $infants, '$tclass', '$phone')";
            if ($conn->query($sql) === TRUE) {
                $response = ['status' => 'success', 'message' => 'Flight Booking Request Sent!'];
            } else {
                $response = ['status' => 'error', 'message' => 'Error: ' . $conn->error];
            }
        }
    }
    else if ($type == "flight_search") {
        $from = isset($_POST['from']) ? $conn->real_escape_string($_POST['from']) : '';
        $to = isset($_POST['to']) ? $conn->real_escape_string($_POST['to']) : '';
        $mobile = isset($_POST['mobile']) ? $conn->real_escape_string($_POST['mobile']) : '';
        
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

            $sql = "INSERT INTO flight_searches (from_city, to_city, depart_date, trip_type, adults, children, infants, travel_class, mobile) 
                    VALUES ('$from', '$to', '$depart_date', '$trip_type', $adults, $children, $infants, '$tclass', '$mobile')";
            if ($conn->query($sql) === TRUE) {
                $response = ['status' => 'success', 'message' => 'Search Logged'];
            } else {
                $response = ['status' => 'error', 'message' => 'Error: ' . $conn->error];
            }
        }
    }
    else if ($type == "hotel") {
        $check_in = isset($_POST['check_in']) ? $conn->real_escape_string($_POST['check_in']) : '';
        $phone = isset($_POST['phone']) ? $conn->real_escape_string($_POST['phone']) : '';
        $user_name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : '';
        $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
        
        if (empty($check_in) || empty($phone) || empty($user_name)) {
            $response = ['status' => 'error', 'message' => 'Check-in date, Name, and Mobile are required.'];
        } elseif (!isValidPhone($phone)) {
            $response = ['status' => 'error', 'message' => 'Enter a valid 10-digit mobile number.'];
        } elseif (!empty($email) && !isValidEmail($email)) {
            $response = ['status' => 'error', 'message' => 'Invalid email format.'];
        } else {
            $check_out = $conn->real_escape_string($_POST['check_out'] ?? '');
            $search = $conn->real_escape_string($_POST['search'] ?? '');
            $room_type = $conn->real_escape_string($_POST['room_type'] ?? '');
            $guests = $conn->real_escape_string($_POST['guests'] ?? '1 Room, 2 Guests');
            $price = intval($_POST['price'] ?? 0);
            $hotel_id = intval($_POST['hotel_id'] ?? 0);
            $status = $conn->real_escape_string($_POST['status'] ?? 'Checked');
            $b_type = $conn->real_escape_string($_POST['booking_type'] ?? 'Check');
            
            $sql = "INSERT INTO hotels (check_in, check_out, hotel_search, room_type, guests, price, phone, hotel_id, status, user_name, email, booking_type, booking_status) 
                    VALUES ('$check_in', '$check_out', '$search', '$room_type', '$guests', $price, '$phone', $hotel_id, '$status', '$user_name', '$email', '$b_type', 'Requested')";
                    
            if ($conn->query($sql) === TRUE) {
                if (isset($_SESSION['user_id'])) {
                    $uid = $_SESSION['user_id'];
                    $upd = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ? AND (name IS NULL OR email IS NULL OR name = '' OR email = '')");
                    $upd->bind_param("ssi", $user_name, $email, $uid);
                    $upd->execute();
                    $upd->close();
                }
                $msg = ($b_type == 'Booking') ? "Booking Query Sent Successfully!" : "Hotel Availability Checked!";
                $response = ['status' => 'success', 'message' => $msg];
            } else {
                $response = ['status' => 'error', 'message' => 'Error: ' . $conn->error];
            }
        }
    }
    else if ($type == "cab") {
        $from = isset($_POST['from']) ? $conn->real_escape_string($_POST['from']) : '';
        $pickup_date = isset($_POST['pickup_date']) ? $conn->real_escape_string($_POST['pickup_date']) : '';
        $phone = $conn->real_escape_string($_POST['mobile'] ?? $_POST['phone'] ?? '');
        
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
                $sql = "INSERT INTO cabs (trip_type, pickup_type, from_city, to_city, pickup_date, pickup_time, return_date, return_time, hours, phone) VALUES ('$trip_type', '$pickup', '$from', '$to', '$pickup_date', '$pickup_time', '$return_date', '$return_time', '$hours', '$phone')";
                if ($conn->query($sql) === TRUE) {
                    $response = ['status' => 'success', 'message' => 'Cab Booking Request Sent!'];
                } else {
                    $response = ['status' => 'error', 'message' => 'Error: ' . $conn->error];
                }
            }
        }
    }

    // Return JSON response for AJAX
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
