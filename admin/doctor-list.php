<!-- include the header first -->
<?php require_once('assets/header.php') ?>

<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-7 col-auto">
                    <h3 class="page-title">List of Doctors</h3>
                    <!-- <ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Doctor</li>
					</ul> -->
                </div>
                <div class="col-sm-5 col">
                    <form method="post" action="assets/doctors_generate_csv.php">
                        <button type="submit" name="doctor_generate_file"
                            class="btn btn-rounded btn-primary float-right mt-2">Generate File</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="datatable table table-hover table-center mb-0">
                                <thead>
                                    <tr>
                                        <th>Doctor Name</th>
                                        <th>Services</th>
                                        <th>Reviews</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
									if (!$connect) {
										die("Connection failed: " . mysqli_connect_error());
									}

									// Fetch distinct doctor information including specialties
									$doctorQuery = "SELECT DISTINCT doctors.doctor_id, users.fname, users.lname, users.usertype, doctors.doctor_services
													FROM doctors
													JOIN users ON doctors.doctor_id = users.id
													WHERE users.usertype = 'practitioner' AND doctor_status = 'Active'";

									$doctorResult = mysqli_query($connect, $doctorQuery);

									// Check if the doctor query was successful
									if ($doctorResult) {
										if (mysqli_num_rows($doctorResult) == 0) {
											echo '<tr><td colspan="4" class="text-center">No doctors found.</td></tr>';
										} else {
											while ($doctorRow = mysqli_fetch_assoc($doctorResult)) {
												$doctorId = $doctorRow['doctor_id'];
												$fname = $doctorRow['fname'];
												$lname = $doctorRow['lname'];
												$doctorSpecialties = $doctorRow['doctor_services'];

												// Fetch doctor reviews
												$reviewQuery = "SELECT AVG(rating) AS average_rating
																FROM reviews
																WHERE doctor_id = $doctorId";

												$reviewResult = mysqli_query($connect, $reviewQuery);

												// Check if the review query was successful
												if ($reviewResult) {
													$reviewRow = mysqli_fetch_assoc($reviewResult);
													$averageRating = $reviewRow ? $reviewRow['average_rating'] : 0;

													// Display doctor information in the HTML table
													echo "<tr>";
													echo "<td>";
													echo "<h2 class='table-avatar'>";
													echo "<a href='profile.php?doctor_id=$doctorId'>$fname $lname</a>";
													echo "</h2>";
													echo "</td>";
													echo "<td>$doctorSpecialties</td>";
													echo "<td>";
													// Display stars based on the average rating
													for ($i = 1; $i <= 5; $i++) {
														if ($i <= $averageRating) {
															echo "<i class='fe fe-star text-warning'></i>";
														} else {
															echo "<i class='fe fe-star-o text-secondary'></i>";
														}
													}
													echo "</td>";
													echo "<td class='text-center'>";
													echo "<div class='actions'>";
													echo "<a class='btn btn-sm bg-info-light' href='profile.php?doctor_id=$doctorId'>";
													echo "<i class='fe fe-pencil'></i> Edit";
													echo "</a>&nbsp;";
													echo "<a class='btn btn-sm bg-danger-light delete_doctor' data-toggle='modal' href='#delete_modal' data-doctor-id='$doctorId'>";
													echo "<i class='fe fe-trash'></i> Delete";
													echo "</a>";
													echo "</div>";
													echo "</td>";
													echo "</tr>";
												} else {
													// Handle error if the review query fails
													echo '<tr><td colspan="4" class="text-center">Error fetching reviews: ' . mysqli_error($connect) . '</td></tr>';
												}
											}
										}
									} else {
										// Handle error if the doctor query fails
										echo '<tr><td colspan="4" class="text-center">Error fetching data: ' . mysqli_error($connect) . '</td></tr>';
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
<!-- /Page Wrapper -->

<!-- Delete Modal -->
<div class="modal fade" id="delete_modal" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-content p-2">
                    <h4 class="modal-title">Delete</h4>
                    <p class="mb-4">Are you sure want to delete?</p>
                    <button type="button" class="btn btn-rounded btn-primary" id="deleteDoctorButton">Yes </button>
                    <button type="button" class="btn btn-rounded btn-danger" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('.datatable')) {
        $('.datatable').DataTable().destroy();
    }
    $('.datatable').DataTable({

    });
});
</script>

<!-- /Delete Modal -->
<script>
// Attach a click event to all elements with the class 'delete_appt'
$('.delete_doctor').click(function() {
    // Get the appointment ID from the data attribute of the clicked element
    currentDoctorId = $(this).data('doctor-id');

    console.log(currentDoctorId);

    // Set the data-appointment-id attribute of the modal's 'Yes' button
    $('#deleteDoctorButton').data('doctor-id', currentDoctorId);
});

// Use class selector for the "Yes" button
$('#deleteDoctorButton').click(function() {
    // Get the appointment ID from the data-appointment-id attribute of the 'Yes' button
    currentDoctorId = $(this).data('doctor-id');

    // Make an AJAX request to delete the appointment
    $.ajax({
        url: 'assets/delete_doctor.php',
        method: 'POST',
        data: {
            currentDoctorId: currentDoctorId,
        },
        success: function(response) {
            console.log(response);

            // Handle the response from the server
            alert('Doctor deleted');

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

<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/doctor-list.php by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:51 GMT -->

</html>