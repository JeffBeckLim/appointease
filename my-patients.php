<!-- include header -->
<?php require_once('assets/header.php'); ?>

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
	<div class="container-fluid">
		<div class="row align-items-center">
			<div class="col-md-12 col-12">
				<nav aria-label="breadcrumb" class="page-breadcrumb">
					<!-- <ol class="breadcrumb">
						<li class="breadcrumb-item"><a href=" index-2.php?page=home">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">My Patients</li>
					</ol> -->
				</nav>
				<h2 class="breadcrumb-title">My Patients</h2>
			</div>
		</div>
	</div>
</div>
<!-- /Breadcrumb -->

<!-- Page Content -->
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<!-- include doctor side profile -->
			<?php require_once('assets/doctor-sideprofile.php') ?>
			<div class="col-md-7 col-lg-8 col-xl-9">

				<div class="row row-grid">
					<?php
					$doctorId = $_SESSION['id'];

					// Fetch patient data along with the count
					$query = "SELECT DISTINCT u.id, u.idpic, u.fname, u.lname, u.contactnum, u.birthday, u.email, u.gender
							  FROM users u
							  JOIN appointments a ON u.id = a.user_id
							  WHERE a.doctor_id = '$doctorId'";

					$result = mysqli_query($connect, $query);

					// Check if there are any patients
					if (mysqli_num_rows($result) > 0) {
						// Loop through each patient
						while ($row = mysqli_fetch_assoc($result)) {
							// Extract patient information
							$patientID = $row['id'];
							$fname = $row['fname'];
							$lname = $row['lname'];
							$contactnum = $row['contactnum'];
							$age = calculateAge($row['birthday']);
							$gender =  $row['gender'];
							$email = $row['email'];
							$idpic = $row["idpic"];
							$imgSrc = ($idpic !== null) ? $idpic : 'assets/uploads/idpics/default-id.png';

							// Output HTML structure for each patient
							echo '<div class="col-md-6 col-lg-4 col-xl-3">';
							echo '<div class="card widget-profile pat-widget-profile">';
							echo '<div class="card-body">';
							echo '<div class="pro-widget-content">';
							echo '<div class="profile-info-widget">';
							echo '<a href="patient-profile.php?page=doctor&sub=patients-profile&patient_id=' . $patientID . '" class="booking-doc-img">';
							echo '<img src="' . $imgSrc . '" alt="User Image">';
							echo '</a>';
							echo '<div class="profile-det-info">';
							echo '<h3><a href="patient-profile.php">' . $fname . ' ' . $lname . '</a></h3>';
							echo '<div class="patient-details">';
							echo '<h5><b>Patient ID :</b> ' . $patientID . '</h5>';
							echo '</div>';
							echo '</div>';
							echo '</div>';
							echo '</div>';
							echo '<div class="patient-info">';
							echo '<ul>';
							echo '<li>Phone <span>' . $contactnum . '</span></li>';
							echo '<li>Email <span>' . $email . '</span></li>';
							echo '<li>Age <span>' . $age .  ' Years Old, ' . $gender . '</span></li>';
							echo '</ul>';
							echo '</div>';
							echo '</div>';
							echo '</div>';
							echo '</div>';
							
						}
					} else {
						// If there are no patients, display a message or perform other actions
						echo 'No patients found.';
					}

					?>

				</div>

			</div>
		</div>

	</div>

</div>
<!-- /Page Content -->

<!-- include header -->
<?php require_once('assets/footer.php'); ?>