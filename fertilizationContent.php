<?php
// Include database connection
include 'Services/database.php';

// Get database connection
$conn = getDbConnection();

// Define upload directory
$upload_dir = 'uploads/fertilization_images/';

// Handle Insert Operation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'insert') {
    $variety_id = $_POST['variety_id'];
    $fertilization_method = $_POST['fertilization_method'];
    $fertilization_description = $_POST['fertilization_description'];
    $time_of_application = $_POST['time_of_application'];

    // Handle file upload
    $fertilization_image_url = '';
    if (isset($_FILES['fertilization_image_url']) && $_FILES['fertilization_image_url']['error'] == UPLOAD_ERR_OK) {
        $target_file = $upload_dir . basename($_FILES["fertilization_image_url"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a valid image
        $check = getimagesize($_FILES["fertilization_image_url"]["tmp_name"]);
        if ($check === false) {
            echo "<div class='alert alert-danger'>File is not an image.</div>";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["fertilization_image_url"]["size"] > 500000) {
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
            if (move_uploaded_file($_FILES["fertilization_image_url"]["tmp_name"], $target_file)) {
                $fertilization_image_url = $target_file;
            } else {
                echo "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
            }
        }
    }

    $stmt = $conn->prepare("INSERT INTO Fertilization (variety_id, fertilization_method, fertilization_description, time_of_application, fertilization_image_url) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $variety_id, $fertilization_method, $fertilization_description, $time_of_application, $fertilization_image_url);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>New fertilization record added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle Update Operation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update') {
    $fertilization_id = $_POST['fertilization_id'];
    $variety_id = $_POST['variety_id'];
    $fertilization_method = $_POST['fertilization_method'];
    $fertilization_description = $_POST['fertilization_description'];
    $time_of_application = $_POST['time_of_application'];

    // Handle file upload
    $fertilization_image_url = $_POST['current_fertilization_image_url']; // Preserve the existing image
    if (isset($_FILES['fertilization_image_url']) && $_FILES['fertilization_image_url']['error'] == UPLOAD_ERR_OK) {
        $target_file = $upload_dir . basename($_FILES["fertilization_image_url"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a valid image
        $check = getimagesize($_FILES["fertilization_image_url"]["tmp_name"]);
        if ($check === false) {
            echo "<div class='alert alert-danger'>File is not an image.</div>";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["fertilization_image_url"]["size"] > 500000) {
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
            if (move_uploaded_file($_FILES["fertilization_image_url"]["tmp_name"], $target_file)) {
                $fertilization_image_url = $target_file;
            } else {
                echo "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
            }
        }
    }

    $stmt = $conn->prepare("UPDATE Fertilization SET variety_id=?, fertilization_method=?, fertilization_description=?, time_of_application=?, fertilization_image_url=? WHERE fertilization_id=?");
    $stmt->bind_param("issssi", $variety_id, $fertilization_method, $fertilization_description, $time_of_application, $fertilization_image_url, $fertilization_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Fertilization record updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle Delete Operation
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['fertilization_id'])) {
    $fertilization_id = $_GET['fertilization_id'];

    $stmt = $conn->prepare("SELECT fertilization_image_url FROM Fertilization WHERE fertilization_id=?");
    $stmt->bind_param("i", $fertilization_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $fertilization_image_url = $row['fertilization_image_url'];
    $stmt->close();

    if ($fertilization_image_url && file_exists($fertilization_image_url)) {
        unlink($fertilization_image_url); // Delete the image file
    }

    $stmt = $conn->prepare("DELETE FROM Fertilization WHERE fertilization_id=?");
    $stmt->bind_param("i", $fertilization_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Fertilization record deleted successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle Edit Operation
$edit_fertilization = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['fertilization_id'])) {
    $fertilization_id = $_GET['fertilization_id'];

    $stmt = $conn->prepare("SELECT * FROM Fertilization WHERE fertilization_id=?");
    $stmt->bind_param("i", $fertilization_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_fertilization = $result->fetch_assoc();
    $stmt->close();
}

// Fetch Variety Options for Dropdown
$varieties = [];
$varieties_result = $conn->query("SELECT variety_id, variety_name FROM Varieties");
while ($row = $varieties_result->fetch_assoc()) {
    $varieties[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fertilization Management</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Fertilization Management</h1>

        <!-- Fertilization Form -->
        <h2 class="mb-3"><?php echo isset($edit_fertilization) ? 'Update Fertilization' : 'Add New Fertilization'; ?></h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?php echo isset($edit_fertilization) ? 'update' : 'insert'; ?>">
            <?php if (isset($edit_fertilization)) { ?>
                <input type="hidden" name="fertilization_id" value="<?php echo $edit_fertilization['fertilization_id']; ?>">
                <input type="hidden" name="current_fertilization_image_url" value="<?php echo $edit_fertilization['fertilization_image_url']; ?>">
            <?php } ?>
            <div class="form-group">
                <label for="variety_id">Variety</label>
                <select id="variety_id" name="variety_id" class="form-control" required>
                    <?php foreach ($varieties as $variety) { ?>
                        <option value="<?php echo $variety['variety_id']; ?>" <?php echo isset($edit_fertilization) && $edit_fertilization['variety_id'] == $variety['variety_id'] ? 'selected' : ''; ?>>
                            <?php echo $variety['variety_name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="fertilization_method">Fertilization Method</label>
                <input type="text" id="fertilization_method" name="fertilization_method" class="form-control" value="<?php echo isset($edit_fertilization) ? $edit_fertilization['fertilization_method'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="fertilization_description">Description</label>
                <textarea id="fertilization_description" name="fertilization_description" class="form-control" rows="3" required><?php echo isset($edit_fertilization) ? $edit_fertilization['fertilization_description'] : ''; ?></textarea>
            </div>
            <div class="form-group">
                <label for="time_of_application">Time of Application</label>
                <input type="text" id="time_of_application" name="time_of_application" class="form-control" value="<?php echo isset($edit_fertilization) ? $edit_fertilization['time_of_application'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="fertilization_image_url">Image</label>
                <input type="file" id="fertilization_image_url" name="fertilization_image_url" class="form-control">
                <?php if (isset($edit_fertilization) && $edit_fertilization['fertilization_image_url']) { ?>
                    <img src="<?php echo $edit_fertilization['fertilization_image_url']; ?>" alt="Fertilization Image" class="img-thumbnail mt-2" style="max-width: 200px;">
                <?php } ?>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo isset($edit_fertilization) ? 'Update' : 'Add'; ?></button>
        </form>

        <!-- Fertilization Records Table -->
        <h2 class="mt-4 mb-3">Fertilization Records</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Variety</th>
                    <th>Method</th>
                    <th>Description</th>
                    <th>Time of Application</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $records = $conn->query("SELECT f.*, v.variety_name FROM Fertilization f JOIN Varieties v ON f.variety_id = v.variety_id");
                while ($row = $records->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['fertilization_id']}</td>
                        <td>{$row['variety_name']}</td>
                        <td>{$row['fertilization_method']}</td>
                        <td>{$row['fertilization_description']}</td>
                        <td>{$row['time_of_application']}</td>
                        <td>";
                        if ($row['fertilization_image_url']) {
                            echo "<img src='{$row['fertilization_image_url']}' alt='Image' class='img-thumbnail' style='max-width: 100px;'>";
                        }
                        echo "</td>
                        <td>
                            <a href='?page=fertilization&action=edit&fertilization_id={$row['fertilization_id']}' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='?page=fertilization&action=delete&fertilization_id={$row['fertilization_id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
