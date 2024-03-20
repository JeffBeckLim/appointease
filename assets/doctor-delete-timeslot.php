<?php

// Check if the schedule ID is provided
if (isset($_POST['scheduleId'])) {
    
    require_once('database-connection.php');

    $connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check the database connection
    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Sanitize input
    $scheduleId = filter_var($_POST['scheduleId'], FILTER_VALIDATE_INT);

    if ($scheduleId === false) {
        // Return an error response if the provided schedule ID is not valid
        echo 'error';
        exit;
    }

    // Perform the deletion using prepared statement and parameter binding
    $sql = "DELETE FROM doctor_schedule WHERE doctor_schedule_id = ?";
    $stmt = mysqli_prepare($connect, $sql);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "i", $scheduleId);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Check if the deletion was successful
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo 'success';
    } else {
        echo 'error';
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($connect);
} else {
    echo 'error';
}
?>
