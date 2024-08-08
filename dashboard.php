<?php
// Start the session
session_start();

// Check if session variables are set
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php"); // Replace with your login page URL
    exit();
}

// If session variables are set, you can set placeholder values or perform other actions if needed
if (!isset($_SESSION['image'])) {
    $_SESSION['image'] = 'uploads/admin_images/defaultAdmin.png'; // Placeholder image
}
?>

<!-- Include header -->
<?php include 'Components/AdminLayout/header.php'; ?>

<div class="container-fluid h-100 d-flex flex-column">
    <!-- Include sidebar -->
    <?php include 'Components/AdminLayout/sidebar.php'; ?>

    <div class=" ms-auto" style="width: calc(100% - 260px);">
        <div class="row vh-100">
            <div class="col-lg-12 border">
                <!-- Main Content -->
                <?php
                // Include content based on the page parameter
                if (isset($_GET['page'])) {
                    $page = $_GET['page'];

                    switch ($page) {
                        case 'dashboard':
                            include 'dashboardContent.php';
                            break;
                        case 'crops':
                            include 'cropsContent.php';
                            break;
                        case 'tasks':
                            include 'tasksContent.php';
                            break;
                        case 'advisory':
                            include 'advisoryContent.php';
                            break;
                        case 'profile':
                            include 'profileContent.php';
                            break;
                        case 'farmers':
                            include 'farmersContent.php'; // Add the file for farmers
                            break;
                        case 'varieties':
                            include 'varietiesContent.php'; // Add the file for varieties
                            break;
                        case 'fertilization':
                            include 'fertilizationContent.php'; // Add the file for fertilization
                            break;
                        default:
                            echo "Page not found.";
                    }
                } else {
                    include 'dashboardContent.php'; // Default content
                }
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Include footer -->
<?php include 'Components/AdminLayout/footer.php'; ?>
