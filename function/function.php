<?php

include("../config.php");
include("../lib/phpqrcode/qrlib.php");
include("../config.php");


function getDataDelivery($conn)
{
    $sql = "SELECT customer,cycle,dock_customer,qty_paket,qty FROM scheudule_delivery ";

    $result = mysqli_query($conn, $sql);

    // Check if the query was successful
    if (!$result) {
        die("Error in SQL query: " . mysqli_error($conn));
    }

    // Fetch data and return the result
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
         // Generate QR Code for each row
         $qrData = "Customer: {$row['customer']}, Cycle: {$row['cycle']}, Dock: {$row['dock_customer']}, Qty Paket: {$row['qty_paket']}, Qty: {$row['qty']}";
         $qrFilename = "../gbrqrcode/{$row['customer']}.png"; // Save QR codes with unique filenames
         QRcode::png($qrData, $qrFilename);
 
         // Add QR code filename to the row data
         $row['qrcode'] = $qrFilename;
 
         $data[] = $row;
    }

    return $data;
}
?>