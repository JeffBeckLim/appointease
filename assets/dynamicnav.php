<?php

// Set default value for active page
$_SESSION['currpage'] = isset($_SESSION['currpage']) ? $_SESSION['currpage'] : 'home';
$_SESSION['subpage'] = isset($_SESSION['subpage']) ? $_SESSION['subpage'] : '';
$_SESSION['subsubpage'] = isset($_SESSION['subsubpage']) ? $_SESSION['subsubpage'] : '';

// Handle page navigation
// get the value sent using the URL
if (isset($_GET['page'])) {
    $_SESSION['currpage'] = $_GET['page'];
}

if (isset($_GET['sub'])) {
    $_SESSION['subpage'] = $_GET['sub'];
}

if (isset($_GET['sub1'])) {
    $_SESSION['subsubpage'] = $_GET['sub1'];
}

?>
