<?php include_once 'includes/auth.php'; ?>
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
        .emt-search-container {
            background: #ffffff !important;
            border-radius: 10px !important;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.15) !important;
            display: flex !important;
            align-items: stretch !important;
            width: 100% !important;
            margin-top: -60px !important;
            position: relative !important;
            z-index: 999 !important;
            overflow: visible !important;
            border: 1px solid #efefef !important;
        }

        .emt-search-item {
            padding: 15px 25px !important;
            border-right: 1px solid #eee !important;
            flex: 1 !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: center !important;
            position: relative !important;
            text-align: left !important;
        }

        .emt-search-item:nth-last-child(2) {
            border-right: none !important;
        }

        .emt-search-item-large {
            flex: 2 !important;
        }

        .emt-search-item-medium {
            flex: 1.5 !important;
        }

        #hotelResultsContainer {
            display: flex !important;
            flex-wrap: wrap !important;
            margin-right: -15px !important;
            margin-left: -15px !important;
        }

        .places-column {
            display: flex !important;
            flex-direction: column !important;
            padding: 15px !important;
        }

        .single-place-item {
            display: flex !important;
            flex-direction: column !important;
            height: 100% !important;
            margin-bottom: 0 !important;
            border: 1.5px solid #eee !important;
            border-radius: 18px !important;
            background: #fff !important;
            overflow: hidden !important;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03) !important;
            /* Very Light Shadow */
        }

        .single-place-item:hover {
            transform: translateY(-8px) !important;
            border-color: #00a79d !important;
            /* Teal Hover */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important;
            /* Slightly more on hover */
        }

        .place-content {
            flex: 1 !important;
            display: flex !important;
            flex-direction: column !important;
            padding: 25px !important;
        }

        .place-content .info {
            flex: 1 !important;
            display: flex !important;
            flex-direction: column !important;
        }

        /* Aggressive Truncation with Ellipsis (...) */
        .place-content h4.title a {
            display: -webkit-box !important;
            -webkit-line-clamp: 1 !important;
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
            height: 1.4em !important;
            font-size: 20px !important;
            font-weight: 800 !important;
            margin-bottom: 0 !important;
            color: #1a1a1a !important;
        }

        .place-content p.location {
            display: -webkit-box !important;
            -webkit-line-clamp: 2 !important;
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
            min-height: 2.8em !important;
            height: 2.8em !important;
            margin-bottom: 15px !important;
            font-size: 14px !important;
            line-height: 1.4 !important;
            color: #777 !important;
        }

        .place-content p.description {
            display: -webkit-box !important;
            -webkit-line-clamp: 2 !important;
            /* Truncate to 2 Rows */
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
            min-height: 3.2em !important;
            height: 3.2em !important;
            margin-bottom: 20px !important;
            font-size: 14px !important;
            line-height: 1.6 !important;
            color: #555 !important;
            flex: none !important;
        }

        .place-content .meta {
            margin-top: auto !important;
            padding-top: 15px !important;
            border-top: 1px solid #f0f0f0 !important;
        }

        .single-place-item .place-img img {
            height: 220px !important;
            object-fit: cover !important;
        }

        .emt-label {
            font-size: 11px !important;
            color: #888 !important;
            margin-bottom: 2px !important;
            display: block !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            font-family: 'Prompt', sans-serif !important;
        }

        .emt-input {
            font-size: 14px !important;
            font-weight: 300 !important;
            color: #333 !important;
            border: none !important;
            padding: 0 !important;
            background: transparent !important;
            width: 100% !important;
            outline: none !important;
            box-shadow: none !important;
            height: auto !important;
            font-family: 'Prompt', sans-serif !important;
        }

        .emt-display-value {
            font-size: 17px !important;
            font-weight: 700 !important;
            color: #000 !important;
            cursor: pointer !important;
            line-height: normal !important;
            font-family: 'Prompt', sans-serif !important;
        }

        .emt-display-sub {
            font-size: 12px !important;
            color: #666 !important;
            font-weight: 400 !important;
            display: block !important;
        }

        .emt-search-btn-wrapper {
            background: #F7921E !important;
            border-top-right-radius: 10px !important;
            border-bottom-right-radius: 10px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 45px !important;
            cursor: pointer !important;
        }

        .emt-search-btn-wrapper button {
            background: transparent !important;
            color: #fff !important;
            font-size: 20px !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            border: none !important;
            padding: 0 !important;
            cursor: pointer !important;
        }

        .emt-rooms-guests-popover {
            position: absolute !important;
            top: calc(100% + 15px) !important;
            right: 0 !important;
            width: 320px !important;
            background: #fff !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.25) !important;
            border-radius: 12px !important;
            padding: 25px !important;
            z-index: 10000 !important;
            display: none !important;
            border: 1px solid #ddd !important;
        }

        .emt-rooms-guests-popover.active {
            display: block !important;
        }

        .emt-room-item {
            border-bottom: 1px solid #f0f0f0 !important;
            padding-bottom: 15px !important;
            margin-bottom: 15px !important;
        }

        .emt-room-title {
            font-size: 15px !important;
            font-weight: 800 !important;
            margin-bottom: 20px !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            color: #1a1a1a !important;
        }

        .emt-remove-room {
            color: #d9534f !important;
            cursor: pointer !important;
            font-size: 12px !important;
            font-weight: 600 !important;
        }

        .emt-guest-row {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            margin-bottom: 15px !important;
        }

        .emt-guest-info {
            font-size: 15px !important;
            color: #333 !important;
            font-weight: 600 !important;
        }

        .emt-guest-info span {
            display: block !important;
            font-size: 11px !important;
            color: #999 !important;
            font-weight: 400 !important;
        }

        .emt-counter-group {
            display: flex !important;
            align-items: center !important;
            border: 1px solid #ccc !important;
            border-radius: 6px !important;
            overflow: hidden !important;
            height: 34px !important;
        }

        .emt-counter-btn {
            width: 34px !important;
            height: 100% !important;
            background: #fdfdfd !important;
            border: none !important;
            font-size: 20px !important;
            font-weight: bold !important;
            cursor: pointer !important;
            color: #F7921E !important;
            line-height: 1 !important;
            padding: 0 !important;
        }

        .emt-count-display {
            width: 34px !important;
            text-align: center !important;
            font-size: 15px !important;
            font-weight: 800 !important;
            color: #000 !important;
        }

        .emt-popover-footer {
            display: flex !important;
            justify-content: space-between !important;
            margin-top: 5px !important;
        }

        .emt-btn-add {
            background: transparent !important;
            border: 1.5px solid #00a79d !important;
            color: #00a79d !important;
            padding: 8px 15px !important;
            border-radius: 6px !important;
            font-size: 14px !important;
            font-weight: 800 !important;
            cursor: pointer !important;
        }

        .emt-btn-done {
            background: #F7921E !important;
            color: #fff !important;
            padding: 8px 30px !important;
            border-radius: 6px !important;
            font-size: 14px !important;
            font-weight: 800 !important;
            cursor: pointer !important;
            border: none !important;
        }

        @media (max-width: 991px) {
            .emt-search-container {
                flex-direction: column !important;
                margin-top: 0 !important;
            }

            .emt-search-item {
                border-right: none !important;
                border-bottom: 1px solid #efefef !important;
            }

            .emt-search-btn-wrapper {
                border-radius: 0 0 10px 10px !important;
                padding: 15px !important;
            }

            .emt-rooms-guests-popover {
                width: 100% !important;
                right: 0 !important;
            }
        }
    </style>
</head>

<body>
    <?php include 'includes/navbar.php'; ?>



    <!--====== Start Breadcrumb Section ======-->
    <section class="page-banner overlay pt-170 pb-170 bg_cover"
        style="background-image: url(assets/images/abt-bg.jpg);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="page-banner-content text-center text-white">
                        <h1 class="page-title text-white">Hotels</h1>
                        <ul class="breadcrumb-link text-white">
                            <li><a href="index.php">Home</a></li>
                            <li class="active">Hotels</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section><!--====== End Breadcrumb Section ======-->
    <!--====== Start Booking Section ======-->
    <section class="booking-form-section pb-100" style="padding-top: 0;">
        <div class="container">
            <div class="emt-search-container">
                <form id="hotelSearchForm" action="submit.php" method="POST"
                    class="d-flex w-100 flex-wrap flex-lg-nowrap" style="margin:0; padding:0;">
                    <input type="hidden" name="form_type" value="hotel">
                    <input type="hidden" name="hotel_id" id="selectedHotelId" value="0">
                    <input type="hidden" name="status" id="checkStatus" value="Checked">
                    <input type="hidden" name="accommodations" id="accommodationsInput" value="1 Room, 2 Guests">

                    <div class="emt-search-item emt-search-item-large">
                        <span class="emt-label">City / Hotel</span>
                        <input type="text" class="emt-input" name="search" placeholder="Where are you going?"
                            list="hotelList" required autocomplete="off">
                        <datalist id="hotelList"></datalist>
                    </div>

                    <div class="emt-search-item">
                        <span class="emt-label">Check-In</span>
                        <input type="text" class="emt-input datepicker" name="check_in" id="check_in"
                            placeholder="Select Date" required readonly style="background:transparent;">
                    </div>

                    <div class="emt-search-item">
                        <span class="emt-label">Check-Out</span>
                        <input type="text" class="emt-input datepicker" name="check_out" id="check_out"
                            placeholder="Select Date" required readonly style="background:transparent;">
                    </div>

                    <div class="emt-search-item emt-search-item-medium">
                        <span class="emt-label">Email Address</span>
                        <input type="email" class="emt-input" name="email" placeholder="you@example.com"
                            value="<?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?>" required>
                    </div>

                    <div class="emt-search-item emt-search-item-medium">
                        <span class="emt-label">Mobile Number</span>
                        <input type="tel" class="emt-input" name="phone" placeholder="+91 1234567890"
                            value="<?php echo htmlspecialchars($_SESSION['user_phone'] ?? ''); ?>" required>
                    </div>

                    <div class="emt-search-item emt-search-item-large" id="roomsGuestsTrigger" style="cursor:pointer;">
                        <span class="emt-label">Rooms & Guests</span>
                        <div class="emt-display-value" id="roomsGuestsDisplay">1 Room, 2 Guests</div>
                        <div class="emt-display-sub">Adults, Children</div>

                        <div class="emt-rooms-guests-popover" id="roomsGuestsPopover">
                            <div id="roomContainer">
                                <!-- Dynamic Content -->
                            </div>
                            <div class="emt-popover-footer">
                                <button type="button" class="emt-btn-add" id="addRoomBtn">Add Room</button>
                                <button type="button" class="emt-btn-done" id="doneBtn">Done</button>
                            </div>
                        </div>
                    </div>

                    <div class="emt-search-btn-wrapper">
                        <button type="submit">SEARCH</button>
                    </div>
                </form>
            </div>
        </div>
    </section><!--====== End Booking Section ======-->
    <!--====== Start Places Section ======-->
    <section class="places-section pb-100">
        <div class="container">
            <div class="row justify-content-center" id="hotelResultsContainer">
                <!-- Dynamic Hotel Items will be injected here -->
                <div class="col-12 text-center my-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="row pt-4">
                <div class="col-lg-12">
                    <!--=== Gowilds Pagination ===-->
                    <ul class="gowilds-pagination wow fadeInUp text-center" id="paginationContainer">
                    </ul>
                </div>
            </div>
        </div>
    </section><!--====== End Places Section ======-->



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
    </section>
    <!--====== End Gallery Section ======-->



    <?php include 'includes/footer.php'; ?>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Room and Guest Management
        let rooms = [
            { id: 1, adults: 2, children: 0 }
        ];

        const roomsGuestsTrigger = document.getElementById('roomsGuestsTrigger');
        const roomsGuestsPopover = document.getElementById('roomsGuestsPopover');
        const roomContainer = document.getElementById('roomContainer');
        const addRoomBtn = document.getElementById('addRoomBtn');
        const doneBtn = document.getElementById('doneBtn');
        const roomsGuestsDisplay = document.getElementById('roomsGuestsDisplay');
        const accommodationsInput = document.getElementById('accommodationsInput');

        // Toggle Popover
        roomsGuestsTrigger.addEventListener('click', function (e) {
            if (e.target.closest('.emt-rooms-guests-popover')) return;
            roomsGuestsPopover.classList.toggle('active');
        });

        // Close on click outside
        document.addEventListener('click', function (e) {
            if (!roomsGuestsTrigger.contains(e.target)) {
                roomsGuestsPopover.classList.remove('active');
            }
        });

        function updateRoomsUI() {
            roomContainer.innerHTML = '';
            rooms.forEach((room, index) => {
                const roomDiv = document.createElement('div');
                roomDiv.className = 'emt-room-item';
                roomDiv.innerHTML = `
                    <div class="emt-room-title">
                        Room ${index + 1}:
                        ${index > 0 ? `<span class="emt-remove-room" onclick="removeRoom(${index})">Remove</span>` : ''}
                    </div>
                    <div class="emt-guest-row">
                        <div class="emt-guest-info">
                            Adult
                            <span>(Above 12 years)</span>
                        </div>
                        <div class="emt-counter-group">
                            <button type="button" class="emt-counter-btn" onclick="updateGuest(${index}, 'adults', -1)" ${room.adults <= 1 ? 'disabled' : ''}>-</button>
                            <div class="emt-count-display">${room.adults}</div>
                            <button type="button" class="emt-counter-btn" onclick="updateGuest(${index}, 'adults', 1)">+</button>
                        </div>
                    </div>
                    <div class="emt-guest-row">
                        <div class="emt-guest-info">
                            Child
                            <span>(Below 12 years)</span>
                        </div>
                        <div class="emt-counter-group">
                            <button type="button" class="emt-counter-btn" onclick="updateGuest(${index}, 'children', -1)" ${room.children <= 0 ? 'disabled' : ''}>-</button>
                            <div class="emt-count-display">${room.children}</div>
                            <button type="button" class="emt-counter-btn" onclick="updateGuest(${index}, 'children', 1)">+</button>
                        </div>
                    </div>
                `;
                roomContainer.appendChild(roomDiv);
            });
            updateSummary();
        }

        window.updateGuest = function (roomIndex, type, delta) {
            rooms[roomIndex][type] += delta;
            updateRoomsUI();
        };

        window.removeRoom = function (roomIndex) {
            rooms.splice(roomIndex, 1);
            updateRoomsUI();
        };

        addRoomBtn.addEventListener('click', function () {
            if (rooms.length < 5) {
                rooms.push({ id: Date.now(), adults: 2, children: 0 });
                updateRoomsUI();
            } else {
                alert('Maximum 5 rooms allowed');
            }
        });

        doneBtn.addEventListener('click', function () {
            roomsGuestsPopover.classList.remove('active');
        });

        function updateSummary() {
            const totalRooms = rooms.length;
            const totalGuests = rooms.reduce((acc, room) => acc + room.adults + room.children, 0);
            const summaryStr = `${totalRooms} Room${totalRooms > 1 ? 's' : ''}, ${totalGuests} Guest${totalGuests > 1 ? 's' : ''}`;
            roomsGuestsDisplay.innerText = summaryStr;
            accommodationsInput.value = summaryStr; // For form submission
        }

        // Initialize Rooms UI
        updateRoomsUI();

        // Search Form Logic
        document.getElementById('hotelSearchForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const checkIn = formData.get('check_in');
            const checkOut = formData.get('check_out');
            const hotelName = formData.get('search');
            const accommodations = formData.get('accommodations');

            if (!checkIn || !hotelName || !accommodations) {
                Swal.fire({ icon: 'error', title: 'Missing Fields', text: 'Please fill in all the required fields.' });
                return;
            }

            // Find selected hotel details
            const hotel = allHotels.find(h => h.name.toLowerCase() === hotelName.toLowerCase());
            let isAvailable = false;
            let hId = 0;

            if (hotel) {
                hId = hotel.id;
                document.getElementById('selectedHotelId').value = hId;
                if (hotel.available_dates) {
                    const availableDates = hotel.available_dates.split(', ');
                    if (availableDates.includes(checkIn)) {
                        isAvailable = true;
                    }
                }
            }

            const statusText = isAvailable ? 'Available' : 'Not Available';
            document.getElementById('checkStatus').value = statusText;

            const swalConfig = {
                title: isAvailable ? 'Good news!' : 'Sorry!',
                text: isAvailable ? `Yes, ${hotelName} is available on ${checkIn}. Save this check?` : `${hotelName} is not available on ${checkIn}. Save this request anyway?`,
                icon: isAvailable ? 'success' : 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Save it!',
                confirmButtonColor: '#F7921E'
            };

            Swal.fire(swalConfig).then((result) => {
                if (result.isConfirmed) {
                    submitBooking(this);
                }
            });
        });

        function submitBooking(form) {
            Swal.fire({
                title: 'Processing...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            fetch('submit.php', {
                method: 'POST',
                body: new FormData(form)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({ icon: 'success', title: 'Saved!', text: data.message, confirmButtonColor: '#F7921E' })
                            .then(() => {
                                // Reset form and summary
                                form.reset();
                                rooms = [{ id: 1, adults: 2, children: 0 }];
                                updateRoomsUI();
                            });
                    } else {
                        if (data.redirect) {
                            Swal.fire({ icon: 'warning', title: 'Login Required', text: data.message, confirmButtonColor: '#F7921E' })
                                .then(() => {
                                    const sep = data.redirect.includes('?') ? '&' : '?';
                                    window.location.href = data.redirect + sep + "return_url=" + encodeURIComponent(window.location.href);
                                });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Oops...', text: data.message });
                        }
                    }
                })
                .catch(error => {
                    Swal.fire({ icon: 'error', title: 'System Error', text: 'Something went wrong.' });
                });
        }

        // Fetch and setup dynamic hotels
        let allHotels = [];
        let filteredHotels = [];
        let currentPage = 1;
        const itemsPerPage = 6;

        document.addEventListener('DOMContentLoaded', () => {
            // Robust Datepicker Setup
            if ($.fn.datepicker) {
                $(".datepicker").datepicker({
                    dateFormat: "yy-mm-dd",
                    minDate: 0,
                    changeMonth: true,
                    changeYear: true,
                    onSelect: function (selectedDate) {
                        if (this.id === 'check_in') {
                            var date = $(this).datepicker('getDate');
                            date.setDate(date.getDate() + 1);
                            $('#check_out').datepicker('option', 'minDate', date);
                        }
                    }
                });
                
                // Initialize Today & Tomorrow
                let today = new Date();
                let tomorrow = new Date();
                tomorrow.setDate(today.getDate() + 1);
                
                $('#check_in').datepicker('setDate', today);
                $('#check_out').datepicker('setDate', tomorrow);
            }

            fetch('get_hotels.php')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        allHotels = data.data;
                        filteredHotels = allHotels;
                        renderHotels();
                        updateDatalist();
                    } else {
                        document.getElementById('hotelResultsContainer').innerHTML = "<p class='text-center text-danger'>Failed to load hotels. Please try again.</p>";
                    }
                })
                .catch(error => {
                    document.getElementById('hotelResultsContainer').innerHTML = "<p class='text-center text-muted'>Error pulling hotel data.</p>";
                });
        });

        function updateDatalist() {
            const list = document.getElementById('hotelList');
            list.innerHTML = allHotels.map(h => `<option value="${h.name}">`).join('');
        }

        const searchInput = document.querySelector('input[name="search"]');
        searchInput.addEventListener('input', function (e) {
            const val = e.target.value.toLowerCase();
            filteredHotels = allHotels.filter(hotel =>
                hotel.name.toLowerCase().includes(val) ||
                hotel.location.toLowerCase().includes(val) ||
                hotel.accommodations.toLowerCase().includes(val)
            );
            currentPage = 1;
            renderHotels();
        });

        function goToPage(page) {
            currentPage = page;
            renderHotels();
            document.querySelector('.places-section').scrollIntoView({ behavior: 'smooth' });
        }

        function renderHotels() {
            const container = document.getElementById('hotelResultsContainer');
            const pagination = document.getElementById('paginationContainer');

            if (filteredHotels.length === 0) {
                container.innerHTML = `<div class="col-12 text-center py-5"><h5>No hotels found matching your search.</h5></div>`;
                pagination.innerHTML = '';
                return;
            }

            const totalPages = Math.ceil(filteredHotels.length / itemsPerPage);
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const currentHotels = filteredHotels.slice(startIndex, endIndex);

            let htmlString = '';
            currentHotels.forEach(hotel => {
                const checkIn = document.getElementById('check_in').value;
                const checkOut = document.getElementById('check_out').value;
                const roomsVal = document.getElementById('roomsGuestsDisplay').innerText;
                const mobile = document.querySelector('input[name="phone"]').value;
                const email = document.querySelector('input[name="email"]').value;

                const searchParams = `&checkin=${checkIn}&checkout=${checkOut}&rooms=${encodeURIComponent(roomsVal)}&mobile=${mobile}&email=${email}`;
                const detailUrl = `hotel-details.php?id=${hotel.id}${searchParams}`;

                htmlString += `
                <div class="col-xl-4 col-md-6 col-sm-12 places-column">
                    <div class="single-place-item mb-60 wow fadeInUp">
                        <div class="place-img text-center" style="background:#eee; border-radius: 15px; overflow: hidden;">
                            <a href="${detailUrl}" class="d-block w-100">
                                <img src="${hotel.image}" alt="Place Image" style="object-fit:cover; height:240px; width:100%; display: block;">
                            </a>
                        </div>
                        <div class="place-content">
                            <div class="info">
                                <ul class="ratings">
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                    <li><a href="#">(5.0)</a></li>
                                </ul>
                                <h4 class="title"><a href="${detailUrl}">${hotel.name}</a></h4>
                                <p class="location"><i class="far fa-map-marker-alt"></i> ${hotel.location}</p>
                                <p class="price"><i class="fas fa-rupee-sign"></i> ₹${hotel.price} <span>/ Night</span></p>
                                <p class="description">${hotel.description}</p>
                                <div class="meta">
                                    <span><i class="far fa-bed"></i> ${hotel.accommodations}</span>
                                    <span><a href="${detailUrl}">Book Now <i class="far fa-long-arrow-right"></i></a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
            });
            container.innerHTML = htmlString;

            if (totalPages > 1) {
                let phtml = '';
                if (currentPage > 1) phtml += `<li><a href="javascript:void(0)" onclick="goToPage(${currentPage - 1})"><i class="far fa-arrow-left"></i></a></li>`;
                for (let i = 1; i <= totalPages; i++) {
                    const activeClass = i === currentPage ? 'class="active"' : '';
                    phtml += `<li><a href="javascript:void(0)" ${activeClass} onclick="goToPage(${i})">${i < 10 ? '0' + i : i}</a></li>`;
                }
                if (currentPage < totalPages) phtml += `<li><a href="javascript:void(0)" onclick="goToPage(${currentPage + 1})"><i class="far fa-arrow-right"></i></a></li>`;
                pagination.innerHTML = phtml;
            } else {
                pagination.innerHTML = '';
            }
        }
    </script>
</body>

</html>