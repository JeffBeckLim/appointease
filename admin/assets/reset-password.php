<?php

require_once('../../assets/database-connection.php');

$connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check if the user ID is provided in the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userId'])) {
    $userId = $_POST['userId'];

    // Generate a new password 
    $newPassword = 'password';

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update the user's password in the database using prepared statements
    $updateQuery = "UPDATE users SET password = ? WHERE id = ?";
    
    $stmt = mysqli_prepare($connect, $updateQuery);
    mysqli_stmt_bind_param($stmt, "si", $hashedPassword, $userId);
    
    // Execute the update query
    if (mysqli_stmt_execute($stmt)) {
        // Return a success message
        echo 'Password reset successful';
    } else {
        // Return an error message
        echo 'Error resetting password: ' . mysqli_error($connect);
    }

    // Close statement
    mysqli_stmt_close($stmt);
} else {
    // Return an error message if the user ID is not provided
    echo 'User ID not provided';
}

// Close the database connection
mysqli_close($connect);
?>
