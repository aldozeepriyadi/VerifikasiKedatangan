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
$sqlProblem = "SELECT id_schedule,lat,longi,actual_arrival,bukti_foto,status,id_driver FROM verifikasi_delivery WHERE id_driver='" . $_SESSION['id_driver'] . "' AND status = 1";
$resultProblem = mysqli_query($conn, $sqlProblem);
$counterProblem = 1;



// For Upcoming Events
// $sqlVerifikasi = "SELECT bpid, cycle, dock_customer, qty_palet, TIME(plan_arrival) as plan_arrival_time,id_schedule
//                      FROM schedule_delivery
//                      WHERE plan_arrival > DATE_ADD('$currentDate', INTERVAL 3 DAY)";
// $resultUpcomingEvents = mysqli_query($conn, $sqlUpcomingEvents);
// $counterUpcomingEvents = 1;







?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Historical Verifikasi</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="../assets/img/favicon.png" rel="icon">
    <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <script></script>
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.1/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.0/css/responsive.bootstrap5.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Template Main CSS File -->
    <link href="../assets/css/style.css" rel="stylesheet">
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <!-- Include DataTables CSS and JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.1/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.0/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.0/js/responsive.bootstrap5.js"></script>
    <!-- Your custom styles or other scripts -->

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
                <a class="nav-link  " data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-layout-text-window-reverse"></i><span>Dashboard</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="tables-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">

                    <li>
                        <a href="../Dashboard/dashboardVerify.php">
                            <i class="bi bi-circle"></i><span>Verifikasi Kedatangan</span>
                        </a>

                    </li>
                    <li>
                        <a href="historical.php">
                            <i class="bi bi-circle"></i><span>Historical Problem</span>
                        </a>

                    </li>
                    <li>
                        <a href="historicalKedatangan.php" class="active">
                            <i class="bi bi-circle"></i><span>Historical Verifikasi Kedatangan</span>
                        </a>

                    </li>
                </ul>
            </li><!-- End Tables Nav -->



        </ul>

    </aside><!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Historical Verifikasi Kedatangan</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item active">Historical Verifikasi Kedatangan</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Historical Verifikasi Kedatangan</h5>

                            <div class="table-responsive">
                                <!-- Table with stripped rows -->
                                <table class="table table-striped nowrap datatable" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Driver</th>
                                            <th>Jarak</th>
                                            <th>Actual Kedatangan</th>
                                            <th>Bukti Foto</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($rowProblem = mysqli_fetch_assoc($resultProblem)) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo $counterProblem ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $sql2 = "SELECT nama
                                                         FROM driver where id_driver = '" . $rowProblem["id_driver"] . "'";
                                                    $result2 = mysqli_query($conn, $sql2);
                                                    while ($rowDriver = mysqli_fetch_assoc($result2)) {
                                                        echo $rowDriver["nama"];
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <iframe width="300" height="200" frameborder="0" scrolling="no"
                                                        marginheight="0" marginwidth="0"
                                                        src="https://www.openstreetmap.org/export/embed.html?bbox=<?php echo $rowProblem['longi']; ?> ,<?php echo $rowProblem['lat']; ?>,<?php echo $rowProblem['longi']; ?>,<?php echo $rowProblem['lat']; ?>&amp;layer=mapnik&amp;marker=<?php echo $rowProblem['lat']; ?>,<?php echo $rowProblem['longi']; ?>"
                                                        allowfullscreen></iframe>
                                                </td>
                                                <td>
                                                    <?php echo $rowProblem['actual_arrival']; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $imageData = $rowProblem['bukti_foto'];

                                                    if ($imageData) {
                                                        echo '<img src="' . $imageData . '"/>';
                                                    } else {
                                                        echo 'No Image';
                                                    }
                                                    ?>

                                                </td>
                                                <td>
                                                    <?php
                                                    if ($rowProblem['status'] == 0) {
                                                        $style = 'btn btn-warning   ';
                                                        $text = 'On Delivery';
                                                    } else if ($rowProblem['status'] == 1) {
                                                        $style = 'btn btn-success';
                                                        $text = 'Sudah sampai';
                                                    }

                                                    ?>
                                                    <button type="button" class="<?php echo $style ?>">
                                                        <?php echo $text ?>
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php
                                            $counterProblem++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <!-- End Table with stripped rows -->
                            </div>
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

    <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="../assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="../assets/js/main.js"></script>
    <!-- DataTables CSS -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- ... (Other includes) -->
    <script src="https://openrouteservice.org/assets/js/ors-js-client.js"></script>
    <script src="https://openrouteservice.org/assets/js/openrouteservice-js-sdk.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    <script>
        $(document).ready(function () {
            $('.datatable').DataTable();
        });
    </script>


</body>

</html>