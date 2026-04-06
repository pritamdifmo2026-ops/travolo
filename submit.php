<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['form_type'];
    $response = ['status' => 'error', 'message' => 'An unknown error occurred.'];
    
    if ($type == "contact") {
        $name = $conn->real_escape_string($_POST['name']);
        $email = $conn->real_escape_string($_POST['email']);
        $phone = $conn->real_escape_string($_POST['number']);
        $website = $conn->real_escape_string($_POST['website']);
        $message = $conn->real_escape_string($_POST['message']);
        
        $sql = "INSERT INTO contact_messages (name, email, phone, website, message) VALUES ('$name', '$email', '$phone', '$website', '$message')";
        if ($conn->query($sql) === TRUE) {
            $response = ['status' => 'success', 'message' => 'Message Sent successfully!'];
        } else {
            $response = ['status' => 'error', 'message' => 'Error: ' . $conn->error];
        }
    }
    else if ($type == "flight") {
        $trip_type = $conn->real_escape_string($_POST['tripType'] ?? '');
        $from = $conn->real_escape_string($_POST['from'] ?? '');
        $to = $conn->real_escape_string($_POST['to'] ?? '');
        $depart_date = $conn->real_escape_string($_POST['depart_date'] ?? '');
        $return_date = $conn->real_escape_string($_POST['return_date'] ?? '');
        $adults = intval($_POST['adults'] ?? 1);
        $children = intval($_POST['children'] ?? 0);
        $infants = intval($_POST['infants'] ?? 0);
        $tclass = $conn->real_escape_string($_POST['travel_class'] ?? 'Economy');
        $phone = $conn->real_escape_string($_POST['mobile'] ?? $_POST['phone'] ?? '');
        
        if ($from === $to && $from !== '') {
            $response = ['status' => 'error', 'message' => 'Origin and destination cannot be the same.'];
        } else {
            $sql = "INSERT INTO flights (trip_type, from_city, to_city, depart_date, return_date, adults, children, infants, travel_class, phone) VALUES ('$trip_type', '$from', '$to', '$depart_date', '$return_date', $adults, $children, $infants, '$tclass', '$phone')";
            if ($conn->query($sql) === TRUE) {
                $response = ['status' => 'success', 'message' => 'Flight Booking Request Sent!'];
            } else {
                $response = ['status' => 'error', 'message' => 'Error: ' . $conn->error];
            }
        }
    }
    else if ($type == "flight_search") {
        $from = $conn->real_escape_string($_POST['from'] ?? '');
        $to = $conn->real_escape_string($_POST['to'] ?? '');
        $depart_date = $conn->real_escape_string($_POST['depart_date'] ?? '');
        $trip_type = $conn->real_escape_string($_POST['tripType'] ?? 'One Way');
        $adults = intval($_POST['adults'] ?? 1);
        $children = intval($_POST['children'] ?? 0);
        $infants = intval($_POST['infants'] ?? 0);
        $tclass = $conn->real_escape_string($_POST['travel_class'] ?? 'Economy');
        $mobile = $conn->real_escape_string($_POST['mobile'] ?? '');

        $sql = "INSERT INTO flight_searches (from_city, to_city, depart_date, trip_type, adults, children, infants, travel_class, mobile) 
                VALUES ('$from', '$to', '$depart_date', '$trip_type', $adults, $children, $infants, '$tclass', '$mobile')";
        if ($conn->query($sql) === TRUE) {
            $response = ['status' => 'success', 'message' => 'Search Logged'];
        } else {
            $response = ['status' => 'error', 'message' => 'Error: ' . $conn->error];
        }
    }
    else if ($type == "hotel") {
        $check_in = $conn->real_escape_string($_POST['check_in'] ?? '');
        $check_out = $conn->real_escape_string($_POST['check_out'] ?? '');
        $search = $conn->real_escape_string($_POST['search'] ?? '');
        $room_type = $conn->real_escape_string($_POST['room_type'] ?? '');
        $guests = $conn->real_escape_string($_POST['guests'] ?? '1 Room, 2 Guests');
        $price = intval($_POST['price'] ?? 0);
        $phone = $conn->real_escape_string($_POST['phone'] ?? '');
        $hotel_id = intval($_POST['hotel_id'] ?? 0);
        $status = $conn->real_escape_string($_POST['status'] ?? 'Checked');
        
        $user_name = $conn->real_escape_string($_POST['name'] ?? '');
        $email = $conn->real_escape_string($_POST['email'] ?? '');
        $b_type = $conn->real_escape_string($_POST['booking_type'] ?? 'Check');
        
        $sql = "INSERT INTO hotels (check_in, check_out, hotel_search, room_type, guests, price, phone, hotel_id, status, user_name, email, booking_type, booking_status) 
                VALUES ('$check_in', '$check_out', '$search', '$room_type', '$guests', $price, '$phone', $hotel_id, '$status', '$user_name', '$email', '$b_type', 'Requested')";
                
        if ($conn->query($sql) === TRUE) {
            $msg = ($b_type == 'Booking') ? "Booking Query Sent Successfully!" : "Hotel Availability Checked!";
            $response = ['status' => 'success', 'message' => $msg];
        } else {
            $response = ['status' => 'error', 'message' => 'Error: ' . $conn->error];
        }
    }
    else if ($type == "cab") {
        $trip_type = $conn->real_escape_string($_POST['tripType'] ?? '');
        $pickup = $conn->real_escape_string($_POST['pickup'] ?? '');
        $from = $conn->real_escape_string($_POST['from'] ?? '');
        $to = $conn->real_escape_string($_POST['to'] ?? '');
        $pickup_date = $conn->real_escape_string($_POST['pickup_date'] ?? '');
        $pickup_time = $conn->real_escape_string($_POST['pickup_time'] ?? '');
        $return_date = $conn->real_escape_string($_POST['return_date'] ?? '');
        $return_time = $conn->real_escape_string($_POST['return_time'] ?? '');
        $hours = $conn->real_escape_string($_POST['hours'] ?? '');
        $hours = $conn->real_escape_string($_POST['hours'] ?? '');
        $phone = $conn->real_escape_string($_POST['phone'] ?? '');
        
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

    // Return JSON response for AJAX
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
