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
if ($tripType === 'Hourly')
    $price_col = 'hourly_price';
elseif ($tripType === 'Airport Transfer' || $to === 'Airport' || $from === 'Airport')
    $price_col = 'airport_price';
elseif ($tripType === 'Outstation')
    $price_col = 'outstation_price';

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

// Partial payment calculation (e.g., 25% advance)
$advance_amount = round($display_price * 0.25);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Your Booking | Travolo</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        :root {
            --primary-color: #00a79d;
            --secondary-color: #e35a14;
            --light-bg: #f4f7f6;
            --card-border: #eaeaea;
        }

        body {
            background: var(--light-bg);
            font-family: 'Inter', sans-serif;
        }

        .checkout-box {
            background: #fff;
            border-radius: 16px;
            border: 1px solid var(--card-border);
            padding: 30px;
            margin-bottom: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        }

        /* Cab Summary Card */
        .cab-summary-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid var(--card-border);
            display: flex;
            align-items: stretch;
            overflow: hidden;
        }

        .cab-summary-img {
            width: 180px;
            background: #f9f9f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            border-right: 1px solid var(--card-border);
        }

        .cab-summary-img img {
            max-width: 100%;
            height: auto;
            object-fit: contain;
        }

        .cab-details {
            padding: 25px;
            flex-grow: 1;
        }

        .cab-details h4 {
            font-weight: 800;
            margin-bottom: 5px;
            color: #111;
        }

        .route-info {
            display: flex;
            align-items: center;
            gap: 20px;
            background: #eff6f5;
            padding: 15px 25px;
            border-radius: 12px;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
        }

        .route-info i {
            color: var(--primary-color);
            font-size: 14px;
        }

        .route-info .loc {
            line-height: 1.2;
        }

        .route-info .loc small {
            color: #888;
            font-size: 11px;
            text-transform: uppercase;
            display: block;
            margin-bottom: 2px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-top: 15px;
        }

        .feature-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 10px;
            background: #fcfcfc;
            border: 1px solid #f0f0f0;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            color: #555;
            text-align: center;
        }

        .feature-item i {
            font-size: 16px;
            margin-bottom: 5px;
            color: var(--primary-color);
        }

        /* Payment Selection */
        .payment-option {
            border: 1.5px solid #eee;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: 0.2s;
            position: relative;
        }

        .payment-option:hover {
            border-color: var(--primary-color);
            background: rgba(0, 167, 157, 0.02);
        }

        .payment-option.active {
            border-color: var(--primary-color);
            background: rgba(0, 167, 157, 0.05);
        }

        .payment-option input {
            position: absolute;
            opacity: 0;
        }

        .payment-option .radio-circle {
            width: 20px;
            height: 20px;
            border: 2px solid #ddd;
            border-radius: 50%;
            margin-right: 15px;
            display: inline-block;
            vertical-align: middle;
            position: relative;
        }

        .payment-option.active .radio-circle {
            border-color: var(--primary-color);
        }

        .payment-option.active .radio-circle::after {
            content: '';
            width: 10px;
            height: 10px;
            background: var(--primary-color);
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        /* Price Sidebar */
        .price-summary {
            background: #fff;
            border-radius: 20px;
            border: 1px solid var(--card-border);
            padding: 30px;
            position: sticky;
            top: 100px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 15px;
            font-weight: 500;
        }

        .grand-total {
            border-top: 2px dashed #eee;
            padding-top: 20px;
            margin-top: 20px;
        }

        .total-label {
            font-size: 18px;
            font-weight: 800;
            color: #111;
        }

        .total-amount {
            font-size: 32px;
            font-weight: 900;
            color: #111;
        }

        /* Coupons */
        .coupon-box {
            display: flex;
            gap: 10px;
            margin-top: 25px;
            padding: 15px;
            background: #fff9f5;
            border: 1px dashed #ffd8c2;
            border-radius: 12px;
        }

        .coupon-input {
            border: 1px solid #e1e1e1;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 13px;
            flex-grow: 1;
        }

        .apply-btn {
            background: #111;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 8px 20px;
            font-weight: 700;
            font-size: 13px;
        }

        @media (max-width: 767px) {
            .cab-summary-card {
                flex-direction: column;
            }

            .cab-summary-img {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid var(--card-border);
            }

            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .price-summary {
                position: static;
                margin-top: 25px;
                padding: 20px;
            }

            .checkout-box {
                padding: 20px;
            }

            .route-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .total-amount {
                font-size: 26px;
            }
        }

        /* General UI */
        .section-title {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 25px;
            color: #111;
            border-left: 5px solid var(--primary-color);
            padding-left: 15px;
        }

        .custom-input {
            background: #f9f9f9;
            border: 1px solid #e1e1e1;
            border-radius: 10px;
            padding: 14px 18px;
            font-size: 14px;
        }

        .custom-input:focus {
            background: #fff;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(0, 167, 157, 0.1);
        }

        .submit-btn {
            background: var(--secondary-color);
            color: #fff;
            font-weight: 800;
            font-size: 18px;
            border-radius: 50px;
            padding: 18px;
            width: 100%;
            border: none;
            transition: 0.3s;
            /* box-shadow: 0 10px 20px rgba(227, 90, 20, 0.2);*/
        }

        .submit-btn:hover {
            background: #c64a0b;
            transform: translateY(-2px);
        }

        .timeline-steps {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            margin-bottom: 40px;
            background: #fdfdfd;
            padding: 15px 25px;
            border-radius: 12px;
            border: 1px solid #eee;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 600;
            color: #888;
        }

        .step.active {
            color: var(--primary-color);
        }

        .step-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            border: 1px solid #ddd;
        }

        .step.active .step-circle {
            background: var(--primary-color);
            color: #fff;
            border-color: var(--primary-color);
        }

        .step-line {
            height: 1px;
            width: 50px;
            background: #eee;
            margin: 0 15px;
        }

        .checkout-box {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid #ececec;
        }

        .trust-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 700;
            color: #00a79d;
            background: #f0fdfc;
            padding: 8px 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            border: 1px solid #cff2f0;
        }
    </style>
</head>

<body>
    <?php include_once 'includes/navbar.php'; ?>

    <div class="container py-4 mt-1 pt-5">
        <div class="timeline-steps">
            <div class="step active">
                <div class="step-circle">1</div>
                Review
                & Travellers
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
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-car-side me-2 text-primary"></i> Review
                                Booking Details</h5>
                            <span
                                class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">Confirmed
                                Availability</span>
                        </div>

                        <div class="cab-summary-card">
                            <div class="cab-summary-img">
                                <img src="<?php echo (!empty($cab['image_path']) && strpos($cab['image_path'], 'watch') === false) ? $cab['image_path'] : 'assets/images/cab-placeholder.png'; ?>"
                                    alt="<?php echo htmlspecialchars($cab['car_name']); ?>">
                                <div class="bg-dark text-white rounded-pill small mt-3 px-3 py-1 fw-bold">
                                    <?php echo htmlspecialchars($cab['category']); ?>
                                </div>
                            </div>
                            <div class="cab-details">
                                <div class="route-info">
                                    <div class="loc">
                                        <small>From</small>
                                        <?php echo $from; ?>
                                    </div>
                                    <i class="fas fa-long-arrow-alt-right mx-2"></i>
                                    <div class="loc">
                                        <small>To</small>
                                        <?php echo $to; ?>
                                    </div>
                                </div>
                                <h4><?php echo htmlspecialchars($cab['car_name']); ?> <small
                                        class="text-muted fw-normal" style="font-size: 14px;">or Equivalent</small></h4>

                                <div class="features-grid">
                                    <?php if ($tripType === 'Hourly'): ?>
                                        <div class="feature-item text-primary fw-bold" title="Package Duration">
                                            <i class="fas fa-clock"></i>
                                            <?php echo htmlspecialchars($_GET['duration'] ?? ''); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="feature-item">
                                        <i class="fas fa-users"></i>
                                        <?php echo $cab['capacity']; ?> Seats
                                    </div>
                                    <div class="feature-item">
                                        <i class="fas fa-briefcase"></i>
                                        <?php echo $cab['luggage']; ?> Bags
                                    </div>
                                    <div class="feature-item">
                                        <i class="fas fa-snowflake"></i>
                                        AC
                                    </div>
                                    <div class="feature-item">
                                        <i class="fas fa-gas-pump"></i>
                                        CNG/Petrol
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Choice -->
                    <div class="checkout-box">
                        <h4 class="section-title">Payment Options</h4>
                        <p class="small text-muted mb-4">Choose how you would like to pay for your journey.</p>

                        <label class="payment-option active" onclick="togglePayment(this)">
                            <input type="radio" name="payment_type" value="partial" checked>
                            <span class="radio-circle"></span>
                            <div class="d-inline-block">
                                <span class="fw-bold d-block">Make Part Payment Now</span>
                                <span class="small text-muted">Pay ₹<?php echo number_format($advance_amount); ?> now &
                                    rest to the driver</span>
                            </div>
                            <span class="float-end fw-bold">₹<?php echo number_format($advance_amount); ?></span>
                        </label>

                        <label class="payment-option" onclick="togglePayment(this)">
                            <input type="radio" name="payment_type" value="full">
                            <span class="radio-circle"></span>
                            <div class="d-inline-block">
                                <span class="fw-bold d-block">Make Full Payment Now</span>
                                <span class="small text-muted">Pay the entire amount now for a hassle-free trip</span>
                            </div>
                            <span class="float-end fw-bold">₹<?php echo number_format($display_price); ?></span>
                        </label>
                    </div>

                    <!-- Travellers Details -->
                    <div class="checkout-box">
                        <h4 class="section-title">Traveller Information</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="small fw-bold mb-2 text-muted">Full Name</label>
                                <input type="text" name="name" class="form-control custom-input"
                                    placeholder="As per ID proof" value="<?php echo htmlspecialchars($u_name); ?>"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold mb-2 text-muted">Email Address</label>
                                <input type="email" name="email" class="form-control custom-input"
                                    placeholder="For booking confirmation"
                                    value="<?php echo htmlspecialchars($u_email); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold mb-2 text-muted">Mobile Number</label>
                                <div class="input-group">
                                    <span class="input-group-text border-0 bg-light fw-bold">+91</span>
                                    <input type="tel" name="mobile" class="form-control custom-input"
                                        placeholder="10-digit number" value="<?php echo htmlspecialchars($u_phone); ?>"
                                        pattern="[0-9]{10}" maxlength="10" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Trip Details -->
                    <div class="checkout-box">
                        <h4 class="section-title">Pickup & Drop Details</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="small fw-bold mb-2 text-muted"><i
                                        class="far fa-calendar-alt me-1 text-primary"></i> Journey Date</label>
                                <input type="date" name="date" class="form-control custom-input"
                                    value="<?php echo $date; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold mb-2 text-muted"><i
                                        class="far fa-clock me-1 text-primary"></i> Pickup Time</label>
                                <input type="time" name="time" class="form-control custom-input"
                                    value="<?php echo date('H:i', strtotime($time)); ?>" required>
                            </div>
                            <div class="col-12 mt-4">
                                <label class="small fw-bold mb-2 text-dark">Exact Pick-Up Address</label>
                                <input type="text" name="pickup_address" class="form-control custom-input"
                                    placeholder="e.g. Hotel Name, Apartment No, Landmark" required>
                                <div class="small mt-2 px-2 py-1 bg-light rounded text-muted d-inline-block"><i
                                        class="fas fa-city me-1"></i> Area: <?php echo $from; ?></div>
                            </div>
                            <div class="col-12">
                                <label class="small fw-bold mb-2 text-dark">Exact Drop-Off Address</label>
                                <input type="text" name="dropoff_address" class="form-control custom-input"
                                    placeholder="e.g. Destination Point, Street Name" required>
                                <div class="small mt-2 px-2 py-1 bg-light rounded text-muted d-inline-block"><i
                                        class="fas fa-city me-1"></i> Area: <?php echo $to; ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Price Summary -->
                    <div class="price-summary">
                        <div class="trust-badge">
                            <i class="fas fa-shield-alt"></i> 100% Safe & Secure Payments
                        </div>
                        <div class="trust-badge text-success" style="background: #e9f7ef;">
                            <i class="fas fa-clock"></i> Free Cancellation (1h before)
                        </div>

                        <h5 class="fw-bold mb-4 mt-3">Fare Summary</h5>

                        <div class="price-row text-muted">
                            <span>Base Fare</span>
                            <span>₹<?php echo number_format($display_price); ?></span>
                        </div>
                        <div class="price-row text-muted">
                            <span>Taxes & Tolls</span>
                            <span class="text-success">Included</span>
                        </div>
                        <div class="price-row text-muted">
                            <span>Booking Fee</span>
                            <span class="text-decoration-line-through">₹150</span> <span
                                class="text-success ml-1">FREE</span>
                        </div>

                        <div class="grand-total">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="total-label">Total Amount</span>
                                    <a href="javascript:void(0)" class="d-block text-primary small fw-bold mt-1">View
                                        Fare Breakup</a>
                                </div>
                                <div class="total-amount">₹<?php echo number_format($display_price); ?></div>
                            </div>
                        </div>

                        <div class="coupon-box">
                            <input type="text" name="coupon_code" class="coupon-input" placeholder="Enter Coupon Code">
                            <button type="button" class="apply-btn">APPLY</button>
                        </div>
                        <div class="small text-success fw-bold mt-2 ps-2"><i class="fas fa-tag me-1"></i> Use TRAVEL20
                            for 20% off</div>

                        <button type="submit" class="submit-btn mt-4">Confirm Booking <i
                                class="fas fa-arrow-right ms-2"></i></button>

                        <p class="text-center small text-muted mt-3">By continuing, you agree to our <a
                                href="terms-conditions.php">T&C</a></p>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <?php include_once 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePayment(element) {
            document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('active'));
            element.classList.add('active');
            element.querySelector('input').checked = true;
        }
    </script>
</body>

</html>