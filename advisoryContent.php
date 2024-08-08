<?php
// Include database connection
include 'Services/database.php';

// Get database connection
$conn = getDbConnection();

// Define upload directory
$upload_dir = 'uploads/advisory_images/';

// Handle Insert Operation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'insert') {
    $variety_id = $_POST['variety_id'];
    $best_practices = $_POST['best_practices'];
    $estimated_costs = $_POST['estimated_costs'];

    // Handle file upload
    $advisory_image_url = '';
    if (isset($_FILES['advisory_image_url']) && $_FILES['advisory_image_url']['error'] == UPLOAD_ERR_OK) {
        $target_file = $upload_dir . basename($_FILES["advisory_image_url"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a valid image
        $check = getimagesize($_FILES["advisory_image_url"]["tmp_name"]);
        if ($check === false) {
            echo "<div class='alert alert-danger'>File is not an image.</div>";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["advisory_image_url"]["size"] > 500000) {
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
            if (move_uploaded_file($_FILES["advisory_image_url"]["tmp_name"], $target_file)) {
                $advisory_image_url = $target_file;
            } else {
                echo "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
            }
        }
    }

    $stmt = $conn->prepare("INSERT INTO Advisory (variety_id, best_practices, estimated_costs, advisory_image_url) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isis", $variety_id, $best_practices, $estimated_costs, $advisory_image_url);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>New advisory added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle Update Operation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update') {
    $advisory_id = $_POST['advisory_id'];
    $variety_id = $_POST['variety_id'];
    $best_practices = $_POST['best_practices'];
    $estimated_costs = $_POST['estimated_costs'];

    // Handle file upload
    $advisory_image_url = $_POST['current_advisory_image_url']; // Preserve the existing image
    if (isset($_FILES['advisory_image_url']) && $_FILES['advisory_image_url']['error'] == UPLOAD_ERR_OK) {
        $target_file = $upload_dir . basename($_FILES["advisory_image_url"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a valid image
        $check = getimagesize($_FILES["advisory_image_url"]["tmp_name"]);
        if ($check === false) {
            echo "<div class='alert alert-danger'>File is not an image.</div>";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["advisory_image_url"]["size"] > 500000) {
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
            if (move_uploaded_file($_FILES["advisory_image_url"]["tmp_name"], $target_file)) {
                $advisory_image_url = $target_file;
            } else {
                echo "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
            }
        }
    }

    $stmt = $conn->prepare("UPDATE Advisory SET variety_id=?, best_practices=?, estimated_costs=?, advisory_image_url=? WHERE advisory_id=?");
    $stmt->bind_param("isssi", $variety_id, $best_practices, $estimated_costs, $advisory_image_url, $advisory_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Advisory updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle Delete Operation
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['advisory_id'])) {
    $advisory_id = $_GET['advisory_id'];

    $stmt = $conn->prepare("SELECT advisory_image_url FROM Advisory WHERE advisory_id=?");
    $stmt->bind_param("i", $advisory_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $advisory_image_url = $row['advisory_image_url'];
    $stmt->close();

    if ($advisory_image_url && file_exists($advisory_image_url)) {
        unlink($advisory_image_url); // Delete the image file
    }

    $stmt = $conn->prepare("DELETE FROM Advisory WHERE advisory_id=?");
    $stmt->bind_param("i", $advisory_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Advisory deleted successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle Edit Operation
$edit_advisory = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['advisory_id'])) {
    $advisory_id = $_GET['advisory_id'];

    $stmt = $conn->prepare("SELECT * FROM Advisory WHERE advisory_id=?");
    $stmt->bind_param("i", $advisory_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_advisory = $result->fetch_assoc();
    $stmt->close();
}

// Fetch Varieties for Dropdowns
$varieties_result = $conn->query("SELECT variety_id, variety_name FROM Varieties");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advisory Management</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Advisory Management</h1>

        <!-- Advisory Form -->
        <h2 class="mb-3"><?php echo isset($edit_advisory) ? 'Update Advisory' : 'Add New Advisory'; ?></h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?php echo isset($edit_advisory) ? 'update' : 'insert'; ?>">
            <?php if (isset($edit_advisory)) { ?>
                <input type="hidden" name="advisory_id" value="<?php echo $edit_advisory['advisory_id']; ?>">
                <input type="hidden" name="current_advisory_image_url" value="<?php echo $edit_advisory['advisory_image_url']; ?>">
            <?php } ?>
            <div class="form-group">
                <label for="variety_id">Variety</label>
                <select id="variety_id" name="variety_id" class="form-control" required>
                    <option value="">Select Variety</option>
                    <?php while ($variety = $varieties_result->fetch_assoc()) { ?>
                        <option value="<?php echo $variety['variety_id']; ?>" <?php echo isset($edit_advisory) && $edit_advisory['variety_id'] == $variety['variety_id'] ? 'selected' : ''; ?>>
                            <?php echo $variety['variety_name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="best_practices">Best Practices</label>
                <textarea id="best_practices" name="best_practices" class="form-control" rows="3" required><?php echo isset($edit_advisory) ? $edit_advisory['best_practices'] : ''; ?></textarea>
            </div>
            <div class="form-group">
                <label for="estimated_costs">Estimated Costs</label>
                <input type="text" id="estimated_costs" name="estimated_costs" class="form-control" value="<?php echo isset($edit_advisory) ? $edit_advisory['estimated_costs'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="advisory_image_url">Advisory Image</label>
                <input type="file" id="advisory_image_url" name="advisory_image_url" class="form-control">
                <?php if (isset($edit_advisory) && $edit_advisory['advisory_image_url']) { ?>
                    <img src="<?php echo $edit_advisory['advisory_image_url']; ?>" alt="Advisory Image" class="img-thumbnail mt-2" width="200">
                <?php } ?>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo isset($edit_advisory) ? 'Update Advisory' : 'Add Advisory'; ?></button>
        </form>

        <!-- Advisory List -->
        <h2 class="mt-4">Advisory List</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Variety</th>
                    <th>Best Practices</th>
                    <th>Estimated Costs</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT a.advisory_id, a.variety_id, v.variety_name, a.best_practices, a.estimated_costs, a.advisory_image_url FROM Advisory a JOIN Varieties v ON a.variety_id = v.variety_id");
                while ($row = $result->fetch_assoc()) {
                ?>
                    <tr>
                        <td><?php echo $row['advisory_id']; ?></td>
                        <td><?php echo $row['variety_name']; ?></td>
                        <td><?php echo $row['best_practices']; ?></td>
                        <td><?php echo $row['estimated_costs']; ?></td>
                        <td>
                            <?php if ($row['advisory_image_url']) { ?>
                                <img src="<?php echo $row['advisory_image_url']; ?>" alt="Advisory Image" class="img-thumbnail" width="100">
                            <?php } ?>
                        </td>
                        <td>
                            <a href="?page=advisory&action=edit&advisory_id=<?php echo $row['advisory_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="?page=advisory&action=delete&advisory_id=<?php echo $row['advisory_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this advisory?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
