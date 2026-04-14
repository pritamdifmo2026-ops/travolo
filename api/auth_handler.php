<?php
header('Content-Type: application/json');
include '../includes/db.php';
session_start();

$action = $_POST['action'] ?? '';

if ($action === 'send_otp') {
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $name = $_POST['name'] ?? '';
    
    if (empty($phone) || empty($email) || empty($name)) {
        echo json_encode(['status' => 'error', 'message' => 'Full Name, Email and Phone number are mandatory']);
        exit;
    }

    // Generate a 6-digit OTP
    $otp = "123456"; // For demo purposes, we use 123456. In real implementation, use random_int(100000, 999999);
    $expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

    // Upsert user
    $stmt = $conn->prepare("SELECT id FROM users WHERE phone = ?");
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE users SET email = ?, name = ?, otp = ?, otp_expiry = ? WHERE phone = ?");
        $stmt->bind_param("sssss", $email, $name, $otp, $expiry, $phone);
    } else {
        $stmt = $conn->prepare("INSERT INTO users (phone, email, name, otp, otp_expiry) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $phone, $email, $name, $otp, $expiry);
    }

    if ($stmt->execute()) {
        // Here you would normally send the OTP via SMS using an API (e.g. Twilio, MSG91)
        echo json_encode(['status' => 'success', 'message' => 'OTP sent successfully', 'otp' => $otp]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
    }
    $stmt->close();
    exit;
}

if ($action === 'verify_otp') {
    $phone = $_POST['phone'] ?? '';
    $otp = $_POST['otp'] ?? '';

    if (empty($phone) || empty($otp)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing phone or OTP']);
        exit;
    }

    $stmt = $conn->prepare("SELECT id, otp, otp_expiry FROM users WHERE phone = ?");
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Phone number not found']);
        exit;
    }

    $user = $result->fetch_assoc();
    $now = date('Y-m-d H:i:s');

    if ($user['otp'] !== $otp) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid OTP']);
        exit;
    }

    if ($user['otp_expiry'] < $now) {
        echo json_encode(['status' => 'error', 'message' => 'OTP has expired']);
        exit;
    }

    // Login successful
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_phone'] = $phone;
    
    // Fetch name and email if they exist
    $stmt2 = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
    $stmt2->bind_param("i", $user['id']);
    $stmt2->execute();
    $uResult = $stmt2->get_result();
    if ($uMeta = $uResult->fetch_assoc()) {
        $_SESSION['user_name'] = $uMeta['name'];
        $_SESSION['user_email'] = $uMeta['email'];
    }
    $stmt2->close();
    
    // Clear OTP after use
    $conn->query("UPDATE users SET otp = NULL, otp_expiry = NULL WHERE id = " . $user['id']);

    echo json_encode(['status' => 'success', 'message' => 'Logged in successfully']);
    $stmt->close();
    exit;
}

if ($action === 'social_login') {
    $email = $_POST['email'] ?? '';
    $name = $_POST['name'] ?? '';
    $social_id = $_POST['social_id'] ?? '';
    $social_type = $_POST['social_type'] ?? '';
    $phone = $_POST['phone'] ?? '';

    if (empty($email) || empty($social_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing essential social data']);
        exit;
    }

    // 1. Check if user exists by email
    $stmt = $conn->prepare("SELECT id, phone, name FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $uid = $user['id'];
        
        // Update social info if missing
        $stmt_upd = $conn->prepare("UPDATE users SET social_id = ?, social_type = ? WHERE id = ? AND social_id IS NULL");
        $stmt_upd->bind_param("ssi", $social_id, $social_type, $uid);
        $stmt_upd->execute();

        // Check if phone is needed
        $currentPhone = $user['phone'];
        if (empty($currentPhone) && empty($phone)) {
            echo json_encode(['status' => 'require_phone', 'message' => 'Phone number mandatory']);
            exit;
        }

        // If newly provided phone, save it
        if (empty($currentPhone) && !empty($phone)) {
             $stmt_ph = $conn->prepare("UPDATE users SET phone = ? WHERE id = ?");
             $stmt_ph->bind_param("si", $phone, $uid);
             $stmt_ph->execute();
             $currentPhone = $phone;
        }

        // LOGIN
        $_SESSION['user_id'] = $uid;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_phone'] = $currentPhone;
        $_SESSION['user_name'] = $user['name'] ?? $name;

        echo json_encode(['status' => 'success', 'message' => 'Social login successful']);
        exit;
    } else {
        // 2. New User Registration via Social
        if (empty($phone)) {
            echo json_encode(['status' => 'require_phone', 'message' => 'New user requires phone number']);
            exit;
        }

        $stmt_ins = $conn->prepare("INSERT INTO users (name, email, phone, social_id, social_type) VALUES (?, ?, ?, ?, ?)");
        $stmt_ins->bind_param("sssss", $name, $email, $phone, $social_id, $social_type);
        
        if ($stmt_ins->execute()) {
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_phone'] = $phone;
            $_SESSION['user_name'] = $name;
            echo json_encode(['status' => 'success', 'message' => 'Registration via social successful']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to create user: ' . $conn->error]);
        }
        exit;
    }
}

echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
?>
