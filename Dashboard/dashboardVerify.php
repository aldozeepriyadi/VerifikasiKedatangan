<?php
include("../config.php");

session_start(); // Make sure to start the session at the beginning

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../Login/login.php");
    exit();
}

if (!isset($_SESSION['username'])) {
    header("Location: ../Login/login.php");
    exit();
}
$currentDate = date("Y-m-d"); // Get the current date in the format YYYY-MM-DD


// For Current Event
$sqlCurrentEvent = "SELECT 
vd.id_verifikasi,
sd.bpid, 
sd.cycle, 
sd.dock_customer, 
sd.qty_palet, 
TIME(sd.plan_arrival) AS plan_arrival_time, 
sd.id_schedule, 
sd.waktu
FROM 
schedule_delivery sd 
JOIN 
verifikasi_delivery vd ON sd.id_schedule = vd.id_schedule
WHERE 
sd.plan_arrival <= DATE_ADD('$currentDate', INTERVAL 3 DAY)
AND vd.status = 0 ";
$resultCurrentEvent = mysqli_query($conn, $sqlCurrentEvent);
$counterCurrentEvent = 1;



// For Upcoming Events
$sqlUpcomingEvents = "SELECT vd.id_verifikasi, sd.bpid, sd.cycle, sd.dock_customer, sd.qty_palet, TIME(sd.plan_arrival) AS plan_arrival_time, sd.id_schedule, sd.waktu FROM schedule_delivery sd JOIN verifikasi_delivery vd ON sd.id_schedule = vd.id_schedule WHERE sd.plan_arrival > DATE_ADD('$currentDate', INTERVAL 3 DAY) AND vd.status = 0";
$resultUpcomingEvents = mysqli_query($conn, $sqlUpcomingEvents);
$counterUpcomingEvents = 1;

if (isset($_POST["simpan"])) {
    $problem = $_POST["problem"];
    $lat = $_POST["latitude"];
    $longi = $_POST['longitude'];
    $id_schedule = $_POST['id_schedule'];

    // Decode base64-encoded image data
    $fotoData = $_POST["foto"];
    $imageBlob = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $fotoData));
    $newImagePath = "../uploads/image_" . uniqid() . ".png";
    file_put_contents($newImagePath, $imageBlob);

    // Convert the binary image data to a hexadecimal representation
    $fotoHex = bin2hex($fotoBinary);

    $sql = "INSERT INTO problem_delivery(bukti_foto, problem, lat, longi, id_schedule,ins_dt,inst_usr) 
            VALUES ('$newImagePath', '$problem', '$lat', '$longi', '$id_schedule',NOW(),'" . $_SESSION['id_driver'] . "')";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        // header("Location:dashboardVerify.php");
    } else {
        echo "Failed: " . mysqli_error($conn);
    }
}
if (isset($_POST["submit"])) {

    $latitude = $_POST["latitudeKedatangan"];
    $longitude = $_POST['longitudeKedatangan'];
    $id_schedule = $_POST['id_scheduleKedatangan'];

    $id_verifikasi =$_POST['id_verifikasi'];

    // Decode base64-encoded image data
    $fotoData = $_POST["fotoBinary"];
    $imageBlob = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $fotoData));
    $newImagePath = "../uploads/image_" . uniqid() . ".png";
    file_put_contents($newImagePath, $imageBlob);


    // Convert the binary image data to a hexadecimal representation


    $sql = "UPDATE verifikasi_delivery 
    SET bukti_foto='$newImagePath', 
        status=1, 
        actual_arrival=NOW(), 
        longi='$longitude', 
        lat='$latitude' 
    WHERE id_verifikasi='$id_verifikasi' ";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location:dashboardVerify.php");
    } else {
        echo "Failed: " . mysqli_error($conn);
    }
}






?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Verifikasi Kedatangan</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="../assets/img/favicon.png" rel="icon">
    <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">


    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <script type="text/javascript" src="https://unpkg.com/webcam-easy/dist/webcam-easy.min.js"></script>
    <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Template Main CSS File -->
    <link href="../assets/css/style.css" rel="stylesheet">

    <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Nov 17 2023 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="index.html" class="logo d-flex align-items-center">
                <img src="../assets/img/kybgambar-removebg-preview.png" alt="">

            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->



        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">


                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="../assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
                        <span class="d-none d-md-block dropdown-toggle ps-2">
                            <?php echo $_SESSION['nama']; ?>
                        </span>
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6>
                                <?php echo $_SESSION['nama']; ?>
                            </h6>
                            <span>Driver</span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="post" name="logoutForm">
                                <button type="submit" class="dropdown-item d-flex align-items-center" name="logout"
                                    id="logout">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Sign Out</span>
                                </button>
                            </form>

                            </script>
                        </li>

                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->

            </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">


            <li class="nav-item">
                <a class="nav-link " data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-layout-text-window-reverse"></i><span>Dashboard</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="tables-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="dashboardVerify.php" class="active">
                            <i class="bi bi-circle"></i><span>Verifikasi Kedatangan</span>
                        </a>

                    </li>
                    <li>
                        <a href="../Historical/historical.php">
                            <i class="bi bi-circle"></i><span>Historical Problem</span>
                        </a>

                    </li>
                    <li>
                        <a href="../Historical/historicalKedatangan.php">
                            <i class="bi bi-circle"></i><span>Historical Verifikasi Kedatangan</span>
                        </a>

                    </li>

                </ul>
            </li><!-- End Tables Nav -->



        </ul>

    </aside><!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Verifikasi Kedatangan</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item active">Verifikasi Kedatangan</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Current Event</h5>

                            <div class="table-responsive">
                                <!-- Table with stripped rows -->
                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th>Action</th>
                                            <th>Customer</th>
                                            <th>Cycle</th>
                                            <th>Dock</th>
                                            <th>Plan</th>


                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $index = 1;

                                        while ($rowCurrentEvent = mysqli_fetch_assoc($resultCurrentEvent)) {

                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="row mb-3">
                                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                            id="kirimfotoBtnCurrent<?php echo $index ?>"
                                                            data-bs-target="#kirimfotoModal"
                                                            data-id_schedule="<?php echo $rowCurrentEvent['id_schedule']; ?>"
                                                            data-plan_arr="<?php echo $rowCurrentEvent['plan_arrival_time']; ?>"
                                                            data-waktu="<?php echo $rowCurrentEvent['waktu']; ?>"
                                                            data-bpid="<?php echo $rowCurrentEvent['bpid']; ?>">Problem
                                                        </button>
                                                    </div>
                                                    <script>
                                                        $(document).ready(function () {
                                                            // Fungsi untuk memperbarui waktu setiap detik


                                                            // Memanggil fungsi updateClock setiap detik
                                                            document.getElementById('kirimfotoBtnCurrent<?php echo $index ?>').addEventListener('click', function () {
                                                                var id_schedule = this.getAttribute('data-id_schedule');
                                                                var plan_arr = this.getAttribute('data-plan_arr');
                                                                var bpid = this.getAttribute('data-bpid');
                                                                var waktu = this.getAttribute('data-waktu');
                                                                var currentTime = new Date();

                                                                // Extract hours, minutes, and seconds
                                                                var currentHours = currentTime.getHours();
                                                                var currentMinutes = currentTime.getMinutes();
                                                                var currentSeconds = currentTime.getSeconds();

                                                                // Format the time as HH:MM:SS
                                                                var formattedCurrentTime = currentHours + ':' + currentMinutes + ':' + currentSeconds;
                                                                console.log(waktu);
                                                                console.log(id_schedule);
                                                                $('#id_schedule').val(id_schedule);

                                                                function updateClock() {


                                                                    // Mengirim waktu dan ID ke server menggunakan AJAX
                                                                    $.ajax({
                                                                        type: "POST",
                                                                        url: "update_time.php",
                                                                        data: { id_schedule: id_schedule, waktu: formattedCurrentTime },
                                                                        success: function (response) {
                                                                            if (response.status === 'success') {
                                                                                console.log("Waktu berhasil diperbarui di server.");
                                                                                console.log(response.message);
                                                                            } else {
                                                                                console.error("Gagal mengirim waktu ke server: " + response.message);
                                                                            }
                                                                        },
                                                                        error: function (xhr, status, error) {
                                                                            console.error("Gagal mengirim waktu ke server: " + error);
                                                                            console.log("Status: " + status);
                                                                            console.log("XHR Object: ", xhr);
                                                                        }
                                                                    });
                                                                }
                                                                function toRadians(degrees) {
                                                                    return degrees * (Math.PI / 180);
                                                                }

                                                                function calculateHaversineDistance(lat1, lon1, lat2, lon2) {
                                                                    const R = 6371; // Radius of the Earth in kilometers
                                                                    const dLat = toRadians(lat2 - lat1);
                                                                    const dLon = toRadians(lon2 - lon1);

                                                                    const a =
                                                                        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                                                                        Math.cos(toRadians(lat1)) * Math.cos(toRadians(lat2)) *
                                                                        Math.sin(dLon / 2) * Math.sin(dLon / 2);

                                                                    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

                                                                    return R * c;
                                                                }

                                                                function validateTimeAndProceed(distance, id_schedule, waktu) {
                                                                    // Set up an interval to update the clock (assuming updateClock is a function)
                                                                    setInterval(function () {
                                                                        updateClock();
                                                                    }, 3600000);

                                                                    var planArrivalTime = waktu;
                                                                    var planArrivalTimeParts = planArrivalTime.split(':');
                                                                    var getHours = planArrivalTimeParts[0];
                                                                    var getMinutes = planArrivalTimeParts[1];
                                                                    var getSeconds = planArrivalTimeParts[2];
                                                                    var PlanformatedTime = getHours + ':' + getMinutes + ':' + getSeconds;
                                                                    var PlanformatedTime = new Date();
                                                                    PlanformatedTime.setHours(getHours, getMinutes, getSeconds, 0);



                                                                    var currentTime = new Date();
                                                                    // var currentHours = currentTime.getHours();
                                                                    // var currentMinutes = currentTime.getMinutes();
                                                                    // var currentSeconds = currentTime.getSeconds();

                                                                    // Format the time as HH:MM:SS
                                                                    // var formattedCurrentTime = currentHours + ':' + currentMinutes + ':' + currentSeconds;



                                                                    var timeDifference = currentTime - PlanformatedTime;
                                                                    // Convert time difference to hours
                                                                    var hoursDifference = timeDifference / (1000 * 60 * 60);
                                                                    var hourAsli = (hoursDifference < 0) ? 24 + hoursDifference : hoursDifference;

                                                                    //    $('#kirimfotoModal').modal('show');
                                                                    console.log(hourAsli);
                                                                    if (hourAsli >= 1 && distance >= 0.005) {
                                                                        // //     // Continue with the process as one hour has passed and the device is within distance
                                                                        $('#kirimfotoModal').modal('show');
                                                                        console.log("aplikasinya jalan yaa");
                                                                        document.getElementById('simpanBtn').addEventListener('click', function () {
                                                                            // Check your condition here

                                                                            // Call the updateClock function
                                                                            updateClock();
                                                                        });
                                                                    } else {

                                                                        // Show SweetAlert indicating that one hour hasn't passed yet
                                                                        swal({
                                                                            icon: 'warning',
                                                                            title: 'Oops...',
                                                                            text: 'Tidak dapat Melakukan Input problem karena anda tidak mengalami stagnan',
                                                                        }).then(function () {
                                                                            // Hide the modal here

                                                                            window.location = "dashboardVerify.php";
                                                                        });
                                                                    }
                                                                }



                                                                $.ajax({
                                                                    url: 'get_lokasi.php',
                                                                    type: 'GET',
                                                                    data: { bpid: bpid },
                                                                    dataType: 'json',
                                                                    success: function (data) {
                                                                        console.log(data.lat, data.longi);
                                                                        var lat2 = parseFloat(data.lat);
                                                                        var lon2 = parseFloat(data.longi);



                                                                        if (navigator.geolocation) {
                                                                            navigator.geolocation.getCurrentPosition(
                                                                                function (position) {
                                                                                    var lat1 = position.coords.latitude;
                                                                                    var lon1 = position.coords.longitude;
                                                                                    console.log(lat1, lon1);

                                                                                    const distance = calculateHaversineDistance(lat1, lon1, lat2, lon2);
                                                                                    validateTimeAndProceed(distance, id_schedule, waktu);
                                                                                },
                                                                                function (error) {
                                                                                    console.error('Error getting user location:', error);
                                                                                    // Handle errors here, e.g., show a default location or prompt the user
                                                                                }

                                                                            );
                                                                        } else {
                                                                            console.error('Geolocation is not supported by this browser.');
                                                                            // Handle browsers that do not support geolocation
                                                                        }
                                                                    },
                                                                    error: function () {
                                                                        // Handle AJAX errors
                                                                        console.log('Error fetching location data');
                                                                    }
                                                                });
                                                            });


                                                        });


                                                    </script>


                                                    <div class="row mb-3">
                                                        <button type="button" class="btn btn-success action-verifikasi"
                                                            id="verifikasiBtn<?php echo $index ?>" data-bs-toggle="modal"
                                                            data-bs-target="#verifikasiModal"
                                                            data-bpid="<?php echo $rowCurrentEvent['bpid']; ?>"
                                                            data-id_schedule="<?php echo $rowCurrentEvent['id_schedule']; ?>"
                                                            data-id_verifikasi="<?php echo $rowCurrentEvent['id_verifikasi']; ?>">Verifikasi
                                                            Kedatangan</button>
                                                        <script>
                                                            document.getElementById('verifikasiBtn<?php echo $index ?>').addEventListener('click', function () {

                                                                var bpid = this.getAttribute('data-bpid')
                                                                var id_schedule = this.getAttribute('data-id_schedule');
                                                                var id_verifikasi = this.getAttribute('data-id_verifikasi');
                                                                $('#id_scheduleKedatangan').val(id_schedule);
                                                                $('#id_verifikasi').val(id_verifikasi);
                                                                $.ajax({
                                                                    url: 'get_lokasi.php',
                                                                    type: 'GET',
                                                                    data: { bpid: bpid },
                                                                    dataType: 'json',
                                                                    success: function (data) {
                                                                        console.log(data.lat, data.longi);
                                                                        var lat2 = parseFloat(data.lat);
                                                                        var lon2 = parseFloat(data.longi);

                                                                        if (navigator.geolocation) {
                                                                            navigator.geolocation.getCurrentPosition(function (position) {
                                                                                var lat1 = position.coords.latitude;
                                                                                var lon1 = position.coords.longitude;
                                                                                const distance = calculateHaversineDistance(lat1, lon1, lat2, lon2);

                                                                                if (distance <= 0.005) {
                                                                                    $('#verifikasiModal').modal('show');
                                                                                    

                                                                                } else if (distance >= 0.005) {
                                                                                    swal({
                                                                                        icon: 'warning',
                                                                                        title: 'Oops...',
                                                                                        text: 'Anda Belum Sampai Tujuan Customer',
                                                                                    }).then(function () {

                                                                                        // Hide the modal here
                                                                                        $('#verifikasiModal').modal('hide');
                                                                                        window.location = "dashboardVerify.php"
                                                                                    });
                                                                                }

                                                                            }, function (error) {
                                                                                console.error('Error getting user location:', error);
                                                                                // Handle errors here, e.g., show a default location or prompt the user
                                                                            });
                                                                        } else {
                                                                            console.error('Geolocation is not supported by this browser.');
                                                                            // Handle browsers that do not support geolocation
                                                                        }
                                                                    },
                                                                    error: function () {
                                                                        // Handle AJAX errors
                                                                        console.log('Error fetching location data');
                                                                    }
                                                                });
                                                            });
                                                            function toRadians(degrees) {
                                                                return degrees * (Math.PI / 180);
                                                            }
                                                            function calculateHaversineDistance(lat1, lon1, lat2, lon2) {
                                                                const R = 6371; // Radius of the Earth in kilometers
                                                                const dLat = toRadians(lat2 - lat1);
                                                                const dLon = toRadians(lon2 - lon1);

                                                                const a =
                                                                    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                                                                    Math.cos(toRadians(lat1)) * Math.cos(toRadians(lat2)) *
                                                                    Math.sin(dLon / 2) * Math.sin(dLon / 2);

                                                                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

                                                                return R * c;
                                                            }


                                                        </script>
                                                    </div>
                                                </td>

                                                <td>

                                                    <?php echo $rowCurrentEvent['bpid'] ?>
                                                </td>

                                                <td>

                                                    <?php echo $rowCurrentEvent['cycle']; ?>
                                                </td>

                                                <td>

                                                    <?php echo $rowCurrentEvent["dock_customer"];
                                                    ?>
                                                </td>
                                                <td>

                                                    <?php echo $rowCurrentEvent['plan_arrival_time']; ?>
                                                </td>
                                                <td style="display:none;">

                                                    <?php echo $rowCurrentEvent["qty_palet"];
                                                    ?>
                                                </td>
                                                <td style="display:none;">

                                                    <?php echo $rowCurrentEvent["id_schedule"];
                                                    ?>
                                                </td>




                                            </tr>


                                        </tbody>
                                        <?php
                                        $index++;
                                        $counterCurrentEvent++;
                                        }


                                        ?>
                                </table>
                                <!-- End Table with stripped rows -->
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Upcoming Event</h5>

                            <div class="table-responsive">
                                <!-- Table with stripped rows -->
                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th>Action</th>
                                            <th>Customer</th>
                                            <th>Cycle</th>
                                            <th>Dock</th>
                                            <th>Plan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $index = 1;
                                        while ($rowUpcomingEvent = mysqli_fetch_assoc($resultUpcomingEvents)) {

                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="row mb-3">
                                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                            id="kirimfotoBtn<?php echo $index ?>"
                                                            data-bs-target="#kirimfotoModal"
                                                            data-bpid="<?php echo $rowUpcomingEvent['bpid']; ?>"
                                                            data-id_schedule="<?php echo $rowUpcomingEvent['id_schedule']; ?>"
                                                            data-plan_arr="<?php echo $rowUpcomingEvent['plan_arrival_time']; ?>"
                                                            data-waktu="<?php echo $rowUpcomingEvent['waktu']; ?>"
                                                          >Problem
                                                        </button>
                                                    </div>

                                                    <script>
                                                        $(document).ready(function () {
                                                            // Fungsi untuk memperbarui waktu setiap detik
                                                            function updateClock(id_schedule, formattedCurrentTime) {


                                                                // Mengirim waktu dan ID ke server menggunakan AJAX
                                                                $.ajax({
                                                                    type: "POST",
                                                                    url: "update_time.php",
                                                                    data: { id_schedule: id_schedule, waktu: formattedCurrentTime }, // Ganti 1 dengan ID yang sesuai
                                                                    success: function (response) {
                                                                        console.log("Waktu berhasil diperbarui di server.");
                                                                    },
                                                                    error: function (error) {
                                                                        console.error("Gagal mengirim waktu ke server: " + error);
                                                                    }
                                                                });
                                                            }
                                                            function toRadians(degrees) {
                                                                return degrees * (Math.PI / 180);
                                                            }

                                                            function calculateHaversineDistance(lat1, lon1, lat2, lon2) {
                                                                const R = 6371; // Radius of the Earth in kilometers
                                                                const dLat = toRadians(lat2 - lat1);
                                                                const dLon = toRadians(lon2 - lon1);

                                                                const a =
                                                                    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                                                                    Math.cos(toRadians(lat1)) * Math.cos(toRadians(lat2)) *
                                                                    Math.sin(dLon / 2) * Math.sin(dLon / 2);

                                                                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

                                                                return R * c;
                                                            }

                                                            function validateTimeAndProceed(distance, id_schedule, waktu, formattedCurrentTime) {
                                                                // Set up an interval to update the clock (assuming updateClock is a function)
                                                                setInterval(function () {
                                                                    updateClock();
                                                                }, 3600000);

                                                                var planArrivalTime = waktu;
                                                                var planArrivalTimeParts = planArrivalTime.split(':');
                                                                var getHours = planArrivalTimeParts[0];
                                                                var getMinutes = planArrivalTimeParts[1];
                                                                var getSeconds = planArrivalTimeParts[2];
                                                                var PlanformatedTime = getHours + ':' + getMinutes + ':' + getSeconds;
                                                                var PlanformatedTime = new Date();
                                                                PlanformatedTime.setHours(getHours, getMinutes, getSeconds, 0);



                                                                var currentTime = new Date();
                                                                var currentHours = currentTime.getHours();
                                                                var currentMinutes = currentTime.getMinutes();
                                                                var currentSeconds = currentTime.getSeconds();

                                                                // Format the time as HH:MM:SS
                                                                var formattedCurrentTime = currentHours + ':' + currentMinutes + ':' + currentSeconds;




                                                                var timeDifference = currentTime - PlanformatedTime;
                                                                // Convert time difference to hours
                                                                var hoursDifference = timeDifference / (1000 * 60 * 60);
                                                                var hourAsli = (hoursDifference < 0) ? 24 + hoursDifference : hoursDifference;

                                                                //    $('#kirimfotoModal').modal('show');
                                                                console.log(hourAsli);
                                                                if (hourAsli >= 1 && distance >= 0.005) {
                                                                    $('#kirimfotoModal').modal('show');
                                                                    console.log("aplikasinya jalan yaa");
                                                                    document.getElementById('simpanBtn').addEventListener('click', function () {
                                                                        updateClock(id_schedule, formattedCurrentTime);
                                                                    });
                                                                } else {

                                                                    // Show SweetAlert indicating that one hour hasn't passed yet
                                                                    swal({
                                                                        icon: 'warning',
                                                                        title: 'Oops...',
                                                                        text: 'Tidak dapat Melakukan Input problem karena anda tidak mengalami stagnan',
                                                                    }).then(function () {
                                                                        window.location = "dashboardVerify.php";
                                                                    });
                                                                }
                                                            }




                                                            // Memanggil fungsi updateClock setiap detik
                                                            document.getElementById('kirimfotoBtn<?php echo $index ?>').addEventListener('click', function () {
                                                                var id_schedule = this.getAttribute('data-id_schedule');
                                                                var plan_arr = this.getAttribute('data-plan_arr');
                                                                var bpid = this.getAttribute('data-bpid');
                                                                var waktu = this.getAttribute('data-waktu');
                                                                
                                                                var currentTime = new Date();
                                                                var currentHours = currentTime.getHours();
                                                                var currentMinutes = currentTime.getMinutes();
                                                                var currentSeconds = currentTime.getSeconds();

                                                                // Format the time as HH:MM:SS
                                                                var formattedCurrentTime = currentHours + ':' + currentMinutes + ':' + currentSeconds;
                                                                console.log(waktu);
                                                                console.log(id_schedule);
                                                                $('#id_schedule').val(id_schedule);
                                                                
                                                                $.ajax({
                                                                    url: 'get_lokasi.php',
                                                                    type: 'GET',
                                                                    data: { bpid: bpid },
                                                                    dataType: 'json',
                                                                    success: function (data) {
                                                                        console.log(data.lat, data.longi);
                                                                        var lat2 = parseFloat(data.lat);
                                                                        var lon2 = parseFloat(data.longi);



                                                                        if (navigator.geolocation) {
                                                                            navigator.geolocation.getCurrentPosition(
                                                                                function (position) {
                                                                                    var lat1 = position.coords.latitude;
                                                                                    var lon1 = position.coords.longitude;
                                                                                    console.log(lat1, lon1);

                                                                                    const distance = calculateHaversineDistance(lat1, lon1, lat2, lon2);
                                                                                    validateTimeAndProceed(distance, id_schedule, waktu, formattedCurrentTime);
                                                                                },
                                                                                function (error) {
                                                                                    console.error('Error getting user location:', error);
                                                                                    // Handle errors here, e.g., show a default location or prompt the user
                                                                                }

                                                                            );
                                                                        } else {
                                                                            console.error('Geolocation is not supported by this browser.');
                                                                            // Handle browsers that do not support geolocation
                                                                        }
                                                                    },
                                                                    error: function () {
                                                                        // Handle AJAX errors
                                                                        console.log('Error fetching location data');
                                                                    }
                                                                });
                                                            });


                                                        });


                                                    </script>

                                                    <div class="row mb-3">
                                                        <button type="button" id="verifikasiBtnupcoming<?php echo $index ?>"
                                                            class="btn btn-success action-verifikasi" data-bs-toggle="modal"
                                                            data-bs-target="#verifikasiModal"
                                                            data-bpid="<?php echo $rowUpcomingEvent['bpid']; ?>"
                                                            data-id_schedule="<?php echo $rowUpcomingEvent['id_schedule']; ?>"
                                                            data-id_verifikasi="<?php echo $rowUpcomingEvent['id_verifikasi']; ?>">Verifikasi
                                                            Kedatangan</button>
                                                    </div>
                                                    <script>
                                                        document.getElementById('verifikasiBtnupcoming<?php echo $index ?>').addEventListener('click', function () {

                                                            var bpid = this.getAttribute('data-bpid');
                                                            var id_schedule = this.getAttribute('data-id_schedule');
                                                            var id_verifikasi = this.getAttribute('data-id_verifikasi');
                                                            $('#id_scheduleKedatangan').val(id_schedule);
                                                            $('#id_verifikasi').val(id_verifikasi);

                                                            console.log(id_schedule)
                                                            $.ajax({
                                                                url: 'get_lokasi.php',
                                                                type: 'GET',
                                                                data: { bpid: bpid },
                                                                dataType: 'json',
                                                                success: function (data) {
                                                                    console.log(data.lat, data.longi);
                                                                    var lat2 = parseFloat(data.lat);
                                                                    var lon2 = parseFloat(data.longi);

                                                                    if (navigator.geolocation) {
                                                                        navigator.geolocation.getCurrentPosition(function (position) {
                                                                            // var lat1 = position.coords.latitude;
                                                                            // var lon1 = position.coords.longitude;
                                                                            var lat1 = position.coords.latitude;
                                                                            var lon1 = position.coords.longitude;
                                                                            const distance = calculateHaversineDistance(lat1, lon1, lat2, lon2);

                                                                            if (distance <= 0.005) {
                                                                                $('#verifikasiModal').modal('show');
                                                                                console.log("aplikasinya jalan yaa");
                                                                            } else if (distance >= 0.005) {
                                                                                swal({
                                                                                    icon: 'warning',
                                                                                    title: 'Oops...',
                                                                                    text: 'Anda Belum Sampai Tujuan Customer',
                                                                                }).then(function () {
                                                                                    // Hide the modal here
                                                                                    $('#verifikasiModal').modal('hide');
                                                                                    window.location = "dashboardVerify.php"
                                                                                });
                                                                            }

                                                                        }, function (error) {
                                                                            console.error('Error getting user location:', error);
                                                                            // Handle errors here, e.g., show a default location or prompt the user
                                                                        });
                                                                    } else {
                                                                        console.error('Geolocation is not supported by this browser.');
                                                                        // Handle browsers that do not support geolocation
                                                                    }
                                                                },
                                                                error: function () {
                                                                    // Handle AJAX errors
                                                                    console.log('Error fetching location data');
                                                                }
                                                            });
                                                        });
                                                        function toRadians(degrees) {
                                                            return degrees * (Math.PI / 180);
                                                        }
                                                        function calculateHaversineDistance(lat1, lon1, lat2, lon2) {
                                                            const R = 6371; // Radius of the Earth in kilometers
                                                            const dLat = toRadians(lat2 - lat1);
                                                            const dLon = toRadians(lon2 - lon1);

                                                            const a =
                                                                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                                                                Math.cos(toRadians(lat1)) * Math.cos(toRadians(lat2)) *
                                                                Math.sin(dLon / 2) * Math.sin(dLon / 2);

                                                            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

                                                            return R * c;
                                                        }


                                                    </script>
                                                </td>
                                                <td>
                                                    <?php echo $rowUpcomingEvent['bpid'] ?>
                                                </td>

                                                <td>
                                                    <?php echo $rowUpcomingEvent['cycle']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $rowUpcomingEvent['dock_customer']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $rowUpcomingEvent['plan_arrival_time']; ?>
                                                </td>
                                                <td style="display:none;">
                                                    <?php echo $rowUpcomingEvent['qty_palet']; ?>
                                                </td>
                                                <td style="display:none;">
                                                    <?php echo $rowUpcomingEvent['id_schedule']; ?>
                                                </td>


                                            </tr>


                                        </tbody>
                                        <?php
                                        $counterUpcomingEvents++;
                                        $index++;
                                        }


                                        ?>
                                </table>
                                <!-- End Table with stripped rows -->
                            </div>


                        </div>
                    </div>

                </div>
            </div>
            <div class="modal fade" id="kirimfotoModal" tabindex="-1" role="dialog"
                aria-labelledby="kirimfotoModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <form class="form-horizontal" enctype="multipart/form-data" method="post">
                            <div class="modal-header">
                                <h5 class="modal-title" id="kirimfotoModalLabel">Problem Driver</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="stopWebcam()"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <h4 class="card-title">Kirim Foto</h4>

                                <div class="row mb-3">
                                    <video id="webcam" autoplay playsinline></video>
                                </div>
                                <div class="row mb-3">

                                    <canvas id="imageCanvas"></canvas>

                                </div>
                                <div class="row mb-3">
                                    <label for="problem" class="col-sm-2 col-form-label">Problem</label>
                                    <div class="col-sm-10">
                                        <textarea type="textarea" id="problem" name="problem" class="form-control"
                                            required></textarea>
                                    </div>
                                </div>
                                <input type="hidden" id="latitude" name="latitude">
                                <input type="hidden" id="longitude" name="longitude">
                                <input type="hidden" id="id_schedule" name="id_schedule">
                                <input type="hidden" id="foto" name="foto">



                            </div>
                            <div class="modal-footer">

                                <button type="button" class="btn btn-primary" onclick="captureImage()">Ambil
                                    Gambar</button>
                                <button type="simpan" id="simpanBtn" name="simpan"
                                    class="btn btn-primary">Kirim</button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="verifikasiModal" tabindex="-1" role="dialog"
                aria-labelledby="verifikasiModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="verifikasiModalLabel">Detail</h5>
                            <button type="button" class="btn-close" onclick="stopWebcamverifikasi()"
                                data-bs-dismiss="modal" aria-label="Close"></button>

                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post">

                                <!-- DataTales Example -->
                                <div class="card shadow mb-4">


                                    <div class="card-body">

                                        <div class="table-responsive">
                                            <form class="form-horizontal" method="post">
                                                <div class="card-body">
                                                    <h4 class="card-title">Detail Verifikasi Kedatangan</h4>
                                                    <div class="row mb-3">
                                                        <video id="webcamVerify" autoplay playsinline></video>
                                                    </div>
                                                    <div class="row mb-3">

                                                        <canvas id="imageCanvasVerify"></canvas>

                                                    </div>
                                                    <input type="hidden" id="id_verifikasi"
                                                        name="id_verifikasi">
                                                    <input type="hidden" id="latitudeKedatangan"
                                                        name="latitudeKedatangan">
                                                    <input type="hidden" id="longitudeKedatangan"
                                                        name="longitudeKedatangan">
                                                    <input type="hidden" id="id_scheduleKedatangan"
                                                        name="id_scheduleKedatangan">
                                                    <input type="hidden" id="fotoBinary" name="fotoBinary">

                                                </div>

                                                <div class="border-top">
                                                    <div class="card-body">
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-primary"
                                                                id="captureImageBtn">Capture Image</button>
                                                            <button type="submit" name="submit" class="btn btn-primary">
                                                                Submit
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>



    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>PT Kayaba Indonesia</span></strong>. All Rights Reserved
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/chart.js/chart.umd.js"></script>
    <script src="../assets/vendor/echarts/echarts.min.js"></script>
    <script src="../assets/vendor/quill/quill.min.js"></script>
    <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="../assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="../assets/js/main.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- ... (Other includes) -->
    <script src="https://openrouteservice.org/assets/js/ors-js-client.js"></script>
    <script src="https://openrouteservice.org/assets/js/openrouteservice-js-sdk.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

    <script>
        function startCamera() {
            // Get the video element and canvas element
            var video = document.getElementById('webcamVerify');
            var canvas = document.getElementById('imageCanvasVerify');
            var context = canvas.getContext('2d');

            // Check if the browser supports getUserMedia
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                // Start the camera
                navigator.mediaDevices.getUserMedia({ video: true })
                    .then(function (stream) {
                        video.srcObject = stream;
                    })
                    .catch(function (error) {
                        console.error('Error accessing the camera: ', error);
                    });

                // Add event listener to the "Capture Image" button
                document.getElementById('captureImageBtn').addEventListener('click', function () {
                    // Draw the current frame from the video to the canvas
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                    document.getElementById('fotoBinary').value = canvas.toDataURL('image/png'); // Get base64-encoded PNG data

                    // Set the canvas data in a hidden input field


                    // Stop the video stream (optional, depends on your requirements)
                    stream.getTracks().forEach(track => track.stop());
                });
            } else {
                console.error('getUserMedia is not supported in this browser.');
            }
        }







        $('.action-verifikasi').on('click', function () {

            $('#verifikasiModal').modal('show');
            startCamera();
        });

        // Add an event listener to the "Verifikasi Kedatangan" button

        $('#verifikasiModal').on('shown.bs.modal', function (e) {

            var modal = $(this);

            var idSchedule = $(this).data('id_schedule');
            var bpid = $(this).data('bpid');
            $.ajax({
                url: 'get_lokasi.php',
                type: 'GET',
                data: { bpid: bpid },
                dataType: 'json',
                success: function (data) {
                    // Assuming your JSON response has 'lat' and 'long' properties
                    var lat2 = parseFloat(data.lat);
                    var lon2 = parseFloat(data.longi);
                    validateDistance(lat2, lon2);
                },
                error: function () {
                    // Handle AJAX errors
                    console.log('Error fetching location data');
                }
            });

            $('#verifikasiModal').modal('show');
            startCamera();






            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;
                    console.log(latitude);
                    console.log(longitude);

                    // Set latitude and longitude in the modal
                    modal.find('#latitudeKedatangan').val(latitude);
                    modal.find('#longitudeKedatangan').val(longitude);
                }, function (error) {
                    console.error('Error getting user location:', error);
                    // Handle errors here, e.g., show a default location or prompt the user
                });
            } else {
                console.error('Geolocation is not supported by this browser.');
                // Handle browsers that do not support geolocation
            }
        });
    </script>

    <script>




        function haversineDistance(lat1, lon1, lat2, lon2) {
            // Convert degrees to radians
            function toRad(degrees) {
                return degrees * (Math.PI / 180);
            }

            // Haversine formula
            var dLat = toRad(lat2 - lat1);
            var dLon = toRad(lon2 - lon1);
            var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            var radius = 6371; // Earth radius in kilometers
            var distance = radius * c;


            return distance;
        }
        // setInterval(updateCustomerLocation, 1000);



        let webcamStream;
        const video = document.getElementById('webcam');
        const canvas = document.getElementById('imageCanvas');
        const context = canvas.getContext('2d');
        const imagePreview = document.getElementById('imagePreview');

        let webcamStreamVerify;
        const videoVerifikasi = document.getElementById('webcamVerify');
        const canvasVerifikasi = document.getElementById('imageCanvasVerify');
        const contextVerifikasi = canvas.getContext('2d');
        const imagePreviewVerify = document.getElementById('imagePreviewVerify');





        async function startWebcam() {
            try {
                webcamStream = await navigator.mediaDevices.getUserMedia({ video: true });
                video.srcObject = webcamStream;
            } catch (error) {
                console.error('Error accessing webcam:', error);
            }
        }

        function captureImage() {

            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            document.getElementById('foto').value = canvas.toDataURL('image/png');
            imagePreview.src = canvas.toDataURL('image/png');

            stopWebcam();
        }


        function stopWebcam() {
            if (webcamStream) {
                const tracks = webcamStream.getTracks();
                tracks.forEach(track => track.stop());
                video.srcObject = null;

                // Clear the canvas
                context.clearRect(0, 0, canvas.width, canvas.height);

                // Clear the image preview
                imagePreview.src = '';
            }
        }
        $('#kirimfotoModal').on('shown.bs.modal', function (e) {
            var modal = $(this);


            // Start the webcam
            startWebcam();




            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;

                    // Set latitude and longitude in the modal
                    modal.find('#latitude').val(latitude);
                    modal.find('#longitude').val(longitude);
                }, function (error) {
                    console.error('Error getting user location:', error);
                    // Handle errors here, e.g., show a default location or prompt the user
                });
            } else {
                console.error('Geolocation is not supported by this browser.');
                // Handle browsers that do not support geolocation
            }
        });

        $('#kirimfotoModal').on('hidden.bs.modal', function (e) {
            stopWebcam();
        });
        async function startWebcamverifikasi() {
            try {
                webcamStreamVerify = await navigator.mediaDevices.getUserMedia({ videoVerifikasi: true });
                videoVerifikasi.srcObject = webcamStreamVerify;
            } catch (error) {
                console.error('Error accessing webcam:', error);
            }
        }
        function captureImageVerifikasi() {
            context.drawImage(video, 0, 0, canvasVerifikasi.width, canvasVerifikasi.height);
            contextVerifikasi.src = canvasVerifikasi.toDataURL('image/png');
            stopWebcam();
        }
        function stopWebcamverifikasi() {
            if (webcamStreamVerify) {
                const tracks = webcamStreamVerify.getTracks();
                tracks.forEach(track => track.stop());
                videoVerifikasi.srcObject = null;

                // Clear the canvas
                contextVerifikasi.clearRect(0, 0, canvasVerifikasi.width, canvasVerifikasi.height);

                // Clear the image preview
                imagePreviewVerify.src = '';
            }
        }

    </script>
</body>

</html>