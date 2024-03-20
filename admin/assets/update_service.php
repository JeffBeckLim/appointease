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
    $serviceId = mysqli_real_escape_string($connect, $_POST['serviceId']);

    // Check if a new picture is uploaded
    if (!empty($_FILES['serviceIcon']['name'])) {
        // Handle file upload
        $targetDir = 'img/specialities/';
        $targetFile = $targetDir . basename($_FILES['serviceIcon']['name']);

        // Validate and move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['serviceIcon']['tmp_name'], $targetFile)) {
            $targetFile = 'assets/' . $targetFile;
        } else {
            // If there was an error moving the uploaded file, send an error response
            echo 'Error moving uploaded file.';
            exit();
        }
    } else {
        // If no new picture is uploaded, retain the existing one
        $getServiceIconQuery = "SELECT icon FROM services WHERE service_id = ?";
        $stmt = mysqli_prepare($connect, $getServiceIconQuery);
        mysqli_stmt_bind_param($stmt, "i", $serviceId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $existingIcon);
        mysqli_stmt_fetch($stmt);
        $targetFile = $existingIcon;
        mysqli_stmt_close($stmt);
    }

    // Update data in the services table using prepared statement
    $updateServiceQuery = "UPDATE services SET service = ?, specialty = ?, icon = ? WHERE service_id = ?";
    $stmt = mysqli_prepare($connect, $updateServiceQuery);

    // Use bind_param to bind variables and prevent SQL injection
    mysqli_stmt_bind_param($stmt, "sssi", $serviceName, $specialties, $targetFile, $serviceId);

    $resultUpdateService = mysqli_stmt_execute($stmt);

    // Check if the update operation was successful
    if ($resultUpdateService) {
        // Send a success response back to the client
        echo 'Success';
    } else {
        // If there was an error updating the service, send an error response
        echo 'Error updating service: ' . mysqli_error($connect);
    }

    // Close statement
    mysqli_stmt_close($stmt);
} else {
    // If the request is not a POST request, send an error response
    echo 'Invalid request method';
}
?>
