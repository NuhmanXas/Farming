<?php 
$title = "CRAS Home";
include 'Components/header.php'; 
include 'Components/navigation.php'; 
include 'Components/loader.php'; 
?>


    <div class="container mt-5">
        <h2 class="text-center mb-4">Register Farmer</h2>
        <form action="AppClass/register_farmer.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="farm_name">Farm Name:</label>
                <input type="text" id="farm_name" name="farm_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="contact_number">Contact Number:</label>
                <input type="text" id="contact_number" name="contact_number" class="form-control" required pattern="\d{10,15}">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="farm_size">Farm Size (in acres):</label>
                <input type="number" id="farm_size" name="farm_size" class="form-control" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="farming_experience">Farming Experience (in years):</label>
                <input type="number" id="farming_experience" name="farming_experience" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="profile_image">Profile Image:</label>
                <input type="file" id="profile_image" name="profile_image" class="form-control-file" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
  

   
  <?php 
include 'Components/footer.php'; 
include 'Components/scripts.php'; 
?>