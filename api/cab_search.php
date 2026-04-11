<?php
include_once '../includes/db.php';
include_once '../includes/auth.php';

// Get Search Parameters from POST
$from = $conn->real_escape_string(trim(isset($_POST['from']) ? $_POST['from'] : ''));
$to = $conn->real_escape_string(trim(isset($_POST['to']) ? $_POST['to'] : ''));
$date = isset($_POST['pickup_date']) ? $_POST['pickup_date'] : date('Y-m-d');
$time = isset($_POST['pickup_time']) ? $_POST['pickup_time'] : '12:00 PM';
$tripType = trim(isset($_POST['tripType']) ? $_POST['tripType'] : 'Transfer');
$pickup_type = isset($_POST['pickupType']) ? $_POST['pickupType'] : 'One Way';
$mobile = $_SESSION['user_phone'] ?? ($_POST['mobile'] ?? '');

// Serviceability Check
$serviceable = false;
$city_pack = null;
if ($tripType === 'Transfer' || $tripType === 'Airport Transfer') {
    $q = $conn->query("SELECT * FROM cab_transfers WHERE (
        city LIKE '%$from%' OR '$from' LIKE CONCAT('%', city, '%') OR
        city LIKE '%$to%' OR '$to' LIKE CONCAT('%', city, '%') OR
        airport LIKE '%$from%' OR '$from' LIKE CONCAT('%', airport, '%') OR
        airport LIKE '%$to%' OR '$to' LIKE CONCAT('%', airport, '%')
    ) AND status = 1 LIMIT 1");
    if ($q && $q->num_rows > 0) { $serviceable = true; $city_pack = $q->fetch_assoc(); }
} elseif ($tripType === 'Outstation') {
    $q = $conn->query("SELECT * FROM cab_outstation WHERE (
        city LIKE '%$from%' OR '$from' LIKE CONCAT('%', city, '%') OR
        destinations LIKE '%$to%' OR '$to' LIKE CONCAT('%', destinations, '%')
    ) AND status = 1 LIMIT 1");
    if ($q && $q->num_rows > 0) { $serviceable = true; $city_pack = $q->fetch_assoc(); }
} elseif ($tripType === 'Hourly') {
    $q = $conn->query("SELECT * FROM cab_hourly WHERE (
        city LIKE '%$from%' OR '$from' LIKE CONCAT('%', city, '%') OR
        location_tag LIKE '%$from%' OR '$from' LIKE CONCAT('%', location_tag, '%') OR
        location_tag LIKE '%$to%' OR '$to' LIKE CONCAT('%', location_tag, '%')
    ) AND status = 1 LIMIT 1");
    if ($q && $q->num_rows > 0) { $serviceable = true; $city_pack = $q->fetch_assoc(); }
} else {
    $serviceable = true; 
}

if (!$serviceable) {
    $displayTripType = ($tripType === 'Transfer') ? 'Airport Transfer' : $tripType;
    echo "<div class='text-center py-5 bg-white border-dashed rounded-3 shadow-sm'>
            <i class='fas fa-map-marked-alt fa-3x mb-3 text-muted opacity-50'></i>
            <h4 class='fw-bold'>Service Not Available Yet</h4>
            <p class='text-muted px-5'>Sorry, but we currently do not provide $displayTripType services for <b>" . htmlspecialchars($from ?: $to) . "</b>. Please try another city or contact us for personalized support.</p>
          </div>";
    exit;
}

// Show City Pack Info if available
if ($city_pack) {
    $city_img = $city_pack['image_path'] ?? ($city_pack['thumbnail'] ?? 'assets/images/tour-3-550x590.jpg');
    $city_name = $city_pack['city'] ?? $from;
    $pack_desc = $city_pack['airport'] ?? ($city_pack['destinations'] ?? ($city_pack['location_tag'] ?? 'Serving your route'));
    ?>
    <style>
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
        .city-info-side h2 { font-size: 28px; font-weight: 800; color: #133a25; margin-bottom: 5px; }
        .city-info-side p { color: #777; margin-bottom: 0; font-size: 15px; }
        .featured-badge { background: #f7921e; color: #fff; font-size: 10px; font-weight: 800; padding: 4px 12px; border-radius: 50px; text-transform: uppercase; margin-bottom: 15px; display: inline-block; }
        
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
        .car-result-card:hover { border-color: #00a79d; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .car-image-box { width: 220px; min-width: 220px; height: 140px; display: flex; align-items: center; justify-content: center; background: #fff; border-radius: 12px; }
        .car-image-box img { max-width: 100%; max-height: 100%; object-fit: contain; }
        .car-detail-main { flex-grow: 1; }
        .car-name-title { font-size: 22px; font-weight: 800; color: #133a25; margin-bottom: 8px; }
        .car-price-section { text-align: right; border-left: 1px dashed #ddd; padding-left: 30px; min-width: 200px; }
        .car-price-tag { font-size: 28px; font-weight: 900; color: #133a25; }
        .book-now-premium { background: #00a79d; color: #fff; border: none; padding: 10px 25px; border-radius: 30px; font-weight: 800; margin-top: 10px; transition: 0.3s; width: 100%; }
        .book-now-premium:hover { background: #133a25; transform: translateY(-2px); }
        .car-category { font-size: 10px; font-weight: 800; text-transform: uppercase; color: #fff; background: #00a79d; padding: 2px 10px; border-radius: 4px; margin-bottom: 5px; display: inline-block; }
        @media (max-width: 768px) {
            .car-result-card { flex-direction: column; text-align: center; gap: 15px; }
            .car-price-section { border-left: none; padding-left: 0; min-width: 100%; }
            .city-img-side { display: none; }
        }
    </style>
    <div class="city-pack-banner wow fadeInUp">
        <img src="<?php echo $city_img; ?>" class="city-img-side" alt="<?php echo $city_name; ?>">
        <div class="city-info-side">
            <span class="featured-badge">Standard Service Area</span>
            <h2>Premium Cabs in <?php echo $city_name; ?></h2>
            <p><i class="fas fa-check-circle text-success me-2"></i> Verified Chauffeurs | <?php echo $pack_desc; ?> | No hidden costs</p>
        </div>
    </div>
    <?php
}

$price_col = 'base_price';
if ($tripType === 'Hourly') {
    $price_col = 'hourly_price';
} elseif ($tripType === 'Transfer' || $tripType === 'Airport Transfer' || strpos(strtolower($to), 'airport') !== false || strpos(strtolower($from), 'airport') !== false) {
    $price_col = 'airport_price';
} elseif ($tripType === 'Outstation') {
    $price_col = 'outstation_price';
}

$cabs_res = $conn->query("SELECT *, $price_col as display_price FROM cab_inventory WHERE status = 1 ORDER BY display_price ASC");
if ($cabs_res && $cabs_res->num_rows > 0) {
    while ($cab = $cabs_res->fetch_assoc()) {
        $display_price = ($cab['display_price'] > 0) ? $cab['display_price'] : $cab['base_price'];
        $car_img = (!empty($cab['image_path'])) ? $cab['image_path'] : "https://placehold.co/400x250/f4f7f6/133a25?text=" . urlencode($cab['car_name']);
        ?>
        <div class="car-result-card wow fadeInUp">
            <div class="car-image-box">
                <img src="<?php echo $car_img; ?>" alt="<?php echo htmlspecialchars($cab['car_name']); ?>">
            </div>
            <div class="car-detail-main">
                <span class="car-category"><?php echo htmlspecialchars($cab['category']); ?></span>
                <h3 class="car-name-title"><?php echo htmlspecialchars($cab['car_name']); ?></h3>
                <div class="d-flex gap-3 mb-2 small text-muted justify-content-center justify-content-md-start">
                    <span><i class="fas fa-users text-teal"></i> <?php echo $cab['capacity']; ?> People</span>
                    <?php if(isset($cab['luggage'])): ?>
                    <span><i class="fas fa-briefcase text-teal"></i> <?php echo $cab['luggage']; ?> Bags</span>
                    <?php endif; ?>
                </div>
                <div class="text-success small fw-bold"><i class="fas fa-check-circle me-1"></i> Refundable fare | All Inclusive</div>
            </div>
            <div class="car-price-section">
                <div class="car-price-tag">₹<?php echo number_format($display_price); ?></div>
                <span class="small text-muted d-block mb-3">estimated fare</span>
                <button class="book-now-premium" onclick="bookCab(<?php echo $cab['id']; ?>)">Book Now</button>
            </div>
        </div>
        <?php
    }
} else {
    echo "<div class='text-center py-5 bg-white rounded-4 shadow-sm'>
            <i class='fas fa-car-crash fa-3x mb-3 text-muted opacity-50'></i>
            <h5 class='fw-bold'>No Cabs Available</h5>
            <p class='text-muted'>Currently, our fleet is fully booked for this route. Please check back later.</p>
          </div>";
}
?>
