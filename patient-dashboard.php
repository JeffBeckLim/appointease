<!-- include header first -->
<?php require_once('assets/header.php') ?>

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
	<div class="container-fluid">
		<div class="row align-items-center">
			<div class="col-md-12 col-12">
				<nav aria-label="breadcrumb" class="page-breadcrumb">
					<!-- removed as per client's request -->
					<!-- <ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="index-2.php?page=home">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
					</ol> -->
				</nav>
				<h2 id="breadcrumb-title" class="breadcrumb-title">Appointments</h2>
			</div>
		</div>
	</div>
</div>
<!-- /Breadcrumb -->

<!-- Page Content -->
<div class="content">
	<div class="container-fluid">

		<div class="row">

			<!-- include the side profile -->
			<?php require_once('assets/patient-sideprofile.php') ?>

			<div class="col-md-7 col-lg-8 col-xl-9">
				<div class="card">
					<div class="card-body pt-0">

						<!-- Tab Menu -->
						<nav class="user-tabs mb-4">
							<ul class="nav nav-tabs nav-tabs-bottom nav-justified">
								<li class="nav-item">
									<a class="nav-link active" href="#pat_appointments" data-toggle="tab">Appointments</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="#pat_medical_records" data-toggle="tab"><span class="med-records">Medical Records</span></a>
								</li>
							</ul>
						</nav>
						<!-- /Tab Menu -->

						<!-- Tab Content -->
						<div class="tab-content pt-0">
							<!-- Appointment Tab -->
							<div id="pat_appointments" class="tab-pane fade show active">
								<div class="card card-table mb-0">
									<div class="card-body">
										<div class="table-responsive">
											<table class="table table-hover table-center mb-0">
												<thead>
													<tr>
														<th class='text-center'>Doctor</th>
														<th class='text-center'>Appt Date</th>
														<th class='text-center'>Start Time</th>
														<th class='text-center'>Duration</th>
														<th class='text-center'>Status</th>
														<th class='text-center'>Action</th>
													</tr>
												</thead>
												<tbody>
													<?php
													$userId = $_SESSION['id'];

													$userAppointments = mysqli_query($connect, "SELECT a.*, u.fname AS patient_fname, u.lname AS patient_lname, u.id AS patient_id, u.idpic AS patient_idpic, 
																								d.doctor_id, du.fname AS doctor_fname, du.lname AS doctor_lname, du.idpic AS doctor_idpic, d.doctor_specialties AS doctor_specialties
																								FROM appointments a
																								JOIN users u ON a.user_id = u.id
																								JOIN doctors d ON a.doctor_id = d.doctor_id
																								JOIN users du ON d.doctor_id = du.id
																								WHERE a.user_id = '$userId'");
													if (mysqli_num_rows($userAppointments) == 0) {
														echo '<tr><td colspan="6" class="text-center">No appointments found for this user.</td></tr>';
													} else {

														// Loop through user appointments
														while ($row = mysqli_fetch_assoc($userAppointments)) {

															$reason = $row['appointment_reason'];
															echo '<tr>';
															echo '<td>';
															echo '<h2 class="table-avatar">';
															echo '<a href="doctor-profile.php?page=patients&sub=docprof&doctor_id=' . $row['doctor_id'] . '" class="avatar avatar-sm mr-2">';
															echo '<img class="avatar-img rounded-circle" src="' . (isset($row['doctor_idpic']) ? $row['doctor_idpic'] : 'assets/uploads/idpics/default-id.png') . '" alt="User Image">';
															echo '</a>';
															echo '<a href="doctor-profile.php?page=patients&sub=docprof&doctor_id=' . $row['doctor_id'] . '">' . $row['doctor_fname'] . ' ' . $row['doctor_lname'] . '</a>';
															echo '</h2>';
															echo '</td>';
															echo "<td class='text-center'>" . $row['appointment_date'] . '</td>';
															echo "<td class='text-center'>" . date('H:i A', strtotime($row['appointment_start_time']))  . '</td>';
															echo "<td class='text-center'>" . $row['appointment_duration'] . '</td>';
															echo "<td class='text-center' data-appointment-reason='$reason'>";
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
															// Check if the status is 'Pending' to enable the Cancel button
															if ($row['appointment_status'] == 'Pending') {
																echo "<a href='#cancelAppointmentModal' class='btn btn-sm cancel_booking bg-danger-light' data-toggle='modal' data-appointment-id='{$row['appointment_id']}' id='cancelButton'>";
																echo '<i class="fas fa-times"></i> Cancel';
																echo '</a>';
															}
															echo '</div>';
															echo '</td>';
															echo '</tr>';
														}
													}

													?>

												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>

							<!-- /Appointment Tab -->

							<!-- Medical Records Tab -->
							<div id="pat_medical_records" class="tab-pane fade">
								<div class="card card-table mb-0">
									<div class="card-body">
										<div class="table-responsive">
											<table class="table table-hover table-center mb-0">
												<thead>
													<tr>
														<th class='text-center'>Created</th>
														<th class='text-center'>Date </th>
														<th class='text-center'>Description</th>
														<th class='text-center'>Attachment</th>
														<th class='text-center'>Action</th>
													</tr>
												</thead>
												<tbody>
													<?php
													$userId = $_SESSION['id'];

													$patientRecords = mysqli_query($connect, "SELECT r.*, d.doctor_id, du.fname AS doctor_fname, du.lname AS doctor_lname, du.idpic AS doctor_idpic, d.doctor_specialties, du.id
																							FROM records r
																							JOIN doctors d ON r.doctor_id = d.doctor_id
																							JOIN users du ON d.doctor_id = du.id
																							WHERE r.user_id = '$userId'");

													if (mysqli_num_rows($patientRecords) == 0) {
														echo '<tr><td colspan="5" class="text-center">No records found for this user.</td></tr>';
													} else {
														// Loop through patient records
														while ($row = mysqli_fetch_assoc($patientRecords)) {
															echo '<tr>';
															echo "<td>";
															echo '<h2 class="table-avatar">';
															echo '<a href="doctor-profile.php?page=patients&sub=docprof&doctor_id=' . $row['doctor_id'] . '" class="avatar avatar-sm mr-2">';
															echo '<img class="avatar-img rounded-circle" src="' . (isset($row['doctor_idpic']) ? $row['doctor_idpic'] : 'assets/uploads/idpics/default-id.png') . '" alt="User Image">';
															echo '</a>';
															echo '<a href="doctor-profile.php?page=patients&sub=docprof&doctor_id=' . $row['doctor_id'] . '">' . $row['doctor_fname'] . ' ' . $row['doctor_lname'] . '</a>';
															echo '</h2>';
															echo "</td>";
															echo "<td class='text-center'>{$row['record_date']}</td>";
															echo "<td class='text-center'>{$row['description']}</td>";
															echo "<td class='text-center'><a href='assets/{$row['attachment_path']}'  target='_blank'>{$row['attachment_title']}</a></td>";
															echo "<td class='text-center'>";
															echo '<div class="table-action">';
															echo "<a href='assets/{$row['attachment_path']}' target='_blank' class='btn btn-sm bg-info-light mr-2'>";
															echo '<i class="far fa-eye"></i> View';
															echo '</a>';
															echo "<a href='assets/{$row['attachment_path']}' download class='btn btn-sm bg-primary-light'>";
															echo '<i class="fas fa-download"></i> Download';
															echo '</a>';
															echo '</div>';
															echo '</td>';
															echo '</tr>';
														}
													}
													?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
							<!-- /Medical Records Tab -->

						</div>
						<!-- Tab Content -->

					</div>
				</div>
			</div>
		</div>

	</div>

</div>


<!-- dynamically change the breadcrumb title -->
<script>
    $(document).ready(function(){
        // Update breadcrumb title based on the selected tab
        $('.nav-tabs a').on('shown.bs.tab', function (e) {
            var tabTitle = $(e.target).text();
            $('#breadcrumb-title').text(tabTitle);
        });
    });
</script>

<!-- /Page Content -->

<!-- include footer -->
<?php require_once('assets/footer.php') ?>