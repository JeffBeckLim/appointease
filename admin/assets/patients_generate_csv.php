<?php

require_once('../../assets/database-connection.php');

$connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check the database connection
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch patient information using a prepared statement
$patientQuery = "SELECT * FROM users WHERE usertype = 'patient'";
$patientStatement = mysqli_prepare($connect, $patientQuery);

// Check if the prepared statement was successful
if ($patientStatement) {
    // Execute the statement
    mysqli_stmt_execute($patientStatement);

    // Get the result set
    $patientResult = mysqli_stmt_get_result($patientStatement);

    // Set headers for CSV file
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="patients_export.csv"');
    header('Cache-Control: max-age=0');

    // Output CSV data
    $output = fopen('php://output', 'w');

    // Write merged column header
    fputcsv($output, array('Patients', '', '', '', '', '', ''), ',', ' ');

    // Write headers
    fputcsv($output, array('User ID', 'Patient Name', 'Email', 'Contact Number'));

    // Loop through the data and add rows
    while ($patientRow = mysqli_fetch_assoc($patientResult)) {
        $patientId = $patientRow['id'];
        $fname = $patientRow['fname'];
        $lname = $patientRow['lname'];
        $email = $patientRow['email'];
        $contactnum = $patientRow['contactnum'];

        // Add data to CSV file
        fputcsv($output, array(
            $patientId,
            "$fname $lname",
            $email,
            $contactnum
        ));
    }

    // Close the output file
    fclose($output);

    // Close the prepared statement
    mysqli_stmt_close($patientStatement);
} else {
    // Handle error if the prepared statement fails
    echo "Error preparing statement: " . mysqli_error($connect);
}

// Close the database connection
mysqli_close($connect);

exit(); // Prevent further execution of the HTML code after file generation
