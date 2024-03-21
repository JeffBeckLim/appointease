<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Appointease</title>

    <!-- Favicons -->
    <link type="image/x-icon" href="assets/img/favicon.png" rel="icon">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css">

    <link rel="stylesheet" href="assets/plugins/dropzone/dropzone.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- include JS -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Bootstrap Timepicker CSS -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">

    <!-- Bootstrap Timepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js">
    </script>

    <!-- Tempus Dominus Bootstrap 4 CSS -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/css/tempusdominus-bootstrap-4.min.css">

    <!-- Tempus Dominus Bootstrap 4 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/js/tempusdominus-bootstrap-4.min.js">
    </script>


    <script>
    jQuery(function($) {
        $('#add_doctor_schedule_start_time').datetimepicker({
            format: 'HH:mm',
            icons: {
                time: 'fas fa-clock'
            },
            useCurrent: false
        });
    });

    jQuery(function($) {
        $('#edit_doctor_schedule_start_time').datetimepicker({
            format: 'HH:mm',
            icons: {
                time: 'fas fa-clock'
            },
            useCurrent: false
        });
    });

    jQuery(function($) {
        $('#patient_choose_booking_date').datetimepicker({
            format: 'DD-MM-YYYY',
            icons: {
                time: 'fas fa-clock'
            },
            useCurrent: false,
            widgetPositioning: {
                horizontal: 'auto',
                vertical: 'bottom'
            },
        });
    });
    </script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif]-->

</head>

<?php
//define database variables
require_once('database-connection.php');

$connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);


session_start();

$_SESSION['currpage'] = 'home';

// login and register
// if login button was clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["login"])) {

    //if fields are empty, alert user and save entered input
    if (empty($_POST["username"]) || empty($_POST["password"])) {
        echo '<script>alert("Both Fields are required")</script>';

        //if fields are filled, check credentials
    } else {

        $username = mysqli_real_escape_string($connect, $_POST["username"]);
        $password = mysqli_real_escape_string($connect, $_POST["password"]);
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($connect, $query);

        //if the data exists, check password
        if (mysqli_num_rows($result) > 0) {

            $row = mysqli_fetch_assoc($result);

            if (
                password_verify($password, $row["password"]) &&
                $username === $row["username"]
            ) {

                // Extract the value of user type
                $usertype = $row['usertype'];
                $id = $row['id'];

                // get details of user
                $_SESSION["username"] = $username;
                $_SESSION["usertype"] = $usertype;
                $_SESSION["id"] = $id;
                $_SESSION["isFirstUse"] = false;

                if ($usertype == 'patient') {
                    header("location:index-2.php?page=home");
                } else if ($usertype == 'practitioner') {
                    header("location:doctor-dashboard.php?page=doctor&sub=dashboard");
                } else {
                    header("location:admin/index.php?page=dashboard");
                }
            } else {
                // Incorrect credentials, set error message and reload login page
                $_SESSION['error_message_login'] = "Username or Password is incorrect";
            }
        } else {
            // Incorrect credentials, set error message and reload login page
            $_SESSION['error_message_login'] = "Username or Password is incorrect";
        }
    }
}

// if register button is clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["register"])) {

    // check if the username is taken already
    $username = mysqli_real_escape_string($connect, $_POST["username"]);
    $userCount = (int)mysqli_query($connect, "SELECT COUNT(*) as user_count FROM users WHERE username = '$username'")->fetch_assoc()['user_count'];

    if ($userCount > 0) {
        echo '<script>alert("Username is already taken")</script>';
    }

    // check if password and confirm password matches
    if ($_POST["password"] !== $_POST["cpassword"]) {
        echo '<script>alert("Passwords do not match")</script>';
    }

    // check if contact number given is all numeric
    if (!is_numeric($_POST["contactnum"])) {
        echo '<script>alert("Contact number is not all numbers)</script>';
    }

    // check if fields are empty
    if (
        empty($_POST["fname"]) || empty($_POST["lname"]) || empty($_POST["birthday"]) ||
        empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["cpassword"]) ||
        empty($_POST["email"]) || empty($_POST["contactnum"])
    ) {
        // if all fields are empty, alert then go back to registration page
        echo '<script>alert("All fields are required")</script>';

        // if all fields are filled, get all input
    } else {

        // embed all fields to the variables
        $fname = mysqli_real_escape_string($connect, $_POST["fname"]);
        $lname = mysqli_real_escape_string($connect, $_POST["lname"]);
        $birthday = mysqli_real_escape_string($connect, $_POST["birthday"]);
        $password = mysqli_real_escape_string($connect, $_POST["password"]);
        $password = password_hash($password, PASSWORD_DEFAULT);
        $email = mysqli_real_escape_string($connect, $_POST["email"]);
        $contactnum = mysqli_real_escape_string($connect, $_POST["contactnum"]);
        $gender = $_POST["gender"];
        $usertype = 'patient';
        $idpic = 'assets/uploads/idpics/default-id.png';

        // insert values to the database
        $query = "INSERT INTO users (fname, lname, birthday, gender, username, password, email, contactnum, usertype, idpic) 
        VALUES('$fname', '$lname', '$birthday', '$gender', '$username' ,'$password', '$email', '$contactnum', '$usertype', '$idpic')";

        // if query is successful, assign the variables to session variables and go to home page
        if (mysqli_query($connect, $query)) {

            //get value of id
            $id = mysqli_insert_id($connect);
            $_SESSION["id"] = $id;
            $_SESSION["fname"] = $fname;
            $_SESSION["lname"] = $lname;
            $_SESSION["username"] = $username;
            $_SESSION["usertype"] = $usertype;
            $_SESSION["isFirstUse"] = true;

            echo '<script>alert("Registration Done")</script>';

            header("location:patient-form.php?page=patients&sub=patientinfo");
        }
    }
}

// if register button is clicked for doctors
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["register_doctor"])) {

    // check if the username is taken already
    $username = mysqli_real_escape_string($connect, $_POST["username"]);
    $userCount = (int)mysqli_query($connect, "SELECT COUNT(*) as user_count FROM users WHERE username = '$username'")->fetch_assoc()['user_count'];

    if ($userCount > 0) {
        echo '<script>alert("Username is already taken")</script>';
    }

    // check if password and confirm password matches
    if ($_POST["password"] !== $_POST["cpassword"]) {
        echo '<script>alert("Passwords do not match")</script>';
    }

    // check if contact number given is all numeric
    if (!is_numeric($_POST["contactnum"])) {
        echo '<script>alert("Contact number is not all numbers)</script>';
    }

    // check if fields are empty
    if (
        empty($_POST["fname"]) || empty($_POST["lname"]) || empty($_POST["birthday"]) ||
        empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["cpassword"]) ||
        empty($_POST["email"]) || empty($_POST["contactnum"])
    ) {
        // if all fields are empty, alert then go back to registration page
        echo '<script>alert("All fields are required")</script>';

        // if all fields are filled, get all input
    } else {

        // embed all fields to the variables
        $fname = mysqli_real_escape_string($connect, $_POST["fname"]);
        $lname = mysqli_real_escape_string($connect, $_POST["lname"]);
        $birthday = mysqli_real_escape_string($connect, $_POST["birthday"]);
        $password = mysqli_real_escape_string($connect, $_POST["password"]);
        $password = password_hash($password, PASSWORD_DEFAULT);
        $email = mysqli_real_escape_string($connect, $_POST["email"]);
        $contactnum = mysqli_real_escape_string($connect, $_POST["contactnum"]);
        $gender = $_POST["gender"];
        $usertype = 'register_doctor';
        $idpic = 'assets/uploads/idpics/default-id.png';

        // insert values to the database
        $query = "INSERT INTO users (fname, lname, birthday, gender, username, password, email, contactnum, usertype, idpic) 
        VALUES('$fname', '$lname', '$birthday', '$gender', '$username' ,'$password', '$email', '$contactnum', '$usertype', '$idpic')";

        // if query is successful, assign the variables to session variables and go to home page
        if (mysqli_query($connect, $query)) {

            //get value of id
            $id = mysqli_insert_id($connect);
            $_SESSION["id"] = $id;
            $_SESSION["fname"] = $fname;
            $_SESSION["lname"] = $lname;
            $_SESSION["username"] = $username;
            $_SESSION["usertype"] = $usertype;
            //$_SESSION["isFirstUse"] = true;

            echo '<script>alert("Registration Done. Please wait for the administrator for updates.")</script>';

            header("location:index-2.php?page=home");
        }
    }
}

// if logout is clicked, do this
if ($_SERVER['REQUEST_METHOD'] === 'GET' &&  isset($_GET['logout'])) {
    //when logged out, go to home page and destroy session
    header('Location: index-2.php?page=home');
    session_destroy();
}

// Check if the 'id' session variable is set
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];

    // SQL query to get all the user details
    $result = mysqli_query($connect, "SELECT * FROM users WHERE id = '$id'")->fetch_assoc();

    if ($result) {
        // Update session variables with user details
        $_SESSION["fname"] = $result['fname'];
        $_SESSION["lname"] = $result['lname'];

        // Get the idpic value, use the default if it's not set
        $_SESSION['idpic'] = isset($result['idpic']) ? $result['idpic'] : "assets/uploads/idpics/default-id.png";
    }
}



// for setting up the dynamic navigation bar - uses get method
include 'assets/dynamicnav.php';
include 'assets/calculateage.php';
?>


<!-- for cloud dialog of services in home page -->
<style>
/* CSS styles */
.image-container {
    position: relative;
    display: inline-block;
}

.image-circle {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    /* Creates a circular shape */
    overflow: hidden;
    /* Hide overflow content */
    border: 1px solid #ddd;
    /* Add a border around the circle */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    /* Add a subtle shadow */
    background-size: cover;
    background-position: center;
    /* Center the image within the circle */
}

.cloud-dialog {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    /* Center the cloud dialog */
    background-color: #ffffff;
    border: 1px solid #ccc;
    padding: 5px;
    max-width: 170px;
    /* Set maximum width for the dialog */
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    /* Add a subtle shadow */
    visibility: hidden;
    opacity: 0;
    transition: visibility 0s, opacity 0.3s linear;
}

.image-container:hover .cloud-dialog {
    visibility: visible;
    opacity: 1;
}

.cloud-text {
    margin: 0;
    padding: 0;
    text-align: left;
    /* Center text horizontally */
}

/* Adjust list styling */
.cloud-dialog ul {
    list-style-type: disc;
    /* Use bullets for list items */
    margin: 0;
    padding-left: 20px;
}
</style>

<body>

    <!-- Main Wrapper -->
    <div class="main-wrapper">

        <!-- Header -->
        <header class="header">
            <nav class="navbar navbar-expand-lg header-nav">
                <div class="navbar-header">
                    <a id="mobile_btn" href="javascript:void(0);">
                        <span class="bar-icon">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </a>
                    <a href="index-2.php?page=home" class="navbar-brand logo">
                        <img src="assets/img/logo-with-seal.png" class="img-fluid" alt="Logo" width="201" height="52"
                            style="object-fit: contain">
                    </a>
                </div>
                <div class="main-menu-wrapper">
                    <div class="menu-header">
                        <a href="index-2.php?page=home" class="menu-logo">
                            <img src="assets/img/logo-with-seal.png" class="img-fluid" alt="Logo" width="201"
                                height="52" style="object-fit: contain">
                        </a>
                        <a id="menu_close" class="menu-close" href="javascript:void(0);">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>

                    <!-- check if the current page is in home -->
                    <ul class="main-nav">
                        <!-- check if the current page is practitioner then check the sub page -->
                        <?php if (
                            isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'practitioner'
                        ) : ?>
                        <li class="has-submenu <?php echo ($_SESSION['currpage'] == 'doctor') ? "active" : '' ?>">
                            <a href="#">Doctors <i class="fas fa-chevron-down"></i></a>
                            <ul class="submenu">
                                <li <?php echo ($_SESSION['subpage'] == 'dashboard') ? "class=\"active\"" : '' ?>>
                                    <a href="doctor-dashboard.php?page=doctor&sub=dashboard">Doctor Dashboard</a>
                                </li>
                                <li <?php echo ($_SESSION['subpage'] == 'sched') ? "class=\"active\"" : '' ?>>
                                    <a href="schedule-timings.php?page=doctor&sub=sched">Schedule Timing</a>
                                </li>
                                <li <?php echo ($_SESSION['subpage'] == 'patients') ? "class=\"active\"" : '' ?>>
                                    <a href="my-patients.php?page=doctor&sub=patients">Patients List</a>
                                </li>
                                <li <?php echo ($_SESSION['subpage'] == 'reviews') ? "class=\"active\"" : '' ?>>
                                    <a href="reviews.php?page=doctor&sub=reviews">Reviews</a>
                                </li>
                                <li
                                    <?php echo ($_SESSION['subpage'] == 'profile-settings') ? "class=\"active\"" : '' ?>>
                                    <a href="doctor-profile-settings.php?page=doctor&sub=profile-settings">Profile
                                        Settings</a>
                                </li>
                                <li <?php echo ($_SESSION['subpage'] == 'docchangepass') ? "class=\"active\"" : '' ?>>
                                    <a href="doctor-change-password.php?page=doctor&sub=docchangepass">Change
                                        Password</a>
                                </li>
                            </ul>
                        </li>
                        <?php endif; ?>

                        <!-- check if the page is in the patients -->
                        <?php if (
                            isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'patient'
                        ) : ?>
                        <li <?php echo ($_SESSION['currpage'] == 'home') ? "class=\"active\"" : '' ?>>
                            <a href="index-2.php?page=home">Home</a>
                        </li>
                        <li <?php echo ($_SESSION['currpage'] == 'announcements') ? "class=\"active\"" : '' ?>>
                            <a href="announcements.php?page=announcements">Announcements</a>
                        </li>
                        <li class="has-submenu <?php echo ($_SESSION['currpage'] == 'patients') ? "active" : '' ?>">
                            <a href="#">Patients <i class="fas fa-chevron-down"></i></a>
                            <ul class="submenu">
                                <li <?php echo ($_SESSION['subpage'] == 'patientinfo') ? "class=\"active\"" : '' ?>>
                                    <a href="patient-form.php?page=patients&sub=patientinfo">Patient Information</a>
                                </li>
                                <li <?php echo ($_SESSION['subpage'] == 'patdash') ? "class=\"active\"" : '' ?>>
                                    <a href="patient-dashboard.php?page=patients&sub=patdash">Patient Dashboard</a>
                                </li>
                                <li <?php echo ($_SESSION['subpage'] == 'searchdoc') ? "class=\"active\"" : '' ?>>
                                    <a href="search.php?page=patients&sub=searchdoc">Services</a>
                                </li>
                                <li <?php echo ($_SESSION['subpage'] == 'profset') ? "class=\"active\"" : '' ?>>
                                    <a href="profile-settings.php?page=patients&sub=profset">Profile Settings</a>
                                </li>
                                <li <?php echo ($_SESSION['subpage'] == 'changepass') ? "class=\"active\"" : '' ?>>
                                    <a href="change-password.php?page=patients&sub=changepass">Change Password</a>
                                </li>
                            </ul>
                        </li>
                        <?php endif; ?>

                        <!-- for pending doctors -->
                        <?php if (
                            isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'register_doctor'
                        ) : ?>
                        <li <?php echo ($_SESSION['currpage'] == 'home') ? "class=\"active\"" : '' ?>>
                            <a href="index-2.php?page=home">Home</a>
                        </li>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'admin') : ?>
                        <li>
                            <a href="admin/index.php" target="_blank">Admin</a>
                        </li>
                        <?php endif; ?>
                        <li class="login-link">
                            <a href="login.php">Login / Signup</a>
                        </li>
                    </ul>
                </div>
                <ul class="nav header-navbar-rht">

                    <!-- for login and logout flexibility -->
                    <li class="nav-item <?php echo ($_SESSION['currpage'] == 'login') ? "active" : '' ?>">

                        <!-- if logged in, show user menu -->
                        <?php if (isset($_SESSION['username'])) : ?>

                        <!-- User Menu -->
                    <li class="nav-item dropdown has-arrow logged-item">
                        <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                            <span class="user-img">
                                <img src="<?php echo isset($_SESSION['idpic']) ? $_SESSION['idpic'] : 'assets/uploads/idpics/default-id.png'; ?>"
                                    alt="User Image">
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="user-header">
                                <div class="avatar avatar-sm">
                                    <img src="<?php echo isset($_SESSION['idpic']) ? $_SESSION['idpic'] : 'assets/uploads/idpics/default-id.png'; ?>"
                                        alt="User Image" class="avatar-img rounded-circle">
                                </div>
                                <div class="user-text">
                                    <?php echo "<h6>" . $_SESSION['fname'] . " " . $_SESSION['lname'] . "</h6>"; ?>
                                    <p class="text-muted mb-0"><?php echo ucfirst($_SESSION['usertype']); ?></p>
                                </div>
                            </div>
                            <a class="dropdown-item"
                                href="<?php echo ($_SESSION['usertype'] === 'practitioner') ? 'doctor-dashboard.php?page=doctor&sub=dashboard' : 'patient-dashboard.php?page=patients&sub=patdash'; ?>">
                                Dashboard
                            </a>
                            <a class="dropdown-item"
                                href="<?php echo ($_SESSION['usertype'] === 'practitioner') ? 'doctor-profile-settings.php?page=doctor&sub=profile-settings' : 'profile-settings.php?page=patients&sub=profset'; ?>">
                                Profile Settings
                            </a>
                            <a class="dropdown-item" href="login.php?logout" name='logout'>Logout</a>

                        </div>
                    </li>

                    <?php else : ?>

                    <a class="nav-link header-login" href="login.php?page=login">Login / Signup </a>

                    <?php endif; ?>

                    <!-- /User Menu -->
                    </li>
                </ul>
            </nav>
        </header>
        <!-- /Header -->