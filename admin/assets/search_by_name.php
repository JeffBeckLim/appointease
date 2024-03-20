<?php
require_once('../../assets/database-connection.php');

// Check if the name parameter is set in the POST request
if (isset($_POST['name'])) {
    // Establish database connection
    $connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    $name = mysqli_real_escape_string($connect, $_POST['name']);

    // Check the database connection
    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare the query to select records based on the entered name
    $query = "SELECT r.record_id, r.doctor_id, r.user_id, r.description, r.record_date, r.attachment_title, r.attachment_path,
                    CONCAT(u_patient.fname, ' ', u_patient.lname) AS patient_name,
                    CONCAT(u_doctor.fname, ' ', u_doctor.lname) AS doctor_name
                FROM records r
                LEFT JOIN users u_patient ON r.user_id = u_patient.id
                LEFT JOIN users u_doctor ON r.doctor_id = u_doctor.id
                WHERE u_patient.fname LIKE '%$name%' OR u_patient.lname LIKE '%$name%'";

    // Execute the query
    $result = mysqli_query($connect, $query);

    // Check if there are rows in the result set
    if ($result->num_rows > 0) {
        echo '<div class="table-responsive">';
        echo '<table class="datatable table table-hover table-center mb-0">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Record Title</th>';
        echo '<th>Patient</th>';
        echo '<th>Doctor</th>';
        echo '<th>Date</th>';
        echo '<th class="text-center">Actions</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        // Loop through each row and display the data
        while ($row = $result->fetch_assoc()) {
            $recordName = $row['attachment_title'];
            $recordDate = date('M-d-Y', strtotime($row['record_date']));
            $recordId = $row['record_id'];
            $patientName = $row['patient_name'];
            $doctorName  = $row['doctor_name'];

            echo '<tr>';
            echo '<td>';
            echo "<h2 class='table-avatar'><a href='#'>{$recordName}</a></h2>";
            echo '</td>';
            echo "<td>{$patientName}</td>";
            echo "<td>{$doctorName}</td>";
            echo "<td>{$recordDate}</td>";
            echo '<td class="text-center">';
            echo '<div class="actions">';
            echo "<a href='../assets/{$row['attachment_path']}' target='_blank' class='btn btn-sm bg-primary-light'>";
            echo '<i class="fas fa-eye"></i> View';
            echo '</a>';
            echo "<a data-toggle='modal' href='#delete_modal' class='btn btn-sm bg-danger-light delete_record' data-record-id='{$recordId}'>";
            echo '<i class="fe fe-trash"></i> Delete';
            echo '</a>';
            echo '</div>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    } else {
        // Display a message if no records are found
        echo '<div class="table-responsive">';
        echo '<table class="datatable table table-hover table-center mb-0">';
        echo '<tbody>';
        echo '<tr><td colspan="5">No Records found</td></tr>';
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    }

    // Close the database connection
    mysqli_close($connect);
} else {
    // Output message if name parameter is not provided
    echo "Name parameter not provided.";
}
