
    
    <?php
// register_farmer.php

include '../Services/crud.php';

if (isset($_POST['register_farmer'])) {
    // Retrieve form data
    $user_id = $_POST['user_id'];
    $farm_name = $_POST['farm_name'];
    $address = $_POST['address'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $farm_size = $_POST['farm_size'];
    $farming_experience = $_POST['farming_experience'];
    
    // Handle file upload
    $profile_image_url = '';
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
            $profile_image_url = $target_file;
        }
    }

    // Prepare data for insertion
    $data = [
        'user_id' => $user_id,
        'farm_name' => $farm_name,
        'address' => $address,
        'contact_number' => $contact_number,
        'email' => $email,
        'farm_size' => $farm_size,
        'farming_experience' => $farming_experience,
        'profile_image_url' => $profile_image_url
    ];

    // Insert record into the Farmers table
    if (createRecord('Farmers', $data)) {
        echo "Farmer registered successfully!";
    } else {
        echo "Error registering farmer.";
    }
}
?>