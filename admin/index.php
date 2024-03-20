<!-- include the header first -->
<?php require_once('assets/header.php') ?>

<!-- Page Wrapper -->
<div class="page-wrapper">

	<div class="content container-fluid">

		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col-sm-12">
					<?php echo "<h3>Welcome " . $_SESSION['username'] . "!</h3>"; ?>
					<!-- <ul class="breadcrumb">
						<li class="breadcrumb-item active">Dashboard</li>
					</ul> -->
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		<?php
		// Fetch counts from the database
		$doctorCountQuery = "SELECT COUNT(*) AS totalDoctors FROM doctors WHERE doctor_status = 'Active'";
		$patientCountQuery = "SELECT COUNT(*) AS totalPatients FROM users WHERE usertype = 'patient'";
		$appointmentCountQuery = "SELECT COUNT(*) AS totalAppointments FROM appointments";

		$doctorResult = mysqli_query($connect, $doctorCountQuery);
		$patientResult = mysqli_query($connect, $patientCountQuery);
		$appointmentResult = mysqli_query($connect, $appointmentCountQuery);

		// Check if queries were successful
		if ($doctorResult && $patientResult && $appointmentResult) {
			$doctorCount = mysqli_fetch_assoc($doctorResult)['totalDoctors'];
			$patientCount = mysqli_fetch_assoc($patientResult)['totalPatients'];
			$appointmentCount = mysqli_fetch_assoc($appointmentResult)['totalAppointments'];
		} else {
			// Handle error if queries fail
			$doctorCount = $patientCount = $appointmentCount = "Error";
		}

		?>
		<div class="row">
			<div class="col-xl-4 col-sm-6 col-12">
				<div class="card">
					<div class="card-body">
						<div class="dash-widget-header">
							<span class="dash-widget-icon text-primary border-primary">
								<i class="fas fa-stethoscope"></i>
							</span>
							<div class="dash-count">
								<h3><?php echo $doctorCount; ?></h3>
							</div>
						</div>
						<div class="dash-widget-info">
							<h6 class="text-muted">Doctors</h6>
							<div class="progress progress-sm">
								<div class="progress-bar bg-primary w-50"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl-4 col-sm-6 col-12">
				<div class="card">
					<div class="card-body">
						<div class="dash-widget-header">
							<span class="dash-widget-icon text-success">
								<i class="fas fa-bed"></i>
							</span>
							<div class="dash-count">
								<h3><?php echo $patientCount; ?></h3>
							</div>
						</div>
						<div class="dash-widget-info">
							<h6 class="text-muted">Patients</h6>
							<div class="progress progress-sm">
								<div class="progress-bar bg-success w-50"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl-4 col-sm-6 col-12">
				<div class="card">
					<div class="card-body">
						<div class="dash-widget-header">
							<span class="dash-widget-icon text-danger border-danger">
								<i class="fas fa-calendar"></i>
							</span>
							<div class="dash-count">
								<h3><?php echo $appointmentCount; ?></h3>
							</div>
						</div>
						<div class="dash-widget-info">
							<h6 class="text-muted">Appointments</h6>
							<div class="progress progress-sm">
								<div class="progress-bar bg-danger w-50"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 d-flex">

				<!-- Recent Orders -->
				<div class="card card-table flex-fill">
					<div class="card-header">
						<h4 class="card-title">Doctors List</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-hover table-center mb-0">
								<thead>
									<tr>
										<th>Doctor Name</th>
										<th>Specialties</th>
										<th>Reviews</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if (!$connect) {
										die("Connection failed: " . mysqli_connect_error());
									}

									// Fetch doctor information and their average ratings
									$combinedQuery = "SELECT doctors.*, users.fname, users.lname, AVG(reviews.rating) AS average_rating
                FROM doctors
                JOIN users ON doctors.doctor_id = users.id
                LEFT JOIN reviews ON doctors.doctor_id = reviews.doctor_id
                WHERE doctor_status = 'Active'
                GROUP BY doctors.doctor_id";

									$result = mysqli_query($connect, $combinedQuery);

									// Check if the query was successful
									if ($result) {
										if (mysqli_num_rows($result) == 0) {
											echo '<tr><td colspan="3" class="text-center">No doctors found.</td></tr>';
										} else {
											$counter = 0;
											while ($counter <= 5 && ($row = mysqli_fetch_assoc($result))) {
												$doctorId = $row['doctor_id'];
												$fname = $row['fname'];
												$lname = $row['lname'];
												$doctorSpecialties = $row['doctor_specialties'];
												$averageRating = $row['average_rating'] ?: 0; // Use 0 if no reviews are found

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
												echo "</tr>";
												$counter++;
											}
										}
									} else {
										// Handle error if the query fails
										echo '<tr><td colspan="3" class="text-center">Error fetching data: ' . mysqli_error($connect) . '</td></tr>';
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!-- /Recent Orders -->

			</div>
			<div class="col-md-6 d-flex">

				<!-- Feed Activity -->
				<div class="card  card-table flex-fill">
					<div class="card-header">
						<h4 class="card-title">Patients List</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-hover table-center mb-0">
								<thead>
									<tr>
										<th>Patient Name</th>
										<th>Email</th>
										<th>Phone</th>
									</tr>
								</thead>
								<tbody>
									<?php
									// Fetch patient information
									$patientQuery = "SELECT * FROM users WHERE usertype = 'patient' LIMIT 5";
									$patientResult = mysqli_query($connect, $patientQuery);

									// Check if the query was successful
									if ($patientResult) {
										if (mysqli_num_rows($patientResult) == 0) {
											echo '<tr><td colspan="3" class="text-center">No patients found.</td></tr>';
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
												echo "</tr>";
											}
										}
									} else {
										// Handle error if the query fails
										echo '<tr><td colspan="3" class="text-center">Error fetching patient data: ' . mysqli_error($connect) . '</td></tr>';
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!-- /Feed Activity -->

			</div>
		</div>
		<div class="row">
			<div class="col-md-12">

				<!-- Recent Orders -->
				<div class="card card-table">
					<div class="card-header">
						<h4 class="card-title">Recent Appointments</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-hover table-center mb-0">
								<thead>
									<tr>
										<th>Doctor Name</th>
										<th>Specialties</th>
										<th>Patient Name</th>
										<th>Appt Date</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$appointmentQuery = "SELECT a.*, d.doctor_specialties, du.fname AS doctor_fname, du.lname AS doctor_lname, u.fname AS patient_fname, u.lname AS patient_lname
														FROM appointments a
														LEFT JOIN doctors d ON a.doctor_id = d.doctor_id
														LEFT JOIN users AS du ON d.doctor_id = du.id
														LEFT JOIN users AS u ON a.user_id = u.id
														ORDER BY a.appointment_date DESC
														LIMIT 10";

									$appointmentResult = mysqli_query($connect, $appointmentQuery);

									// Check if the query was successful
									if ($appointmentResult) {
										if (mysqli_num_rows($appointmentResult) == 0) {
											echo '<tr><td colspan="5" class="text-center">No appointments found.</td></tr>';
										} else {
											while ($appointmentRow = mysqli_fetch_assoc($appointmentResult)) {
												$doctorName = $appointmentRow['doctor_fname'] . ' ' . $appointmentRow['doctor_lname'];
												$specialties = $appointmentRow['doctor_specialties'];
												$patientName = $appointmentRow['patient_fname'] . ' ' . $appointmentRow['patient_lname'];
												$appointmentDate = $appointmentRow['appointment_date'];
												$status = $appointmentRow['appointment_status'];

												// Display appointment information in the HTML table
												echo "<tr>";
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
												echo "<td class='text-center'>";
												// Display different badges based on appointment status
												$statusClass = '';
												switch ($status) {
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

												echo '<span class="badge badge-pill ' . $statusClass . '">' . ucfirst($status) . '</span>';
												echo '</td>';
												echo "</tr>";
											}
										}
									} else {
										// Handle error if the query fails
										echo '<tr><td colspan="5" class="text-center">Error fetching appointment data: ' . mysqli_error($connect) . '</td></tr>';
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

</div>
<!-- /Main Wrapper -->

<!-- jQuery -->
<script src="assets/js/jquery-3.2.1.min.js"></script>

<!-- Bootstrap Core JS -->
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

<!-- Slimscroll JS -->
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<script src="assets/plugins/raphael/raphael.min.js"></script>
<script src="assets/plugins/morris/morris.min.js"></script>
<script src="assets/js/chart.morris.js"></script>

<!-- Custom JS -->
<script src="assets/js/script.js"></script>

</body>

<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/index.php by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:34 GMT -->

</html>