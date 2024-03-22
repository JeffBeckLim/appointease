<!-- include the header first -->
<?php require_once('assets/header.php') ?>

<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-7 col-auto">
                    <h3 class="page-title">List of Retired Doctors</h3>
                    <!-- <ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Doctor</li>
					</ul> -->
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
                                        <th>Status</th>
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
                                                    WHERE users.usertype = 'patient' AND doctors.doctor_status = 'Retired'";

                                    $doctorResult = mysqli_query($connect, $doctorQuery);
                                    
									// Check if the doctor query was successful
									if ($doctorResult) {
                                        if (mysqli_num_rows($doctorResult) == 0) {
                                            echo '<tr><td colspan="3" class="text-center">No retired doctors found.</td></tr>';
                                        } else {
                                            while ($doctorRow = mysqli_fetch_assoc($doctorResult)) {
                                                $doctorId = $doctorRow['doctor_id'];
                                                $fname = $doctorRow['fname'];
                                                $lname = $doctorRow['lname'];
                                                $doctorSpecialties = $doctorRow['doctor_services'];
                                    
                                                // Output table row for each retired doctor
                                                echo "<tr>";
                                                echo "<td><a href='profile.php?doctor_id=$doctorId'>$fname $lname</a></td>";
                                                echo "<td>$doctorSpecialties</td>";
                                                echo "<td class='text-danger'>Retired</td>";
                                                echo "</tr>";
                                            }
                                        }
                                    } else {
                                        // Handle database query error
                                        echo '<tr><td colspan="3" class="text-center">Error fetching retired doctors: ' . mysqli_error($connect) . '</td></tr>';
                                    }
                                    
                                    // Close database connection
                                    mysqli_close($connect);
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

<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/doctor-list.php by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:51 GMT -->

</html>