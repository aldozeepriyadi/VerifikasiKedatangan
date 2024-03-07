<?php
include("../config.php");

// Assuming you have a database connection established
// Include your database connection file here

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle the uploaded image
    $imageBase64 = $_POST['imageCanvas'];
    $decodedImage = base64_decode($imageBase64);
    $uploadDirectory = '../images/';
    
    $imageFilename = $uploadDirectory . uniqid() . '.png';
    file_put_contents($imageFilename, $decodedImage);

    
    $userId = $_POST['userId'];

    $sql = "UPDATE transaksi_delivery SET endpoint_filename = '$imageFilename' WHERE id_transaksi = '$userId'";
   
    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>