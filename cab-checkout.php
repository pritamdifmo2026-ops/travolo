<?php
include_once 'includes/db.php';
include_once 'includes/auth.php';

$cab_id = intval($_GET['cab_id'] ?? 0);
$from = htmlspecialchars($_GET['from'] ?? 'Delhi');
$to = htmlspecialchars($_GET['to'] ?? 'Airport');
$date = htmlspecialchars($_GET['date'] ?? date('Y-m-d'));
$time = htmlspecialchars($_GET['time'] ?? '12:00 PM');
$tripType = htmlspecialchars($_GET['tripType'] ?? 'Transfer');
$pickup_type = htmlspecialchars($_GET['pickup'] ?? 'One Way');

if ($cab_id <= 0) {
    header("Location: cab-booking.php");
    exit;
}

$price_col = 'base_price';
if ($tripType === 'Hourly') $price_col = 'hourly_price';
elseif ($tripType === 'Airport Transfer' || $to === 'Airport' || $from === 'Airport') $price_col = 'airport_price';
elseif ($tripType === 'Outstation') $price_col = 'outstation_price';

$cabs_res = $conn->query("SELECT *, $price_col as display_price FROM cab_inventory WHERE id = $cab_id LIMIT 1");
if (!$cabs_res || $cabs_res->num_rows == 0) {
    header("Location: cab-booking.php");
    exit;
}
$cab = $cabs_res->fetch_assoc();
$display_price = ($cab['display_price'] > 0) ? $cab['display_price'] : $cab['base_price'];

// Dynamic Pricing Calculation for Hourly
if ($tripType === 'Hourly') {
    $duration_str = $_GET['duration'] ?? '8 hrs / 80 km';
    $selected_hours = intval($duration_str); // Extracts '4' from '4 hrs / 40 km'
    if ($selected_hours > 0) {
        $price_per_hour = $display_price / 8;
        $display_price = round($price_per_hour * $selected_hours);
    }
}

// User details
$u_name = $_SESSION['user_name'] ?? '';
$u_email = $_SESSION['user_email'] ?? '';
$u_phone = $_SESSION['user_phone'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review & Book Cab - TravoLo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .checkout-box { background: #fff; border-radius: 12px; border: 1px solid #eaeaea; padding: 25px; margin-bottom: 20px; }
        .cab-summary-card { background: #fdfdfd; border-radius: 12px; border: 1px solid #eaeaea; display: flex; align-items: center; padding: 20px; }
        .cab-summary-img { width: 140px; text-align: center; margin-right: 20px; }
        .cab-summary-img img { max-width: 100%; max-height: 80px; object-fit: contain; }
        .cab-details h4 { font-weight: 800; margin-bottom: 5px; }
        .cab-details p { color: #666; margin-bottom: 10px; font-size: 14px; }
        .features-list { display: flex; gap: 15px; font-size: 13px; color: #555; }
        .features-list span { display: flex; align-items: center; gap: 5px; }
        .price-summary { background: #fff; border-radius: 12px; border: 1px solid #eaeaea; padding: 25px; position: sticky; top: 100px; }
        .price-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 15px; }
        .grand-total { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #eee; padding-top: 15px; margin-top: 10px; }
        .grand-total .amount { font-size: 28px; font-weight: 800; color: #111; }
        .section-title { font-size: 18px; font-weight: 800; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; color: #333; }
        .custom-input { background: #f9f9f9; border: 1px solid #e1e1e1; border-radius: 8px; padding: 12px 15px; font-size: 14px; transition: all 0.2s; }
        .custom-input:focus { background: #fff; border-color: #00a79d; box-shadow: 0 0 0 4px rgba(0, 167, 157, 0.1); }
        .submit-btn { background: #e35a14; color: #fff; font-weight: 800; font-size: 16px; border-radius: 30px; padding: 15px 30px; width: 100%; border: none; transition: 0.3s; }
        .submit-btn:hover { background: #c64a0b; color: #fff; }
        
        .route-info { display: flex; align-items: center; gap: 15px; background: #f4f7f6; padding: 10px 20px; border-radius: 8px; font-weight: 700; color: #333; margin-top: 10px;}
        .route-info i { color: #888; }
        
        .timeline-steps { display: flex; align-items: center; justify-content: center; gap: 30px; margin-bottom: 40px; margin-top: 20px; }
        .step { display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 700; color: #aaa; }
        .step.active { color: #00a79d; }
        .step-circle { width: 24px; height: 24px; border-radius: 50%; background: #eee; display: flex; align-items: center; justify-content: center; font-size: 12px; }
        .step.active .step-circle { background: #00a79d; color: #fff; }
        .step-line { height: 2px; width: 60px; background: #eee; }
    </style>
</head>
<body style="background: #f4f7f6;">
    <?php include_once 'includes/navbar.php'; ?>

    <div class="container py-4 mt-5 pt-5">
        <div class="timeline-steps">
            <div class="step active">
                <div class="step-circle">1</div>
                Review & Travellers
            </div>
            <div class="step-line"></div>
            <div class="step">
                <div class="step-circle">2</div>
                Payment
            </div>
        </div>

        <form id="checkoutForm" action="submit.php" method="GET">
            <input type="hidden" name="action" value="book_cab">
            <input type="hidden" name="cab_id" value="<?php echo $cab_id; ?>">
            <input type="hidden" name="from" value="<?php echo $from; ?>">
            <input type="hidden" name="to" value="<?php echo $to; ?>">
            <input type="hidden" name="tripType" value="<?php echo $tripType; ?>">
            <input type="hidden" name="pickup" value="<?php echo $pickup_type; ?>">
            <input type="hidden" name="hours" value="<?php echo htmlspecialchars($_GET['duration'] ?? ''); ?>">

            <div class="row g-4">
                <div class="col-lg-8">
                    <!-- Cab Details -->
                    <div class="checkout-box">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-check-circle me-2"></i> Selected Cab Details</h5>
                        </div>
                        
                        <div class="cab-summary-card">
                            <div class="cab-summary-img">
                                <img src="<?php echo $cab['image_path']; ?>" alt="<?php echo htmlspecialchars($cab['car_name']); ?>">
                                <div class="bg-dark text-white rounded-pill small mt-2 py-1 fw-bold"><?php echo htmlspecialchars($cab['category']); ?></div>
                            </div>
                            <div class="cab-details flex-grow-1">
                                <div class="route-info mb-3">
                                    <span>Pickup <br><span class="text-primary"><?php echo $from; ?></span></span>
                                    <i class="fas fa-arrow-right"></i>
                                    <span>Drop-Off <br><span class="text-primary"><?php echo $to; ?></span></span>
                                </div>
                                <div class="features-list">
                                    <span><i class="fas fa-car text-muted"></i> <?php echo htmlspecialchars($cab['car_name']); ?></span>
                                    <span><i class="fas fa-briefcase text-muted"></i> <?php echo $cab['luggage']; ?> Bags</span>
                                    <span><i class="fas fa-users text-muted"></i> <?php echo $cab['capacity']; ?> Seats</span>
                                    <span><i class="fas fa-snowflake text-muted"></i> AC</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Travellers Details -->
                    <div class="checkout-box">
                        <h4 class="section-title">Travellers Details</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="small fw-bold mb-1 text-muted">Full Name</label>
                                <input type="text" name="name" class="form-control custom-input" placeholder="Enter Full Name" value="<?php echo htmlspecialchars($u_name); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold mb-1 text-muted">Email</label>
                                <input type="email" name="email" class="form-control custom-input" placeholder="Enter Email Address" value="<?php echo htmlspecialchars($u_email); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold mb-1 text-muted">Mobile Number</label>
                                <input type="tel" name="mobile" class="form-control custom-input" placeholder="10-digit mobile number" value="<?php echo htmlspecialchars($u_phone); ?>" pattern="[0-9]{10}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                                <div class="form-text" style="font-size:11px;">Your booking details will be sent to this email address and mobile number.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Trip Details -->
                    <div class="checkout-box mb-4">
                        <h4 class="section-title">Trip Details</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="small fw-bold mb-1 text-muted"><i class="far fa-calendar-alt me-1"></i> Pickup Date</label>
                                <input type="date" name="date" class="form-control custom-input" value="<?php echo $date; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold mb-1 text-muted"><i class="far fa-clock me-1"></i> Pickup Time</label>
                                <input type="time" name="time" class="form-control custom-input" value="<?php echo date('H:i', strtotime($time)); ?>" required>
                            </div>
                            <div class="col-12 mt-4">
                                <label class="small fw-bold mb-1 text-primary"><i class="fas fa-map-marker-alt me-1"></i> Exact Pick-Up Address</label>
                                <input type="text" name="pickup_address" class="form-control custom-input" placeholder="Enter Exact Pickup Location (e.g. Hotel, Street, Area)" required>
                                <div class="form-text" style="font-size: 11px; background: #fff3cd; padding: 4px 8px; border-radius: 4px; display: inline-block; margin-top: 5px;"><?php echo $from; ?>, India</div>
                            </div>
                            <div class="col-12">
                                <label class="small fw-bold mb-1 text-danger"><i class="fas fa-map-pin me-1"></i> Exact Drop-Off Address</label>
                                <input type="text" name="dropoff_address" class="form-control custom-input" placeholder="Enter Exact Drop Location" required>
                                <div class="form-text" style="font-size: 11px; background: #fff3cd; padding: 4px 8px; border-radius: 4px; display: inline-block; margin-top: 5px;"><?php echo $to; ?>, India</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Price Summary -->
                    <div class="price-summary">
                        <div class="d-flex align-items-center text-success mb-3 pb-3 border-bottom" style="font-size: 13px; font-weight: 700;">
                            <i class="fas fa-check-circle me-2"></i> Free Cancellation before 1 hours of journey time.
                        </div>
                        
                        <div class="price-row text-muted">
                            <span>Base Fare</span>
                            <span>₹<?php echo number_format($display_price); ?></span>
                        </div>
                        <div class="price-row text-muted">
                            <span>Taxes & Tolls</span>
                            <span class="text-success">Included</span>
                        </div>

                        <div class="grand-total">
                            <div>
                                <h5 class="mb-0 fw-bold">Grand Total</h5>
                                <a href="#" class="text-primary text-decoration-none" style="font-size: 12px;">Fare Breakups</a>
                            </div>
                            <div class="amount">₹<?php echo number_format($display_price); ?></div>
                        </div>

                        <button type="submit" class="submit-btn mt-4">Continue to Payment</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <?php include_once 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
