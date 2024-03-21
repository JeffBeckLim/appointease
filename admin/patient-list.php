<!-- include the header first -->
<?php require_once('assets/header.php') ?>

<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-7 col-auto">
                    <h3 class="page-title">List of Patient</h3>
                    <!-- <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Patient</li>
            </ul> -->
                </div>
                <div class="col-sm-5 col">
                    <div class="mt-2">
                        <form method="post" action="assets/patients_generate_by_appt_csv.php">
                            <button type="submit" name="patients_generate_file"
                                class="btn btn-rounded btn-primary float-right" style="margin: 5px;">Generate by
                                Appointments</button>
                        </form>
                    </div>
                    <div class="mt-2">
                        <form method="post" action="assets/patients_generate_csv.php">
                            <button type="submit" name="patients_generate_file"
                                class="btn btn-rounded btn-primary float-right" style="margin: 5px;">Generate
                                File</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- /Page Header -->

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="table-responsive">
                                <table class="datatable table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Patient Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
										// Fetch patient information
										$patientQuery = "SELECT * FROM users WHERE usertype = 'patient'";
										$patientResult = mysqli_query($connect, $patientQuery);

										// Check if the query was successful
										if ($patientResult) {
											if (mysqli_num_rows($patientResult) == 0) {
												echo '<tr><td colspan="4" class="text-center">No patients found.</td></tr>';
											} else {
												while ($patientRow = mysqli_fetch_assoc($patientResult)) {
													$patientId = $patientRow['id'];
													$fname = $patientRow['fname'];
													$lname = $patientRow['lname'];
													$email = $patientRow['email'];
													$contactnum = $patientRow['contactnum'];

													// Display patient information in the HTML table
													echo "<tr>";
													echo "<td>";
													echo "<h2 class='table-avatar'>";
													echo "<a href='profile.php?patient_id=$patientId'>$fname $lname</a>";
													echo "</h2>";
													echo "</td>";
													echo "<td>$email</td>";
													echo "<td>$contactnum</td>";
													echo "<td class='text-center'>";
													echo "<div class='actions'>";
													echo "<a class='btn btn-sm bg-info-light' href='profile.php?patient_id=$patientId'>";
													echo "<i class='fe fe-pencil'></i> Edit";
													echo "</a>&nbsp;";
													echo "<a class='btn btn-sm bg-danger-light delete_patient' data-toggle='modal' href='#delete_modal' data-patient-id='$patientId'>";
													echo "<i class='fe fe-trash'></i> Delete";
													echo "</a>";
													echo "</div>";
													echo "</td>";
													echo "</tr>";
												}
											}
										} else {
											// Handle error if the query fails
											echo '<tr><td colspan="4" class="text-center">Error fetching patient data: ' . mysqli_error($connect) . '</td></tr>';
										}
										?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
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
                    <button type="button" class="btn btn-rounded btn-primary" id="deletePatientButton">Yes</button>
                    <button type="button" class="btn btn-rounded btn-danger" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Attach a click event to all elements with the class 'delete_patient'
$('.delete_patient').click(function() {
    // Get the patient ID from the data attribute of the clicked element
    currentPatientId = $(this).data('patient-id');

    console.log(currentPatientId);

    // Set the data-patient-id attribute of the modal's 'Yes' button
    $('#deletePatientButton').data('patient-id', currentPatientId);
});

// Use class selector for the "Yes" button
$('#deletePatientButton').click(function() {
    // Get the patient ID from the data-patient-id attribute of the 'Yes' button
    currentPatientId = $(this).data('patient-id');

    // Make an AJAX request to delete the patient
    $.ajax({
        url: 'assets/delete_patient.php',
        method: 'POST',
        data: {
            currentPatientId: currentPatientId,
        },
        success: function(response) {
            console.log(response);

            // Handle the response from the server
            alert('Patient deleted');

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

<script>
$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('.datatable')) {
        $('.datatable').DataTable().destroy();
    }
    $('.datatable').DataTable({

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

</html>