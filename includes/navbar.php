<?php include_once __DIR__ . '/auth.php'; ?>
<!--====== Start Preloader ======-->
<div class="preloader">
    <div class="loader">
        <div class="pre-box"></div>
    </div>
</div><!--====== End Preloader ======-->

<!--====== Search From ======-->
<div class="modal fade search-modal" id="search-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form>
                <div class="form_group">
                    <input type="search" class="form_control" placeholder="Search here" name="search">
                    <label><i class="fa fa-search"></i></label>
                </div>
            </form>
        </div>
    </div>
</div><!--====== Search From ======-->

<!--====== Start Header ======-->
<!-- Custom Navigation Styling moved to style.css for better maintenance -->
<header class="header-area header-three ">
    <!--====== Header Top Bar ======-->
    <div class="header-top-bar bg-green ">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-6 col-lg-12">
                    <!--====== Information Wrapper ======-->
                    <div class="information-wrapper">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="single-info-item-two justify-content-start">
                                    <div class="inner-info text-Start">
                                        <div class="icon">
                                            <i class="fas fa-phone-alt"></i>
                                        </div>
                                        <div class="info2">
                                            <p><a href="tel:+918373996644">+91-8373996644</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="single-info-item-two justify-content-start">
                                    <div class="inner-info">
                                        <div class="icon">
                                            <i class="far fa-envelope"></i>
                                        </div>
                                        <div class="info">
                                            <p><a href="mailto:info@travolo.online">sales@travolo.online</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 d-none d-xl-block">
                    <!--====== User Top Info ======-->
                    <div class="header-top-right d-flex align-items-center justify-content-end">
                        <?php if (is_logged_in()): ?>
                            <div class="user-top-info text-white me-4" style="font-size: 14px; font-weight: 500;">
                                <i class="fas fa-user-circle me-2"></i>Hello,
                                <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>
                            </div>
                            <a href="logout.php" class="text-white fw-bold" style="font-size: 14px;">
                                <i class="fas fa-sign-out-alt me-1"></i>Logout
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--====== Header Navigation ======-->
    <div class="header-navigation navigation-white">
        <div class="nav-overlay"></div>
        <div class="container">
            <div class="primary-menu px-0">
                <!--====== Site Branding ======-->
                <div class="site-branding">
                    <a href="index.php" class="brand-logo"><img src="assets/images/logo1.png" width="200px"
                            alt="Logo"></a>
                </div>
                <!--====== Nav Menu ======-->
                <div class="nav-menu">
                    <!--=== Nav Search ===-->
                    <div class="nav-search mb-30 d-block d-xl-none ">
                        <form>
                            <div class="form_group">
                                <input type="email" class="form_control" placeholder="Search Here" name="email"
                                    required>
                                <button class="search-btn"><i class="fas fa-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <!--====== Main Menu ======-->
                    <nav class="main-menu nav-right-item">
                        <ul>
                            <li class="menu-item has-children"><a href="index.php">Home</a></li>
                            <li class="menu-item has-children"><a href="about.php">About Us</a></li>
                            <li class="menu-item has-children"><a href="flight-booking.php">Flight</a></li>
                            <li class="menu-item has-children"><a href="hotel.php">Hotel</a></li>
                            <li class="menu-item has-children"><a href="cab-booking.php">Cab</a></li>
                            <li class="menu-item has-children"><a href="contact.php">Contact</a></li>
                            <?php if (is_logged_in()): ?>
                                <li class="menu-item">
                                    <a href="user-dashboard.php" class="text-success fw-bold">
                                        <i class="fas fa-calendar-check me-1"></i>My Booking
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="menu-item"><a href="login-user.php"><i class="fas fa-user"></i> Login</a></li>
                            <?php endif; ?>
                        </ul>
                    </nav>

                    <!--====== Menu Button ======-->
                    <div class="menu-button mt-40 d-xl-none">
                        <a href="contact.php" class="main-btn secondary-btn">Book Now<i
                                class="fas fa-paper-plane"></i></a>
                    </div>
                </div>
                <!--====== Nav Right Item ======-->
                <div class="nav-right-item">
                    <div class="navbar-toggler">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!--====== End Header ======-->