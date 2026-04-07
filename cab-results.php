<?php
include_once 'db.php';
include_once 'auth.php';

// Get Search Parameters
$from = isset($_GET['from']) ? $_GET['from'] : 'Delhi';
$to = isset($_GET['to']) ? $_GET['to'] : 'Airport';
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$time = isset($_GET['time']) ? $_GET['time'] : '12:00 PM';
$tripType = isset($_GET['tripType']) ? $_GET['tripType'] : 'Airport Transfer';
$mobile = isset($_GET['mobile']) ? $_GET['mobile'] : '';
$pickup_type = isset($_GET['pickup']) ? $_GET['pickup'] : 'One Way';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Your Cab | TravoLo</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.png" type="image/png">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!--====== FontAwesome css ======-->
    <link rel="stylesheet" href="assets/fonts/fontawesome/css/all.min.css">
    <!--====== Bootstrap css ======-->
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <!--====== Style css ======-->
    <link rel="stylesheet" href="assets/css/style.css">
    <!--====== SweetAlert2 ======-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --travolo-teal: #00a79d;
            --travolo-dark: #133a25;
            --travolo-orange: #f7921e;
        }

        body {
            background-color: #f4f7f6;
            font-family: 'Outfit', sans-serif;
        }

        .results-header-box {
            background: #fff;
            padding: 15px 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .search-info-pill {
            background: #f8f9fa;
            border: 1.5px solid #eee;
            padding: 8px 20px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 15px;
            font-size: 14px;
            font-weight: 600;
            color: #444;
        }

        .search-info-pill i { color: var(--travolo-teal); }

        .modify-search-btn {
            background: var(--travolo-orange);
            color: #fff;
            border: none;
            padding: 8px 25px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 14px;
            transition: 0.3s;
        }

        .modify-search-btn:hover { background: #e07d0d; color: #fff; }

        /* Car Result Card - Premium Row Style */
        .car-result-card {
            background: #fff;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1.5px solid #eee;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        }

        .car-result-card:hover { 
            border-color: var(--travolo-teal); 
            box-shadow: 0 10px 30px rgba(0,0,0,0.08); 
        }

        .car-image-box {
            width: 240px;
            min-width: 240px;
            height: 150px;
            background: #fdfdfd;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
        }

        .car-image-box img { max-width: 100%; max-height: 100%; object-fit: contain; }

        .car-detail-main { flex-grow: 1; }

        .car-category {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            color: #fff;
            background: #999;
            padding: 2px 10px;
            border-radius: 4px;
            margin-bottom: 8px;
            display: inline-block;
        }

        .category-sedan { background: #4dabf7; }
        .category-suv { background: #51cf66; }
        .category-luxury { background: #cc5de8; }
        .category-hatchback { background: #ff922b; }

        .car-name-title { font-size: 24px; font-weight: 800; color: var(--travolo-dark); margin-bottom: 12px; }

        .car-features-icons { display: flex; gap: 20px; margin-bottom: 15px; flex-wrap: wrap; }

        .feature-icon-item { display: flex; align-items: center; gap: 7px; font-size: 13px; font-weight: 600; color: #666; }
        .feature-icon-item i { font-size: 14px; color: var(--travolo-teal); }

        .car-price-section {
            text-align: right;
            border-left: 1px dashed #ddd;
            padding-left: 35px;
            min-width: 220px;
        }

        .car-price-tag { font-size: 34px; font-weight: 900; color: var(--travolo-dark); margin-bottom: 5px; }
        .car-price-unit { font-size: 13px; color: #888; display: block; margin-bottom: 15px; }

        .book-now-premium {
            background: var(--travolo-teal);
            color: #fff;
            border: none;
            padding: 12px 0;
            width: 100%;
            border-radius: 12px;
            font-weight: 800;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
            box-shadow: 0 4px 10px rgba(0, 167, 157, 0.2);
        }

        .book-now-premium:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 8px 20px rgba(0, 167, 157, 0.3); 
            color: #fff; 
            background: #008981;
        }
            font-weight: 800;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
        }

        .book-now-premium:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0, 167, 157, 0.3); color: #fff; }

        /* Mobile Specific */
        @media (max-width: 767px) {
            .car-result-card { flex-direction: column; text-align: center; gap: 20px; }
            .car-price-section { border-left: none; border-top: 1px dashed #ddd; padding-left: 0; padding-top: 20px; width: 100%; text-align: center; }
            .car-features-icons { justify-content: center; }
            .search-info-pill { width: 100%; justify-content: center; font-size: 12px; height: auto; padding: 10px; }
        }
    </style>
</head>
<body>

    <?php include_once 'navbar.php'; ?>

    <!-- Results Selection Control -->
    <div class="results-header-box">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="search-info-pill">
                    <div><i class="fas fa-map-marker-alt"></i> <span><?php echo htmlspecialchars($from); ?></span></div>
                    <i class="fas fa-arrow-right text-muted" style="font-size: 10px;"></i>
                    <div><i class="fas fa-map-pin"></i> <span><?php echo htmlspecialchars($to); ?></span></div>
                    <div class="ms-3 ps-3 border-start">
                        <?php if($tripType === 'Hourly'): ?>
                            <i class="far fa-clock"></i> <span>Hourly Package (8h/80km)</span>
                        <?php else: ?>
                            <i class="fas fa-calendar-alt"></i> <span><?php echo htmlspecialchars($date); ?> | <?php echo htmlspecialchars($time); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <button class="modify-search-btn" onclick="window.history.back()"><i class="fas fa-pencil-alt me-2"></i> Modify Search</button>
            </div>
        </div>
    </div>

    <div class="container pb-100">
        <div class="row">
            <div class="col-lg-12">
                <div class="mb-4 d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold mb-0">Cabs available for your route (<?php echo htmlspecialchars($from); ?>)</h5>
                    <div class="text-muted small">Prices include taxes and tolls (estimated)</div>
                </div>

                <div class="col-12">
                <?php
                $price_col = 'base_price';
                if ($tripType === 'Hourly') $price_col = 'hourly_price';
                elseif ($tripType === 'Airport Transfer' || $to === 'Airport' || $from === 'Airport') $price_col = 'airport_price';
                elseif ($tripType === 'Outstation') $price_col = 'outstation_price';

                $cabs_res = $conn->query("SELECT *, $price_col as display_price FROM cab_inventory WHERE status = 1 ORDER BY display_price ASC");
                if ($cabs_res && $cabs_res->num_rows > 0) {
                    while ($cab = $cabs_res->fetch_assoc()) {
                        $display_price = ($cab['display_price'] > 0) ? $cab['display_price'] : $cab['base_price'];
                        $cat_class = 'category-' . strtolower($cab['category']);
                        ?>
                        <div class="car-result-card wow fadeInUp">
                            <div class="car-image-box">
                                <img src="https://placehold.co/400x250/f4f7f6/133a25?text=<?php echo urlencode($cab['car_name']); ?>" alt="<?php echo htmlspecialchars($cab['car_name']); ?>">
                            </div>
                            <div class="car-detail-main">
                                <span class="car-category <?php echo $cat_class; ?>"><?php echo htmlspecialchars($cab['category']); ?></span>
                                <h3 class="car-name-title"><?php echo htmlspecialchars($cab['car_name']); ?></h3>
                                <div class="car-features-icons">
                                    <div class="feature-icon-item" title="Capacity"><i class="fas fa-users"></i> <?php echo $cab['capacity']; ?> People</div>
                                    <div class="feature-icon-item" title="Luggage"><i class="fas fa-briefcase"></i> <?php echo $cab['luggage']; ?> Bags</div>
                                    <div class="feature-icon-item"><i class="fas fa-snowflake"></i> AC</div>
                                    <div class="feature-icon-item"><i class="fas fa-shield-alt"></i> Safe journey</div>
                                </div>
                                <div class="text-success small fw-bold"><i class="fas fa-check-circle me-1"></i> Refundable fare | Free Cancellation</div>
                            </div>
                            <div class="car-price-section">
                                <div class="car-price-tag">₹<?php echo number_format($display_price); ?></div>
                                <span class="car-price-unit"><?php echo ($tripType === 'Hourly') ? 'for 8 hrs / 80 km' : 'all inclusive fare'; ?></span>
                                <button class="book-now-premium" onclick="bookCab(<?php echo $cab['id']; ?>)">Book Now</button>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<div class='alert alert-info py-4 text-center'>No cabs available for this route currently. Please try another selection.</div>";
                }
                ?>
                </div>
            </div>
        </div>
    </div>

    <?php include_once 'footer.php'; ?>

    <script>
        function bookCab(id) {
            Swal.fire({
                title: 'Confirm Booking',
                text: 'Proceed to finalize your booking for this cab?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#00a79d',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Proceed!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'submit.php?action=book_cab&cab_id=' + id + 
                        '&from=<?php echo urlencode($from); ?>' +
                        '&to=<?php echo urlencode($to); ?>' +
                        '&date=<?php echo urlencode($date); ?>' +
                        '&time=<?php echo urlencode($time); ?>' +
                        '&tripType=<?php echo urlencode($tripType); ?>' +
                        '&pickup=<?php echo urlencode($pickup_type); ?>' +
                        '&mobile=<?php echo urlencode($mobile); ?>';
                }
            })
        }
    </script>
</body>
</html>
