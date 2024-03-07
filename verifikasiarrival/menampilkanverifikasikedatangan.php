<?php
include("../lib/phpqrcode/qrlib.php");
include("../config.php");
session_start();

// Check if the user is not authenticated
if (!isset($_SESSION['username'])) {
    header("Location: ../Login/login.php");
    exit();
}


if (isset($_POST["submit"])) {
    $driverName = $_POST["id_driver"];
    $longitude = $_POST["act_long"];
    $latitude = $_POST["act_lat"];
    $lokasi = $_POST["id_lokasi"];
    $scheudule = $_POST["id_scheudule"];

    $plan = $_POST["plan_arr"];
    $customer = $_POST["customer"];
    $cycle = $_POST["cycle"];
    $dock_customer = $_POST["dock_customer"];
    $qty_paket = $_POST["qty_paket"];
    $qty = $_POST["qty"];


    $sql = "INSERT INTO transaksi_delivery(id_driver,act_long,act_lat,id_lokasi) 
            VALUES ('$driverName',  '$longitude', '$latitude', '$lokasi')";

    $sql1 = "INSERT INTO scheudule_delivery(plan_arr,customer,cycle,dock_customer, qty_paket,qty) 
    VALUES ('$plan', '$customer', '$cycle', '$dock_customer', '$qty_paket','$qty')";

    $result = mysqli_query($conn, $sql);
    $result1 = mysqli_query($conn, $sql1);

    if ($result && $result1) {
        header("Location:menampilkanverifikasikedatangan.php");
    } else {
        echo "Failed: " . mysqli_error($conn);
    }
}


// Logout functionality
// if (isset($_POST['logout'])) {
//     session_destroy();
//     header("Location: ../Login/login.php");
//     exit();
// }
?>





<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Proses Delivery</title>

    <!-- Custom fonts for this template -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Custom styles for this template -->
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center"
                href="../dashboard/dashboardVerify.php">
                <div class="sidebar-brand-icon">

                    <img src="../assets/img/KYB.png" alt="Logo KYB" class="img-fluid p-5  " style=" display: flex;" />
                </div>

            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="dashboardVerify.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Verifikasi Kedatangan di Customer
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Verifikasi Kedatangan Driver</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="../verifikasiarrival/menampilkanverifikasikedatangan.php">Proses
                            Verifikasi</a>
                    </div>
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="../verifikasiarrival/prosesverifikasi.php">Scan QR Kedatangan</a>
                    </div>
                </div>

            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Delivery</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">

                        <a class="collapse-item" href="../Dashboard/dashboardPemonitor.php">Submit Delivery</a>

                    </div>
                </div>
            </li>



        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">


                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>
                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>

                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alerts Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 12, 2019</div>
                                        <span class="font-weight-bold">A new monthly report is ready to download!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-donate text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 7, 2019</div>
                                        $290.29 has been deposited into your account!
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 2, 2019</div>
                                        Spending Alert: We've noticed unusually high spending for your account.
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li>

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter">7</span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Message Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_1.svg" alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div class="font-weight-bold">
                                        <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                            problem I've been having.</div>
                                        <div class="small text-gray-500">Emily Fowler · 58m</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_2.svg" alt="...">
                                        <div class="status-indicator"></div>
                                    </div>

                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_3.svg" alt="...">
                                        <div class="status-indicator bg-warning"></div>
                                    </div>
                                    <div>

                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                                            told me that people say this to all dogs, even if they aren't good...</div>
                                        <div class="small text-gray-500">Chicken the Dog · 2w</div>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown no-arrow mx-1">
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </li>
                    </ul>

                </nav>
                <!-- End of Topbar -->

                <div class="container-fluid">
                    <?php
                    if (isset($_GET["msg"])) {
                        $msg = $_GET["msg"];
                        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
      ' . $msg . '
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
                    }
                    ?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Proses Verifikasi</h1>
                    <div class="card-body">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#submitModal">
                            Verifikasi Delivery
                        </button>
                    </div>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">




                        <div class="card-body">

                            <div class="table-responsive">


                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Driver</th>
                                            <th>Plan/Actual</th>
                                            <th>Latitide</th>
                                            <th>Longitude</th>
                                            <th>Action</th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $sql = "SELECT td.plan_arr, td.act_arr, td.id_lokasi, td.id_driver, td.act_long, td.act_lat
                                            FROM scheudule_delivery sd
                                            JOIN transaksi_delivery td ON sd.id_scheudule = td.id_scheudule";
                                        $result = mysqli_query($conn, $sql);
                                        $counter = 1;
                                        while ($row = mysqli_fetch_assoc($result)) {

                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo $counter ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $sql2 = "SELECT nama_driver
                                                         FROM driver where id_driver = '" . $row["id_driver"] . "'";
                                                    $result2 = mysqli_query($conn, $sql2);
                                                    while ($rowDriver = mysqli_fetch_assoc($result2)) {
                                                        echo $rowDriver["nama_driver"];
                                                        ?>
                                                    </td>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['plan_arr'] . '/' . $row['act_arr'] ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $sql1 = "SELECT lat,longi
                                                         FROM master_lokasi where id_lokasi = '" . $row["id_lokasi"] . "'";
                                                        $result1 = mysqli_query($conn, $sql1);
                                                        while ($rowLokasi = mysqli_fetch_assoc($result1)) {
                                                            echo $rowLokasi["lat"];
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $rowLokasi["longi"];
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-primary action-button"
                                                                data-toggle="modal" data-target="#detailModal"
                                                                data-driver="<?php echo $rowDriver["nama_driver"]; ?>"
                                                                data-plan-actual="<?php echo $row['plan_arr'] . '/' . $row['act_arr']; ?>"
                                                                data-latitude="<?php echo $rowLokasi["lat"]; ?>"
                                                                data-longitude="<?php echo $rowLokasi["longi"]; ?>">
                                                                Detail
                                                            </button>
                                                        </td>


                                                    </tr>


                                                </tbody>
                                                <?php
                                                $counter++;
                                                        }
                                                    }
                                        } ?>
                                </table>

                            </div>
                        </div>
                    </div>

                </div>

                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; PT Kayaba Indonesia 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- Modal for Details -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Display details using input fields -->
                    <label for="driverName">Driver:</label>
                    <input type="text" id="driverName" class="form-control" readonly>

                    <label for="planActual">Plan/Actual:</label>
                    <input type="text" id="planActual" class="form-control" readonly>

                    <label for="latitude">Latitude:</label>
                    <input type="text" id="latitude" class="form-control" readonly>

                    <label for="longitude">Longitude:</label>
                    <input type="text" id="longitude" class="form-control" readonly>
                    <!-- Add more input fields as needed -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="submitModal" tabindex="-1" role="dialog" aria-labelledby="submitModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Verifikasi delivery</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post">
                    <div class="modal-body">

                        <!-- Display details using input fields -->
                        <label for="id_driver">Driver:</label>
                        <select id="id_driver" name="id_driver" class="form-control">
                            <option value="" selected></option>
                            <?php
                            $queryDriver = "SELECT id_driver, nama_driver FROM driver";
                            $resultDriver = $conn->query($queryDriver);
                            // Loop untuk menampilkan opsi lokasi
                            while ($rowDriver = $resultDriver->fetch_assoc()) {
                                echo "<option value='" . $rowDriver['id_driver'] . "'>" . $rowDriver['nama_driver'] . "</option>";
                            }
                            ?>
                        </select>
                        <label for="plan_arr">Plan</label>
                        <input type="text" id="plan_arr" name="plan_arr" class="form-control">

                        <label for="customer">Customer</label>
                        <input type="text" id="customer" name="customer" class="form-control">

                        <label for="cycle">Cycle</label>
                        <input type="text" id="cycle" name="cycle" class="form-control">

                        <label for="dock_customer">Dock Customer</label>
                        <input type="text" id="dock_customer" name="dock_customer" class="form-control">

                        <label for="qty_paket ">Quantity paket</label>
                        <input type="text" id="qty_paket" name="qty_paket" class="form-control">

                        <label for="qty ">Quantity</label>
                        <input type="text" id="qty" name="qty" class="form-control">

                        <label for="id_lokasi">Lokasi</label>
                        <select id="id_lokasi" name="id_lokasi" class="form-control">
                            <option value="" selected></option>
                            <?php
                            $queryLokasi = "SELECT id_lokasi, lat,longi FROM master_lokasi";
                            $resultLokasi = $conn->query($queryLokasi);
                            // Loop untuk menampilkan opsi lokasi
                            while ($rowLokasi = $resultLokasi->fetch_assoc()) {

                                echo "<option value='" . $rowLokasi['id_lokasi'] . "'>" . $rowLokasi['lat'] . "-" . $rowLokasi['longi'] . "</option>";
                            } ?>
                        </select>
                        <div class="card-body">

                            <div class="table-responsive">
                                <div id="map" class="container" style="width:30rem;height: 30rem;"></div>

                            </div>
                        </div>
                        <label for="act_lat">Latitude:</label>
                        <input type="text" id="act_lat" name="act_lat" class="form-control">

                        <label for="act_long">Longitude:</label>
                        <input type="text" id="act_long" name="act_long" class="form-control">

                        <!-- Add more input fields as needed -->

                    </div>

                    <div class="modal-footer">
                        <button type="submit" name="submit" class="btn btn-primary">Proses verifikasi</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <!-- Add a form for logout -->

                    <!-- <button class="btn btn-primary" type="button" name="logout">Logout</button> -->

                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://openrouteservice.org/assets/js/ors-js-client.js"></script>
    <script src="https://openrouteservice.org/assets/js/openrouteservice-js-sdk.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../assets/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../assets/js/demo/datatables-demo.js"></script>
    <!-- Link Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    <!-- Include jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Include jQuery and DataTables script if not already included -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

    <!-- Your existing script may look like this -->



    <script>
        var random = L.latLng(-6.311192920012522, 107.0998013767025);
        var verificationDistance = 0.5;
        var mymap = L.map('map').setView([-6.311192920012522, 107.0998013767025], 2);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(mymap);

        var waypoints = [];

        mymap.on('click', function (e) {
            var latlng = e.latlng;
            waypoints.push(latlng);

            // Display waypoints on the map
            L.marker(latlng).addTo(mymap);

            // Update latitude and longitude fields in the form with the last waypoint
            var lastWaypoint = waypoints[waypoints.length - 1];
            document.getElementById('act_lat').value = lastWaypoint.lat;
            document.getElementById('act_long').value = lastWaypoint.lng;
        });
        $(document).ready(function () {
            // Initialize DataTable
            $('#dataTable').DataTable();

            // Capture click event on "Action" button
            $('.action-button').on('click', function () {
                // Extract data attributes
                var driver = $(this).data('driver');
                var planActual = $(this).data('plan-actual');
                var latitude = $(this).data('latitude');
                var longitude = $(this).data('longitude');

                // Populate modal fields
                $('#driverName').val(driver);
                $('#planActual').val(planActual);
                $('#latitude').val(latitude);
                $('#longitude').val(longitude);
            });
        });

    </script>


</body>

</html>