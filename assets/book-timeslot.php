<?php

// Check if the form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once('database-connection.php');

    $connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check the database connection
    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Extract data from the POST request
    $doctorid = $_POST['doctorid'];
    $userid = $_POST['userid'];
    $currentScheduleId = $_POST['currentScheduleId'];
    $bookStartTime = $_POST['bookStartTime'];
    $bookDuration = $_POST['bookDuration'];
    $bookDate = $_POST['bookDate'];
    $bookReason = $_POST['bookReason'];
    $doctorSpecialties = $_POST['doctorSpecialties']; 
    $specialtiesString = implode(',', $doctorSpecialties);
    $status = 'Pending';

    // Convert the book date for the database
    $bookDate = date("Y-m-d", strtotime($bookDate));

    // Prepare and execute the database query using parameter binding
    $sql = "INSERT INTO appointments (doctor_id, user_id, doctor_schedule_id, appointment_reason, appointment_start_time ,appointment_duration, appointment_date, appointment_status, appointment_specialties) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "iisssisss", $doctorid, $userid, $currentScheduleId, $bookReason, $bookStartTime, $bookDuration, $bookDate, $status, $specialtiesString);

    if (mysqli_stmt_execute($stmt)) {
        // Return success response
        echo json_encode(array('status' => 'success', 'message' => 'Time slot added successfully'));
    } else {
        // Return error response
        echo json_encode(array('status' => 'error', 'message' => 'Error adding time slot: ' . mysqli_error($connect)));
    }

    // Close statement
    mysqli_stmt_close($stmt);
    // Close the database connection
    mysqli_close($connect);
} else {
    // Return error response if the request method is not POST
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request method'));
}
?>
