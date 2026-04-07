<?php
header('Content-Type: application/json');
include '../db.php';
session_start();

$action = $_POST['action'] ?? '';

if ($action === 'send_otp') {
    $phone = $_POST['phone'] ?? '';
    
    if (empty($phone)) {
        echo json_encode(['status' => 'error', 'message' => 'Phone number is required']);
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
        $stmt = $conn->prepare("UPDATE users SET otp = ?, otp_expiry = ? WHERE phone = ?");
        $stmt->bind_param("sss", $otp, $expiry, $phone);
    } else {
        $stmt = $conn->prepare("INSERT INTO users (phone, otp, otp_expiry) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $phone, $otp, $expiry);
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

echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
?>
