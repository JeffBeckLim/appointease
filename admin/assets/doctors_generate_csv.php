<?php
require_once('../../assets/database-connection.php');

$connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check the database connection
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch doctor information including specialties
$doctorQuery = "SELECT doctors.*, users.fname, users.lname
                FROM doctors
                JOIN users ON doctors.doctor_id = users.id";

$doctorResult = mysqli_query($connect, $doctorQuery);

// Set headers for CSV file
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="doctors_export.csv"');
header('Cache-Control: max-age=0');

// Output CSV data
$output = fopen('php://output', 'w');

// Write merged column header
fputcsv($output, array('Doctors', '', '', '', '', '', ''), ',', ' ');

// Write headers
fputcsv($output, array('Doctor ID', 'Doctor Name', 'Specialties', 'Average Rating'));

// Loop through the data and add rows
while ($doctorRow = mysqli_fetch_assoc($doctorResult)) {
    $doctorId = $doctorRow['doctor_id'];
    $fname = $doctorRow['fname'];
    $lname = $doctorRow['lname'];
    $doctorSpecialties = $doctorRow['doctor_specialties'];

    // Fetch doctor reviews with parameter binding to prevent SQL injection
    $reviewQuery = "SELECT DISTINCT AVG(rating) AS average_rating
                    FROM reviews
                    WHERE doctor_id = ?";

    $stmt = mysqli_prepare($connect, $reviewQuery);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $doctorId);
        mysqli_stmt_execute($stmt);

        $reviewResult = mysqli_stmt_get_result($stmt);

        // Check if the review query was successful
        if ($reviewResult) {
            $reviewRow = mysqli_fetch_assoc($reviewResult);
            $averageRating = $reviewRow ? number_format($reviewRow['average_rating'], 2) : 0;

            // Add data to CSV file
            fputcsv($output, array(
                $doctorId,
                "$fname $lname",
                $doctorSpecialties,
                $averageRating // Format rating to two decimal places
            ));
        } else {
            // Handle error if the review query fails
            echo "Error fetching reviews: " . mysqli_error($connect);
        }

        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        // Handle error if the statement preparation fails
        echo "Error preparing review statement: " . mysqli_error($connect);
    }
}

// Close the output file
fclose($output);

// Close the database connection
mysqli_close($connect);

exit(); // Prevent further execution of the HTML code after file generation
?>
