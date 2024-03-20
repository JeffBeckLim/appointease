<!-- include the header first -->

<?php require_once('assets/header.php') ?>

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
	<div class="container-fluid">
		<div class="row align-items-center">
			<div class="col-md-12 col-12">
				<nav aria-label="breadcrumb" class="page-breadcrumb">
					<!-- <ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="index-2.php?page=home">Home</a></li>
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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["save-profile"])) {

	//also add pictures
	$fname = $_POST["fname"];
	$lname = $_POST["lname"];
	$birthday = $_POST["birthday"];
	$email = $_POST["email"];
	$contactnum = $_POST["contactnum"];
	$gender = $_POST["gender"];

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
	$updateQuery = "UPDATE users SET fname='$fname', lname='$lname', birthday='$birthday', email='$email', 
					idpic='$idpic', contactnum ='$contactnum', gender ='$gender'
					WHERE id='$id'";

	// Execute the query and show feedback
	if (mysqli_query($connect, $updateQuery)) {
			// url structure    
		   $url = '/appointease-final/profile-settings.php?page=patients&sub=profset&success=1';
		   // js code to redirect
		   echo "<script>window.location.href = '$url';</script>";
		   exit();
		// echo "<meta http-equiv='refresh' content='0'>";
		// echo '<script>alert("Profile updated successfully")</script>';	
	} else {
		// If there was an error with the query, show an error message
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
	function resetHeader(){
		window.location.href = '/appointease-final/profile-settings.php?page=patients&sub=profset';
	}
</script>

<!-- Page Content -->
<div class="content">

	<div class="container-fluid">
	<?php
		if (isset($_GET['success']) && $_GET['success'] == 1) {
			echo <<<HTML
			<div id="alert-message" class="alert alert-primary alert-dismissible fade show" role="alert">
				Profile updated successfully
				<button type="button" onClick="resetHeader()" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			HTML;
		}
	?>
	
		<div class="row">
			<!-- include the side profile -->
			<!-- side profile already uses the value $result array to pass values -->
			<?php require_once('assets/patient-sideprofile.php') ?>

			<div class="col-md-7 col-lg-8 col-xl-9">
				<div class="card">
					<div class="card-body">
						<!-- Profile Settings Form -->
						<form method="POST" enctype="multipart/form-data">
							<div class="row form-row">
								<div class="col-12 col-md-12">
									<div class="form-group">
										<div class="change-avatar">
											<div class="profile-img">
												<!-- put default profile picture if nothing is set yet -->
												<img src="<?php echo isset($result['idpic']) ? $result['idpic'] : 'assets/uploads/idpics/default-id.png'; ?>" alt="User Image">
											</div>
											<div class="upload-img">
												<div class="change-photo-btn">
													<span><i class="fa fa-upload"></i> Upload Photo</span>
													<input type="file" name="idpic" class="upload" id="idpicInput" onchange="displayFileName()">
													<input type="hidden" name="idpic" id="hiddenIdpicInput" value="<?php echo isset($result['idpic']) ? $result['idpic'] : ''; ?>">
												</div>

												<small class="form-text text-muted">Allowed JPG, GIF or PNG. Max size of 2MB</small>

												<!-- Display filename below upload photo -->
												<small id="fileNameDisplay" class="text-success"></small>
											</div>
										</div>
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label>First Name</label>
										<input type="text" class="form-control" value="<?php echo $result['fname']; ?>" name="fname">
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label>Last Name</label>
										<input type="text" class="form-control" value="<?php echo $result['lname']; ?>" name="lname">
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label>Date of Birth</label>
										<input type="date" class="form-control" value="<?php echo $result['birthday']; ?>" name="birthday">
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label>Sex</label>
										<select class="form-control" name="gender">
											<option value="-">-</option>
											<option value="Male" <?php echo ($result['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
											<option value="Female" <?php echo ($result['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
										</select>
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label>Email</label>
										<input type="email" class="form-control" value="<?php echo $result['email']; ?>" name="email">
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label>Mobile</label>
										<input type="text" value="<?php echo $result['contactnum']; ?>" class="form-control" name="contactnum">
									</div>
								</div>
							</div>
							<div class="submit-section">
								<button type="submit" class="btn btn-rounded btn-primary submit-btn" name="save-profile">Save Changes</button>
							</div>
						</form>
						<!-- /Profile Settings Form -->
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
<!-- /Page Content -->

<!-- include footer -->
<?php require_once('assets/footer.php') ?>