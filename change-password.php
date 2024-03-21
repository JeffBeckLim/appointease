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
						<li class="breadcrumb-item active" aria-current="page">Change Password</li>
					</ol> -->
				</nav>
				<h2 class="breadcrumb-title">Change Password</h2>
			</div>
		</div>
	</div>
</div>
<!-- /Breadcrumb -->

<!-- php script for changing password -->
<?php
// if save changes button was clicked
if (isset($_POST["change-password"])) {

	// Get user id
	$id = $_SESSION["id"];

	//get inputs
	$opass = mysqli_real_escape_string($connect, $_POST["opass"]);
	$npass = mysqli_real_escape_string($connect, $_POST["npass"]);
	$cpass = mysqli_real_escape_string($connect, $_POST["cpass"]);

	// Fetch user details
	$result = mysqli_query($connect, "SELECT * FROM users WHERE id = '$id'")->fetch_assoc();

	// Check if the old password matches the stored password
	if (password_verify($opass, $result['password'])) {

		// Check if new password and confirm password match
		if ($npass === $cpass) {

			// Hash the new password
			$hashedPassword = password_hash($npass, PASSWORD_DEFAULT);

			// Prepare the SQL statement
			$updateQuery = "UPDATE users SET password='$hashedPassword' WHERE id='$id'";

			// Execute the query and show feedback
			if (mysqli_query($connect, $updateQuery)) {
				$url = $_SERVER['PHP_SELF'].'?page=patients&sub=changepass&response=success';
				echo "<script>window.location.href = '$url';</script>";
				// echo '<script>alert("Password changed successfully")</script>';
			} else {
				// If there was an error with the query, show an error message
				$url = $_SERVER['PHP_SELF'].'?page=patients&sub=changepass&response=error';
				echo "<script>window.location.href = '$url';</script>";
				// echo '<script>alert("Error updating password: ' . mysqli_error($connect) . '")</script>';
				
			}
		} else {
			// If new password and confirm password do not match, show an error message
			$url = $_SERVER['PHP_SELF'].'?page=patients&sub=changepass&response=noMatch';
			echo "<script>window.location.href = '$url';</script>";
			// echo '<script>alert("New password and confirm password do not match")</script>';
		}
	} else {
		// If old password is incorrect, show an error message
		$url = $_SERVER['PHP_SELF'].'?page=patients&sub=changepass&response=oldIncorrect';
		echo "<script>window.location.href = '$url';</script>";
		// echo '<script>alert("Old password is incorrect")</script>';
	}
}
?>

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
	function resetHeader(){
		window.location.href = '<?php $_SERVER['PHP_SELF']?>' + '?page=patients&sub=changepass';
	}
</script>

<!-- Page Content -->
<div class="content">
	<div class="container-fluid">
		
		<?php
			$color = "alert-danger";
			
			if (isset($_GET['response'])){
				if($_GET['response'] == "success"){
					$message = "Password changed successfully";
					$color ="alert-success";
				}else if($_GET['response'] == "error"){
					$message  = "Error updating password (Query Failed)";
				}
				else if($_GET['response'] == "noMatch"){
					$message = "New password and confirm password do not match";
				}
				else if($_GET['response'] == "oldIncorrect"){
					$message = "Old password is incorrect";
				}
				else{
					$message = "Error fetching message.";
				}

				echo "<div id=\"alert-message\" class=\"alert $color alert-dismissible fade show\" role=\"alert\">";
				echo <<<HTML
					$message
					<button type="button" onClick="resetHeader()" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				HTML;
			}
			
		?>

		<div class="row">
			<!-- include the side profile -->
			<?php require_once('assets/patient-sideprofile.php') ?>
			<div class="col-md-7 col-lg-8 col-xl-9">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-md-12 col-lg-6">
								<!-- Change Password Form -->
								<form method="POST">
									<div class="form-group">
										<label>Old Password</label>
										<input type="password" class="form-control" name="opass" required>
									</div>
									<div class="form-group">
										<label>New Password</label>
										<input type="password" class="form-control" name="npass" id="npass" required>
										<span toggle="#npass" class="fa fa-fw fa-eye field-icon toggle-password"></span>
									</div>
									<div class="form-group">
										<label>Confirm Password</label>
										<input type="password" class="form-control" name="cpass" id="cpass" required>
										<span toggle="#cpass" class="fa fa-fw fa-eye field-icon toggle-password"></span>
									</div>
									<div class="submit-section">
										<button type="submit" class="btn btn-rounded btn-primary submit-btn" name="change-password">Save Changes</button>
									</div>
								</form>
								<!-- /Change Password Form -->

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