<?php
include "../config.php";

if (isset($_POST["submit"])) {
    $driverName = $_POST["driverName1"];
    $plan = $_POST["plan1"];
    $actual = $_POST["actual1"];
    $longitude = $_POST["longitude1"];
    $latitude = $_POST["latitude1"];
    $lokasi = $_POST["Lokasi1"];

    // Validate inputs (you may customize validation rules)
    if (empty($driverName) || empty($plan) || empty($actual) || empty($longitude) || empty($latitude) || empty($lokasi)) {
        die("All fields are required");
    }

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO transaksi_delivery(id_driver, plan_arr, act_arr, act_long, act_lat, id_lokasi) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss",$driverName, $plan, $actual, $longitude, $latitude, $lokasi);

    $result = $stmt->execute();

    if ($result) {
        echo "Success"; // Send success message to the AJAX call
    } else {
        echo "Error: " . $stmt->error; // Send error message to the AJAX call
    }

    $stmt->close();
}
?>