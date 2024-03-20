<?php

// Check if the schedule ID is provided
if (isset($_POST['appointmentId'])) {
    require_once('database-connection.php');

    $connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check the database connection
    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $appointmentId = filter_input(INPUT_POST, 'appointmentId', FILTER_VALIDATE_INT);

    // Validate the input
    if (!$appointmentId) {
        // Return error response for invalid or missing data
        echo 'error';
        exit;
    }

    // Prepare and execute the database query using parameter binding
    $sql = "DELETE FROM appointments WHERE appointment_id = ?";

    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "i", $appointmentId);
    mysqli_stmt_execute($stmt);

    // Check if the deletion was successful
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo 'success';
    } else {
        echo 'error';
    }

    // Close statement
    mysqli_stmt_close($stmt);
} else {
    echo 'error';
}
?>
