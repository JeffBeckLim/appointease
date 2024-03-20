<?php

require_once('../../assets/database-connection.php');

$connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check the database connection
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch patient information with appointments and associated doctor information, sorted by patient name
$patientQuery = "SELECT u.id AS user_id, CONCAT(u.fname, ' ', u.lname) AS patient_name, u.email, u.contactnum, a.appointment_date, a.appointment_specialties, CONCAT(d.fname, ' ', d.lname) AS doctor_name
                FROM users u
                LEFT JOIN appointments a ON u.id = a.user_id
                LEFT JOIN users d ON a.doctor_id = d.id
                WHERE u.usertype = 'patient'
                ORDER BY patient_name";
$patientResult = mysqli_query($connect, $patientQuery);

// Set headers for CSV file
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="patients_by_appt_export.csv"');
header('Cache-Control: max-age=0');

// Output CSV data
$output = fopen('php://output', 'w');

// Write merged column header
fputcsv($output, array('Patients by appointments', '', '', '', '', '', ''), ',', ' ');

// Write headers
fputcsv($output, array('User ID', 'Patient Name', 'Email', 'Contact Number', 'Appointment Date', 'Consultation Type', 'Doctor Name'));

// Loop through the data and add rows
while ($row = mysqli_fetch_assoc($patientResult)) {
    // Add data to CSV file
    fputcsv($output, array_values($row));
}

// Close the output file
fclose($output);

// Close the database connection
mysqli_close($connect);

exit(); // Prevent further execution of the HTML code after file generation
