<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Helper function for File Uploads
function handleFileUpload($fileInputName, $targetDir = "assets/images/") {
    if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
        $name = basename($_FILES[$fileInputName]["name"]);
        $targetFile = $targetDir . time() . "_" . $name;
        if (move_uploaded_file($_FILES[$fileInputName]["tmp_name"], $targetFile)) {
            return $targetFile;
        }
    }
    return null;
}

// Add New Hotel logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_hotel') {
    $name = $conn->real_escape_string($_POST['name']);
    $loc = $conn->real_escape_string($_POST['location']);
    $price = $conn->real_escape_string($_POST['price']);
    $accom = $conn->real_escape_string($_POST['accommodations']);
    $desc = $conn->real_escape_string($_POST['description']);
    
    // File Upload for Hotel Image
    $image = handleFileUpload('hotel_image') ?: 'assets/images/tour-3-550x590.jpg';

    $conn->query("INSERT INTO app_hotels (name, location, price, accommodations, image, description) VALUES ('$name', '$loc', '$price', '$accom', '$image', '$desc')");
    header("Location: admin.php?success=Hotel+Added+Successfully");
    exit;
}

// Add New Offer Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_offer') {
    $title = $conn->real_escape_string($_POST['title']);
    $badge = $conn->real_escape_string($_POST['badge_text']);
    $color = $conn->real_escape_string($_POST['badge_color']);
    $desc = $conn->real_escape_string($_POST['description']);
    $footer = $conn->real_escape_string($_POST['footer_text']);
    
    // File Upload for Offer Image
    $image = handleFileUpload('offer_image') ?: 'assets/images/tour-3-550x590.jpg';

    $conn->query("INSERT INTO app_offers (image_url, badge_text, badge_color, title, description, footer_text) VALUES ('$image', '$badge', '$color', '$title', '$desc', '$footer')");
    header("Location: admin.php?success=Exclusive+Offer+Added+Successfully");
    exit;
}

// Update Offer Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_offer') {
    $offer_id = (int)$_POST['offer_id'];
    $title = $conn->real_escape_string($_POST['title']);
    $badge = $conn->real_escape_string($_POST['badge_text']);
    $color = $conn->real_escape_string($_POST['badge_color']);
    $desc = $conn->real_escape_string($_POST['description']);
    $footer = $conn->real_escape_string($_POST['footer_text']);
    $image = $_POST['existing_image']; // Keep the old image by default
    
    // File Upload (Update existing only if new provided)
    $new_image = handleFileUpload('offer_image');
    if ($new_image) {
        $image = $new_image;
    }

    $conn->query("UPDATE app_offers SET image_url='$image', badge_text='$badge', badge_color='$color', title='$title', description='$desc', footer_text='$footer' WHERE id=$offer_id");
    header("Location: admin.php?success=Exclusive+Offer+Updated+Successfully");
    exit;
}

// Toggle Offer Status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggle_offer') {
    $offer_id = (int)$_POST['offer_id'];
    $current_status = (int)$_POST['current_status'];
    $new_status = $current_status ? 0 : 1;
    $conn->query("UPDATE app_offers SET status=$new_status WHERE id=$offer_id");
    header("Location: admin.php?success=Offer+Status+Toggled");
    exit;
}

// Delete Offer Logic
if (isset($_GET['action']) && $_GET['action'] === 'delete_offer' && isset($_GET['id'])) {
    $offer_id = (int)$_GET['id'];
    $conn->query("DELETE FROM app_offers WHERE id=$offer_id");
    header("Location: admin.php?success=Offer+Deleted");
    exit;
}

// Delete Hotel Logic
if (isset($_GET['action']) && $_GET['action'] === 'delete_hotel' && isset($_GET['id'])) {
    $hotel_id = (int)$_GET['id'];
    $conn->query("DELETE FROM app_hotels WHERE id=$hotel_id");
    header("Location: admin.php?success=Hotel+Deleted+Successfully");
    exit;
}

// Delete Search Log Logic
if (isset($_GET['action']) && $_GET['action'] === 'delete_search' && isset($_GET['id'])) {
    $search_id = (int)$_GET['id'];
    $conn->query("DELETE FROM flight_searches WHERE id=$search_id");
    header("Location: admin.php?success=Search+Log+Deleted");
    exit;
}

// Clear All Search Logs
if (isset($_GET['action']) && $_GET['action'] === 'clear_searches') {
    $conn->query("TRUNCATE TABLE flight_searches");
    header("Location: admin.php?success=All+Search+Logs+Cleared");
    exit;
}

// Toggle Hotel Availability
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggle_hotel') {
    $hotel_id = (int)$_POST['hotel_id'];
    $current_status = (int)$_POST['current_status'];
    $new_status = $current_status ? 0 : 1;
    $conn->query("UPDATE app_hotels SET availability=$new_status WHERE id=$hotel_id");
    header("Location: admin.php?success=Availability+Updated");
    exit;
}

// Update Hotel Dates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_dates') {
    $hotel_id = (int)$_POST['hotel_id'];
    $dates = $conn->real_escape_string($_POST['available_dates']);
    $conn->query("UPDATE app_hotels SET available_dates='$dates' WHERE id=$hotel_id");
    header("Location: admin.php?success=Calendar+Dates+Updated");
    exit;
}

$offer_modals_html = '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Travolo Admin Dashboard</title>
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/fonts/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_orange.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f6fa;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background-color: #1a1c22;
            color: #b8c2cc;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 30px 25px;
            background-color: #121418;
            text-align: center;
            border-bottom: 1px solid #2a2c33;
        }

        .sidebar-header img {
            max-width: 140px;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 20px 0;
            flex-grow: 1;
        }

        .sidebar-menu li {
            margin-bottom: 5px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: #b8c2cc;
            text-decoration: none;
            font-size: 15px;
            transition: all 0.3s ease;
            position: relative;
        }

        .sidebar-menu a i {
            margin-right: 15px;
            font-size: 18px;
            width: 25px;
            text-align: center;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            color: #fff;
            background-color: #2a2c33;
        }

        .sidebar-menu a.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background-color: #F7921E;
        }

        .logout-wrapper {
            padding: 20px 25px;
            border-top: 1px solid #2a2c33;
        }

        .btn-logout {
            background-color: #e74c3c;
            color: white;
            width: 100%;
            padding: 10px;
            text-align: center;
            border-radius: 6px;
            display: block;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn-logout:hover {
            background-color: #c0392b;
            color: white;
        }

        /* Main Content */
        .main-content {
            flex-grow: 1;
            margin-left: 260px;
            padding: 40px;
            min-height: 100vh;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            background: #fff;
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
        }

        .page-header h2 {
            margin: 0;
            font-weight: 700;
            font-size: 24px;
            color: #2c3e50;
        }

        .user-info {
            display: flex;
            align-items: center;
            color: #7f8c8d;
            font-weight: 500;
        }

        .user-info i {
            margin-right: 8px;
            font-size: 20px;
            color: #133a25;
        }

        /* Cards */
        .data-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            margin-bottom: 40px;
            overflow: hidden;
            display: none;
            /* hidden by default for tab switching */
        }

        .data-card.active {
            display: block;
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-header {
            background-color: #fff;
            padding: 20px 30px;
            border-bottom: 1px solid #edf2f6;
            display: flex;
            align-items: center;
        }

        .card-header h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: #34495e;
            display: flex;
            align-items: center;
        }

        .card-header h4 i {
            color: #F7921E;
            margin-right: 12px;
            font-size: 20px;
            background: rgba(247, 146, 30, 0.1);
            padding: 10px;
            border-radius: 8px;
        }

        /* Table */
        .table-responsive {
            padding: 0 30px 30px;
        }

        .table {
            margin-top: 20px;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead th {
            background: #f8f9fa;
            color: #7f8c8d;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
            border: none;
            padding: 15px;
            border-bottom: 2px solid #edf2f6;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            color: #2c3e50;
            border-bottom: 1px solid #edf2f6;
            font-size: 14px;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr:hover td {
            background-color: #fcfdfe;
        }

        .pill {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            background: #e1f5fe;
            color: #0288d1;
            display: inline-block;
        }

        .pill.economy {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .pill.business {
            background: #fff3e0;
            color: #ef6c00;
        }

        .pill.transfer {
            background: #f3e5f5;
            color: #7b1fa2;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3 style="color:white; font-weight:700; margin:0;"><span style="color:#F7921E;">T</span>ravolo</h3>
            <span style="font-size: 12px; color: #7f8c8d;">Admin Panel</span>
        </div>

        <ul class="sidebar-menu">
            <li><a href="#" class="nav-link active" data-target="flights"><i class="fas fa-plane-departure"></i> Flight
                    Bookings</a></li>
            <li><a href="#" class="nav-link" data-target="cabs"><i class="fas fa-car-side"></i> Cab Bookings</a></li>
            <li><a href="#" class="nav-link" data-target="hotels"><i class="fas fa-hotel"></i> Hotel Bookings</a></li>
            <li><a href="#" class="nav-link" data-target="manage-hotels"><i class="fas fa-building"></i> Manage Hotels</a></li>
            <li><a href="#" class="nav-link" data-target="manage-offers"><i class="fas fa-tags"></i> Manage Offers</a></li>
            <li><a href="#" class="nav-link" data-target="flight-searches"><i class="fas fa-search-location"></i> Flight Searches</a></li>
            <li><a href="#" class="nav-link" data-target="contacts"><i class="fas fa-envelope-open-text"></i>
                    Messages</a></li>
            <li><a href="index.php" target="_blank"><i class="fas fa-external-link-alt"></i> View Website</a></li>
        </ul>

        <div class="logout-wrapper">
            <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <!-- Additional Styles for Premium Feel -->
    <style>
        .calendar-container {
            background: #fff9f2;
            border: 1px solid #ffe8cc;
            border-radius: 10px;
            padding: 15px;
            margin-top: 10px;
            transition: all 0.3s ease;
        }

        .calendar-container:hover {
            box-shadow: 0 4px 12px rgba(247, 146, 30, 0.1);
            transform: translateY(-2px);
        }

        .flatpickr-calendar {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
            border: none !important;
        }

        .selected-dates-display {
            font-size: 11px;
            color: #4b5563;
            background: #ffffff;
            border: 1px dashed #cbd5e1;
            padding: 8px;
            border-radius: 6px;
            margin: 10px 0;
            max-height: 60px;
            overflow-y: auto;
            line-height: 1.5;
        }

        .date-badge {
            display: inline-block;
            background: #F7921E;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            margin: 2px;
            font-size: 10px;
        }
    </style>

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h2 id="page-title">Flight Bookings</h2>
            <div class="user-info">
                <i class="fas fa-user-circle"></i>
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</span>
            </div>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show mx-4 mt-2 mb-0" role="alert" id="success-alert">
                <i class="fas fa-check-circle me-2"></i> <?php echo htmlspecialchars($_GET['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Flights Card -->
        <div class="data-card active" id="flights-card">
            <div class="card-header">
                <h4><i class="fas fa-plane"></i> Recent Flight Bookings</h4>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Trip</th>
                            <th>Route</th>
                            <th>Dates</th>
                            <th>Passengers</th>
                            <th>Class</th>
                            <th>Phone</th>
                            <th>Requested</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
$res = $conn->query("SELECT * FROM flights ORDER BY id DESC LIMIT 50");
while ($row = $res->fetch_assoc()) {
    $trip_class = ($row['trip_type'] == 'OneWay') ? 'pill' : 'pill transfer';
    $cab_class = strtolower($row['travel_class']) == 'business' ? 'pill business' : 'pill economy';
    echo "<tr>";
    echo "<td><span class='{$trip_class}'>{$row['trip_type']}</span></td>";
    echo "<td><strong>{$row['from_city']}</strong><br>to <strong>{$row['to_city']}</strong></td>";
    echo "<td>D: {$row['depart_date']}<br>R: {$row['return_date']}</td>";
    echo "<td>{$row['adults']}A, {$row['children']}C, {$row['infants']}I</td>";
    echo "<td><span class='{$cab_class}'>{$row['travel_class']}</span></td>";
    echo "<td><strong>{$row['phone']}</strong></td>";
    echo "<td><span style='color:#95a5a6; font-size:12px;'>" . date('M j, Y g:i A', strtotime($row['booking_date'])) . "</span></td>";
    echo "</tr>";
}
?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Flight Searches Card -->
        <div class="data-card" id="flight-searches-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-search-location"></i> Flight Search Logs</h4>
                <a href="admin.php?action=clear_searches" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete ALL search history?')">
                    <i class="fas fa-trash-sweep"></i> Clear All History
                </a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Route</th>
                            <th>Date / Type</th>
                            <th>Passengers / Class</th>
                            <th>Searched At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $res = $conn->query("SELECT * FROM flight_searches ORDER BY id DESC LIMIT 100");
                        if ($res && $res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td><strong>{$row['from_city']}</strong><br>to <strong>{$row['to_city']}</strong></td>";
                                echo "<td>{$row['depart_date']}<br><span class='badge bg-light text-dark border'>{$row['trip_type']}</span></td>";
                                echo "<td>{$row['adults']}A, {$row['children']}C, {$row['infants']}I<br><span class='text-muted' style='font-size:11px;'>{$row['travel_class']}</span></td>";
                                echo "<td><span style='color:#95a5a6; font-size:12px;'>" . date('M j, Y g:i A', strtotime($row['search_time'])) . "</span></td>";
                                echo "<td>
                                        <a href='admin.php?action=delete_search&id={$row['id']}' class='btn btn-outline-danger btn-sm' onclick=\"return confirm('Delete this log entry?')\">
                                            <i class='fas fa-trash-alt'></i>
                                        </a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center py-4 text-muted'>No search records found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Cabs Card -->
        <div class="data-card" id="cabs-card">
            <div class="card-header">
                <h4><i class="fas fa-car"></i> Recent Cab Bookings</h4>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Route/Details</th>
                            <th>Pickup Time</th>
                            <th>Return/Hours</th>
                            <th>Phone</th>
                            <th>Requested</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
$res = $conn->query("SELECT * FROM cabs ORDER BY id DESC LIMIT 50");
while ($row = $res->fetch_assoc()) {
    echo "<tr>";
    echo "<td><span class='pill'>{$row['trip_type']}</span><br><span style='font-size:12px; color:#7f8c8d;'>{$row['pickup_type']}</span></td>";
    echo "<td><strong>{$row['from_city']}</strong><br>to <strong>{$row['to_city']}</strong></td>";
    echo "<td>{$row['pickup_date']}<br>{$row['pickup_time']}</td>";
    echo "<td>{$row['return_date']} {$row['return_time']}<br>{$row['hours']}</td>";
    echo "<td><strong>{$row['phone']}</strong></td>";
    echo "<td><span style='color:#95a5a6; font-size:12px;'>" . date('M j, Y g:i A', strtotime($row['booking_date'])) . "</span></td>";
    echo "</tr>";
}
?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Hotels Card -->
        <div class="data-card" id="hotels-card">
            <div class="card-header">
                <h4><i class="fas fa-hotel"></i> Hotel Interest & Bookings</h4>
            </div>

            <div class="px-4 pt-3">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-checks-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-checks" type="button" role="tab"
                            style="font-size: 13px; padding: 8px 20px;">Availability Checks</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-queries-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-queries" type="button" role="tab"
                            style="font-size: 13px; padding: 8px 20px;">Booking Queries</button>
                    </li>
                </ul>
            </div>

            <div class="tab-content px-1" id="pills-tabContent">
                <!-- Checks Tab -->
                <div class="tab-pane fade show active" id="pills-checks" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Check-in</th>
                                    <th>Hotel Requested</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
$res = $conn->query("SELECT * FROM hotels WHERE booking_type = 'Check' ORDER BY id DESC LIMIT 50");
while ($row = $res->fetch_assoc()) {
    echo "<tr>";
    echo "<td><strong>{$row['check_in']}</strong></td>";
    echo "<td>{$row['hotel_search']}</td>";
    echo "<td>{$row['phone']}</td>";
    echo "<td><span class='badge bg-light text-dark border'>{$row['status']}</span></td>";
    echo "<td><span style='color:#95a5a6; font-size:12px;'>" . date('M j, g:i A', strtotime($row['booking_date'])) . "</span></td>";
    echo "</tr>";
}
?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Queries Tab -->
                <div class="tab-pane fade" id="pills-queries" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Contact Info</th>
                                    <th>Hotel / Date</th>
                                    <th>Requested On</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
$res = $conn->query("SELECT * FROM hotels WHERE booking_type = 'Booking' ORDER BY id DESC LIMIT 50");
while ($row = $res->fetch_assoc()) {
    echo "<tr>";
    echo "<td><strong>{$row['user_name']}</strong></td>";
    echo "<td>{$row['email']}<br>{$row['phone']}</td>";
    echo "<td><strong>{$row['hotel_search']}</strong><br>{$row['check_in']}</td>";
    echo "<td><span style='color:#95a5a6; font-size:12px;'>" . date('M j, g:i A', strtotime($row['booking_date'])) . "</span></td>";
    echo "</tr>";
}
?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contacts Card -->
        <div class="data-card" id="contacts-card">
            <div class="card-header">
                <h4><i class="fas fa-envelope"></i> Contact Messages</h4>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>User Details</th>
                            <th>Contact Info</th>
                            <th>Message</th>
                            <th>Received</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
$res = $conn->query("SELECT * FROM contact_messages ORDER BY id DESC LIMIT 50");
while ($row = $res->fetch_assoc()) {
    echo "<tr>";
    echo "<td><strong>{$row['name']}</strong></td>";
    echo "<td><a href='mailto:{$row['email']}'>{$row['email']}</a><br>{$row['phone']}</td>";
    echo "<td style='max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;' title='" . htmlspecialchars($row['message']) . "'>{$row['message']}</td>";
    echo "<td><span style='color:#95a5a6; font-size:12px;'>" . date('M j, Y g:i A', strtotime($row['date_sent'])) . "</span></td>";
    echo "</tr>";
}
?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Manage Hotels Card -->
        <div class="data-card" id="manage-hotels-card">
            <div class="card-header">
                <h4><i class="fas fa-building"></i> Manage Hotels Data</h4>
            </div>
            <div class="table-responsive" style="padding: 30px;">
                <h5>Add New Hotel</h5>
                <form action="admin.php" method="POST" enctype="multipart/form-data" class="mb-5 row g-3">
                    <input type="hidden" name="action" value="add_hotel">
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Hotel Name</label>
                        <input type="text" class="form-control" name="name" placeholder="Hotel Name" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="location" placeholder="Location City" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control" name="price" placeholder="Price (INR)" required>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="accommodations" required>
                            <option value="">Accommodation</option>
                            <option value="Classic Tent">Classic Tent</option>
                            <option value="Forest Camping">Forest Camping</option>
                            <option value="Small Trailer">Small Trailer</option>
                            <option value="Tree House Tent">Tree House Tent</option>
                            <option value="Tent Camping">Tent Camping</option>
                            <option value="Couple Tent">Couple Tent</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small text-muted">Hotel Description</label>
                        <textarea class="form-control" name="description" placeholder="Description" rows="1"
                            required></textarea>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small text-muted">Upload Hotel Image</label>
                        <input type="file" class="form-control" name="hotel_image" accept="image/*" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100"
                            style="background-color: #F7921E; border:none; padding:10px;">Add Hotel</button>
                    </div>
                </form>

                <hr>

                <h5 class="mt-4">Hotel Catalog</h5>
                <table class="table mt-3 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Preview</th>
                            <th>Hotel Details</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
$res = $conn->query("SELECT * FROM app_hotels ORDER BY id DESC");
if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        $avail_btn_class = $row['availability'] ? 'btn-success' : 'btn-secondary';
        $avail_text = $row['availability'] ? 'Active' : 'Inactive';
        $toggle_title = $row['availability'] ? 'Deactivate' : 'Activate';

        echo "<tr>";
        echo "<td><img src='{$row['image']}' alt='hotel' style='border-radius:8px; width:70px; height:50px; object-fit:cover;'></td>";
        echo "<td>
                <div style='font-weight:600; font-size:15px;'>{$row['name']}</div>
                <div style='font-size:12px; color:#7f8c8d;'><i class='fas fa-map-marker-alt me-1'></i>{$row['location']} | ₹{$row['price']}</div>
              </td>";
        echo "<td>
                <form action='admin.php' method='POST' style='display:inline;'>
                    <input type='hidden' name='action' value='toggle_hotel'>
                    <input type='hidden' name='hotel_id' value='{$row['id']}'>
                    <input type='hidden' name='current_status' value='{$row['availability']}'>
                    <button type='submit' class='btn {$avail_btn_class} btn-sm' style='font-size: 11px; padding: 2px 10px; border-radius: 20px;' title='{$toggle_title}'>
                        {$avail_text}
                    </button>
                </form>
              </td>";
        echo "<td>
                <div class='btn-group'>
                    <a href='hotel-edit.php?id={$row['id']}' class='btn btn-outline-primary btn-sm' title='Edit'>
                        <i class='fas fa-edit'></i> Edit
                    </a>
                    <a href='admin.php?action=delete_hotel&id={$row['id']}' class='btn btn-outline-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this hotel?')\" title='Delete'>
                        <i class='fas fa-trash-alt'></i> Delete
                    </a>
                </div>
              </td>";
        echo "</tr>";
    }
}
else {
    echo "<tr><td colspan='4' class='text-center py-4 text-muted'>No hotels found in database.</td></tr>";
}
?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Manage Offers Card -->
        <div class="data-card" id="manage-offers-card">
            <div class="card-header">
                <h4><i class="fas fa-tags"></i> Manage Exclusive Offers</h4>
            </div>
            <div class="table-responsive" style="padding: 30px;">
                <h5>Add New Offer</h5>
                <form action="admin.php" method="POST" enctype="multipart/form-data" class="mb-5 row g-3">
                    <input type="hidden" name="action" value="add_offer">
                    <div class="col-md-2">
                        <label class="form-label small text-muted">Badge Code</label>
                        <input type="text" class="form-control" name="badge_text" placeholder="e.g. FLAT25" required>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="badge_color" required>
                            <option value="primary">Blue</option>
                            <option value="danger">Red</option>
                            <option value="success">Green</option>
                            <option value="warning">Yellow</option>
                            <option value="dark">Black</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="title" placeholder="Title (Up to 25%)" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Description (Flights)</label>
                        <input type="text" class="form-control" name="description" placeholder="on Domestic Flights" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Upload Offer Image</label>
                        <input type="file" class="form-control" name="offer_image" accept="image/*" required>
                    </div>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="footer_text" placeholder="Footer text (EMI Valid)" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100" style="background-color: #F7921E; border:none;">Add Offer Card</button>
                    </div>
                </form>

                <hr>

                <h5 class="mt-4">Active Offers</h5>
                <table class="table mt-3 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Theme</th>
                            <th>Details</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $res = $conn->query("SELECT * FROM app_offers ORDER BY id DESC");
                        if ($res && $res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $colorCode = [
                                    'primary' => '#0d6efd',
                                    'danger'  => '#dc3545',
                                    'success' => '#198754',
                                    'warning' => '#ffc107',
                                    'dark'    => '#212529'
                                ][$row['badge_color']] ?? '#000';

                                echo "<tr>";
                                echo "<td><img src='{$row['image_url']}' style='border-radius:8px; width:70px; height:50px; object-fit:cover;'></td>";
                                echo "<td>
                                        <div style='font-weight:600; font-size:15px;'>{$row['title']}</div>
                                        <div style='font-size:12px; color:#7f8c8d;'><span style='color: white; background: {$colorCode}; padding: 2px 6px; border-radius: 4px;'>{$row['badge_text']}</span> • {$row['description']}</div>
                                      </td>";
                                 $status_btn_class = $row['status'] == 1 ? 'btn-success' : 'btn-secondary';
                                 $status_text = $row['status'] == 1 ? 'Active' : 'Inactive';
                                 $toggle_title = $row['status'] == 1 ? 'Deactivate' : 'Activate';

                                 echo "<td>
                                         <form action='admin.php' method='POST' style='display:inline;'>
                                             <input type='hidden' name='action' value='toggle_offer'>
                                             <input type='hidden' name='offer_id' value='{$row['id']}'>
                                             <input type='hidden' name='current_status' value='{$row['status']}'>
                                             <button type='submit' class='btn {$status_btn_class} btn-sm' style='font-size: 11px; padding: 2px 10px; border-radius: 20px;' title='{$toggle_title}'>
                                                 {$status_text}
                                             </button>
                                         </form>
                                       </td>";
                                 echo "<td>
                                         <div class='btn-group'>
                                             <button type='button' class='btn btn-outline-primary btn-sm' data-bs-toggle='modal' data-bs-target='#editOfferModal{$row['id']}' title='Edit'>
                                                 <i class='fas fa-edit'></i> Edit
                                             </button>
                                             <a href='admin.php?action=delete_offer&id={$row['id']}' class='btn btn-outline-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this offer?\");' title='Delete'>
                                                 <i class='fas fa-trash-alt'></i> Delete
                                             </a>
                                         </div>
                                       </td>";
                                echo "</tr>";

                                // Setup modal for editing this particular offer
                                $offer_modals_html .= "
                                <div class='modal fade' id='editOfferModal{$row['id']}' tabindex='-1' aria-hidden='true'>
                                  <div class='modal-dialog modal-lg modal-dialog-centered'>
                                    <div class='modal-content'>
                                      <div class='modal-header'>
                                        <h5 class='modal-title'><i class='fas fa-edit me-2 text-primary'></i>Edit Exclusive Offer</h5>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                      </div>
                                      <div class='modal-body'>
                                        <form action='admin.php' method='POST' enctype='multipart/form-data' class='row g-3'>
                                            <input type='hidden' name='action' value='edit_offer'>
                                            <input type='hidden' name='offer_id' value='{$row['id']}'>
                                            <input type='hidden' name='existing_image' value='{$row['image_url']}'>
                                            <div class='col-md-4'>
                                                <label class='form-label small text-muted'>Badge Code</label>
                                                <input type='text' class='form-control' name='badge_text' value='" . htmlspecialchars($row['badge_text'], ENT_QUOTES) . "' required>
                                            </div>
                                            <div class='col-md-3'>
                                                <label class='form-label small text-muted'>Badge Color</label>
                                                <select class='form-select' name='badge_color' required>
                                                    <option value='primary' " . ($row['badge_color']=='primary'?'selected':'') . ">Blue</option>
                                                    <option value='danger' " . ($row['badge_color']=='danger'?'selected':'') . ">Red</option>
                                                    <option value='success' " . ($row['badge_color']=='success'?'selected':'') . ">Green</option>
                                                    <option value='warning' " . ($row['badge_color']=='warning'?'selected':'') . ">Yellow</option>
                                                    <option value='dark' " . ($row['badge_color']=='dark'?'selected':'') . ">Black</option>
                                                </select>
                                            </div>
                                            <div class='col-md-5'>
                                                <label class='form-label small text-muted'>Title</label>
                                                <input type='text' class='form-control' name='title' value='" . htmlspecialchars($row['title'], ENT_QUOTES) . "' required>
                                            </div>
                                            <div class='col-md-6'>
                                                <label class='form-label small text-muted'>Description</label>
                                                <input type='text' class='form-control' name='description' value='" . htmlspecialchars($row['description'], ENT_QUOTES) . "' required>
                                            </div>
                                            <div class='col-md-6'>
                                                <label class='form-label small text-muted'>Offer Image (Upload new to change)</label>
                                                <input type='file' class='form-control' name='offer_image' accept='image/*'>
                                                <div class='mt-1 small text-muted'>Current: " . basename($row['image_url']) . "</div>
                                            </div>
                                            <div class='col-md-12'>
                                                <label class='form-label small text-muted'>Footer Text</label>
                                                <input type='text' class='form-control' name='footer_text' value='" . htmlspecialchars($row['footer_text'], ENT_QUOTES) . "' required>
                                            </div>
                                            <div class='col-12 text-end mt-4'>
                                                <button type='button' class='btn btn-secondary me-2' data-bs-dismiss='modal'>Cancel</button>
                                                <button type='submit' class='btn btn-primary px-4' style='background-color: #F7921E; border:none;'>Update Offer</button>
                                            </div>
                                        </form>
                                      </div>
                                    </div>
                                  </div>
                                </div>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center py-4 text-muted'>No Offers Found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <?php echo $offer_modals_html; ?>

    <!-- JS for Tabs -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Initialize Flatpickr for multiple date selection
        document.addEventListener('DOMContentLoaded', function () {
            flatpickr(".flatpickr-multiple", {
                mode: "multiple",
                dateFormat: "Y-m-d",
                onChange: function (selectedDates, dateStr, instance) {
                    // Get the corresponding display div
                    const hotelId = instance.element.closest('form').querySelector('input[name="hotel_id"]').value;
                    const displayDiv = document.getElementById('display-' + hotelId);

                    if (dateStr) {
                        const datesArray = dateStr.split(', ');
                        displayDiv.innerHTML = datesArray.map(d => `<span class='date-badge'>${d}</span>`).join('');
                    } else {
                        displayDiv.innerHTML = '<span class="text-muted">No dates selected</span>';
                    }
                }
            });
        });

        const navLinks = document.querySelectorAll('.nav-link');
        const dataCards = document.querySelectorAll('.data-card');
        const pageTitle = document.getElementById('page-title');

        function switchTab(target) {
            const link = document.querySelector(`.nav-link[data-target="${target}"]`);
            if (link) {
                // Update active link
                navLinks.forEach(l => l.classList.remove('active'));
                link.classList.add('active');

                // Update Page Title
                pageTitle.textContent = link.textContent.trim();

                // Hide all cards, show target
                const targetId = target + '-card';
                dataCards.forEach(card => card.classList.remove('active'));
                const targetCard = document.getElementById(targetId);
                if (targetCard) targetCard.classList.add('active');
                
                // Save to localStorage
                localStorage.setItem('activeAdminTab', target);
            }
        }

        navLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const target = this.getAttribute('data-target');
                switchTab(target);
            });
        });

        // On page load, restore tab from localStorage immediately
        const savedTab = localStorage.getItem('activeAdminTab');
        if (savedTab) {
            switchTab(savedTab);
        }

        // Clean URL after success message displayed to prevent reappear on refresh
        if (window.history.replaceState && window.location.search.includes('success=')) {
            const url = new URL(window.location);
            url.searchParams.delete('success');
            window.history.replaceState({}, '', url);
        }
    </script>
</body>

</html>
