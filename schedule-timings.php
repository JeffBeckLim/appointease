<!-- Include database connection and other checking of session variables -->
<?php require_once('assets/header.php') ?>

<?php
if (isset($_POST["change-password"])) {
}

?>

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-12 col-12">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <!-- <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href=" index-2.php?page=home">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Schedule Timings</li>
                    </ol> -->
                </nav>
                <h2 class="breadcrumb-title">Schedule Timings</h2>
            </div>
        </div>
    </div>
</div>
<!-- /Breadcrumb -->

<!-- Page Content -->
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <!-- Include the side profile -->
            <?php require_once('assets/doctor-sideprofile.php') ?>

            <div class="col-md-7 col-lg-8 col-xl-9">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Schedule Timings</h4>
                                <div class="profile-box">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card schedule-widget mb-0">

                                                <!-- Schedule Header -->
                                                <div class="schedule-header">

                                                    <!-- Schedule Nav -->
                                                    <div class="schedule-nav" id="scheduleNav">
                                                        <ul class="nav nav-tabs nav-justified">
                                                            <?php
                                                            // Define an array of days
                                                            $days = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");

                                                            // Initialize lastSelectedDay from localStorage or a default value
                                                            $lastSelectedDay = isset($_SESSION['lastSelectedDay']) ? $_SESSION['lastSelectedDay'] : 'Monday';

                                                            // Loop through each day
                                                            foreach ($days as $day) {
                                                                $tabId = strtolower($day);
                                                                $isActive = ($day === $lastSelectedDay) ? 'active' : '';

                                                                echo "<li class='nav-item'>";
                                                                echo "<a class='nav-link $isActive' data-toggle='tab' href='#slot_$tabId'>$day</a>";
                                                                echo "</li>";
                                                            }
                                                            ?>
                                                        </ul>
                                                    </div>
                                                    <!-- /Schedule Nav -->

                                                </div>
                                                <!-- /Schedule Header -->

                                                <!-- Schedule Content -->
                                                <div class="tab-content schedule-cont">
                                                    <?php
                                                    // Loop through each day
                                                    foreach ($days as $day) {
                                                        $tabId = strtolower($day);
                                                        $isActive = ($day === $lastSelectedDay) ? 'show active' : '';

                                                        echo "<div id='slot_$tabId' class='tab-pane fade $isActive'>";
                                                        echo "<h4 class='card-title d-flex justify-content-between'><span>Time Slots</span>";
                                                        echo "<a class='edit-link' data-toggle='modal' href='#add_time_slot'><i class='fa fa-plus-circle'></i> Add Slot</a></h4>";

                                                        $day = ucfirst($day);
                                                        // Query to fetch schedule for the day from the database
                                                        // TO DO change this to match the doctor id - this means when deleting a doctor, delete their timeslots 
                                                        $sql = "SELECT * FROM doctor_schedule WHERE doctor_schedule_day = '$day' AND doctor_id = '$id'";
                                                        $result = $connect->query($sql);

                                                        // Check if there are rows in the result
                                                        if ($result->num_rows > 0) {
                                                            echo "<div class='doc-times'>";
                                                            echo "<table class='table'>";
                                                            echo "<thead><tr><th class='text-center'>Start Time</th><th class='text-center'>End Time</th><th class='text-center'>Duration(mins.)</th><th class='text-center'>Action</th></tr></thead>";
                                                            echo "<tbody>";

                                                            // Loop through the result set
                                                            while ($row = $result->fetch_assoc()) {
                                                                echo "<tr>";
                                                                // Format the start time to show only hours and minutes
                                                                $startTime = date('H:i A', strtotime($row['doctor_schedule_start_time']));
                                                                echo "<td class='text-center'>$startTime</td>";

                                                                // Format the end time to show only hours and minutes
                                                                $endTime = date('H:i A', strtotime($row['doctor_schedule_end_time']));
                                                                echo "<td class='text-center'>$endTime</td>";

                                                                echo "<td class='text-center'>{$row['doctor_schedule_duration']}</td>";
                                                                echo "<td class='text-center'>";
                                                                echo "<a href='#edit_time_slot' class='btn btn-primary edit_schedule' data-toggle='modal' data-schedule-id='{$row['doctor_schedule_id']}'>Edit</a>&nbsp;";
                                                                echo "<a href='javascript:void(0)' class='btn btn-danger delete_schedule' data-schedule-id='{$row['doctor_schedule_id']}'>Delete</a>";
                                                                echo "</td>";
                                                                echo "</tr>";
                                                            }

                                                            echo "</tbody></table></div>"; // Close the table and doc-times div
                                                        } else {
                                                            echo "<p class='text-muted mb-0'>Not Available</p>";
                                                        }

                                                        echo "</div>"; // Close the tab-pane div
                                                    }
                                                    ?>
                                                </div>

                                                <!-- /Schedule Content -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
<!-- /Page Content -->

<!-- Script section -->
<script>
    $(document).ready(function() {
        // Event listener for tab click
        $('#scheduleNav a').click(function() {
            // Remove active class from all tabs
            $('#scheduleNav a').removeClass('active');

            // Add active class to the clicked tab
            $(this).addClass('active');

            // Update lastSelectedDay
            var lastSelectedDay = $(this).text();

            // Store the last selected day in sessionStorage
            sessionStorage.setItem('lastSelectedDay', lastSelectedDay);

            // Show the corresponding tab content
            $('.tab-pane').removeClass('show active');
            $('#slot_' + lastSelectedDay.toLowerCase()).addClass('show active');
        });

        // Initialize lastSelectedDay from sessionStorage or a default value
        var lastSelectedDay = sessionStorage.getItem('lastSelectedDay') || 'Monday';

        // Set the last selected day based on the stored value
        $('#scheduleNav a').removeClass('active');
        $('#scheduleNav a:contains(' + lastSelectedDay + ')').addClass('active');

        // Show the corresponding tab content
        $('.tab-pane').removeClass('show active');
        $('#slot_' + lastSelectedDay.toLowerCase()).addClass('show active');
    });
</script>

<!-- Include footer -->
<?php require_once('assets/footer.php') ?>
