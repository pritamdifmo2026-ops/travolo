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

    $sql = "INSERT INTO app_hotels (name, location, price, accommodations, image, description) VALUES ('$name', '$loc', '$price', '$accom', '$image', '$desc')";
    if ($conn->query($sql) === TRUE) {
        $hotel_id = $conn->insert_id;
        
        // Handle Gallery Uploads
        if (isset($_FILES['hotel_gallery']) && !empty($_FILES['hotel_gallery']['name'][0])) {
            $galleryInputs = $_FILES['hotel_gallery'];
            for ($i = 0; $i < count($galleryInputs['name']); $i++) {
                if ($galleryInputs['error'][$i] === UPLOAD_ERR_OK) {
                    $tmpName = $galleryInputs['tmp_name'][$i];
                    $fileName = basename($galleryInputs["name"][$i]);
                    $targetPath = "assets/images/" . time() . "_" . $i . "_" . $fileName;
                    if (move_uploaded_file($tmpName, $targetPath)) {
                        $conn->query("INSERT INTO hotel_images (hotel_id, image_path) VALUES ($hotel_id, '$targetPath')");
                    }
                }
            }
        }
        header("Location: admin.php?success=Hotel+Added+Successfully");
    } else {
        header("Location: admin.php?error=" . urlencode($conn->error));
    }
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

// Add New Route Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_route') {
    $city = $conn->real_escape_string($_POST['city_name']);
    $via = $conn->real_escape_string($_POST['via_cities']);
    $from = $conn->real_escape_string($_POST['from_query']);
    $to = $conn->real_escape_string($_POST['to_query']);
    
    $image = handleFileUpload('route_image', 'assets/images/cities/') ?: 'assets/images/destinations/default.png';

    $conn->query("INSERT INTO top_flight_routes (city_name, via_cities, image_path, from_query, to_query) VALUES ('$city', '$via', '$image', '$from', '$to')");
    header("Location: admin.php?success=Flight+Route+Added");
    exit;
}

// Edit Route Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_route') {
    $id = (int)$_POST['route_id'];
    $city = $conn->real_escape_string($_POST['city_name']);
    $via = $conn->real_escape_string($_POST['via_cities']);
    $from = $conn->real_escape_string($_POST['from_query']);
    $to = $conn->real_escape_string($_POST['to_query']);
    $image = $_POST['existing_image'];

    $new_image = handleFileUpload('route_image', 'assets/images/cities/');
    if ($new_image) $image = $new_image;

    $conn->query("UPDATE top_flight_routes SET city_name='$city', via_cities='$via', image_path='$image', from_query='$from', to_query='$to' WHERE id=$id");
    header("Location: admin.php?success=Flight+Route+Updated");
    exit;
}

// Toggle Route Status
if (isset($_GET['action']) && $_GET['action'] === 'toggle_route' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $conn->query("UPDATE top_flight_routes SET status = !status WHERE id=$id");
    header("Location: admin.php?success=Route+Status+Updated");
    exit;
}

// Delete Route Logic
if (isset($_GET['action']) && $_GET['action'] === 'delete_route' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $conn->query("DELETE FROM top_flight_routes WHERE id=$id");
    header("Location: admin.php?success=Route+Deleted");
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

// Update Booking Status Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_booking_status') {
    $booking_id = (int)$_POST['booking_id'];
    $new_status = $conn->real_escape_string($_POST['booking_status']);
    
    $sql = "UPDATE hotels SET booking_status='$new_status' WHERE id=$booking_id";
    if ($conn->query($sql)) {
        header("Location: admin.php?success=Booking+Status+Updated+to+$new_status");
    } else {
        header("Location: admin.php?error=Failed+to+update+status");
    }
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
            overflow: hidden;
        }

        .sidebar-header {
            padding: 30px 25px;
            background-color: #121418;
            text-align: center;
            border-bottom: 1px solid #2a2c33;
            flex-shrink: 0;
        }

        .sidebar-content {
            flex-grow: 1;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #F7921E transparent;
        }

        .sidebar-content::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-content::-webkit-scrollbar-thumb {
            background-color: #F7921E;
            border-radius: 10px;
        }

        .sidebar-content::-webkit-scrollbar-track {
            background: transparent;
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
            <a href="index.php" target="_blank">
                <img src="assets/images/logo1.png" alt="Logo">
            </a>
            <div style="font-size: 11px; color: #7f8c8d; font-weight: 500; letter-spacing: 1px; text-transform: uppercase; margin-top: 5px;">Admin Shield</div>
        </div>

        <div class="sidebar-content">
            <ul class="sidebar-menu">
                <li><a href="#" class="nav-link active" data-target="flights"><i class="fas fa-plane-departure"></i> Flight
                        Bookings</a></li>
                <li><a href="#" class="nav-link" data-target="cabs"><i class="fas fa-car-side"></i> Cab Bookings</a></li>
                <li><a href="#" class="nav-link" data-target="hotels"><i class="fas fa-hotel"></i> Hotel Bookings</a></li>
                <li><a href="#" class="nav-link" data-target="manage-hotels"><i class="fas fa-building"></i> Manage Hotels</a></li>
                <li><a href="#" class="nav-link" data-target="manage-offers"><i class="fas fa-tags"></i> Manage Offers</a></li>
                <li><a href="#" class="nav-link" data-target="manage-routes"><i class="fas fa-route"></i> Manage Routes</a></li>
                <li><a href="#" class="nav-link" data-target="flight-searches"><i class="fas fa-search-location"></i> Flight Searches</a></li>
                <li><a href="#" class="nav-link" data-target="contacts"><i class="fas fa-envelope-open-text"></i>
                        Messages</a></li>
                <li><a href="index.php" target="_blank"><i class="fas fa-external-link-alt"></i> View Website</a></li>
            </ul>

            <div class="logout-wrapper">
                <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
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
                            <th>Mobile</th>
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
                                echo "<td><strong class='text-primary'>{$row['mobile']}</strong></td>";
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
                            echo "<tr><td colspan='6' class='text-center py-4 text-muted'>No search records found.</td></tr>";
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
                        <table class="table align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>Customer</th>
                                    <th>Hotel / Room</th>
                                    <th>Dates (In/Out)</th>
                                    <th>Guests</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th class="text-end">Requested</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
$res = $conn->query("SELECT * FROM hotels WHERE booking_type = 'Booking' ORDER BY id DESC LIMIT 50");
while ($row = $res->fetch_assoc()) {
    $pay_badge = ($row['payment_status'] == 'Paid') ? 'bg-success' : 'bg-warning text-dark';
    $status_options = ['Requested', 'Pending', 'Confirmed', 'Cancelled', 'Completed'];
    $status_styles = [
        'Requested' => 'background: #f0f7ff; color: #007bff; border-color: #cce5ff;',
        'Pending'   => 'background: #fff9ed; color: #856404; border-color: #ffeeba;',
        'Confirmed' => 'background: #f0fff4; color: #28a745; border-color: #c3e6cb;',
        'Cancelled' => 'background: #fff5f5; color: #dc3545; border-color: #f5c6cb;',
        'Completed' => 'background: #f8f9fa; color: #6c757d; border-color: #d6d8db;'
    ];
    $current_style = $status_styles[$row['booking_status']] ?? $status_styles['Requested'];
    
    echo "<tr>";
    echo "<td>
            <div class='fw-bold'>{$row['user_name']}</div>
            <div class='small text-muted'>{$row['phone']}</div>
            <div class='small text-muted'>{$row['email']}</div>
          </td>";
    echo "<td>
            <div class='text-primary fw-bold'>{$row['hotel_search']}</div>
            <div class='badge bg-light text-dark border-0' style='font-weight: 500; font-size: 11px;'>{$row['room_type']}</div>
          </td>";
    echo "<td>
            <div class='small'><i class='fas fa-sign-in-alt text-success me-1'></i>{$row['check_in']}</div>
            <div class='small'><i class='fas fa-sign-out-alt text-danger me-1'></i>{$row['check_out']}</div>
          </td>";
    echo "<td><span class='badge' style='background: #eef2f7; color: #4b5563; font-weight: 500;'>{$row['guests']}</span></td>";
    echo "<td><div class='fw-bold text-dark'>₹" . number_format($row['price']) . "</div><span class='badge' style='background: #fffcf0; color: #9c8e1d; font-size: 10px; border: 1px solid #fffae6;'>{$row['payment_status']}</span></td>";
    echo "<td>
            <form method='POST' style='width: 130px;'>
                <input type='hidden' name='action' value='update_booking_status'>
                <input type='hidden' name='booking_id' value='{$row['id']}'>
                <select name='booking_status' class='form-select form-select-sm fw-bold border shadow-sm' onchange='this.form.submit()' style='font-size: 11px; {$current_style}'>";
                foreach($status_options as $opt) {
                    $sel = ($row['booking_status'] == $opt) ? 'selected' : '';
                    echo "<option value='$opt' $sel>$opt</option>";
                }
    echo "      </select>
            </form>
          </td>";
    echo "<td class='text-end'><span style='color:#95a5a6; font-size:12px;'>" . date('M j, g:i A', strtotime($row['booking_date'])) . "</span></td>";
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
            
            <div class="p-4">
                <!-- Add New Hotel Card -->
                <div class="card border-0 bg-light rounded-4 mb-5 shadow-sm p-4">
                    <h5 class="fw-bold mb-4 text-dark"><i class="fas fa-plus-circle me-2 text-warning"></i>Add New Hotel</h5>
                    <form action="admin.php" method="POST" enctype="multipart/form-data" class="row g-4">
                        <input type="hidden" name="action" value="add_hotel">
                        
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted"><i class="fas fa-hotel me-1"></i>Hotel Name</label>
                            <input type="text" class="form-control border-white shadow-none py-2 px-3 rounded-pill bg-white" name="name" placeholder="E.g. Grand Plaza" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted"><i class="fas fa-map-marker-alt me-1"></i>Location City</label>
                            <input type="text" class="form-control border-white shadow-none py-2 px-3 rounded-pill bg-white" name="location" placeholder="E.g. Delhi" required>
                        </div>
                        
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-muted"><i class="fas fa-rupee-sign me-1"></i>Price (INR)</label>
                            <input type="number" class="form-control border-white shadow-none py-2 px-3 rounded-pill bg-white" name="price" placeholder="Price/Night" required>
                        </div>
                        
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-muted"><i class="fas fa-campground me-1"></i>Accommodation</label>
                            <select class="form-select border-white shadow-none py-2 px-3 rounded-pill bg-white" name="accommodations" required>
                                <option value="">Select...</option>
                                <option value="Classic Tent">Classic Tent</option>
                                <option value="Forest Camping">Forest Camping</option>
                                <option value="Small Trailer">Small Trailer</option>
                                <option value="Tree House Tent">Tree House Tent</option>
                                <option value="Tent Camping">Tent Camping</option>
                                <option value="Couple Tent">Couple Tent</option>
                                <option value="Luxury Hotel">Luxury Hotel</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted"><i class="fas fa-info-circle me-1"></i>Hotel Description</label>
                            <textarea class="form-control border-white shadow-none px-3 rounded-4 bg-white" name="description" placeholder="Write hotel summary here..." rows="2" required></textarea>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted"><i class="fas fa-image me-1"></i>Main Image</label>
                            <input type="file" class="form-control border-white shadow-none px-3 rounded-pill bg-white" name="hotel_image" accept="image/*" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted"><i class="fas fa-images me-1"></i>Gallery (Multiple)</label>
                            <input type="file" class="form-control border-white shadow-none px-3 rounded-pill bg-white" name="hotel_gallery[]" accept="image/*" multiple>
                        </div>
                        
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold text-white py-2 shadow-sm" style="background: linear-gradient(135deg, #F7921E, #ff9b1a); border:none;">
                                <i class="fas fa-plus me-1"></i>Add Hotel
                            </button>
                        </div>
                    </form>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-th-list me-2 text-primary"></i>Hotel Catalog</h5>
                </div>
                
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="bg-light border-0">
                            <tr>
                                <th class="border-0 rounded-start">Preview</th>
                                <th class="border-0">Hotel Details</th>
                                <th class="border-0">Accommodation</th>
                                <th class="border-0">Status</th>
                                <th class="border-0 rounded-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $res = $conn->query("SELECT * FROM app_hotels ORDER BY id DESC");
                            if ($res && $res->num_rows > 0) {
                                while ($row = $res->fetch_assoc()) {
                                    $avail_status_class = $row['availability'] ? 'bg-success' : 'bg-secondary';
                                    $avail_text = $row['availability'] ? 'Working' : 'Locked';
                                    $avail_btn_class = $row['availability'] ? 'btn-outline-danger' : 'btn-outline-success';
                                    $avail_btn_text = $row['availability'] ? '<i class="fas fa-lock me-1"></i>Lock' : '<i class="fas fa-unlock me-1"></i>Active';
                            
                                    echo "<tr>";
                                    echo "<td><div class='position-relative'><img src='{$row['image']}' alt='hotel' class='rounded-3' style='width:90px; height:60px; object-fit:cover; border:2px solid #fff; box-shadow:0 3px 6px rgba(0,0,0,0.1);'></div></td>";
                                    echo "<td>
                                            <div class='fw-bold text-dark' style='font-size:15px;'>{$row['name']}</div>
                                            <div class='small text-muted'><i class='fas fa-map-marker-alt me-1'></i>{$row['location']}</div>
                                            <div class='fw-bold text-success' style='font-size:13px;'>₹{$row['price']} <span class='text-muted fw-normal' style='font-size:11px;'>/ night</span></div>
                                          </td>";
                                    echo "<td><span class='badge bg-light text-primary border border-primary-subtle' style='font-size:12px; font-weight:500;'>{$row['accommodations']}</span></td>";
                                    echo "<td><span class='badge {$avail_status_class}' style='font-size: 11px; padding: 5px 12px; border-radius: 20px; font-weight:500;'>{$avail_text}</span></td>";
                                    echo "<td>
                                            <div class='btn-group'>
                                                <form action='admin.php' method='POST' style='display:inline;'>
                                                    <input type='hidden' name='action' value='toggle_hotel'>
                                                    <input type='hidden' name='hotel_id' value='{$row['id']}'>
                                                    <input type='hidden' name='current_status' value='{$row['availability']}'>
                                                    <button type='submit' class='btn btn-sm {$avail_btn_class} rounded-pill px-3 me-2' style='font-size: 12px;'>
                                                        {$avail_btn_text}
                                                    </button>
                                                </form>
                                                <a href='hotel-edit.php?id={$row['id']}' class='btn btn-sm btn-outline-primary rounded-pill px-3 me-2' style='font-size: 12px;'>
                                                    <i class='fas fa-edit'></i> Edit
                                                </a>
                                                <a href='admin.php?action=delete_hotel&id={$row['id']}' class='btn btn-sm btn-outline-danger rounded-pill px-3' style='font-size: 12px;' onclick=\"return confirm('Confirm deletion of this hotel?')\">
                                                    <i class='fas fa-trash-alt'></i>
                                                </a>
                                            </div>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center py-5 text-muted'>
                                        <i class='fas fa-hotel fa-3x mb-3' style='opacity:0.2;'></i><br>
                                        No hotels found in catalog
                                      </td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Manage Offers Card -->
        <div class="data-card" id="manage-offers-card">
            <div class="card-header">
                <h4><i class="fas fa-tags"></i> Manage Exclusive Offers</h4>
            </div>
            <div class="p-4">
                <!-- Add New Offer Card -->
                <div class="card border-0 bg-light rounded-4 mb-5 shadow-sm p-4">
                    <h5 class="fw-bold mb-4 text-dark"><i class="fas fa-plus-circle me-2 text-warning"></i>Add New Exclusive Offer</h5>
                    <form action="admin.php" method="POST" enctype="multipart/form-data" class="row g-4">
                        <input type="hidden" name="action" value="add_offer">
                        
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted"><i class="fas fa-ticket-alt me-1"></i>Badge Code</label>
                            <input type="text" class="form-control border-white shadow-none py-2 px-3 rounded-pill bg-white" name="badge_text" placeholder="e.g. FLAT25" required>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted"><i class="fas fa-palette me-1"></i>Badge Color</label>
                            <select class="form-select border-white shadow-none py-2 px-3 rounded-pill bg-white" name="badge_color" required>
                                <option value="primary">Blue Theme</option>
                                <option value="danger">Red Theme</option>
                                <option value="success">Green Theme</option>
                                <option value="warning">Yellow Theme</option>
                                <option value="dark">Black Theme</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted"><i class="fas fa-heading me-1"></i>Offer Title</label>
                            <input type="text" class="form-control border-white shadow-none py-2 px-3 rounded-pill bg-white" name="title" placeholder="E.g. Up to 25% Off" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted"><i class="fas fa-image me-1"></i>Offer Image</label>
                            <input type="file" class="form-control border-white shadow-none px-3 rounded-pill bg-white" name="offer_image" accept="image/*" required>
                        </div>
                        
                        <div class="col-md-5">
                            <label class="form-label small fw-bold text-muted"><i class="fas fa-align-left me-1"></i>Short Description</label>
                            <input type="text" class="form-control border-white shadow-none py-2 px-3 rounded-pill bg-white" name="description" placeholder="E.g. on Domestic Flights" required>
                        </div>
                        
                        <div class="col-md-5">
                            <label class="form-label small fw-bold text-muted"><i class="fas fa-info-circle me-1"></i>Footer Terms/Text</label>
                            <input type="text" class="form-control border-white shadow-none py-2 px-3 rounded-pill bg-white" name="footer_text" placeholder="E.g. EMI Valid on select banks" required>
                        </div>
                        
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold text-white py-2 shadow-sm" style="background: linear-gradient(135deg, #F7921E, #ff9b1a); border:none;">
                                <i class="fas fa-magic me-1"></i>Add Offer
                            </button>
                        </div>
                    </form>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-star me-2 text-primary"></i>Live Offers</h5>
                </div>
                
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="bg-light border-0">
                            <tr>
                                <th class="border-0 rounded-start">Banner</th>
                                <th class="border-0">Offer Details</th>
                                <th class="border-0">Current Status</th>
                                <th class="border-0 rounded-end text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $res = $conn->query("SELECT * FROM app_offers ORDER BY id DESC");
                            if ($res && $res->num_rows > 0) {
                                while ($row = $res->fetch_assoc()) {
                                    $themeColor = [
                                        'primary' => '#0d6efd',
                                        'danger'  => '#dc3545',
                                        'success' => '#198754',
                                        'warning' => '#ffc107',
                                        'dark'    => '#212529'
                                    ][$row['badge_color']] ?? '#000';

                                    $status_badge = $row['status'] == 1 ? 'bg-success' : 'bg-secondary';
                                    $status_text = $row['status'] == 1 ? 'Live' : 'Hidden';
                                    $toggle_btn_class = $row['status'] == 1 ? 'btn-outline-danger' : 'btn-outline-success';
                                    $toggle_btn_text = $row['status'] == 1 ? '<i class="fas fa-eye-slash me-1"></i>Hide' : '<i class="fas fa-eye me-1"></i>Live';

                                    echo "<tr>";
                                    echo "<td><img src='{$row['image_url']}' class='rounded-3 shadow-sm' style='width:90px; height:60px; object-fit:cover; border:2px solid #fff;'></td>";
                                    echo "<td>
                                            <div class='fw-bold text-dark' style='font-size:15px;'>{$row['title']}</div>
                                            <div class='small text-muted mb-1'>{$row['description']}</div>
                                            <div><span class='badge' style='background: {$themeColor}; font-size:10px; padding: 4px 8px; border-radius: 4px;'>{$row['badge_text']}</span> <span class='ms-2 text-muted' style='font-size:11px;'>{$row['footer_text']}</span></div>
                                          </td>";
                                    echo "<td><span class='badge {$status_badge}' style='font-size: 11px; padding: 5px 12px; border-radius: 20px; font-weight:500;'>{$status_text}</span></td>";
                                    echo "<td class='text-end'>
                                            <div class='btn-group shadow-sm rounded-pill p-1 bg-white border'>
                                                <form action='admin.php' method='POST' style='display:inline;'>
                                                    <input type='hidden' name='action' value='toggle_offer'>
                                                    <input type='hidden' name='offer_id' value='{$row['id']}'>
                                                    <input type='hidden' name='current_status' value='{$row['status']}'>
                                                    <button type='submit' class='btn btn-sm btn-link text-decoration-none fw-bold' style='color: " . ($row['status'] == 1 ? '#e74c3c' : '#27ae60') . "; font-size: 12px;'>
                                                        {$toggle_btn_text}
                                                    </button>
                                                </form>
                                                <button type='button' class='btn btn-sm btn-link text-primary text-decoration-none fw-bold mx-2' style='font-size: 12px;' data-bs-toggle='modal' data-bs-target='#editOfferModal{$row['id']}'>
                                                    <i class='fas fa-edit me-1'></i>Edit
                                                </button>
                                                <a href='admin.php?action=delete_offer&id={$row['id']}' class='btn btn-sm btn-link text-danger text-decoration-none fw-bold' style='font-size: 12px;' onclick=\"return confirm('Are you sure you want to delete this offer?');\">
                                                    <i class='fas fa-trash-alt me-1'></i>
                                                </a>
                                            </div>
                                          </td>";
                                    echo "</tr>";

                                    // Setup modal for editing this particular offer
                                    $offer_modals_html .= "
                                    <div class='modal fade' id='editOfferModal{$row['id']}' tabindex='-1' aria-hidden='true'>
                                      <div class='modal-dialog modal-lg modal-dialog-centered'>
                                        <div class='modal-content border-0 shadow-lg rounded-4'>
                                          <div class='modal-header border-0 pb-0'>
                                            <h5 class='modal-title fw-bold'><i class='fas fa-edit me-2 text-primary'></i>Edit Offer</h5>
                                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                          </div>
                                          <div class='modal-body p-4'>
                                            <form action='admin.php' method='POST' enctype='multipart/form-data' class='row g-3'>
                                                <input type='hidden' name='action' value='edit_offer'>
                                                <input type='hidden' name='offer_id' value='{$row['id']}'>
                                                <input type='hidden' name='existing_image' value='{$row['image_url']}'>
                                                <div class='col-md-4'>
                                                    <label class='form-label small text-muted'>Badge Code</label>
                                                    <input type='text' class='form-control rounded-pill' name='badge_text' value='" . htmlspecialchars($row['badge_text'], ENT_QUOTES) . "' required>
                                                </div>
                                                <div class='col-md-4 text-start'>
                                                    <label class='form-label small text-muted'>Badge Theme</label>
                                                    <select class='form-select rounded-pill' name='badge_color' required>
                                                        <option value='primary' " . ($row['badge_color'] == 'primary' ? 'selected' : '') . ">Blue Theme</option>
                                                        <option value='danger' " . ($row['badge_color'] == 'danger' ? 'selected' : '') . ">Red Theme</option>
                                                        <option value='success' " . ($row['badge_color'] == 'success' ? 'selected' : '') . ">Green Theme</option>
                                                        <option value='warning' " . ($row['badge_color'] == 'warning' ? 'selected' : '') . ">Yellow Theme</option>
                                                        <option value='dark' " . ($row['badge_color'] == 'dark' ? 'selected' : '') . ">Black Theme</option>
                                                    </select>
                                                </div>
                                                <div class='col-md-4'>
                                                    <label class='form-label small text-muted'>Title</label>
                                                    <input type='text' class='form-control rounded-pill' name='title' value='" . htmlspecialchars($row['title'], ENT_QUOTES) . "' required>
                                                </div>
                                                <div class='col-md-6'>
                                                    <label class='form-label small text-muted'>Description</label>
                                                    <input type='text' class='form-control rounded-pill' name='description' value='" . htmlspecialchars($row['description'], ENT_QUOTES) . "' required>
                                                </div>
                                                <div class='col-md-6'>
                                                    <label class='form-label small text-muted'>Footer Text</label>
                                                    <input type='text' class='form-control rounded-pill' name='footer_text' value='" . htmlspecialchars($row['footer_text'], ENT_QUOTES) . "' required>
                                                </div>
                                                <div class='col-md-12'>
                                                    <label class='form-label small text-muted'>Replace Image (Optional)</label>
                                                    <input type='file' class='form-control rounded-4' name='offer_image' accept='image/*'>
                                                </div>
                                                <div class='col-12 mt-4'>
                                                    <button type='submit' class='btn btn-primary w-100 rounded-pill fw-bold py-2 shadow-sm'>Save Changes</button>
                                                </div>
                                            </form>
                                          </div>
                                        </div>
                                      </div>
                                    </div>";
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center py-5 text-muted'>
                                        <i class='fas fa-tags fa-3x mb-3' style='opacity:0.2;'></i><br>
                                        No active offers found
                                      </td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
        <!-- Manage Routes Card -->
        <div class="data-card" id="manage-routes-card">
            <div class="card-header">
                <h4><i class="fas fa-route"></i> Manage Top Flight Routes</h4>
            </div>
            <div class="p-4">
                <!-- Add New Route Form -->
                <div class="card border-0 bg-light rounded-4 mb-5 shadow-sm p-4">
                    <h5 class="fw-bold mb-4 text-dark"><i class="fas fa-plus-circle me-2 text-warning"></i>Add New Flight Route</h5>
                    <form action="admin.php" method="POST" enctype="multipart/form-data" class="row g-4">
                        <input type="hidden" name="action" value="add_route">
                        
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted">Destination City</label>
                            <input type="text" class="form-control border-white shadow-none rounded-pill bg-white" name="city_name" placeholder="E.g. Goa" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Via Cities (Comma separated)</label>
                            <input type="text" class="form-control border-white shadow-none rounded-pill bg-white" name="via_cities" placeholder="E.g. Delhi, Mumbai, Pune" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted">Thumbnail Image</label>
                            <input type="file" class="form-control border-white shadow-none rounded-pill bg-white" name="route_image" accept="image/*" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Search Query From (E.g. Delhi (DEL))</label>
                            <input type="text" class="form-control border-white shadow-none rounded-pill bg-white" name="from_query" value="Delhi (DEL)" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Search Query To (E.g. Goa (GOI))</label>
                            <input type="text" class="form-control border-white shadow-none rounded-pill bg-white" name="to_query" placeholder="E.g. Goa (GOI)" required>
                        </div>
                        
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold text-white py-2" style="background: linear-gradient(135deg, #F7921E, #ff9b1a); border:none;">
                                <i class="fas fa-save me-1"></i>Save Route
                            </button>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Icon</th>
                                <th>City / Flights</th>
                                <th>Via Details</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $routes_res = $conn->query("SELECT * FROM top_flight_routes ORDER BY id DESC");
                            while ($route = $routes_res->fetch_assoc()) {
                                $status_badge = $route['status'] == 1 ? 'bg-success' : 'bg-secondary';
                                $status_text = $route['status'] == 1 ? 'Active' : 'Hidden';
                                
                                echo "<tr>";
                                echo "<td><img src='{$route['image_path']}' class='rounded-circle shadow-sm' style='width:50px; height:50px; object-fit:cover; border:2px solid #fff;'></td>";
                                echo "<td><div class='fw-bold'>{$route['city_name']} Flights</div><div class='small text-muted'>{$route['from_query']} to {$route['to_query']}</div></td>";
                                echo "<td><div class='small' style='max-width:300px;'>Via - {$route['via_cities']}</div></td>";
                                echo "<td><span class='badge {$status_badge} rounded-pill'>{$status_text}</span></td>";
                                echo "<td class='text-end'>
                                        <div class='btn-group border shadow-sm rounded-pill p-1 bg-white'>
                                            <a href='admin.php?action=toggle_route&id={$route['id']}' class='btn btn-sm btn-link text-decoration-none fw-bold small text-" . ($route['status'] == 1 ? 'danger' : 'success') . "'>
                                                " . ($route['status'] == 1 ? 'Hide' : 'Show') . "
                                            </a>
                                            <button type='button' class='btn btn-sm btn-link text-primary text-decoration-none fw-bold small mx-2' data-bs-toggle='modal' data-bs-target='#editRouteModal{$route['id']}'>Edit</button>
                                            <a href='admin.php?action=delete_route&id={$route['id']}' class='btn btn-sm btn-link text-danger text-decoration-none fw-bold small' onclick=\"return confirm('Delete this route?')\">Delete</a>
                                        </div>
                                      </td>";
                                echo "</tr>";

                                // Edit Modal for this route
                                $offer_modals_html .= "
                                <div class='modal fade' id='editRouteModal{$route['id']}' tabindex='-1' aria-hidden='true'>
                                  <div class='modal-dialog modal-lg'>
                                    <div class='modal-content border-0 shadow-lg rounded-4'>
                                      <div class='modal-header'>
                                        <h5 class='modal-title fw-bold'>Edit Flight Route</h5>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                      </div>
                                      <div class='modal-body p-4'>
                                        <form action='admin.php' method='POST' enctype='multipart/form-data' class='row g-3'>
                                            <input type='hidden' name='action' value='edit_route'>
                                            <input type='hidden' name='route_id' value='{$route['id']}'>
                                            <input type='hidden' name='existing_image' value='{$route['image_path']}'>
                                            <div class='col-md-6'>
                                                <label class='form-label small text-muted'>Destination City</label>
                                                <input type='text' class='form-control rounded-pill' name='city_name' value='".htmlspecialchars($route['city_name'])."'>
                                            </div>
                                            <div class='col-md-6'>
                                                <label class='form-label small text-muted'>Via Cities</label>
                                                <input type='text' class='form-control rounded-pill' name='via_cities' value='".htmlspecialchars($route['via_cities'])."'>
                                            </div>
                                            <div class='col-md-6'>
                                                <label class='form-label small text-muted'>From Query</label>
                                                <input type='text' class='form-control rounded-pill' name='from_query' value='".htmlspecialchars($route['from_query'])."'>
                                            </div>
                                            <div class='col-md-6'>
                                                <label class='form-label small text-muted'>To Query</label>
                                                <input type='text' class='form-control rounded-pill' name='to_query' value='".htmlspecialchars($route['to_query'])."'>
                                            </div>
                                            <div class='col-md-12'>
                                                <label class='form-label small text-muted'>Change Image (Optional)</label>
                                                <input type='file' class='form-control rounded-pill' name='route_image' accept='image/*'>
                                            </div>
                                            <div class='col-12 mt-4'>
                                                <button type='submit' class='btn btn-primary w-100 rounded-pill py-2'>Save Route Changes</button>
                                            </div>
                                        </form>
                                      </div>
                                    </div>
                                  </div>
                                </div>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
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
