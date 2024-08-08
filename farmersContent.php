<?php
// Include database connection
include 'Services/database.php';

// Get database connection
$conn = getDbConnection();

// Define upload directory
$upload_dir = 'uploads/farmer_images/';

// Handle Insert Operation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'insert') {
    $farm_name = $_POST['farm_name'];
    $address = $_POST['address'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $farm_size = $_POST['farm_size'];
    $farming_experience = $_POST['farming_experience'];

    // Handle file upload
    $profile_image_url = '';
    if (isset($_FILES['profile_image_url']) && $_FILES['profile_image_url']['error'] == UPLOAD_ERR_OK) {
        $target_file = $upload_dir . basename($_FILES["profile_image_url"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a valid image
        $check = getimagesize($_FILES["profile_image_url"]["tmp_name"]);
        if ($check === false) {
            echo "<div class='alert alert-danger'>File is not an image.</div>";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["profile_image_url"]["size"] > 500000) {
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
            if (move_uploaded_file($_FILES["profile_image_url"]["tmp_name"], $target_file)) {
                $profile_image_url = $target_file;
            } else {
                echo "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
            }
        }
    }

    $stmt = $conn->prepare("INSERT INTO Farmers (farm_name, address, contact_number, email, farm_size, farming_experience, profile_image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssdis", $farm_name, $address, $contact_number, $email, $farm_size, $farming_experience, $profile_image_url);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>New farmer added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle Update Operation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update') {
    $farmer_id = $_POST['farmer_id'];
    $farm_name = $_POST['farm_name'];
    $address = $_POST['address'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $farm_size = $_POST['farm_size'];
    $farming_experience = $_POST['farming_experience'];

    // Handle file upload
    $profile_image_url = $_POST['current_profile_image_url']; // Preserve the existing image
    if (isset($_FILES['profile_image_url']) && $_FILES['profile_image_url']['error'] == UPLOAD_ERR_OK) {
        $target_file = $upload_dir . basename($_FILES["profile_image_url"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a valid image
        $check = getimagesize($_FILES["profile_image_url"]["tmp_name"]);
        if ($check === false) {
            echo "<div class='alert alert-danger'>File is not an image.</div>";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["profile_image_url"]["size"] > 500000) {
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
            if (move_uploaded_file($_FILES["profile_image_url"]["tmp_name"], $target_file)) {
                $profile_image_url = $target_file;
            } else {
                echo "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
            }
        }
    }

    $stmt = $conn->prepare("UPDATE Farmers SET farm_name=?, address=?, contact_number=?, email=?, farm_size=?, farming_experience=?, profile_image_url=? WHERE farmer_id=?");
    $stmt->bind_param("ssssdisi", $farm_name, $address, $contact_number, $email, $farm_size, $farming_experience, $profile_image_url, $farmer_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Farmer updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle Delete Operation
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['farmer_id'])) {
    $farmer_id = $_GET['farmer_id'];

    $stmt = $conn->prepare("SELECT profile_image_url FROM Farmers WHERE farmer_id=?");
    $stmt->bind_param("i", $farmer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $profile_image_url = $row['profile_image_url'];
    $stmt->close();

    if ($profile_image_url && file_exists($profile_image_url)) {
        unlink($profile_image_url); // Delete the image file
    }

    $stmt = $conn->prepare("DELETE FROM Farmers WHERE farmer_id=?");
    $stmt->bind_param("i", $farmer_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Farmer deleted successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle Edit Operation
$edit_farmer = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['farmer_id'])) {
    $farmer_id = $_GET['farmer_id'];

    $stmt = $conn->prepare("SELECT * FROM Farmers WHERE farmer_id=?");
    $stmt->bind_param("i", $farmer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_farmer = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmers Management</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Farmers Management</h1>

        <!-- Farmer Form -->
        <h2 class="mb-3"><?php echo isset($edit_farmer) ? 'Update Farmer' : 'Add New Farmer'; ?></h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?php echo isset($edit_farmer) ? 'update' : 'insert'; ?>">
            <?php if (isset($edit_farmer)): ?>
                <input type="hidden" name="farmer_id" value="<?php echo htmlspecialchars($edit_farmer['farmer_id']); ?>">
                <input type="hidden" name="current_profile_image_url" value="<?php echo htmlspecialchars($edit_farmer['profile_image_url']); ?>">
            <?php endif; ?>
            <div class="form-group">
                <label for="farm_name">Farm Name</label>
                <input type="text" class="form-control" id="farm_name" name="farm_name" placeholder="Farm Name" value="<?php echo isset($edit_farmer) ? htmlspecialchars($edit_farmer['farm_name']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="<?php echo isset($edit_farmer) ? htmlspecialchars($edit_farmer['address']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" class="form-control" id="contact_number" name="contact_number" placeholder="Contact Number" value="<?php echo isset($edit_farmer) ? htmlspecialchars($edit_farmer['contact_number']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo isset($edit_farmer) ? htmlspecialchars($edit_farmer['email']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="farm_size">Farm Size (in acres)</label>
                <input type="number" step="0.01" class="form-control" id="farm_size" name="farm_size" placeholder="Farm Size" value="<?php echo isset($edit_farmer) ? htmlspecialchars($edit_farmer['farm_size']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="farming_experience">Farming Experience (years)</label>
                <input type="number" class="form-control" id="farming_experience" name="farming_experience" placeholder="Farming Experience" value="<?php echo isset($edit_farmer) ? htmlspecialchars($edit_farmer['farming_experience']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="profile_image_url">Profile Image</label>
                <input type="file" class="form-control-file" id="profile_image_url" name="profile_image_url">
                <?php if (isset($edit_farmer) && $edit_farmer['profile_image_url']): ?>
                    <img src="<?php echo htmlspecialchars($edit_farmer['profile_image_url']); ?>" alt="Profile Image" style="width: 150px; height: auto; margin-top: 10px;">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo isset($edit_farmer) ? 'Update Farmer' : 'Add Farmer'; ?></button>
        </form>

        <!-- Farmers Table -->
        <h2 class="mt-4">Farmers List</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Farm Name</th>
                    <th>Address</th>
                    <th>Contact Number</th>
                    <th>Email</th>
                    <th>Farm Size</th>
                    <th>Experience</th>
                    <th>Profile Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM Farmers");
                while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['farmer_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['farm_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                    <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['farm_size']); ?></td>
                    <td><?php echo htmlspecialchars($row['farming_experience']); ?></td>
                    <td>
                        <?php if ($row['profile_image_url']): ?>
                            <img src="<?php echo htmlspecialchars($row['profile_image_url']); ?>" alt="Profile Image" style="width: 100px; height: auto;">
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="?page=farmers&action=edit&farmer_id=<?php echo htmlspecialchars($row['farmer_id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="?page=farmers&action=delete&farmer_id=<?php echo htmlspecialchars($row['farmer_id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this farmer?');">Delete</a>
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
