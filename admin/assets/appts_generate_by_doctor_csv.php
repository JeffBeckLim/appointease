<?php

require_once('../../assets/database-connection.php');

$connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check the database connection
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// SQL query for appointments
$appointmentQuery = "SELECT a.*, du.fname AS doctor_fname, du.lname AS doctor_lname, u.fname AS patient_fname, u.lname AS patient_lname
                    FROM appointments a
                    LEFT JOIN doctors d ON a.doctor_id = d.doctor_id
                    LEFT JOIN users AS du ON d.doctor_id = du.id
                    LEFT JOIN users AS u ON a.user_id = u.id
                    ORDER BY CONCAT(du.fname, ' ', du.lname) ASC, a.appointment_date DESC";

// Execute the query using prepared statement
$stmt = mysqli_prepare($connect, $appointmentQuery);
mysqli_stmt_execute($stmt);
$appointmentResult = mysqli_stmt_get_result($stmt);

// Set headers for CSV file
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="appointments_by_doctor_export.csv"');
header('Cache-Control: max-age=0');

// Output CSV data
$output = fopen('php://output', 'w');

// Write merged column header
fputcsv($output, array('Appointments by Doctor', '', '', '', '', '', ''), ',', ' ');

// Write headers
fputcsv($output, array('Doctor ID', 'Doctor Name', 'Specialties', 'Patient ID', 'Patient Name', 'Appointment Date', 'Status'));

// Loop through the data and add rows
while ($appointmentRow = mysqli_fetch_assoc($appointmentResult)) {
    // Format the appointment date  
    $appointmentDate = date('d M Y', strtotime($appointmentRow['appointment_date']));

    // Sanitize and format data for CSV
    $doctorName = $appointmentRow['doctor_fname'] . ' ' . $appointmentRow['doctor_lname'];
    $patientName = $appointmentRow['patient_fname'] . ' ' . $appointmentRow['patient_lname'];
    $appointmentStatus = ucfirst($appointmentRow['appointment_status']);

    // Add data to CSV file
    fputcsv($output, array(
        $appointmentRow['doctor_id'],
        $doctorName,
        $appointmentRow['appointment_specialties'],
        $appointmentRow['user_id'],
        $patientName,
        $appointmentDate,
        $appointmentStatus
    ));
}

// Close the output file
fclose($output);

// Close statement
mysqli_stmt_close($stmt);

exit(); // Prevent further execution of the HTML code after file generation
?>
