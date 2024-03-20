<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once('database-connection.php');

    $connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check the database connection
    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Extract and sanitize data from the POST request
    $appointment_status = mysqli_real_escape_string($connect, $_POST['appointment_status']);
    $currentScheduleId = filter_var($_POST['currentScheduleId'], FILTER_VALIDATE_INT);
    $doctorComments = isset($_POST['doctorComments']) ? mysqli_real_escape_string($connect, $_POST['doctorComments']) : '';

    // Validate the integer value
    if ($currentScheduleId === false || $currentScheduleId === null) {
        // Return error response if validation fails
        header('Content-Type: application/json');
        echo json_encode(array('status' => 'error', 'message' => 'Invalid appointment ID'));
        exit;
    }

    // Update the appointment status using a prepared statement
    $stmt = mysqli_prepare($connect, "UPDATE appointments SET appointment_status = ?, doctor_comments = ? WHERE appointment_id = ?");
    mysqli_stmt_bind_param($stmt, "ssi", $appointment_status, $doctorComments, $currentScheduleId);

    if (mysqli_stmt_execute($stmt)) {
        // Return success response
        header('Content-Type: application/json');
        echo json_encode(array('status' => 'success', 'message' => 'Booking updated'));
    } else {
        // Return error response
        header('Content-Type: application/json');
        echo json_encode(array('status' => 'error', 'message' => 'Error confirming booking.'));
    }

    mysqli_stmt_close($stmt);
} else {
    // Return error response if the request method is not POST
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request method'));
}

?>