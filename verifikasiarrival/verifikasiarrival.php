<?php
include("../lib/phpqrcode/qrlib.php");
include("../config.php");
include("get_driver_location.php");
session_start();

// Check if the user is not authenticated
if (!isset($_SESSION['username'])) {
    header("Location: ../Login/login.php");
    exit();
}


// Logout functionality
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../Login/login.php");
    exit();
}
if (isset($_GET['qrData'])) {
    // Retrieve the QR data from the query parameters
    $qrData = urldecode($_GET['qrData']);
    // For example, you can echo it or perform further processing
    $dataArray = explode(" || ", $qrData);
    // $dataArray sekarang berisi data terpisah
    $customer_name = $dataArray[0];
    $cycle = $dataArray[1];
    $dock_customer = $dataArray[2];
    $qty_paket = $dataArray[3];
    $qty = $dataArray[4];

    // Now you can use $qrData as needed
    // For example, you can echo it or perform further processing
    echo "Scanned QR Data: " . $qrData;
}

//else {
//     // If QR data is not present, redirect to the main page
//     header("Location: prosesverifikasi.php");
//     exit();
// }

echo $formattedDate;
$conn = new MySQLi($DBhost, $DBuser, $DBpass, $DBname);



function getDataDriver($conn, $idDriver)
{
    $sql = "SELECT nama_driver
    FROM driver
    WHERE id_driver= '$idDriver'";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Fetch the data
        $row = mysqli_fetch_assoc($result);

        // You can use $row['column_name'] to access the values
        return $row;
    } else {
        echo "Failed: " . mysqli_error($conn);
        return false;
    }
}
function getDataLokasi($conn, $idLokasi)
{
    $sql = "SELECT l.long, l.lat
            FROM master_lokasi l 
            WHERE l.id_lokasi = '$idLokasi'";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Fetch the data
        $row = mysqli_fetch_assoc($result);

        // You can use $row['column_name'] to access the values
        return $row;
    } else {
        echo "Failed: " . mysqli_error($conn);
        return false;
    }
}

function updateDataScheuduleDelivery()
{


}
$idLokasi = 1; // Replace with the actual ID

$idDriver = 1;


$driverData = getDataDriver($conn, $idDriver);

if ($driverData) {
    // Extract latitude and longitude values
    $nama_driver = $driverData['nama_driver'];
    ;
} else {
    // Handle the case where data retrieval failed
    $nama_driver = '';
}
// Call the function to get data
$lokasiData = getDataLokasi($conn, $idLokasi);

// Check if data retrieval was successful
if ($lokasiData) {
    // Extract latitude and longitude values
    $latitude = $lokasiData['lat'];
    $longitude = $lokasiData['long'];
} else {
    // Handle the case where data retrieval failed
    $latitude = $longitude = '';
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
    <title>Verifikasi Kedatangan</title>
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

    <!-- Custom styles for this page -->
    <link href="../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

    <!-- Include OpenRouteService JS SDK -->
    <script src="https://openrouteservice.org/assets/js/openrouteservice-js-sdk.js"></script>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboardVerify.php">
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
                    <span>Scan QR Kedatangan</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="../prosesDelivery/verifikasikedatangan.php">Scan QR
                            Kedatangan</a>
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
                    <h1 class="h3 mb-2 text-gray-800">Verifikasi Kedatangan</h1>

                    <form class="form-horizontal" method="POST">

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
                                            <div class="form-group">
                                                <label for="plan_arr"
                                                    class="col-sm-3 text-end control-label col-form-label">
                                                    Planning</label>
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
                                                        name="longitude" value="<?php echo $longitude; ?>" readonly />
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="latitude"
                                                    class="col-sm-3 text-end control-label col-form-label">Latitude</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="latitude"
                                                        name="latitude" value="<?php echo $latitude; ?>" readonly />
                                                    <input type="hidden" id="id_lokasi" name="id_lokasi"
                                                        value="<?php echo $idLokasi; ?>" />
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="user_driver"
                                                    class="col-sm-3 text-end control-label col-form-label">
                                                    Driver</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" name="driver"
                                                        value="<?php echo $nama_driver; ?>" readonly />
                                                    <input type="hidden" id="id_driver" name="id_driver"
                                                        value="<?php echo $idDriver; ?>" />
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="waktu_kedatangan"
                                                    class="col-sm-3 text-end control-label col-form-label">Waktu
                                                    Kedatangan</label>
                                                <div class="col-sm-9">
                                                    <input type="date" class="form-control" id="waktu_kedatangan"
                                                        name="waktu_kedatangan" value="<?php echo $formattedDate; ?>" />
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

                    <button class="btn btn-primary" type="submit" name="logout">Logout</button>

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
    <script src="../assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

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
        crossorigin="anonymous"></script>




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
        // </script>


</body>

</html>