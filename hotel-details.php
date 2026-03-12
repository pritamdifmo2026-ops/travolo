<?php
include 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$res = $conn->query("SELECT * FROM app_hotels WHERE id = $id AND availability = 1");
$hotel = $res->fetch_assoc();

if (!$hotel) {
    header("Location: hotel.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="zxx">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title><?php echo $hotel['name']; ?> - Travelo</title>
        
        <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/png">
        <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="assets/fonts/flaticon/flaticon_gowilds.css">
        <link rel="stylesheet" href="assets/fonts/fontawesome/css/all.min.css">
        <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/vendor/magnific-popup/dist/magnific-popup.css">
        <link rel="stylesheet" href="assets/vendor/slick/slick.css">
        <link rel="stylesheet" href="assets/vendor/jquery-ui/jquery-ui.min.css">
        <link rel="stylesheet" href="assets/vendor/nice-select/css/nice-select.css">
        <link rel="stylesheet" href="assets/vendor/animate.css">
        <link rel="stylesheet" href="assets/css/default.css">
        <link rel="stylesheet" href="assets/css/style.css">
        
        <!-- Flatpickr -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/material_orange.css">

        <style>
            .hotel-hero { height: 450px; border-radius: 20px; overflow: hidden; margin-bottom: 40px; }
            .price-tag { background: #F7921E; color: white; padding: 15px 30px; border-radius: 50px; font-weight: 700; font-size: 24px; display: inline-block; }
            .calendar-card { background: #fff; border: 1px solid #eee; border-radius: 15px; padding: 25px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
            .booking-sidebar { background: #F8F9FA; border-radius: 15px; padding: 30px; position: sticky; top: 100px; }
            .btn-book-now { background: #F7921E; color: white; border: none; padding: 18px; width: 100%; border-radius: 10px; font-weight: 700; font-size: 18px; transition: 0.3s; }
            .btn-book-now:hover { background: #e6851b; transform: translateY(-2px); color: white; }
            .hotel-desc { font-size: 16px; line-height: 1.8; color: #666; }
            #inline-calendar .flatpickr-calendar { box-shadow: none; border: none; width: 100%; }
        </style>
    </head>
    <body>
        <div class="preloader">
            <div class="loader">
                <div class="pre-shadow"></div>
                <div class="pre-box"></div>
            </div>
        </div>

        <header class="header-area header-three ">
            <div class="header-top-bar bg-green ">
                <div class="container">
                    <div class="row align-items-center">   
                        <div class="col-xl-6 col-lg-12">
                            <div class="information-wrapper">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="single-info-item-two justify-content-start">
                                            <div class="inner-info text-Start">
                                                <div class="icon"><i class="fas fa-phone-alt"></i></div>
                                                <div class="info2"><p><a href="tel:+919910516644">+91-9910516644</a></p></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="single-info-item-two justify-content-start">
                                            <div class="inner-info">
                                                <div class="icon"><i class="far fa-envelope"></i></div>
                                                <div class="info"><p><a href="mailto:info@travolo.online">info@travolo.online</a></p></div>
                                            </div>
                                        </div>
                                    </div>                                   
                                </div>
                            </div>
                        </div>
						<div class="col-xl-6 col-lg-12">
							<div class="row align-items-center">
                                <div class="col-lg-9 ">
									<ul class="d-flex justify-content-end">
										<li class="pe-3"><a href="index.html">Booking Now</a></li>
										<li><a href="about.html">About Us</a></li>                                   
									</ul>
								</div>
								<div class="col-lg-3">								
									<div class="booking-item">
										<div class="bk-item booking-user" id="currency">
											<select class="wide"><option value="01">USD</option><option value="02">INR</option><option value="03">EUR</option></select>
                                       </div>
                                   </div>
								</div>
							</div>
						</div>	
                    </div>
                </div>
            </div>
            <div class="header-navigation navigation-white">
                <div class="nav-overlay"></div>
                <div class="container">
                    <div class="primary-menu black-bg px-0">
                        <div class="site-brading ">
                            <a href="index.html" class="brand-logo"><img src="assets/images/logo.jpg" width="150px" alt="Logo"></a>
                        </div>
                        <div class="nav-menu">                           
                            <nav class="main-menu nav-right-item">
                                <ul>
                                    <li><a href="index.html">Home</a></li>
                                    <li><a href="about.html">About Us</a></li>
                                    <li><a href="flight-booking.html">Flight</a></li>
                                    <li><a href="hotel.html">Hotel</a></li>
                                    <li><a href="cab-booking.html">Cab</a></li>
                                    <li><a href="contact.html">Contact</a></li>
                                </ul>
                            </nav>
                        </div>
                        <div class="nav-right-item">                       
                            <div class="navbar-toggler"><span></span><span></span><span></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <section class="page-banner overlay pt-170 pb-170 bg_cover" style="background-image: url(assets/images/abt-bg.jpg);">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="page-banner-content text-center text-white">
                            <h1 class="page-title text-white"><?php echo $hotel['name']; ?></h1>
                            <ul class="breadcrumb-link text-white">
                                <li><a href="index.html">Home</a></li>
                                <li><a href="hotel.html">Hotels</a></li>
                                <li class="active"><?php echo $hotel['location']; ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="hotel-details-section pt-100 pb-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="hotel-hero shadow-lg">
                            <img src="<?php echo $hotel['image']; ?>" alt="Hotel" style="width:100%; height:100%; object-fit:cover;">
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <span class="badge bg-green text-white px-3 py-2 mb-2"><?php echo $hotel['accommodations']; ?></span>
                                <h2 class="fw-bold"><?php echo $hotel['name']; ?></h2>
                                <p class="text-muted"><i class="fas fa-map-marker-alt text-warning me-2"></i><?php echo $hotel['location']; ?></p>
                            </div>
                            <div class="price-tag">₹<?php echo $hotel['price']; ?> <small style="font-size:14px; font-weight:400;">/ Night</small></div>
                        </div>

                        <div class="hotel-info-tabs mt-40">
                            <h4 class="mb-3">Description</h4>
                            <p class="hotel-desc"><?php echo nl2br($hotel['description']); ?></p>
                        </div>

                        <div class="calendar-card mt-50">
                            <h4 class="mb-4"><i class="far fa-calendar-check text-warning me-2"></i>Availability Calendar</h4>
                            <p class="text-muted mb-4">Dates shown in the calendar below are available for booking at this property.</p>
                            <div id="inline-calendar"></div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="booking-sidebar">
                            <h4 class="mb-4">Book This Stay</h4>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-3"><i class="fas fa-check text-success me-2"></i> Free Cancellation</li>
                                <li class="mb-3"><i class="fas fa-check text-success me-2"></i> Best Price Guaranteed</li>
                                <li class="mb-3"><i class="fas fa-check text-success me-2"></i> Instant Confirmation</li>
                            </ul>
                            
                            <button class="btn-book-now" data-bs-toggle="modal" data-bs-target="#bookingModal">
                                Book Now <i class="far fa-paper-plane ms-2"></i>
                            </button>
                            
                            <div class="contact-info mt-4 pt-4 border-top">
                                <p class="mb-1 text-muted">Need help?</p>
                                <h6 class="fw-bold"><i class="fas fa-phone-alt text-warning me-2"></i> +91-9910516644</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Booking Modal -->
        <div class="modal fade" id="bookingModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0" style="border-radius: 15px;">
                    <div class="modal-header bg-green text-white p-4">
                        <h5 class="modal-title fw-bold text-white">Send Booking Inquiry</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form id="bookingQueryForm">
                            <input type="hidden" name="form_type" value="hotel">
                            <input type="hidden" name="booking_type" value="Booking">
                            <input type="hidden" name="hotel_id" value="<?php echo $hotel['id']; ?>">
                            <input type="hidden" name="search" value="<?php echo $hotel['name']; ?>">
                            <input type="hidden" name="accommodations" value="<?php echo $hotel['accommodations']; ?>">

                            <div class="form_group mb-3">
                                <label class="fw-bold small mb-1">Full Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                            </div>
                            <div class="form_group mb-3">
                                <label class="fw-bold small mb-1">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="email@example.com" required>
                            </div>
                            <div class="form_group mb-3">
                                <label class="fw-bold small mb-1">Phone Number</label>
                                <input type="tel" name="phone" class="form-control" placeholder="+91" required>
                            </div>
                            <div class="form_group mb-4">
                                <label class="fw-bold small mb-1">Check-in Date</label>
                                <input type="text" name="check_in" id="modalDatePicker" class="form-control" placeholder="Select Date" required>
                            </div>

                            <button type="submit" class="btn-book-now py-3">Send Inquiry Now</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <footer class="main-footer black-bg2 pt-100">
            <div class="container">
                <div class="footer-widget-area pt-75 pb-30">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="footer-widget about-company-widget mb-40">
                                <div class="footer-content">
                                    <a href="index.html" class="footer-logo"><img src="assets/images/logo-white.png" alt="Logo"></a>
                                    <p class="pt-4">Bringing you the finest travel experiences across the globe.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <div class="footer-widget service-nav-widget mb-40 pl-lg-20">
                                <h4 class="widget-title text-white">Pages</h4>
                                <ul class="footer-widget-nav">
                                    <li><a href="about.html">About us</a></li>
                                    <li><a href="hotel.html">Hotels</a></li>
                                    <li><a href="contact.html">Contact</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="footer-widget footer-newsletter-widget mb-40 pl-lg-20">
                                <h4 class="widget-title text-white">Newsletter</h4>
                                <form>
                                    <div class="form_group">
                                        <input type="email" class="form_control" placeholder="Email Address" required>	
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer-copyright text-center py-4 border-top border-secondary">
                    <p class="mb-0">Copyright @ 2025 <span style="color: #F7921E;">Travelo</span>. All Rights Reserved</p>
                </div>
            </div>
        </footer>

        <a href="#" class="back-to-top" ><i class="far fa-angle-up"></i></a>

        <!-- Jquery and Bootstrap JS -->
        <script src="assets/vendor/jquery-3.6.0.min.js"></script>
        <script src="assets/vendor/popper/popper.min.js"></script>
        <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/vendor/slick/slick.min.js"></script>
        <script src="assets/vendor/magnific-popup/dist/jquery.magnific-popup.min.js"></script>
        <script src="assets/vendor/nice-select/js/jquery.nice-select.min.js"></script>
        <script src="assets/vendor/jquery-ui/jquery-ui.min.js"></script>
        <script src="assets/vendor/wow.min.js"></script>
        <script src="assets/js/theme.js"></script>
        
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            $(document).ready(function() {
                const availableDates = "<?php echo $hotel['available_dates']; ?>".split(', ');

                flatpickr("#inline-calendar", {
                    inline: true,
                    enable: availableDates,
                    dateFormat: "Y-m-d",
                });

                flatpickr("#modalDatePicker", {
                    enable: availableDates,
                    dateFormat: "Y-m-d",
                });

                $('#bookingQueryForm').on('submit', function(e) {
                    e.preventDefault();
                    const btn = $(this).find('button[type="submit"]');
                    btn.prop('disabled', true).text('Sending...');

                    const formData = new FormData(this);
                    
                    fetch('submit.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire({ icon: 'success', title: 'Query Sent!', text: 'Our team will contact you shortly.', confirmButtonColor: '#F7921E' });
                            $('#bookingModal').modal('hide');
                            this.reset();
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error', text: data.message });
                        }
                    })
                    .catch(() => {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong.' });
                    })
                    .finally(() => {
                        btn.prop('disabled', false).text('Send Inquiry Now');
                    });
                });
            });
        </script>
    </body>
</html>
