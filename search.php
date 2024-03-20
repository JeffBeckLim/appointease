<!-- Include databse connection and other checking of session variables-->
<?php require_once('assets/header.php') ?>

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
	<div class="container-fluid">
		<div class="row align-items-center">
			<div class="col-md-8 col-12">
				<nav aria-label="breadcrumb" class="page-breadcrumb">
					<!-- <ol class="breadcrumb">
						<li class="breadcrumb-item"><a href=" index-2.php?page=home">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Services</li>
					</ol> -->
				</nav>
				<h2 class="breadcrumb-title">Services</h2>
			</div>
		</div>
	</div>
</div>
<!-- /Breadcrumb -->

<!-- Page Content -->
<div class="content">
	<div class="container-fluid">

		<div class="row">
			<div class="col-md-12 col-lg-4 col-xl-3 theiaStickySidebar">

				<!-- Search Filter -->
				<div class="card search-filter">
					<div class="card-header">
						<h4 class="card-title mb-0">Search Filter</h4>
					</div>
					<div class="card-body">

						<form method="post" action="search.php">
							<div class="input-group mb-3">
								<input type="text" class="form-control" placeholder="Search Doctors by Name" aria-label="Search" aria-describedby="search-btn" name="search_name">
								<div class="input-group-append">
									<button class="btn btn-primary" type="submit" id="search-btn" name="search_by_name">
										<i class="fas fa-search"></i>
									</button>
								</div>
							</div>
						</form>

						<div class="filter-widget">
						<h4>Select Service</h4>


						<form method="post" action="search.php">
							<div class="input-group mb-3">
								<input type="text" class="form-control" placeholder="Search a Specialty" aria-label="Search" aria-describedby="search-btn" name="search_specialty">
								<div class="input-group-append">
									<button class="btn btn-primary" type="submit" id="search-btn" name="search_by_specialty">
										<i class="fas fa-search"></i>
									</button>
								</div>
							</div>
						</form>

						<form method="post" action="search.php">
							<?php
							$sql = "SELECT DISTINCT doctor_services FROM doctors WHERE doctor_services IS NOT NULL AND doctor_services != '' AND doctor_status = 'Active'";
							$result = $connect->query($sql);

							// Check if there are any results
							if ($result->num_rows > 0) {

								// Collect all services in an array
								$allServices = array();

								while ($row = $result->fetch_assoc()) {
									$doctorServices = explode(',', $row["doctor_services"]);
									$allServices = array_merge($allServices, $doctorServices);
								}
								// Display unique services
								$uniqueServices = array_unique($allServices);
								?>

								<div class="scrollable-container" style="max-height: 200px; overflow-y: auto;">
									<?php
									foreach ($uniqueServices as $service) {
										echo '<div>';
										echo '<label class="custom_check">';
										echo '<input type="checkbox" name="select_service[]" value="' . $service . '" ' . (isset($_POST['select_service']) && in_array($service, $_POST['select_service']) ? 'checked' : '') . '>';
										echo '<span class="checkmark"></span> ' . $service;
										echo '</label>';
										echo '</div>';
									}
									?>
								</div>
							<?php
								echo '</div>';
							} else {
								echo "No services found in the database.";
							}
							?>
							<div class="btn btn-search">
								<button type="submit" class="btn btn-rounded btn-block" name="search">Search</button>
							</div>
							<div class="btn btn-search">
								<button type="submit" class="btn btn-rounded btn-block clear-btn">Clear All</button>
							</div>
						</form>
					</div>
				</div>
				<!-- /Search Filter -->
			</div>

			<div class="col-md-12 col-lg-8 col-xl-9">
				<!-- Doctor Widget with Search Filter -->
				<?php

				if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_by_name'])) {
					// echo '<script>console.log("Condition met: Search button clicked or form submitted.");</script>';
					// Retrieve the search term from the form input
					$search_name = mysqli_real_escape_string($connect, $_POST['search_name']);

					// Construct the SQL query with the search term
					$sql = "SELECT u.*, d.*,
								COUNT(r.review_id) AS review_count,
								ROUND(AVG(r.rating)) AS average_rating 
							FROM
								users u
								JOIN doctors d ON u.id = d.doctor_id
								LEFT JOIN reviews r ON d.doctor_id = r.doctor_id
							WHERE
								u.usertype = 'practitioner'
								AND d.doctor_services IS NOT NULL
								AND d.doctor_services != ''
								AND d.doctor_status = 'Active'
								AND (u.fname LIKE '%$search_name%' OR u.lname LIKE '%$search_name%')
							GROUP BY
								u.id, d.doctor_id;";
				} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_by_specialty'])) {
					// echo '<script>console.log("Condition met: Search button clicked or form submitted.");</script>';
					// Retrieve the search term from the form input
					$search_specialty = mysqli_real_escape_string($connect, $_POST['search_specialty']);

					// Construct the SQL query with the search term
					$sql = "SELECT u.*, d.*,
								COUNT(r.review_id) AS review_count,
								ROUND(AVG(r.rating)) AS average_rating 
							FROM
								users u
								JOIN doctors d ON u.id = d.doctor_id
								LEFT JOIN reviews r ON d.doctor_id = r.doctor_id
							WHERE
								u.usertype = 'practitioner'
								AND d.doctor_specialties IS NOT NULL
								AND d.doctor_specialties != ''
								AND d.doctor_status = 'Active'
								AND (d.doctor_specialties LIKE '%$search_specialty%')
							GROUP BY
								u.id, d.doctor_id;";
				}

				// Check if the search button or the form was submitted
				else if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['search']) || isset($_POST['searchBtn']))) {

					// Retrieve the selected service filters
					$selectedServices = isset($_POST['select_service']) ? $_POST['select_service'] : [];

					// Construct the WHERE clause for services
					$serviceCondition = '';
					if (!empty($selectedServices)) {
						// Iterate through selected services and build the condition
						$serviceConditions = [];
						foreach ($selectedServices as $service) {
							// Exclude records with NULL values in doctor_services
							$serviceConditions[] = "d.doctor_services IS NOT NULL AND FIND_IN_SET('$service', d.doctor_services)";
						}
						$serviceCondition = " AND (" . implode(" OR ", $serviceConditions) . ")";
					}

					$sql = "SELECT u.*, d.*,
								COUNT(r.review_id) AS review_count,
								ROUND(AVG(r.rating)) AS average_rating 
							FROM
								users u
								JOIN doctors d ON u.id = d.doctor_id
								LEFT JOIN reviews r ON d.doctor_id = r.doctor_id
							WHERE
								u.usertype = 'practitioner'
								$serviceCondition
								AND d.doctor_services IS NOT NULL
								AND d.doctor_services != ''
								AND d.doctor_status = 'Active'
							GROUP BY
								u.id, d.doctor_id;";
				}


				// if the clear all button is selected or no button is clicked
				else if (
					$_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear']) ||
					(!isset($_POST['search']) && !isset($_POST['search_by_specialty']) && !isset($_POST['search_by_name']))
				) {
					// echo '<script>console.log("Condition METTTTTT met: Search button clicked or form submitted.");</script>';
					$sql = "SELECT u.*, d.*,
								COUNT(r.review_id) AS review_count,
								ROUND(AVG(r.rating)) AS average_rating 
							FROM
								users u
								JOIN doctors d ON u.id = d.doctor_id
								LEFT JOIN reviews r ON d.doctor_id = r.doctor_id
							WHERE
								u.usertype = 'practitioner'
								AND d.doctor_services IS NOT NULL
								AND d.doctor_services != ''
								AND d.doctor_status = 'Active'
							GROUP BY
								u.id, d.doctor_id;";
				}

				$result = $connect->query($sql);

				// Check if there are any results
				if ($result->num_rows > 0) {
					// Loop through the results and generate HTML code for each doctor
					while ($row = $result->fetch_assoc()) {
						// Display each doctor's information  
						$firstName = $row["fname"];
						$lastName = $row["lname"];
						$doctorabout = $row['doctor_about_me'];
						$doctorSpecializations = explode(',', $row["doctor_specialties"]);
						$doctorRate = $row["average_rating"];
						$doctorReviews = $row["review_count"];
						$doctorId = $row['id'];
						$idpic = $row["idpic"];
						$doctorServices = $row["doctor_services"];
						// Set the dynamic image source based on idpic column
						$imgSrc = ($idpic !== null) ? $idpic : 'assets/uploads/idpics/default-id.png';

						//html for fetched data
						echo '<div class="card">';
						echo '<div class="card-body">';
						echo '<div class="doctor-widget">';
						echo '<div class="doc-info-left">';
						echo '<div class="doctor-img">';
						echo '<a href="doctor-profile.php?page=patients&sub=docprof&doctor_id=' . $doctorId . '">';
						echo '<img src="' . $imgSrc . '" class="img-fluid" alt="User Image">';
						echo '</a>';
						echo '</div>';
						echo '<div class="doc-info-cont">';
						echo '<h4 class="doc-name"><a href="doctor-profile.php?page=patients&sub=docprof&doctor_id=' . $doctorId . '">' . $firstName . ' ' . $lastName . '</a></h4>';
						echo '<p class="doc-speciality">' . $row['doctor_degree'] . '</p>';
						echo '<h5 class="doc-department">' . $doctorServices . '</h5>';
						echo '<div class="rating">';

						for ($i = 1; $i <= 5; $i++) {
							echo '<i class="fas fa-star ' . (($i <= $doctorRate) ? 'filled' : '') . '"></i>';
						}
						echo '<span class="d-inline-block average-rating">(' . $doctorReviews . ')</span>';
						echo '</div>';

						echo '<div class="clinic-services">';
						foreach ($doctorSpecializations as $specialization) {
							echo '<span>' . $specialization . '</span>';
						}
						echo '</div>';
						echo '</div>';
						echo '</div>';
						echo '<div class="doc-info-right">';
						echo '<div class="clinic-booking">';
						echo '<a class="view-pro-btn" href="doctor-profile.php?page=patients&sub=docprof&doctor_id=' . $doctorId . '">View Profile</a>';
						echo '<a class="apt-btn" href="booking.php?page=patients&sub=booking&doctor_id=' . $doctorId . '">Book Appointment</a>';
						echo '</div>';
						echo '</div>';
						echo '</div>';
						echo '</div>';
						echo '</div>';
					}
				} else {
					echo "No doctors found.";
				}
				?>
				<!-- /Doctor Widget -->

			</div>
			<!-- /Doctor Widget with Search Filter -->
		</div>

	</div>

</div>

<!-- add to remove the check on check box when clear all button is clicked -->
<script>
	// JavaScript to handle Clear All button click
	document.addEventListener("DOMContentLoaded", function() {
		document.querySelector(".clear-btn").addEventListener("click", function() {
			document.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
				checkbox.checked = false;
			});
		});
	});
</script>



<!-- /Page Content -->

<!-- include footer -->
<?php require_once('assets/footer.php') ?>