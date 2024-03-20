<?php
if (isset($_GET['appointment_date'])) {
    require_once('database-connection.php');

    $connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    $selectedDate = $_GET['appointment_date'];
    $userId =  $_GET['user_id'];

    $query = "SELECT a.*, u.fname, u.lname, u.id, u.idpic
              FROM appointments a
              JOIN users u ON a.user_id = u.id
              WHERE a.doctor_id = ?
              AND DATE(a.appointment_date) = ?
              ORDER BY a.appointment_date ASC";

    $statement = mysqli_prepare($connect, $query);

    // Bind parameters
    mysqli_stmt_bind_param($statement, "ss", $userId, $selectedDate);

    // Execute the statement
    mysqli_stmt_execute($statement);

    // Get the result set
    $result = mysqli_stmt_get_result($statement);

    // Check if appointments are found for the selected date
    if (mysqli_num_rows($result) > 0) {
        // Generate HTML table header row
        echo "
        <thead>
            <tr>
                <th class='text-center'>Patient Name</th>
                <th class='text-center'>Appt Date</th>
                <th class='text-center'>Start Time</th>
                <th class='text-center'>Duration (mins.)</th>
                <th class='text-center'>Status</th>
                <th class='text-center'>Action</th>
            </tr>
        </thead>";
        echo '<tbody>';
        // Generate HTML table rows
        while ($row = mysqli_fetch_assoc($result)) {
            $reason = $row['appointment_reason'];
            $comments = $row['doctor_comments'];
            $patientID = $row['id'];
            $doctorSpecialties = $row["appointment_specialties"];

            // Echo HTML for each table row
            echo '<tr>';
            echo '<td>';
            echo '<h2 class="table-avatar">';
            echo '<a href="patient-profile.php?page=doctor&sub=patients-profile&patient_id=' . $patientID . '" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="' . (isset($row['idpic']) ? $row['idpic'] : 'assets/uploads/idpics/default-id.png') . '" alt="User Image"></a>';
            echo '<a href="patient-profile.php?page=doctor&sub=patients-profile&patient_id=' . $patientID . '">' . $row['fname'] . $row['lname'] . ' <span>#' . $row['id'] . '</span></a>';
            echo '</h2>';
            echo '</td>';
            echo "<td class='text-center'>" . $row['appointment_date'] . '</td>';
            echo "<td class='text-center'>" . date('H:i A', strtotime($row['appointment_start_time'])) . '</td>';
            echo "<td class='text-center'>" . $row['appointment_duration'] . '</td>';
            echo "<td class='text-center' data-appointment-reason='$reason' data-doctor-comments='$comments'>";
            // Display different badges based on appointment status
            $statusClass = '';
            switch ($row['appointment_status']) {
                case 'Pending':
                    $statusClass = 'bg-warning-light';
                    break;
                case 'Confirmed':
                    $statusClass = 'bg-success-light';
                    break;
                case 'Cancelled':
                    $statusClass = 'bg-danger-light';
                    break;
                case 'Completed':
                    $statusClass = 'bg-info-light';
                    break;
            }
            echo '<span class="badge badge-pill ' . $statusClass . '">' . ucfirst($row['appointment_status']) . '</span>';
            echo '</td>';
            echo '<td class="text-center">';
            echo '<div class="table-action">';
            echo "<a href='#view_booking' class='btn btn-sm view_booking bg-info-light' data-toggle='modal' data-appointment-id='{$row['appointment_id']}' data-specialties='{$doctorSpecialties}'>";
            echo '<i class="fas fa-eye"></i> View';
            echo '</a>';
            echo '</div>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
    } else {
        // If no appointments found, display a message
        echo '<tr><td colspan="6" class="text-center">No appointments found for the selected date.</td></tr>';
    }
} else {
    echo '<tr><td colspan="6" class="text-center">Please select a date to view appointments.</td></tr>';
}
