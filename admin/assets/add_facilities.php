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
    $facilityName = $_POST['facilityName'];

    // Handle file upload
    $targetDir = 'img/features/';
    $targetFile = $targetDir . basename($_FILES['facilityIcon']['name']);

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES['facilityIcon']['tmp_name'], $targetFile)) {
        // Insert data into the facilities table using prepared statement
        $insertServiceQuery = "INSERT INTO facilities (description, icon) VALUES (?, ?)";

        $targetFile = 'assets/' . $targetFile;
        $stmt = mysqli_prepare($connect, $insertServiceQuery);

        // Use bind_param to bind variables and prevent SQL injection
        mysqli_stmt_bind_param($stmt, "ss", $facilityName, $targetFile);

        $resultInsertService = mysqli_stmt_execute($stmt);

        // Check if the insertion into the facilities table was successful
        if ($resultInsertService) {
            // Send a success response back to the client
            echo 'Success';
        } else {
            // If there was an error inserting into the facilities table, send an error response
            echo 'Error inserting into facilities table: ' . mysqli_error($connect);
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
