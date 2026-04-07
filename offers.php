<?php
include_once 'db.php';
include_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offers | TravoLo - Exclusive Deals on Flights, Hotels & Cabs</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.png" type="image/png">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!--====== Flaticon css ======-->
    <link rel="stylesheet" href="assets/fonts/flaticon/flaticon_gowilds.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/fonts/fontawesome/css/all.min.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <!-- Default CSS -->
    <link rel="stylesheet" href="assets/css/default.css">
    <!-- Style CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        :root {
            --travolo-teal: #00a79d;
            --travolo-dark: #133a25;
            --travolo-orange: #f7921e;
        }

        body { font-family: 'Outfit', sans-serif; background: #f8fafc; }

        /* Unified Banner Style matching Screenshot */
        .offers-banner {
            background: linear-gradient(135deg, rgba(19, 58, 37, 0.95) 0%, rgba(0, 167, 157, 0.8) 100%), url('assets/images/outstation/outstation_bg.jpg');
            background-size: cover;
            background-position: center;
            padding: 80px 0 120px;
            text-align: center;
            position: relative;
            margin-bottom: -60px;
        }

        .offers-banner h1 {
            color: #fff;
            font-size: 48px;
            font-weight: 900;
            margin-bottom: 15px;
            letter-spacing: -1px;
        }

        .offers-banner p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 18px;
            max-width: 600px;
            margin: 0 auto 40px;
        }

        .search-offers {
            max-width: 700px;
            margin: 0 auto;
            position: relative;
        }

        .search-offers input {
            width: 100%;
            padding: 20px 30px;
            padding-left: 60px;
            border-radius: 100px;
            border: none;
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
            font-size: 16px;
        }

        .search-offers i {
            position: absolute;
            left: 25px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--travolo-teal);
            font-size: 20px;
        }

        /* Category Navigation Bar */
        .offers-nav-box {
            background: #fff;
            border-radius: 20px;
            padding: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            margin-bottom: 50px;
            overflow-x: auto;
            white-space: nowrap;
            position: sticky;
            top: 100px;
            z-index: 100;
        }

        .nav-offers {
            display: flex;
            gap: 10px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-offers .nav-link {
            padding: 12px 25px;
            border-radius: 15px;
            font-weight: 700;
            font-size: 14px;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .nav-offers .nav-link i { font-size: 18px; }

        .nav-offers .nav-link.active {
            background: var(--travolo-teal);
            color: #fff;
            box-shadow: 0 8px 15px rgba(0, 167, 157, 0.2);
        }

        .nav-offers .nav-link:hover:not(.active) {
            background: #f1f5f9;
            color: var(--travolo-teal);
        }

        /* Dynamic Grid Layout - Optimized for 4 Items per line */
        .offers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            padding-bottom: 100px;
        }

        @media (min-width: 1400px) {
            .offers-grid { grid-template-columns: repeat(4, 1fr); }
        }

        @media (max-width: 1399px) and (min-width: 992px) {
            .offers-grid { grid-template-columns: repeat(3, 1fr); }
        }

        /* Matching Premium Offer Card Design */
        .offer-card-premium {
            background: #fff;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.04);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            cursor: pointer;
            text-decoration: none;
            height: 100%;
        }

        .offer-card-premium:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.1);
            border-color: var(--travolo-teal);
        }

        .offer-banner-top {
            display: flex;
            height: 160px;
            position: relative;
        }

        .banner-side-info {
            flex: 1.2;
            padding: 25px;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .banner-side-img {
            flex: 1;
            overflow: hidden;
        }

        .banner-side-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.6s ease;
        }

        .offer-card-premium:hover .banner-side-img img { transform: scale(1.15); }

        .o-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(8px);
            color: #fff;
            padding: 4px 12px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 800;
            z-index: 10;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .o-discount { font-size: 12px; font-weight: 600; opacity: 0.9; text-transform: uppercase; margin-bottom: 5px; }
        .o-main-title { font-size: 20px; font-weight: 800; margin-bottom: 15px; line-height: 1.2; }
        .o-promo { 
            background: rgba(255, 255, 255, 0.2); 
            padding: 4px 12px; 
            border-radius: 8px; 
            font-size: 11px; 
            font-weight: 700; 
            border: 1px dashed rgba(255, 255, 255, 0.5);
            display: inline-block;
        }

        .offer-footer-body {
            padding: 25px;
        }

        .offer-footer-body h4 {
            font-size: 20px;
            font-weight: 800;
            color: var(--travolo-dark);
            margin-bottom: 10px;
        }

        .offer-footer-body p {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 0;
            line-height: 1.5;
        }

        .validity-divider {
            height: 1px;
            background: #f1f5f9;
            margin: 20px 0;
        }

        .validity-info {
            font-size: 12px;
            font-weight: 600;
            color: var(--travolo-teal);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        @media (max-width: 768px) {
            .offers-banner h1 { font-size: 32px; }
            .offers-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <?php include_once 'navbar.php'; ?>

    <section class="offers-banner">
        <div class="container">
            <h1>Special Travel Offers</h1>
            <p>Unbeatable deals on Flight, Hotel and Cab rentals worldwide. Book your dream trip for less!</p>
            
            <div class="search-offers">
                <i class="fas fa-search"></i>
                <input type="text" id="offerSearch" placeholder="Search by destination or offer type..." onkeyup="filterOffers()">
            </div>
        </div>
    </section>

    <div class="container">
        <div class="offers-nav-box">
            <ul class="nav-offers" id="offerTabs">
                <li><button class="nav-link active" onclick="showTab('all', this)"><i class="fas fa-th-large"></i> Special Offers</button></li>
                <li><button class="nav-link" onclick="showTab('flight', this)"><i class="fas fa-plane"></i> Flight Offers</button></li>
                <li><button class="nav-link" onclick="showTab('hotel', this)"><i class="fas fa-hotel"></i> Hotel Offers</button></li>
                <li><button class="nav-link" onclick="showTab('cab', this)"><i class="fas fa-taxi"></i> Cab Offers</button></li>
                <li><button class="nav-link" onclick="showTab('bank', this)"><i class="fas fa- University"></i> Bank Offers</button></li>
            </ul>
        </div>

        <div class="offers-grid" id="offersGrid">
            <?php
            // 1. Fetch App Offers (Flights/General)
            $app_res = $conn->query("SELECT *, 'flight' as category FROM app_offers WHERE status=1");
            while ($row = $app_res->fetch_assoc()) {
                $category = 'flight';
                if (stripos($row['title'], 'hotel') !== false || stripos($row['description'], 'hotel') !== false) $category = 'hotel';
                if (stripos($row['title'], 'card') !== false || stripos($row['description'], 'bank') !== false) $category = 'bank';
                
                $themeColor = [
                    'primary' => '#0d6efd',
                    'danger'  => '#dc3545',
                    'success' => '#198754',
                    'warning' => '#ffc107',
                    'dark'    => '#212529'
                ][$row['badge_color']] ?? '#00a79d';
                ?>
                <div class="offer-item" data-category="<?php echo $category; ?>" data-search="<?php echo strtolower($row['title'] . ' ' . $row['description']); ?>">
                    <a href="#" class="offer-card-premium">
                        <div class="offer-banner-top">
                            <div class="o-badge"><?php echo htmlspecialchars($row['badge_text']); ?></div>
                            <div class="banner-side-info" style="background: <?php echo $themeColor; ?>;">
                                <div class="o-discount">Special Deal</div>
                                <div class="o-main-title"><?php echo htmlspecialchars($row['title']); ?></div>
                                <div class="o-promo">Use Code: <b>TRAVOLO</b></div>
                            </div>
                            <div class="banner-side-img"><img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="Offer"></div>
                        </div>
                        <div class="offer-footer-body">
                            <h4><?php echo htmlspecialchars($row['title']); ?></h4>
                            <p><?php echo htmlspecialchars($row['description']); ?></p>
                            <div class="validity-divider"></div>
                            <div class="validity-info"><i class="far fa-calendar-check"></i> <?php echo htmlspecialchars($row['footer_text'] ?: 'Valid for limited time'); ?></div>
                        </div>
                    </a>
                </div>
                <?php
            }

            // 2. Fetch Cab Offers
            $cab_res = $conn->query("SELECT *, 'cab' as category FROM cab_offers WHERE status=1");
            while ($row = $cab_res->fetch_assoc()) {
                ?>
                <div class="offer-item" data-category="cab" data-search="<?php echo strtolower($row['main_title'] . ' ' . $row['header_main']); ?>">
                    <a href="#" class="offer-card-premium">
                        <div class="offer-banner-top">
                            <div class="o-badge"><?php echo htmlspecialchars($row['badge']); ?></div>
                            <div class="banner-side-info" style="background: <?php echo htmlspecialchars($row['theme_color']); ?>;">
                                <div class="o-discount"><?php echo htmlspecialchars($row['header_small']); ?></div>
                                <div class="o-main-title"><?php echo htmlspecialchars($row['header_main']); ?></div>
                                <div class="o-promo">CODE: <b><?php echo htmlspecialchars($row['promo_code']); ?></b></div>
                            </div>
                            <div class="banner-side-img"><img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Offer"></div>
                        </div>
                        <div class="offer-footer-body">
                            <h4><?php echo htmlspecialchars($row['main_title']); ?></h4>
                            <p>Exclusive TravoLo cab rental deals for intercity, hourly and airport transfers.</p>
                            <div class="validity-divider"></div>
                            <div class="validity-info"><i class="far fa-calendar-check"></i> <?php echo htmlspecialchars($row['validity_text']); ?></div>
                        </div>
                    </a>
                </div>
                <?php
            }
            ?>
        </div>
    </div>

    <?php include_once 'footer.php'; ?>

    <script>
        function showTab(cat, btn) {
            // Update Active Link
            document.querySelectorAll('#offerTabs .nav-link').forEach(l => l.classList.remove('active'));
            btn.classList.add('active');

            // Filter Items
            const items = document.querySelectorAll('.offer-item');
            items.forEach(item => {
                if (cat === 'all' || item.getAttribute('data-category') === cat) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function filterOffers() {
            const query = document.getElementById('offerSearch').value.toLowerCase();
            const activeTab = document.querySelector('#offerTabs .nav-link.active').innerText.toLowerCase();
            let cat = 'all';
            if (activeTab.includes('flight')) cat = 'flight';
            if (activeTab.includes('hotel')) cat = 'hotel';
            if (activeTab.includes('cab')) cat = 'cab';
            if (activeTab.includes('bank')) cat = 'bank';

            const items = document.querySelectorAll('.offer-item');
            items.forEach(item => {
                const text = item.getAttribute('data-search');
                const matchesSearch = text.includes(query);
                const matchesTab = (cat === 'all' || item.getAttribute('data-category') === cat);

                if (matchesSearch && matchesTab) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
