<?php include_once 'includes/auth.php';error_reporting(E_ALL);ini_set('display_errors', 1);include 'includes/db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
// Fetching details if available
$res = $conn->query("SELECT * FROM app_hotels WHERE id = $id AND availability = 1");
$hotel = $res ? $res->fetch_assoc() : null;

if (!$hotel) {
    header("Location: hotel.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?php echo htmlspecialchars($hotel['name']); ?> | Hotel Details - Travelo</title>
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/fonts/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Slick Slider CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Prompt', sans-serif;
            color: #333;
        }

        .hotel-header-section {
            background: #fff;
            padding: 25px 0;
            border-bottom: 1px solid #e0e0e0;
            margin-top: 10px;
        }

        .rating-stars {
            color: #f7921e;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .hotel-title {
            font-size: 32px;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 5px;
        }

        .hotel-loc {
            color: #666;
            font-size: 15px;
        }

        .price-display {
            text-align: right;
        }

        .price-val {
            font-size: 32px;
            font-weight: 800;
            color: #ef4323;
            margin-bottom: 0;
        }

        .price-unit {
            font-size: 14px;
            color: #666;
        }

        .search-summary-sticky {
            background: #f1f8ff;
            padding: 12px 0;
            border-bottom: 1px solid #d5e8ff;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .back-link {
            color: #2196f3;
            font-weight: 600;
            text-decoration: none;
            font-size: 14px;
        }

        /* Mosaic Grid + Slider */
        .gallery-container {
            margin-top: 25px;
        }

        .main-slider-box {
            height: 500px;
            border-radius: 15px 0 0 15px;
            overflow: hidden;
            position: relative;
        }

        .main-slider-item {
            height: 500px;
        }

        .main-slider-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .side-img-box {
            position: relative;
            height: 246px;
            border-radius: 0 15px 0 0;
            overflow: hidden;
        }

        .side-img-box.bottom {
            border-radius: 0 0 15px 0;
            margin-top: 8px;
        }

        .img-fluid-full {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .img-overlay-more {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 24px;
            font-weight: 700;
            pointer-events: none;
        }

        .details-tabs {
            background: #fff;
            position: sticky;
            top: 75px;
            z-index: 98;
            border-bottom: 1px solid #ddd;
            margin-top: 30px;
        }

        .details-tabs .nav-link {
            color: #555;
            font-weight: 600;
            padding: 15px 25px;
            border: none;
            border-bottom: 3px solid transparent;
        }

        .details-tabs .nav-link.active {
            color: #2196f3;
            border-bottom-color: #2196f3;
        }

        .detail-card {
            background: #fff;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        }

        .card-title {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 20px;
            border-left: 4px solid #f7921e;
            padding-left: 15px;
        }

        .room-row {
            border: 1px solid #eee;
            border-radius: 12px;
            margin-bottom: 20px;
            overflow: hidden;
            transition: 0.3s;
        }

        .room-row:hover {
            border-color: #f7921e;
        }

        .room-img-box {
            width: 220px;
        }

        .room-content {
            padding: 20px;
            flex: 1;
            border-right: 1px solid #eee;
        }

        .room-price-box {
            width: 200px;
            padding: 20px;
            background: #fff9f2;
            text-align: center;
        }

        .btn-select {
            background: #F7921E;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 700;
            width: 100%;
        }

        .slick-prev,
        .slick-next {
            z-index: 10;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.8) !important;
            border-radius: 50% !important;
        }

        .slick-prev:before,
        .slick-next:before {
            color: #333 !important;
            font-size: 20px;
        }

        .slick-prev {
            left: 15px;
        }

        .slick-next {
            right: 15px;
        }
    </style>
</head>

<body>
    <?php include 'includes/navbar.php'; ?>

    <!-- Sticky Summary -->
    <div class="search-summary-sticky">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div class="search-info">
                    <a href="hotel.php" class="back-link me-3"><i class="fas fa-arrow-left me-1"></i> Back</a>
                    <span class="search-text">
                        <i class="fas fa-map-marker-alt text-danger me-1"></i>
                        <?php echo htmlspecialchars($hotel['location']); ?>,
                        <strong><?php 
                            $raw_ci = $_GET['checkin'] ?? '';
                            $ci_time = strtotime($raw_ci);
                            if (!$ci_time || date('Y', $ci_time) > 2030 || date('Y', $ci_time) < 2024) $ci_time = time();
                            echo date('d M Y', $ci_time); 
                        ?></strong> to
                        <strong><?php 
                            $raw_co = $_GET['checkout'] ?? '';
                            $co_time = strtotime($raw_co);
                            if (!$co_time || $co_time <= $ci_time || date('Y', $co_time) > 2030) $co_time = strtotime('+1 day', $ci_time);
                            echo date('d M Y', $co_time); 
                        ?></strong>,
                        <span class="text-muted"><?php echo htmlspecialchars($_GET['rooms'] ?? '1 Room, 2 Guests'); ?></span>
                    </span>
                </div>
                <a href="hotel.php" class="btn btn-outline-primary btn-sm rounded-pill px-4">Modify</a>
            </div>
        </div>
    </div>

    <!-- Header -->
    <section class="hotel-header-section">
        <div class="container">
            <div class="row align-items-center mb-4">
                <div class="col-sm-8">
                    <div class="rating-stars mb-1"><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                            class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <h1 class="hotel-title"><?php echo htmlspecialchars($hotel['name']); ?></h1>
                    <p class="hotel-loc mb-0"><i class="fas fa-map-marker-alt text-danger"></i>
                        <?php echo htmlspecialchars($hotel['location']); ?></p>
                </div>
                <div class="col-sm-4 price-display">
                    <div class="price-val">₹<?php echo number_format($hotel['price']); ?></div>
                    <div class="price-unit">per night</div>
                    <a href="#room-selection" class="btn btn-primary mt-2 px-4 shadow-sm">Select Room</a>
                </div>
            </div>

            <?php
            // Always start with the main hotel image
            $galleryLinks = [$hotel['image']];

            // Add gallery images from hotel_images table
            $galleryRes = $conn->query("SELECT image_path FROM hotel_images WHERE hotel_id = $id ORDER BY id ASC");
            if ($galleryRes) {
                while ($g = $galleryRes->fetch_assoc()) {
                    $galleryLinks[] = $g['image_path'];
                }
            }
            // Remove potential duplicates and re-index
            $galleryLinks = array_values(array_unique($galleryLinks));
            ?>
            <div class="row g-2 gallery-container">
                <div class="col-md-9">
                    <div class="main-slider-box shadow-sm">
                        <div class="hotel-main-slider">
                            <?php foreach ($galleryLinks as $link): ?>
                                <div class="main-slider-item">
                                    <img src="<?php echo $link; ?>" class="img-fluid-full" alt="Hotel Photo">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 d-none d-md-block">
                    <div class="side-img-box shadow-sm">
                        <img src="<?php echo $galleryLinks[1] ?? $galleryLinks[0]; ?>" class="img-fluid-full"
                            alt="Snapshot">
                    </div>
                    <div class="side-img-box bottom shadow-sm">
                        <img src="<?php echo $galleryLinks[2] ?? $galleryLinks[0]; ?>" class="img-fluid-full"
                            alt="Snapshot">
                        <?php if (count($galleryLinks) > 3): ?>
                            <div class="img-overlay-more">+<?php echo count($galleryLinks) - 3; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tabs -->
    <nav class="details-tabs shadow-sm">
        <div class="container">
            <div class="nav nav-tabs border-0" id="hotelTab">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-overview">Overview</button>
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-rooms">Rooms</button>
            </div>
        </div>
    </nav>

    <div class="tab-content mt-5 mb-5">
        <div class="container tab-pane fade show active" id="tab-overview">
            <div class="row">
                <div class="col-lg-8">
                    <div class="detail-card">
                        <h3 class="card-title">Property Overview</h3>
                        <p style="white-space: pre-line; line-height: 1.8; color: #444;">
                            <?php echo htmlspecialchars($hotel['description']); ?></p>
                    </div>
                    <div class="detail-card" id="room-selection">
                        <h3 class="card-title">Available Rooms</h3>
                        <?php
                        $rooms = $conn->query("SELECT * FROM hotel_rooms WHERE hotel_id = $id ORDER BY room_price ASC");
                        if ($rooms && $rooms->num_rows > 0):
                            while ($room = $rooms->fetch_assoc()):
                                $features = json_decode($room['features'], true) ?: [];
                                ?>
                                <div class="room-row d-flex flex-wrap flex-md-nowrap align-items-stretch">
                                    <div class="room-img-box">
                                        <img src="<?php echo $room['room_image'] ?: $hotel['image']; ?>" class="img-fluid-full"
                                            style="height: 100%;">
                                    </div>
                                    <div class="room-content">
                                        <h5 class="room-name"><?php echo htmlspecialchars($room['room_name']); ?></h5>
                                        <div class="text-muted small mb-3"><i class="far fa-user"></i>
                                            <?php echo htmlspecialchars($room['capacity']); ?> | <i class="far fa-bed"></i>
                                            <?php echo htmlspecialchars($room['bed_type']); ?></div>
                                        <?php foreach ($features as $f): ?>
                                            <div class="small mb-1"><i
                                                    class="fas fa-check text-success me-2"></i><?php echo htmlspecialchars($f); ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="room-price-box d-flex flex-column justify-content-center align-items-center">
                                        <div class="fw-bold fs-4">₹<?php echo number_format($room['room_price']); ?></div>
                                        <div class="text-success small mb-3">+Taxes</div>
                                        <button class="btn btn-warning w-100 fw-bold"
                                            onclick="openBookingModal('<?php echo addslashes($room['room_name']); ?>', <?php echo $room['room_price']; ?>)">RESERVE</button>
                                    </div>
                                </div>
                            <?php endwhile; else: ?>
                            <div class="p-4 text-center border rounded bg-light">
                                <i class="fas fa-bed fa-3x text-muted mb-3 d-block"></i>
                                <p class="text-muted">No specific room types available. Please contact support for booking.
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="detail-card bg-light p-4 sticky-top" style="top: 150px;">
                        <h4 class="fw-bold mb-3">Support</h4>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle p-2 me-3"><i class="fas fa-phone-alt"></i>
                            </div>
                            <div>
                                <div class="small text-muted">Call</div>
                                <div class="fw-bold">+91 8373996644</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-success text-white rounded-circle p-2 me-3"><i class="fab fa-whatsapp"></i>
                            </div>
                            <div>
                                <div class="small text-muted">WhatsApp</div>
                                <div class="fw-bold">Contact Support</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 rounded-4 shadow overflow-hidden">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold text-white">Book Your Stay</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="mb-3 p-2 bg-light rounded-3 border-start border-warning border-4">
                        <div id="sumRoomName" class="fw-bold text-dark small mb-0"></div>
                        <div class="small text-muted" style="font-size: 11px;">Base Price: ₹<span id="sumBasePrice"></span></div>
                    </div>
                    <form id="bookingQueryForm">
                        <input type="hidden" name="form_type" value="hotel">
                        <input type="hidden" name="hotel_id" value="<?php echo $hotel['id']; ?>">
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($hotel['name']); ?>">
                        <input type="hidden" name="room_type" id="inputRoomType">
                        <input type="hidden" name="booking_type" value="Booking">

                        <div class="mb-2"><label class="small fw-bold mb-1">Full Name</label><input type="text" name="name"
                                 class="form-control form-control-sm rounded-3" value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>" placeholder="Enter Full Name" required></div>
                        <div class="row g-2">
                            <div class="col-6 mb-2"><label class="small fw-bold mb-1">Email</label><input type="email"
                                    name="email" class="form-control form-control-sm rounded-3" value="<?php echo htmlspecialchars($_SESSION['user_email'] ?? $_GET['email'] ?? ''); ?>" placeholder="example@mail.com" required></div>
                            <div class="col-6 mb-2"><label class="small fw-bold mb-1">Mobile</label><input type="tel" name="phone" class="form-control form-control-sm rounded-3" value="<?php 
                                $mob = $_SESSION['user_phone'] ?? $_GET['mobile'] ?? '';
                                echo htmlspecialchars($mob); 
                            ?>" placeholder="10 Digit Mobile" required pattern="[6-9][0-9]{9}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');" title="Please enter a valid 10-digit mobile number starting with 6-9"></div>
                        </div>
                        <div class="row g-2">
                            <div class="col-6 mb-2"><label class="small fw-bold mb-1">Check-in</label><input type="date"
                                    name="check_in" id="modalCheckIn" class="form-control form-control-sm rounded-3"
                                    min="<?php echo date('Y-m-d'); ?>"
                                    value="<?php 
                                        $ci_raw = $_GET['checkin'] ?? '';
                                        $ci_time = strtotime($ci_raw);
                                        if (!$ci_time || date('Y', $ci_time) > 2030 || date('Y', $ci_time) < 2024) $ci_time = time();
                                        echo date('Y-m-d', $ci_time); 
                                    ?>"
                                    required></div>
                            <div class="col-6 mb-2"><label class="small fw-bold mb-1">Check-out</label><input type="date"
                                    name="check_out" id="modalCheckOut" class="form-control form-control-sm rounded-3"
                                    min="<?php echo date('Y-m-d'); ?>"
                                    value="<?php 
                                        $co_raw = $_GET['checkout'] ?? '';
                                        $co_time = strtotime($co_raw);
                                        if (!$co_time || $co_time <= $ci_time || date('Y', $co_time) > 2030) $co_time = strtotime('+1 day', $ci_time);
                                        echo date('Y-m-d', $co_time); 
                                    ?>"
                                    required></div>
                        </div>

                        <!-- Unified Guest Picker -->
                        <div class="mb-2 position-relative">
                            <label class="small fw-bold mb-1">Guests & Rooms</label>
                            <div class="dropdown">
                                <button class="form-control form-control-sm rounded-3 text-start d-flex justify-content-between align-items-center" type="button" id="guestPickerBtn" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                    <span id="guestSummaryText">1 Room, 2 Adults</span>
                                    <i class="fas fa-chevron-down small text-muted"></i>
                                </button>
                                <div class="dropdown-menu p-3 shadow-lg border-0 rounded-3" style="width: 250px;">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="small fw-bold text-dark">Rooms</div>
                                        <div class="d-flex align-items-center gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle p-0" style="width:24px;height:24px;" onclick="changeCount('rooms', -1)">-</button>
                                            <span id="count_rooms" class="fw-bold px-1" style="min-width:15px;text-align:center;">1</span>
                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle p-0" style="width:24px;height:24px;" onclick="changeCount('rooms', 1)">+</button>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="small fw-bold text-dark">Adults</div>
                                        <div class="d-flex align-items-center gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle p-0" style="width:24px;height:24px;" onclick="changeCount('adults', -1)">-</button>
                                            <span id="count_adults" class="fw-bold px-1" style="min-width:15px;text-align:center;">2</span>
                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle p-0" style="width:24px;height:24px;" onclick="changeCount('adults', 1)">+</button>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="small fw-bold text-dark">Children</div>
                                        <div class="d-flex align-items-center gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle p-0" style="width:24px;height:24px;" onclick="changeCount('children', -1)">-</button>
                                            <span id="count_children" class="fw-bold px-1" style="min-width:15px;text-align:center;">0</span>
                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle p-0" style="width:24px;height:24px;" onclick="changeCount('children', 1)">+</button>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="small fw-bold text-dark">Infants</div>
                                        <div class="d-flex align-items-center gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle p-0" style="width:24px;height:24px;" onclick="changeCount('infants', -1)">-</button>
                                            <span id="count_infants" class="fw-bold px-1" style="min-width:15px;text-align:center;">0</span>
                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle p-0" style="width:24px;height:24px;" onclick="changeCount('infants', 1)">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-primary bg-opacity-10 p-2 px-3 rounded-3 mb-2 border border-primary border-opacity-25 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small text-primary fw-bold" style="font-size: 10px;">Estimated Total</div>
                                <div class="fw-bold mb-0 text-dark">₹<span id="displayTotalPrice">0</span></div>
                            </div>
                            <input type="hidden" name="price" id="inputTotalPrice">
                            <input type="hidden" name="guests" id="inputGuests">
                        </div>
                        <button type="submit"
                            class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm mt-2">CONFIRM BOOKING</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const isLoggedIn = <?php echo is_logged_in() ? 'true' : 'false'; ?>;

        $(document).ready(function () {
            if ($('.hotel-main-slider').length) {
                $('.hotel-main-slider').slick({
                    dots: true,
                    infinite: true,
                    fade: true,
                    autoplay: true,
                    autoplaySpeed: 3000,
                    arrows: true
                });
            }

            // Auto-open modal if returning from login
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('reserve_room') && isLoggedIn) {
                openBookingModal(urlParams.get('reserve_room'), urlParams.get('reserve_price'));
                // Clean up URL without refreshing
                const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?' + 
                                 window.location.search.replace(/&?reserve_room=[^&]*/, '').replace(/&?reserve_price=[^&]*/, '');
                window.history.replaceState({path: cleanUrl}, '', cleanUrl);
            }
        });

        let bookingState = {
            rooms: 1,
            adults: 2,
            children: 0,
            infants: 0
        };

        function changeCount(key, val) {
            let newVal = bookingState[key] + val;
            if (key === 'rooms' && (newVal < 1 || newVal > 10)) return;
            if (key === 'adults' && (newVal < 1 || newVal > 20)) return;
            if (key === 'children' && (newVal < 0 || newVal > 10)) return;
            if (key === 'infants' && (newVal < 0 || newVal > 5)) return;
            
            bookingState[key] = newVal;
            document.getElementById('count_' + key).innerText = newVal;
            updateModalPrice();
        }

        function updateModalPrice() {
            const cin = new Date(document.getElementById('modalCheckIn').value);
            const cout = new Date(document.getElementById('modalCheckOut').value);
            
            let nights = Math.ceil((cout - cin) / (1000 * 60 * 60 * 24));
            if (nights < 1) nights = 1;

            const total = currentBasePrice * bookingState.rooms * nights;
            document.getElementById('displayTotalPrice').innerText = total.toLocaleString();
            document.getElementById('inputTotalPrice').value = total;
            
            // Format summary text
            let summary = `${bookingState.rooms} Room${bookingState.rooms>1?'s':''}, ${bookingState.adults} Adult${bookingState.adults>1?'s':''}`;
            document.getElementById('guestSummaryText').innerText = summary;
            
            // Format full guest string for DB
            let guestStr = summary;
            if(bookingState.children > 0) guestStr += `, ${bookingState.children} Child${bookingState.children!=1?'ren':''}`;
            if(bookingState.infants > 0) guestStr += `, ${bookingState.infants} Infant${bookingState.infants!=1?'s':''}`;
            document.getElementById('inputGuests').value = guestStr;
        }

        // Add listeners
        ['modalCheckIn', 'modalCheckOut'].forEach(id => {
            const el = document.getElementById(id);
            if(el) el.addEventListener('change', updateModalPrice);
        });

        function openBookingModal(name, price) {
            if (!isLoggedIn) {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('reserve_room', name);
                currentUrl.searchParams.set('reserve_price', price);
                window.location.href = 'login-user.php?return_url=' + encodeURIComponent(currentUrl.toString());
                return;
            }
            // Robust parsing: handle strings with commas or raw numbers
            currentBasePrice = typeof price === 'string' ? parseInt(price.replace(/,/g, '')) : parseInt(price);
            
            document.getElementById('sumRoomName').innerText = name;
            document.getElementById('sumBasePrice').innerText = currentBasePrice.toLocaleString();
            document.getElementById('inputRoomType').value = name;
            
            updateModalPrice();
            new bootstrap.Modal(document.getElementById('bookingModal')).show();
        }

        document.getElementById('bookingQueryForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const btn = e.target.querySelector('button');
            btn.innerText = 'Processing...';
            btn.disabled = true;

            fetch('submit.php', { method: 'POST', body: new FormData(this) })
                .then(r => r.json()).then(data => {
                    btn.innerText = 'CONFIRM BOOKING'; btn.disabled = false;
                    if (data.status === 'success') {
                        Swal.fire({ icon: 'success', title: 'Request Sent!' });
                        bootstrap.Modal.getInstance(document.getElementById('bookingModal')).hide();
                    } else Swal.fire({ icon: 'error', title: 'Error', text: data.message });
                });
        });
    </script>
</body>

</html>