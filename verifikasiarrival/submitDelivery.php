<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the form was submitted

    if (isset($_POST["submit"])) {
        // Form data is submitted through traditional form submission or AJAX

        $driverName = $_POST["id_driver"];
        $plan = $_POST["plan_arr"];
        $actual = $_POST["act_arr"];
        $longitude = $_POST["act_long"];
        $latitude = $_POST["act_lat"];
        $lokasi = $_POST["id_lokasi"];

        // Use prepared statements to prevent SQL injection
        $sql = "INSERT INTO transaksi_delivery(id_driver, plan_arr, act_arr, act_long, act_lat, id_lokasi) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            // Bind parameters
            mysqli_stmt_bind_param($stmt, "sssssi", $driverName, $plan, $actual, $longitude, $latitude, $lokasi);

            // Execute statement
            $result = mysqli_stmt_execute($stmt);

            if ($result) {
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    // AJAX request
                    echo json_encode(array("success" => true, "message" => "Form submitted successfully"));
                } else {
                    // Traditional form submission
                    header("Location: menampilkanverifikasikedatangan.php");
                    exit();
                }
            } else {
                // Failed submission
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    // AJAX request
                    echo json_encode(array("success" => false, "message" => "Failed to execute statement: " . mysqli_stmt_error($stmt)));
                } else {
                    // Traditional form submission
                    echo "Failed to execute statement: " . mysqli_stmt_error($stmt);
                }
            }

            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            // Failed to prepare statement
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                // AJAX request
                echo json_encode(array("success" => false, "message" => "Failed to prepare statement: " . mysqli_error($conn)));
            } else {
                // Traditional form submission
                echo "Failed to prepare statement: " . mysqli_error($conn);
            }
        }
    } else {
        // Submit button not pressed
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            // AJAX request
            echo json_encode(array("success" => false, "message" => "Submit button not pressed."));
        } else {
            // Traditional form submission
            echo "Submit button not pressed.";
        }
    }
} else {
    // Request method is not POST
    // You may want to handle this case accordingly
    echo "Invalid request method.";
}
?>