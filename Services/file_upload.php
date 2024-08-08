<?php
// file_upload.php

function uploadImage($file, $targetDir = "uploads/") {
    $targetFile = $targetDir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Validate file type
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowedTypes)) {
        return ["success" => false, "message" => "Invalid file type. Only JPG, JPEG, PNG, & GIF files are allowed."];
    }

    // Move the uploaded file
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return ["success" => true, "path" => $targetFile];
    } else {
        return ["success" => false, "message" => "Error uploading file."];
    }
}
?>
