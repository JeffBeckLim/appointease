<?php
require_once('../../assets/database-connection.php');

$connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check the database connection
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data and sanitize it
    $serviceName = mysqli_real_escape_string($connect, $_POST['serviceName']);
    $specialties = mysqli_real_escape_string($connect, $_POST['specialties']);

    // Handle file upload
    $targetDir = 'img/specialities/';
    $targetFile = $targetDir . basename($_FILES['serviceIcon']['name']);

    // Validate and move the uploaded file to the target directory
    if (move_uploaded_file($_FILES['serviceIcon']['tmp_name'], $targetFile)) {
        // Insert data into the services table using prepared statement
        $insertServiceQuery = "INSERT INTO services (service, specialty, icon) VALUES (?,?, ?)";

        $targetFile = 'assets/' . $targetFile;
        $stmt = mysqli_prepare($connect, $insertServiceQuery);

        // Use bind_param to bind variables and prevent SQL injection
        mysqli_stmt_bind_param($stmt, "sss", $serviceName, $specialties, $targetFile);

        $resultInsertService = mysqli_stmt_execute($stmt);

        // Check if the insertion into the services table was successful
        if ($resultInsertService) {
            // Send a success response back to the client
            echo 'Success';
        } else {
            // If there was an error inserting into the services table, send an error response
            echo 'Error inserting into services table: ' . mysqli_error($connect);
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
