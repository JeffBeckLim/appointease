<!-- include the header first -->
<?php require_once('assets/header.php') ?>

<!-- add password visibility -->
<script>
	document.addEventListener('DOMContentLoaded', function() {
		const passwordFields = document.querySelectorAll('.toggle-password');

		passwordFields.forEach(function(icon) {
			icon.addEventListener('click', function() {
				const targetField = document.querySelector(icon.getAttribute('toggle'));
				const type = targetField.getAttribute('type') === 'password' ? 'text' : 'password';
				targetField.setAttribute('type', type);
				icon.classList.toggle('fa-eye');
				icon.classList.toggle('fa-eye-slash');
			});
		});
	});
</script>

<!-- php script for saving profile settings -->
<?php
// if save changes button was clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["admin-save-profile"])) {

	//also add pictures
	$fname = $_POST["fname"];
	$lname = $_POST["lname"];
	$birthday = $_POST["birthday"];
	$email = $_POST["email"];
	$contactnum = $_POST["contactnum"];
	$id = $_POST['userId'];

	//prepare the sql statement
	$updateQuery = "UPDATE users SET fname='$fname', lname='$lname', birthday='$birthday', email='$email', 
					contactnum ='$contactnum'
					WHERE id='$id'";

	// Execute the query and show feedback
	if (mysqli_query($connect, $updateQuery)) {
		echo '<script>alert("Profile updated successfully")</script>';
		echo "<meta http-equiv='refresh' content='0'>";
	} else {
		// If there was an error with the query, show an error message
		echo '<script>alert("Error updating profile: ' . mysqli_error($connect) . '")</script>';
	}
}

?>

<!-- Page Wrapper -->
<div class="page-wrapper">
	<div class="content container-fluid">

		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Profile</h3>
					<!-- <ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Profile</li>
					</ul> -->
				</div>
			</div>
		</div>
		<!-- /Page Header -->

		<?php
		// Get user ID from the URL
		if (isset($_GET['patient_id'])) {
			$userId = $_GET['patient_id'];
			$userType = 'patient';
		} elseif (isset($_GET['doctor_id'])) {
			$userId = $_GET['doctor_id'];
			$userType = 'practitioner';
		} else {
			echo "Invalid URL parameters";
			exit();
		}

		// Fetch user data from the database based on user type
		$sql = "SELECT * FROM users WHERE id = $userId";
		$result = $connect->query($sql);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();

			// Extracting user data
			$firstName = $row["fname"];
			$lastName = $row["lname"];
			$email = $row["email"];
			$birthday = $row['birthday'];
			$contactnum = $row['contactnum'];
		} else {
			// Handle the case where the user is not found
			echo "User not found";
		}
		?>

		<div class="row">
			<div class="col-md-12">
				<div class="profile-header">
					<div class="row align-items-center">
						<div class="col ml-md-n2 profile-user-info">
							<h4 class="user-name mb-0"><?php echo $firstName . ' ' . $lastName; ?></h4>
							<h6 class="text-muted"><?php echo $email; ?></h6>
						</div>
						<div class="col-auto profile-btn">
							<?php if ($userType === 'patient') : ?>
								<a href="#" class="btn btn-rounded btn-primary" id="addAsDoctorBtn">
									Add as Doctor
								</a>
							<?php endif; ?>

							<!-- for adding the user as a doctor -->
							<script>
								// Attach a click event to the "Add as Doctor" button
								$('#addAsDoctorBtn').click(function() {
									// Make an AJAX request to update the database
									$.ajax({
										url: 'assets/add-as-doctor.php',
										method: 'POST',
										data: {
											userId: <?php echo $userId; ?>
										},
										success: function(response) {
											console.log(response);

											// Handle the response from the server
											alert('Added as Doctor successfully');

											window.location.href = 'profile.php?doctor_id=<?php echo $userId; ?>';
										},
										error: function(error) {
											// Handle errors
											console.error('Error adding as Doctor:', error);
										}
									});
								});
							</script>
							<a href="#" class="btn btn-rounded btn-primary" id="resetPasswordButton">
								Reset Password
							</a>

							<!-- for resetting the password of the user -->
							<script>
								// Attach a click event to the "Reset Password" button
								$('#resetPasswordButton').click(function() {
									// Make an AJAX request to update the user's password
									$.ajax({
										url: 'assets/reset-password.php', 
										method: 'POST',
										data: {
											userId: <?php echo $userId; ?> // Pass the user ID to identify the user
										},
										success: function(response) {
											console.log(response);

											// Handle the response from the server
											alert('Password reset successfully');

										},
										error: function(error) {
											// Handle errors
											console.error('Error resetting password:', error);
										}
									});
								});
							</script>
						</div>
					</div>
				</div>
				<div class="profile-menu">
					<ul class="nav nav-tabs nav-tabs-solid">
						<li class="nav-item">
							<a class="nav-link active" data-toggle="tab" href="#per_details_tab">About</a>
						</li>
					</ul>
				</div>
				<div class="tab-content profile-tab-cont">

					<!-- Personal Details Tab -->
					<div class="tab-pane fade show active" id="per_details_tab">

						<!-- Personal Details -->
						<div class="row">
							<div class="col-lg-12">
								<div class="card">
									<div class="card-body">
										<h5 class="card-title d-flex justify-content-between">
											<span>Personal Details</span>
											<a class="edit-link" data-toggle="modal" href="#edit_personal_details"><i class="fa fa-edit mr-1"></i>Edit</a>
										</h5>
										<div class="row">
											<p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">Name</p>
											<p class="col-sm-10"><?php echo $firstName . ' ' . $lastName; ?></p>
										</div>
										<div class="row">
											<p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">Date of Birth</p>
											<p class="col-sm-10"><?php echo $birthday; ?></p>
										</div>
										<div class="row">
											<p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">Email ID</p>
											<p class="col-sm-10"><?php echo $email; ?></p>
										</div>
										<div class="row">
											<p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">Mobile</p>
											<p class="col-sm-10"><?php echo $contactnum; ?></p>
										</div>
									</div>
								</div>

								<!-- Edit Details Modal -->
								<div class="modal fade" id="edit_personal_details" aria-hidden="true" role="dialog">
									<div class="modal-dialog modal-dialog-centered" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title">Personal Details</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<form method="POST">
													<input type="hidden" name="userId" id="userId" value="<?php echo $userId; ?>">
													<div class="row form-row">
														<div class="col-12 col-sm-6">
															<div class="form-group">
																<label>First Name</label>
																<input type="text" class="form-control" value="<?php echo $row['fname']; ?>" name="fname">
															</div>
														</div>
														<div class="col-12 col-sm-6">
															<div class="form-group">
																<label>Last Name</label>
																<input type="text" class="form-control" value="<?php echo $row['lname']; ?>" name="lname">
															</div>
														</div>
														<div class="col-12 col-sm-6">
															<div class="form-group">
																<label>Username</label>
																<input type="text" class="form-control" value="<?php echo $row['username']; ?>" name="username">
															</div>
														</div>
														<div class="col-12 col-sm-6">
															<div class="form-group">
																<label>Email</label>
																<input type="text" class="form-control" value="<?php echo $row['email']; ?>" name="email">
															</div>
														</div>
														<div class="col-12 col-sm-6">
															<div class="form-group">
																<label>Contact Number</label>
																<input type="text" class="form-control" value="<?php echo $row['contactnum']; ?>" name="contactnum">
															</div>
														</div>
														<div class="col-12 col-sm-6">
															<div class="form-group">
																<label>Date of Birth</label>
																<input type="date" class="form-control" value="<?php echo $row['birthday']; ?>" name="birthday">
															</div>
														</div>
													</div>
													<button type="submit" class="btn btn-rounded btn-primary btn-block" name="admin-save-profile">Save Changes</button>
												</form>
											</div>
										</div>
									</div>
								</div>
								<!-- /Edit Details Modal -->
							</div>


						</div>
						<!-- /Personal Details -->

					</div>
					<!-- /Personal Details Tab -->
				</div>
			</div>
		</div>

	</div>
</div>
<!-- /Page Wrapper -->

</div>
<!-- /Main Wrapper -->

<!-- jQuery -->
<script src="assets/js/jquery-3.2.1.min.js"></script>

<!-- Bootstrap Core JS -->
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

<!-- Slimscroll JS -->
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom JS -->
<script src="assets/js/script.js"></script>

</body>

<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/profile.php by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:46 GMT -->

</html>