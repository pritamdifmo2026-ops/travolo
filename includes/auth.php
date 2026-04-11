<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fetch additional user details if logged in
if (isset($_SESSION['user_id']) && (!isset($_SESSION['user_name']) || !isset($_SESSION['user_email']) || !isset($_SESSION['user_phone']))) {
    include_once __DIR__ . '/db.php';
    if (isset($conn)) {
        $uid = $_SESSION['user_id'];
        $uRes = $conn->query("SELECT name, email, phone FROM users WHERE id = $uid");
        if ($uRes && $uRes->num_rows > 0) {
            $uData = $uRes->fetch_assoc();
            $_SESSION['user_name'] = $uData['name'];
            $_SESSION['user_email'] = $uData['email'];
            $_SESSION['user_phone'] = $uData['phone'];
        }
    }
}

/**
 * Check if the user has exceeded the search limit.
 * If logged in, there is no limit.
 * If not logged in, they can search only once.
 */
function check_search_limit($redirect_on_limit = true) {
    if (isset($_SESSION['user_id'])) {
        return true; // Logged in users have no limit
    }

    if (!isset($_SESSION['search_count'])) {
        $_SESSION['search_count'] = 0;
    }

    if ($_SESSION['search_count'] >= 1) {
        if ($redirect_on_limit) {
            // Check if it's an AJAX request
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                 header('Content-Type: application/json');
                 echo json_encode(['status' => 'error', 'message' => 'Login required for additional searches', 'redirect' => 'login-user.php']);
                 exit;
            }
            header("Location: login-user.php?msg=limit_reached&return_url=" . urlencode($_SERVER['REQUEST_URI']));
            exit;
        }
        return false;
    }
    return true;
}

/**
 * Increment search count for guest users.
 */
function increment_search_count() {
    if (!isset($_SESSION['user_id'])) {
        if (!isset($_SESSION['search_count'])) {
            $_SESSION['search_count'] = 0;
        }
        $_SESSION['search_count']++;
    }
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}
?>
