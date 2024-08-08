<!-- sidebar.php -->
<div class="sidebar border">
    <!-- User Info -->
    <div class="user-info">
        <img src="<?php echo htmlspecialchars($_SESSION['image']); ?>" alt="User Image">
        <h5><?php echo htmlspecialchars($_SESSION['username']); ?></h5>
    </div>

    <!-- Navigation -->
    <nav class="nav flex-column">
    <a href="?page=dashboard" class="nav-link <?php echo ($_GET['page'] ?? '') === 'dashboard' ? 'active' : ''; ?>">
        <i class='bx bx-home'></i> Dashboard
    </a>
    <a href="?page=crops" class="nav-link <?php echo ($_GET['page'] ?? '') === 'crops' ? 'active' : ''; ?>">
        <i class='bx bx-leaf'></i> Crops
    </a>
    <a href="?page=tasks" class="nav-link <?php echo ($_GET['page'] ?? '') === 'tasks' ? 'active' : ''; ?>">
        <i class='bx bx-task'></i> Tasks
    </a>
    <a href="?page=advisory" class="nav-link <?php echo ($_GET['page'] ?? '') === 'advisory' ? 'active' : ''; ?>">
        <i class='bx bx-info-circle'></i> Advisory
    </a>
    <a href="?page=profile" class="nav-link <?php echo ($_GET['page'] ?? '') === 'profile' ? 'active' : ''; ?>">
        <i class='bx bx-user'></i> Profile
    </a>
    <a href="?page=farmers" class="nav-link <?php echo ($_GET['page'] ?? '') === 'farmers' ? 'active' : ''; ?>">
        <i class='bx bx-group'></i> Farmers
    </a>
    <a href="?page=varieties" class="nav-link <?php echo ($_GET['page'] ?? '') === 'varieties' ? 'active' : ''; ?>">
        <i class='bx bxl-mongodb'></i> Varieties
    </a>
    <a href="?page=fertilization" class="nav-link <?php echo ($_GET['page'] ?? '') === 'fertilization' ? 'active' : ''; ?>">
        <i class='bx bx-donate-heart'></i> Fertilization
    </a>
    <a href="logout.php" class="nav-link">
        <i class='bx bx-log-out'></i> Logout
    </a>
</nav>




</div>
