<?php
session_start(); // Start a session

require 'Services/database.php'; // Include the database connection file

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get input values
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Get database connection
    $conn = getDbConnection();
    
    // Prepare and bind
    $stmt = $conn->prepare("SELECT admin_id, username, password, profile_image_url FROM Admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($admin_id, $stored_username, $stored_password, $profile_image_url);
        $stmt->fetch();
        
        // Verify the input password with the stored hashed password
        if ($password === $stored_password) {
            // Password is correct
            $_SESSION['admin_id'] = $admin_id;
            $_SESSION['username'] = $stored_username;
            $_SESSION['image'] = $profile_image_url; // Store profile image URL in the session
            header("Location: dashboard.php"); // Redirect to the admin dashboard or home page
            exit();
        } else {
            $error_message = "Invalid username or password.";
        }
    } else {
        $error_message = "Invalid username or password.";
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <!-- basic -->
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <!-- mobile metas -->
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <!-- site metas -->
   <title>Login</title>
   <meta name="keywords" content="">
   <meta name="description" content="">
   <meta name="author" content="">
   <!-- bootstrap css -->
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   <!-- font awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
   <!-- favicon -->
   <link rel="icon" href="images/fevicon.png" type="image/gif" />
   <link rel="stylesheet" href="./css/LoginPageStyle.css">
</head>
<body>
    <div class="login-container">
        <form class="login-form" method="post" action="">
            <h2>Login</h2>
            <?php if ($error_message): ?>
                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <a href="#">Forgot Password?</a>
            <a href="#">Sign Up</a>
            <button type="submit">Login</button>
        </form>
    </div>

    <!-- bootstrap js and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        // Check if the session data exists in PHP
        <?php if (isset($_SESSION['admin_id']) && isset($_SESSION['username']) && isset($_SESSION['image'])): ?>
            // Store session data in sessionStorage
            sessionStorage.setItem('admin_id', <?php echo json_encode($_SESSION['admin_id']); ?>);
            sessionStorage.setItem('username', <?php echo json_encode($_SESSION['username']); ?>);
            sessionStorage.setItem('image', <?php echo json_encode($_SESSION['image']); ?>);
        <?php endif; ?>
    </script>
</body>
</html>
