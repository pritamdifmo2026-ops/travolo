<?php
session_start();
include_once __DIR__ . '/includes/auth.php';
include_once __DIR__ . '/includes/db.php';

// Protect the page
if (!is_logged_in()) {
    header('Location: login.php?redirect=user-dashboard.php');
    exit;
}

$user_id = (int) ($_SESSION['user_id'] ?? 0);
$user_email = $conn->real_escape_string($_SESSION['user_email'] ?? '');
$user_phone = $conn->real_escape_string($_SESSION['user_phone'] ?? '');
$user_name = $_SESSION['user_name'] ?? 'Guest';

// Fetch User Bookings (Hardened Query with JOINS for Images)
$f_sql = "SELECT * FROM flights WHERE (user_id > 0 AND user_id = $user_id) 
          OR (email != '' AND TRIM(LOWER(email)) = TRIM(LOWER('$user_email'))) 
          OR (phone != '' AND TRIM(phone) = TRIM('$user_phone')) ORDER BY id DESC";

$h_sql = "SELECT h.*, ah.image as hotel_img, ah.id as original_hotel_id 
          FROM hotels h 
          LEFT JOIN app_hotels ah ON h.hotel_id = ah.id 
          WHERE (h.user_id > 0 AND h.user_id = $user_id) 
          OR (h.email != '' AND TRIM(LOWER(h.email)) = TRIM(LOWER('$user_email'))) 
          OR (h.phone != '' AND TRIM(h.phone) = TRIM('$user_phone')) ORDER BY h.id DESC";

$c_sql = "SELECT c.*, ci.image_path as cab_img, ci.id as original_cab_id, ci.car_name, ci.base_price, ci.hourly_price, ci.outstation_price, ci.airport_price 
          FROM cabs c 
          LEFT JOIN cab_inventory ci ON c.cab_id = ci.id 
          WHERE (c.user_id > 0 AND c.user_id = $user_id) 
          OR (c.email != '' AND TRIM(LOWER(c.email)) = TRIM(LOWER('$user_email'))) 
          OR (c.phone != '' AND TRIM(c.phone) = TRIM('$user_phone')) ORDER BY c.id DESC";

$flights = $conn->query($f_sql);
$hotels = $conn->query($h_sql);
$cabs = $conn->query($c_sql);

// Stats
$total_bookings = $flights->num_rows + $hotels->num_rows + $cabs->num_rows;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings | Travolo Dashboard</title>
    <!--====== Favicon Icon ======-->
    <link rel="shortcut icon" href="assets/images/favicon.png" type="image/png">
    <!--====== Google Fonts ======-->
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!--====== Vendor CSS ======-->
    <link rel="stylesheet" href="assets/fonts/flaticon/flaticon_gowilds.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendor/magnific-popup/dist/magnific-popup.css">
    <link rel="stylesheet" href="assets/vendor/slick/slick.css">
    <link rel="stylesheet" href="assets/vendor/jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" href="assets/vendor/nice-select/css/nice-select.css">
    <link rel="stylesheet" href="assets/vendor/animate.css">

    <!--====== Site CSS ======-->
    <link rel="stylesheet" href="assets/css/default.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        :root {
            --primary-dashboard: #00a79d;
            --primary-dark: #133a25;
            --accent: #F7921E;
            --bg-light: #f4f7f6;
            --white: #ffffff;
            --text-dark: #2d3436;
            --text-muted: #636e72;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
        }

        .dashboard-container {
            padding-top: 15px;
            /* Offset for sticky header */
            padding-bottom: 100px;
        }

        .welcome-card {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-dashboard));
            color: #ffffff !important;
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(0, 167, 157, 0.2);
            position: relative;
            overflow: hidden;
        }

        .welcome-card h2, .welcome-card p {
            color: #ffffff !important;
        }

        .welcome-card::after {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            font-size: 24px;
            color: var(--primary-dashboard);
            margin-bottom: 10px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            display: block;
        }

        .stat-label {
            font-size: 14px;
            color: var(--text-muted);
        }

        .booking-card {
            background: white;
            border-radius: 12px;
            margin-bottom: 12px;
            padding: 12px 18px;
            border: 1px solid #edf2f7;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            transition: all 0.2s ease;
        }

        .booking-card:hover {
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.05);
            border-color: var(--primary-dashboard);
        }

        .card-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            width: 100%;
        }

        .booking-preview-container {
            width: 60px;
            height: 60px;
            flex-shrink: 0;
            border-radius: 8px;
            overflow: hidden;
            background: #f7fafc;
        }

        .booking-preview-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .booking-main-info {
            flex: 2;
            min-width: 150px;
        }

        .booking-main-info h5 {
            font-size: 15px;
            font-weight: 800;
            margin-bottom: 0px;
            color: var(--primary-dark);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .booking-type-badge {
            font-size: 8px;
            font-weight: 800;
            text-transform: uppercase;
            padding: 2px 6px;
            border-radius: 4px;
            margin-bottom: 4px;
            display: inline-block;
            letter-spacing: 0.5px;
        }

        .badge-flight {
            background: #eef7ff;
            color: #2196f3;
        }

        .badge-hotel {
            background: #fdf2f9;
            color: #e91e63;
        }

        .badge-cab {
            background: #fff9ed;
            color: #f7921e;
        }

        .booking-meta-info {
            flex: 3;
            display: flex;
            align-items: center;
            gap: 20px;
            border-left: 1px solid #f0f0f0;
            padding-left: 20px;
        }

        .date-line {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-dark);
            white-space: nowrap;
        }

        .date-line i {
            color: var(--primary-dashboard);
            width: 18px;
            font-size: 12px;
        }

        .meta-line {
            font-size: 12px;
            color: var(--text-muted);
            white-space: nowrap;
        }

        .booking-actions {
            flex: 1.5;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 10px;
        }

        .booking-status {
            font-weight: 700;
            font-size: 9px;
            padding: 4px 10px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-requested {
            background: #fff4e5;
            color: #ff9800;
        }

        .status-confirmed {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .btn-action-row {
            padding: 8px 14px;
            font-size: 11px;
            font-weight: 700;
            border-radius: 6px;
            text-decoration: none;
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-view-main {
            background: var(--primary-dashboard);
            color: white;
            border: none;
        }

        .btn-view-main:hover {
            background: var(--primary-dark);
            color: white;
        }

        .btn-receipt-mini {
            background: #f7fafc;
            color: #718096;
            border: 1px solid #e2e8f0;
        }

        @media (max-width: 991px) {
            .card-inner {
                flex-wrap: wrap;
            }

            .booking-meta-info {
                border-left: none;
                padding-left: 0;
                gap: 10px;
                flex-direction: column;
                align-items: flex-start;
            }

            .booking-actions {
                width: 100%;
                justify-content: flex-start;
            }
        }

        @media (max-width: 576px) {
            .booking-preview-container {
                display: none;
            }

            .booking-main-info {
                flex: 1;
            }
        }

        .nav-tabs {
            border: none;
            background: #eef2f1;
            padding: 5px;
            border-radius: 15px;
            display: inline-flex;
            margin-bottom: 30px;
        }

        .nav-tabs .nav-link {
            border: none;
            border-radius: 12px;
            padding: 10px 25px;
            font-weight: 600;
            color: var(--text-muted);
        }

        .nav-tabs .nav-link.active {
            background: var(--primary-dashboard);
            color: white;
        }

        .search-box {
            background: white;
            border-radius: 50px;
            padding: 5px 25px;
            border: 1px solid #eee;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
        }

        .search-box input {
            border: none;
            width: 100%;
            padding: 10px;
            font-size: 15px;
        }

        .search-box input:focus {
            outline: none;
        }

        .booking-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        @media (max-width: 768px) {
            .welcome-card {
                padding: 30px 20px;
            }

            .booking-header {
                flex-direction: column;
            }

            .booking-status {
                float: none;
                margin-bottom: 10px;
                display: block;
            }
        }
    </style>
</head>

<body>

    <?php include 'includes/navbar.php'; ?>

    <div class="container dashboard-container">
        <div class="welcome-card">
            <h2>Hi, <?php echo htmlspecialchars($user_name); ?>!</h2>
            <p class="mb-0 opacity-75">Welcome back to your travel companion. Manage your upcoming adventures here.</p>
        </div>

        <!-- Stats Row -->
        <div class="row g-4 mb-5 text-center">
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <i class="stat-icon fas fa-ticket-alt"></i>
                    <span class="stat-value"><?php echo $total_bookings; ?></span>
                    <span class="stat-label">Total Bookings</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <i class="stat-icon fas fa-plane-departure"></i>
                    <span class="stat-value"><?php echo $flights->num_rows; ?></span>
                    <span class="stat-label">Flights</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <i class="stat-icon fas fa-hotel"></i>
                    <span class="stat-value"><?php echo $hotels->num_rows; ?></span>
                    <span class="stat-label">Hotels</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <i class="stat-icon fas fa-taxi"></i>
                    <span class="stat-value"><?php echo $cabs->num_rows; ?></span>
                    <span class="stat-label">Cabs</span>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <ul class="nav nav-tabs" id="bookingTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="all-tab" data-bs-toggle="tab" href="#all" role="tab">All Bookings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="flights-tab" data-bs-toggle="tab" href="#flights" role="tab">Flights</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="hotels-tab" data-bs-toggle="tab" href="#hotels" role="tab">Hotels</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="cabs-tab" data-bs-toggle="tab" href="#cabs" role="tab">Cabs</a>
                </li>
            </ul>

            <div class="search-box col-12 col-md-4">
                <i class="fas fa-search text-muted"></i>
                <input type="text" id="bookingSearch" placeholder="Search by city or hotel...">
            </div>
        </div>

        <div class="tab-content mt-2" id="bookingTabsContent">
            <!-- ALL BOOKINGS TAB -->
            <div class="tab-pane fade show active" id="all" role="tabpanel">
                <div class="booking-list">
                    <?php if ($total_bookings == 0): ?>
                        <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                            <img src="assets/images/no-data.png" alt="No data" style="width: 150px; opacity: 0.5;"
                                class="mb-3">
                            <h4 class="text-muted">No bookings found yet.</h4>
                            <a href="index.php" class="btn btn-primary mt-3 px-4 rounded-pill">Explore Destinations</a>
                        </div>
                    <?php else: ?>
                        <!-- Merge and list all (logic below) -->
                        <?php
                        // Combine and Interleave all bookings by date for a Professional View
                        $all_data = [];
                        while ($f = $flights->fetch_assoc()) {
                            $f['type'] = 'Flight';
                            $all_data[] = $f;
                        }
                        while ($h = $hotels->fetch_assoc()) {
                            $h['type'] = 'Hotel';
                            $all_data[] = $h;
                        }
                        while ($c = $cabs->fetch_assoc()) {
                            $c['type'] = 'Cab';
                            $all_data[] = $c;
                        }

                        // Sort by booking_date DESC
                        usort($all_data, function ($a, $b) {
                            return strtotime($b['booking_date']) - strtotime($a['booking_date']);
                        });

                        if (empty($all_data)) {
                            echo "<div class='text-center py-5'>
                                <img src='assets/images/no-data.png' style='width: 120px; opacity: 0.5;'>
                                <p class='text-muted mt-3'>No recent activity found.</p>
                              </div>";
                        } else {
                            foreach ($all_data as $item) {
                                $type = $item['type'];
                                $badge = ($type == 'Flight') ? 'badge-flight' : (($type == 'Hotel') ? 'badge-hotel' : 'badge-cab');
                                $icon = ($type == 'Flight') ? 'fas fa-plane' : (($type == 'Hotel') ? 'fas fa-hotel' : 'fas fa-taxi');
                                render_single_booking($item, $type, $badge, $icon);
                            }
                        }
                        ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- FLIGHTS TAB -->
            <div class="tab-pane fade" id="flights" role="tabpanel">
                <?php
                $flights->data_seek(0);
                while ($row = $flights->fetch_assoc())
                    render_single_booking($row, 'Flight', 'badge-flight', 'fas fa-plane');
                ?>
            </div>

            <!-- HOTELS TAB -->
            <div class="tab-pane fade" id="hotels" role="tabpanel">
                <?php
                $hotels->data_seek(0);
                while ($row = $hotels->fetch_assoc())
                    render_single_booking($row, 'Hotel', 'badge-hotel', 'fas fa-hotel');
                ?>
            </div>

            <!-- CABS TAB -->
            <div class="tab-pane fade" id="cabs" role="tabpanel">
                <?php
                $cabs->data_seek(0);
                while ($row = $cabs->fetch_assoc())
                    render_single_booking($row, 'Cab', 'badge-cab', 'fas fa-taxi');
                ?>
            </div>
        </div>
    </div>

    <!--====== Start Gallery Section ======-->
    <section class="gallery-section mbm-150">
        <div class="container-fluid">
            <div class="slider-active-5-item wow fadeInUp">
                <!--=== Single Gallery Item ===-->
                <div class="single-gallery-item">
                    <div class="gallery-img">
                        <img src="assets/images/tour-3-550x590.jpg" alt="Gallery Image">
                        <div class="hover-overlay">
                            <a href="assets/images/tour-3-550x590.jpg" class="icon-btn img-popup"><i
                                    class="far fa-plus"></i></a>
                        </div>
                    </div>
                </div>
                <!--=== Single Gallery Item ===-->
                <div class="single-gallery-item">
                    <div class="gallery-img">
                        <img src="assets/images/tour-4-550x590.jpg" alt="Gallery Image">
                        <div class="hover-overlay">
                            <a href="assets/images/tour-4-550x590.jpg" class="icon-btn img-popup"><i
                                    class="far fa-plus"></i></a>
                        </div>
                    </div>
                </div>
                <!--=== Single Gallery Item ===-->
                <div class="single-gallery-item">
                    <div class="gallery-img">
                        <img src="assets/images/tour-12-550x590.jpg" alt="Gallery Image">
                        <div class="hover-overlay">
                            <a href="assets/images/tour-12-550x590.jpg" class="icon-btn img-popup"><i
                                    class="far fa-plus"></i></a>
                        </div>
                    </div>
                </div>
                <!--=== Single Gallery Item ===-->
                <div class="single-gallery-item">
                    <div class="gallery-img">
                        <img src="assets/images/tour-8-550x590.jpg" alt="Gallery Image">
                        <div class="hover-overlay">
                            <a href="assets/images/tour-8-550x590.jpg" class="icon-btn img-popup"><i
                                    class="far fa-plus"></i></a>
                        </div>
                    </div>
                </div>
                <!--=== Single Gallery Item ===-->
                <div class="single-gallery-item">
                    <div class="gallery-img">
                        <img src="assets/images/tour-3-550x590.jpg" alt="Gallery Image">
                        <div class="hover-overlay">
                            <a href="assets/images/gallery/gl-5.jpg" class="icon-btn img-popup"><i
                                    class="far fa-plus"></i></a>
                        </div>
                    </div>
                </div>
                <!--=== Single Gallery Item ===-->
                <div class="single-gallery-item">
                    <div class="gallery-img">
                        <img src="assets/images/tour-8-550x590.jpg" alt="Gallery Image">
                        <div class="hover-overlay">
                            <a href="assets/images/tour-8-550x590.jpg" class="icon-btn img-popup"><i
                                    class="far fa-plus"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== End Gallery Section ======-->

    <?php
    include 'includes/footer.php';

    function render_single_booking($row, $type, $badge_class, $icon)
    {
        if (!$row)
            return;
        $status = $row['booking_status'] ?: 'Requested';
        $status_lc = str_replace(' ', '-', strtolower($status));

        $title = "";
        $meta = "";
        $dates = "";
        $img = "";
        $view_link = "#";

        if ($type == 'Flight') {
            $title = $row['from_city'] . " to " . $row['to_city'];
            $meta = $row['trip_type'] . " | " . $row['travel_class'] . " | " . ($row['adults'] + $row['children'] + $row['infants']) . " Pax";
            $dates = date('d M Y', strtotime($row['depart_date'])) . ($row['return_date'] ? " - " . date('d M Y', strtotime($row['return_date'])) : "");
            $img = "assets/images/plane.png";
            $view_link = "flight-booking.php";
        } elseif ($type == 'Hotel') {
            $title = $row['hotel_search'];
            $price_display = "";
            if (isset($row['price']) && $row['price'] > 0) {
                $price_display = " | ₹" . number_format($row['price']);
            }
            $meta = $row['guests'] . " | " . ($row['room_type'] ?: 'Standard') . $price_display;
            $dates = date('d M Y', strtotime($row['check_in'])) . " to " . date('d M Y', strtotime($row['check_out']));
            $img = !empty($row['hotel_img']) ? $row['hotel_img'] : "assets/images/tour-2-550x590.jpg";
            $view_link = !empty($row['hotel_id']) ? "hotel-details.php?id=" . $row['hotel_id'] : "hotel.php";
        } elseif ($type == 'Cab') {
            $cab_name = !empty($row['car_name']) ? " (" . $row['car_name'] . ")" : "";
            $title = $row['from_city'] . " to " . $row['to_city'] . $cab_name;
            
            $price_display = "";
            $price_val = 0;
            if (!empty($row['car_name'])) {
                if ($row['trip_type'] === 'Hourly') $price_val = $row['hourly_price'];
                elseif ($row['trip_type'] === 'Airport Transfer' || $row['to_city'] === 'Airport' || $row['from_city'] === 'Airport') $price_val = $row['airport_price'];
                elseif ($row['trip_type'] === 'Outstation') $price_val = $row['outstation_price'];
                else $price_val = $row['base_price'];
                
                // Fallback to base price if the specific tier is 0 or null
                if (empty($price_val) || $price_val <= 0) {
                    $price_val = $row['base_price'];
                }
            }
            if ($price_val > 0) {
                $price_display = " | ₹" . number_format($price_val);
            }

            $meta = $row['trip_type'] . " | " . ($row['pickup_type'] ?: 'Transfer') . $price_display;
            $dates = date('d M Y', strtotime($row['pickup_date'])) . " at " . $row['pickup_time'];
            $img = !empty($row['cab_img']) ? $row['cab_img'] : "assets/images/car.png";

            // Dynamic Search Link for Cabs
            $params = http_build_query([
                'from' => $row['from_city'],
                'to' => $row['to_city'],
                'date' => $row['pickup_date'],
                'time' => $row['pickup_time'],
                'tripType' => $row['trip_type'],
                'pickup' => $row['pickup_type'],
                'cab_id' => $row['original_cab_id']
            ]);
            $view_link = "cab-results.php?" . $params;
        }

        echo "
    <div class='booking-card' data-search='{$title} {$type}'>
        <div class='card-inner'>
            <!-- Preview -->
            <div class='booking-preview-container'>
                <img src='{$img}' class='booking-preview-img' alt='{$type}'>
            </div>

            <!-- Main Info -->
            <div class='booking-main-info'>
                <span class='booking-type-badge {$badge_class}'>{$type}</span>
                <h5>{$title}</h5>
                <div class='meta-line'>{$meta}</div>
            </div>

            <!-- Meta/Dates -->
            <div class='booking-meta-info'>
                <div class='date-line'><i class='fas fa-calendar-alt'></i> {$dates}</div>
                <div class='booking-status status-{$status_lc}'>{$status}</div>
            </div>

            <!-- Actions -->
            <div class='booking-actions'>
                " . (($status_lc == 'requested' || $status_lc == 'pending') ? "
                <button class='btn-action-row btn-receipt-mini' onclick='editBooking(\"{$type}\", " . json_encode($row) . ")' style='background: #fff5f5; color: #e53e3e; border-color: #feb2b2;'>
                    <i class='fas fa-edit'></i> Edit
                </button>" : "") . "
                <button class='btn-action-row btn-receipt-mini' onclick='alert(\"Booking ID: #TRV-{$row['id']}\\nDate: {$row['booking_date']}\")' title='View Receipt'>
                    <i class='fas fa-file-invoice'></i> Receipt
                </button>
                <a href='{$view_link}' class='btn-action-row btn-view-main'>
                    <i class='fas fa-eye'></i> View
                </a>
            </div>
        </div>
    </div>";
    }
    ?>

    <!-- Edit Modal -->
    <div class="modal fade" id="editBookingModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title fw-bold text-white" id="editModalTitle">Edit Booking</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="bg-light px-4 py-2 border-bottom d-flex justify-content-between align-items-center" id="editModalMetaHeader" style="font-size: 13px;">
                    <div><span class="text-muted fw-bold">ID:</span> <span id="display_booking_id" class="fw-bold text-dark"></span></div>
                    <div><span class="text-muted fw-bold">Status:</span> <span id="display_booking_status" class="badge bg-warning text-dark"></span></div>
                </div>
                <div class="modal-body p-4">
                    <form id="editBookingForm">
                        <input type="hidden" name="action" value="update_booking">
                        <input type="hidden" name="type" id="edit_type">
                        <input type="hidden" name="id" id="edit_id">
                        
                        <!-- Common Field: Contact -->
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Mobile Number</label>
                            <input type="tel" class="form-control rounded-3" name="phone" id="edit_phone" required pattern="[0-9]{10}">
                        </div>

                        <!-- Flight Specific Fields -->
                        <div id="flight_fields" class="type-fields" style="display:none;">
                            <div class="row g-2">
                                <div class="col-6 mb-3"><label class="small fw-bold">From</label><input type="text" name="from_city" id="edit_f_from" class="form-control rounded-3"></div>
                                <div class="col-6 mb-3"><label class="small fw-bold">To</label><input type="text" name="to_city" id="edit_f_to" class="form-control rounded-3"></div>
                            </div>
                            <div class="row g-2">
                                <div class="col-6 mb-3"><label class="small fw-bold">Departure</label><input type="date" name="depart_date" id="edit_f_depart" class="form-control rounded-3"></div>
                                <div class="col-6 mb-3"><label class="small fw-bold">Return</label><input type="date" name="return_date" id="edit_f_return" class="form-control rounded-3"></div>
                            </div>
                        </div>

                        <!-- Hotel Specific Fields -->
                        <div id="hotel_fields" class="type-fields" style="display:none;">
                            <div class="mb-3"><label class="small fw-bold">Hotel/Location</label><input type="text" name="hotel_search" id="edit_h_search" class="form-control rounded-3"></div>
                            <div class="row g-2">
                                <div class="col-6 mb-3"><label class="small fw-bold">Check-in</label><input type="date" name="check_in" id="edit_h_in" class="form-control rounded-3" onchange="calcHotelPrice()"></div>
                                <div class="col-6 mb-3"><label class="small fw-bold">Check-out</label><input type="date" name="check_out" id="edit_h_out" class="form-control rounded-3" onchange="calcHotelPrice()"></div>
                            </div>
                            
                            <!-- Room Type -->
                            <div class="mb-3">
                                <label class="small fw-bold">Room Type</label>
                                <input type="text" name="room_type" id="edit_h_room_type" class="form-control rounded-3" placeholder="e.g. Standard Room">
                            </div>
                            
                            <!-- Guests configuration -->
                            <div class="mb-3">
                                <label class="small fw-bold d-block mb-2">Guests & Rooms</label>
                                <div class="row g-2 text-center">
                                    <div class="col-3">
                                        <div class="small text-muted mb-1">Rooms</div>
                                        <input type="number" id="edit_h_rooms" class="form-control text-center px-1" value="1" min="1" max="10" onchange="calcHotelPrice()">
                                    </div>
                                    <div class="col-3">
                                        <div class="small text-muted mb-1">Adults</div>
                                        <input type="number" id="edit_h_adults" class="form-control text-center px-1" value="2" min="1" max="20" onchange="updateGuestString()">
                                    </div>
                                    <div class="col-3">
                                        <div class="small text-muted mb-1">Children</div>
                                        <input type="number" id="edit_h_children" class="form-control text-center px-1" value="0" min="0" max="10" onchange="updateGuestString()">
                                    </div>
                                    <div class="col-3">
                                        <div class="small text-muted mb-1">Infants</div>
                                        <input type="number" id="edit_h_infants" class="form-control text-center px-1" value="0" min="0" max="5" onchange="updateGuestString()">
                                    </div>
                                </div>
                                <input type="hidden" name="guests" id="edit_h_guests_str">
                            </div>
                            
                            <!-- Price Recalculation -->
                            <div class="bg-primary bg-opacity-10 p-2 px-3 rounded-3 mb-2 border border-primary border-opacity-25 d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="small text-primary fw-bold" style="font-size: 10px;">Estimated Total</div>
                                    <div class="fw-bold mb-0 text-dark">₹<span id="displayHotelPrice">0</span></div>
                                </div>
                                <input type="hidden" name="price" id="inputHotelPrice">
                            </div>
                        </div>

                        <!-- Cab Specific Fields -->
                        <div id="cab_fields" class="type-fields" style="display:none;">
                            <div class="mb-3">
                                <label class="small fw-bold">Trip Type</label>
                                <select name="trip_type" id="edit_c_trip_type" class="form-select rounded-3" onchange="calcCabPrice()">
                                    <option value="Transfer">Local / One Way</option>
                                    <option value="Hourly">Hourly Package (8h/80km)</option>
                                    <option value="Airport Transfer">Airport Transfer</option>
                                    <option value="Outstation">Outstation</option>
                                </select>
                            </div>
                            <div class="row g-2">
                                <div class="col-6 mb-3"><label class="small fw-bold">From</label><input type="text" name="from_city" id="edit_c_from" list="citiesList" class="form-control rounded-3" onchange="calcCabPrice()"></div>
                                <div class="col-6 mb-3"><label class="small fw-bold">To</label><input type="text" name="to_city" id="edit_c_to" list="citiesList" class="form-control rounded-3" onchange="calcCabPrice()"></div>
                            </div>
                            <div class="row g-2">
                                <div class="col-6 mb-3"><label class="small fw-bold">Date</label><input type="date" name="pickup_date" id="edit_c_date" class="form-control rounded-3"></div>
                                <div class="col-6 mb-3"><label class="small fw-bold">Time</label><input type="time" name="pickup_time" id="edit_c_time" class="form-control rounded-3"></div>
                            </div>
                            
                            <!-- Cab Price Recalculation -->
                            <div class="bg-primary bg-opacity-10 p-2 px-3 rounded-3 mb-2 border border-primary border-opacity-25 d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="small text-primary fw-bold" style="font-size: 10px;">Estimated Total</div>
                                    <div class="fw-bold mb-0 text-dark">₹<span id="displayCabPrice">0</span></div>
                                </div>
                            </div>
                        </div>

                        <datalist id="citiesList">
                            <option value="Delhi">
                            <option value="Mumbai">
                            <option value="Bangalore">
                            <option value="Hyderabad">
                            <option value="Chennai">
                            <option value="Kolkata">
                            <option value="Pune">
                            <option value="Ahmedabad">
                            <option value="Jaipur">
                            <option value="Lucknow">
                            <option value="Airport">
                        </datalist>

                        <div class="text-end mt-4">
                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- (Remove duplicate script includes) -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        // Real-time Search
        document.getElementById('bookingSearch').addEventListener('input', function (e) {
            let term = e.target.value.toLowerCase();
            let cards = document.querySelectorAll('.booking-card');
            cards.forEach(card => {
                let searchData = card.getAttribute('data-search').toLowerCase();
                if (searchData.includes(term)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        let currentHotelBasePrice = 0;
        let currentCabBasePrice = 0;
        let currentCabHourlyPrice = 0;
        let currentCabOutstationPrice = 0;
        let currentCabAirportPrice = 0;

        function calcCabPrice() {
            const tripType = document.getElementById('edit_c_trip_type').value;
            let price = currentCabBasePrice;
            
            if(tripType === 'Hourly' && currentCabHourlyPrice > 0) price = currentCabHourlyPrice;
            else if(tripType === 'Outstation' && currentCabOutstationPrice > 0) price = currentCabOutstationPrice;
            else if((tripType === 'Airport Transfer' || document.getElementById('edit_c_to').value.toLowerCase().includes('airport') || document.getElementById('edit_c_from').value.toLowerCase().includes('airport')) && currentCabAirportPrice > 0) price = currentCabAirportPrice;
            
            document.getElementById('displayCabPrice').innerText = price > 0 ? price.toLocaleString() : 'N/A';
        }

        function parseGuestString(str) {
            let result = { rooms: 1, adults: 2, children: 0, infants: 0 };
            if(!str) return result;
            
            // Example format: 1 Room, 2 Adults, 1 Child
            let parts = str.split(',').map(s => s.trim());
            parts.forEach(part => {
                let num = parseInt(part);
                if(isNaN(num)) return;
                let lower = part.toLowerCase();
                if(lower.includes('room')) result.rooms = num;
                else if(lower.includes('adult')) result.adults = num;
                else if(lower.includes('child')) result.children = num;
                else if(lower.includes('infant')) result.infants = num;
            });
            return result;
        }

        function calcHotelPrice() {
            if(currentHotelBasePrice <= 0) {
                updateGuestString();
                return;
            }
            
            const cin = new Date(document.getElementById('edit_h_in').value);
            const cout = new Date(document.getElementById('edit_h_out').value);
            const rooms = parseInt(document.getElementById('edit_h_rooms').value) || 1;
            
            let nights = Math.ceil((cout - cin) / (1000 * 60 * 60 * 24));
            if (isNaN(nights) || nights < 1) nights = 1;
            
            const total = currentHotelBasePrice * rooms * nights;
            document.getElementById('displayHotelPrice').innerText = total.toLocaleString();
            document.getElementById('inputHotelPrice').value = total;
            updateGuestString();
        }

        function updateGuestString() {
            let r = parseInt(document.getElementById('edit_h_rooms').value) || 1;
            let a = parseInt(document.getElementById('edit_h_adults').value) || 1;
            let c = parseInt(document.getElementById('edit_h_children').value) || 0;
            let i = parseInt(document.getElementById('edit_h_infants').value) || 0;
            
            let str = `${r} Room${r > 1 ? 's' : ''}, ${a} Adult${a > 1 ? 's' : ''}`;
            if (c > 0) str += `, ${c} Child${c > 1 ? 'ren' : ''}`;
            if (i > 0) str += `, ${i} Infant${i > 1 ? 's' : ''}`;
            
            document.getElementById('edit_h_guests_str').value = str;
        }

        function editBooking(type, data) {
            document.getElementById('edit_type').value = type;
            document.getElementById('edit_id').value = data.id;
            document.getElementById('editModalTitle').innerText = 'Edit ' + type + ' Request';
            
            // Read-Only Header
            document.getElementById('display_booking_id').innerText = '#TRV-' + data.id;
            let statusBadge = document.getElementById('display_booking_status');
            statusBadge.innerText = data.booking_status || 'Pending';
            if((data.booking_status||'').toLowerCase() === 'confirmed') {
                statusBadge.className = 'badge bg-success text-white';
            } else {
                statusBadge.className = 'badge bg-warning text-dark';
            }
            
            // Hide all specific fields first
            document.querySelectorAll('.type-fields').forEach(el => el.style.display = 'none');
            
            // Common phone
            document.getElementById('edit_phone').value = data.phone || data.mobile || '';

            if (type === 'Flight') {
                document.getElementById('flight_fields').style.display = 'block';
                document.getElementById('edit_f_from').value = data.from_city;
                document.getElementById('edit_f_to').value = data.to_city;
                document.getElementById('edit_f_depart').value = data.depart_date;
                document.getElementById('edit_f_return').value = data.return_date || '';
            } else if (type === 'Hotel') {
                document.getElementById('hotel_fields').style.display = 'block';
                document.getElementById('edit_h_search').value = data.hotel_search;
                document.getElementById('edit_h_in').value = data.check_in;
                document.getElementById('edit_h_out').value = data.check_out;
                document.getElementById('edit_h_room_type').value = data.room_type || '';
                
                let guestData = parseGuestString(data.guests);
                document.getElementById('edit_h_rooms').value = guestData.rooms;
                document.getElementById('edit_h_adults').value = guestData.adults;
                document.getElementById('edit_h_children').value = guestData.children;
                document.getElementById('edit_h_infants').value = guestData.infants;
                
                // Derive Base Price to allow dynamic recalculation
                const cin = new Date(data.check_in);
                const cout = new Date(data.check_out);
                let nights = Math.ceil((cout - cin) / (1000 * 60 * 60 * 24));
                if (isNaN(nights) || nights < 1) nights = 1;
                
                let totalPrice = parseInt(data.price) || 0;
                currentHotelBasePrice = totalPrice > 0 ? (totalPrice / (guestData.rooms * nights)) : 0;
                
                calcHotelPrice();
            } else if (type === 'Cab') {
                document.getElementById('cab_fields').style.display = 'block';
                document.getElementById('edit_c_from').value = data.from_city;
                document.getElementById('edit_c_to').value = data.to_city;
                document.getElementById('edit_c_date').value = data.pickup_date;
                
                // Format time correctly for time input if needed (HH:mm)
                let t = data.pickup_time;
                if(t && t.includes(' ')) {
                    // Try parsing AM/PM to 24h format for input type=time
                    let [time, modifier] = t.split(' ');
                    let [hours, minutes] = time.split(':');
                    if (hours === '12') hours = '00';
                    if (modifier === 'PM') hours = parseInt(hours, 10) + 12;
                    t = `${hours}:${minutes}`;
                }
                document.getElementById('edit_c_time').value = t;

                let dbTrip = data.trip_type || 'Transfer';
                let selectEl = document.getElementById('edit_c_trip_type');
                
                // If the option doesn't exist, create it dynamically so it doesn't show empty
                if(![...selectEl.options].some(o => o.value === dbTrip)) {
                    let newOption = new Option(dbTrip, dbTrip);
                    selectEl.add(newOption);
                }
                
                selectEl.value = dbTrip;
                
                currentCabBasePrice = parseInt(data.base_price) || 0;
                currentCabHourlyPrice = parseInt(data.hourly_price) || 0;
                currentCabOutstationPrice = parseInt(data.outstation_price) || 0;
                currentCabAirportPrice = parseInt(data.airport_price) || 0;
                
                calcCabPrice();
            }

            let modal = new bootstrap.Modal(document.getElementById('editBookingModal'));
            modal.show();
        }

        document.getElementById('editBookingForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const btn = e.target.querySelector('button[type=\"submit\"]');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class=\"fas fa-spinner fa-spin\"></i> Saving...';
            btn.disabled = true;

            fetch('submit.php', {
                method: 'POST',
                body: new FormData(this)
            })
            .then(r => r.json())
            .then(data => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                
                // Hide modal
                let modalEl = document.getElementById('editBookingModal');
                let modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) {
                    modalInstance.hide();
                }

                if(data.status === 'success') {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Updated!', data.message, 'success').then(() => {
                            window.location.reload();
                        });
                    } else {
                        alert(data.message);
                        window.location.reload();
                    }
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Error', data.message, 'error');
                    } else {
                        alert('Error: ' + data.message);
                    }
                }
            })
            .catch(err => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Error', 'Something went wrong', 'error');
                } else {
                    alert('Something went wrong');
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>