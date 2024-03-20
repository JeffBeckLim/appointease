<?php
require_once('../../assets/database-connection.php');

$connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check the database connection
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $announcementName = $_POST['announcementName'];

    // Handle file upload
    $targetDir = 'img/announcements/';
    $targetFile = $targetDir . basename($_FILES['announcementIcon']['name']);

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES['announcementIcon']['tmp_name'], $targetFile)) {
        // Insert data into the announcements table using prepared statement
        $insertServiceQuery = "INSERT INTO announcements (description, pic_path) VALUES (?, ?)";

        $targetFile = 'assets/' . $targetFile;
        $stmt = mysqli_prepare($connect, $insertServiceQuery);
        mysqli_stmt_bind_param($stmt, "ss", $announcementName, $targetFile);

        $resultInsertService = mysqli_stmt_execute($stmt);

        // Check if the insertion into the announcements table was successful
        if ($resultInsertService) {
            // Send a success response back to the client
            echo 'Success';
        } else {
            // If there was an error inserting into the announcements table, send an error response
            echo 'Error inserting into announcements table: ' . mysqli_error($connect);
        }

        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        // If there was an error moving the uploaded file, send an error response
        echo 'Error moving uploaded file.';
    }
} else {
    // If the request is not a POST request, send an error response
    echo 'Invalid request method';
}

?>
