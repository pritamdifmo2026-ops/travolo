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
$hotel = $res ? $res->fetch_assoc() : null;

if (!$hotel) {
    die("Hotel not found.");
}

// 1. Handle Room Addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['room_action']) && $_POST['room_action'] === 'add_room') {
    $roomName = $conn->real_escape_string($_POST['room_name']);
    $roomPrice = (int)$_POST['room_price'];
    $capacity = $conn->real_escape_string($_POST['capacity']);
    $bedType = $conn->real_escape_string($_POST['bed_type']);
    $features = isset($_POST['features']) ? json_encode($_POST['features']) : '[]';
    
    $roomImg = handleFileUpload('room_image') ?: $hotel['image'];

    $sql = "INSERT INTO hotel_rooms (hotel_id, room_name, room_price, capacity, bed_type, features, room_image) 
            VALUES ($id, '$roomName', $roomPrice, '$capacity', '$bedType', '$features', '$roomImg')";
    $conn->query($sql);
    header("Location: hotel-edit.php?id=$id&success=Room+Added");
    exit;
}

// 2. Handle Room Deletion
if (isset($_GET['delete_room'])) {
    $roomId = (int)$_GET['delete_room'];
    $conn->query("DELETE FROM hotel_rooms WHERE id = $roomId AND hotel_id = $id");
    header("Location: hotel-edit.php?id=$id&success=Room+Deleted");
    exit;
}

// 3. Handle Hotel Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_hotel') {
    $name = $conn->real_escape_string($_POST['name']);
    $loc = $conn->real_escape_string($_POST['location']);
    $price = $conn->real_escape_string($_POST['price']);
    $accom = $conn->real_escape_string($_POST['accommodations']);
    $desc = $conn->real_escape_string($_POST['description']);
    $dates = $conn->real_escape_string($_POST['available_dates']);
    $image = $conn->real_escape_string($_POST['existing_image']);
    $avail = isset($_POST['availability']) ? 1 : 0;

    $new_image = handleFileUpload('hotel_image');
    if ($new_image) $image = $new_image;

    $sql = "UPDATE app_hotels SET name='$name', location='$loc', price='$price', accommodations='$accom', description='$desc', available_dates='$dates', image='$image', availability=$avail WHERE id=$id";

    // Gallery Uploads
    if (isset($_FILES['hotel_gallery']) && !empty($_FILES['hotel_gallery']['name'][0])) {
        $galleryInputs = $_FILES['hotel_gallery'];
        for ($i = 0; $i < count($galleryInputs['name']); $i++) {
            if ($galleryInputs['error'][$i] === UPLOAD_ERR_OK) {
                $tmpName = $galleryInputs['tmp_name'][$i];
                $fileName = basename($galleryInputs["name"][$i]);
                $targetPath = "assets/images/" . time() . "_" . $i . "_" . $fileName;
                if (move_uploaded_file($tmpName, $targetPath)) {
                    $conn->query("INSERT INTO hotel_images (hotel_id, image_path) VALUES ($id, '$targetPath')");
                }
            }
        }
    }

    if ($conn->query($sql)) {
        header("Location: admin.php?success=Hotel+Updated");
        exit;
    }
}

// Handle Image Deletion
if (isset($_GET['delete_img'])) {
    $img_id = (int)$_GET['delete_img'];
    $conn->query("DELETE FROM hotel_images WHERE id = $img_id AND hotel_id = $id");
    header("Location: hotel-edit.php?id=$id&success=Image+Deleted");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Hotel - Travelo Admin</title>
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        body { background: #f4f7f6; font-family: 'Inter', sans-serif; padding: 40px 0; }
        .edit-card { background: #fff; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); padding: 40px; margin-bottom: 30px; }
        .section-title { font-size: 20px; font-weight: 700; color: #2c3e50; margin-bottom: 25px; border-left: 5px solid #F7921E; padding-left: 15px; }
        .btn-save { background: #F7921E; border: none; padding: 12px 30px; font-weight: 600; color: white; border-radius: 8px; }
        .form-control { border-radius: 8px; border: 1px solid #e1e9ec; padding: 10px; }
        .room-table img { height: 50px; width: 70px; object-fit: cover; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="admin.php" class="btn btn-outline-secondary rounded-pill px-4"><i class="fas fa-arrow-left me-2"></i>Dashboard</a>
            <h2 class="fw-bold mb-0">Manager: <?php echo htmlspecialchars($hotel['name']); ?></h2>
        </div>

        <!-- 1. Hotel Base Info -->
        <div class="edit-card">
            <h4 class="section-title">General Information</h4>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update_hotel">
                <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($hotel['image']); ?>">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="small fw-bold mb-1">Hotel Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($hotel['name']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-bold mb-1">Location</label>
                        <input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($hotel['location']); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="small fw-bold mb-1">Base Price (INR)</label>
                        <input type="number" name="price" class="form-control" value="<?php echo $hotel['price']; ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="small fw-bold mb-1">Category</label>
                        <select class="form-select form-control" name="accommodations" required>
                            <?php $types = ["Classic Tent", "Forest Camping", "Small Trailer", "Tree House Tent", "Tent Camping", "Couple Tent", "Luxury Hotel"];
                            foreach($types as $type) echo "<option value='$type' ".($hotel['accommodations']==$type?'selected':'').">$type</option>"; ?>
                        </select>
                    </div>
                     <div class="col-md-4">
                        <label class="small fw-bold mb-1">Publish Status</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="availability" <?php echo $hotel['availability'] ? 'checked' : ''; ?>>
                            <label class="form-check-label ps-2">Active on Website</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-bold mb-1">Main Banner Image</label>
                        <input type="file" name="hotel_image" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-bold mb-1">Add More Gallery Images</label>
                        <input type="file" name="hotel_gallery[]" class="form-control" accept="image/*" multiple>
                    </div>
                    <div class="col-12">
                        <label class="small fw-bold mb-1">Description</label>
                        <textarea name="description" class="form-control" rows="3" required><?php echo htmlspecialchars($hotel['description']); ?></textarea>
                    </div>
                    <div class="col-12">
                         <label class="small fw-bold mb-1">Gallery Preview</label>
                         <div class="row g-2 p-3 bg-light rounded shadow-sm border">
                            <?php 
                            $gi = $conn->query("SELECT * FROM hotel_images WHERE hotel_id = $id");
                            while($img = $gi->fetch_assoc()) {
                                echo "<div class='col-md-1 col-sm-3 position-relative'>
                                        <img src='{$img['image_path']}' class='rounded-3 w-100' style='height:60px; object-fit:cover;'>
                                        <a href='?id=$id&delete_img={$img['id']}' class='btn btn-danger btn-sm scale-on-hover position-absolute top-0 end-0 m-0 p-1' onclick=\"return confirm('Delete image?')\"><i class='fas fa-times' style='font-size:10px;'></i></a>
                                      </div>";
                            }
                            ?>
                         </div>
                    </div>
                    <div class="col-12 text-end mt-4">
                        <button type="submit" class="btn-save">Update Base Info</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- 2. Room Management -->
        <div class="edit-card shadow-lg" style="border: 1px solid #ffe8cc;">
            <h4 class="section-title">Manage Available Room Types</h4>
            
            <!-- Room List Table -->
            <div class="table-responsive mb-5">
                <table class="table room-table align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>Icon</th>
                            <th>Room Name</th>
                            <th>Capacity</th>
                            <th>Bed Type</th>
                            <th>Price</th>
                            <th>Features</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $rooms = $conn->query("SELECT * FROM hotel_rooms WHERE hotel_id = $id");
                        if($rooms->num_rows > 0):
                            while($rm = $rooms->fetch_assoc()):
                                $fts = json_decode($rm['features'], true);
                        ?>
                        <tr>
                            <td><img src="<?php echo $rm['room_image']; ?>"></td>
                            <td class="fw-bold"><?php echo htmlspecialchars($rm['room_name']); ?></td>
                            <td><?php echo $rm['capacity']; ?></td>
                            <td><?php echo $rm['bed_type']; ?></td>
                            <td class="text-success fw-bold">₹<?php echo number_format($rm['room_price']); ?></td>
                            <td><small class="text-muted"><?php echo implode(', ', $fts); ?></small></td>
                            <td>
                                <a href="?id=<?php echo $id; ?>&delete_room=<?php echo $rm['id']; ?>" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="return confirm('Remove this room type?')">
                                    <i class="fas fa-trash-alt me-1"></i>Delete
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr><td colspan="7" class="text-center py-4 text-muted">No rooms added yet. Add one below!</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Add New Room Form -->
            <div class="bg-light p-4 rounded-4 border border-warning border-opacity-25">
                <h5 class="fw-bold mb-4 text-warning"><i class="fas fa-plus-circle me-2"></i>Add New Room Variety</h5>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="room_action" value="add_room">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="small fw-bold">Room Title</label>
                            <input type="text" name="room_name" class="form-control" placeholder="e.g. Deluxe Suite" required>
                        </div>
                        <div class="col-md-2">
                            <label class="small fw-bold">Price / Night</label>
                            <input type="number" name="room_price" class="form-control" placeholder="INR" required>
                        </div>
                        <div class="col-md-3">
                            <label class="small fw-bold">Capacity</label>
                            <input type="text" name="capacity" class="form-control" placeholder="e.g. 2 Adults, 1 Child">
                        </div>
                        <div class="col-md-3">
                            <label class="small fw-bold">Bed Type</label>
                            <input type="text" name="bed_type" class="form-control" placeholder="e.g. King Bed">
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold">Room Specific Photo</label>
                            <input type="file" name="room_image" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold">Quick Features</label>
                            <div class="d-flex flex-wrap gap-2 mt-1">
                                <label class="btn btn-sm btn-outline-primary"><input type="checkbox" name="features[]" value="AC" hidden> AC</label>
                                <label class="btn btn-sm btn-outline-primary"><input type="checkbox" name="features[]" value="Free WiFi" hidden> WiFi</label>
                                <label class="btn btn-sm btn-outline-primary"><input type="checkbox" name="features[]" value="Bathtub" hidden> Bathtub</label>
                                <label class="btn btn-sm btn-outline-primary"><input type="checkbox" name="features[]" value="Minibar" hidden> Minibar</label>
                                <label class="btn btn-sm btn-outline-primary"><input type="checkbox" name="features[]" value="City View" hidden> View</label>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-warning w-100 fw-bold py-2 rounded-3 text-white">ADD ROOM TO SYSTEM</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
