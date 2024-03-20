<!-- include the header first -->
<?php require_once('assets/header.php') ?>

<!-- removed as per requested -->
<!-- Breadcrumb -->
<!-- <div class="breadcrumb-bar">
	<div class="container-fluid">
		<div class="row align-items-center">
			<div class="col-md-12 col-12">
				<nav aria-label="breadcrumb" class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href=" index-2.php?page=home">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Booking</li>
					</ol>
				</nav>
				<h2 class="breadcrumb-title">Booking</h2>
			</div>
		</div>
	</div>
</div> -->
<!-- /Breadcrumb -->

<!-- Page Content -->
<div class="content success-page-cont">
	<div class="container-fluid">

		<div class="row justify-content-center">
			<div class="col-lg-6">
				<div class="card success-card">
					<div class="card-body">
						<div class="success-cont">
							<i class="fas fa-check"></i>
							<h3>Appointment booked Successfully!</h3>

							<?php

							// Get recently booked doctor information
							$recentlyBookedDoctor = array();

							$query = "SELECT a.appointment_date, a.appointment_start_time, a.appointment_duration, u.fname, u.lname, u.id
									FROM appointments a
									JOIN users u ON a.doctor_id = u.id
									ORDER BY a.appointment_id DESC
									LIMIT 1";

							$result = mysqli_query($connect, $query);

							if ($result) {
								$recentlyBookedDoctor = mysqli_fetch_assoc($result);
							}

							if (!empty($recentlyBookedDoctor)) {
								echo "<p>Appointment booked with <strong>Dr. " . $recentlyBookedDoctor['fname'] . ' ' . $recentlyBookedDoctor['lname'] . "</strong>";
								echo " on <strong>" . date('M d, Y', strtotime($recentlyBookedDoctor['appointment_date'])) . "</strong>";
								echo " from <strong>" . date('H:i A', strtotime($recentlyBookedDoctor['appointment_start_time'])) . "</strong>";
								echo " for <strong>" . $recentlyBookedDoctor['appointment_duration'] . ' minutes' . "</strong></p>";
								echo "<p><strong>Please wait for the doctor to approve your appointment.</strong></p>";
								echo "<small>*Please screenshot this page to serve as your reminder and proof of appointment. </small>";
							} else {
								echo "<p>No recent appointments found.</p>";
							}
							?>
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