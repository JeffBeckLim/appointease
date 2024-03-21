<!-- include the header first -->
<?php require_once('assets/header.php') ?>

<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6 col-auto">
                    <h3 class="page-title">Appointments</h3>
                    <!-- <ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Appointments</li>
					</ul> -->
                </div>
                <div class="col-sm-6 col-auto">
                    <div>
                        <form method="post" action="assets/appt_generate_csv.php">
                            <button type="submit" name="patients_generate_file"
                                class="btn btn-rounded btn-primary float-right" style="margin: 2px;">Generate
                                File</button>
                        </form>
                    </div>
                    <div>
                        <form method="post" action="assets/appts_generate_by_doctor_csv.php">
                            <button type="submit" name="patients_generate_file"
                                class="btn btn-rounded btn-primary float-right" style="margin: 2px;">Generate by
                                Doctor</button>
                        </form>
                    </div>
                    <div>
                        <a href="#choose_date" data-toggle="modal" class="btn btn-rounded btn-primary float-right"
                            style="margin: 2px;">Generate by Date</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- /Page Header -->
        <div class="row">
            <div class="col-md-12">

                <!-- Recent Orders -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="datatable table table-hover table-center mb-0">
                                <thead>
                                    <tr>
                                        <th>Doctor Name</th>
                                        <th>Consultation Type</th>
                                        <th>Patient Name</th>
                                        <th>Appt Date</th>
                                        <th>Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
									$appointmentQuery = "SELECT a.*, du.fname AS doctor_fname, du.lname AS doctor_lname, u.fname AS patient_fname, u.lname AS patient_lname
														FROM appointments a
														LEFT JOIN doctors d ON a.doctor_id = d.doctor_id
														LEFT JOIN users AS du ON d.doctor_id = du.id
														LEFT JOIN users AS u ON a.user_id = u.id
														ORDER BY a.appointment_date DESC";

									$appointmentResult = mysqli_query($connect, $appointmentQuery);

									// Check if the query was successful
									if ($appointmentResult) {
										if (mysqli_num_rows($appointmentResult) == 0) {
											echo '<tr><td colspan="6" class="text-center">No appointments found.</td></tr>';
										} else {
											while ($appointmentRow = mysqli_fetch_assoc($appointmentResult)) {
												$doctorName = $appointmentRow['doctor_fname'] . ' ' . $appointmentRow['doctor_lname'];
												$specialties = $appointmentRow['appointment_specialties'];
												$patientName = $appointmentRow['patient_fname'] . ' ' . $appointmentRow['patient_lname'];
												$appointmentDate = $appointmentRow['appointment_date'];
												$status = $appointmentRow['appointment_status'];

												// Display appointment information in the HTML table
												echo "<tr id='appointmentRow{$appointmentRow['appointment_id']}'>";
												echo "<td>";
												echo "<h2 class='table-avatar'>";
												echo "<a href='profile.php?doctor_id={$appointmentRow['doctor_id']}'>$doctorName</a>";
												echo "</h2>";
												echo "</td>";
												echo "<td>$specialties</td>";
												echo "<td>";
												echo "<h2 class='table-avatar'>";
												echo "<a href='profile.php?patient_id={$appointmentRow['user_id']}'>$patientName</a>";
												echo "</h2>";
												echo "</td>";
												echo "<td>$appointmentDate</td>";
												echo "<td>";
												// Display different badges based on appointment status
												$statusClass = '';
												switch ($appointmentRow['appointment_status']) {
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

												echo '<span class="badge badge-pill ' . $statusClass . '">' . ucfirst($appointmentRow['appointment_status']) . '</span>';
												echo '</td>';
												echo "<td class='text-center'>";
												echo "<div class='actions'>";
												echo "<a class='btn btn-sm bg-danger-light delete_appt' data-toggle='modal' href='#delete_modal' data-appointment-id='{$appointmentRow['appointment_id']}'>";
												echo "<i class='fe fe-trash'></i> Delete";
												echo "</a>";
												echo "</div>";
												echo "</td>";
												echo "</tr>";
											}
										}
									} else {
										// Handle error if the query fails
										echo '<tr><td colspan="6" class="text-center">Error fetching appointment data: ' . mysqli_error($connect) . '</td></tr>';
									}
									?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /Recent Orders -->

            </div>
        </div>
    </div>
</div>
<!-- /Page Wrapper -->

<!-- Delete Modal -->
<div class="modal fade" id="delete_modal" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-content p-2">
                    <h4 class="modal-title">Delete</h4>
                    <p class="mb-4">Are you sure want to delete?</p>
                    <!-- Add an ID to the Yes button to easily target it in JavaScript -->
                    <button type="button" class="btn btn-rounded btn-primary" id="deleteAppointmentButton">Yes</button>
                    <button type="button" class="btn btn-rounded btn-danger" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {

    $(document).ready(function() {
        // Destroy existing DataTable instance if it exists
        if ($.fn.DataTable.isDataTable('.datatable')) {
            $('.datatable').DataTable().destroy();
        }

        // Initialize DataTables
        $('.datatable').DataTable({
            "order": [
                [3, 'desc']
            ] // Order by date
        });
    });


});
</script>


<script>
// Attach a click event to all elements with the class 'delete_appt'
$('.delete_appt').click(function() {
    // Get the appointment ID from the data attribute of the clicked element
    currentAppointmentId = $(this).data('appointment-id');

    console.log(currentAppointmentId);

    // Set the data-appointment-id attribute of the modal's 'Yes' button
    $('#deleteAppointmentButton').data('appointment-id', currentAppointmentId);
});

// Use class selector for the "Yes" button
$('#deleteAppointmentButton').click(function() {
    // Get the appointment ID from the data-appointment-id attribute of the 'Yes' button
    currentAppointmentId = $(this).data('appointment-id');

    // Make an AJAX request to delete the appointment
    $.ajax({
        url: 'assets/delete_appointment.php',
        method: 'POST',
        data: {
            currentAppointmentId: currentAppointmentId,
        },
        success: function(response) {
            console.log(response);

            // Handle the response from the server
            alert('Appointment deleted');

            // Reload the page after successful deletion
            location.reload();
        },
        error: function(error) {
            // Handle errors
            console.error('Error deleting:', error);
        }
    });
});
</script>

<!-- Choose date Modal -->
<div class="modal fade" id="choose_date" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-content p-2">
                    <h4 class="modal-title">Choose Start and End Dates</h4>
                    <form id="generateCsvForm" method="post" action="assets/appts_generate_by_date_csv.php">
                        <div class="form-group">
                            <label for="start_date">Start Date:</label>
                            <input type="date" class="form-control" id="start_date" name="start_date">
                        </div>
                        <div class="form-group">
                            <label for="end_date">End Date:</label>
                            <input type="date" class="form-control" id="end_date" name="end_date">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-rounded btn-primary" id="generateCsvButton">Generate
                                CSV</button>
                            <button type="button" class="btn btn-rounded btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Choose date Modal -->

<script>
// Event listener for the form submission
$('#generateCsvForm').submit(function(e) {
    e.preventDefault(); // Prevent the default form submission

    // Retrieve the selected start and end dates from the datepicker inputs
    var startDate = $('#start_date').val();
    var endDate = $('#end_date').val();

    // Make an AJAX request to generate the CSV file
    $.ajax({
        url: 'assets/appts_generate_by_date_csv.php',
        method: 'POST',
        data: {
            startDate: startDate,
            endDate: endDate
        },
        success: function(response) {
            // Check if the response contains any data
            if (response.trim() !== '') {
                // Create a blob object from the response data
                var blob = new Blob([response], {
                    type: 'text/csv'
                });

                // Create a temporary URL for the blob object
                var url = window.URL.createObjectURL(blob);

                // Create a download link
                var downloadLink = document.createElement('a');
                downloadLink.href = url;
                downloadLink.download = 'appointments_by_date_export.csv';
                downloadLink.click();

                // Release the allocated memory
                window.URL.revokeObjectURL(url);

                // Close the modal
                $('#generateCsvForm').modal('hide');

                window.location.reload();
            } else {
                console.error('Empty response received.');
            }
        },

        error: function(xhr, status, error) {
            console.error('Error generating CSV:', error);
        }
    });
});
</script>


</div>
<!-- /Main Wrapper -->

<!-- jQuery -->
<script src="assets/js/jquery-3.2.1.min.js"></script>

<!-- Bootstrap Core JS -->
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

<!-- Slimscroll JS -->
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Datatables JS -->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/datatables.min.js"></script>

<!-- Custom JS -->
<script src="assets/js/script.js"></script>

</body>

<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/appointment-list.php by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:49 GMT -->

</html>