<?php
include "../config.php";
include "../lib/phpPasswordHashingLib-master/passwordLib.php";

session_start();

$err = "";
$username = "";
$rememberMe = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $enteredCaptcha = $_POST['captcha'];

    // Validate captcha
    if (isset($_SESSION['captcha_code']) && strtoupper($enteredCaptcha) === strtoupper($_SESSION['captcha_code'])) {
        // Captcha is correct
        unset($_SESSION['captcha_code']); // Remove captcha code after successful validation

        // Validate user credentials
        $query = "SELECT * FROM driver WHERE username='$username'";
        $result = $conn->query($query);

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                // Password is correct
                $_SESSION['id_driver'] = $user['id_driver'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama'] = $user['nama'];


                if (isset($_POST['rememberMe']) && $_POST['rememberMe'] == '1') {
                    $cookie_name = "username";
                    $cookie_value = $user['password'];
                    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
                }

                if (isset($_SESSION['username'])) {
                    header("refresh:0;URL=../Dashboard/dashboardVerify.php");
                }


            } else {
                $err = "Invalid username or password.";
            }
        } else {
            $err = "Invalid username or password.";
        }
    } else {
        // Captcha is incorrect
        $err = "Invalid captcha code.";
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
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>

    <main>
        <div class="container">

            <section
                class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">



                            <div class="card mb-3">

                                <div class="card-body">

                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Verifikasi Kedatangan Driver</h5>
                                        <p class="text-center small">Masukan Username dan Password</p>

                                    </div>

                                    <form class="row g-3 needs-validation" class="login" method="post"
                                        action="<?php echo $_SERVER['PHP_SELF']; ?>">

                                        <div class="col-12">
                                            <label for="username" class="form-label">Username</label>
                                            <div class="input-group has-validation">

                                                <input type="text" name="username" class="form-control" id="username"
                                                    required>
                                                <div class="invalid-feedback">Masukan username</div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" name="password" class="form-control" id="password"
                                                required>
                                            <div class="invalid-feedback">Masukan password!</div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                    value="true" id="rememberMe" <?php if ($ingataku == '1')
                                                        echo "checked" ?>>
                                                    <label class="form-check-label" for="rememberMe">Remember me</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <img src="http://localhost/VerifyActualonCustomerSite/Login/captcha.php"
                                                    alt="Captcha Image" />
                                                <div class="invalid-feedback">Invalid captcha code.</div>

                                            </div>
                                            <div class="col-12">
                                                <label for="captcha" class="form-label">Captcha</label>
                                                <input type="text" name="captcha" class="form-control" id="captcha"
                                                    required>
                                            </div>
                                            <div class="col-12">
                                                <button class="btn btn-primary w-100" type="submit" id="login"
                                                    name="login">Login</button>
                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </section>

            </div>
        </main><!-- End #main -->

        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
                class="bi bi-arrow-up-short"></i></a>

        <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    </body>

    </html>