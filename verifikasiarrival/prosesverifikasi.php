<?php
include("../lib/phpqrcode/qrlib.php");
include("../config.php");
session_start();

// Check if the user is not authenticated
if (!isset($_SESSION['username'])) {
    header("Location: ../Login/login.php");
    exit();
}

include "../config.php";
// Logout functionality
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../Login/login.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the QR data from the submitted form
    $qrData = $_POST["qrData"];

    // You can process the QR data as needed
    // For example, redirect to another page with the QR data as a query parameter
    header("Location: verifikasiarrival.php?qrData=" . urlencode($qrData));
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Scan QR Driver</title>

    <!-- Custom fonts for this template -->
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

    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

    <!-- Include OpenRouteService JS SDK -->
    <script src="https://openrouteservice.org/assets/js/openrouteservice-js-sdk.js"></script>


    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
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
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
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
                        <a class="collapse-item" href="menampilkanverifikasikedatangan.php">Submit Delivery</a>
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
                    <h1 class="h3 mb-2 text-gray-800">Scan QR</h1>



                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">

                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Customer</th>
                                            <th>Cycle</th>
                                            <th>Dock Customer</th>
                                            <th>Paket</th>
                                            <th>Quantity</th>
                                            <th>QR Code</th>
                                            <th>Action</th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $sql = "SELECT sd.plan_arr, td.act_lat, td.act_long, sd.customer, sd.cycle, sd.dock_customer, sd.qty_paket, sd.qty, sd.id_scheudule, td.id_driver, td.id_lokasi
                                        FROM scheudule_delivery sd
                                        JOIN transaksi_delivery td ON td.id_scheudule = sd.id_scheudule";
                                        $result = mysqli_query($conn, $sql);
                                        $counter = 1;
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $qrData = "{$row['customer']} || {$row['cycle']} || {$row['dock_customer']} || {$row['qty_paket']} || {$row['qty']}";
                                            $qrFilename = "../gbrqrcode/{$row['customer']}.png"; // Save QR codes with unique filenames
                                            QRcode::png($qrData, $qrFilename);

                                            // Add QR code filename to the row data
                                            $row['qrcode'] = $qrFilename;
                                            ?>
                                            <tr data-qrcode="<?php echo $row['qrcode']; ?>"
                                                data-qrdata="<?php echo $qrData; ?>" data-scanned="false">
                                                <td>
                                                    <?php echo $counter ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['customer'] ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['cycle']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['dock_customer']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['qty_paket']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['qty']; ?>
                                                </td>
                                                <td style="display: none;">
                                                    <?php echo $row['id_scheudule']; ?>
                                                </td>
                                                <td style="display: none;">
                                                    <?php echo $row['id_driver']; ?>
                                                </td>

                                                <td style="display: none;">
                                                    <?php echo $row['id_lokasi']; ?>
                                                </td>
                                                <td style="display: none;">
                                                    <?php echo $row['plan_arr']; ?>
                                                </td>
                                                <td>
                                                    <img src="<?php echo $row['qrcode']; ?>" alt="">
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                            data-bs-target="#qrcodeModal">
                                                            Scan
                                                        </button>
                                                    </div>
                                                    <div class="form-group">
                                                        <button type="button" class="btn btn-secondary submit-button"
                                                            data-bs-toggle="modal" data-bs-target="#submitModal"
                                                            data-plan_arr="<?php echo $row["plan_arr"]; ?>"
                                                            data-lokasi="<?php echo $row["id_lokasi"]; ?>"
                                                            data-driver="<?php echo $row["id_driver"]; ?>"
                                                            data-qrcode="<?php echo $row['qrcode']; ?>">
                                                            Detail
                                                        </button>
                                                    </div>
                                                </td>



                                            </tr>
                                            <?php
                                            $counter++;
                                        } ?>

                                    </tbody>
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
    <!-- Add this modal code at the end of your HTML body -->
    <div class="modal fade" id="qrcodeModal" tabindex="-1" role="dialog" aria-labelledby="qrcodeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrcodeModalLabel">QR Code</h5>
                    <button type="button" id="closeQrCodeModalBtn" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalQrCode" class="mx-auto d-block" src="" alt="QR Code">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="closeQrCodeModalBtn">Close</button>
                    <button type="button" class="btn btn-primary" id="fakeScanButton">Fake Scan QR Code</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="submitModal" tabindex="-1" role="dialog" aria-labelledby="submitModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="submitModalLabel">Detail</h5>
                    <button type="button" id="closeSubmitModalBtn" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post">


                        <!-- DataTales Example -->
                        <div class="card shadow mb-4">


                            <div class="card-body">

                                <div class="table-responsive">
                                    <div id="map" class="container" style="width:30rem;height: 30rem;"></div>
                                    <div id="coordinates"></div>
                                    <div id="directions"></div>
                                    <form class="form-horizontal">
                                        <div class="card-body">
                                            <h4 class="card-title">Detail Verifikasi Kedatangan</h4>
                                            <div class="form-group row">
                                                <label for="plan_arr"
                                                    class="col-sm-3 text-end control-label col-form-label">
                                                    Plan</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="plan_arr"
                                                        name="plan_arr" />
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="act_arr"
                                                    class="col-sm-3 text-end control-label col-form-label">
                                                    Actual</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="act_arr"
                                                        nama="act_arr" />
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="longitude"
                                                    class="col-sm-3 text-end control-label col-form-label">Longitude</label>
                                                <div class="col-sm-9">

                                                    <input type="text" class="form-control" id="longitude"
                                                        name="longitude" />
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="latitude"
                                                    class="col-sm-3 text-end control-label col-form-label">Latitude</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="latitude"
                                                        name="latitude" readonly />
                                                    <input type="hidden" id="id_lokasi" name="id_lokasi" />
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="user_driver"
                                                    class="col-sm-3 text-end control-label col-form-label">
                                                    Driver</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="nama_driver" name="nama_driver" readonly />
                                                    <input type="hidden" id="id_driver" name="id_driver" />
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="waktu_kedatangan"
                                                    class="col-sm-3 text-end control-label col-form-label">Waktu
                                                    Kedatangan</label>
                                                <div class="col-sm-9">
                                                    <input type="date" class="form-control" id="waktu_kedatangan"
                                                        name="waktu_kedatangan"  />
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" class="form-control" id="qrdata" />
                                        <div class="border-top">
                                            <div class="card-body">
                                                <button type="submit" name="submit" class="btn btn-primary">
                                                    Submit
                                                </button>
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
                    <form method="post">
                        <button class="btn btn-primary" type="submit" name="logout">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../assets/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->

    <!-- Page level custom scripts -->
    <script src="../assets/js/demo/datatables-demo.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- ... (Other includes) -->
    <script src="https://openrouteservice.org/assets/js/ors-js-client.js"></script>
    <script src="https://openrouteservice.org/assets/js/openrouteservice-js-sdk.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    <!-- Link Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script><!-- Load jQuery from a CDN -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Load DataTables from a CDN -->
    <script src="https://cdn.datatables.net/1.11.10/js/jquery.dataTables.min.js"></script>




    <script>
        var ptKayabaLocation = L.latLng(-6.311192920012522, 107.0998013767025);
        var verificationDistance = 0.5;

        var mymap = L.map('map').setView([-6.311192920012522, 107.0998013767025], 2);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(mymap);



        var driverMarker = L.marker([0, 0]).addTo(mymap);

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

        // var line = L.polyline([
        //     [-6.311192920012522, 107.0998013767025],
        //     [0, 0]
        // ], {
        //     color: 'red',
        //     weight: 5
        // }).addTo(mymap);
        var radarCircle = L.circle(ptKayabaLocation, {
            color: 'blue',
            fillColor: 'blue',
            fillOpacity: 0.2,
            radius: 0.3 * 1000,
        }).addTo(mymap);

        var control = L.Routing.control({
            waypoints: [
                L.latLng(ptKayabaLocation.lat, ptKayabaLocation.lng),
                L.latLng(ptKayabaLocation.lat, ptKayabaLocation.lng) // Initial waypoint for driver's location
            ],
            routeWhileDragging: true
        }).addTo(mymap);



        function updateDriverLocation() {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    $.ajax({
                        type: 'GET',
                        url: 'get_driver_location.php',
                        success: function (response) {
                            try {
                                var driverCoordinates = JSON.parse(response);

                                // Check if the response contains valid latitude and longitude
                                if (driverCoordinates &&
                                    driverCoordinates.latitude_user !== undefined &&
                                    driverCoordinates.longitude_user !== undefined) {
                                    // Extract latitude and longitude from the response
                                    var driverLat = parseFloat(driverCoordinates.latitude_user);
                                    var driverLng = parseFloat(driverCoordinates.longitude_user);

                                    // Create a LatLng object using the retrieved coordinates
                                    var driverLatLng = L.latLng(driverLat, driverLng);

                                    // Update directions, set driver marker, and update the line
                                    updateDirections(driverLatLng.lat, driverLatLng.lng);
                                    driverMarker.setLatLng(driverLatLng);
                                    //line.setLatLngs([ptKayabaLocation, driverLatLng]);

                                    // Calculate distance between driver and PT Kayaba
                                    var distance = haversineDistance(driverLatLng.lat, driverLatLng.lng, ptKayabaLocation.lat, ptKayabaLocation.lng);

                                    radarCircle.setStyle({
                                        opacity: 1,
                                        fillOpacity: 0.2
                                    });



                                    // Calculate bounds for the PT Kayaba location and driver's location
                                    var bounds = L.latLngBounds([ptKayabaLocation, driverLatLng]);

                                    // Fit the map to the calculated bounds with padding (adjust as needed)
                                    mymap.fitBounds(bounds, {
                                        padding: [50, 50]
                                    });
                                    // Update the waypoints to reflect PT Kayaba and driver's locations
                                    control.setWaypoints([
                                        L.latLng(ptKayabaLocation),
                                        L.latLng(driverLat, driverLng)
                                    ]);
                                    // Update driver's location in the database

                                    // Perform your logic based on the calculated distance
                                    if (distance > verificationDistance) {
                                        console.log('Distance is greater than 5 meters. Cannot verify arrival and check APD.');
                                    } else {
                                        console.log('Distance is within 5 meters. Proceed to verify arrival and check APD.');
                                    }
                                } else {
                                    console.error('Invalid response format from server:', response);
                                }
                            } catch (error) {
                                console.error('Error parsing server response:', error);
                            }
                        },
                        error: function (error) {
                            console.error('Error fetching driver location from the server:', error);
                        }
                    });
                });
        }

        function updateDirections(driverLatitude, driverLongitude) {
            // Use a mapping API like Google Maps API to get directions and display them
            // For simplicity, this example uses a static URL for demonstration purposes
            var directionsUrl = 'https://www.google.com/maps/dir/' + driverLatitude + ',' + driverLongitude + '/' +
                ptKayabaLocation.lat + ',' + ptKayabaLocation.lng;

            document.getElementById('directions').innerHTML = '<a href="' + directionsUrl + '" target="_blank">Get Directions</a>';
        }

        marker.on('click', function (e) {
            var userLatLng = e.latlng;
            document.getElementById('coordinates').innerHTML = 'Latitude: ' + userLatLng.lat + ', Longitude: ' + userLatLng.lng;

            // Calculate distance between user and PT Kayaba
            var distance = haversineDistance(userLatLng.lat, userLatLng.lng, ptKayabaLocation.lat, ptKayabaLocation.lng);

            // Perform your logic based on the calculated distance
            if (distance > verificationDistance) {
                console.log('Distance is greater than 5 meters. Cannot verify arrival and check APD.');
            } else {
                console.log('Distance is within 5 meters. Proceed to verify arrival and check APD.');
                updateDriverLocation();
            }


        });


        // // Update driver's location every 10 seconds (adjust the interval as needed)
        // setInterval(updateDriverLocation, 1000);
        //     var map = L.map('map').setView([0, 0], 13);
        //     L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        //         attribution: '© OpenStreetMap contributors'
        //     }).addTo(map);

        //     function updateMap(latitude, longitude) {
        //         map.setView([latitude, longitude], 13);
        //         // Clear existing markers before adding a new one
        //         map.eachLayer(function (layer) {
        //             if (layer instanceof L.Marker) {
        //                 map.removeLayer(layer);
        //             }
        //         });
        //         L.marker([latitude, longitude]).addTo(map);
        //     }

        //     // Update the map when the page loads with default values or values from PHP
        //     var defaultLatitude = 0; // Set your default latitude here
        //     var defaultLongitude = 0; // Set your default longitude here
        //     updateMap(defaultLatitude, defaultLongitude);

        //     // Function to update the map based on input values
        //     function updateMapFromInputs() {
        //         var inputLatitude = parseFloat(document.getElementById('latitude').value);
        //         var inputLongitude = parseFloat(document.getElementById('longitude').value);

        //         // Check if input values are valid numbers
        //         if (!isNaN(inputLatitude) && !isNaN(inputLongitude)) {
        //             updateMap(inputLatitude, inputLongitude);
        //         } else {
        //             // Optionally clear the map if the input is invalid
        //             // map.eachLayer(function (layer) {
        //             //     if (layer instanceof L.Marker) {
        //             //         map.removeLayer(layer);
        //             //     }
        //             // });
        //         }
        //     }
        //     // Call updateMapFromInputs when the input values change
        //     document.getElementById('latitude').addEventListener('input', updateMapFromInputs);
        //     document.getElementById('longitude').addEventListener('input', updateMapFromInputs);
        // 
    </script>
    <script>
        $(document).ready(function () {
            
            // Handle QR code scanning
            $('.submit-button').on('click', function () {
                // Extract data attributes
                var driver = $(this).data('driver');
                var plan = $(this).data('plan_arr');
                var lokasi = $(this).data('lokasi');
                var qrcode = $(this).data('qrcode');

                // Populate modal fields
                $('#id_driver').val(driver);
                $('#plan_arr').val(plan);
                $('#id_lokasi').val(lokasi);
            });
            $('button[data-bs-target="#qrcodeModal"]').on('click', function () {
                var qrCode = $(this).closest('tr').data('qrdata');
                console.log('QR Code Data:', qrCode);
                var detailButton = $('#submitModal button[type="button"]');

                // Check if QR Code is true or false and show the corresponding modal
                if (qrCode) {
                    $('#submitModal').modal('show');
                    detailButton.prop('disabled', false);
                } else {
                    $('#submitModal').modal('hide');
                    detailButton.prop('disabled', true);
                }
            });

            // Attach click event to each table row
            $('#dataTable tbody').on('click', 'tr', function () {
                // Get the QR code filename from the data attribute
                var qrCodeFilename = $(this).data('qrcode');


                // Set the QR code image source in the modal
                $('#modalQrCode').attr('src', qrCodeFilename);

                // Set the QR data in the hidden input field
                var qrData = $(this).data('qrdata');
                $('#qrdata').val(qrData);


                // Attach the fakeScanQR function to the "Scan QR Code" button
                $('#fakeScanButton').on('click', function () {
                    // Simulate QR scan
                    isQrScanned = true;

                    // Enable or disable the "Detail" button based on QR scan status
                    updateDetailButtonStatus();
                });
            });

            $('#closeQrCodeModalBtn').on('click', function () {
                $('#qrcodeModal').modal('hide');
            });

            // Function to update "Detail" button status based on QR scan status
            function updateDetailButtonStatus() {
                var detailButton = $('#submitModal button[type="button"]');

                if (isQrScanned) {
                    // If QR is scanned, enable the "Detail" button
                    detailButton.prop('disabled', false);
                } else {
                    // If QR is not scanned, disable the "Detail" button
                    detailButton.prop('disabled', true);
                }
            }

            // Function to submit the form with QR data
            $('#submitModal form').submit(function (e) {
                e.preventDefault();

                // Your form submission logic here

                // Reset QR scan status after form submission
                isQrScanned = false;

                // Update "Detail" button status
                updateDetailButtonStatus();

                // Hide the QR Code modal after form submission
                $('#qrcodeModal').modal('hide');
            });

        });
    </script>

</body>

</html>