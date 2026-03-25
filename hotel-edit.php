<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Helper function for File Uploads
function handleFileUpload($fileInputName, $targetDir = "assets/images/") {
    if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
        $name = basename($_FILES[$fileInputName]["name"]);
        $targetFile = $targetDir . time() . "_" . $name;
        if (move_uploaded_file($_FILES[$fileInputName]["tmp_name"], $targetFile)) {
            return $targetFile;
        }
    }
    return null;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$res = $conn->query("SELECT * FROM app_hotels WHERE id = $id");
$hotel = $res->fetch_assoc();

if (!$hotel) {
    die("Hotel not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $loc = $conn->real_escape_string($_POST['location']);
    $price = $conn->real_escape_string($_POST['price']);
    $accom = $conn->real_escape_string($_POST['accommodations']);
    $desc = $conn->real_escape_string($_POST['description']);
    $dates = $conn->real_escape_string($_POST['available_dates']);
    $image = $conn->real_escape_string($_POST['existing_image']); // Keep old image by default
    $avail = isset($_POST['availability']) ? 1 : 0;

    // Handle File Upload
    $new_image = handleFileUpload('hotel_image');
    if ($new_image) {
        $image = $new_image;
    }

    $sql = "UPDATE app_hotels SET 
            name='$name', 
            location='$loc', 
            price='$price', 
            accommodations='$accom', 
            description='$desc', 
            available_dates='$dates', 
            image='$image',
            availability=$avail 
            WHERE id=$id";

    if ($conn->query($sql)) {
        header("Location: admin.php?success=Hotel+Updated+Successfully");
        exit;
    } else {
        $error = "Update failed: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Hotel - Travelo Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/material_orange.css">
    <style>
        body { background: #f4f7f6; font-family: 'Inter', sans-serif; padding: 40px 0; }
        .edit-card { background: #fff; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); padding: 40px; }
        .back-btn { margin-bottom: 20px; display: inline-block; color: #7f8c8d; text-decoration: none; }
        .back-btn:hover { color: #F7921E; }
        .btn-save { background: #F7921E; border: none; padding: 12px 30px; font-weight: 600; color: white; border-radius: 8px; }
        .btn-save:hover { background: #e6851b; }
        label { font-weight: 500; color: #2c3e50; margin-bottom: 8px; }
        .form-control { border-radius: 8px; border: 1px solid #e1e9ec; padding: 12px; }
        .calendar-section { background: #fff9f2; border: 1px solid #ffe8cc; border-radius: 10px; padding: 20px; margin-top: 20px; }
        .date-badge { display: inline-block; background: #F7921E; color: white; padding: 4px 10px; border-radius: 20px; margin: 4px; font-size: 11px; }
    </style>
</head>
<body>
    <div class="container">
        <a href="admin.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        
        <div class="edit-card">
            <h2 class="mb-4">Edit Hotel Details</h2>
            <?php if(isset($error)): ?>
                <div class="alert alert-danger px-3"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($hotel['image']); ?>">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label>Hotel Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($hotel['name']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label>Location</label>
                        <input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($hotel['location']); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label>Price (INR)</label>
                        <input type="number" name="price" class="form-control" value="<?php echo $hotel['price']; ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label>Accommodation Type</label>
                        <select class="form-select form-control" name="accommodations" required>
                            <?php 
                            $types = ["Classic Tent", "Forest Camping", "Small Trailer", "Tree House Tent", "Tent Camping", "Couple Tent"];
                            foreach($types as $type) {
                                $selected = ($hotel['accommodations'] == $type) ? 'selected' : '';
                                echo "<option value='$type' $selected>$type</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Hotel Image (Upload new to change)</label>
                        <input type="file" name="hotel_image" class="form-control" accept="image/*">
                        <div class="mt-1 small text-muted">Current: <?php echo htmlspecialchars($hotel['image']); ?></div>
                    </div>
                    <div class="col-12">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($hotel['description']); ?></textarea>
                    </div>
                    
                    <div class="col-12">
                        <div class="form-check form-switch mt-3">
                            <input class="form_check_input" type="checkbox" name="availability" id="availSwitch" <?php echo $hotel['availability'] ? 'checked' : ''; ?>>
                            <label class="form-check-label ms-2" for="availSwitch">Show this hotel on website</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="calendar-section">
                            <label><i class="fas fa-calendar-alt text-warning"></i> Availability Calendar (Select Multiple Dates)</label>
                            <input type="text" name="available_dates" id="datesInput" class="form-control mb-3" placeholder="Pick Dates" value="<?php echo $hotel['available_dates']; ?>">
                            <div id="selectedDatesDisplay" class="border rounded p-3 bg-white" style="min-height: 50px;">
                                <?php 
                                if (!empty($hotel['available_dates'])) {
                                    $datesArr = explode(', ', $hotel['available_dates']);
                                    foreach($datesArr as $d) echo "<span class='date-badge'>$d</span>";
                                } else {
                                    echo "<span class='text-muted'>No dates selected</span>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-5 text-end">
                        <button type="submit" class="btn-save">Save All Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#datesInput", {
                mode: "multiple",
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr) {
                    const display = document.getElementById('selectedDatesDisplay');
                    if (dateStr) {
                        display.innerHTML = dateStr.split(', ').map(d => `<span class='date-badge'>${d}</span>`).join('');
                    } else {
                        display.innerHTML = '<span class="text-muted">No dates selected</span>';
                    }
                }
            });
        });
    </script>
</body>
</html>
