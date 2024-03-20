<!-- include header -->
<?php require_once('assets/header.php') ?>

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
	<div class="container-fluid">
		<div class="row align-items-center">
			<div class="col-md-12 col-12">
				<nav aria-label="breadcrumb" class="page-breadcrumb">
					<!-- <ol class="breadcrumb">
						<li class="breadcrumb-item"><a href=" index-2.php?page=home">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Profile Settings</li>
					</ol> -->
				</nav>
				<h2 class="breadcrumb-title">Profile Settings</h2>
			</div>
		</div>
	</div>
</div>
<!-- /Breadcrumb -->


<!-- php script for saving profile settings -->
<?php
// if save changes button was clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["save-doctor-profile"])) {

	//get the values for processing
	$fname = $_POST["fname"];
	$lname = $_POST["lname"];
	$email = $_POST["email"];
	$contactnum = $_POST["contactnum"];
	$gender = $_POST['gender'];
	$birthday = $_POST["birthday"];
	$doctor_about_me = $_POST['doctor_about_me'];
	$services = $_POST['services'];
	$specialties = $_POST['specialties'];
	$doctor_degree = $_POST['doctor_degree'];
	$doctor_school = $_POST['doctor_school'];
	$doctor_grad_year = $_POST['doctor_grad_year'];

	// Check if file is uploaded
	if (isset($_FILES['idpic']) && $_FILES['idpic']['error'] == UPLOAD_ERR_OK) {
		// Handle file upload
		$targetDir = "assets/uploads/idpics/";
		$targetFile = $targetDir . basename($_FILES["idpic"]["name"]);
		move_uploaded_file($_FILES["idpic"]["tmp_name"], $targetFile);

		// Update profile picture path
		$idpic = $targetFile;
	} else {
		// Use the hidden input value if no new file is uploaded
		$idpic = $_POST['idpic'];
	}

	//get user id
	$id = $_SESSION["id"];

	//prepare the sql statement
	// Update the 'users' table
	$updateUserQuery = "UPDATE users SET fname='$fname', lname='$lname', email='$email', contactnum = '$contactnum',
						gender= '$gender', birthday='$birthday', idpic='$idpic' 
						WHERE id='$id'";

	// Update the 'doctors' table
	$updateDoctorQuery = "UPDATE doctors SET doctor_about_me = '$doctor_about_me', doctor_services='$services', doctor_specialties='$specialties',
							doctor_degree = '$doctor_degree', doctor_school = '$doctor_school', doctor_grad_year = '$doctor_grad_year'	
							WHERE doctor_id='$id'";

	// Execute the queries and show feedback
	if (mysqli_query($connect, $updateUserQuery) && mysqli_query($connect, $updateDoctorQuery)) {
		echo '<script>alert("Profile updated successfully")</script>';
		echo "<meta http-equiv='refresh' content='0'>";
	} else {
		// If there was an error with any of the queries, show an error message
		echo '<script>alert("Error updating profile: ' . mysqli_error($connect) . '")</script>';
	}
}
?>

<!-- script for showing the profile uploaded for id -->
<script>
	function displayFileName() {
		const fileInput = document.getElementById('idpicInput');
		const fileNameDisplay = document.getElementById('fileNameDisplay');
		const hiddenIdpicInput = document.getElementById('hiddenIdpicInput');

		if (fileInput.files.length > 0) {
			const fileName = fileInput.files[0].name;
			fileNameDisplay.textContent = 'Uploaded file: ' + fileName;
			// Set the hidden input value
			hiddenIdpicInput.value = fileName;
		} else {
			fileNameDisplay.textContent = '';
			// Reset the hidden input value
			hiddenIdpicInput.value = '';
		}
	}
</script>

<!-- Page Content -->
<div class="content">
	<div class="container-fluid">

		<div class="row">
			<!-- include doctor side profile -->
			<!-- side profile already uses the value $row array to pass values -->
			<?php require_once('assets/doctor-sideprofile.php') ?>
			<div class="col-md-7 col-lg-8 col-xl-9">
				<form method="POST" enctype="multipart/form-data">
					<!-- Basic Information -->
					<div class="card">
						<div class="card-body">
							<!-- Profile settings form -->
							<h4 class="card-title">Basic Information</h4>
							<div class="row form-row">
								<div class="col-md-12">
									<div class="form-group">
										<div class="change-avatar">
											<div class="profile-img">
												<img src="<?php echo isset($row['idpic']) ? $row['idpic'] : 'assets/uploads/idpics/default-id.png'; ?>" alt="User Image">
											</div>
											<div class="upload-img">
												<div class="change-photo-btn">
													<span><i class="fa fa-upload"></i> Upload Photo</span>
													<input type="file" name="idpic" class="upload" id="idpicInput" onchange="displayFileName()">
													<input type="hidden" name="idpic" id="hiddenIdpicInput" value="<?php echo isset($row['idpic']) ? $row['idpic'] : ''; ?>">
												</div>
												<small class="form-text text-muted">Allowed JPG, GIF or PNG. Max size of 2MB</small>

												<!-- Display filename below upload photo -->
												<small id="fileNameDisplay" class="text-success"></small>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Username <span class="text-danger">*</span></label>
										<input type="text" class="form-control" value="<?php echo $row['username']; ?>" name="username" readonly>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Email <span class="text-danger">*</span></label>
										<input type="text" class="form-control" value="<?php echo $row['email']; ?>" name="email">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>First Name <span class="text-danger">*</span></label>
										<input type="text" class="form-control" value="<?php echo $row['fname']; ?>" name="fname">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Last Name <span class="text-danger">*</span></label>
										<input type="text" class="form-control" value="<?php echo $row['lname']; ?>" name="lname">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Phone Number</label>
										<input type="text" class="form-control" value="<?php echo $row['contactnum']; ?>" name="contactnum">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Gender</label>
										<select class="form-control select" name="gender">
											<option <?php echo ($row['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
											<option <?php echo ($row['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group mb-0">
										<label>Date of Birth</label>
										<input type="date" class="form-control" value="<?php echo $row['birthday']; ?>" name="birthday">
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- /Basic Information -->

					<!-- About Me -->
					<div class="card">
						<div class="card-body">
							<h4 class="card-title">About Me</h4>
							<div class="form-group mb-0">
								<label>Biography</label>
								<textarea class="form-control" rows="5" name="doctor_about_me"><?php echo $row['doctor_about_me']; ?></textarea>
							</div>
						</div>
					</div>
					<!-- /About Me -->

					<!-- Services and Specialization -->
					<div class="card services-card">
						<div class="card-body">
							<h4 class="card-title">Services and Specialization</h4>
							<div class="form-group">
								<label>Services</label>
								<?php
								$servicesArray = explode(',', $row['doctor_services']);
								$servicesValue = implode(',', $servicesArray);

								// Output the input field with the values from the array
								echo '<input class="input-tags form-control" type="text" data-role="tagsinput" placeholder="Enter Services" name="services" value="' . $servicesValue . '" id="services">';
								?>
								<small class="form-text text-muted">Note : Type & Press enter to add new services</small>
							</div>
							<div class="form-group mb-0">
								<label>Specialties </label>
								<?php
								$specialtiesArray = explode(',', $row['doctor_specialties']);
								$specialtiesValue = implode(',', $specialtiesArray);

								// Output the input field with the values from the array
								echo '<input class="input-tags form-control" type="text" data-role="tagsinput" placeholder="Enter Specialties" name="specialties" value="' . $specialtiesValue . '" id="specialties">';
								?>
								<small class="form-text text-muted">Note : Type & Press enter to add new specialization</small>
							</div>
						</div>
					</div>
					<!-- /Services and Specialization -->

					<!-- Education -->
					<div class="card">
						<div class="card-body">
							<h4 class="card-title">Education</h4>
							<div class="education-info">
								<div class="row form-row education-cont">
									<div class="col-12 col-md-10 col-lg-11">
										<div class="row form-row">
											<div class="col-12 col-md-6 col-lg-12">
												<div class="form-group">
													<label for="doctor_degree">Degree</label>
													<select class="form-control" name="doctor_degree" id="doctor_degree">
														<option value="">-</option>
														<option value="Pharmacy Technician" <?php if ($row['doctor_degree'] === "Pharmacy Technician") echo "selected"; ?>>Pharmacy Technician</option>
														<option value="Health Information Management" <?php if ($row['doctor_degree'] === "Health Information Management") echo "selected"; ?>>Health Information Management</option>
														<option value="Medical Coding" <?php if ($row['doctor_degree'] === "Medical Coding") echo "selected"; ?>>Medical Coding</option>
														<option value="Health Information Technology" <?php if ($row['doctor_degree'] === "Health Information Technology") echo "selected"; ?>>Health Information Technology</option>
														<option value="Medical Laboratory Technology" <?php if ($row['doctor_degree'] === "Medical Laboratory Technology") echo "selected"; ?>>Medical Laboratory Technology</option>
														<option value="Bachelor of Science in Pharmacy or Pharmaceutical Sciences" <?php if ($row['doctor_degree'] === "Bachelor of Science in Pharmacy or Pharmaceutical Sciences") echo "selected"; ?>>Bachelor of Science in Pharmacy or Pharmaceutical Sciences</option>
														<option value="Bachelor of Science in Health Information Management" <?php if ($row['doctor_degree'] === "Bachelor of Science in Health Information Management") echo "selected"; ?>>Bachelor of Science in Health Information Management</option>
														<option value="Bachelor of Science in Health Sciences" <?php if ($row['doctor_degree'] === "Bachelor of Science in Health Sciences") echo "selected"; ?>>Bachelor of Science in Health Sciences</option>
														<option value="Bachelor of Science in Medical Technology" <?php if ($row['doctor_degree'] === "Bachelor of Science in Medical Technology") echo "selected"; ?>>Bachelor of Science in Medical Technology</option>
													</select>
												</div>
											</div>
											<div class="col-12 col-md-6 col-lg-8">
												<div class="form-group">
													<label>College/Institute</label>
													<input type="text" class="form-control" value="<?php echo $row['doctor_school']; ?>" name="doctor_school">
												</div>
											</div>
											<div class="col-12 col-md-6 col-lg-4">
												<div class="form-group">
													<label>Year of Completion</label>
													<input type="text" class="form-control" value="<?php echo $row['doctor_grad_year']; ?>" name="doctor_grad_year">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- /Education -->

					<div class="submit-section submit-btn-bottom">
						<button type="submit" class="btn btn-rounded btn-primary submit-btn" name="save-doctor-profile">Save Changes</button>
					</div>
				</form>
			</div>
		</div>

	</div>

</div>
<!-- /Page Content -->

<!-- include footer -->
<?php require_once('assets/footer.php'); ?>