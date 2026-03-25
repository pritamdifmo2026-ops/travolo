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
</head>

<body>
<?php include 'navbar.php'; ?>
    


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
    <section class="booking-form-section pb-100">
        <div class="container-fluid ">
            <div class="booking-form-wrapper p-r z-2">
                <form id="hotelSearchForm" action="submit.php" method="POST"
                    class="booking-form-two justify-content-center">
                    <input type="hidden" name="form_type" value="hotel">
                    <div class="form_group">
                        <span>Tour Start Date</span>
                        <label><i class="far fa-calendar-alt"></i></label>
                        <input type="text" class="form_control datepicker" name="check_in" placeholder="Check In"
                            required>
                    </div>

                    <div class="form_group">
                        <span>Search</span>
                        <label></label>
                        <input type="text" class="form_control" name="search" placeholder="hotel" list="hotelList"
                            required>
                        <datalist id="hotelList"></datalist>
                    </div>

                    <div class="form_group">
                        <span>Accommodations</span>
                        <select class="wide" name="accommodations" required>
                            <option value="" data-display="Accommodations">Accommodations</option>
                            <option value="Classic Tent">Classic Tent</option>
                            <option value="Forest Camping">Forest Camping</option>
                            <option value="Small Trailer">Small Trailer</option>
                            <option value="Tree House Tent">Tree House Tent</option>
                            <option value="Tent Camping">Tent Camping</option>
                            <option value="Couple Tent">Couple Tent</option>
                        </select>
                    </div>
                    <div class="form_group">
                        <span>Phone Number</span>
                        <label><i class="far fa-phone"></i></label>
                        <input type="tel" class="form_control" name="phone" placeholder="Phone Number" required>
                    </div>
                    <div class="form_group">
                        <input type="hidden" name="hotel_id" id="selectedHotelId" value="0">
                        <input type="hidden" name="status" id="checkStatus" value="Checked">
                        <button type="submit" class="booking-btn">Check Availability <i
                                class="far fa-angle-double-right"></i></button>
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



<?php include 'footer.php'; ?>
    

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('hotelSearchForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const checkIn = formData.get('check_in');
            const hotelName = formData.get('search');
            const accommodations = formData.get('accommodations');
            const phone = formData.get('phone');

            if (!checkIn || !hotelName || !accommodations || !phone) {
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
                    // Format check-in to match flatpickr Y-m-d if needed. 
                    // Datepicker might be d/m/Y or similar, depends on init.
                    // Assuming current datepicker provides YYYY-MM-DD or we can parse it.
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
                            .then(() => { form.reset(); });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Oops...', text: data.message });
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
            // Force datepicker to use YYYY-MM-DD
            if ($.fn.datepicker) {
                $(".datepicker").datepicker("option", "dateFormat", "yy-mm-dd");
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
            // Scroll to top of list
            document.querySelector('.places-section').scrollIntoView({ behavior: 'smooth' });
        }

        function renderHotels() {
            const container = document.getElementById('hotelResultsContainer');
            const pagination = document.getElementById('paginationContainer');

            if (filteredHotels.length === 0) {
                container.innerHTML = `<div class="col-12 text-center py-5">
                    <h5>No hotels found matching your search.</h5>
                </div>`;
                pagination.innerHTML = '';
                return;
            }

            // Pagination calculations
            const totalPages = Math.ceil(filteredHotels.length / itemsPerPage);
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const currentHotels = filteredHotels.slice(startIndex, endIndex);

            // Render Items
            let htmlString = '';
            currentHotels.forEach(hotel => {
                htmlString += `
                <div class="col-xl-4 col-md-6 col-sm-12 places-column">
                    <div class="single-place-item mb-60 wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                        <div class="place-img text-center" style="background:#eee; border-radius: 15px; overflow: hidden;">
                            <a href="hotel-details.php?id=${hotel.id}" class="d-block w-100">
                                <img src="${hotel.image}" alt="Place Image" style="object-fit:cover; height:240px; width:100%; display: block;">
                            </a>
                        </div>
                        <div class="place-content">
                            <div class="info" style="word-wrap: break-word; overflow-wrap: break-word;">
                                <ul class="ratings">
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                    <li><a href="#">(5.0)</a></li>
                                </ul>
                                <h4 class="title"><a href="hotel-details.php?id=${hotel.id}">${hotel.name}</a></h4>
                                <p class="location" style="margin-bottom:8px; word-break: break-word;"><i class="far fa-map-marker-alt"></i> ${hotel.location}</p>
                                <p class="price" style="margin-bottom:8px; font-weight:600;"><i class="fas fa-rupee-sign"></i> ₹${hotel.price} <span style="font-size:12px; font-weight:normal; color:#888;">/ Night</span></p>
                                <p style="font-size:13px; color:#666; margin-bottom:15px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; word-break: break-word;">${hotel.description}</p>
                                <div class="meta" style="padding-top:12px; border-top:1px dashed #eee;">
                                    <span><i class="far fa-bed"></i> ${hotel.accommodations}</span>
                                    <span><a href="hotel-details.php?id=${hotel.id}">Book Now <i class="far fa-long-arrow-right"></i></a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
            });
            container.innerHTML = htmlString;

            // Render Pagination Logic
            if (totalPages > 1) {
                let phtml = '';
                if (currentPage > 1) {
                    phtml += `<li><a href="javascript:void(0)" onclick="goToPage(${currentPage - 1})"><i class="far fa-arrow-left"></i></a></li>`;
                }
                for (let i = 1; i <= totalPages; i++) {
                    const activeClass = i === currentPage ? 'class="active"' : '';
                    phtml += `<li><a href="javascript:void(0)" ${activeClass} onclick="goToPage(${i})">${i < 10 ? '0' + i : i}</a></li>`;
                }
                if (currentPage < totalPages) {
                    phtml += `<li><a href="javascript:void(0)" onclick="goToPage(${currentPage + 1})"><i class="far fa-arrow-right"></i></a></li>`;
                }
                pagination.innerHTML = phtml;
            } else {
                pagination.innerHTML = '';
            }
        }
    </script>
</body>

</html>
