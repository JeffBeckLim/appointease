<?php

// Set default value for active page
$_SESSION['admincurrpage'] = isset($_SESSION['admincurrpage']) ? $_SESSION['admincurrpage'] : 'dashboard';
$_SESSION['adminsubpage'] = isset($_SESSION['adminsubpage']) ? $_SESSION['adminsubpage'] : '';

// Handle page navigation
// get the value sent using the URL
if (isset($_GET['page'])) {
    $_SESSION['admincurrpage'] = $_GET['page'];
}

if (isset($_GET['sub'])) {
    $_SESSION['adminsubpage'] = $_GET['sub'];
}

if (isset($_GET['sub1'])) {
    $_SESSION['subsubpage'] = $_GET['sub1'];
}

?>