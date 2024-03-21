<?php
require_once('../../assets/database-connection.php');

$connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check the database connection
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the doctor ID is provided in the POST request
if (isset($_POST['currentDoctorId'])) {

    $doctorId = $_POST['currentDoctorId'];

    // Prepare and execute the update query for users table using parameter binding
    $updateUserQuery = "UPDATE users SET usertype = 'patient' WHERE id = ?";
    $stmtUser = mysqli_prepare($connect, $updateUserQuery);

    // Prepare and execute the update query for doctors table using parameter binding
    $updateDoctorQuery = "UPDATE doctors SET doctor_status = 'Retired' WHERE doctor_id = ?";
    $stmtDoctor = mysqli_prepare($connect, $updateDoctorQuery);

    if ($stmtUser && $stmtDoctor) {
        mysqli_stmt_bind_param($stmtUser, "i", $doctorId);
        mysqli_stmt_execute($stmtUser);

        mysqli_stmt_bind_param($stmtDoctor, "i", $doctorId);
        mysqli_stmt_execute($stmtDoctor);

        // Check if both updates are successful
        if (mysqli_stmt_affected_rows($stmtUser) > 0 && mysqli_stmt_affected_rows($stmtDoctor) > 0) {
            // If both updates are successful, send a success response
            $response = ['success' => true];
        } else {
            // If any update fails, send an error response
            $response = ['success' => false, 'error' => 'No matching record found for update or update failed'];
        }

        // Close statements
        mysqli_stmt_close($stmtUser);
        mysqli_stmt_close($stmtDoctor);
    } else {
        // If statement preparation fails, send an error response
        $response = ['success' => false, 'error' => 'Failed to prepare update statements'];
    }
} else {
    // If doctor ID is not provided, send an error response
    $response = ['success' => false, 'error' => 'Doctor ID not provided'];
}

// Output JSON response
echo json_encode($response);
?>