<?php 
ob_start();
session_start();
// Set timezone 
date_default_timezone_set('Asia/Jakarta'); 
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "verifyarrival";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

?> 
