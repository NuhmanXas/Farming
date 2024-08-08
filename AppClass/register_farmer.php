<?php
// register_farmer.php

include '../Services/crud.php';

// Define upload directory
$upload_dir = 'uploads/farmer_images/';


if (isset($_POST['register_farmer'])) {
    // Retrieve form data
    $farm_name = $_POST['farm_name'];
    $address = $_POST['address'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $farm_size = $_POST['farm_size'];
    $farming_experience = $_POST['farming_experience'];
    
    // Handle file upload
    $profile_image_url = '';
    $profile_image_urlForStore = '';
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
        $target_file = '../'. $upload_dir . basename($_FILES["profile_image"]["name"]);
        $target_file_for_stoer = $upload_dir . basename($_FILES["profile_image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a valid image
        $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
        if ($check === false) {
            echo "<script>alert('File is not an image.');</script>";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["profile_image"]["size"] > 500000) { // 500KB limit
            echo "<script>alert('Sorry, your file is too large.');</script>";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "<script>alert('Sorry, your file was not uploaded.');</script>";
        } else {
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $profile_image_url = $target_file;
                $profile_image_urlForStore = $target_file_for_stoer;
            } else {
                echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
            }
        }
    }

    // Prepare data for insertion
    $data = [
        'farm_name' => $farm_name,
        'address' => $address,
        'contact_number' => $contact_number,
        'email' => $email,
        'farm_size' => $farm_size,
        'farming_experience' => $farming_experience,
        'profile_image_url' => $profile_image_urlForStore
    ];

    // Insert record into the Farmers table
    if (createRecord('Farmers', $data)) {
        echo "<script>
                alert('Farmer registered successfully!');
                window.location.href = document.referrer;
              </script>";
    } else {
        echo "<script>
                alert('Error registering farmer.');
                window.location.href = document.referrer;
              </script>";
    }
}
?>
