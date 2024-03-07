<?php
include('../config.php');
$bpid = $_GET['bpid'];

// Fetch location data based on bpid
$query = "SELECT lat, longi FROM master_lokasi WHERE bpid = '$bpid'";
$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo json_encode($row);
} else {
    echo json_encode(array('lat' => null, 'long' => null));
}

mysqli_close($conn);
?>