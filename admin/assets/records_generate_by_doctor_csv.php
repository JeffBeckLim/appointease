<?php

require_once('../../assets/database-connection.php');

$connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check the database connection
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// SQL query for records
$recordQuery = "SELECT r.*, du.fname AS doctor_fname, du.lname AS doctor_lname, CONCAT(u.fname, ' ', u.lname) AS patient_name
                FROM records r
                LEFT JOIN doctors d ON r.doctor_id = d.doctor_id
                LEFT JOIN users AS du ON d.doctor_id = du.id
                LEFT JOIN users AS u ON r.user_id = u.id
                ORDER BY CONCAT(du.fname, ' ', du.lname) ASC, r.record_date DESC";

// Execute the query using prepared statement
$stmt = mysqli_prepare($connect, $recordQuery);
mysqli_stmt_execute($stmt);
$recordResult = mysqli_stmt_get_result($stmt);

// Set headers for CSV file
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="records_by_doctor_export.csv"');
header('Cache-Control: max-age=0');

// Output CSV data
$output = fopen('php://output', 'w');

// Write merged column header
fputcsv($output, array('Records by Doctor', '', '', '', '', '', ''), ',', ' ');

// Write headers
fputcsv($output, array('Record ID', 'Doctor ID', 'Doctor Name', 'Patient ID', 'Patient Name', 'Record Date', 'Description', 'Attachment Title', 'Attachment Path'));

// Loop through the data and add rows
while ($recordRow = mysqli_fetch_assoc($recordResult)) {
    // Format the record date  
    $recordDate = date('d M Y', strtotime($recordRow['record_date']));

    // Sanitize and format data for CSV
    $doctorName = $recordRow['doctor_fname'] . ' ' . $recordRow['doctor_lname'];
    $patientName = $recordRow['patient_name'];

    // Add data to CSV file
    fputcsv($output, array(
        $recordRow['record_id'],
        $recordRow['doctor_id'],
        $doctorName,
        $recordRow['user_id'],
        $patientName,
        $recordDate,
        $recordRow['description'],
        $recordRow['attachment_title'],
        $recordRow['attachment_path']
    ));
}

// Close the output file
fclose($output);

// Close statement
mysqli_stmt_close($stmt);

exit(); // Prevent further execution of the HTML code after file generation
?>
