<?php
// Include database connection
include 'Services/database.php';

// Get database connection
$conn = getDbConnection();

// Define upload directory
$upload_dir = 'uploads/crop_image/';

// Handle Insert Operation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'insert') {
    $crop_name = $_POST['crop_name'];
    $scientific_name = $_POST['scientific_name'];
    $suitable_season = $_POST['suitable_season'];
    $seed_requirement = $_POST['seed_requirement'];

    // Handle file upload
    $crop_image_url = '';
    if (isset($_FILES['crop_image_url']) && $_FILES['crop_image_url']['error'] == UPLOAD_ERR_OK) {
        $target_file = $upload_dir . basename($_FILES["crop_image_url"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image
        $check = getimagesize($_FILES["crop_image_url"]["tmp_name"]);
        if ($check === false) {
            echo "<div class='alert alert-danger'>File is not an image.</div>";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["crop_image_url"]["size"] > 500000) {
            echo "<div class='alert alert-danger'>Sorry, your file is too large.</div>";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            echo "<div class='alert alert-danger'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "<div class='alert alert-danger'>Sorry, your file was not uploaded.</div>";
        } else {
            if (move_uploaded_file($_FILES["crop_image_url"]["tmp_name"], $target_file)) {
                $crop_image_url = $target_file;
            } else {
                echo "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
            }
        }
    }

    $stmt = $conn->prepare("INSERT INTO Crops (crop_name, scientific_name, suitable_season, seed_requirement, crop_image_url) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $crop_name, $scientific_name, $suitable_season, $seed_requirement, $crop_image_url);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>New crop added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle Update Operation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update') {
    $crop_id = $_POST['crop_id'];
    $crop_name = $_POST['crop_name'];
    $scientific_name = $_POST['scientific_name'];
    $suitable_season = $_POST['suitable_season'];
    $seed_requirement = $_POST['seed_requirement'];

    // Handle file upload
    $crop_image_url = $_POST['current_crop_image_url']; // Preserve the existing image
    if (isset($_FILES['crop_image_url']) && $_FILES['crop_image_url']['error'] == UPLOAD_ERR_OK) {
        $target_file = $upload_dir . basename($_FILES["crop_image_url"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image
        $check = getimagesize($_FILES["crop_image_url"]["tmp_name"]);
        if ($check === false) {
            echo "<div class='alert alert-danger'>File is not an image.</div>";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["crop_image_url"]["size"] > 500000) {
            echo "<div class='alert alert-danger'>Sorry, your file is too large.</div>";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            echo "<div class='alert alert-danger'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "<div class='alert alert-danger'>Sorry, your file was not uploaded.</div>";
        } else {
            if (move_uploaded_file($_FILES["crop_image_url"]["tmp_name"], $target_file)) {
                $crop_image_url = $target_file;
            } else {
                echo "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
            }
        }
    }

    $stmt = $conn->prepare("UPDATE Crops SET crop_name=?, scientific_name=?, suitable_season=?, seed_requirement=?, crop_image_url=? WHERE crop_id=?");
    $stmt->bind_param("sssssi", $crop_name, $scientific_name, $suitable_season, $seed_requirement, $crop_image_url, $crop_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Crop updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle Delete Operation
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['crop_id'])) {
    $crop_id = $_GET['crop_id'];

    $stmt = $conn->prepare("SELECT crop_image_url FROM Crops WHERE crop_id=?");
    $stmt->bind_param("i", $crop_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $crop_image_url = $row['crop_image_url'];
    $stmt->close();

    if ($crop_image_url && file_exists($crop_image_url)) {
        unlink($crop_image_url); // Delete the image file
    }

    $stmt = $conn->prepare("DELETE FROM Crops WHERE crop_id=?");
    $stmt->bind_param("i", $crop_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Crop deleted successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle Edit Operation
$edit_crop = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['crop_id'])) {
    $crop_id = $_GET['crop_id'];

    $stmt = $conn->prepare("SELECT * FROM Crops WHERE crop_id=?");
    $stmt->bind_param("i", $crop_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_crop = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crops Management</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Crops Management</h1>

        <!-- Crop Form -->
        <h2 class="mb-3"><?php echo isset($edit_crop) ? 'Update Crop' : 'Add New Crop'; ?></h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?php echo isset($edit_crop) ? 'update' : 'insert'; ?>">
            <?php if (isset($edit_crop)): ?>
                <input type="hidden" name="crop_id" value="<?php echo htmlspecialchars($edit_crop['crop_id']); ?>">
                <input type="hidden" name="current_crop_image_url" value="<?php echo htmlspecialchars($edit_crop['crop_image_url']); ?>">
            <?php endif; ?>
            <div class="form-group">
                <label for="crop_name">Crop Name</label>
                <input type="text" class="form-control" id="crop_name" name="crop_name" placeholder="Crop Name" value="<?php echo isset($edit_crop) ? htmlspecialchars($edit_crop['crop_name']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="scientific_name">Scientific Name</label>
                <input type="text" class="form-control" id="scientific_name" name="scientific_name" placeholder="Scientific Name" value="<?php echo isset($edit_crop) ? htmlspecialchars($edit_crop['scientific_name']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="suitable_season">Suitable Season</label>
                <input type="text" class="form-control" id="suitable_season" name="suitable_season" placeholder="Suitable Season" value="<?php echo isset($edit_crop) ? htmlspecialchars($edit_crop['suitable_season']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="seed_requirement">Seed Requirement (per unit area)</label>
                <input type="number" step="0.01" class="form-control" id="seed_requirement" name="seed_requirement" placeholder="Seed Requirement" value="<?php echo isset($edit_crop) ? htmlspecialchars($edit_crop['seed_requirement']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="crop_image_url">Crop Image</label>
                <input type="file" class="form-control-file" id="crop_image_url" name="crop_image_url">
                <?php if (isset($edit_crop) && $edit_crop['crop_image_url']): ?>
                    <img src="<?php echo htmlspecialchars($edit_crop['crop_image_url']); ?>" alt="Crop Image" class="img-thumbnail mt-2" style="max-width: 200px;">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo isset($edit_crop) ? 'Update Crop' : 'Add Crop'; ?></button>
        </form>

        <!-- Crops List -->
        <h2 class="mt-5 mb-3">Crops List</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Crop Name</th>
                    <th>Scientific Name</th>
                    <th>Suitable Season</th>
                    <th>Seed Requirement</th>
                    <th>Crop Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM Crops");
                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['crop_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['crop_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['scientific_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['suitable_season']); ?></td>
                        <td><?php echo htmlspecialchars($row['seed_requirement']); ?></td>
                        <td>
                            <?php if ($row['crop_image_url']): ?>
                                <img src="<?php echo htmlspecialchars($row['crop_image_url']); ?>" alt="Crop Image" class="img-thumbnail" style="max-width: 100px;">
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="?page=crops&action=edit&crop_id=<?php echo htmlspecialchars($row['crop_id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="?page=crops&action=delete&crop_id=<?php echo htmlspecialchars($row['crop_id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this crop?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
