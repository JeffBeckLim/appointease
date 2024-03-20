<!-- Include databse connection and other checking of session variables -->
<?php require_once('assets/header.php') ?>

<?php
// Check if a doctor ID is provided in the URL
if (isset($_GET['doctor_id'])) :
	$doctorId = $_GET['doctor_id'];
	// Query to retrieve detailed doctor information based on the doctor ID, including average rate and reviews count
	$sql = "SELECT u.id, u.fname, u.lname, u.email, u.contactnum, d.doctor_degree, d.doctor_specialties, d.doctor_services,
                d.doctor_about_me, u.idpic, AVG(r.rating) AS average_rating, COUNT(r.review_id) AS review_count
            FROM
                users u
                JOIN doctors d ON u.id = d.doctor_id
                LEFT JOIN reviews r ON d.doctor_id = r.doctor_id
            WHERE
                u.usertype = 'practitioner' AND u.id = $doctorId
            GROUP BY
                u.id, u.fname, u.lname, u.email, u.contactnum, d.doctor_degree, d.doctor_specialties, d.doctor_services, d.doctor_about_me, u.idpic";

	$result = $connect->query($sql);

	// Check if there are any results
	if ($result->num_rows > 0) :
		$row = $result->fetch_assoc();
		$firstName = $row["fname"];
		$lastName = $row["lname"];
		$email = $row["email"];
		$doctordegree = $row['doctor_degree'];
		$doctorabout = $row['doctor_about_me'];
		$contactNum = $row["contactnum"];
		$doctorSpecializations = explode(',', $row["doctor_specialties"]);
		$doctorSpecialties = $row["doctor_specialties"];
		$averageRating = $row["average_rating"];
		$reviewCount = $row["review_count"];
		$idpic = $row["idpic"];
		$doctorServices = $row["doctor_services"];

		// Set the dynamic image source based on idpic column
		$imgSrc = ($idpic !== null) ? $idpic : 'assets/uploads/idpics/default-id.png';
?>

		<!-- Breadcrumb -->
		<div class="breadcrumb-bar">
			<div class="container-fluid">
				<div class="row align-items-center">
					<div class="col-md-12 col-12">
						<nav aria-label="breadcrumb" class="page-breadcrumb">
							<!-- <ol class="breadcrumb">
								<li class="breadcrumb-item"><a href=" index-2.php?page=home">Home</a></li>
								<li class="breadcrumb-item active" aria-current="page">Booking</li>
							</ol> -->
						</nav>
						<h2 class="breadcrumb-title">Booking</h2>
					</div>
				</div>
			</div>
		</div>
		<!-- /Breadcrumb -->

		<!-- Page Content -->
		<div class="content">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="card">
							<div class="card-body">
								<div class="booking-doc-info">
									<a href="doctor-profile.php?doctor_id=<?php echo $doctorId; ?>" class="booking-doc-img">
										<img src="<?php echo empty($imgSrc) ? 'default-image.jpg' : $imgSrc; ?>" alt="User Image">
									</a>
									<div class="booking-info">
										<?php
										$fullName = "Dr. " . $firstName . " " . $lastName;
										echo "<h4>" . (empty($fullName) ? "Unknown Doctor" : $fullName) . "</h4>";

										echo '<div class="rating">';
										if (is_numeric($averageRating) && $averageRating >= 0 && $averageRating <= 5) {
											for ($i = 1; $i <= 5; $i++) {
												echo '<i class="fas fa-star ' . (($i <= $averageRating) ? 'filled' : '') . '"></i>';
											}
										} else {
											echo '<i class="fas fa-star"></i>'; // Display a default star if the rating is invalid
										}
										echo '<span class="d-inline-block average-rating">(' . (is_numeric($reviewCount) ? $reviewCount : 0) . ')</span>';
										echo '</div>';
										?>
										<p class="text-muted mb-0"><?php echo empty($doctorServices) ? "Specialties Not Available" : $doctorServices; ?></p>
										<p class="text-muted small mb-0"><?php echo empty($doctorSpecialties) ? "Specialties Not Available" : $doctorSpecialties; ?></p>
									</div>
								</div>
							</div>
						</div>

						<!-- Schedule Widget -->
						<div class="card booking-schedule schedule-widget">

							<!-- Schedule Header -->
							<div class="schedule-header">
								<div class="row">
									<div class="col-md-12">
										<!-- Day Slot -->
										<div class="day-slot">
											<div class="col-12 col-md-4">
												<form action="" method="post" id="searchDateForm">
													<div class="form-group">
														<label for="bookingDate">Choose Date</label>
														<div class="input-group date" id="patient_choose_booking_date" data-target-input="nearest">
															<div class="input-group-prepend" data-target="#patient_choose_booking_date" data-toggle="datetimepicker">
																<span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar"></i></span>
															</div>
															<!-- datepicker -->
															<input type="text" id="bookingDate" value="<?php echo !empty($chosenDateOtherFormat) ? $chosenDateOtherFormat : ''; ?>" name="patient_choose_booking_date" class="form-control datetimepicker-input" data-target="#patient_choose_booking_date" required autocomplete="off" placeholder="Click the calendar icon to choose date" />
															<div class="input-group-append">
																<button class="btn btn-rounded btn-outline-secondary" type="submit" name="searchTimeslotButton">
																	<i class="fas fa-search"></i> Search
																</button>
															</div>
														</div>
													</div>
												</form>
											</div>
										</div>

										<!-- /Day Slot -->

									</div>
								</div>
							</div>
							<!-- /Schedule Header -->

							<!-- Schedule Content -->
							<div class="schedule-cont">
								<div class="row">
									<div class="col-md-12">
										<?php
										$chosenDate = "";

										// Check if the form was submitted
										if ($_SERVER['REQUEST_METHOD'] === 'POST') {
											// Check if the searchTimeslotButton is set in the POST data
											if (isset($_POST["searchTimeslotButton"])) {
												// Get the value of patient_choose_booking_date if set, otherwise use today's date
												$chosenDate = isset($_POST['patient_choose_booking_date']) ? date('Y-m-d', strtotime($_POST['patient_choose_booking_date'])) : date('Y-m-d');

												$chosenDateOtherFormat = isset($_POST['patient_choose_booking_date']) ? date('M-d-Y', strtotime($_POST['patient_choose_booking_date'])) : date('m-d-Y');

										?>
												<!-- Time Slot -->
												<div id="timeSlotsSection">
													<div class='card-title d-flex justify-content-between'><span>Available Time Slots</span></div>

													<?php
													// Use 'l' to get the full day name
													$dayOfWeek = date('l', strtotime($chosenDate));

													// Make the first letter uppercase
													$dayOfWeek = ucfirst($dayOfWeek);

													echo '<script>console.log("Chosen Date: ' . $chosenDate . '")</script>';
													echo '<script>console.log("Day of Week: ' . $dayOfWeek . '")</script>';

													if (!empty($chosenDateOtherFormat)) {
														// Display the chosen date
														echo "<span><strong>Chosen Date:</strong> $chosenDateOtherFormat</span><br>";

														// Display the chosen day
														echo "<span><strong>Chosen Day:</strong> $dayOfWeek</span>";
													}

													/// Query to fetch schedule for the day from the database
													$sql = "SELECT ds.*, COUNT(a.appointment_id) AS appointment_count, MAX(a.appointment_status) AS max_appointment_status, a.appointment_date
												FROM doctor_schedule ds
												LEFT JOIN appointments a ON ds.doctor_schedule_id = a.doctor_schedule_id
												WHERE ds.doctor_schedule_day = '$dayOfWeek' AND ds.doctor_id = '$doctorId'
												GROUP BY ds.doctor_schedule_id";

													$result = $connect->query($sql);

													// Check if there are rows in the result
													if ($result->num_rows > 0) {
														echo "<div class='doc-times'>";
														echo "<table class='table'>";
														echo "<thead><tr><th class='text-center'>Start Time</th><th class='text-center'>End Time</th><th class='text-center'>Duration(mins.)</th><th class='text-center'>Action</th></tr></thead>";
														echo "<tbody>";

														// Loop through the result set
														while ($row = $result->fetch_assoc()) {

															// Check if there are any appointments on the chosen date
															$isAppointmentOnChosenDate = $row['appointment_date'] == $chosenDate;

															echo "<tr>";
															// Format the start time to show only hours and minutes
															$startTime = date('H:i A', strtotime($row['doctor_schedule_start_time']));
															echo "<td class='text-center'>$startTime</td>";

															// Format the end time to show only hours and minutes
															$endTime = date('H:i A', strtotime($row['doctor_schedule_end_time']));
															echo "<td class='text-center'>$endTime</td>";

															echo "<td class='text-center'>{$row['doctor_schedule_duration']}</td>";
															echo "<td class='text-center'>";

															// Check if the timeslot is available and there are no appointments on the chosen date
															if (!$isAppointmentOnChosenDate && $row['doctor_schedule_duration'] > 0) {
																// Display "Book" link only if the timeslot is available and not confirmed
																echo "<a href='#book_time_slot' class='btn btn-primary book_time_slot' data-toggle='modal' data-schedule-id='{$row['doctor_schedule_id']}' data-chosen-date='$chosenDate'>Book</a>&nbsp;";
															} else {
																// Display "Not Available" if the timeslot is not available
																echo "<p class='text-muted mb-0'>Not Available</p>";
															}

															echo "</td>";
															echo "</tr>";
														}

														// Close the table and doc-times div
														echo "</tbody></table></div>";
													} else {
														echo "<p class='text-muted mb-0'>No Available Slots</p>";
													}

													?>
													<!-- /Time Slot -->
												</div>
										<?php
											}
										}
										?>
									</div>
								</div>
							</div>
							<!-- /Schedule Content -->

						</div>
						<!-- /Schedule Widget -->
					</div>
				</div>
			</div>

		</div>
		<!-- /Page Content -->

<?php else :
		echo "Doctor not found.";
	endif;

else :
	echo "Doctor ID not provided.";
endif;

// Include footer
require_once('assets/footer.php');
?>