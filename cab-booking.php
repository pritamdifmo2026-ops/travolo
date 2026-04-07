<?php 
include_once 'db.php';
include_once 'auth.php'; 
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <!--====== Required meta tags ======-->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="Adventure, Tours, Travel">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!--====== Title ======-->
    <title>Travelo - Tours and Travel</title>
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
        .city-wrapper {
            position: relative;
        }

        .city-input {
            position: relative;
            background: transparent;
            z-index: 2;
            padding: 1rem;
        }

        .city-display {
            position: absolute;
            inset: 0;
            padding: .375rem .75rem;
            pointer-events: none;
            background: #fff;
            border: 1px solid #ced4da;
            border-radius: .375rem;
            display: none;
        }

        .city-display .city-name {
            font-weight: 600;
            line-height: 1.1;
        }

        .city-display .city-airport {
            font-size: .75rem;
            color: #6c757d;
        }

        .city-dropdown {
            width: 100%;
            max-height: 260px;
            overflow-y: auto;
        }

        /* Unique Professional Hero Buttons */
        .hero-content .main-btn {
            height: 60px;
            padding: 5px 5px 5px 35px;
            border-radius: 35px;
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 0.5px;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            border: none;
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
        }

        .hero-content .main-btn i {
            width: 50px;
            height: 50px;
            background: #fff;
            color: #1a1a1a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 20px;
            font-size: 15px;
            transition: all 0.4s ease;
        }

        /* Explore More Theme (Original Teal-Red Sync) */
        .hero-content .main-btn.primary-btn {
            background: linear-gradient(135deg, #00a79d 0%, #00796b 100%);
            color: #fff;
            box-shadow: none;
        }

        .hero-content .main-btn.primary-btn:hover {
            transform: translateY(-5px);
            box-shadow: none;
            background: linear-gradient(135deg, #00796b 0%, #00a79d 100%);
        }

        .hero-content .main-btn.primary-btn:hover i {
            transform: rotate(-15deg) translateX(5px) translateY(-5px);
            background: #fff;
            color: #00a79d;
        }

        /* Learn More Theme (Original Maroon-Red Sync) */
        .hero-content .main-btn.secondary-btn {
            background: linear-gradient(135deg, #bf1e2e 0%, #8e1622 100%);
            color: #fff;
            box-shadow: none;
            padding: 5px 5px 5px 35px !important;
        }

        .hero-content .main-btn.secondary-btn i {
            background: #fff;
            color: #bf1e2e;
        }

        .hero-content .main-btn.secondary-btn:hover {
            transform: translateY(-5px);
            box-shadow: none;
            background: linear-gradient(135deg, #8e1622 0%, #bf1e2e 100%);
        }

        .hero-content .main-btn.secondary-btn:hover i {
            background: #fff;
            color: #8e1622;
        }

        .cab-wrapper {
            position: relative;
        }

        .cab-input {
            position: relative;
            background: transparent;
            z-index: 2;
            padding: 1rem;
        }

        .cab-display {
            position: absolute;
            inset: 0;
            padding: 8px 14px 8px 50px;
            pointer-events: none;
            background: #fff;
            border: 1.5px solid #eee;
            border-radius: 12px;
            display: none;
            z-index: 5;
            flex-direction: column;
            justify-content: center;
        }

        .cab-display .cab-name {
            font-weight: 700;
            line-height: 1.2;
            color: #1a1a1a;
            font-size: 15px;
        }

        .cab-display .cab-airport {
            font-size: 11px;
            color: #999;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .cab-dropdown {
            width: 100%;
            max-height: 260px;
            overflow-y: auto;
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            background: #fff;
            border: 1px solid #eee;
            border-radius: 0 0 14px 14px;
            display: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .cab-dropdown.show { display: block; }

        .cursor-pointer {
            cursor: pointer;
        }

        /* Professional Cab Form Overhaul */
        .services-seciton {
            margin-top: -80px;
            position: relative;
            z-index: 10;
        }

        .booking-form-wrapper {
            background: #fff;
            border-radius: 24px;
            padding: 25px 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.08);
            border: 1px solid rgba(0,0,0,0.05);
        }

        /* Custom Trip Type Selector (Segmented Tabs) */
        .trip-type-container {
            display: flex;
            background: #f8f9fa;
            padding: 5px;
            border-radius: 12px;
            margin-bottom: 20px;
            width: fit-content;
        }

        .trip-type-item {
            position: relative;
        }

        .trip-type-item input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .trip-type-item label {
            padding: 10px 25px;
            font-size: 14px;
            font-weight: 700;
            color: #666;
            cursor: pointer;
            border-radius: 10px;
            transition: all 0.3s ease;
            margin-bottom: 0;
            display: block;
        }

        .trip-type-item input[type="radio"]:checked + label {
            background: #fff;
            color: #00a79d;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        /* Form Controls */
        .form-group-custom {
            margin-bottom: 0;
        }

        .form-group-custom label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #999;
            margin-bottom: 4px;
            padding-left: 2px;
        }

        .input-with-icon {
            position: relative;
            height: 52px;
            display: block;
        }

        .input-with-icon i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #00a79d;
            font-size: 16px;
            z-index: 5;
        }

        .input-with-icon .form-control,
        .input-with-icon .nice-select {
            padding-left: 50px !important;
            height: 52px;
            border: 1.5px solid #eee !important;
            border-radius: 12px !important;
            font-weight: 600;
            color: #1a1a1a;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #fff;
        }

        .input-with-icon .form-control:focus {
            border-color: #00a79d !important;
            box-shadow: 0 0 0 4px rgba(0, 167, 157, 0.1);
            background: #fff;
        }

        /* Time Picker Dropdown Improvements */
        .time-picker-item .input-group-custom {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
        }
        .time-picker-item .hour-input,
        .time-picker-item .minute-input {
            width: 70px !important;
            height: 45px;
            text-align: center;
            border: 1.5px solid #eee;
            border-radius: 8px;
            font-weight: 700;
            color: #333 !important;
        }
        .time-picker-item .ampm-select {
            width: 80px !important;
            height: 45px;
            border: 1.5px solid #eee;
            border-radius: 8px;
            font-weight: 700;
            padding: 0 10px;
            color: #333 !important;
            font-size: 14px;
        }

        /* Submit Button */
        .btn-submit-premium {
            background: linear-gradient(135deg, #00a79d 0%, #00796b 100%);
            color: #fff;
            border: none;
            height: 52px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: none;
            margin-top: 5px;
        }

        .btn-submit-premium:hover {
            transform: translateY(-3px);
            box-shadow: none;
            color: #fff;
        }

        /* Airport Transfer Cards */
        .airport-transfer-section {
            padding: 100px 0 20px; /* Reduced bottom padding from 60px to 20px */
            background: #fff;
        }

        .sub-title {
            display: inline-block !important;
            font-size: 14px !important;
            font-weight: 800 !important;
            letter-spacing: 1px !important;
            padding: 5px 18px !important;
            background: rgba(0, 167, 157, 0.1) !important;
            color: #00a79d !important;
            border-radius: 8px !important;
            margin-bottom: 20px !important;
            text-transform: uppercase !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        .transfer-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-top: 40px;
        }

        .transfer-card {
            position: relative;
            height: 240px;
            border-radius: 20px;
            overflow: hidden;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: all 0.4s ease;
        }

        .transfer-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .transfer-card:hover img { transform: scale(1.1); }

        .transfer-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.2) 60%, transparent 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 25px;
            color: #fff;
        }

        .city-name { font-size: 22px; font-weight: 800; margin-bottom: 5px; }
        .transfer-label { font-size: 14px; color: rgba(255,255,255,0.8); }

        .badge-discount {
            position: absolute;
            top: 20px;
            left: 20px;
            background: #00a79d;
            color: #fff;
            padding: 5px 15px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 800;
            z-index: 5;
            box-shadow: 0 4px 10px rgba(0, 167, 157, 0.3);
        }

        /* Responsive Fixes */
        @media (max-width: 991px) {
            .transfer-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 767px) {
            .booking-form-wrapper { padding: 25px; }
            .trip-type-container { width: 100%; overflow-x: auto; }
            .trip-type-item label { white-space: nowrap; }
            .transfer-grid { grid-template-columns: 1fr; }
        }

        /* View More Button Styles */
        .view-more-transfers-container {
            display: flex;
            justify-content: center;
            margin-top: 40px;
        }

        .view-more-transfers-btn {
            background: #fff;
            border: 1.5px solid #00a79d;
            color: #00a79d;
            padding: 10px 30px;
            border-radius: 30px;
            font-weight: 800;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .view-more-transfers-btn:hover {
            background: #00a79d;
            color: #fff;
            box-shadow: 0 10px 20px rgba(0, 167, 157, 0.2);
        }

        .view-more-transfers-btn i {
            transition: transform 0.4s ease;
        }

        .view-more-transfers-btn.active i {
            transform: rotate(180deg);
        }

        /* Global Overseas Section Spacing */
        .cab-feature-section {
            padding: 30px 0 80px; /* Reduced top padding from 80px to 30px */
            background: #fff;
        }

        .cab-card {
            border-radius: 20px;
            background: #fff;
            border: 1px solid #eee;
            overflow: hidden;
            transition: all 0.4s ease;
            height: 100%;
        }

        .cab-card:hover {
            box-shadow: 0 15px 45px rgba(0,0,0,0.1);
            transform: translateY(-10px);
        }

        .cab-card-img {
            height: 250px;
            position: relative;
            overflow: hidden;
        }

        .cab-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.8s ease;
        }

        .cab-card-content {
            padding: 25px;
        }

        .cab-card-title {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .cab-price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px dashed #ddd;
        }

        .cab-price {
            font-size: 18px;
            font-weight: 800;
            color: #1a1a1a;
        }

        /* Benefits & Promo */
        .benefit-item {
            text-align: center;
            padding: 30px;
        }

        .benefit-icon {
            width: 70px;
            height: 70px;
            background: rgba(0, 167, 157, 0.1);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: #00a79d;
            margin-bottom: 20px;
        }

        .promo-banner {
            background: linear-gradient(135deg, #00a79d 0%, #004d40 100%);
            border-radius: 24px;
            padding: 60px;
            color: #fff;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            box-shadow: 0 15px 40px rgba(0, 167, 157, 0.2);
        }

        .promo-content {
            position: relative;
            z-index: 2;
            max-width: 60%;
        }

        .promo-img {
            position: absolute;
            right: -20px;
            bottom: -20px;
            width: 45%;
            z-index: 1;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.2));
        }

        /* Travel Insight Section (SEO & Info) */
        .info-insight-section {
            padding: 100px 0;
            background: #111;
            color: #ccc;
        }

        .insight-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 40px;
            border-radius: 20px;
            height: 100%;
            transition: all 0.4s ease;
        }

        .insight-card h3 {
            color: #fff;
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .insight-card .icon-box {
            width: 50px;
            height: 50px;
            background: rgba(0, 167, 157, 0.1);
            color: #00a79d;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 25px;
        }

        /* Exclusive Offers - UNIQUE TRAVOLO BRANDED SYSTEM */
        .cab-offers-section {
            background: #fff;
            padding: 80px 0 20px; /* Reduced bottom padding from 80px to 20px */
            position: relative;
        }

        .offer-card {
            background: #fff;
            border-radius: 12px;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            display: flex !important;
            flex-direction: column;
            margin: 15px 12px;
            text-decoration: none;
            color: inherit;
            height: 310px !important; /* Reduced from 380px to eliminate whitespace */
            overflow: hidden;
            border: 1px solid #f2f2f2;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03); 
            position: relative;
        }

        .offer-card:hover {
            box-shadow: 0 15px 35px rgba(0, 167, 157, 0.1);
            transform: translateY(-8px);
            border-color: #00a79d;
        }

        /* Travolo Elite Badge */
        .travolo-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #ffc107;
            color: #000;
            padding: 4px 10px;
            border-radius: 5px;
            font-size: 10px;
            font-weight: 800;
            z-index: 10;
            text-transform: uppercase;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        /* The Curved Split Banner Header - TRAVOLO THEMES */
        .offer-split-banner {
            display: flex;
            height: 180px;
            width: 100%;
            overflow: hidden;
            position: relative;
            background: #fdfdfd;
        }

        .banner-info {
            width: 60%;
            height: 100%;
            padding: 22px 25px; /* Increased padding-left to 25px */
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: #fff;
            position: relative;
            z-index: 2;
            border-top-right-radius: 100px 180px; 
            border-bottom-right-radius: 10px;
        }

        .banner-img {
            width: 50%;
            height: 100%;
            position: absolute;
            right: 0;
            top: 0;
            z-index: 1;
        }

        .banner-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Unique Travolo Color Gradients */
        .bg-travolo-main { background: linear-gradient(135deg, #00a79d 0%, #00796af0 100%); }
        .bg-travolo-dark { background: linear-gradient(135deg, #133a25 0%, #0c2518 100%); }
        .bg-travolo-alt { background: linear-gradient(135deg, #0288d1 0%, #01579b 100%); }
        .bg-travolo-gold { background: linear-gradient(135deg, #00a79d 0%, #004d40 100%); }

        .discount-text {
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 2px;
            opacity: 0.9;
        }

        .service-name {
            font-size: 14px; /* Reduced from 16px for a more refined look */
            font-weight: 900;
            margin-bottom: 10px;
            line-height: 1.2;
        }

        /* Use Code - TRAVOLO Style */
        .banner-use-code {
            background: #fff;
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 11px;
            color: #00a79d;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            width: fit-content;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border: 1px dashed #00a79d;
            text-transform: uppercase;
        }
        
        .copy-icon { color: #00a79d; font-size: 11px; }

        .offer-body {
            padding: 15px; /* Reduced from 20px */
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            background: #fff;
        }

        .offer-body h4 {
            font-size: 15px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 5px;
            line-height: 1.4;
        }

        .validity-text {
            font-size: 11px;
            color: #999;
            font-weight: 600;
        }

        /* Premium Vertical Hourly Rentals Section */
        .hourly-slider-section {
            padding: 40px 0 30px; /* Reduced bottom padding from 120px to 30px */
            background: #fff;
            position: relative;
        }

        .hourly-slider {
            margin: 0 -10px;
        }

        .hourly-card {
            position: relative;
            background: #fff;
            border-radius: 24px;
            overflow: visible; /* Important for the floating box overlap */
            margin: 20px 10px 50px; /* Extra bottom margin for floating box */
            height: 320px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: all 0.4s ease;
        }

        .hourly-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 24px;
        }

        .hourly-info-box {
            position: absolute;
            bottom: -45px; /* Lowered from -30px to show more image */
            left: 15px;
            right: 15px;
            background: #fff;
            border-radius: 20px;
            padding: 12px 18px; /* Reduced from 18px 20px for a more compact look */
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            z-index: 10;
        }

        .hourly-info-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px; /* Reduced margin */
        }

        .hourly-city-name {
            font-size: 15px; /* Reduced font size */
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 1px;
        }

        .hourly-loc-tag {
            font-size: 11px; /* Reduced font size */
            color: #999;
            font-weight: 500;
        }

        .hourly-card-icons {
            display: flex;
            gap: 8px;
            color: #eee;
            font-size: 20px;
        }

        .hourly-info-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 10px;
            border-top: 1px dashed #f0f0f0;
        }

        .hourly-price-row {
            display: flex;
            align-items: baseline;
            gap: 4px;
        }

        .hourly-price-from { font-size: 11px; color: #666; }
        .hourly-price-val { font-size: 18px; font-weight: 900; color: #1a1a1a; }

        .hourly-btn {
            font-size: 12px; /* Reduced font size */
            font-weight: 800;
            color: #ff5e37;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .hourly-btn:hover { color: #f7921e; }

        /* Slider Arrows for Hourly */
        .slick-prev-hourly, .slick-next-hourly {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 100;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #fff;
            border: 1px solid #eee;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #00a79d;
            font-size: 14px;
        }

        .slick-prev-hourly { left: -22px; }
        .slick-next-hourly { right: -22px; }

        .slick-prev-hourly:hover, .slick-next-hourly:hover {
            background: #00a79d;
            color: #fff;
            box-shadow: 0 5px 20px rgba(0, 167, 157, 0.3);
        }

        /* Floating Circular Arrows Overlaying the Slider - TRAVOLO COLOR */
        .offer-arrow { 
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 46px !important; 
            height: 46px !important; 
            border-radius: 50% !important; 
            border: none !important; 
            display: flex !important; 
            align-items: center; 
            justify-content: center; 
            cursor: pointer; 
            background: #fff !important;
            color: #00a79d !important;
            box-shadow: 0 8px 20px rgba(0,0,0,0.12) !important;
            z-index: 100;
            transition: all 0.3s ease;
        }
        .offer-arrow.prev-offer { left: -25px; }
        .offer-arrow.next-offer { right: -25px; }
        .offer-arrow:hover { background: #00a79d !important; color: #fff !important; transform: translateY(-50%) scale(1.1); }

        .view-all-container { text-align: center; margin-top: 20px; } /* Reduced from 40px */
        .view-all-btn { 
            display: inline-block; 
            background: #00a79d; 
            color: #fff; 
            padding: 8px 30px; 
            border-radius: 50px; 
            font-weight: 800; 
            font-size: 12px; 
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: none; /* Shadow removed */
        }
        .view-all-btn:hover { background: #133a25; transform: translateY(-3px); box-shadow: none; }

        /* Outstation Cabs Section - PREMIERE BESPOKE DESIGN */
        .outstation-section {
            padding: 40px 0 100px;
            background: #fff;
        }

        .outstation-card {
            background-image: url('assets/images/outstation/outstation_bg.jpg');
            background-size: cover;
            background-position: center;
            border-radius: 40px;
            padding: 80px 60px;
            position: relative;
            overflow: hidden;
            color: #fff;
            min-height: 550px;
            box-shadow: 0 40px 100px rgba(0, 167, 157, 0.15);
            display: flex;
            align-items: center;
        }

        .outstation-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(19, 58, 37, 0.9) 0%, rgba(0, 167, 157, 0.4) 100%);
            z-index: 1;
        }

        .outstation-content {
            position: relative;
            z-index: 5;
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 40px;
        }

        .outstation-text-box {
            max-width: 550px;
        }

        .outstation-discount {
            font-size: 13px;
            font-weight: 800;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            padding: 6px 15px;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .outstation-title {
            font-size: 52px;
            font-weight: 900;
            margin-bottom: 20px;
            line-height: 1.05;
            letter-spacing: -1px;
        }

        .outstation-sub-title {
            font-size: 18px;
            opacity: 0.85;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .outstation-book-btn {
            background: #fff;
            color: #133a25;
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 900;
            font-size: 15px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .outstation-book-btn:hover { 
            background: #00a79d; 
            color: #fff; 
            transform: scale(1.05); 
        }

        .outstation-tiles-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            width: 100%;
        }

        .outstation-tile {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 25px;
            padding: 25px;
            text-align: center;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .outstation-tile::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: transparent;
            transition: all 0.3s ease;
        }

        .outstation-tile:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-10px);
            border-color: rgba(255, 255, 255, 0.4);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .outstation-tile:hover::after {
            background: #00a79d;
        }

        .tile-img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            overflow: hidden;
            margin-bottom: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            transition: all 0.4s ease;
        }

        .outstation-tile:hover .tile-img {
            transform: scale(1.1) rotate(5deg);
            border-color: #00a79d;
        }

        .tile-img img { width: 100%; height: 100%; object-fit: cover; }

        .tile-city { 
            font-size: 20px; 
            font-weight: 800; 
            margin-bottom: 8px; 
            letter-spacing: -0.5px;
        }

        .tile-routes {
            font-size: 12px;
            opacity: 0.7;
            font-weight: 500;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
        }

        .overseas-card { background: #fff; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); transition: all 0.3s ease; border: 1px solid #eee; height: 100%; }
        .overseas-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); border-color: #00a79d; }
        .overseas-img-box { position: relative; height: 200px; }
        .overseas-img-box img { width: 100%; height: 100%; object-fit: cover; }
        .overseas-price-tag { position: absolute; bottom: 15px; left: 15px; background: rgba(0, 0, 0, 0.9); color: #fff; padding: 6px 15px; border-radius: 30px; font-size: 13px; backdrop-filter: blur(5px); }
        .overseas-body-content { padding: 25px; }
        .overseas-body-content h4 { font-size: 22px; font-weight: 800; margin-bottom: 10px; color: #133a25; }
        .overseas-body-content p { font-size: 14px; color: #6b7280; margin-bottom: 20px; }
        .overseas-action-btn { color: #00a79d; font-weight: 700; font-size: 14px; text-decoration: none; display: flex; align-items: center; }
        .overseas-action-btn:hover { color: #133a25; }

        /* Airport Transfer Cards */
        .transfer-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 30px; margin-bottom: 60px; }
        .transfer-card { position: relative; height: 240px; border-radius: 20px; overflow: hidden; cursor: pointer; }
        .transfer-card img { width: 100%; height: 100%; object-fit: cover; transition: all 0.6s ease; }
        .transfer-card:hover img { transform: scale(1.15); }
        .transfer-overlay { 
            position: absolute; 
            inset: 0; 
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, transparent 60%); 
            display: flex; 
            flex-direction: column; 
            justify-content: flex-end; 
            padding: 25px; 
            color: #fff;
        }
        .city-name { font-size: 24px; font-weight: 800; margin-bottom: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3); }
        .transfer-label { font-size: 12px; opacity: 0.8; font-weight: 500; font-family: 'Inter', sans-serif; }
        .badge-discount { position: absolute; top: 15px; right: 15px; background: #00a79d; color: #fff; font-size: 11px; font-weight: 700; padding: 5px 12px; border-radius: 30px; box-shadow: 0 4px 10px rgba(0,0,0,0.2); }

        @media (max-width: 1200px) {
            .outstation-tiles-grid { grid-template-columns: repeat(3, 1fr); }
        }

        @media (max-width: 991px) {
            .outstation-tiles-grid { grid-template-columns: repeat(2, 1fr); }
            .outstation-title { font-size: 40px; }
            .outstation-card { padding: 60px 30px; }
        }

        @media (max-width: 575px) {
            .outstation-tiles-grid { grid-template-columns: 1fr; }
            .outstation-card { padding: 50px 20px; min-height: auto; }
        }

        .time-dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1050;
            display: none;
            min-width: 280px;
            padding: 20px;
            background: #fff;
            border: 1px solid #eee;
            border-radius: 14px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        .time-dropdown-menu.show { display: block; }
    </style>
</head>

<body>
    
<?php include 'navbar.php'; ?>

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
                                    <div class="hero-content text-white">
                                        <span class="sub-title">Welcome to Travolo</span>
                                        <h1 data-animation="fadeInDown" data-delay=".4s">Tour Travel & Adventure</h1>
                                        <div class="hero-button" data-animation="fadeInRight" data-delay=".6s">
                                            <a href="about.php" class="main-btn primary-btn">Explore More<i class="fas fa-paper-plane"></i></a>
                                            <a href="about.php" class="main-btn secondary-btn">Learn More<i class="fas fa-paper-plane"></i></a>
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
                                    <div class="hero-content text-white">
                                        <span class="sub-title">Welcome to Travolo</span>
                                        <h1 data-animation="fadeInDown" data-delay=".4s">Tour Travel & Camping</h1>
                                        <div class="hero-button" data-animation="fadeInRight" data-delay=".6s">
                                            <a href="about.php" class="main-btn primary-btn">Explore More<i class="fas fa-paper-plane"></i></a>
                                            <a href="about.php" class="main-btn secondary-btn">Learn More<i class="fas fa-paper-plane"></i></a>
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
                                    <div class="hero-content text-white">
                                        <span class="sub-title">Welcome to Travolo</span>
                                        <h1 data-animation="fadeInDown" data-delay=".4s">Tour Travel & Adventure</h1>
                                        <div class="hero-button" data-animation="fadeInRight" data-delay=".6s">
                                            <a href="about.php" class="main-btn primary-btn">Explore More<i class="fas fa-paper-plane"></i></a>
                                            <a href="about.php" class="main-btn secondary-btn">Learn More<i class="fas fa-paper-plane"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="services-seciton">
            <div class="container">
                <div class="booking-form-wrapper">
                    <form id="cabSearchForm" action="cab-results.php" method="GET">
                        <input type="hidden" name="form_type" value="cab">
                        
                        <!-- Trip Type Switcher -->
                        <div class="trip-type-container">
                            <div class="trip-type-item">
                                <input type="radio" name="tripType" id="Transfer" value="Transfer" checked>
                                <label for="Transfer">Airport Transfer</label>
                            </div>
                            <div class="trip-type-item">
                                <input type="radio" name="tripType" id="Outstation" value="Outstation">
                                <label for="Outstation">Outstation</label>
                            </div>
                            <div class="trip-type-item">
                                <input type="radio" name="tripType" id="Hourly" value="Hourly">
                                <label for="Hourly">Hourly Rentals</label>
                            </div>
                        </div>

                        <div class="row g-4 align-items-end">
                            <!-- Pickup Type (Sub-toggle for Airport Transfer) -->
                            <div class="col-lg-3 col-md-6" id="pickupTypeWrapper">
                                <div class="form-group-custom">
                                    <label>Travel Type</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-exchange-alt"></i>
                                        <select name="pickupType" class="nice-select">
                                            <option value="To Airport">Go to Airport</option>
                                            <option value="From Airport">Pickup from Airport</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- From Location -->
                            <div class="col-lg-3 col-md-6" id="fromWrapper">
                                <div class="form-group-custom">
                                    <label>City / Airport</label>
                                    <div class="cab-wrapper" id="fromCabWrapper">
                                        <div class="input-with-icon">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <input type="text" name="from" class="form-control cab-input" placeholder="Type city or airport" required>
                                            <div class="cab-display"></div>
                                            <div class="cab-dropdown"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- To Location (Hidden for Hourly) -->
                            <div class="col-lg-3 col-md-6" id="toWrapper">
                                <div class="form-group-custom">
                                    <label>Destination</label>
                                    <div class="cab-wrapper" id="toCabWrapper">
                                        <div class="input-with-icon">
                                            <i class="fas fa-map-pin"></i>
                                            <input type="text" name="to" class="form-control cab-input" placeholder="Type destination">
                                            <div class="cab-display"></div>
                                            <div class="cab-dropdown"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pickup Date & Time -->
                            <div class="col-lg-3 col-md-6" id="pickupWrapper">
                                <div class="form-group-custom">
                                    <label>Pickup Date & Time</label>
                                    <div class="input-with-icon cursor-pointer" id="pickupTrigger">
                                        <i class="fas fa-calendar-alt"></i>
                                        <div class="form-control d-flex align-items-center justify-content-between">
                                            <span id="selectedPickupDate">Select Date</span>
                                            <span class="text-muted" id="selectedPickupTime">Select Time</span>
                                        </div>
                                        
                                        <!-- Custom Dropdown Menu -->
                                        <div class="time-dropdown-menu" id="pickupDropdown">
                                            <div class="mb-3">
                                                <label class="small text-muted mb-1">Pick Date</label>
                                                <input type="date" class="form-control" id="pickupDateInput" min="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                            <div class="time-picker-item">
                                                <label class="small text-muted mb-1">Pick Time</label>
                                                <div class="input-group-custom">
                                                    <input type="number" class="hour-input" id="hourInput" placeholder="HH" min="1" max="12" value="12">
                                                    <input type="number" class="minute-input" id="minuteInput" placeholder="MM" min="0" max="59" value="00">
                                                    <select class="ampm-select ignore-nice-select" id="ampmSelect">
                                                        <option value="AM">AM</option>
                                                        <option value="PM" selected>PM</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <button type="button" class="main-btn primary-btn w-100 mt-3 py-2">Set Time</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Return Date & Time (Only for Outstation) -->
                            <div class="col-lg-3 col-md-6 d-none" id="returnWrapper">
                                <div class="form-group-custom">
                                    <label>Return Date & Time</label>
                                    <div class="input-with-icon cursor-pointer" id="returnTrigger">
                                        <i class="fas fa-history"></i>
                                        <div class="form-control d-flex align-items-center justify-content-between">
                                            <span id="selectedReturnDate">Select Date</span>
                                            <span class="text-muted" id="selectedReturnTime">Select Time</span>
                                        </div>
                                        
                                        <div class="time-dropdown-menu" id="returnDropdown">
                                            <div class="mb-3">
                                                <label class="small text-muted mb-1">Return Date</label>
                                                <input type="date" class="form-control" id="returnDateInput" min="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                            <div class="time-picker-item">
                                                <label class="small text-muted mb-1">Return Time</label>
                                                <div class="input-group-custom">
                                                    <input type="number" class="hour-input" id="returnHourInput" placeholder="HH" min="1" max="12" value="12">
                                                    <input type="number" class="minute-input" id="returnMinuteInput" placeholder="MM" min="0" max="59" value="00">
                                                    <select class="ampm-select ignore-nice-select" id="returnAmpmSelect">
                                                        <option value="AM">AM</option>
                                                        <option value="PM" selected>PM</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <button type="button" class="main-btn primary-btn w-100 mt-3 py-2">Set Time</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Package (Only for Hourly) -->
                            <div class="col-lg-3 col-md-6 d-none" id="hourlyWrapper">
                                <div class="form-group-custom">
                                    <label>Rental Duration</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-clock"></i>
                                        <select name="duration" class="nice-select">
                                            <?php
                                            $p_res = $conn->query("SELECT * FROM cab_packages WHERE status=1 ORDER BY hours ASC");
                                            if ($p_res && $p_res->num_rows > 0) {
                                                while($p = $p_res->fetch_assoc()){
                                                    echo "<option value='{$p['package_name']}'>{$p['package_name']}</option>";
                                                }
                                            } else {
                                                echo "<option value='8hrs / 80km'>8hrs / 80km (Default)</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Mobile Number -->
                            <div class="col-lg-3 col-md-6">
                                <div class="form-group-custom">
                                    <label>Mobile Number</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-phone-alt"></i>
                                        <input type="tel" name="mobile" class="form-control" placeholder="10-digit number" required pattern="[0-9]{10}" maxlength="10">
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-lg-3 col-md-6">
                                <button type="submit" class="btn-submit-premium">
                                    Search Cabs <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Hidden Inputs to sync with custom picker -->
                        <input type="hidden" name="pickup_date" id="hiddenPickupDate">
                        <input type="hidden" name="pickup_time" id="hiddenPickupTime">
                        <input type="hidden" name="return_date" id="hiddenReturnDate">
                        <input type="hidden" name="return_time" id="hiddenReturnTime">
                    </form>
                </div>

                <!-- Exclusive Offers - DYNAMIC TRAVOLO SYSTEM -->
                <div class="cab-offers-section">
                    <div class="container" style="position: relative;">
                        <div class="d-flex align-items-center justify-content-center mb-60">
                            <h2 class="mb-0" style="font-weight: 900; font-size: 44px; color: #133a25;">Travolo Exclusive Offers</h2>
                        </div>
                        
                        <!-- Floating Arrows -->
                        <div class="offer-arrow prev-offer"><i class="fas fa-chevron-left"></i></div>
                        <div class="offer-arrow next-offer"><i class="fas fa-chevron-right"></i></div>

                        <div class="offer-slider">
                                <?php
                                $offers_res = $conn->query("SELECT * FROM cab_offers WHERE status=1 ORDER BY id DESC");
                                if ($offers_res && $offers_res->num_rows > 0) {
                                    while ($offer = $offers_res->fetch_assoc()) {
                                        ?>
                                        <a href="#" class="offer-card">
                                            <div class="travolo-badge"><?php echo htmlspecialchars($offer['badge']); ?></div>
                                            <div class="offer-split-banner">
                                                <div class="banner-info" style="background: <?php echo htmlspecialchars($offer['theme_color']); ?>;">
                                                    <div class="discount-text"><?php echo htmlspecialchars($offer['header_small']); ?></div>
                                                    <div class="service-name"><?php echo htmlspecialchars($offer['header_main']); ?></div>
                                                    <div class="banner-use-code">CODE: <b><?php echo htmlspecialchars($offer['promo_code']); ?></b> <i class="far fa-copy copy-icon"></i></div>
                                                </div>
                                                <div class="banner-img"><img src="<?php echo htmlspecialchars($offer['image_path']); ?>" alt="Offer"></div>
                                            </div>
                                            <div class="offer-body">
                                                <h4><?php echo htmlspecialchars($offer['main_title']); ?></h4>
                                                <div class="validity-text"><?php echo htmlspecialchars($offer['validity_text']); ?></div>
                                            </div>
                                        </a>
                                        <?php
                                    }
                                } else {
                                    echo "<div class='text-center w-100 py-5 text-muted'>New exclusive offers coming soon!</div>";
                                }
                                ?>
                        </div>

                        <!-- View All Button -->
                        <div class="view-all-container">
                            <a href="offers.php" class="view-all-btn">Discover All Offers</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!--====== Airport Transfer Section ======-->
        <section class="airport-transfer-section pb-100" style="padding-top: 0; margin-top: -30px;">
            <div class="container">
                <div class="row align-items-end mb-40">
                    <div class="col-lg-8">
                        <div class="section-title">
                            <span class="sub-title">ELITE TRANSFERS</span>
                            <h2 class="title" style="font-weight: 800;">Quick Airport Pickup & Drop</h2>
                        </div>
                    </div>
                </div>

                <div class="transfer-grid" id="transferGrid">
                    <?php
                    $transfers_res = $conn->query("SELECT * FROM cab_transfers WHERE status = 1 ORDER BY id ASC");
                    if ($transfers_res && $transfers_res->num_rows > 0) {
                        $counter = 0;
                        while ($t = $transfers_res->fetch_assoc()) {
                            $counter++;
                            $hiddenClass = ($counter > 6) ? 'd-none' : '';
                            ?>
                            <div class="transfer-card extra-transfer <?php echo $hiddenClass; ?>" data-id="<?php echo $counter; ?>" onclick="window.location.href='cab-results.php?from=<?php echo urlencode($t['city']); ?>&to=Airport'">
                                <img src="<?php echo htmlspecialchars($t['image_path']); ?>" alt="<?php echo htmlspecialchars($t['city']); ?>" loading="lazy">
                                <div class="badge-discount"><?php echo htmlspecialchars($t['badge_text'] ?: 'FIXED FARE'); ?></div>
                                <div class="transfer-overlay">
                                    <div class="d-flex justify-content-between align-items-end w-100">
                                        <div>
                                            <div class="city-name text-white"><?php echo htmlspecialchars($t['city']); ?></div>
                                            <div class="transfer-label text-white-50" style="font-size: 11px;"><?php echo htmlspecialchars($t['airport']); ?></div>
                                        </div>
                                        <div class="book-arrow" style="color: var(--travolo-teal); font-weight: 800; font-size: 13px;">
                                            Book <i class="fas fa-arrow-right"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<div class='text-center w-100 py-5 text-muted'>New transfer locations coming soon!</div>";
                    }
                    ?>
                </div>

                <?php if (isset($counter) && $counter > 6): ?>
                    <div class="view-more-transfers-container">
                        <button class="view-more-transfers-btn" onclick="toggleTransfers(this)">
                            <span>View All Transfers</span>
                            <i class="fas fa-arrow-down"></i>
                        </button>
                    </div>
                <?php endif; ?>

                <!-- Premium Overseas Transfers -->
                <div class="row align-items-end mb-40 mt-60">
                    <div class="col-lg-8">
                        <div class="section-title">
                            <span class="sub-title" style="color: #00a79d; font-weight: 800;">GLOBAL EXCELLENCE</span>
                            <h2 class="title" style="font-weight: 800;">Overseas Transfers</h2>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-40 justify-content-center text-center">
                    <?php
                    $over_res = $conn->query("SELECT * FROM cab_overseas WHERE status = 1 ORDER BY id DESC LIMIT 4");
                    if ($over_res && $over_res->num_rows > 0) {
                        while ($o = $over_res->fetch_assoc()) {
                            ?>
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <div class="overseas-card">
                                    <div class="overseas-img-box">
                                        <img src="<?php echo htmlspecialchars($o['image_path']); ?>" alt="<?php echo htmlspecialchars($o['city']); ?>" loading="lazy">
                                        <div class="overseas-price-tag">Starts <b><?php echo htmlspecialchars($o['price_starts']); ?></b></div>
                                    </div>
                                    <div class="overseas-body-content text-start">
                                        <h4><?php echo htmlspecialchars($o['city']); ?></h4>
                                        <p><?php echo htmlspecialchars($o['description']); ?></p>
                                        <a href="#cabSearchForm" class="overseas-action-btn">Book Overseas Ride <i class="fas fa-chevron-right ms-2"></i></a>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<div class='text-center w-100 py-5 text-muted'>Global transfer locations coming soon!</div>";
                    }
                    ?>
                </div>

                <!-- Premium Hourly Car Rentals Slider Section -->
                <div class="hourly-slider-section mt-60">
                    <div class="container">
                        <div class="row align-items-end mb-40">
                            <div class="col-lg-8">
                                <div class="section-title">
                                    <span class="sub-title" style="color: #00a79d; font-weight: 800;">LOCAL EXPLORATION</span>
                                    <h2 class="title" style="font-weight: 800;">Premium Hourly Rentals</h2>
                                </div>
                            </div>
                        </div>

                        <div class="hourly-slider">
                            <?php
                            $hourly_res = $conn->query("SELECT * FROM cab_hourly WHERE status = 1 ORDER BY id ASC");
                            if ($hourly_res && $hourly_res->num_rows > 0) {
                                while ($h = $hourly_res->fetch_assoc()) {
                                    ?>
                                    <div class="hourly-card">
                                        <img src="<?php echo htmlspecialchars($h['image_path']); ?>" alt="<?php echo htmlspecialchars($h['city']); ?>">
                                        <div class="hourly-info-box">
                                            <div class="hourly-info-top">
                                                <div>
                                                    <div class="hourly-city-name"><?php echo htmlspecialchars($h['city']); ?></div>
                                                    <div class="hourly-loc-tag"><?php echo htmlspecialchars($h['location_tag']); ?></div>
                                                </div>
                                                <div class="hourly-card-icons">
                                                    <i class="far fa-clock"></i>
                                                    <i class="fas fa-car-alt"></i>
                                                </div>
                                            </div>
                                            <div class="hourly-info-bottom">
                                                <div class="hourly-price-row">
                                                    <span class="hourly-price-from">From</span>
                                                    <span class="hourly-price-val">₹<?php echo number_format($h['price_per_hr']); ?></span>
                                                </div>
                                                <a href="cab-results.php?tripType=Hourly&from=<?php echo urlencode($h['city']); ?>&to=Local" class="hourly-btn">Book <i class="fas fa-arrow-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>        <!--====== Premium Outstation Cabs Section (BESPOKE UNIQUE DESIGN) ======-->
        <section class="outstation-section">
            <div class="container">
                <div class="outstation-card">
                    <div class="outstation-content">
                        <div class="row align-items-center">
                            <div class="col-xl-5">
                                <div class="outstation-text-box">
                                    <span class="outstation-discount">Exclusive: 15% Member Discount</span>
                                    <h2 class="outstation-title">The Elite <br>Intercity <span style="color: #00a79d;">Corridor</span></h2>
                                    <p class="outstation-sub-title">Bypass the ordinary. Our premium outstation service offers door-to-door luxury across India's most popular routes.</p>
                                    
                                    <a href="#cabSearchForm" class="outstation-book-btn">Secure Your Ride <i class="fas fa-shield-alt ms-2"></i></a>
                                </div>
                            </div>
                            <div class="col-xl-7">
                                <div class="outstation-tiles-grid">
                                    <?php
                                    $out_res = $conn->query("SELECT * FROM cab_outstation WHERE status=1 ORDER BY id ASC");
                                    if ($out_res && $out_res->num_rows > 0) {
                                        while ($o = $out_res->fetch_assoc()) {
                                            ?>
                                            <div class="outstation-tile">
                                                <div class="tile-img">
                                                    <img src="<?php echo htmlspecialchars($o['thumbnail']); ?>" alt="<?php echo htmlspecialchars($o['city']); ?>">
                                                </div>
                                                <div class="tile-city"><?php echo htmlspecialchars($o['city']); ?></div>
                                                <div class="tile-routes"><?php echo htmlspecialchars($o['destinations']); ?></div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>



        <!--====== Travel Insight Section (SEO/FAQ) ======-->
        <section class="info-insight-section">
            <div class="container">
                <div class="row mb-60 justify-content-center text-center">
                    <div class="col-lg-8">
                        <span class="sub-title" style="color: #00a79d; display: block; margin-bottom: 20px; font-weight: 800;">TRAVEL INSIGHTS</span>
                        <h2 style="color: #fff; font-size: 42px; font-weight: 800;">Everything You Need for a Seamless Journey</h2>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="insight-card">
                            <div class="icon-box"><i class="fas fa-check-circle"></i></div>
                            <h3>Premium Cab Booking</h3>
                            <p>Travolo simplifies your travel with premium airport transfers and outstation journeys. Whether you're heading for a business meeting or a weekend getaway, our user-friendly platform ensures a hassle-free booking experience with guaranteed punctuality.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="insight-card">
                            <div class="icon-box"><i class="fas fa-shield-alt"></i></div>
                            <h3>Safe & Secure Travel</h3>
                            <p>Your safety is our top priority. We partner only with verified chauffeurs and offer SOS features within our booking flow. Our rigorous vehicle inspection and driver-health checks ensure you enjoy peace of mind on every single ride.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="insight-card">
                            <div class="icon-box"><i class="fas fa-route"></i></div>
                            <h3>Elite Outstation Rides</h3>
                            <p>Explore hidden gems and popular destinations with our outstation services. We provide both one-way and round-trip options with top-rated drivers who are experts in highway navigation, ensuring you reach your destination refreshed.</p>
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div class="col-lg-4 col-md-6">
                        <div class="insight-card">
                            <div class="icon-box"><i class="fas fa-tags"></i></div>
                            <h3>Exclusive Deals & Offers</h3>
                            <p>Get the best value for your money with Travolo's transparent pricing. We offer seasonal discounts, loyalty rewards, and early bird specials on airport rentals. No hidden surcharges, no surprise fees—just premium service at honest prices.</p>
                        </div>
                    </div>

                    <!-- Card 5 -->
                    <div class="col-lg-4 col-md-6">
                        <div class="insight-card">
                            <div class="icon-box"><i class="fas fa-car"></i></div>
                            <h3>Versatile Fleet Options</h3>
                            <p>From sleek sedans for business to spacious SUVs for family trips, our fleet is diverse and well-maintained. All our vehicles are equipped with GPS tracking, air conditioning, and premium interior to match your style and needs.</p>
                        </div>
                    </div>

                    <!-- Card 6 -->
                    <div class="col-lg-4 col-md-6">
                        <div class="insight-card">
                            <div class="icon-box"><i class="fas fa-question-circle"></i></div>
                            <h3>Why Choose Travolo?</h3>
                            <p>With 24/7 customer support, a wide network of local partners, and a focus on premium traveler comfort, Travolo stands as the top choice for smart travelers. We don't just provide a ride; we provide an elite travel experience.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!--====== Start Gallery Section ======-->
        <section class="gallery-section mbm-150">
            <div class="container-fluid">
                <div class="slider-active-5-item wow fadeInUp">
                    <div class="single-gallery-item">
                        <div class="gallery-img">
                            <img src="assets/images/tour-3-550x590.jpg" alt="Gallery Image">
                            <div class="hover-overlay">
                                <a href="assets/images/tour-3-550x590.jpg" class="icon-btn img-popup"><i class="far fa-plus"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="single-gallery-item">
                        <div class="gallery-img">
                            <img src="assets/images/tour-4-550x590.jpg" alt="Gallery Image">
                            <div class="hover-overlay">
                                <a href="assets/images/tour-4-550x590.jpg" class="icon-btn img-popup"><i class="far fa-plus"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="single-gallery-item">
                        <div class="gallery-img">
                            <img src="assets/images/tour-12-550x590.jpg" alt="Gallery Image">
                            <div class="hover-overlay">
                                <a href="assets/images/tour-12-550x590.jpg" class="icon-btn img-popup"><i class="far fa-plus"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="single-gallery-item">
                        <div class="gallery-img">
                            <img src="assets/images/tour-8-550x590.jpg" alt="Gallery Image">
                            <div class="hover-overlay">
                                <a href="assets/images/tour-8-550x590.jpg" class="icon-btn img-popup"><i class="far fa-plus"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="single-gallery-item">
                        <div class="gallery-img">
                            <img src="assets/images/tour-3-550x590.jpg" alt="Gallery Image">
                            <div class="hover-overlay">
                                <a href="assets/images/gallery/gl-5.jpg" class="icon-btn img-popup"><i class="far fa-plus"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="single-gallery-item">
                        <div class="gallery-img">
                            <img src="assets/images/tour-4-550x590.jpg" alt="Gallery Image">
                            <div class="hover-overlay">
                                <a href="assets/images/tour-4-550x590.jpg" class="icon-btn img-popup"><i class="far fa-plus"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php include 'footer.php'; ?>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Destroy niceSelect for time selectors to avoid conflicts with dropdowns
                if (typeof $ !== 'undefined' && $.fn.niceSelect) {
                    $('.ignore-nice-select').niceSelect('destroy');
                }

                // ===== CAB CITY DROPDOWN =====
                const cabCities = <?php 
                    $s_res = $conn->query("SELECT city_name as city, city_code as code, airport_name as airport FROM cab_cities_suggestions WHERE status=1");
                    $s_data = [];
                    if ($s_res) {
                        while($s = $s_res->fetch_assoc()) $s_data[] = $s;
                    }
                    echo json_encode($s_data);
                ?>;

                function closeAllCabDropdowns() {
                    document.querySelectorAll(".cab-dropdown").forEach(d => d.classList.remove("show"));
                    document.querySelectorAll(".time-dropdown-menu").forEach(d => d.classList.remove("show"));
                }

                document.querySelectorAll(".cab-wrapper").forEach(wrapper => {
                    const input = wrapper.querySelector(".cab-input");
                    const display = wrapper.querySelector(".cab-display");
                    const dropdown = wrapper.querySelector(".cab-dropdown");

                    function showCabDropdown(filteredCities) {
                        dropdown.innerHTML = "";
                        filteredCities.forEach(c => {
                            const btn = document.createElement("button");
                            btn.type = "button";
                            btn.className = "dropdown-item p-3 border-bottom";
                            btn.style.textAlign = "left";
                            btn.style.width = "100%";
                            btn.style.background = "none";
                            btn.style.border = "none";
                            btn.innerHTML = `<strong>${c.city} (${c.code})</strong><div class="small text-muted">${c.airport}</div>`;
                            btn.onclick = () => {
                                input.value = `${c.city} (${c.code})`;
                                display.innerHTML = `<div class="cab-name">${c.city}</div><div class="cab-airport">[${c.code}] ${c.airport}</div>`;
                                display.style.display = "flex";
                                input.style.color = "transparent";
                                closeAllCabDropdowns();
                            };
                            dropdown.appendChild(btn);
                        });
                        dropdown.classList.toggle("show", filteredCities.length > 0);
                    }

                    input.addEventListener("focus", () => showCabDropdown(cabCities));
                    input.addEventListener("input", () => {
                        input.style.color = "#212529";
                        const query = input.value.toLowerCase();
                        const filtered = cabCities.filter(c =>
                            c.city.toLowerCase().includes(query) ||
                            c.code.toLowerCase().includes(query) ||
                            c.airport.toLowerCase().includes(query)
                        );
                        showCabDropdown(filtered);
                    });
                    display.addEventListener("click", () => {
                        display.style.display = "none";
                        input.style.color = "#212529";
                        input.focus();
                    });
                });

                // ===== DATE/TIME PICKER SETUP =====
                function setupDropdown(triggerId, dropdownId, dateInputId, hourId, minuteId, ampmId, dateDisplayId, timeDisplayId) {
                    const trigger = document.getElementById(triggerId);
                    const dropdown = document.getElementById(dropdownId);
                    const dateInput = document.getElementById(dateInputId);
                    const hourInput = document.getElementById(hourId);
                    const minuteInput = document.getElementById(minuteId);
                    const ampmSelect = document.getElementById(ampmId);
                    const dateDisplay = document.getElementById(dateDisplayId);
                    const timeDisplay = document.getElementById(timeDisplayId);
                    const setBtn = dropdown.querySelector('button');

                    if (!trigger || !dropdown) return;

                    trigger.addEventListener('click', e => {
                        e.stopPropagation();
                        const isOpen = dropdown.classList.contains('show');
                        closeAllCabDropdowns();
                        if (!isOpen) dropdown.classList.add('show');
                    });

                    dropdown.addEventListener('click', e => e.stopPropagation());

                    setBtn.addEventListener('click', e => {
                        e.stopPropagation();
                        if (!dateInput.value) {
                             Swal.fire({ icon: 'warning', title: 'Missing Date', text: 'Please select a date first.' });
                             return;
                        }
                        const pad = (n) => (n + '').length < 2 ? '0' + n : n;
                        const hhStr = hourInput.value ? pad(hourInput.value) : '12';
                        const mmStr = minuteInput.value ? pad(minuteInput.value) : '00';
                        const ampmStr = ampmSelect.value || 'AM';
                        const finalTime = `${hhStr}:${mmStr} ${ampmStr}`;
                        
                        if (dateDisplay) dateDisplay.textContent = dateInput.value;
                        if (timeDisplay) timeDisplay.textContent = finalTime;
                        dropdown.classList.remove('show');

                        if (dateInputId === 'pickupDateInput') {
                            document.getElementById('hiddenPickupDate').value = dateInput.value;
                            document.getElementById('hiddenPickupTime').value = finalTime;
                        } else {
                            document.getElementById('hiddenReturnDate').value = dateInput.value;
                            document.getElementById('hiddenReturnTime').value = finalTime;
                        }
                    });
                }

                setupDropdown('pickupTrigger', 'pickupDropdown', 'pickupDateInput', 'hourInput', 'minuteInput', 'ampmSelect', 'selectedPickupDate', 'selectedPickupTime');
                setupDropdown('returnTrigger', 'returnDropdown', 'returnDateInput', 'returnHourInput', 'returnMinuteInput', 'returnAmpmSelect', 'selectedReturnDate', 'selectedReturnTime');

                // ===== CLICK OUTSIDE TO CLOSE =====
                document.addEventListener('click', e => {
                    if (!e.target.closest(".cab-wrapper") && !e.target.closest(".input-with-icon")) closeAllCabDropdowns();
                });

                // ===== TRIP TYPE TOGGLE =====
                const tripRadios = document.querySelectorAll('input[name="tripType"]');
                function updateCabForm() {
                    if (!document.querySelector('input[name="tripType"]:checked')) return;
                    const tripType = document.querySelector('input[name="tripType"]:checked').value;
                    const ids = ['pickupTypeWrapper', 'fromWrapper', 'toWrapper', 'pickupWrapper', 'returnWrapper', 'hourlyWrapper'];
                    ids.forEach(id => {
                        const el = document.getElementById(id);
                        if (el) el.classList.add('d-none');
                    });

                    if (tripType === 'Transfer') {
                        ['pickupTypeWrapper', 'fromWrapper', 'toWrapper', 'pickupWrapper'].forEach(id => {
                            const el = document.getElementById(id); if (el) el.classList.remove('d-none');
                        });
                    } else if (tripType === 'Outstation') {
                        ['fromWrapper', 'toWrapper', 'pickupWrapper', 'returnWrapper'].forEach(id => {
                            const el = document.getElementById(id); if (el) el.classList.remove('d-none');
                        });
                    } else if (tripType === 'Hourly') {
                        ['pickupTypeWrapper', 'fromWrapper', 'pickupWrapper', 'hourlyWrapper'].forEach(id => {
                            const el = document.getElementById(id); if (el) el.classList.remove('d-none');
                        });
                    }
                }
                tripRadios.forEach(r => r.addEventListener('change', updateCabForm));
                updateCabForm();

                // ===== OFFERS SLIDER =====
                if (typeof $ !== 'undefined' && $.fn.slick) {
                    $('.offer-slider').slick({
                        dots: false, 
                        infinite: true, 
                        speed: 500, 
                        slidesToShow: 4, 
                        slidesToScroll: 1,
                        prevArrow: $('.prev-offer'), 
                        nextArrow: $('.next-offer'),
                        responsive: [
                            { breakpoint: 1200, settings: { slidesToShow: 3 } },
                            { breakpoint: 992, settings: { slidesToShow: 2 } },
                            { breakpoint: 768, settings: { slidesToShow: 1 } }
                        ]
                    });

                    $('.hourly-slider').slick({
                        dots: false,
                        infinite: true,
                        speed: 500,
                        slidesToShow: 4,
                        slidesToScroll: 1,
                        prevArrow: '<button type="button" class="slick-prev-hourly"><i class="fas fa-chevron-left"></i></button>',
                        nextArrow: '<button type="button" class="slick-next-hourly"><i class="fas fa-chevron-right"></i></button>',
                        responsive: [
                            { breakpoint: 1200, settings: { slidesToShow: 3 }},
                            { breakpoint: 992, settings: { slidesToShow: 2 }},
                            { breakpoint: 768, settings: { slidesToShow: 1 }}
                        ]
                    });
                }

                // ===== FORM SUBMIT =====
                document.getElementById('cabSearchForm').addEventListener('submit', function (e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const from = formData.get('from');
                    const pDate = formData.get('pickup_date');
                    const pTime = formData.get('pickup_time');

                    if (!from || from.trim() === '') { Swal.fire({ icon: 'error', title: 'Missing Field', text: 'Please select a pickup location.' }); return; }
                    if (!pDate || !pTime) { Swal.fire({ icon: 'error', title: 'Missing Field', text: 'Please select a pickup date and time.' }); return; }

                    Swal.fire({ title: 'Processing...', text: 'Submitting your request.', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                    fetch('submit.php', { method: 'POST', body: formData })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire({ icon: 'success', title: 'Success!', text: data.message, confirmButtonColor: '#133a25' }).then(() => {
                                    this.reset();
                                    document.querySelectorAll('.cab-display').forEach(d => d.style.display = 'none');
                                    document.querySelectorAll('.cab-input').forEach(i => i.style.color = '#212529');
                                    ['selectedPickupDate', 'selectedPickupTime', 'selectedReturnDate', 'selectedReturnTime'].forEach(id => {
                                        const el = document.getElementById(id);
                                        if (el) el.innerText = id.includes('Date') ? 'Select Date' : 'Select Time';
                                    });
                                    updateCabForm();
                                });
                            } else {
                                if (data.redirect) window.location.href = data.redirect + (data.redirect.includes('?') ? '&' : '?') + "return_url=" + encodeURIComponent(window.location.href);
                                else Swal.fire({ icon: 'error', title: 'Oops...', text: data.message });
                            }
                        }).catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong.' }));
                });
            });
        </script>
        <style>
            .modal-all-offers .modal-content { border-radius: 30px; border: none; overflow: hidden; }
            .modal-all-offers .modal-header { background: #133a25; color: #fff; padding: 25px; border: none; }
            .modal-all-offers .btn-close { filter: invert(1); opacity: 1; }
            .modal-all-offers .offer-grid-item { margin-bottom: 30px; height: 100%; }
            .modal-all-offers .offer-card { transform: scale(1) !important; box-shadow: 0 10px 20px rgba(0,0,0,0.05); height: 100%; }
            .modal-all-offers .offer-card:hover { transform: translateY(-5px) !important; box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
        </style>

        <!-- All Offers Modal -->
        <div class="modal fade modal-all-offers" id="allOffersModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" style="font-size: 24px;">TravoLo Exclusive Offers - All Active Deals</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-5 bg-light">
                        <div class="row">
                            <?php
                            $all_offers = $conn->query("SELECT * FROM cab_offers WHERE status=1 ORDER BY id DESC");
                            if ($all_offers && $all_offers->num_rows > 0) {
                                while ($offer = $all_offers->fetch_assoc()) {
                                    ?>
                                    <div class="col-xl-4 col-lg-6 col-md-6 offer-grid-item">
                                        <a href="#" class="offer-card d-block text-decoration-none">
                                            <div class="travolo-badge"><?php echo htmlspecialchars($offer['badge']); ?></div>
                                            <div class="offer-split-banner">
                                                <div class="banner-info" style="background: <?php echo htmlspecialchars($offer['theme_color']); ?>;">
                                                    <div class="discount-text"><?php echo htmlspecialchars($offer['header_small']); ?></div>
                                                    <div class="service-name"><?php echo htmlspecialchars($offer['header_main']); ?></div>
                                                    <div class="banner-use-code">CODE: <b><?php echo htmlspecialchars($offer['promo_code']); ?></b></div>
                                                </div>
                                                <div class="banner-img"><img src="<?php echo htmlspecialchars($offer['image_path']); ?>" alt="Offer"></div>
                                            </div>
                                            <div class="offer-body">
                                                <h4 class="text-dark"><?php echo htmlspecialchars($offer['main_title']); ?></h4>
                                                <div class="validity-text"><?php echo htmlspecialchars($offer['validity_text']); ?></div>
                                            </div>
                                        </a>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function toggleTransfers(btn) {
                const extras = document.querySelectorAll('.extra-transfer');
                const btnLabel = btn.querySelector('span');
                
                if (btn.classList.contains('active')) {
                    // Hide
                    extras.forEach(item => {
                        if (parseInt(item.getAttribute('data-id')) > 6) {
                            item.classList.add('d-none');
                        }
                    });
                    btnLabel.innerText = "View All Transfers";
                    btn.classList.remove('active');
                } else {
                    // Show
                    extras.forEach(item => {
                        item.classList.remove('d-none');
                    });
                    btnLabel.innerText = "Show Less";
                    btn.classList.add('active');
                }
            }
        </script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>
</html>
