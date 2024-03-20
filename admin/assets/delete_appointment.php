<?php

require_once('../../assets/database-connection.php');
$connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check the database connection
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the appointment ID is provided in the POST request
if (isset($_POST['currentAppointmentId'])) {

    $appointmentId = $_POST['currentAppointmentId'];

    // Prepare and execute the deletion query using parameter binding
    $deleteQuery = "DELETE FROM appointments WHERE appointment_id = ?";
    
    $stmt = mysqli_prepare($connect, $deleteQuery);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $appointmentId);
        mysqli_stmt_execute($stmt);

        // Check if any rows were affected
        $rowsAffected = mysqli_stmt_affected_rows($stmt);

        // Close statement
        mysqli_stmt_close($stmt);

        if ($rowsAffected > 0) {
            // If deletion is successful, send a success response
            $response = ['success' => true];
        } else {
            // If deletion fails, send an error response
            $response = ['success' => false, 'error' => 'No matching record found for deletion'];
        }
    } else {
        // If the statement preparation fails, send an error response
        $response = ['success' => false, 'error' => 'Failed to prepare the deletion statement'];
    }
} else {
    // If appointment ID is not provided, send an error response
    $response = ['success' => false, 'error' => 'Appointment ID not provided'];
}

// Output JSON response
echo json_encode($response);
?>
