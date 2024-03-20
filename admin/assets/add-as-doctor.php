<?php 
require_once('../../assets/database-connection.php');

$connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check the database connection
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user ID from the POST data
    $userId = $_POST['userId'];

    // Prepare and execute the query to insert into the doctors table
    $insertDoctorQuery = "INSERT INTO doctors (doctor_id, doctor_status) VALUES (?, 'Active')";
    $stmtInsertDoctor = mysqli_prepare($connect, $insertDoctorQuery);
    mysqli_stmt_bind_param($stmtInsertDoctor, "i", $userId);
    $resultInsertDoctor = mysqli_stmt_execute($stmtInsertDoctor);

    // Prepare and execute the query to update the users table
    $updateUserQuery = "UPDATE users SET usertype='practitioner' WHERE id=?";
    $stmtUpdateUser = mysqli_prepare($connect, $updateUserQuery);
    mysqli_stmt_bind_param($stmtUpdateUser, "i", $userId);
    $resultUpdateUser = mysqli_stmt_execute($stmtUpdateUser);

    // Check if both queries were successful
    if ($resultInsertDoctor && $resultUpdateUser) {
        // Send a success response back to the client
        echo 'Success';
    } else {
        // If there was an error with either query, send an error response
        echo 'Error: ' . mysqli_error($connect);
    }

    // Close statements
    mysqli_stmt_close($stmtInsertDoctor);
    mysqli_stmt_close($stmtUpdateUser);
} else {
    // If the request is not a POST request, send an error response
    echo 'Invalid request method';
}

?>
