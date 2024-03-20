<?php
// Include databse connection and other checking of session variables
require_once('assets/header.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["add-review"])) {
	// Retrieve the values from the form
	$doctorId = $_POST['doctor_id'];
	$userId = $_SESSION['id'];
	$rating = $_POST['rating'];
	$comment = $_POST['comment'];
	$dateSubmitted = date('Y-m-d');

	$addQuery = "INSERT INTO reviews (user_id, doctor_id, review_date, comment, rating)
	VALUES ('$userId', '$doctorId', '$dateSubmitted', '$comment', '$rating');";

	if (mysqli_query($connect, $addQuery)) {
		echo '<script>alert("Reviews submitted successfully!")</script>';
	} else {
		// If there was an error with the query, show an error message
		echo '<script>alert("Error updating password: ' . mysqli_error($connect) . '")</script>';
	}
}

// Check if a doctor ID is provided in the URL
if (isset($_GET['doctor_id'])) :
	$doctorId = $_GET['doctor_id'];

	// Query to retrieve detailed doctor information based on the doctor ID
	$sql = "SELECT
    u.*,
    d.*,
    COALESCE(review_data.review_count, 0) AS review_count,
    COALESCE(review_data.average_rating, 0) AS average_rating
	FROM
		users u
	JOIN
		doctors d ON u.id = d.doctor_id
	LEFT JOIN (
		SELECT
			doctor_id,
			COUNT(review_id) AS review_count,
			ROUND(AVG(rating), 2) AS average_rating
		FROM
			reviews
		GROUP BY
			doctor_id
	) review_data ON d.doctor_id = review_data.doctor_id
	WHERE
		u.usertype = 'practitioner' AND u.id = $doctorId";

	$result = $connect->query($sql);

	// Check if there are any results
	if ($result->num_rows > 0) :
		$row = $result->fetch_assoc();

		$firstName = $row["fname"];
		$lastName = $row["lname"];
		$email = $row["email"];
		$doctorabout = $row['doctor_about_me'];
		$contactNum = $row["contactnum"];
		$doctorSpecializations = explode(',', $row["doctor_specialties"]);
		$doctorSpecialties = $row["doctor_specialties"];
		$doctorRate = $row["average_rating"];
		$doctorReviews = $row["review_count"];
		$doctorSchool = $row["doctor_school"];
		$doctorDegree = $row["doctor_degree"];
		$doctorYear = $row["doctor_grad_year"];
		$idpic = $row["idpic"];
		$doctorServices = explode(',', $row["doctor_services"]);

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
								<li class="breadcrumb-item active" aria-current="page">Doctor Profile</li>
							</ol> -->
						</nav>
						<h2 class="breadcrumb-title">Doctor Profile</h2>
					</div>
				</div>
			</div>
		</div>
		<!-- /Breadcrumb -->

		<!-- Page Content -->
		<div class="content">
			<div class="container">

				<!-- Doctor Widget -->
				<div class="card">
					<div class="card-body">
						<div class="doctor-widget">
							<div class="doc-info-left">
								<div class="doctor-img">
									<img src="<?php echo $imgSrc; ?>" class="img-fluid" alt="User Image">
								</div>
								<div class="doc-info-cont">
									<?php
									$fullName = ($firstName !== null ? "Dr. " . $firstName : "") . ($lastName !== null ? " " . $lastName : "");

									echo "<h4 class='doc-name'>" . (empty($fullName) ? "Doctor Name Not Available" : $fullName) . "</h4>";
									?>
									<?php if ($doctorDegree !== null) : ?>
										<p class="doc-speciality"><?php echo $doctorDegree; ?></p>
									<?php else : ?>
										<p class="doc-speciality">No degree information available</p>
									<?php endif; ?>

									<?php if ($doctorSpecialties !== null) : ?>
										<p class="doc-department"><?php echo $doctorSpecialties; ?></p>
									<?php else : ?>
										<p class="doc-department">No specialties information available</p>
									<?php endif; ?>

									<?php
									echo '<div class="rating">';
									
									for ($i = 1; $i <= 5; $i++) {
										echo '<i class="fas fa-star ' . (($i <= $doctorRate) ? 'filled' : '') . '"></i>';
									}
									echo '<span class="d-inline-block average-rating">(' . $doctorReviews . ')</span>';
									echo '</div>';

									echo '<div class="clinic-services">';
									if ($doctorServices !== null) {
										foreach ($doctorServices as $service) {
											echo '<span>' . $service . '</span>';
										}
									} else {
										echo '<p>No services information available</p>';
									}
									echo '</div>';
									?>
								</div>

							</div>
							<div class="doc-info-right">
								<div class="clinic-booking">
									<a class="apt-btn" href="booking.php?page=patients&sub=booking&doctor_id=<?php echo $doctorId; ?>">Book Appointment</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Doctor Widget -->

				<!-- Doctor Details Tab -->
				<div class="card">
					<div class="card-body pt-0">

						<!-- Tab Menu -->
						<nav class="user-tabs mb-4">
							<ul class="nav nav-tabs nav-tabs-bottom nav-justified">
								<li class="nav-item">
									<a class="nav-link active" href="#doc_overview" data-toggle="tab">Overview</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="#doc_reviews" data-toggle="tab">Reviews</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="#doc_business_hours" data-toggle="tab">Business Hours</a>
								</li>
							</ul>
						</nav>
						<!-- /Tab Menu -->

						<!-- Tab Content -->
						<div class="tab-content pt-0">

							<!-- Overview Content -->
							<div role="tabpanel" id="doc_overview" class="tab-pane fade show active">
								<div class="row">
									<div class="col-md-12 col-lg-9">

										<!-- About Details -->
										<div class="widget about-widget">
											<h4 class="widget-title">About Me</h4>
											<p><?php echo ($doctorabout !== null) ? $doctorabout : "No data available"; ?></p>
										</div>
										<!-- /About Details -->

										<!-- Education Details -->
										<div class="widget education-widget">
											<h4 class="widget-title">Education</h4>
											<?php if ($doctorSchool !== null || $doctorDegree !== null || $doctorYear !== null) : ?>
												<div class="experience-content">
													<div class="timeline-content">
														<div><strong><?php echo $doctorSchool; ?></strong></div>
														<div><?php echo $doctorDegree; ?></div>
														<span class="time"><?php echo $doctorYear; ?></span>
													</div>
												</div>
											<?php else : ?>
												<p>No education details available</p>
											<?php endif; ?>
										</div>
										<!-- /Education Details -->

										<!-- Services List -->
										<div class="service-list">
											<h4>Services</h4>
											<?php if ($doctorServices !== null) : ?>
												<ul class="clearfix">
													<?php foreach ($doctorServices as $service) : ?>
														<li><?= $service ?></li>
													<?php endforeach; ?>
												</ul>
											<?php else : ?>
												<p>No services available</p>
											<?php endif; ?>
										</div>
										<!-- /Services List -->

										<!-- Specializations List -->
										<div class="service-list">
											<h4>Specializations</h4>
											<?php if ($doctorSpecializations !== null) : ?>
												<ul class="clearfix">
													<?php foreach ($doctorSpecializations as $specialization) : ?>
														<li><?= $specialization ?></li>
													<?php endforeach; ?>
												</ul>
											<?php else : ?>
												<p>No specializations available</p>
											<?php endif; ?>
										</div>
										<!-- /Specializations List -->

									</div>
								</div>
							</div>
							<!-- /Overview Content -->

							<!-- Reviews Content -->
							<div role="tabpanel" id="doc_reviews" class="tab-pane fade">

								<div class="widget review-listing">
									<ul class="comments-list" id="reviewList">
										<?php
										$count = 0;
										$doctorId = $_GET['doctor_id'];

										$query = "SELECT r.*, u.fname, u.lname, u.idpic 
												FROM reviews r
												INNER JOIN users u ON r.user_id = u.id
												WHERE r.doctor_id = $doctorId";

										$result = mysqli_query($connect, $query);

										if (mysqli_num_rows($result) > 0) {
											while ($row = $result->fetch_assoc()) {
												// Display each review
												$class = ($count > 0) ? ' hidden' : ''; // Add hidden class to all reviews except the first
												echo '<li class="review-item' . $class . '">';
												echo '<div class="comment" style="display:flex;">';
												echo '<img class="avatar rounded-circle" alt="User Image" src="' . $row['idpic'] . '">';
												echo '<div class="comment-body" style="flex:1;">';
												echo '<div class="meta-data">';
												echo '<span class="comment-author">' . $row['fname'] . ' ' . $row['lname'] . '</span>';
												echo '<span class="comment-date">' . date('F d, Y', strtotime($row['review_date'])) . '</span>';
												echo '<div class="review-count rating">';
												for ($i = 1; $i <= 5; $i++) {
													$starClass = ($i <= $row['rating']) ? 'fas fa-star filled' : 'fas fa-star';
													echo '<i class="' . $starClass . '"></i>';
												}
												echo '</div>';
												echo '</div>';
												echo '<p class="comment-content">' . $row['comment'] . '</p>';
												echo '</div>';
												echo '</div>';
												echo '</li>';
												$count++;
											}
										} else {
											echo '<li>No reviews available for this doctor.</li>';
										}
										?>
									</ul>

									<style>
										.hidden {
											display: none;
										}
									</style>

									<!-- Show All Button -->
									<div class="all-feedback text-center">
										<a href="#" class="btn btn-rounded btn-primary btn-sm" id="show-all-feedback">
											Show all feedback <strong>(<?php echo $count - 1; ?>)</strong>
										</a>
									</div>
									<!-- /Show All Button -->

									<!-- script to show all feedback -->
									<script>
										document.addEventListener("DOMContentLoaded", function() {
											var showAllButton = document.getElementById("show-all-feedback");
											var reviews = document.querySelectorAll('.review-item');

											showAllButton.addEventListener("click", function(event) {
												event.preventDefault();

												// Toggle visibility of additional reviews
												reviews.forEach(function(review) {
													review.style.display = 'block';
												});

												// Hide the "Show all" button after all reviews are displayed
												showAllButton.style.display = "none";
											});
										});
									</script>
								</div>
								<!-- /Review Listing -->

								<!-- Write Review -->
								<div class="write-review">
									<h4>Write a review for <strong><?php echo "Dr. " . $firstName . " " . $lastName; ?></strong></h4>

									<!-- Write Review Form -->
									<form method="post" action="">
										<!-- Hidden input for doctor_id -->
										<input type="hidden" name="doctor_id" value="<?php echo $_GET['doctor_id']; ?>">

										<div class="form-group">
											<label>Rating</label>
											<div class="star-rating">
												<input id="star-5" type="radio" name="rating" value="5">
												<label for="star-5" title="5 stars">
													<i class="active fa fa-star"></i>
												</label>
												<input id="star-4" type="radio" name="rating" value="4">
												<label for="star-4" title="4 stars">
													<i class="active fa fa-star"></i>
												</label>
												<input id="star-3" type="radio" name="rating" value="3">
												<label for="star-3" title="3 stars">
													<i class="active fa fa-star"></i>
												</label>
												<input id="star-2" type="radio" name="rating" value="2">
												<label for="star-2" title="2 stars">
													<i class="active fa fa-star"></i>
												</label>
												<input id="star-1" type="radio" name="rating" value="1">
												<label for="star-1" title="1 star">
													<i class="active fa fa-star"></i>
												</label>
											</div>
										</div>

										<div class="form-group">
											<label>How was your experience?</label>
											<textarea name="comment" id="comment" maxlength="200" class="form-control"></textarea>
											<div class="d-flex justify-content-between mt-3"><small class="text-muted"><span id="chars">200</span> characters only</small></div>
										</div>

										<hr>

										<div class="submit-section">
											<button type="submit" class="btn btn-rounded btn-primary submit-btn" name="add-review">Add Review</button>
										</div>
									</form>
									<!-- /Write Review Form -->
								</div>
								<!-- /Write Review -->

							</div>
							<!-- /Reviews Content -->

							<!-- Business Hours Content -->
							<div role="tabpanel" id="doc_business_hours" class="tab-pane fade">
								<div class="row">
									<div class="col-md-6 offset-md-3">

										<!-- Business Hours Widget -->
										<div class="widget business-widget">
											<div class="widget-content">
												<?php
												// Get the current day of the week (0 = Sunday, 1 = Monday, ..., 6 = Saturday)
												$currentDay = date('l');

												// Convert the current day to the format used in the database
												$currentDayDatabaseFormat = ucfirst(strtolower($currentDay));

												// Function to format time in 24-hour format with minutes and AM/PM
												function formatTime($timeString)
												{
													return date('H:i A', strtotime($timeString));
												}

												// Query to get the doctor's schedule for the current day
												$query = "SELECT doctor_schedule_start_time, doctor_schedule_end_time
														FROM doctor_schedule
														WHERE doctor_id = $doctorId
														AND doctor_schedule_day = '$currentDayDatabaseFormat'
														AND doctor_schedule_start_time <= CURRENT_TIME
														AND doctor_schedule_end_time >= CURRENT_TIME
														LIMIT 1";

												$result = mysqli_query($connect, $query);

												if (mysqli_num_rows($result) > 0) {
													// The doctor has a schedule for the current day and is currently open
													$row = mysqli_fetch_assoc($result);
													$openStatus = '<span class="badge bg-success-light">Open Now</span>';
													$time = formatTime($row['doctor_schedule_start_time']) . ' - ' . formatTime($row['doctor_schedule_end_time']);
												} else {
													// The doctor is closed for the current day or has no schedule
													$openStatus = '<span class="badge bg-danger-light">Closed</span>';
													$time = '';
												}

												// Close the result set
												mysqli_free_result($result);

												// Output the HTML for the current day
												echo '<div class="listing-hours">
														<div class="listing-day current">
															<div class="day">Today <span>' . date('j M Y') . '</span></div>
															<div class="time-items">
																<span class="open-status">' . $openStatus . '</span>
																<span class="time">' . $time . '</span>
															</div>
														</div>';

												// Loop through the days
												for ($i = 0; $i < 7; $i++) {
													// Calculate the next day based on the initial day and loop index
													$nextDay = date('l', strtotime("Monday +$i days"));
													$nextDayDatabaseFormat = ucfirst(strtolower($nextDay));

													// Query to get the doctor's schedule for the next day
													$query = "SELECT MIN(doctor_schedule_start_time) AS first_start_time, MAX(doctor_schedule_end_time) AS last_end_time
															FROM doctor_schedule
															WHERE doctor_id = $doctorId
															AND doctor_schedule_day = '$nextDayDatabaseFormat'";

													$result = mysqli_query($connect, $query);

													// Fetch the row
													$row = mysqli_fetch_assoc($result);

													// Get the values from the row
													$firstStartTime = $row['first_start_time'];
													$lastEndTime = $row['last_end_time'];

													// Check if both start and end times are NULL (no schedule)
													if ($firstStartTime === null && $lastEndTime === null) {
														// The doctor is closed for the next day or has no schedule
														echo '<div class="listing-day">
																<div class="day">' . $nextDay . '</div>
																<div class="time-items">
																	<span class="badge bg-danger-light">Closed</span>
																</div>
															</div>';
													} else {
														// The doctor has a schedule for the next day
														$firstStartTime = formatTime($firstStartTime);
														$lastEndTime = formatTime($lastEndTime);
														$openStatus = '<span class="badge bg-success-light">Open</span>';

														echo '<div class="listing-day">
																<div class="day">' . $nextDay . '</div>
																<div class="time-items">
																	<span class="time">' . $firstStartTime . ' - ' . $lastEndTime . '</span>
																</div>
															</div>';
													}
												}

												echo '</div>';
												?>
											</div>
											<small>*Each day has different timeslots. Detailed timeslots per date are
												<a href="booking.php?page=patients&sub=booking&doctor_id=<?php echo $doctorId; ?>"><strong>HERE</strong></a>
											</small>
										</div>
										<!-- /Business Hours Widget -->

									</div>
								</div>
							</div>
							<!-- /Business Hours Content -->

						</div>
					</div>
				</div>
				<!-- /Doctor Details Tab -->

			</div>
		</div>
		<!-- /Page Content -->

<?php else :
		echo "Doctor not found.";
	endif;

else :
	echo "Doctor ID not provided.";
endif;

// Include footer code
require_once('assets/footer.php');
?>