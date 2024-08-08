<?php
// Include database connection
include 'Services/database.php';

// Get database connection
$conn = getDbConnection();

// Define upload directory
$upload_dir = 'uploads/variety_images/';

// Handle Insert Operation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'insert') {
    $crop_id = $_POST['crop_id'];
    $variety_name = $_POST['variety_name'];
    $spacing = $_POST['spacing'];

    // Handle file upload
    $variety_image_url = '';
    if (isset($_FILES['variety_image_url']) && $_FILES['variety_image_url']['error'] == UPLOAD_ERR_OK) {
        $target_file = $upload_dir . basename($_FILES["variety_image_url"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a valid image
        $check = getimagesize($_FILES["variety_image_url"]["tmp_name"]);
        if ($check === false) {
            echo "<div class='alert alert-danger'>File is not an image.</div>";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["variety_image_url"]["size"] > 500000) {
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
            if (move_uploaded_file($_FILES["variety_image_url"]["tmp_name"], $target_file)) {
                $variety_image_url = $target_file;
            } else {
                echo "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
            }
        }
    }

    $stmt = $conn->prepare("INSERT INTO Varieties (crop_id, variety_name, spacing, variety_image_url) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $crop_id, $variety_name, $spacing, $variety_image_url);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>New variety added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle Update Operation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update') {
    $variety_id = $_POST['variety_id'];
    $crop_id = $_POST['crop_id'];
    $variety_name = $_POST['variety_name'];
    $spacing = $_POST['spacing'];

    // Handle file upload
    $variety_image_url = $_POST['current_variety_image_url']; // Preserve the existing image
    if (isset($_FILES['variety_image_url']) && $_FILES['variety_image_url']['error'] == UPLOAD_ERR_OK) {
        $target_file = $upload_dir . basename($_FILES["variety_image_url"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a valid image
        $check = getimagesize($_FILES["variety_image_url"]["tmp_name"]);
        if ($check === false) {
            echo "<div class='alert alert-danger'>File is not an image.</div>";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["variety_image_url"]["size"] > 500000) {
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
            if (move_uploaded_file($_FILES["variety_image_url"]["tmp_name"], $target_file)) {
                $variety_image_url = $target_file;
            } else {
                echo "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
            }
        }
    }

    $stmt = $conn->prepare("UPDATE Varieties SET crop_id=?, variety_name=?, spacing=?, variety_image_url=? WHERE variety_id=?");
    $stmt->bind_param("isssi", $crop_id, $variety_name, $spacing, $variety_image_url, $variety_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Variety updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle Delete Operation
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['variety_id'])) {
    $variety_id = $_GET['variety_id'];

    $stmt = $conn->prepare("SELECT variety_image_url FROM Varieties WHERE variety_id=?");
    $stmt->bind_param("i", $variety_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $variety_image_url = $row['variety_image_url'];
    $stmt->close();

    if ($variety_image_url && file_exists($variety_image_url)) {
        unlink($variety_image_url); // Delete the image file
    }

    $stmt = $conn->prepare("DELETE FROM Varieties WHERE variety_id=?");
    $stmt->bind_param("i", $variety_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Variety deleted successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle Edit Operation
$edit_variety = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['variety_id'])) {
    $variety_id = $_GET['variety_id'];

    $stmt = $conn->prepare("SELECT * FROM Varieties WHERE variety_id=?");
    $stmt->bind_param("i", $variety_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_variety = $result->fetch_assoc();
    $stmt->close();
}

// Fetch Crop Options for Dropdown
$crops = [];
$crops_result = $conn->query("SELECT crop_id, crop_name FROM Crops");
while ($row = $crops_result->fetch_assoc()) {
    $crops[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Varieties Management</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Varieties Management</h1>

        <!-- Variety Form -->
        <h2 class="mb-3"><?php echo isset($edit_variety) ? 'Update Variety' : 'Add New Variety'; ?></h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?php echo isset($edit_variety) ? 'update' : 'insert'; ?>">
            <?php if (isset($edit_variety)): ?>
                <input type="hidden" name="variety_id" value="<?php echo htmlspecialchars($edit_variety['variety_id']); ?>">
                <input type="hidden" name="current_variety_image_url" value="<?php echo htmlspecialchars($edit_variety['variety_image_url']); ?>">
            <?php endif; ?>
            <div class="form-group">
                <label for="crop_id">Crop</label>
                <select class="form-control" id="crop_id" name="crop_id" required>
                    <option value="" disabled <?php echo !isset($edit_variety) ? 'selected' : ''; ?>>Select Crop</option>
                    <?php foreach ($crops as $crop): ?>
                        <option value="<?php echo htmlspecialchars($crop['crop_id']); ?>" <?php echo isset($edit_variety) && $edit_variety['crop_id'] == $crop['crop_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($crop['crop_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="variety_name">Variety Name</label>
                <input type="text" class="form-control" id="variety_name" name="variety_name" placeholder="Variety Name" value="<?php echo isset($edit_variety) ? htmlspecialchars($edit_variety['variety_name']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="spacing">Spacing</label>
                <input type="text" class="form-control" id="spacing" name="spacing" placeholder="Spacing" value="<?php echo isset($edit_variety) ? htmlspecialchars($edit_variety['spacing']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="variety_image_url">Variety Image</label>
                <input type="file" class="form-control-file" id="variety_image_url" name="variety_image_url">
                <?php if (isset($edit_variety) && $edit_variety['variety_image_url']): ?>
                    <img src="<?php echo htmlspecialchars($edit_variety['variety_image_url']); ?>" alt="Variety Image" style="width: 150px; height: auto; margin-top: 10px;">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo isset($edit_variety) ? 'Update Variety' : 'Add Variety'; ?></button>
        </form>

        <!-- Varieties Table -->
        <h2 class="mt-4">Varieties List</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Crop</th>
                    <th>Variety Name</th>
                    <th>Spacing</th>
                    <th>Variety Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT v.variety_id, v.variety_name, v.spacing, v.variety_image_url, c.crop_name 
                                         FROM Varieties v 
                                         JOIN Crops c ON v.crop_id = c.crop_id");
                while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['variety_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['crop_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['variety_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['spacing']); ?></td>
                    <td>
                        <?php if ($row['variety_image_url']): ?>
                            <img src="<?php echo htmlspecialchars($row['variety_image_url']); ?>" alt="Variety Image" style="width: 100px; height: auto;">
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="?page=varieties&action=edit&variety_id=<?php echo htmlspecialchars($row['variety_id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="?page=varieties&action=delete&variety_id=<?php echo htmlspecialchars($row['variety_id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this variety?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
