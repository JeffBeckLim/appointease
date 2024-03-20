<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('database-connection.php');

    $connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check the database connection
    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Extract and sanitize data from the POST request
    $recordId = mysqli_real_escape_string($connect, $_POST['editRecordId']);
    $recordDescription = isset($_POST['editrecordDescription']) ? mysqli_real_escape_string($connect, $_POST['editrecordDescription']) : '';

    // Process file upload
    $targetDir = "uploads/records/";
    $originalFileName = $_FILES["recordInput"]["name"];

    // Replace spaces with underscores and remove special characters
    $sanitizedFileName = preg_replace("/[^a-zA-Z0-9_\-\.]/", "_", $originalFileName);

    $targetFile = $targetDir . basename($sanitizedFileName);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if file is a valid image
    if (!empty($_FILES["recordInput"]["name"])) {
        // If a file is uploaded, move and update the attachment path
        if ($uploadOk && move_uploaded_file($_FILES["recordInput"]["tmp_name"], $targetFile)) {
            $attachmentTitle = $sanitizedFileName; // Use sanitized filename
            $sql = "UPDATE records SET description = ?, attachment_title = ?, attachment_path = ? WHERE record_id = ?";
            $stmt = mysqli_prepare($connect, $sql);
            mysqli_stmt_bind_param($stmt, "sssi", $recordDescription, $attachmentTitle, $targetFile, $recordId);
            $updateSuccess = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            // Handle file upload errors
            header('Content-Type: application/json');
            echo json_encode(array('status' => 'error', 'message' => 'Error uploading file.'));
            exit;
        }
    } else {
        // If no file is uploaded, update only the description
        $sql = "UPDATE records SET description = ? WHERE record_id = ?";
        $stmt = mysqli_prepare($connect, $sql);
        mysqli_stmt_bind_param($stmt, "si", $recordDescription, $recordId);
        $updateSuccess = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // Check the update status and return an appropriate response
    if (isset($updateSuccess) && $updateSuccess) {
        header('Content-Type: application/json');
        echo json_encode(array('status' => 'success', 'message' => 'Record updated successfully'));
    } else {
        header('Content-Type: application/json');
        echo json_encode(array('status' => 'error', 'message' => 'Error updating record.'));
    }

    // Close the database connection
    mysqli_close($connect);
} else {
    // Return error response if the request method is not POST
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request method'));
}
?>
