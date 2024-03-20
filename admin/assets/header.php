<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/index.php by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:20 GMT -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Appointease - Admin</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Feathericon CSS -->
    <link rel="stylesheet" href="assets/css/feathericon.min.css">

    <link rel="stylesheet" href="assets/plugins/morris/morris.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Main CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!--[if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif]-->
</head>

<?php
//define database variables
require_once('../assets/database-connection.php');

$connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);


session_start();

$_SESSION['admincurrpage'] = 'dashboard';

//get the updated user details
if (isset($_SESSION['id'])) {

    $id = $_SESSION['id'];

    //sql query to get all the user details
    $result = mysqli_query($connect, "SELECT * FROM users WHERE id = '$id'")->fetch_assoc();

    $_SESSION["fname"] = $result['fname'];
    $_SESSION["lname"] = $result['lname'];

    //get the idpic value
    $_SESSION['idpic'] = isset($result['idpic']) ? $result['idpic'] : "assets/uploads/idpics/default-id.png";
}

// if logout is clicked, do this
if ($_SERVER['REQUEST_METHOD'] === 'GET' &&  isset($_GET['logout'])) {
    //when logged out, go to home page and destroy session
    header('Location: index-2.php?page=home');
    session_destroy();
}

// for setting up the dynamic navigation bar - uses get method
include 'assets/admin-dynamicnav.php';

?>

<body>

    <!-- Main Wrapper -->
    <div class="main-wrapper">

        <!-- Header -->
        <div class="header">

            <!-- Logo -->
            <div class="header-left">
                <a href="index.php" class="logo">
                    <img src="assets/img/logo-with-seal.png" class="img-fluid"  alt="Logo" style="object-fit: contain;">
                </a>
                <a href="index.php" class="logo logo-small">
                    <img src="assets/img/legazpi-health-logo.png" alt="Logo" width="30" height="30">
                </a>
            </div>
            <!-- /Logo -->

            <a href="javascript:void(0);" id="toggle_btn">
                <i class="fe fe-text-align-left"></i>
            </a>

            <!-- Mobile Menu Toggle -->
            <a class="mobile_btn" id="mobile_btn">
                <i class="fa fa-bars"></i>
            </a>
            <!-- /Mobile Menu Toggle -->
        </div>
        <!-- /Header -->

        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <ul>
                        <li class="menu-title">
                            <span>Main</span>
                        </li>
                        <li <?php echo ($_SESSION['admincurrpage'] == 'dashboard') ? "class=\"active\"" : '' ?>>
                            <a href="index.php?page=dashboard"><i class="fe fe-home"></i> <span>Dashboard</span></a>
                        </li>
                        <li <?php echo ($_SESSION['admincurrpage'] == 'appointments') ? "class=\"active\"" : '' ?>>
                            <a href="appointment-list.php?page=appointments"><i class="fe fe-layout"></i> <span>Appointments</span></a>
                        </li>
                        <li <?php echo ($_SESSION['admincurrpage'] == 'doctors') ? "class=\"active\"" : '' ?>>
                            <a href="doctor-list.php?page=doctors"><i class="fe fe-user-plus"></i> <span>Doctors</span></a>
                        </li>
                        <li <?php echo ($_SESSION['admincurrpage'] == 'pending') ? "class=\"active\"" : '' ?>>
                            <a href="doctor-pending-list.php?page=pending"><i class="fe fe-user"></i> <span>Pending Doctors</span></a>
                        </li>
                        <li <?php echo ($_SESSION['admincurrpage'] == 'patients') ? "class=\"active\"" : '' ?>>
                            <a href="patient-list.php?page=patients"><i class="fe fe-user"></i> <span>Patients</span></a>
                        </li>
                        <li <?php echo ($_SESSION['admincurrpage'] == 'records') ? "class=\"active\"" : '' ?>>
                            <a href="records.php?page=records"><i class="fe fe-calendar"></i> <span>Medical Records</span></a>
                        </li>
                        <li <?php echo ($_SESSION['admincurrpage'] == 'reviews') ? "class=\"active\"" : '' ?>>
                            <a href="reviews.php?page=reviews"><i class="fe fe-star-o"></i> <span>Reviews</span></a>
                        </li>
                        <li <?php echo ($_SESSION['admincurrpage'] == 'services') ? "class=\"active\"" : '' ?>>
                            <a href="specialities.php?page=services"><i class="fe fe-users"></i> <span>Services</span></a>
                        </li>
                        <li <?php echo ($_SESSION['admincurrpage'] == 'facilities') ? "class=\"active\"" : '' ?>>
                            <a href="facilities.php?page=facilities"><i class="fe fe-map"></i> <span>Facilities</span></a>
                        </li>
                        <li <?php echo ($_SESSION['admincurrpage'] == 'announcements') ? "class=\"active\"" : '' ?>>
                            <a href="announcements.php?page=announcements"><i class="fe fe-speaker"></i> <span>Announcements</span></a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="../login.php?logout" name='logout'><i class="fe fe-step-backward"></i> <span>Logout</span></a>
                        </li>
                        <!-- <li <?php echo ($_SESSION['admincurrpage'] == 'settings') ? "class=\"active\"" : '' ?>>
                            <a href="settings.php?page=settings"><i class="fe fe-vector"></i> <span>Settings</span></a>
                        </li> -->
                        <!-- <li class="submenu <?php echo ($_SESSION['admincurrpage'] == 'reports') ? "active" : '' ?>">
                            <a href="#"><i class="fe fe-document"></i> <span> Reports</span> <span class="menu-arrow"></span></a>
                            <ul style="display: none;">
                                <li <?php echo ($_SESSION['subpage'] == 'invoice') ? "class=\"active\"" : '' ?>><a href="invoice-report.php">Invoice Reports</a></li>
                            </ul>
                        </li> -->
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Sidebar -->