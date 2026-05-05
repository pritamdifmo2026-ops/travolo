<?php
include_once 'includes/db.php';
include_once 'includes/auth.php';

// Get Search Parameters
$from = trim(isset($_GET['from']) ? $_GET['from'] : 'Delhi');
$to = trim(isset($_GET['to']) ? $_GET['to'] : 'Airport');
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$time = isset($_GET['time']) ? $_GET['time'] : '12:00 PM';
$tripType = trim(isset($_GET['tripType']) ? $_GET['tripType'] : 'Transfer');
$mobile = $_SESSION['user_phone'] ?? ($_GET['mobile'] ?? '');
$pickup_type = isset($_GET['pickup']) ? $_GET['pickup'] : 'One Way';
$duration = trim(isset($_GET['duration']) && $_GET['duration'] !== '' ? $_GET['duration'] : '8hrs / 80km');

// Serviceability Check
$serviceable = false;
$city_pack = null;
if ($tripType === 'Transfer' || $tripType === 'Airport Transfer') {
    // Prioritize City Match first, then Airport
    $q = $conn->query("SELECT * FROM cab_transfers WHERE status = 1 AND (
        city = '$from' OR city = '$to'
    ) LIMIT 1");
    
    if (!$q || $q->num_rows == 0) {
        $q = $conn->query("SELECT * FROM cab_transfers WHERE status = 1 AND (
            city LIKE '%$from%' OR '$from' LIKE CONCAT('%', city, '%') OR
            city LIKE '%$to%' OR '$to' LIKE CONCAT('%', city, '%') OR
            airport LIKE '%$from%' OR '$from' LIKE CONCAT('%', airport, '%') OR
            airport LIKE '%$to%' OR '$to' LIKE CONCAT('%', airport, '%')
        ) LIMIT 1");
    }
    if ($q && $q->num_rows > 0) { $serviceable = true; $city_pack = $q->fetch_assoc(); }
} elseif ($tripType === 'Outstation') {
    $q = $conn->query("SELECT * FROM cab_outstation WHERE status = 1 AND (
        city = '$from' OR city LIKE '%$from%' OR '$from' LIKE CONCAT('%', city, '%') OR
        destinations LIKE '%$to%' OR '$to' LIKE CONCAT('%', destinations, '%')
    ) LIMIT 1");
    if ($q && $q->num_rows > 0) { $serviceable = true; $city_pack = $q->fetch_assoc(); }
} elseif ($tripType === 'Hourly') {
    $q = $conn->query("SELECT * FROM cab_hourly WHERE status = 1 AND (
        city = '$from' OR city LIKE '%$from%' OR '$from' LIKE CONCAT('%', city, '%') OR
        location_tag LIKE '%$from%' OR '$from' LIKE CONCAT('%', location_tag, '%')
    ) LIMIT 1");
    if ($q && $q->num_rows > 0) { $serviceable = true; $city_pack = $q->fetch_assoc(); }
} else {
    $serviceable = true; 
}
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
            min-width: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .car-price-tag { 
            font-size: 32px; 
            font-weight: 900; 
            color: #111; 
            margin-bottom: 0px; 
            line-height: 1.2;
        }

        .select-car-btn {
            background: #00a79d;
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
            margin-top: 10px;
            cursor: pointer;
        }

        .select-car-btn:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 8px 20px rgba(0, 167, 157, 0.3); 
            color: #fff; 
            background: #008981;
        }

        /* City Pack Banner */
        .city-pack-banner {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            border: 1.5px solid #eee;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
        }

        .city-img-side { width: 30%; height: 180px; object-fit: cover; }
        .city-info-side { padding: 30px; flex-grow: 1; }
        .city-info-side h2 { font-size: 28px; font-weight: 800; color: var(--travolo-dark); margin-bottom: 5px; }
        .city-info-side p { color: #777; margin-bottom: 0; font-size: 15px; }
        .featured-badge { background: var(--travolo-orange); color: #fff; font-size: 10px; font-weight: 800; padding: 4px 12px; border-radius: 50px; text-transform: uppercase; margin-bottom: 15px; display: inline-block; }

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

    <?php include_once 'includes/navbar.php'; ?>

    <!-- Inline Search Control (EaseMyTrip Style) -->
    <div class="results-header-box" style="background: linear-gradient(90deg, #1aa39a, #158b83); padding: 15px 0;">
        <div class="container">
            <form action="cab-results.php" method="GET" id="inlineSearchForm" class="row g-2 align-items-end">
                <!-- Trip Type Radio Buttons -->
                <div class="col-12 text-white mb-2 d-flex flex-wrap gap-4 align-items-center" style="font-size: 12px; font-weight: 700;">
                    <label class="d-flex align-items-center gap-1 cursor-pointer" style="cursor: pointer;">
                        <input type="radio" name="tripType" value="Transfer" <?php if($tripType === 'Transfer' || $tripType === 'Airport Transfer') echo 'checked'; ?> onchange="document.getElementById('inlineSearchForm').submit();">
                        AIRPORT TRANSFER
                    </label>
                    <label class="d-flex align-items-center gap-1 cursor-pointer" style="cursor: pointer;">
                        <input type="radio" name="tripType" value="Outstation" <?php if($tripType === 'Outstation') echo 'checked'; ?> onchange="document.getElementById('inlineSearchForm').submit();">
                        OUTSTATION/OTHER
                    </label>
                    <label class="d-flex align-items-center gap-1 cursor-pointer" style="cursor: pointer;">
                        <input type="radio" name="tripType" value="Hourly" <?php if($tripType === 'Hourly') echo 'checked'; ?> onchange="document.getElementById('inlineSearchForm').submit();">
                        HOURLY
                    </label>
                </div>

                <!-- Input Fields row -->
                <?php if($tripType === 'Hourly'): ?>
                    <div class="col-md-5">
                        <input type="text" name="from" class="form-control rounded-1 border-0 py-2" value="<?php echo htmlspecialchars($from); ?>" placeholder="Enter Pick-up Location" required>
                    </div>
                    <div class="col-md-3">
                        <label class="text-white small fw-bold mb-1 d-block" style="font-size: 10px;">PICK-UP DATE & TIME</label>
                        <div class="d-flex gap-1">
                            <input type="date" name="date" class="form-control rounded-1 border-0 py-2 px-2" value="<?php echo htmlspecialchars($date); ?>" required>
                            <input type="time" name="time" class="form-control rounded-1 border-0 py-2 px-2" value="<?php echo date('H:i', strtotime($time)); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="text-white small fw-bold mb-1 d-block" style="font-size: 10px;">RENT FOR</label>
                        <style>
                            /* Cache-busting fix: force hide the plugin's fake dropdown and force show our native select */
                            #durationForm .nice-select { display: none !important; }
                        </style>
                        <select name="duration" class="form-select rounded-1 border-0 py-2 fw-bold text-dark ignore-nice-select" style="display: block !important;">
                            <?php
                            $p_res = $conn->query("SELECT * FROM cab_packages WHERE status=1 ORDER BY hours ASC");
                            if ($p_res && $p_res->num_rows > 0) {
                                while ($p = $p_res->fetch_assoc()) {
                                    $sel = ($duration == $p['package_name']) ? 'selected' : '';
                                    echo "<option value='{$p['package_name']}' $sel>{$p['package_name']}</option>";
                                }
                            } else {
                                echo "<option value='4 hrs / 40 km' ".(strpos($duration, '4')!==false ? 'selected' : '').">4 hrs / 40 km</option>";
                                echo "<option value='8 hrs / 80 km' ".(strpos($duration, '8')!==false ? 'selected' : '').">8 hrs / 80 km</option>";
                                echo "<option value='12 hrs / 120 km' ".(strpos($duration, '12')!==false ? 'selected' : '').">12 hrs / 120 km</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2 text-end">
                        <button type="submit" class="btn rounded-pill text-white fw-bold px-4 py-2 border border-white" style="background: transparent; margin-top: 15px;">SEARCH</button>
                    </div>
                <?php else: ?>
                    <div class="col-md-3">
                        <label class="text-white small fw-bold mb-1 d-block" style="font-size: 10px;">FROM</label>
                        <input type="text" name="from" class="form-control rounded-1 border-0 py-2" value="<?php echo htmlspecialchars($from); ?>" placeholder="Pick-up City/Airport" required>
                    </div>
                    <div class="col-md-3">
                        <label class="text-white small fw-bold mb-1 d-block" style="font-size: 10px;">TO</label>
                        <input type="text" name="to" class="form-control rounded-1 border-0 py-2" value="<?php echo htmlspecialchars($to); ?>" placeholder="Drop City/Airport" required>
                    </div>
                    <div class="col-md-4">
                        <label class="text-white small fw-bold mb-1 d-block" style="font-size: 10px;">PICK-UP DATE & TIME</label>
                        <div class="d-flex gap-1">
                            <input type="date" name="date" class="form-control rounded-1 border-0 py-2 px-2" value="<?php echo htmlspecialchars($date); ?>" required>
                            <input type="time" name="time" class="form-control rounded-1 border-0 py-2 px-2" value="<?php echo date('H:i', strtotime($time)); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-2 text-end">
                        <button type="submit" class="btn rounded-pill text-white fw-bold px-4 py-2 border border-white" style="background: transparent; margin-top: 15px;">SEARCH</button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="container pb-100">
        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-lg-3 d-none d-lg-block">
                <div class="checkout-box p-4" style="background: #fff; border-radius: 16px; border: 1px solid #eee; position: sticky; top: 100px;">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-bold mb-0">Filters</h6>
                        <a href="cab-results.php?from=<?php echo urlencode($from); ?>&to=<?php echo urlencode($to); ?>&tripType=<?php echo urlencode($tripType); ?>" class="text-primary small text-decoration-none">Reset</a>
                    </div>

                    <div class="filter-section mb-4">
                        <p class="small fw-bold text-muted mb-3">CAB TYPE</p>
                        <?php
                        $types = ['Hatchback', 'Sedan', 'SUV', 'Luxury'];
                        foreach ($types as $t) {
                            echo "<div class='form-check mb-2'>
                                    <input class='form-check-input' type='checkbox' value='$t' id='type$t' onchange='filterCabs()'>
                                    <label class='form-check-label small fw-semibold' for='type$t'>$t</label>
                                  </div>";
                        }
                        ?>
                    </div>

                    <div class="filter-section">
                        <p class="small fw-bold text-muted mb-3">FUEL TYPE</p>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" value="CNG" id="fuelCNG" onchange="filterCabs()">
                            <label class="form-check-label small fw-semibold" for="fuelCNG">CNG</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" value="Petrol" id="fuelPetrol" onchange="filterCabs()">
                            <label class="form-check-label small fw-semibold" for="fuelPetrol">Petrol</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" value="Diesel" id="fuelDiesel" onchange="filterCabs()">
                            <label class="form-check-label small fw-semibold" for="fuelDiesel">Diesel</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Section -->
            <div class="col-lg-9">
                <div class="mb-4 d-flex align-items-center justify-content-between">
                    <?php
                    $target_cab_id = isset($_GET['cab_id']) ? intval($_GET['cab_id']) : 0;
                    $header_text = "Cabs available from " . htmlspecialchars($from);
                    ?>
                    <div>
                        <h4 class="fw-bold mb-1"><?php echo $header_text; ?></h4>
                        <p class="text-muted small mb-0"><i class="fas fa-info-circle me-1"></i> All prices include GST and estimated Tolls</p>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-white text-dark border p-2 small fw-normal"><i class="fas fa-sort-amount-down me-1"></i> Price: Low to High</span>
                    </div>
                </div>

                <div class="results-container">
                <?php
                if (!$serviceable) {
                    echo "<div class='text-center py-5 bg-white border-dashed rounded-3 shadow-sm'>
                            <i class='fas fa-map-marked-alt fa-3x mb-3 text-muted opacity-50'></i>
                            <h4 class='fw-bold'>Service Not Available Yet</h4>
                            <p class='text-muted px-5'>Sorry, but we currently do not provide $tripType services for <b>".htmlspecialchars($from)."</b>. Please try another city or contact us for personalized support.</p>
                            <a href='cab-booking.php' class='btn btn-outline-primary rounded-pill mt-3'>Try Another City</a>
                          </div>";
                } else {
                    $price_col = 'base_price';
                    if ($tripType === 'Hourly') $price_col = 'hourly_price';
                    elseif ($tripType === 'Airport Transfer' || $to === 'Airport' || $from === 'Airport') $price_col = 'airport_price';
                    elseif ($tripType === 'Outstation') $price_col = 'outstation_price';

                    $cab_filter = "";
                    if ($target_cab_id > 0) {
                        $cab_filter = " AND id = $target_cab_id";
                    }

                    $city_name = $city_pack['city'] ?? $from;
                    $city_filter = " AND (city_name = '$city_name' OR city_name = 'All Cities')";
                    
                    $cabs_res = $conn->query("SELECT *, $price_col as display_price FROM cab_inventory WHERE status = 1 $city_filter $cab_filter ORDER BY display_price ASC");
                    
                    if ($cabs_res && $cabs_res->num_rows > 0) {
                        while ($cab = $cabs_res->fetch_assoc()) {
                            $display_price = ($cab['display_price'] > 0) ? $cab['display_price'] : $cab['base_price'];
                            
                            if ($tripType === 'Hourly') {
                                $selected_hours = intval($duration);
                                if ($selected_hours > 0) {
                                    $price_per_hour = $display_price / 8;
                                    $display_price = round($price_per_hour * $selected_hours);
                                }
                            }

                            $cat_class = 'category-' . strtolower($cab['category']);
                            $fuel = (strpos($cab['car_name'], 'Maruti') !== false || strpos($cab['category'], 'Hatchback') !== false) ? 'CNG/Petrol' : 'Diesel';
                            ?>
                            <div class="car-result-card cab-item" data-category="<?php echo $cab['category']; ?>" data-fuel="<?php echo $fuel; ?>">
                                <div class="car-image-box">
                                    <?php 
                                        $car_img = $cab['image_path'];
                                        // Stronger check to avoid watch images or empty paths
                                        if (empty($car_img) || stripos($car_img, 'watch') !== false || stripos($car_img, 'product') !== false) {
                                            $car_img = 'assets/images/cab-placeholder.png';
                                        }
                                    ?>
                                    <img src="<?php echo $car_img; ?>" alt="<?php echo htmlspecialchars($cab['car_name']); ?>">
                                </div>
                                <div class="car-detail-main">
                                    <span class="car-category <?php echo $cat_class; ?>"><?php echo htmlspecialchars($cab['category']); ?></span>
                                    <h3 class="car-name-title mb-1"><?php echo htmlspecialchars($cab['car_name']); ?> <small class="text-muted fw-normal" style="font-size: 14px;">or Equivalent</small></h3>
                                    
                                    <div class="car-features-icons">
                                        <div class="feature-icon-item"><i class="fas fa-users"></i> <?php echo $cab['capacity']; ?> Seats</div>
                                        <div class="feature-icon-item"><i class="fas fa-briefcase"></i> <?php echo $cab['luggage']; ?> Bags</div>
                                        <div class="feature-icon-item"><i class="fas fa-snowflake"></i> AC</div>
                                        <div class="feature-icon-item"><i class="fas fa-gas-pump"></i> <?php echo $fuel; ?></div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="badge bg-success-subtle text-success small border border-success-subtle px-2"><i class="fas fa-check me-1"></i> Free Cancellation</span>
                                            <span class="badge bg-primary-subtle text-primary small border border-primary-subtle px-2"><i class="fas fa-bolt me-1"></i> Instant Confirmation</span>
                                        </div>
                                        <p class="small text-muted mb-0"><i class="fas fa-shield-alt text-warning me-1"></i> Safety Guaranteed Chauffeurs</p>
                                    </div>
                                </div>
                                <div class="car-price-section">
                                    <div class="text-muted small mb-0 fw-bold" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Total Fare</div>
                                    <div class="car-price-tag">₹<?php echo number_format($display_price); ?></div>
                                    <div class="text-muted small mb-3" style="font-size: 10px;">All Inclusive (GST, Tolls)</div>
                                    
                                    <button class="select-car-btn w-100" onclick="bookCab(<?php echo $cab['id']; ?>)">
                                        SELECT CAB <i class="fas fa-chevron-right ms-1" style="font-size: 10px;"></i>
                                    </button>
                                    
                                    <div class="mt-2 text-center">
                                        <a href="javascript:void(0)" class="text-primary text-decoration-none fw-bold" style="font-size: 12px;" onclick="alert('Price breakdown:\nBase Fare: ₹<?php echo number_format($display_price - 150); ?>\nService Fee: ₹150\nTaxes: Included\n\nNo Hidden Charges!')">
                                            <i class="fas fa-info-circle me-1"></i> Fare Breakup
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<div class='alert alert-info py-5 text-center bg-white rounded-4 border-0 shadow-sm'>
                                <i class='fas fa-search fa-2x mb-3 opacity-20'></i>
                                <h5 class='fw-bold'>No Cabs Found</h5>
                                <p class='text-muted'>We couldn't find any cabs matching your current filters. Try resetting them.</p>
                                <button class='btn btn-primary rounded-pill px-4 mt-2' onclick='location.reload()'>Reset Filters</button>
                              </div>";
                    }
                }
                ?>
                </div>
            </div>
        </div>
    </div>

    <?php include_once 'includes/footer.php'; ?>



    <script>
        function filterCabs() {
            const selectedTypes = Array.from(document.querySelectorAll('input[id^="type"]:checked')).map(el => el.value);
            const selectedFuels = Array.from(document.querySelectorAll('input[id^="fuel"]:checked')).map(el => el.value);
            
            document.querySelectorAll('.cab-item').forEach(item => {
                const category = item.dataset.category;
                const fuel = item.dataset.fuel;
                
                const typeMatch = selectedTypes.length === 0 || selectedTypes.includes(category);
                const fuelMatch = selectedFuels.length === 0 || selectedFuels.some(f => fuel.includes(f));
                
                if (typeMatch && fuelMatch) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function bookCab(id) {
            const params = new URLSearchParams();
            params.append('cab_id', id);
            params.append('from', '<?php echo addslashes($from); ?>');
            params.append('to', '<?php echo addslashes($to); ?>');
            params.append('date', '<?php echo addslashes($date); ?>');
            params.append('time', '<?php echo addslashes($time); ?>');
            params.append('tripType', '<?php echo addslashes($tripType); ?>');
            params.append('pickup', '<?php echo addslashes($pickup_type); ?>');
            params.append('duration', '<?php echo addslashes($duration); ?>');

            window.location.href = 'cab-checkout.php?' + params.toString();
        }
    </script>
</body>
</html>
