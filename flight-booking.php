<?php include_once 'includes/auth.php';
include 'includes/db.php'; ?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <!--====== Required meta tags ======-->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="Adventure, Tours, Travel">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!--====== Title ======-->
    <title>Travolo - Tours and Travel</title>
    <!--====== Favicon Icon ======-->
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/png">
    <!--====== Google Fonts ======-->
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!--====== Flaticon css ======-->
    <link rel="stylesheet" href="assets/fonts/flaticon/flaticon_gowilds.css">
    <!--====== FontAwesome css ======-->
    <link rel="stylesheet" href="assets/fonts/fontawesome/css/all.min.css">
    <!--====== Bootstrap css ======-->
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <!--====== magnific-popup css ======-->
    <link rel="stylesheet" href="assets/vendor/magnific-popup/dist/magnific-popup.css">
    <!--====== Slick-popup css ======-->
    <link rel="stylesheet" href="assets/vendor/slick/slick.css">
    <!--====== Jquery UI css ======-->
    <link rel="stylesheet" href="assets/vendor/jquery-ui/jquery-ui.min.css">
    <!--====== Nice Select css ======-->
    <link rel="stylesheet" href="assets/vendor/nice-select/css/nice-select.css">
    <!--====== Animate css ======-->
    <link rel="stylesheet" href="assets/vendor/animate.css">
    <!--====== Default css ======-->
    <link rel="stylesheet" href="assets/css/default.css">
    <!--====== Style css ======-->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        :root {
            --emt-orange: #bf1e2e;
            --emt-blue: #00a79d;
            --emt-border: #e2e8f0;
            --emt-text-main: #1a1a1b;
            --emt-text-sub: #7b7b7b;
        }

        .booking-ui-container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: visible;
            /* Must be visible so dropdowns are not clipped */
            width: 100%;
            margin-top: -100px;
            position: relative;
            z-index: 100;
        }

        .trip-type-tabs {
            display: flex;
            background: #f8fafc;
            padding: 6px 16px;
            gap: 12px;
            border-bottom: 1px solid var(--emt-border);
        }

        .trip-type-tab {
            font-size: 13px;
            font-weight: 600;
            color: var(--emt-text-sub);
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .trip-type-tab.active {
            background: #fff;
            color: #bf1e2e;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border-bottom: 2px solid #bf1e2e;
        }

        .search-row {
            display: flex;
            align-items: stretch;
            border-bottom: 1px solid var(--emt-border);
            width: 100%;
            background: #fff;
        }

        .search-segment {
            flex: 1;
            padding: 8px 12px;
            border-right: 1px solid var(--emt-border);
            cursor: pointer;
            transition: background 0.2s;
            position: relative;
            min-height: 75px;
            min-width: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .search-segment:hover {
            background: #f8fafc;
        }

        .search-segment:last-child {
            border-right: none;
            flex: 0 0 160px;
            padding: 0;
        }

        .segment-label {
            font-size: 11px;
            text-transform: uppercase;
            color: var(--emt-text-sub);
            font-weight: 700;
            margin-bottom: 4px;
        }

        .segment-value {
            font-size: 16px;
            font-weight: 700;
            color: var(--emt-text-main);
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .segment-sub {
            font-size: 12px;
            color: var(--emt-text-sub);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .swap-icon {
            position: absolute;
            right: -15px;
            top: 50%;
            transform: translateY(-50%);
            width: 30px;
            height: 30px;
            background: #fff;
            border: 1px solid var(--emt-border);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 5;
            cursor: pointer;
            color: var(--emt-blue);
            transition: transform 0.3s;
        }

        .swap-icon:hover {
            transform: translateY(-50%) rotate(180deg);
        }

        .search-btn {
            background: linear-gradient(135deg, #bf1e2e 0%, #a61a29 100%);
            color: #fff;
            width: 100%;
            height: 100%;
            border: none;
            font-size: 20px;
            font-weight: 800;
            text-transform: uppercase;
            transition: all 0.3s;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .search-btn:hover {
            background: linear-gradient(135deg, #a61a29 0%, #8c1622 100%);
            box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .search-segment#searchSegment {
            flex: 0 0 160px;
            padding: 0;
            overflow: hidden;
            border-bottom-right-radius: 8px;
        }

        .special-fares {
            display: flex;
            padding: 8px 16px;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .special-fare-label {
            font-size: 13px;
            font-weight: 700;
            color: var(--emt-text-main);
            margin-right: 6px;
        }

        .fare-chip {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border: 1px solid var(--emt-border);
            border-radius: 20px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .fare-chip:hover,
        .fare-chip.active {
            border-color: var(--emt-blue);
            background: #f0f7ff;
        }

        .fare-chip.active {
            color: var(--emt-blue);
            font-weight: 600;
        }

        .city-input-hidden {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .features-bar {
            background: #fff;
            padding: 15px 0;
            margin-top: 20px;
            border-top: 1px solid var(--emt-border);
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 600;
            color: #4a5568;
        }

        .feature-item i {
            color: var(--emt-blue);
            font-size: 20px;
        }

        .feature-badge {
            background: #ff4d4d;
            color: #fff;
            font-size: 9px;
            padding: 1px 4px;
            border-radius: 3px;
            text-transform: uppercase;
            font-weight: 700;
            margin-left: 4px;
        }

        .hero-banner-content {
            text-align: center;
            padding-bottom: 50px;
        }

        .sale-logo {
            max-width: 400px;
            width: 100%;
        }

        /* Override typical hero styles to fit original slider while keeping new search bar */
        .hero-wrapper-three {
            padding-bottom: 0;
        }

        /* Hide default city display as we handle it differently now */
        .city-display {
            display: none !important;
        }

        /* Custom dropdown positioning for new segments */
        .city-dropdown {
            position: absolute !important;
            top: calc(100% + 2px) !important;
            left: 0 !important;
            width: 260px !important;
            min-width: 260px;
            max-height: 260px;
            overflow-y: auto;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            z-index: 9999;
            background: #fff;
        }

        /* Compact dropdown item styles */
        .city-dropdown .dropdown-item {
            padding: 7px 12px !important;
            border-bottom: 1px solid #f1f5f9 !important;
            cursor: pointer;
        }

        .city-dropdown .dropdown-item:last-child {
            border-bottom: none !important;
        }

        .city-dropdown .dropdown-item:hover {
            background: #fdf2f3;
        }

        .city-dropdown .city-row-city {
            font-size: 13px;
            font-weight: 700;
            color: #1a1a1b;
            line-height: 1.2;
        }

        .city-dropdown .city-row-airport {
            font-size: 11px;
            color: #7b7b7b;
            line-height: 1.2;
            margin-top: 1px;
        }

        .city-dropdown .city-row-code {
            font-size: 12px;
            font-weight: 700;
            color: #bf1e2e;
            white-space: nowrap;
        }

        /* Traveller dropdown uses position:fixed so it escapes all overflow contexts */
        #travellerDropdown {
            position: fixed !important;
            width: 280px;
            max-height: 80vh;
            overflow-y: auto;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.14);
            z-index: 99999;
            background: #fff;
            display: none;
        }

        #travellerDropdown h6 {
            font-size: 13px;
            margin-bottom: 0;
        }

        #travellerDropdown small {
            font-size: 10px;
        }

        #travellerDropdown .btn-group .btn {
            padding: 2px 10px !important;
            font-size: 13px;
        }

        #travellerDropdown .btn-group .px-4 {
            padding-left: 12px !important;
            padding-right: 12px !important;
            padding-top: 4px !important;
            padding-bottom: 4px !important;
            font-size: 13px;
        }

        #travellerDropdown .btn-outline-primary {
            font-size: 12px;
            padding: 4px 8px !important;
        }

        #travellerDropdown .btn-primary.btn-lg {
            font-size: 14px;
            padding: 6px 16px !important;
        }


        .city-input-new {
            border: none;
            outline: none;
            width: 100%;
            font-size: 15px;
            font-weight: 700;
            color: var(--emt-text-main);
            background: transparent;
            display: none;
        }

        .city-display-new {
            cursor: pointer;
        }

        .search-segment label {
            display: block;
            font-size: 14px;
            color: var(--emt-text-sub);
            margin-bottom: 5px;
            font-weight: 500;
        }

        /* ==========================================
           RESPONSIVE STYLES
           ========================================== */

        /* ---------- Tablet (max-width: 992px) ---------- */
        @media (max-width: 992px) {
            .booking-ui-container {
                margin-top: -60px;
            }

            .search-row {
                display: grid;
                grid-template-columns: 1fr 1fr;
            }

            .search-segment {
                border-right: 1px solid var(--emt-border);
                border-bottom: 1px solid var(--emt-border);
                min-height: 64px;
            }

            /* FROM segment – remove right border on 2nd column items */
            .search-segment:nth-child(even) {
                border-right: none;
            }

            .search-segment:last-child {
                flex: unset;
            }

            .search-segment#searchSegment {
                grid-column: 1 / -1;
                flex: unset;
                border-bottom-left-radius: 8px;
                border-bottom-right-radius: 8px;
                overflow: hidden;
                min-height: 50px;
            }

            .swap-icon {
                right: -15px;
                z-index: 10;
            }

            .special-fares {
                overflow-x: auto;
                flex-wrap: nowrap;
                padding: 8px 12px;
                -webkit-overflow-scrolling: touch;
            }

            .special-fares::-webkit-scrollbar {
                height: 3px;
            }

            .special-fares::-webkit-scrollbar-thumb {
                background: #ccc;
                border-radius: 10px;
            }

            .fare-chip {
                white-space: nowrap;
                flex-shrink: 0;
            }

            .special-fares .ms-auto {
                flex-shrink: 0;
            }

            #travellerDropdown {
                width: 340px !important;
                min-width: 280px !important;
            }

            .city-dropdown {
                width: 240px !important;
                min-width: 220px !important;
            }

            .hero-content h1 {
                font-size: 32px;
            }
        }

        /* ---------- Mobile (max-width: 768px) ---------- */
        @media (max-width: 768px) {
            .booking-ui-container {
                margin-top: -40px;
                border-radius: 10px;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
            }

            .search-row {
                display: flex;
                flex-direction: column;
            }

            .search-segment {
                border-right: none !important;
                border-bottom: 1px solid var(--emt-border);
                min-height: 60px;
                padding: 10px 14px;
            }

            .search-segment:last-child {
                border-bottom: none;
                flex: unset;
                padding: 0;
            }

            .search-segment#searchSegment {
                border-bottom-left-radius: 10px;
                border-bottom-right-radius: 10px;
                min-height: 48px;
            }

            .search-btn {
                font-size: 16px;
                padding: 14px;
                border-radius: 0 0 10px 10px;
            }

            /* Reposition swap icon for vertical layout */
            .swap-icon {
                right: 14px;
                top: auto;
                bottom: -15px;
                transform: rotate(90deg);
                width: 28px;
                height: 28px;
                font-size: 12px;
            }

            .swap-icon:hover {
                transform: rotate(270deg);
            }

            .segment-value {
                font-size: 15px;
            }

            .segment-sub {
                font-size: 11px;
            }

            .segment-label {
                font-size: 10px;
            }

            .trip-type-tabs {
                padding: 6px 10px;
                gap: 6px;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .trip-type-tab {
                font-size: 12px;
                padding: 5px 8px;
                white-space: nowrap;
                flex-shrink: 0;
            }

            /* City Dropdown – full width on mobile */
            .city-dropdown {
                width: calc(100vw - 40px) !important;
                min-width: unset !important;
                max-width: 360px;
                left: 50% !important;
                transform: translateX(-50%);
            }

            /* Traveller Dropdown – fit mobile */
            #travellerDropdown {
                width: calc(100vw - 24px) !important;
                min-width: unset !important;
                max-width: 400px;
                left: 12px !important;
                right: 12px !important;
            }

            .special-fares {
                padding: 8px 10px;
                gap: 8px;
            }

            .special-fare-label {
                font-size: 12px;
                width: 100%;
                margin-bottom: 4px;
            }

            .fare-chip {
                font-size: 11px;
                padding: 4px 10px;
            }

            .features-bar .row .col-md-2 {
                flex: 0 0 33.333%;
                max-width: 33.333%;
            }

            .feature-item {
                font-size: 12px;
                gap: 6px;
                flex-direction: column;
                text-align: center;
            }

            .feature-item i {
                font-size: 18px;
            }

            .hero-content h1 {
                font-size: 26px;
                line-height: 1.2;
            }

            .hero-content .sub-title {
                font-size: 13px;
            }

            .hero-button {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
            }

            .hero-button .main-btn {
                font-size: 13px;
                padding: 8px 18px;
            }

            /* Gallery section spacing */
            .gallery-section.mbm-150 {
                margin-bottom: -80px !important;
            }

            /* Search results flight cards */
            #searchResults .p-3 {
                padding: 12px !important;
            }

            #searchResults .row {
                row-gap: 6px;
            }
        }

        /* ---------- Small Mobile (max-width: 480px) ---------- */
        @media (max-width: 480px) {
            .booking-ui-container {
                margin-top: -30px;
                border-radius: 8px;
            }

            .search-segment {
                padding: 8px 12px;
                min-height: 54px;
            }

            .segment-value {
                font-size: 14px;
            }

            .segment-sub {
                font-size: 10px;
            }

            .search-btn {
                font-size: 14px;
                padding: 12px;
                letter-spacing: 0.5px;
            }

            .trip-type-tabs {
                gap: 4px;
                padding: 5px 8px;
            }

            .trip-type-tab {
                font-size: 11px;
                padding: 4px 6px;
            }

            .city-dropdown {
                max-height: 200px;
            }

            .city-dropdown .dropdown-item {
                padding: 6px 10px !important;
            }

            .city-dropdown .city-row-city {
                font-size: 12px;
            }

            .city-dropdown .city-row-airport {
                font-size: 10px;
            }

            #travellerDropdown {
                padding: 14px !important;
            }

            #travellerDropdown h6 {
                font-size: 13px;
            }

            #travellerDropdown small {
                font-size: 10px;
            }

            #travellerDropdown .btn-group .btn {
                padding: 4px 10px !important;
            }

            #travellerDropdown .px-4 {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }

            .special-fares {
                padding: 6px 8px;
                gap: 6px;
            }

            .fare-chip {
                font-size: 10px;
                padding: 3px 8px;
                gap: 4px;
            }

            .fare-chip i {
                font-size: 10px;
            }

            .hero-content h1 {
                font-size: 22px;
            }

            .hero-content .sub-title {
                font-size: 12px;
            }

            .hero-button .main-btn {
                font-size: 12px;
                padding: 6px 14px;
            }

            /* Flight results on very small screens */
            #searchResults .fw-bold.fs-5 {
                font-size: 14px !important;
            }

            #searchResults .btn-sm {
                font-size: 12px;
                padding: 4px 12px;
            }
        }

        /* ==========================================
           OFFERS SECTION STYLES
           ========================================== */
        .offers-section-wrapper {
            margin-top: 40px;
        }

        .offers-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .offers-header h4 {
            font-size: 22px;
            font-weight: 800;
            color: var(--emt-text-main);
            margin: 0;
            text-transform: capitalize;
        }

        .offer-card {
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            padding: 24px;
            min-height: 190px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #fff;
            transition: transform 0.3s, box-shadow 0.3s;
            background-size: cover;
            background-position: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            z-index: 1;
            cursor: pointer;
            text-decoration: none;
        }

        .offer-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
            color: #fff;
        }

        .offer-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.85) 0%, rgba(0, 0, 0, 0.2) 100%);
            z-index: -1;
            transition: opacity 0.3s;
        }

        .offer-card:hover::before {
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.3) 100%);
        }

        .offer-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #fff;
            color: #1a1a1b !important;
            font-size: 11px;
            font-weight: 800;
            padding: 5px 10px;
            border-radius: 4px;
            text-transform: uppercase;
            z-index: 2;
            display: inline-block;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .offer-title {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 4px;
            line-height: 1.2;
        }

        .offer-desc {
            font-size: 13px;
            font-weight: 500;
            opacity: 0.9;
            margin-bottom: 0;
        }

        .offer-footer {
            margin-top: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .offer-footer small {
            font-size: 10px;
            opacity: 0.8;
            font-weight: 600;
        }

        /* ==========================================
           NEW INFO & FEATURE SECTIONS
           ========================================== */
        .flight-info-section {
            background-color: #fff;
        }

        .flight-info-content h3 {
            color: #1a1a1b;
            font-size: 24px;
        }

        .why-book-with-us {
            background-color: #f8fafc;
        }

        .feature-card {
            background: #fff;
            transition: all 0.3s ease;
            cursor: default;
        }

        .hover-translate-y:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
        }

        .icon-circle i {
            transition: transform 0.3s ease;
        }

        .feature-card:hover .icon-circle i {
            transform: scale(1.1);
        }

        @media (max-width: 1200px) {
            .why-book-with-us .col-xl-2 {
                flex: 0 0 33.333%;
                max-width: 33.333%;
            }
        }

        @media (max-width: 768px) {
            .why-book-with-us .col-xl-2 {
                flex: 0 0 50%;
                max-width: 50%;
            }

            .section-title h2 {
                font-size: 24px !important;
                text-align: center;
            }

            .flight-info-content h3 {
                font-size: 20px;
                text-align: center;
            }
        }

        @media (max-width: 576px) {
            .why-book-with-us .col-xl-2 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        .pointer {
            cursor: pointer;
        }

        .route-card {
            transition: all 0.3s ease;
        }

        .route-card:hover {
            border-color: #00a79d !important;
            background: #fdfdfd !important;
        }

        .route-img-wrapper img {
            transition: transform 0.3s ease;
        }

        .route-card:hover .route-img-wrapper img {
            transform: scale(1.1);
        }
    </style>
</head>

<body>
    <?php include 'includes/navbar.php'; ?>

    <!--====== Start Hero Section ======-->
    <section class="hero-section">
        <div class="hero-wrapper-three">
            <div class="hero-arrows"></div>
            <div class="hero-slider-three">
                <!--=== Single Slider ===-->
                <div class="single-slider">
                    <div class="image-layer bg_cover" style="background-image: url(assets/images/slider-1.jpg);"></div>
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-xl-7">
                                <!--=== Hero Content ===-->
                                <div class="hero-content text-white">
                                    <span class="sub-title">Welcome to Travolo</span>
                                    <h1 data-animation="fadeInDown" data-delay=".4s">Tour Travel
                                        & Adventure</h1>
                                    <div class="hero-button" data-animation="fadeInRight" data-delay=".6s">
                                        <a href="about.php" class="main-btn primary-btn">Explore More<i
                                                class="fas fa-paper-plane"></i></a>
                                        <a href="about.php" class="main-btn secondary-btn">Learn More<i
                                                class="fas fa-paper-plane"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--=== Single Slider ===-->
                <div class="single-slider">
                    <div class="image-layer bg_cover" style="background-image: url(assets/images/slider-2.jpg);"></div>
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-xl-7">
                                <!--=== Hero Content ===-->
                                <div class="hero-content text-white">
                                    <span class="sub-title">Welcome to Travolo</span>
                                    <h1 data-animation="fadeInDown" data-delay=".4s">Tour Travel
                                        & Camping</h1>
                                    <div class="hero-button" data-animation="fadeInRight" data-delay=".6s">
                                        <a href="about.php" class="main-btn primary-btn">Explore More<i
                                                class="fas fa-paper-plane"></i></a>
                                        <a href="about.php" class="main-btn secondary-btn">Learn More<i
                                                class="fas fa-paper-plane"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--=== Single Slider ===-->
                <div class="single-slider">
                    <div class="image-layer bg_cover" style="background-image: url(assets/images/slider-3.jpg);"></div>
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-xl-7">
                                <!--=== Hero Content ===-->
                                <div class="hero-content text-white">
                                    <span class="sub-title">Welcome to Travolo</span>
                                    <h1 data-animation="fadeInDown" data-delay=".4s">Tour Travel
                                        & Adventure</h1>
                                    <div class="hero-button" data-animation="fadeInRight" data-delay=".6s">
                                        <a href="about.php" class="main-btn primary-btn">Explore More<i
                                                class="fas fa-paper-plane"></i></a>
                                        <a href="about.php" class="main-btn secondary-btn">Learn More<i
                                                class="fas fa-paper-plane"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== End Hero Section ======-->

    <section class="booking-section pb-100">
        <div class="container">
            <div class="booking-ui-container wow fadeInUp">
                <form id="searchForm" action="submit.php" method="POST">
                    <input type="hidden" name="form_type" value="flight">

                    <!-- Trip Type Tabs -->
                    <div class="trip-type-tabs">
                        <div class="trip-type-tab active" data-type="oneWay">One Way</div>
                        <div class="trip-type-tab" data-type="roundTrip">Round Trip</div>
                        <div class="trip-type-tab" data-type="multicity">Multicity</div>
                        <input type="radio" name="tripType" id="oneWay" class="d-none" checked>
                        <input type="radio" name="tripType" id="roundTrip" class="d-none">
                        <input type="radio" name="tripType" id="multicity" class="d-none">
                    </div>

                    <!-- Search Row -->
                    <div class="search-row">
                        <!-- FROM -->
                        <div class="search-segment city-wrapper" id="fromSegment">
                            <label class="segment-label">From</label>
                            <input type="text" name="from" id="fromCityInput" value="Delhi (DEL)" class="city-input-new"
                                autocomplete="off">
                            <div class="city-display-new" id="fromCityDisplay">
                                <div class="segment-value">Delhi</div>
                                <div class="segment-sub">[DEL] Indira Gandhi International Airport</div>
                            </div>
                            <div class="dropdown-menu city-dropdown shadow"></div>
                        </div>

                        <div class="swap-icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>

                        <!-- TO -->
                        <div class="search-segment city-wrapper" id="toSegment">
                            <label class="segment-label">To</label>
                            <input type="text" name="to" id="toCityInput" value="Mumbai (BOM)" class="city-input-new"
                                autocomplete="off">
                            <div class="city-display-new" id="toCityDisplay">
                                <div class="segment-value">Mumbai</div>
                                <div class="segment-sub">[BOM] Chhatrapati Shivaji International Airport</div>
                            </div>
                            <div class="dropdown-menu city-dropdown shadow"></div>
                        </div>

                        <!-- DEPART DATE -->
                        <div class="search-segment" id="departSegment">
                            <label class="segment-label">Departure Date</label>
                            <div class="d-flex align-items-baseline">
                                <div class="segment-value" id="departDayDisplay">--</div>
                                <div class="segment-month-week ms-2">
                                    <div class="segment-month" id="departMonthDisplay">Month</div>
                                    <div class="segment-week" id="departWeekDisplay">Weekday</div>
                                </div>
                            </div>
                            <input type="date" name="depart_date" id="departDateInput" class="city-input-hidden"
                                required>
                        </div>

                        <!-- RETURN DATE -->
                        <div class="search-segment" id="returnSegment">
                            <label class="segment-label">Return Date</label>
                            <div class="d-flex align-items-baseline">
                                <div class="segment-value" id="returnDayDisplay">--</div>
                                <div class="segment-month-week ms-2" id="returnMsgDisplay">
                                    <div class="segment-month" id="returnMonthDisplay">Month</div>
                                    <div class="segment-week" id="returnWeekDisplay">Weekday</div>
                                </div>
                            </div>
                            <input type="date" name="return_date" id="returnDateInput" class="city-input-hidden">
                        </div>

                        <!-- TRAVELLERS -->
                        <div class="search-segment" id="travellerTrigger">
                            <label class="segment-label">Traveller & Class</label>
                            <div class="segment-value"><span id="travellerCountDisplay">1</span> Traveller</div>
                            <div class="segment-sub text-uppercase" id="travelClassDisplay">Economy</div>

                            <!-- Traveller Dropdown -->
                            <div class="dropdown-menu p-3 shadow-lg traveller-dropdown" id="travellerDropdown"
                                style="min-width: 260px; display: none;">
                                <div class="px-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <h6 class="mb-0 fw-bold">Adults</h6><small class="text-muted">(12+
                                                Years)</small>
                                        </div>
                                        <div class="btn-group border rounded-pill overflow-hidden bg-light">
                                            <button type="button" class="btn btn-sm px-3 fw-bold"
                                                onclick="updateCount('adult', -1)">-</button>
                                            <div class="px-4 py-2 bg-white fw-bold" id="adultCount">1</div>
                                            <button type="button" class="btn btn-sm px-3 fw-bold"
                                                onclick="updateCount('adult', 1)">+</button>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <h6 class="mb-0 fw-bold">Children</h6><small class="text-muted">(2–12
                                                Years)</small>
                                        </div>
                                        <div class="btn-group border rounded-pill overflow-hidden bg-light">
                                            <button type="button" class="btn btn-sm px-3 fw-bold"
                                                onclick="updateCount('child', -1)">-</button>
                                            <div class="px-4 py-2 bg-white fw-bold" id="childCount">0</div>
                                            <button type="button" class="btn btn-sm px-3 fw-bold"
                                                onclick="updateCount('child', 1)">+</button>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <h6 class="mb-0 fw-bold">Infants</h6><small class="text-muted">(0–2
                                                Years)</small>
                                        </div>
                                        <div class="btn-group border rounded-pill overflow-hidden bg-light">
                                            <button type="button" class="btn btn-sm px-3 fw-bold"
                                                onclick="updateCount('infant', -1)">-</button>
                                            <div class="px-4 py-2 bg-white fw-bold" id="infantCount">0</div>
                                            <button type="button" class="btn btn-sm px-3 fw-bold"
                                                onclick="updateCount('infant', 1)">+</button>
                                        </div>
                                    </div>
                                    <hr class="my-2">
                                    <div class="segment-label mb-2 fw-bold">SELECT TRAVEL CLASS</div>
                                    <div class="row g-1 mb-2">
                                        <div class="col-6"><button type="button"
                                                class="btn btn-outline-primary btn-sm w-100"
                                                onclick="setTravelClass('Economy', this)"
                                                style="font-size:12px;">Economy</button></div>
                                        <div class="col-6"><button type="button"
                                                class="btn btn-outline-primary btn-sm w-100"
                                                onclick="setTravelClass('Business', this)"
                                                style="font-size:12px;">Business</button></div>
                                        <div class="col-6"><button type="button"
                                                class="btn btn-outline-primary btn-sm w-100"
                                                onclick="setTravelClass('First Class', this)"
                                                style="font-size:12px;">First Class</button></div>
                                        <div class="col-6"><button type="button"
                                                class="btn btn-outline-primary btn-sm w-100"
                                                onclick="setTravelClass('Premium', this)"
                                                style="font-size:12px;">Premium</button></div>
                                    </div>
                                    <div class="d-grid mt-3">
                                        <button type="button" class="btn btn-primary fw-bold rounded-pill py-2"
                                            onclick="toggleDropdown('travellerDropdown')">APPLY</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- EMAIL ADDRESS -->
                        <div class="search-segment" id="emailSegment">
                            <label class="segment-label">Email Address</label>
                            <input type="email" name="email" class="fw-bold border-0 p-0 w-100"
                                style="font-size: 14px; outline: none; background: transparent;"
                                placeholder="your@email.com"
                                value="<?php echo htmlspecialchars($_SESSION['user_email'] ?? $_GET['email'] ?? ''); ?>"
                                required>
                        </div>

                        <!-- MOBILE NUMBER -->
                        <div class="search-segment" id="mobileSegment">
                            <label class="segment-label">Mobile Number</label>
                            <input type="tel" name="mobile" class="fw-bold border-0 p-0 w-100"
                                style="font-size: 14px; outline: none; background: transparent;" placeholder="Mobile No"
                                value="<?php echo htmlspecialchars($_SESSION['user_phone'] ?? $_GET['mobile'] ?? ''); ?>"
                                required pattern="[6-9][0-9]{9}" maxlength="10"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                title="Please enter a valid 10-digit mobile number">
                        </div>

                        <!-- SEARCH BTN -->
                        <div class="search-segment" id="searchSegment">
                            <input type="hidden" name="adults" id="hiddenAdults" value="1">
                            <input type="hidden" name="children" id="hiddenChildren" value="0">
                            <input type="hidden" name="infants" id="hiddenInfants" value="0">
                            <input type="hidden" name="travel_class" id="hiddenClass" value="Economy">
                            <button type="submit" class="search-btn">SEARCH</button>
                        </div>
                    </div> <!-- End search-row -->

                    <!-- Special Fares -->
                    <div class="special-fares">
                        <div class="special-fare-label">Special Fares :</div>
                        <div class="fare-chip"><i class="fas fa-briefcase"></i> Corporate</div>
                        <div class="fare-chip"><i class="fas fa-user-shield"></i> Defence & Army</div>
                        <div class="fare-chip"><i class="fas fa-user-md"></i> Doctors & Nurses</div>
                        <div class="fare-chip"><i class="fas fa-user-graduate"></i> Student</div>
                        <div class="fare-chip"><i class="fas fa-user-friends"></i> Senior Citizen</div>
                        <div class="ms-auto"><button type="button" class="btn btn-link py-0 text-primary fw-bold"
                                style="font-size: 13px;">DISCOVER MORE</button></div>
                    </div>
                </form>
            </div>

            <!-- Dynamic Results Section -->
            <div id="searchResults" class="mt-4" style="display: none;">
                <div class="booking-ui-container p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0"><i class="fas fa-plane"></i> Available Flights</h4>
                        <button class="btn btn-outline-secondary btn-sm"
                            onclick="document.getElementById('searchResults').style.display='none'">Close
                            Results</button>
                    </div>
                    <div id="flightsContainer">
                        <!-- Flights will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Features Bar -->
            <div class="features-bar">
                <div class="row text-center gy-3">
                    <!-- <div class="col-md-2 col-6">
                        <div class="feature-item justify-content-center">
                            <i class="fas fa-plane-departure"></i> Best Flight Deals
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="feature-item justify-content-center">
                            <i class="fas fa-landmark"></i> Monuments 
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="feature-item justify-content-center">
                            <i class="fas fa-subway"></i> Metro
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="feature-item justify-content-center">
                            <i class="fas fa-gift"></i> Gift Cards
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="feature-item justify-content-center">
                            <i class="fas fa-id-card"></i> EMT Cards
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="feature-item justify-content-center">
                            <i class="fas fa-coins"></i> Forex <span class="feature-badge">Hot</span>
                        </div>
                    </div> 
                </div> -->
                </div>

                <!-- Offers Section -->
                <div id="offers-section" class="offers-section-wrapper wow fadeInUp" style="scroll-margin-top: 100px;">
                    <div class="offers-header">
                        <h4>Exclusive Offers</h4>
                        <?php if (!isset($_GET['view_offers'])) { ?>
                            <a href="flight-booking.php?view_offers=all#offers-section"
                                class="text-primary fw-bold text-decoration-none">View All <i
                                    class="fas fa-arrow-right ms-1"></i></a>
                            <?php
                        } else { ?>
                            <a href="flight-booking.php#offers-section" class="text-primary fw-bold text-decoration-none"><i
                                    class="fas fa-arrow-left me-1"></i> View Less </a>
                            <?php
                        } ?>
                    </div>
                    <div class="row g-4">
                        <?php
                        // Only show 4 offers by default, remove LIMIT if "View All" is clicked
                        $limit_clause = isset($_GET['view_offers']) ? "" : "LIMIT 4";
                        $offer_query = $conn->query("SELECT * FROM app_offers WHERE status = 1 ORDER BY id DESC $limit_clause");

                        if ($offer_query && $offer_query->num_rows > 0) {
                            while ($offer = $offer_query->fetch_assoc()) {
                                $colorClass = "text-" . htmlspecialchars($offer['badge_color']);
                                ?>
                                <!-- Dynamic Offer -->
                                <div class="col-lg-3 col-md-6 col-12">
                                    <a href="#" class="offer-card"
                                        style="background-image: url('<?php echo htmlspecialchars($offer['image_url']); ?>'); position: relative;">
                                        <span class="<?php echo $colorClass; ?>"
                                            style="position: absolute; right: 15px; top: 15px; background: white; padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: 800; text-transform: uppercase; z-index: 10; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                            <?php echo htmlspecialchars($offer['badge_text']); ?>
                                        </span>
                                        <div class="mt-4">
                                            <h5 class="offer-title text-white"><?php echo htmlspecialchars($offer['title']); ?>
                                            </h5>
                                            <p class="offer-desc"><?php echo htmlspecialchars($offer['description']); ?></p>
                                        </div>
                                        <div class="offer-footer mt-auto">
                                            <small><?php echo htmlspecialchars($offer['footer_text']); ?></small>
                                        </div>
                                    </a>
                                </div>
                                <?php
                            }
                        } else {
                            // If no offers in DB, display a fallback message
                            echo "<div class='col-12 text-center text-muted my-5'>No exclusive offers available at the moment.</div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
    </section>

    <!--====== Start Flight Info Section ======-->
    <section class="flight-info-section pt-60 pb-40">
        <div class="container">
            <div class="flight-info-content wow fadeInUp">
                <h3 class="mb-20 fw-bold">Book Domestic & International Flight Tickets at the Best Rates</h3>
                <p class="mb-15 text-muted" style="line-height: 1.8; font-size: 14px;">Travolo.com is one of India's
                    leading travel portals, offering cheap flight tickets for both domestic and international travel.
                    Whether you're planning a business trip or a vacation, we provide flight ticket booking at the
                    lowest airfare, ensuring a seamless and budget-friendly travel experience. Our platform allows you
                    to book flights effortlessly while availing exclusive discounts on air tickets.</p>
                <p class="mb-15 text-muted" style="line-height: 1.8; font-size: 14px;">With a commitment to providing
                    transparency in pricing, we charge zero convenience fees and offer attractive deals through our
                    banking partners. Our strong network with major airlines enables us to offer the best flight ticket
                    booking deals for both low-cost carriers and premium airlines. Whether you're searching for
                    last-minute flight deals, cheap flights, or early-bird discounts, Travolo ensures maximum savings on
                    every booking.</p>
                <p class="mb-15 text-muted" style="line-height: 1.8; font-size: 14px;">Planning your journey has never
                    been easier! Our platform provides all essential travel information, including flight status,
                    departure and arrival schedules, web check-in details, and guidance on getting boarding passes.
                    Overall, if you're thinking that how can I Travolo my booking experience, then your wait is finally
                    over. As at Travolo, we strive to make every aspect of your journey smooth and hassle-free, so you
                    can focus on enjoying your trip.</p>
                <p class="mb-15 text-muted" style="line-height: 1.8; font-size: 14px;">Why pay more when you can book
                    your flight tickets at unbeatable prices? Enjoy great savings on domestic and international flights,
                    hotels, and holiday packages with Travolo. Take advantage of our exclusive offers and start planning
                    your next journey today!</p>
            </div>
        </div>
    </section>
    <!--====== End Flight Info Section ======-->

    <!--====== Start Why Book Section ======-->
    <section class="why-book-with-us pt-80 pb-80" style="background-color: #fcfcfc;">
        <div class="container">
            <div class="section-title text-center mb-50 wow fadeInDown">
                <h2 class="title fw-bold" style="font-size: 36px; color: #1a1a1b;">Why Book With Us?</h2>
            </div>
            <div class="row g-4 justify-content-center">
                <!-- Card 1 -->
                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 wow fadeInUp" data-wow-delay=".2s">
                    <div
                        class="feature-card text-center h-100 p-4 border-0 rounded-4 bg-white shadow-sm transition-all hover-translate-y">
                        <div class="feat-icon mb-3">
                            <div class="icon-circle mx-auto d-flex align-items-center justify-content-center shadow-sm"
                                style="width: 75px; height: 75px; background: #e6f7f6; border-radius: 50%;">
                                <i class="fas fa-calendar-alt" style="color: #00a79d; font-size: 28px;"></i>
                            </div>
                        </div>
                        <h5 class="feat-title mb-2 fw-bold" style="font-size: 16px; color: #1a1a1b;">Easy Booking</h5>
                        <p class="feat-desc text-muted mb-0" style="font-size: 13px; line-height: 1.6;">We offer easy
                            and convenient flight bookings with attractive offers.</p>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 wow fadeInUp" data-wow-delay=".3s">
                    <div
                        class="feature-card text-center h-100 p-4 border-0 rounded-4 bg-white shadow-sm transition-all hover-translate-y">
                        <div class="feat-icon mb-3">
                            <div class="icon-circle mx-auto d-flex align-items-center justify-content-center shadow-sm"
                                style="width: 75px; height: 75px; background: #e6f7f6; border-radius: 50%;">
                                <i class="fas fa-hand-holding-usd" style="color: #00a79d; font-size: 28px;"></i>
                            </div>
                        </div>
                        <h5 class="feat-title mb-2 fw-bold" style="font-size: 16px; color: #1a1a1b;">Lowest Price</h5>
                        <p class="feat-desc text-muted mb-0" style="font-size: 13px; line-height: 1.6;">We ensure low
                            rates on hotel reservation, holiday packages and on flight tickets.</p>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 wow fadeInUp" data-wow-delay=".4s">
                    <div
                        class="feature-card text-center h-100 p-4 border-0 rounded-4 bg-white shadow-sm transition-all hover-translate-y">
                        <div class="feat-icon mb-3">
                            <div class="icon-circle mx-auto d-flex align-items-center justify-content-center shadow-sm"
                                style="width: 75px; height: 75px; background: #e6f7f6; border-radius: 50%;">
                                <i class="fas fa-sync-alt" style="color: #00a79d; font-size: 28px;"></i>
                            </div>
                        </div>
                        <h5 class="feat-title mb-2 fw-bold" style="font-size: 16px; color: #1a1a1b;">Instant Refund</h5>
                        <p class="feat-desc text-muted mb-0" style="font-size: 13px; line-height: 1.6;">Get instant
                            refunds effortlessly on your travel bookings with us.</p>
                    </div>
                </div>
                <!-- Card 4 -->
                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 wow fadeInUp" data-wow-delay=".5s">
                    <div
                        class="feature-card text-center h-100 p-4 border-0 rounded-4 bg-white shadow-sm transition-all hover-translate-y">
                        <div class="feat-icon mb-3">
                            <div class="icon-circle mx-auto d-flex align-items-center justify-content-center shadow-sm"
                                style="width: 75px; height: 75px; background: #e6f7f6; border-radius: 50%;">
                                <i class="fas fa-headphones" style="color: #00a79d; font-size: 28px;"></i>
                            </div>
                        </div>
                        <h5 class="feat-title mb-2 fw-bold" style="font-size: 16px; color: #1a1a1b;">24/7 Support</h5>
                        <p class="feat-desc text-muted mb-0" style="font-size: 13px; line-height: 1.6;">Get assistance
                            24/7 on any kind of travel related query. We are happy to assist you.</p>
                    </div>
                </div>
                <!-- Card 5 -->
                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 wow fadeInUp" data-wow-delay=".6s">
                    <div
                        class="feature-card text-center h-100 p-4 border-0 rounded-4 bg-white shadow-sm transition-all hover-translate-y">
                        <div class="feat-icon mb-3">
                            <div class="icon-circle mx-auto d-flex align-items-center justify-content-center shadow-sm"
                                style="width: 75px; height: 75px; background: #e6f7f6; border-radius: 50%;">
                                <i class="fas fa-percent" style="color: #00a79d; font-size: 28px;"></i>
                            </div>
                        </div>
                        <h5 class="feat-title mb-2 fw-bold" style="font-size: 16px; color: #1a1a1b;">Exciting Deals</h5>
                        <p class="feat-desc text-muted mb-0" style="font-size: 13px; line-height: 1.6;">Enjoy exciting
                            deals on flights, hotels, buses, car rental and tour packages.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== End Why Book Section ======-->

    <!--====== Start Top Routes Section ======-->
    <section class="top-routes-section pt-60 pb-60 position-relative" style="background: #fff;">
        <div class="container">
            <div class="section-title text-center mb-50 wow fadeInDown">
                <h2 class="title fw-bold" style="font-size: 32px; color: #1a1a1b;">Top Flight Routes</h2>
            </div>

            <div class="row g-5 justify-content-center">
                <?php
                $routes_res = $conn->query("SELECT * FROM top_flight_routes WHERE status = 1 ORDER BY id ASC");
                if ($routes_res && $routes_res->num_rows > 0) {
                    $index = 0;
                    while ($route = $routes_res->fetch_assoc()) {
                        $delay = ($index % 3) * 0.1;
                        ?>
                        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="<?php echo $delay; ?>s">
                            <div class="route-item d-flex align-items-center pointer transition-all hover-translate-y"
                                onclick="quickSearch('<?php echo addslashes($route['from_query']); ?>', '<?php echo addslashes($route['to_query']); ?>')">
                                <div class="route-thumb me-3 flex-shrink-0" style="width: 65px; height: 65px;">
                                    <img src="<?php echo $route['image_path']; ?>"
                                        alt="<?php echo htmlspecialchars($route['city_name']); ?>"
                                        onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($route['city_name']); ?>&background=e6f7f6&color=00a79d'"
                                        class="w-100 h-100 rounded-circle object-fit-cover shadow-sm">
                                </div>
                                <div class="route-details">
                                    <h5 class="mb-0 fw-bold" style="font-size: 18px; color: #1a1a1b;">
                                        <?php echo htmlspecialchars($route['city_name']); ?> Flights
                                    </h5>
                                    <p class="text-muted small mb-0" style="font-size: 13px; line-height: 1.4;">
                                        Via - <?php echo htmlspecialchars($route['via_cities']); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php
                        $index++;
                    }
                } else {
                    echo "<div class='text-center text-muted'>No top routes available.</div>";
                }
                ?>
            </div>
        </div>
    </section>

    <!--====== Start Trending Destinations Section ======-->
    <section class="trending-destinations-section pt-80 pb-60" style="background-color: #fff;">
        <div class="container">
            <div class="section-title text-center mb-50 wow fadeInDown">
                <h2 class="title fw-bold" style="font-size: 36px; color: #1a1a1b;">Trending Tourist Destinations</h2>
            </div>
            <div class="row g-4 justify-content-center text-center">
                <?php
                $destinations = [
                    ['name' => 'Andaman', 'img' => 'assets/images/destinations/andaman.png'],
                    ['name' => 'Kerala', 'img' => 'assets/images/destinations/kerala.png'],
                    ['name' => 'Kashmir', 'img' => 'assets/images/destinations/kashmir.png'],
                    ['name' => 'Rajasthan', 'img' => 'assets/images/destinations/rajasthan.png'],
                    ['name' => 'Bhutan', 'img' => 'assets/images/destinations/bhutan.png'],
                    ['name' => 'Europe', 'img' => 'assets/images/destinations/europe.png'],
                    ['name' => 'Bali', 'img' => 'assets/images/destinations/bali.png'],
                    ['name' => 'Dubai', 'img' => 'assets/images/destinations/dubai.png'],
                    ['name' => 'Vietnam', 'img' => 'assets/images/destinations/vietnam.png'],
                    ['name' => 'Sri Lanka', 'img' => 'assets/images/destinations/srilanka.png'],
                ];

                foreach ($destinations as $dest) {
                    ?>
                    <div class="col-lg-2 col-md-3 col-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="dest-item mb-4">
                            <div class="dest-icon-circle mx-auto mb-3 d-flex align-items-center justify-content-center transition-all hover-scale overflow-hidden"
                                style="width: 110px; height: 110px; border-radius: 50%;">
                                <img src="<?php echo $dest['img']; ?>" alt="<?php echo $dest['name']; ?>"
                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                            </div>
                            <h6 class="fw-bold" style="color: #1a1a1b; font-size: 14px;"><?php echo $dest['name']; ?></h6>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <!--====== Start Favourite Places Section ======-->
    <section class="favourite-places-section pb-80" style="background-color: #fff;">
        <div class="container">
            <div class="section-title text-center mb-50 wow fadeInDown">
                <h2 class="title fw-bold" style="font-size: 36px; color: #1a1a1b;">Favourite Places To Stay</h2>
            </div>
            <div class="row g-4 scroll-mobile-x">
                <?php
                $stay_places = [
                    ['city' => 'Delhi', 'link' => 'hotel.php?location=Delhi'],
                    ['city' => 'Mumbai', 'link' => 'hotel.php?location=Mumbai'],
                    ['city' => 'Bangalore', 'link' => 'hotel.php?location=Bangalore'],
                    ['city' => 'Jaipur', 'link' => 'hotel.php?location=Jaipur'],
                    ['city' => 'Chennai', 'link' => 'hotel.php?location=Chennai'],
                ];

                foreach ($stay_places as $place) {
                    ?>
                    <div class="col-lg-2 col-md-4 col-sm-6 wow fadeInUp" style="flex: 0 0 calc(20%); max-width: calc(20%);">
                        <div class="stay-card p-3 rounded-4 border transition-all hover-translate-y d-flex justify-content-between align-items-center"
                            style="background: #f8fafc; border-color: #e2e8f0 !important; border-radius: 12px !important;">
                            <div>
                                <h6 class="fw-bold mb-1" style="color: #1a1a1b; font-size: 14px;">
                                    <?php echo $place['city']; ?>
                                </h6>
                                <a href="<?php echo $place['link']; ?>" class="small fw-bold text-decoration-none"
                                    style="color: #00a79d !important; font-size: 12px;">Explore <i
                                        class="fas fa-chevron-right ms-1" style="font-size: 7px;"></i></a>
                            </div>
                            <div class="stay-icon text-end">
                                <i class="fas fa-hotel" style="color: #00a79d; opacity: 0.2; font-size: 24px;"></i>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <style>
        @media (max-width: 991px) {
            .favourite-places-section .col-lg-2 {
                flex: 0 0 33.333% !important;
                max-width: 33.333% !important;
            }
        }

        @media (max-width: 767px) {
            .favourite-places-section .col-lg-2 {
                flex: 0 0 50% !important;
                max-width: 50% !important;
            }
        }

        @media (max-width: 575px) {
            .favourite-places-section .col-lg-2 {
                flex: 0 0 100% !important;
                max-width: 100% !important;
            }
        }

        .hover-scale:hover {
            transform: scale(1.1);
        }

        .scroll-mobile-x {
            display: flex;
            flex-wrap: wrap;
        }
    </style>

    <script>
        function quickSearch(from, to) {
            // Find inputs in the search form
            const fromInput = document.getElementById('fromCityInput');
            const toInput = document.getElementById('toCityInput');
            const fromDisplay = document.getElementById('fromCityDisplay');
            const toDisplay = document.getElementById('toCityDisplay');

            if (fromInput && toInput) {
                // Pre-fill
                fromInput.value = from;
                toInput.value = to;

                // Update displays
                fromDisplay.querySelector('.segment-value').innerText = from.split(' (')[0];
                fromDisplay.querySelector('.segment-sub').innerText = `[${from.split('(')[1].replace(')', '')}] Airport`;
                toDisplay.querySelector('.segment-value').innerText = to.split(' (')[0];
                toDisplay.querySelector('.segment-sub').innerText = `[${to.split('(')[1].replace(')', '')}] Airport`;

                // Scroll to search section
                window.scrollTo({ top: 0, behavior: 'smooth' });

                // Suggest selecting a date
                Swal.fire({
                    title: 'Route Selected!',
                    text: 'Please select your departure date to find flights.',
                    icon: 'info',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        }
    </script>
    <!--====== End Top Routes Section ======-->


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
    </section><!--====== End Gallery Section ======-->


    <?php include 'includes/footer.php'; ?>



    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ===== FLIGHT API KEY =====
        // const FLIGHT_API_KEY = '69bb903f0c5c5d91caaeeb3c';  limit cors
        const FLIGHT_API_KEY = '6b081ac62cf22e3e6b7820e4ba8de01b8';

        // ===== Fallback city list (used when API fails) =====
        const fallbackCities = [
            { city: "Delhi", code: "DEL", airport: "Indira Gandhi International Airport" },
            { city: "Mumbai", code: "BOM", airport: "Chhatrapati Shivaji International Airport" },
            { city: "Bangalore", code: "BLR", airport: "Kempegowda International Airport" },
            { city: "Chennai", code: "MAA", airport: "Chennai International Airport" },
            { city: "Kolkata", code: "CCU", airport: "Netaji Subhas Chandra Bose International Airport" },
            { city: "Hyderabad", code: "HYD", airport: "Rajiv Gandhi International Airport" },
            { city: "Ahmedabad", code: "AMD", airport: "Sardar Vallabhbhai Patel International Airport" },
            { city: "Pune", code: "PNQ", airport: "Pune Airport" },
            { city: "Goa", code: "GOI", airport: "Goa International Airport" },
            { city: "Kochi", code: "COK", airport: "Cochin International Airport" },
            { city: "Dubai", code: "DXB", airport: "Dubai International Airport" },
            { city: "London", code: "LHR", airport: "Heathrow Airport" },
            { city: "Singapore", code: "SIN", airport: "Changi Airport" },
            { city: "Bangkok", code: "BKK", airport: "Suvarnabhumi Airport" },
            { city: "New York", code: "JFK", airport: "John F. Kennedy International Airport" }
        ];

        // ===== Trip Type Tabs =====
        document.querySelectorAll('.trip-type-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.trip-type-tab').forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                const type = tab.dataset.type;
                const radio = document.getElementById(type);
                if (radio) radio.checked = true;

                if (type === 'oneWay') {
                    document.getElementById('returnDayDisplay').innerText = '--';
                    document.getElementById('returnMsgDisplay').innerHTML = '<div class="segment-month">Select</div><div class="segment-week">Return Date</div>';
                    document.getElementById('returnDateInput').removeAttribute('required');
                } else {
                    document.getElementById('returnDateInput').setAttribute('required', 'true');
                    updateDateDisplay('return');
                }
            });
        });

        // ===== City Search (Local Fallback) =====
        let searchTimer = null;

        function setupCitySelection(inputId, displayId) {
            const input = document.getElementById(inputId);
            const display = document.getElementById(displayId);
            const wrapper = input.closest('.city-wrapper');
            const dropdown = wrapper.querySelector('.city-dropdown');

            // Click on display -> show input
            display.addEventListener('click', () => {
                display.style.display = 'none';
                input.style.display = 'block';
                input.value = '';
                input.focus();
                renderDropdown(fallbackCities, dropdown);
            });

            // Type to search
            input.addEventListener('input', () => {
                clearTimeout(searchTimer);
                const query = input.value.trim();
                if (query.length < 2) {
                    renderDropdown(fallbackCities, dropdown);
                    return;
                }

                searchTimer = setTimeout(() => {
                    // Using local fallback list for city search to avoid unnecessary API keys/latency
                    renderDropdown(filterLocal(query), dropdown);
                }, 300);
            });

            function filterLocal(q) {
                return fallbackCities.filter(c =>
                    c.city.toLowerCase().includes(q.toLowerCase()) ||
                    c.code.toLowerCase().includes(q.toLowerCase()) ||
                    c.airport.toLowerCase().includes(q.toLowerCase())
                );
            }

            function renderDropdown(list, dropdown) {
                dropdown.innerHTML = '';
                if (!list.length) {
                    dropdown.innerHTML = '<div class="p-2 text-muted" style="font-size:12px">No airports found</div>';
                }
                list.forEach(c => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'dropdown-item';
                    btn.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center gap-2">
                            <div style="min-width:0">
                                <div class="city-row-city">${c.city}</div>
                                <div class="city-row-airport">${c.airport}</div>
                            </div>
                            <div class="city-row-code">${c.code}</div>
                        </div>`;
                    btn.onclick = () => {
                        input.value = `${c.city} (${c.code})`;
                        display.querySelector('.segment-value').innerText = c.city;
                        display.querySelector('.segment-sub').innerText = `[${c.code}] ${c.airport}`;
                        input.style.display = 'none';
                        display.style.display = 'block';
                        dropdown.style.display = 'none';
                        dropdown.classList.remove('show');
                    };
                    dropdown.appendChild(btn);
                });
                dropdown.style.display = 'block';
                dropdown.classList.add('show');
            }
        }

        setupCitySelection('fromCityInput', 'fromCityDisplay');
        setupCitySelection('toCityInput', 'toCityDisplay');

        // ===== Swap Cities =====
        document.querySelector('.swap-icon').addEventListener('click', () => {
            const fromDisplay = document.getElementById('fromCityDisplay');
            const toDisplay = document.getElementById('toCityDisplay');
            const fromInput = document.getElementById('fromCityInput');
            const toInput = document.getElementById('toCityInput');

            // Swap input values
            const tempVal = fromInput.value;
            fromInput.value = toInput.value;
            toInput.value = tempVal;

            // Swap displayed city
            const tempCity = fromDisplay.querySelector('.segment-value').innerText;
            const tempSub = fromDisplay.querySelector('.segment-sub').innerText;
            fromDisplay.querySelector('.segment-value').innerText = toDisplay.querySelector('.segment-value').innerText;
            fromDisplay.querySelector('.segment-sub').innerText = toDisplay.querySelector('.segment-sub').innerText;
            toDisplay.querySelector('.segment-value').innerText = tempCity;
            toDisplay.querySelector('.segment-sub').innerText = tempSub;
        });

        // ===== Date Display =====
        function updateDateDisplay(type) {
            const input = document.getElementById(type + 'DateInput');
            if (!input || !input.value) return;
            const date = new Date(input.value);
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            document.getElementById(type + 'DayDisplay').innerText = date.getDate();
            document.getElementById(type + 'MonthDisplay').innerText = months[date.getMonth()] + "'" + String(date.getFullYear()).substr(-2);
            document.getElementById(type + 'WeekDisplay').innerText = days[date.getDay()];
        }

        ['depart', 'return'].forEach(type => {
            const seg = document.getElementById(type + 'Segment');
            const input = document.getElementById(type + 'DateInput');
            seg.addEventListener('click', () => input.showPicker());
            input.addEventListener('change', () => {
                updateDateDisplay(type);
                if (type === 'depart' && document.getElementById('roundTrip').checked) {
                    document.getElementById('returnDateInput').min = input.value;
                }
            });
        });

        // ===== DATE PICKER INITIALIZATION =====
        function initFlightDates() {
            const departInput = document.getElementById('departDateInput');
            const returnInput = document.getElementById('returnDateInput');

            // Set Today
            let today = new Date();
            let todayStr = today.toISOString().split('T')[0];

            // Default to today if empty or set to weird 2031 bug
            if (!departInput.value || departInput.value.includes('2031')) {
                departInput.value = todayStr;
            }
            departInput.min = todayStr;

            // Sync displays
            updateDateDisplay('depart');

            if (document.getElementById('roundTrip').checked) {
                if (!returnInput.value || returnInput.value.includes('2031')) {
                    let tomorrow = new Date();
                    tomorrow.setDate(today.getDate() + 1);
                    returnInput.value = tomorrow.toISOString().split('T')[0];
                }
                returnInput.min = departInput.value;
                updateDateDisplay('return');
            }
        }

        // Call on load
        document.addEventListener('DOMContentLoaded', initFlightDates);

        // ===== Traveller Dropdown (position:fixed to escape overflow) =====
        let adultCount = 1, childCount = 0, infantCount = 0, travelClass = 'Economy';

        function positionTravellerDropdown() {
            const trigger = document.getElementById('travellerTrigger');
            const dd = document.getElementById('travellerDropdown');
            const rect = trigger.getBoundingClientRect();
            dd.style.top = (rect.bottom + 6) + 'px';

            if (window.innerWidth <= 768) {
                // Center horizontally on mobile
                dd.style.left = '12px';
                dd.style.right = '12px';
                dd.style.width = 'calc(100vw - 24px)';
            } else {
                // Align right edge of dropdown to right edge of trigger
                const ddWidth = 280;
                let left = rect.right - ddWidth;
                if (left < 8) left = 8; // don't go off left edge
                dd.style.left = left + 'px';
                dd.style.right = 'auto';
                dd.style.width = '280px';
            }
        }

        document.getElementById('travellerTrigger').addEventListener('click', e => {
            if (e.target.closest('#travellerDropdown')) return;
            const dd = document.getElementById('travellerDropdown');
            const isVisible = dd.style.display === 'block';
            closeAllDropdowns();
            if (!isVisible) {
                positionTravellerDropdown();
                dd.style.display = 'block';
                dd.classList.add('show');
            }
        });

        // Reposition on scroll/resize
        window.addEventListener('scroll', () => {
            const dd = document.getElementById('travellerDropdown');
            if (dd.style.display === 'block') positionTravellerDropdown();
        });
        window.addEventListener('resize', () => {
            const dd = document.getElementById('travellerDropdown');
            if (dd.style.display === 'block') positionTravellerDropdown();
        });

        function updateCount(type, delta) {
            if (type === 'adult') adultCount = Math.max(1, adultCount + delta);
            if (type === 'child') childCount = Math.max(0, childCount + delta);
            if (type === 'infant') infantCount = Math.max(0, infantCount + delta);
            document.getElementById('adultCount').innerText = adultCount;
            document.getElementById('childCount').innerText = childCount;
            document.getElementById('infantCount').innerText = infantCount;
            document.getElementById('hiddenAdults').value = adultCount;
            document.getElementById('hiddenChildren').value = childCount;
            document.getElementById('hiddenInfants').value = infantCount;
            document.getElementById('travellerCountDisplay').innerText = adultCount + childCount + infantCount;
        }

        function setTravelClass(cls, btn) {
            travelClass = cls;
            document.getElementById('hiddenClass').value = cls;
            document.getElementById('travelClassDisplay').innerText = cls;
            btn.closest('.row').querySelectorAll('.btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        }

        function toggleDropdown(id) {
            const dd = document.getElementById(id);
            dd.style.display = 'none';
            dd.classList.remove('show');
        }

        function closeAllDropdowns() {
            document.querySelectorAll('.city-dropdown').forEach(d => { d.style.display = 'none'; d.classList.remove('show'); });
            document.querySelectorAll('.city-input-new').forEach(i => { i.style.display = 'none'; });
            document.querySelectorAll('.city-display-new').forEach(d => { d.style.display = 'block'; });
            const td = document.getElementById('travellerDropdown');
            if (td) { td.style.display = 'none'; td.classList.remove('show'); }
        }

        document.addEventListener('click', e => {
            if (!e.target.closest('.search-segment') && !e.target.closest('#travellerTrigger')) {
                closeAllDropdowns();
            }
        });

        // ===== LIVE FLIGHT SEARCH via flightapi.io =====
        document.getElementById('searchForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const from = formData.get('from');
            const to = formData.get('to');
            const rawDate = formData.get('depart_date');

            if (!from || !to) {
                Swal.fire({ icon: 'warning', title: 'Missing Fields', text: 'Please fill in all fields.' });
                return;
            }
            if (from === to) {
                Swal.fire({ icon: 'error', title: 'Invalid Selection', text: 'Origin and destination cannot be the same.' });
                return;
            }

            const fromMatch = from.match(/\(([A-Z]{3})\)/);
            const toMatch = to.match(/\(([A-Z]{3})\)/);
            if (!fromMatch || !toMatch) {
                Swal.fire({ icon: 'error', title: 'Select Airport', text: 'Please select a valid city with an airport code from the dropdown.' });
                return;
            }

            const fromIata = fromMatch[1];
            const toIata = toMatch[1];

            // LOG SEARCH IN BACKGROUND
            const logData = new FormData();
            logData.append('form_type', 'flight_search');
            logData.append('from', from);
            logData.append('to', to);
            logData.append('depart_date', rawDate);
            logData.append('tripType', formData.get('tripType') || 'One Way');
            logData.append('adults', formData.get('adults') || 1);
            logData.append('children', formData.get('children') || 0);
            logData.append('infants', formData.get('infants') || 0);
            logData.append('travel_class', formData.get('travel_class') || 'Economy');
            logData.append('mobile', formData.get('mobile'));
            fetch('submit.php', { method: 'POST', body: logData })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'error' && data.redirect) {
                        Swal.close();
                        Swal.fire({ icon: 'warning', title: 'Login Required', text: data.message, confirmButtonColor: '#F7921E' })
                            .then(() => {
                                const sep = data.redirect.includes('?') ? '&' : '?';
                                window.location.href = data.redirect + sep + "return_url=" + encodeURIComponent(window.location.href);
                            });
                        throw new Error('Redirecting...');
                    }
                });
            const dt = new Date(rawDate);
            const year = dt.getFullYear();
            const month = dt.getMonth() + 1;
            const day = dt.getDate();

            Swal.fire({
                title: '✈ Searching Flights...',
                html: `<b>${from}</b> → <b>${to}</b><br><small>${rawDate}</small>`,
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            // FlightAPI.io – Live Departure Schedule
            // const apiUrl = `https://api.flightapi.io/schedule/v2/${FLIGHT_API_KEY}?mode=dep&iata=${fromIata}&year=${year}&month=${month}&day=${day}&page=1`;
            fetch('flights.json')
                // fetch(apiUrl)
                .then(r => {
                    if (!r.ok) throw new Error('API error: ' + r.status + ' - Please check if your API key is valid.');
                    return r.json();
                })
                .then(apiData => {
                    Swal.close();
                    const container = document.getElementById('flightsContainer');
                    container.innerHTML = '';

                    document.getElementById('searchResults').style.display = 'block';
                    document.getElementById('searchResults').scrollIntoView({ behavior: 'smooth', block: 'start' });

                    // ✅ Filter flights to match the destination airport (toIata)
                    let flights = [];
                    if (apiData?.data?.flights) {
                        flights = apiData.data.flights;
                        // Filter by destination IATA if toIata is provided
                        if (toIata) {
                            flights = flights.filter(f =>
                                (f.airport?.fs && f.airport.fs.toUpperCase() === toIata.toUpperCase()) ||
                                (f.airport?.city && f.airport.city.toLowerCase().includes(to.toLowerCase()))
                            );
                        }
                    }

                    if (!flights.length) {
                        container.innerHTML = `
                        <div class="alert alert-info text-center py-4">
                            <strong>No direct flights found to your exact destination today.</strong><br>
                            <small class="text-muted">You can view all departing flights from ${fromIata} instead.</small>
                            <button class="btn btn-primary btn-sm d-block mx-auto mt-3 rounded-pill px-4" onclick="renderAllDepartures()">Show All Departures</button>
                        </div>`;

                        window.renderAllDepartures = () => {
                            const apiTitle = apiData?.data?.header?.title || `Departures from ${fromIata}`;
                            document.querySelector('#searchResults h4').innerHTML = `<i class="fas fa-plane me-2"></i>${apiTitle}`;
                            renderFlights(apiData.data.flights, fromIata);
                        };
                        return;
                    }

                    // Update heading with API result title
                    const displayTitle = apiData?.data?.header?.title || `Flights to ${to}`;
                    document.querySelector('#searchResults h4').innerHTML = `<i class="fas fa-plane me-2"></i>${displayTitle}`;
                    renderFlights(flights, fromIata);
                })
                .catch(err => {
                    console.error('Flight API Error:', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'API Error',
                        html: `<b>Could not fetch live flight data.</b><br><small>${err.message}</small><br><br>
                           <small class="text-muted">Check your API key or network connection.<br>
                           You can get a free key at <a href="https://flightapi.io" target="_blank">flightapi.io</a></small>`
                    });
                });
        });

        // ===== Render Flights Helper =====
        function renderFlights(flights, fromIata) {
            const container = document.getElementById('flightsContainer');
            container.innerHTML = '';

            flights.slice(0, 20).forEach((f, idx) => {
                const depTime = f.departureTime?.time24 || '';
                const arrTime = f.arrivalTime?.time24 || '';

                const airline = f.carrier?.name || 'Unknown Airline';
                const flightNo = (f.carrier?.fs || '') + (f.carrier?.flightNumber || '');

                const toIata = f.airport?.fs || f.airport?.city || '---';

                const status = 'Scheduled'; // API Doesn't provide status 
                const price = Math.floor(Math.random() * 10000) + 3999;

                const depStr = depTime ? depTime.substring(0, 5) : '--:--';
                const arrStr = arrTime ? arrTime.substring(0, 5) : '--:--';

                const statusColor = 'secondary';

                container.innerHTML += `
                <div class="p-3 mb-3 border rounded-3 bg-white shadow-sm">
                    <div class="row align-items-center g-2">

                        <div class="col-md-3 col-6">
                            <div class="fw-bold text-primary">
                                <i class="fas fa-plane me-1"></i>${airline}
                            </div>
                            <small class="text-muted">${flightNo}</small>
                            <span class="badge bg-${statusColor} ms-2 d-none d-md-inline">${status}</span>
                        </div>

                        <div class="col-md-2 col-3 text-center">
                            <div class="fw-bold fs-5 text-dark">${depStr}</div>
                            <div class="small text-muted fw-semibold">${fromIata}</div>
                        </div>

                        <div class="col-md-2 col text-center text-muted">
                            <i class="fas fa-long-arrow-alt-right"></i>
                            <div style="font-size:10px">Non-stop</div>
                        </div>

                        <div class="col-md-2 col-3 text-center">
                            <div class="fw-bold fs-5 text-dark">${arrStr}</div>
                            <div class="small text-muted fw-semibold">${toIata}</div>
                        </div>

                        <div class="col-md-3 col-12 text-end d-flex justify-content-md-end justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold text-success fs-5">₹${price.toLocaleString('en-IN')}</div>
                                <div style="font-size:11px" class="text-muted">per person</div>
                            </div>

                            <button class="btn btn-sm ms-3 fw-bold rounded-pill px-4"
                                style="background:linear-gradient(135deg,#FF6B35,#F7C59F);color:#fff;"
                                onclick="confirmBookFlight('${airline}', '${flightNo}', '${fromIata}', '${toIata}', '${depStr}', '${arrStr}', ${price})">
                                BOOK
                            </button>
                        </div>

                    </div>
                </div>`;
            });
        }

        // ===== Booking Confirmation =====
        function confirmBookFlight(airline, flightNo, from, to, dep, arr, price) {
            Swal.fire({
                title: 'Confirm Booking',
                html: `
                    <div class="text-start">
                        <p><b>Airline:</b> ${airline} (${flightNo})</p>
                        <p><b>Route:</b> ${from} → ${to}</p>
                        <p><b>Departure:</b> ${dep} &nbsp; <b>Arrival:</b> ${arr}</p>
                        <p class="text-success fw-bold">Price: ₹${price.toLocaleString('en-IN')} /person</p>
                    </div>`,
                showCancelButton: true,
                confirmButtonText: '✅ Confirm & Book',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#FF6B35'
            }).then(result => {
                if (result.isConfirmed) {
                    // Save booking request to DB via submit.php
                    const fd = new FormData(document.getElementById('searchForm'));
                    fd.set('form_type', 'flight');
                    fd.set('flight_number', flightNo);
                    fd.set('airline', airline);
                    fd.set('price', price);

                    fetch('submit.php', { method: 'POST', body: fd })
                        .then(r => r.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Booking Request Sent!',
                                    html: `Your request for <b>${airline} ${flightNo}</b> has been submitted.<br><small>Our team will contact you shortly.</small>`,
                                    confirmButtonColor: '#FF6B35'
                                });
                            } else if (data.redirect) {
                                Swal.fire({ icon: 'warning', title: 'Login Required', text: data.message, confirmButtonColor: '#F7921E' })
                                    .then(() => {
                                        const sep = data.redirect.includes('?') ? '&' : '?';
                                        window.location.href = data.redirect + sep + "return_url=" + encodeURIComponent(window.location.href);
                                    });
                            } else {
                                Swal.fire({ icon: 'error', title: 'Oops...', text: data.message });
                            }
                        }).catch((error) => {
                            if (error.message !== 'Redirecting...') {
                                Swal.fire('Error', 'Selection failed', 'error');
                            }
                        });
                }
            });
        }
    </script>
    <?php include 'includes/footer.php'; ?>
</body>

</html>