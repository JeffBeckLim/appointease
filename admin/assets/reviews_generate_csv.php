<?php

require_once('../../assets/database-connection.php');

$connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check the database connection
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// SQL query to fetch reviews with patient and doctor names
$reviewsQuery = "SELECT r.*, p.fname AS patient_fname, p.lname AS patient_lname, d.fname AS doctor_fname, d.lname AS doctor_lname, r.review_date
                FROM reviews r
                JOIN users p ON r.user_id = p.id
                JOIN users d ON r.doctor_id = d.id
                ORDER BY r.review_date DESC";

// Prepare and execute the statement
$reviewsStatement = mysqli_prepare($connect, $reviewsQuery);

// Check if the prepared statement was successful
if ($reviewsStatement) {
    // Execute the statement
    mysqli_stmt_execute($reviewsStatement);

    // Get the result set
    $reviewsResult = mysqli_stmt_get_result($reviewsStatement);

    // Set headers for CSV file
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="reviews_export.csv"');
    header('Cache-Control: max-age=0');

    // Output CSV data
    $output = fopen('php://output', 'w');

    // Write merged column header
    fputcsv($output, array('Reviews', '', '', '', '', '', ''), ',', ' ');

    // Write headers
    fputcsv($output, array('Patient Name', 'User ID', 'Doctor Name', 'Doctor ID', 'Rating', 'Comment', 'Review Date'));

    // Loop through the data and add rows
    while ($reviewRow = mysqli_fetch_assoc($reviewsResult)) {
        $patientName = htmlspecialchars($reviewRow['patient_fname'] . ' ' . $reviewRow['patient_lname']);
        $userId = $reviewRow['user_id'];
        $doctorName = htmlspecialchars($reviewRow['doctor_fname'] . ' ' . $reviewRow['doctor_lname']);
        $doctorId = $reviewRow['doctor_id'];
        $ratings = $reviewRow['rating'];
        $comment = htmlspecialchars($reviewRow['comment']);
        $date = date('d M Y', strtotime($reviewRow['review_date']));

        // Add data to CSV file
        fputcsv($output, array(
            $patientName,
            $userId,
            $doctorName,
            $doctorId,
            $ratings,
            $comment,
            $date
        ));
    }

    // Close the output file
    fclose($output);

    // Close the prepared statement
    mysqli_stmt_close($reviewsStatement);
} else {
    // Handle error if the prepared statement fails
    echo "Error preparing statement: " . mysqli_error($connect);
}

// Close the database connection
mysqli_close($connect);

exit(); // Prevent further execution of the HTML code after file generation
