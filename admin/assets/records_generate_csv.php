<?php

require_once('../../assets/database-connection.php');

$connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check the database connection
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch records information using a prepared statement
$recordsQuery = "SELECT r.record_id, r.doctor_id, r.user_id, r.description, r.record_date, r.attachment_title, r.attachment_path,
                    u_patient.fname AS patient_fname, u_patient.lname AS patient_lname,
                    u_doctor.fname AS doctor_fname, u_doctor.lname AS doctor_lname
        FROM records r
        LEFT JOIN users u_patient ON r.user_id = u_patient.id
        LEFT JOIN users u_doctor ON r.doctor_id = u_doctor.id";
$recordsStatement = mysqli_prepare($connect, $recordsQuery);

// Check if the prepared statement was successful
if ($recordsStatement) {
    // Execute the statement
    mysqli_stmt_execute($recordsStatement);

    // Get the result set
    $recordsResult = mysqli_stmt_get_result($recordsStatement);

    // Set headers for CSV file
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="records_export.csv"');
    header('Cache-Control: max-age=0');

    // Output CSV data
    $output = fopen('php://output', 'w');

    // Write merged column header
    fputcsv($output, array('Records', '', '', '', '', '', ''), ',', ' ');

    // Write headers
    fputcsv($output, array('Record ID', 'Record Title', 'Patient ID', 'Patient', 'Doctor ID', 'Doctor', 'Description', 'Record Date', 'Attachment Path'));

    // Loop through the data and add rows
    while ($recordRow = mysqli_fetch_assoc($recordsResult)) {
        // Add data to CSV file
        fputcsv($output, array(
            $recordRow['record_id'],
            $recordRow['attachment_title'],
            $recordRow['user_id'],
            $recordRow['patient_fname'] . ' ' . $recordRow['patient_lname'],
            $recordRow['doctor_id'],
            $recordRow['doctor_fname'] . ' ' . $recordRow['doctor_lname'],
            $recordRow['description'],
            $recordRow['record_date'],
            $recordRow['attachment_path']
        ));
    }

    // Close the output file
    fclose($output);

    // Close the prepared statement
    mysqli_stmt_close($recordsStatement);
} else {
    // Handle error if the prepared statement fails
    echo "Error preparing statement: " . mysqli_error($connect);
}

// Close the database connection
mysqli_close($connect);

exit(); // Prevent further execution of the HTML code after file generation
