<?php

require_once('../../assets/database-connection.php');

$connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check the database connection
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve start and end dates from POST data
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];

// SQL query for appointments filtered by date range
$appointmentQuery = "SELECT r.*, du.fname AS doctor_fname, du.lname AS doctor_lname, u.fname AS patient_fname, u.lname AS patient_lname
                    FROM records r
                    LEFT JOIN doctors d ON r.doctor_id = d.doctor_id
                    LEFT JOIN users AS du ON d.doctor_id = du.id
                    LEFT JOIN users AS u ON r.user_id = u.id
                    WHERE r.record_date BETWEEN ? AND ?
                    ORDER BY CONCAT(du.fname, ' ', du.lname) ASC, r.record_date ASC";

// Execute the query using prepared statement
$stmt = mysqli_prepare($connect, $appointmentQuery);

// Bind parameters and execute the statement
mysqli_stmt_bind_param($stmt, "ss", $startDate, $endDate);
mysqli_stmt_execute($stmt);

// Get result set
$appointmentResult = mysqli_stmt_get_result($stmt);

// Set headers for CSV file
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="records_by_date_export.csv"');
header('Cache-Control: max-age=0');

// Output CSV data
$output = fopen('php://output', 'w');

// Write merged column header
fputcsv($output, array('Records by Date', '', '', '', '', '', ''), ',', ' ');

// Write headers
fputcsv($output, array('Record ID', 'Doctor ID', 'Doctor Name', 'User ID', 'Patient Name', 'Record Date', 'Description'));

// Loop through the data and add rows
while ($recordRow = mysqli_fetch_assoc($appointmentResult)) {
    // Format the record date  
    $recordDate = date('d M Y', strtotime($recordRow['record_date']));

    // Sanitize and format data for CSV
    $doctorName = $recordRow['doctor_fname'] . ' ' . $recordRow['doctor_lname'];
    $patientName = $recordRow['patient_fname'] . ' ' . $recordRow['patient_lname'];

    // Add data to CSV file
    fputcsv($output, array(
        $recordRow['record_id'],
        $recordRow['doctor_id'],
        $doctorName,
        $recordRow['user_id'],
        $patientName,
        $recordDate,
        $recordRow['description']
    ));
}

// Close the output file
fclose($output);

// Close statement
mysqli_stmt_close($stmt);

exit(); // Prevent further execution of the HTML code after file generation
?>
