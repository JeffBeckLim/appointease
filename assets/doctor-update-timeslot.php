<?php 
// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    require_once('database-connection.php');
    
    $connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check the database connection
    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Get data from the AJAX request and sanitize
    $scheduleId = filter_input(INPUT_POST, 'scheduleId', FILTER_VALIDATE_INT);
    $startTime = isset($_POST['startTime']) ? mysqli_real_escape_string($connect, $_POST['startTime']) : '';
    $endTime = isset($_POST['endTime']) ? mysqli_real_escape_string($connect, $_POST['endTime']) : '';
    $duration = isset($_POST['duration']) ? mysqli_real_escape_string($connect, $_POST['duration']) : '';

    // Validate input
    if ($scheduleId === null || $scheduleId === false || $startTime === '' || $endTime === '' || $duration === '') {
        // Log an error or handle invalid data as needed
        error_log("Invalid data received");
        die("Invalid data received");
    }

    // Log the sanitized data for debugging
    error_log("Sanitized Schedule ID: $scheduleId, Start Time: $startTime, End Time: $endTime, Duration: $duration");

    // Prepare the SQL statement with placeholders
    $sql = "UPDATE doctor_schedule SET doctor_schedule_start_time = ?, doctor_schedule_end_time = ?, doctor_schedule_duration = ? WHERE doctor_schedule_id = ?";

    // Create a prepared statement
    $stmt = mysqli_prepare($connect, $sql);

    // Bind parameters to the prepared statement
    mysqli_stmt_bind_param($stmt, "sssi", $startTime, $endTime, $duration, $scheduleId);

    // Execute the prepared statement
    $result = mysqli_stmt_execute($stmt);

    // Check for success
    if ($result) {
        echo "Update successful";
    } else {
        echo "Update failed: " . mysqli_error($connect);
    }

    // Close the prepared statement
    mysqli_stmt_close($stmt);

    // Close the database connection
    mysqli_close($connect);
}
?>