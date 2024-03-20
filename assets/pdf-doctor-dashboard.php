<?php
// Retrieve appointments from the database based on the selected date
if (isset($_GET['appointment_date']) && isset($_GET['user_id'])) {
    require('../fpdf/fpdf.php');
    require_once('database-connection.php');

    $selectedDate = $_GET['appointment_date'];
    $userId =  $_GET['user_id'];

    // Validate user input
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate)) {
        echo "<script>alert('Invalid date format.');</script>";
        exit;
    }

    $query = "SELECT a.*, CONCAT(u.fname, ' ', u.lname) AS patient_name, u.id AS patient_id, CONCAT(d.fname, ' ', d.lname) AS doctor_name
              FROM appointments a
              JOIN users u ON a.user_id = u.id
              JOIN users d ON a.doctor_id = d.id
              WHERE a.doctor_id = ?
              AND DATE(a.appointment_date) = ?
              ORDER BY a.appointment_date ASC";

    $connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check the database connection
    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $statement = mysqli_prepare($connect, $query);

    // Bind parameters
    mysqli_stmt_bind_param($statement, "ss", $userId, $selectedDate);

    // Execute the statement
    mysqli_stmt_execute($statement);

    // Get the result set
    $result = mysqli_stmt_get_result($statement);

    // Store appointments in an array
    $appointments = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $appointments[] = $row;
    }

    // Close the database connection
    mysqli_close($connect);

    // Check if appointments are retrieved
    if (!empty($appointments)) {
        // Generate PDF with the fetched appointments
        $pdfData = generatePDF($appointments, $selectedDate, $userId);

        // Set appropriate headers for PDF download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="Appointments.pdf"');
        header('Content-Length: ' . strlen($pdfData));

        // Output the PDF content
        echo $pdfData;
        exit;
    } else {
        echo "<script>alert('No appointments found for the selected date.');</script>";
    }
} else {
    echo "<script>alert('Error generating PDF. Please try again.');</script>";
}

// Function to generate PDF
function generatePDF($appointments, $selectedDate, $userId)
{
    // Create new PDF document with letter size (8.5 x 11 inches) and landscape orientation
    $pdf = new FPDF('L', 'in', 'Letter');
    $pdf->AddPage();

    // Set font size
    $pdf->SetFont('Arial', 'B', 12); // Reduced font size

    // Get the doctor's name from the first appointment 
    $doctorName = isset($appointments[0]['doctor_name']) ? $appointments[0]['doctor_name'] : '';

    // Add title with doctor's name and selected date
    $pdf->Cell(0, 0.5, 'Appointments for Dr. ' . $doctorName . ' on ' . date('Y-m-d', strtotime($selectedDate)), 0, 1, 'C');

    // Add table headers
    $pdf->Cell(2.5, 0.5, 'Patient Name', 1, 0, 'C'); 
    $pdf->Cell(2.2, 0.5, 'Appt Date', 1, 0, 'C'); 
    $pdf->Cell(2, 0.5, 'Start Time', 1, 0, 'C'); 
    $pdf->Cell(1.5, 0.5, 'Duration', 1, 0, 'C'); 
    $pdf->Cell(2, 0.5, 'Status', 1, 1, 'C'); 

    // Add data to the table
    foreach ($appointments as $appointment) {
        // Set font style to regular (not bold)
        $pdf->SetFont('Arial', '', 12);

        $pdf->Cell(2.5, 0.5, $appointment['patient_name'], 1, 0, 'C'); 
        $pdf->Cell(2.2, 0.5, $appointment['appointment_date'], 1, 0, 'C'); 
        $pdf->Cell(2, 0.5, $appointment['appointment_start_time'], 1, 0, 'C'); 
        $pdf->Cell(1.5, 0.5, $appointment['appointment_duration'], 1, 0, 'C'); 
        $pdf->Cell(2, 0.5, $appointment['appointment_status'], 1, 1, 'C'); 
    }

    // Return the PDF content as a string
    return $pdf->Output('S');
}
?>