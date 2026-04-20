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

$c_sql = "SELECT c.*, ci.image_path as cab_img, ci.id as original_cab_id 
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
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/png">
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
            color: white;
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(0, 167, 157, 0.2);
            position: relative;
            overflow: hidden;
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
            $meta = $row['guests'] . " | " . ($row['room_type'] ?: 'Standard');
            $dates = date('d M Y', strtotime($row['check_in'])) . " to " . date('d M Y', strtotime($row['check_out']));
            $img = !empty($row['hotel_img']) ? $row['hotel_img'] : "assets/images/tour-2-550x590.jpg";
            $view_link = !empty($row['hotel_id']) ? "hotel-details.php?id=" . $row['hotel_id'] : "hotel.php";
        } elseif ($type == 'Cab') {
            $title = $row['from_city'] . " to " . $row['to_city'];
            $meta = $row['trip_type'] . " | " . ($row['pickup_type'] ?: 'Transfer');
            $dates = date('d M Y', strtotime($row['pickup_date'])) . " at " . $row['pickup_time'];
            $img = !empty($row['cab_img']) ? $row['cab_img'] : "assets/images/car.png";

            // Dynamic Search Link for Cabs
            $params = http_build_query([
                'from' => $row['from_city'],
                'to' => $row['to_city'],
                'date' => $row['pickup_date'],
                'time' => $row['pickup_time'],
                'tripType' => $row['trip_type'],
                'pickup' => $row['pickup_type']
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
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="editModalTitle">Edit Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editBookingForm">
                        <input type="hidden" name="type" id="edit_type">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Update Phone/Contact</label>
                            <input type="text" class="form-control rounded-pill" name="phone"
                                placeholder="Enter new mobile number">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Message/Special Request</label>
                            <textarea class="form-control" name="message" rows="3"
                                placeholder="Change dates or other requests..."></textarea>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-light rounded-pill px-4"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-5">Submit Request</button>
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

        function editBooking(type, id) {
            document.getElementById('edit_type').value = type;
            document.getElementById('edit_id').value = id;
            document.getElementById('editModalTitle').innerText = 'Edit ' + type + ' Request';
            let modal = new bootstrap.Modal(document.getElementById('editBookingModal'));
            modal.show();
        }

        document.getElementById('editBookingForm').addEventListener('submit', function (e) {
            e.preventDefault();
            alert('Your update request has been sent to our travel experts. We will contact you shortly to confirm the changes.');
            bootstrap.Modal.getInstance(document.getElementById('editBookingModal')).hide();
        });
    </script>

</body>

</html>