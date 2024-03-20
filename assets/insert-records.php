<?php

// Check if the form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Establish database connection
    require_once('database-connection.php');

    $connect = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check the database connection
    if ($connect->connect_error) {
        die("Connection failed: " . $connect->connect_error);
    }

    // Extract data from the POST request
    $record_description = mysqli_real_escape_string($connect, $_POST['recordDescription']);
    $doctorId = filter_input(INPUT_POST, 'doctorId', FILTER_VALIDATE_INT);
    $patientId = filter_input(INPUT_POST, 'patientId', FILTER_VALIDATE_INT);

    // File upload handling
    if (isset($_FILES['recordInput']) && $_FILES['recordInput']['error'] == UPLOAD_ERR_OK) {
        // Handle file upload
        $targetDir = "uploads/records/";

        // Get the original file name
        $originalFileName = $_FILES["recordInput"]["name"];

        // Replace spaces with underscores and remove special characters
        $sanitizedFileName = preg_replace("/[^a-zA-Z0-9_\-\.]/", "_", $originalFileName);

        $targetFile = $targetDir . basename($sanitizedFileName);

        if (move_uploaded_file($_FILES["recordInput"]["tmp_name"], $targetFile)) {
            // File uploaded successfully
            $recordFile = $targetFile;
        } else {
            // File upload failed
            echo json_encode(array('status' => 'error', 'message' => 'Error uploading file'));
            exit;
        }
    } else {
        // No file uploaded
        $recordFile = null;
    }

    // Prepare and execute the database query using prepared statements
    $insertQuery = "INSERT INTO records (doctor_id, user_id, description, record_date, attachment_title, attachment_path)
                    VALUES (?, ?, ?, NOW(), ?, ?)";

    $stmt = $connect->prepare($insertQuery);

    // Bind parameters
    $stmt->bind_param("iisss", $doctorId, $patientId, $record_description, $originalFileName, $recordFile);

    if ($stmt->execute()) {
        // Return success response
        echo json_encode(array('status' => 'success', 'message' => 'Record inserted successfully'));
    } else {
        // Return error response
        echo json_encode(array('status' => 'error', 'message' => 'Error inserting record: ' . $stmt->error));
    }

    $stmt->close(); // Close the prepared statement
    $connect->close(); // Close the database connection
} else {
    // Return error response if the request method is not POST
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request method'));
}
?>
