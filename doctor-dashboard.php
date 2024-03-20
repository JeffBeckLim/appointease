<?php require_once('assets/header.php') ?>

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
	<div class="container-fluid">
		<div class="row align-items-center">
			<div class="col-md-12 col-12">
				<nav aria-label="breadcrumb" class="page-breadcrumb">
					<!-- <ol class="breadcrumb">
						<li class="breadcrumb-item"><a href=" index-2.php?page=home">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
					</ol> -->
				</nav>
				<h2 class="breadcrumb-title">Dashboard</h2>
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
			<?php require_once('assets/doctor-sideprofile.php') ?>

			<div class="col-md-7 col-lg-8 col-xl-9">
				<div class="row">
					<div class="col-md-12">
						<div class="card dash-card">
							<div class="card-body">
								<div class="row">

									<?php

									$doctorId = $_SESSION['id'];

									$result = mysqli_query($connect, "SELECT COUNT(DISTINCT user_id) AS totalPatients
													FROM appointments
													WHERE doctor_id = '$doctorId'");
									$row = mysqli_fetch_assoc($result);

									$totalPatients = $row['totalPatients'];
									?>
									<div class="col-md-12 col-lg-4">
										<div class="dash-widget dct-border-rht">
											<div class="circle-bar">
												<img src="assets/img/icon-01.png" class="img-fluid" alt="patient">
											</div>
											<div class="dash-widget-info">
												<h6>Total Patient</h6>
												<h3><?php echo ($totalPatients !== 0) ? $totalPatients : 0 ?></h3>
												<p class="text-muted">Till Today</p>
											</div>
										</div>
									</div>

									<!-- get patients today -->
									<?php
									$today = date('Y-m-d');  // Get the current date in the format YYYY-MM-DD

									$result = mysqli_query($connect, "SELECT COUNT(DISTINCT user_id) AS totalPatientsToday
												FROM appointments
												WHERE doctor_id = '$doctorId' AND DATE(appointment_date) = '$today'");
									$row = mysqli_fetch_assoc($result);

									$totalPatientsToday = $row['totalPatientsToday'];
									?>
									<div class="col-md-12 col-lg-4">
										<div class="dash-widget dct-border-rht">
											<div class="circle-bar">
												<img src="assets/img/icon-02.png" class="img-fluid" alt="Patient">
											</div>
											<div class="dash-widget-info">
												<h6>Today Patient</h6>
												<h3><?php echo ($totalPatientsToday !== 0) ? $totalPatientsToday : 0 ?></h3>
												<p class="text-muted"><?php echo date('M d, Y') ?></p>
											</div>
										</div>
									</div>

									<!-- get the total appointments for current doctor -->
									<?php
									$result = mysqli_query($connect, "SELECT COUNT(*) AS totalAppointments
												FROM appointments
												WHERE doctor_id = '$doctorId'");

									$row = mysqli_fetch_assoc($result);

									$totalAppointments = $row['totalAppointments'];

									?>
									<div class="col-md-12 col-lg-4">
										<div class="dash-widget">
											<div class="circle-bar">
												<img src="assets/img/icon-03.png" class="img-fluid" alt="Patient">
											</div>
											<div class="dash-widget-info">
												<h6>Appointments</h6>
												<h3><?php echo ($totalAppointments !== 0) ? $totalAppointments : 0 ?></h3>
												<p class="text-muted"><?php echo date('M d, Y') ?></p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<h4 class="mb-4">Patient Appoinment</h4>
						<div class="appointment-tab">

							<!-- Appointment Tab -->
							<ul class="nav nav-tabs nav-tabs-solid nav-tabs-rounded">
								<li class="nav-item">
									<a class="nav-link active" href="#today-appointments" data-toggle="tab">Today</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="#upcoming-appointments" data-toggle="tab">Upcoming</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="#pending-appointments" data-toggle="tab">Pending</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="#all-appointments" data-toggle="tab">All Appointments</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="#appointments-by-date" data-toggle="tab">Select date</a>
								</li>
							</ul>
							<!-- /Appointment Tab -->

							<div class="tab-content">

								<!-- All Appointment Tab -->
								<div class="tab-pane" id="all-appointments">
									<div class="card card-table mb-0">
										<div class="card-body">
											<div class="table-responsive">
												<table class="table table-hover table-center mb-0">
													<thead>
														<?php
														$userId = $_SESSION['id'];

														$userAppointments = mysqli_query($connect, "SELECT a.*, u.fname, u.lname, u.id, u.idpic
																									FROM appointments a
																									JOIN users u ON a.user_id = u.id
																									WHERE a.doctor_id = '$userId'");
														if (mysqli_num_rows($userAppointments) > 0) {
															// If there are appointments, show table headers
															echo "<tr>
																	<th class='text-center'>Patient Name</th>
																	<th class='text-center'>Appt Date</th>
																	<th class='text-center'>Start Time</th>
																	<th class='text-center'>Duration (mins.)</th>
																	<th class='text-center'>Status</th>
																	<th class='text-center'>Action</th>
																	<th></th>
																</tr>";
														}
														?>
													</thead>
													<tbody>
														<?php
														if (mysqli_num_rows($userAppointments) == 0) {
															// If there are no appointments, display a message
															echo "<tr><td colspan='7'><p>No appointments found.</p></td></tr>";
														} else {
															// Loop through user appointments
															while ($row = mysqli_fetch_assoc($userAppointments)) {
																$reason = $row['appointment_reason'];
																$comments = $row['doctor_comments'];
																$patientID = $row['id'];
																$doctorSpecialties = $row["appointment_specialties"];
																echo '<tr>';
																echo '<td>';
																echo '<h2 class="table-avatar">';
																echo '<a href="patient-profile.php?page=doctor&sub=patients-profile&patient_id=' . $patientID . '"class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="' . (isset($row['idpic']) ? $row['idpic'] : 'assets/uploads/idpics/default-id.png') . '" alt="User Image"></a>';
																echo '<a href="patient-profile.php?page=doctor&sub=patients-profile&patient_id=' . $patientID . '">' . $row['fname'] . $row['lname'] . ' <span>#' . $row['id'] . '</span></a>';
																echo '</h2>';
																echo '</td>';
																echo "<td class='text-center'>" . $row['appointment_date'] . '</td>';
																echo "<td class='text-center'>" . date('H:i A', strtotime($row['appointment_start_time']))  . '</td>';
																echo "<td class='text-center'>" . $row['appointment_duration'] . '</td>';
																echo "<td class='text-center'  data-appointment-reason='$reason' data-doctor-comments='$comments'>";

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
														}
														?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								<!-- /All Appointment Tab -->

								<!-- Today Appointment Tab -->
								<div class="tab-pane show active" id="today-appointments">
									<div class="card card-table mb-0">
										<div class="card-body">
											<div class="table-responsive">
												<table class="table table-hover table-center mb-0">
													<thead>
														<?php
														$userId = $_SESSION['id'];
														$currentDate = date('Y-m-d');
														$userAppointments = mysqli_query($connect, "SELECT a.*, u.fname, u.lname, u.id, u.idpic
																									FROM appointments a
																									JOIN users u ON a.user_id = u.id
																									WHERE a.doctor_id = '$userId' 
																									AND DATE(a.appointment_date) = '$currentDate'
																									AND a.appointment_status = 'Confirmed'");

														if (mysqli_num_rows($userAppointments) > 0) {
															// If there are appointments, show table headers
															echo "<tr>
																	<th class='text-center'>Patient Name</th>
																	<th class='text-center'>Appt Date</th>
																	<th class='text-center'>Start Time</th>
																	<th class='text-center'>Duration (mins.)</th>
																	<th class='text-center'>Status</th>
																	<th class='text-center'>Action</th>
																</tr>";
														}
														?>
													</thead>
													<tbody>
														<?php
														if (mysqli_num_rows($userAppointments) == 0) {
															// If there are no appointments, display a message
															echo "<tr><td colspan='6'><p>No confirmed appointments for today.</p></td></tr>";
														} else {
															// Loop through user appointments
															while ($row = mysqli_fetch_assoc($userAppointments)) {
																// Display appointment details
																$reason = $row['appointment_reason'];
																$comments = $row['doctor_comments'];
																$doctorSpecialties = $row["appointment_specialties"];

																$patientID = $row['id'];
																echo '<tr>';
																echo '<td>';
																echo '<h2 class="table-avatar">';
																echo '<a href="patient-profile.php?page=doctor&sub=patients-profile&patient_id=' . $patientID . '"class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="' . (isset($row['idpic']) ? $row['idpic'] : 'assets/uploads/idpics/default-id.png') . '" alt="User Image"></a>';
																echo '<a href="patient-profile.php?page=doctor&sub=patients-profile&patient_id=' . $patientID . '">' . $row['fname'] . $row['lname'] . ' <span>#' . $row['id'] . '</span></a>';
																echo '</h2>';
																echo '</td>';
																echo "<td class='text-center'>" . $row['appointment_date'] . '</td>';
																echo "<td class='text-center'>" . date('H:i A', strtotime($row['appointment_start_time']))  . '</td>';
																echo "<td class='text-center'>" . $row['appointment_duration'] . '</td>';
																echo "<td class='text-center'  data-appointment-reason='$reason' data-doctor-comments='$comments'>";

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
														}
														?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								<!-- /Today Appointment Tab -->

								<!-- Upcoming Appointment Tab -->
								<div class="tab-pane" id="upcoming-appointments">
									<div class="card card-table mb-0">
										<div class="card-body">
											<div class="table-responsive">
												<table class="table table-hover table-center mb-0">
													<thead>
														<?php
														$userId = $_SESSION['id'];

														$currentDate = date('Y-m-d');

														$userAppointments = mysqli_query($connect, "SELECT a.*, u.fname, u.lname, u.id, u.idpic
																									FROM appointments a
																									JOIN users u ON a.user_id = u.id
																									WHERE a.doctor_id = '$userId' 
																										AND DATE(a.appointment_date) > '$currentDate'
																										AND a.appointment_status = 'Confirmed'
																									ORDER BY a.appointment_date ASC");
														if (mysqli_num_rows($userAppointments) > 0) {
															// If there are appointments, show table headers
															echo "<tr>
																	<th class='text-center'>Patient Name</th>
																	<th class='text-center'>Appt Date</th>
																	<th class='text-center'>Start Time</th>
																	<th class='text-center'>Duration (mins.)</th>
																	<th class='text-center'>Status</th>
																	<th class='text-center'>Action</th>
																</tr>";
														}
														?>
													</thead>
													<tbody>
														<?php
														if (mysqli_num_rows($userAppointments) == 0) {
															// If there are no appointments, display a message
															echo "<tr><td colspan='6'><p>No future confirmed appointments found.</p></td></tr>";
														} else {
															// Loop through user appointments
															while ($row = mysqli_fetch_assoc($userAppointments)) {

																$reason = $row['appointment_reason'];
																$comments = $row['doctor_comments'];
																$doctorSpecialties = $row["appointment_specialties"];

																$patientID = $row['id'];
																echo '<tr>';
																echo '<td>';
																echo '<h2 class="table-avatar">';
																echo '<a href="patient-profile.php?page=doctor&sub=patients-profile&patient_id=' . $patientID . '"class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="' . (isset($row['idpic']) ? $row['idpic'] : 'assets/uploads/idpics/default-id.png') . '" alt="User Image"></a>';
																echo '<a href="patient-profile.php?page=doctor&sub=patients-profile&patient_id=' . $patientID . '">' . $row['fname'] . $row['lname'] . ' <span>#' . $row['id'] . '</span></a>';
																echo '</h2>';
																echo '</td>';
																echo '<td class="text-center">' . date('d M Y', strtotime($row['appointment_date'])) . '</td>';
																echo '<td class="text-center">' . date('g:i A', strtotime($row['appointment_start_time'])) . '</td>';
																echo '<td class="text-center">' . $row['appointment_duration'] . '</td>';
																echo "<td class='text-center'  data-appointment-reason='$reason' data-doctor-comments='$comments'>";

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
														}
														?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								<!-- /Upcoming Appointment Tab -->

								<!-- Pending Appointments Tab -->
								<div class="tab-pane" id="pending-appointments">
									<div class="card card-table mb-0">
										<div class="card-body">
											<div class="table-responsive">
												<table class="table table-hover table-center mb-0">
													<thead>
														<?php
														$userId = $_SESSION['id'];

														$currentDate = date('Y-m-d');

														$userAppointments = mysqli_query($connect, "SELECT a.*, u.fname, u.lname, u.id, u.idpic
																									FROM appointments a
																									JOIN users u ON a.user_id = u.id
																									WHERE a.doctor_id = '$userId' 
																										AND DATE(a.appointment_date) > '$currentDate'
																										AND a.appointment_status = 'Pending'
																									ORDER BY a.appointment_date ASC");
														if (mysqli_num_rows($userAppointments) > 0) {
															// If there are appointments, show table headers
															echo "<tr>
																	<th class='text-center'>Patient Name</th>
																	<th class='text-center'>Appt Date</th>
																	<th class='text-center'>Start Time</th>
																	<th class='text-center'>Duration (mins.)</th>
																	<th class='text-center'>Status</th>
																	<th class='text-center'>Action</th>
																</tr>";
														}
														?>
													</thead>
													<tbody>
														<?php
														if (mysqli_num_rows($userAppointments) == 0) {
															// If there are no appointments, display a message
															echo "<tr><td colspan='6'><p>No future pending appointments found.</p></td></tr>";
														} else {
															// Loop through user appointments
															while ($row = mysqli_fetch_assoc($userAppointments)) {
																$reason = $row['appointment_reason'];
																$comments = $row['doctor_comments'];
																$doctorSpecialties = $row["appointment_specialties"];

																$patientID = $row['id'];
																echo '<tr>';
																echo '<td>';
																echo '<h2 class="table-avatar">';
																echo '<a href="patient-profile.php?page=doctor&sub=patients-profile&patient_id=' . $patientID . '"class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="' . (isset($row['idpic']) ? $row['idpic'] : 'assets/uploads/idpics/default-id.png') . '" alt="User Image"></a>';
																echo '<a href="patient-profile.php?page=doctor&sub=patients-profile&patient_id=' . $patientID . '">' . $row['fname'] . $row['lname'] . ' <span>#' . $row['id'] . '</span></a>';
																echo '</h2>';
																echo '</td>';
																echo "<td class='text-center'>" . $row['appointment_date'] . '</td>';
																echo "<td class='text-center'>" . date('H:i A', strtotime($row['appointment_start_time']))  . '</td>';
																echo "<td class='text-center'>" . $row['appointment_duration'] . '</td>';
																echo "<td class='text-center'  data-appointment-reason='$reason' data-doctor-comments='$comments'>";
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
														}
														?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								<!-- /Pending Appointment Tab -->

								<!-- Get Date Tab -->
								<div class="tab-pane" id="appointments-by-date">
									<div class="card card-table mb-0">
										<div class="card-body">
											<div class="table-responsive">
												<div class="container mt-3 mb-3">
													<div class="row justify-content-left">
														<div class="col-md-3">
															<form id="appointmentForm" method="GET">
																<div class="form-group">
																	<label for="appointment_date">Select Date:</label>
																	<input type="date" class="form-control" id="appointment_date" name="appointment_date">
																	<input type="hidden" id="doctor_id" value="<?php echo $_SESSION['id']; ?>">
																</div>
																<button type="button" class="btn btn-primary" id="getAppointments">Get Appointments</button>
																<!-- Button for printing PDF -->
																<button type="button" class="btn btn-primary" id="printPDF" style="margin-top: 3px; display: none;">Print PDF</button>
															</form>
														</div>
													</div>
												</div>
												<table class="table table-hover table-center mb-0" id="appointmentsTable">
													<!-- Automatically generated -->
												</table>
											</div>
										</div>
									</div>
								</div>
								<!-- /Get Date Tab -->
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>

	</div>

</div>
<!-- /Page Content -->

<!-- include footer -->
<?php require_once('assets/footer.php') ?>