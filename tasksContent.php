<?php
// Include database connection
include 'Services/database.php';

// Get database connection
$conn = getDbConnection();

// Define upload directory
$upload_dir = 'uploads/task_images/';

// Handle Insert Operation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'insert') {
    $farmer_id = $_POST['farmer_id'];
    $variety_id = $_POST['variety_id'];
    $task_date = $_POST['task_date'];
    $task_description = $_POST['task_description'];

    // Handle file upload
    $task_image_url = '';
    if (isset($_FILES['task_image_url']) && $_FILES['task_image_url']['error'] == UPLOAD_ERR_OK) {
        $target_file = $upload_dir . basename($_FILES["task_image_url"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a valid image
        $check = getimagesize($_FILES["task_image_url"]["tmp_name"]);
        if ($check === false) {
            echo "<div class='alert alert-danger'>File is not an image.</div>";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["task_image_url"]["size"] > 500000) {
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
            if (move_uploaded_file($_FILES["task_image_url"]["tmp_name"], $target_file)) {
                $task_image_url = $target_file;
            } else {
                echo "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
            }
        }
    }

    $stmt = $conn->prepare("INSERT INTO Tasks (farmer_id, variety_id, task_date, task_description, task_image_url) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $farmer_id, $variety_id, $task_date, $task_description, $task_image_url);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>New task added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle Update Operation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update') {
    $task_id = $_POST['task_id'];
    $farmer_id = $_POST['farmer_id'];
    $variety_id = $_POST['variety_id'];
    $task_date = $_POST['task_date'];
    $task_description = $_POST['task_description'];

    // Handle file upload
    $task_image_url = $_POST['current_task_image_url']; // Preserve the existing image
    if (isset($_FILES['task_image_url']) && $_FILES['task_image_url']['error'] == UPLOAD_ERR_OK) {
        $target_file = $upload_dir . basename($_FILES["task_image_url"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a valid image
        $check = getimagesize($_FILES["task_image_url"]["tmp_name"]);
        if ($check === false) {
            echo "<div class='alert alert-danger'>File is not an image.</div>";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["task_image_url"]["size"] > 500000) {
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
            if (move_uploaded_file($_FILES["task_image_url"]["tmp_name"], $target_file)) {
                $task_image_url = $target_file;
            } else {
                echo "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
            }
        }
    }

    $stmt = $conn->prepare("UPDATE Tasks SET farmer_id=?, variety_id=?, task_date=?, task_description=?, task_image_url=? WHERE task_id=?");
    $stmt->bind_param("iisssi", $farmer_id, $variety_id, $task_date, $task_description, $task_image_url, $task_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Task updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle Delete Operation
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    $stmt = $conn->prepare("SELECT task_image_url FROM Tasks WHERE task_id=?");
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $task_image_url = $row['task_image_url'];
    $stmt->close();

    if ($task_image_url && file_exists($task_image_url)) {
        unlink($task_image_url); // Delete the image file
    }

    $stmt = $conn->prepare("DELETE FROM Tasks WHERE task_id=?");
    $stmt->bind_param("i", $task_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Task deleted successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle Edit Operation
$edit_task = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    $stmt = $conn->prepare("SELECT * FROM Tasks WHERE task_id=?");
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_task = $result->fetch_assoc();
    $stmt->close();
}

// Fetch Farmers and Varieties for Dropdowns
$farmers_result = $conn->query("SELECT farmer_id, farm_name FROM Farmers");
$varieties_result = $conn->query("SELECT variety_id, variety_name FROM Varieties");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Task Management</h1>

        <!-- Task Form -->
        <h2 class="mb-3"><?php echo isset($edit_task) ? 'Update Task' : 'Add New Task'; ?></h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?php echo isset($edit_task) ? 'update' : 'insert'; ?>">
            <?php if (isset($edit_task)) { ?>
                <input type="hidden" name="task_id" value="<?php echo $edit_task['task_id']; ?>">
                <input type="hidden" name="current_task_image_url" value="<?php echo $edit_task['task_image_url']; ?>">
            <?php } ?>
            <div class="form-group">
                <label for="farmer_id">Farmer</label>
                <select id="farmer_id" name="farmer_id" class="form-control" required>
                    <option value="">Select Farmer</option>
                    <?php while ($row = $farmers_result->fetch_assoc()) { ?>
                        <option value="<?php echo $row['farmer_id']; ?>" <?php echo isset($edit_task) && $edit_task['farmer_id'] == $row['farmer_id'] ? 'selected' : ''; ?>>
                            <?php echo $row['farm_name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="variety_id">Variety</label>
                <select id="variety_id" name="variety_id" class="form-control" required>
                    <option value="">Select Variety</option>
                    <?php while ($row = $varieties_result->fetch_assoc()) { ?>
                        <option value="<?php echo $row['variety_id']; ?>" <?php echo isset($edit_task) && $edit_task['variety_id'] == $row['variety_id'] ? 'selected' : ''; ?>>
                            <?php echo $row['variety_name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="task_date">Task Date</label>
                <input type="date" id="task_date" name="task_date" class="form-control" value="<?php echo isset($edit_task) ? $edit_task['task_date'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="task_description">Task Description</label>
                <textarea id="task_description" name="task_description" class="form-control" required><?php echo isset($edit_task) ? $edit_task['task_description'] : ''; ?></textarea>
            </div>
            <div class="form-group">
                <label for="task_image_url">Task Image (Optional)</label>
                <input type="file" id="task_image_url" name="task_image_url" class="form-control">
                <?php if (isset($edit_task) && $edit_task['task_image_url']) { ?>
                    <img src="<?php echo $edit_task['task_image_url']; ?>" alt="Task Image" style="max-width: 200px; margin-top: 10px;">
                <?php } ?>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo isset($edit_task) ? 'Update Task' : 'Add Task'; ?></button>
        </form>

        <!-- Task List -->
        <h2 class="mt-4 mb-3">Task List</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Farmer</th>
                    <th>Variety</th>
                    <th>Task Date</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT t.task_id, f.farm_name, v.variety_name, t.task_date, t.task_description, t.task_image_url 
                                         FROM Tasks t 
                                         JOIN Farmers f ON t.farmer_id = f.farmer_id 
                                         JOIN Varieties v ON t.variety_id = v.variety_id");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['farm_name']}</td>";
                    echo "<td>{$row['variety_name']}</td>";
                    echo "<td>{$row['task_date']}</td>";
                    echo "<td>{$row['task_description']}</td>";
                    echo "<td>" . ($row['task_image_url'] ? "<img src='{$row['task_image_url']}' alt='Task Image' style='max-width: 100px;'>" : 'No Image') . "</td>";
                    echo "<td>
                        <a href='?page=tasks&action=edit&task_id={$row['task_id']}' class='btn btn-warning btn-sm'>Edit</a>
                        <a href='?page=tasks&action=delete&task_id={$row['task_id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this task?');\">Delete</a>
                    </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
