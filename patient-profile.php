<!-- Include databse connection and other checking of session variables -->
<?php require_once('assets/header.php'); ?>

<?php if (isset($_GET['patient_id'])) :
	$patientId = $_GET['patient_id'];

	// Query to retrieve detailed patient information based on the patient ID
	$sql = "SELECT * FROM users WHERE id = $patientId";

	$result = $connect->query($sql);

	// Check if there are any results
	if ($result->num_rows > 0) :
		$row = $result->fetch_assoc();

		$firstName = $row["fname"];
		$lastName = $row["lname"];
		$email = $row["email"];
		$age = calculateAge($row['birthday']);
		$contactnum = $row["contactnum"];
		$gender = $row["gender"];

		// Set the dynamic image source based on idpic column
		$imgSrc = ($row["idpic"] !== null) ? $row["idpic"] : 'assets/uploads/idpics/default-id.png';

?>

		<!-- Breadcrumb -->
		<div class="breadcrumb-bar">
			<div class="container-fluid">
				<div class="row align-items-center">
					<div class="col-md-12 col-12">
						<!-- <nav aria-label="breadcrumb" class="page-breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href=" index-2.php?page=home">Home</a></li>
								<li class="breadcrumb-item active" aria-current="page">Profile</li>
							</ol>
						</nav> -->
						<h2 class="breadcrumb-title">Profile</h2>
					</div>
				</div>
			</div>
		</div>
		<!-- /Breadcrumb -->

		<!-- Page Content -->
		<div class="content">
			<div class="container-fluid">

				<div class="row">
					<div class="col-md-5 col-lg-4 col-xl-3 theiaStickySidebar dct-dashbd-lft">


						<!-- Profile Widget -->
						<div class="card widget-profile pat-widget-profile">
							<div class="card-body">
								<div class="pro-widget-content">
									<div class="profile-info-widget">
										<a href="#" class="booking-doc-img">
											<img src="<?php echo $imgSrc; ?>" alt="User Image" class="booking-doc-img">
										</a>
										<div class="profile-det-info">
											<h3><?php echo $firstName . ' ' . $lastName; ?></h3>
											<!-- Add more patient details   -->
										</div>
									</div>
								</div>
								<div class="patient-info">
									<ul>
										<li>Phone <span><?php echo $contactnum ?></span></li>
										<li>Email <span><?php echo $email; ?></span></li>
										<li>Age <span><?php echo $age; ?> Years Old, <?php echo $gender; ?></span></li>
									</ul>
								</div>
							</div>
						</div>
						<!-- /Profile Widget -->

						<!-- Last Booking -->
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">Last Bookings</h4>
							</div>
							<ul class="list-group list-group-flush">

								<?php

								// Query to get the last two appointments for a patient with doctor details
								$query = "SELECT a.*, u.fname AS doctor_fname, u.lname AS doctor_lname, u.idpic AS doctor_idpic
										FROM appointments a
										JOIN users u ON a.doctor_id = u.id
										WHERE a.user_id = '$patientId' AND a.appointment_status = 'Confirmed'
										ORDER BY a.appointment_date DESC
										LIMIT 2";

								$result = mysqli_query($connect, $query);

								// Loop through the appointments
								while ($row = mysqli_fetch_assoc($result)) {
									$doctorName = "Dr. " . $row['doctor_fname'] . " " . $row['doctor_lname'];
									$appointmentStartTime = date('H:i A', strtotime($row['appointment_start_time']));
									$appointmentDate = date('d M Y', strtotime($row['appointment_date']));
									$doctorImage = ($row['doctor_idpic'] !== null) ? $row['doctor_idpic'] : 'assets/img/doctors/default-doctor.jpg';

									echo '<li class="list-group-item">
											<div class="media align-items-center">
												<div class="mr-3">
													<img alt="Image placeholder" src="' . $doctorImage . '" class="avatar rounded-circle">
												</div>
												<div class="media-body">
													<h5 class="d-block mb-0">' . $doctorName . '</h5>
													<span class="d-block text-sm text-muted">' . $appointmentDate . ' ' . $appointmentStartTime . '</span>
												</div>
											</div>
										</li>';
								}
								?>
							</ul>
						</div>
						<!-- /Last Booking -->

					</div>

					<div class="col-md-7 col-lg-8 col-xl-9 dct-appoinment">
						<div class="card">
							<div class="card-body pt-0">
								<div class="user-tabs">
									<ul class="nav nav-tabs nav-tabs-bottom nav-justified flex-wrap">
										<li class="nav-item">
											<a class="nav-link active" href="#pat_info" data-toggle="tab">Patient Information</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" href="#pat_appointments" data-toggle="tab">Appointments</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" href="#medical" data-toggle="tab"><span class="med-records">Medical Records</span></a>
										</li>
									</ul>
								</div>
								<div class="tab-content">
									<!-- Patient Information Tab -->
									<div id="pat_info" class="tab-pane fade show active">
										<div class="card card-table mb-0">
											<div class="card-body">
												<?php
												$sql = "SELECT * FROM patient_form WHERE user_id = ?";
												$stmt = mysqli_prepare($connect, $sql);
												mysqli_stmt_bind_param($stmt, "i", $patientId);
												mysqli_stmt_execute($stmt);
												$result = mysqli_stmt_get_result($stmt);

												if (mysqli_num_rows($result) > 0) {
													// Data found, display it
													$row = mysqli_fetch_assoc($result);
												?>
													<div class="table-responsive">
														<table class="table table-hover table-center mb-0">
															<thead>
																<tr>
																	<th>Title</th>
																	<th>Data</th>
																</tr>
															</thead>
															<tbody>

																<tr>
																	<td><strong>Health Description:</strong></td>
																	<td><?php echo !empty($row['health_description']) ? htmlspecialchars($row['health_description']) : '-'; ?></td>
																</tr>
																<tr>
																	<td><strong>Taking a prescription:</strong></td>
																	<td><?php echo !empty($row['prescription']) ? htmlspecialchars($row['prescription']) : '-'; ?></td>
																</tr>
																<?php
																$medication_names = isset($row['medication_name']) ? explode(', ', $row['medication_name']) : [];
																$pill_amounts = isset($row['pill_amount']) ? explode(', ', $row['pill_amount']) : [];
																$pill_doses = isset($row['pill_doses']) ? explode(', ', $row['pill_doses']) : [];

																$rowCount = max(count($medication_names), count($pill_amounts), count($pill_doses));

																for ($i = 0; $i < $rowCount; $i++) {
																	$medication_name_item = isset($medication_names[$i]) ? $medication_names[$i] : '';
																	$pill_amount_item = isset($pill_amounts[$i]) ? $pill_amounts[$i] : '';
																	$pill_dose_item = isset($pill_doses[$i]) ? $pill_doses[$i] : '';
																?>
																	<tr>
																		<td style="padding-left: 70px;"><strong><?php echo $i + 1; ?>. Medication Name:</strong></td>
																		<td><?php echo !empty($medication_name_item) ? htmlspecialchars($medication_name_item) : '-'; ?></td>
																	</tr>
																	<tr>
																		<td style="padding-left: 70px;"><strong>Pill Amounts:</strong></td>
																		<td><?php echo !empty($pill_amount_item) ? htmlspecialchars($pill_amount_item) : '-'; ?></td>
																	</tr>
																	<tr>
																		<td style="padding-left: 70px;"><strong>Pill Doses:</strong></td>
																		<td><?php echo !empty($pill_dose_item) ? htmlspecialchars($pill_dose_item) : '-'; ?></td>
																	</tr>
																<?php
																}
																?>
																<tr>
																	<td><strong>Taking OTC Medicines:</strong></td>
																	<td><?php echo !empty($row['otc_medicines']) ? htmlspecialchars($row['otc_medicines']) : '-'; ?></td>
																</tr>
																<tr>
																	<td style="padding-left: 70px;"><strong>Taking Herbal Medicine:</strong></td>
																	<td><?php echo !empty($row['herbal_medicine_list']) ? htmlspecialchars($row['herbal_medicine_list']) : '-'; ?></td>
																</tr>
																<tr>
																	<td style="padding-left: 70px;"><strong>Taking Other Medicine:</strong></td>
																	<td><?php echo !empty($row['other_medicine_list']) ? htmlspecialchars($row['other_medicine_list']) : '-'; ?></td>
																</tr>
																<tr>
																	<td><strong>Allergic to Medicine:</strong></td>
																	<td><?php echo !empty($row['allergic_reaction']) ? htmlspecialchars($row['allergic_reaction']) : '-'; ?></td>
																</tr>
																<?php
																$allergic_medicines = isset($row['allergic_medicine']) ? explode(', ', $row['allergic_medicine']) : [];
																$allergic_reaction_descriptions = isset($row['allergic_reaction_description']) ? explode(', ', $row['allergic_reaction_description']) : [];

																$rowCount = max(count($allergic_medicines), count($allergic_reaction_descriptions));

																for ($i = 0; $i < $rowCount; $i++) {
																	$allergic_medicine_item = isset($allergic_medicines[$i]) ? $allergic_medicines[$i] : '';
																	$allergic_reaction_description_item = isset($allergic_reaction_descriptions[$i]) ? $allergic_reaction_descriptions[$i] : '';
																?>
																	<tr>
																		<td style="padding-left: 70px;"><strong><?php echo $i + 1; ?>. Allergic Medicine:</strong></td>
																		<td><?php echo !empty($allergic_medicine_item) ? htmlspecialchars($allergic_medicine_item) : '-'; ?></td>
																	</tr>
																	<tr>
																		<td style="padding-left: 70px;"><strong>Allergic Reaction Description:</strong></td>
																		<td><?php echo !empty($allergic_reaction_description_item) ? htmlspecialchars($allergic_reaction_description_item) : '-'; ?></td>
																	</tr>
																<?php
																}
																?>
																<tr>
																	<td><strong>Allergy Triggers:</strong></td>
																	<td><?php echo !empty($row['allergy_triggers']) ? htmlspecialchars($row['allergy_triggers']) : '-'; ?></td>
																</tr>
																<tr>
																	<td><strong>Other Allergic Triggers:</strong></td>
																	<td><?php echo !empty($row['other_allergy_triggers']) ? htmlspecialchars($row['other_allergy_triggers']) : '-'; ?></td>
																</tr>
																<tr>
																	<td style="font-size: 20px;">
																		<div style="margin: 0 auto; text-align: center;"><strong>For Women</strong></div>
																	</td>
																</tr>
																<tr>
																	<td><strong>Have been pregnant:</strong></td>
																	<td><?php echo !empty($row['pregnant']) ? htmlspecialchars($row['pregnant']) : '-'; ?></td>
																</tr>
																<tr>
																	<td style="padding-left: 70px;"><strong>Pregnancy Times:</strong></td>
																	<td><?php echo !empty($row['pregnancy_times']) ? htmlspecialchars($row['pregnancy_times']) : '-'; ?></td>
																</tr>
																<tr>
																	<td style="padding-left: 70px;"><strong>Children Birthed:</strong></td>
																	<td><?php echo !empty($row['children_birthed']) ? htmlspecialchars($row['children_birthed']) : '-'; ?></td>
																</tr>
																<tr>
																	<td><strong>Have experienced Pap Smear:</strong></td>
																	<td><?php echo !empty($row['pap_smear']) ? htmlspecialchars($row['pap_smear']) : '-'; ?></td>
																</tr>
																<tr>
																	<td style="padding-left: 70px;"><strong>Last Pap Smear Date:</strong></td>
																	<td><?php echo !empty($row['last_pap_smear_date']) ? htmlspecialchars($row['last_pap_smear_date']) : '-'; ?></td>
																</tr>
																<tr>
																	<td><strong>Have experienced Abnormal Pap:</strong></td>
																	<td><?php echo !empty($row['abnormal_pap']) ? htmlspecialchars($row['abnormal_pap']) : '-'; ?></td>
																</tr>
																<tr>
																	<td><strong>Have experienced Mammogram:</strong></td>
																	<td><?php echo !empty($row['mammogram']) ? htmlspecialchars($row['mammogram']) : '-'; ?></td>
																</tr>
																<tr>
																	<td style="padding-left: 70px;"><strong>Last Mammogram Date:</strong></td>
																	<td><?php echo !empty($row['last_mammogram_date']) ? htmlspecialchars($row['last_mammogram_date']) : '-'; ?></td>
																</tr>
																<tr>
																	<td style="font-size: 20px;">
																		<div style="margin: 0 auto; text-align: center;"><strong>Family History</strong></div>
																	</td>
																</tr>
																<tr>
																	<td><strong>Mother Medical Problems:</strong></td>
																	<td><?php echo !empty($row['mother_medical_problems']) ? htmlspecialchars($row['mother_medical_problems']) : '-'; ?></td>
																</tr>
																<tr>
																	<td><strong>Father Medical Problems:</strong></td>
																	<td><?php echo !empty($row['father_medical_problems']) ? htmlspecialchars($row['father_medical_problems']) : '-'; ?></td>
																</tr>
																<tr>
																	<td><strong>Sisters Medical Problems:</strong></td>
																	<td><?php echo !empty($row['sisters_medical_problems']) ? htmlspecialchars($row['sisters_medical_problems']) : '-'; ?></td>
																</tr>
																<tr>
																	<td><strong>Brothers Medical Problems:</strong></td>
																	<td><?php echo !empty($row['brothers_medical_problems']) ? htmlspecialchars($row['brothers_medical_problems']) : '-'; ?></td>
																</tr>
																<tr>
																	<td style="font-size: 20px;">
																		<div style="margin: 0 auto; text-align: center;"><strong>History of Medical Conditions</strong></div>
																	</td>
																</tr>
																<tr>
																	<td><strong>Medical Conditions:</strong></td>
																	<td><?php echo !empty($row['medical_conditions']) ? htmlspecialchars($row['medical_conditions']) : '-'; ?></td>
																</tr>
																<tr>
																	<td><strong>Other Condition:</strong></td>
																	<td><?php echo !empty($row['other_condition']) ? htmlspecialchars($row['other_condition']) : '-'; ?></td>
																</tr>
															</tbody>
														</table>
													</div>
												<?php
												} else {
													// No data found
													echo "<p>No data found</p>";
												}
												?>
											</div>

										</div>
									</div>
									<!-- /Patient Information Tab -->

									<!-- Appointment Tab -->
									<div id="pat_appointments" class="tab-pane fade">
										<div class="card card-table mb-0">
											<div class="card-body">
												<div class="table-responsive">
													<table class="table table-hover table-center mb-0">
														<thead>
															<tr>
																<th class="text-center">Doctor</th>
																<th class="text-center">Appt Date</th>
																<th class="text-center">Start Time</th>
																<th class="text-center">Duration (mins.)</th>
																<th class="text-center">Status</th>
																<th class="text-center">Action</th>
															</tr>
														</thead>
														<tbody>
															<?php
															$userId = $_SESSION['id'];

															// Query to get all appointments for the patient with doctor details
															$query = "SELECT a.*, u.fname, u.lname, u.idpic, d.doctor_specialties
																	FROM appointments a
																	JOIN users u ON a.doctor_id = u.id
																	JOIN doctors d ON a.doctor_id = d.doctor_id
																	WHERE a.user_id = '$patientId'
																	ORDER BY a.appointment_date DESC";
															$result = mysqli_query($connect, $query);

															// Loop through the appointments
															while ($row = mysqli_fetch_assoc($result)) {

																$reason = $row['appointment_reason'];
																$comments = $row['doctor_comments'];

																$idpic = $row["idpic"];
																$imgSrc = ($idpic !== null) ? $idpic : 'assets/uploads/idpics/default-id.png';

																$doctorSpecialties = $row["appointment_specialties"];

																echo '<tr>';
																echo '<td>';
																echo '<h2 class="table-avatar">';
																echo '<a href="patient-profile.php?page=doctors&sub=docprof&patient_id=' . $patientId . '" class="avatar avatar-sm mr-2">';
																echo '<img class="avatar-img rounded-circle" src="' . $imgSrc . '" alt="User Image">';
																echo '</a>';
																echo '<a href="patient-profile.php?page=doctors&sub=docprof&patient_id=' . $patientId . '">' . $row['fname'] . ' ' . $row['lname'] . '</a>';
																echo '</h2>';
																echo '</td>';
																echo '<td class="text-center">' . date('d M Y', strtotime($row['appointment_date'])) . '</td>';
																echo '<td class="text-center">' . date('g:i A', strtotime($row['appointment_start_time'])) . '</td>';
																echo '<td class="text-center">' . $row['appointment_duration'] . '</td>';
																echo "<td class='text-center'  data-appointment-reason='$reason' data-doctor-comments='$comments'>";
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
																echo '<td class="text-right">';
																echo '<div class="table-action">';
																echo "<a href='#view_booking' class='btn btn-sm view_booking bg-info-light' data-toggle='modal' data-appointment-id='{$row['appointment_id']}'data-specialties='{$doctorSpecialties}'>";
																echo '<i class="fas fa-eye"></i> View';
																echo '</a>';
																echo '</div>';
																echo '</td>';
																echo '</tr>';
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
									<div class="tab-pane fade" id="medical">
										<div class="text-right">
											<a href="#" class="add-new-btn" data-toggle="modal" data-target="#add_medical_records">Add Medical Records</a>
										</div>
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
															$patientRecords = mysqli_query($connect, "SELECT r.*, d.doctor_id, du.fname AS doctor_fname, du.lname AS doctor_lname, du.idpic AS doctor_idpic, d.doctor_specialties, du.id
																		FROM records r
																		JOIN doctors d ON r.doctor_id = d.doctor_id
																		JOIN users du ON d.doctor_id = du.id
																		WHERE r.user_id = '$patientId'");

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
																echo "<a href='#' class='btn btn-sm bg-info-light mr-2' data-toggle='modal' data-target='#edit_medical_records' data-recordid='{$row['record_id']}' data-description='{$row['description']}'>";
																echo '<i class="far fa-edit"></i> Edit';
																echo '</a>';
																echo '</a>';
																echo "<a href='assets/{$row['attachment_path']}'  download class='btn btn-sm bg-primary-light'>";
																echo '<i class="fas fa-download"></i> Download';
																echo '</a>';
																echo '</div>';
																echo '</td>';

																echo '</tr>';
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
							</div>
						</div>
					</div>
				</div>

			</div>

		</div>
		<!-- /Page Content -->

<?php
	else :
		echo "Patient not found.";
	endif;

else :
	echo "Patient ID not provided.";

endif;
?>

<!-- include footer -->
<?php require_once('assets/footer.php') ?>