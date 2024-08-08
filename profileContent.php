<?php
// Include database connection
include 'Services/database.php';

// Get database connection
$conn = getDbConnection();

// Define upload directory
$upload_dir = 'uploads/admin_images/';

// Handle Insert Operation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'insert') {
    $username = $_POST['username'];
    $password = $_POST['password']; // Directly store the plain password

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

    $stmt = $conn->prepare("INSERT INTO Admins (username, password, profile_image_url) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $profile_image_url);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>New admin added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle Update Operation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update') {
    $admin_id = $_POST['admin_id'];
    $username = $_POST['username'];
    $password = isset($_POST['password']) ? $_POST['password'] : null; // Directly store the plain password if provided

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

    $stmt = $conn->prepare("UPDATE Admins SET username=?, password=?, profile_image_url=? WHERE admin_id=?");
    $stmt->bind_param("sssi", $username, $password, $profile_image_url, $admin_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Admin updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle Delete Operation
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['admin_id'])) {
    $admin_id = $_GET['admin_id'];

    $stmt = $conn->prepare("SELECT profile_image_url FROM Admins WHERE admin_id=?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $profile_image_url = $row['profile_image_url'];
    $stmt->close();

    if ($profile_image_url && file_exists($profile_image_url)) {
        unlink($profile_image_url); // Delete the image file
    }

    $stmt = $conn->prepare("DELETE FROM Admins WHERE admin_id=?");
    $stmt->bind_param("i", $admin_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Admin deleted successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle Edit Operation
$edit_admin = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['admin_id'])) {
    $admin_id = $_GET['admin_id'];

    $stmt = $conn->prepare("SELECT * FROM Admins WHERE admin_id=?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_admin = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Management</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Admin Management</h1>

        <!-- Admin Form -->
        <h2 class="mb-3"><?php echo isset($edit_admin) ? 'Update Admin' : 'Add New Admin'; ?></h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?php echo isset($edit_admin) ? 'update' : 'insert'; ?>">
            <?php if (isset($edit_admin)) { ?>
                <input type="hidden" name="admin_id" value="<?php echo $edit_admin['admin_id']; ?>">
                <input type="hidden" name="current_profile_image_url" value="<?php echo $edit_admin['profile_image_url']; ?>">
            <?php } ?>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" value="<?php echo isset($edit_admin) ? $edit_admin['username'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" <?php echo !isset($edit_admin) ? 'required' : ''; ?>>
                <?php if (isset($edit_admin)) { ?>
                    <small class="form-text text-muted">Leave empty to keep current password.</small>
                <?php } ?>
            </div>
            <div class="form-group">
                <label for="profile_image_url">Profile Image</label>
                <input type="file" id="profile_image_url" name="profile_image_url" class="form-control">
                <?php if (isset($edit_admin) && $edit_admin['profile_image_url']) { ?>
                    <img src="<?php echo $edit_admin['profile_image_url']; ?>" alt="Profile Image" class="img-thumbnail mt-2" width="100">
                <?php } ?>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo isset($edit_admin) ? 'Update Admin' : 'Add Admin'; ?></button>
        </form>

        <!-- Admin List -->
        <h2 class="mt-5 mb-3">Admin List</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Profile Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM Admins");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                    echo "<td><img src='" . htmlspecialchars($row['profile_image_url']) . "' alt='Profile Image' class='img-thumbnail' width='100'></td>";
                    echo "<td>
                            <a href='?action=edit&admin_id=" . $row['admin_id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='?action=delete&admin_id=" . $row['admin_id'] . "' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this admin?');\">Delete</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
