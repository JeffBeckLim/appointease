<?php 

// Check if the form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('database-connection.php');

    $connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check the database connection
    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Extract data from the POST request and sanitize
    $startTime = isset($_POST['startTime']) ? mysqli_real_escape_string($connect, trim($_POST['startTime'])) : '';
    $endTime = isset($_POST['endTime']) ? mysqli_real_escape_string($connect, trim($_POST['endTime'])) : '';
    $duration = isset($_POST['duration']) ? mysqli_real_escape_string($connect, trim($_POST['duration'])) : '';
    $doctorid = isset($_POST['doctorid']) ? intval($_POST['doctorid']) : 0; 
    $day = isset($_POST['selectedday']) ? mysqli_real_escape_string($connect, trim($_POST['selectedday'])) : '';

    // Validate input
    if (!($startTime && $endTime && $duration && $doctorid > 0 && $day)) {
        // Return error response for invalid or missing data
        echo json_encode(array('status' => 'error', 'message' => 'Invalid or missing data'));
        exit;
    }

    // Prepare and execute the database query using parameter binding
    $sql = "INSERT INTO doctor_schedule (doctor_id, doctor_schedule_day, doctor_schedule_start_time, doctor_schedule_end_time, doctor_schedule_duration) 
            VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "issss", $doctorid, $day, $startTime, $endTime, $duration);

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