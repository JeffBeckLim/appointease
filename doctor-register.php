<!-- include header first -->
<?php require_once('assets/header.php') ?>

<!-- for empty date type input -->
<script>
	$(document).ready(function() {
		// Focus event
		$('#dob').focus(function() {
			// Set an empty string when the input is focused
			$(this).attr('placeholder', '');
			$(this).attr('type', 'date'); // Change the type to date on focus
		});

		// Blur event
		$('#dob').blur(function() {
			// Set the placeholder back to the default when the input loses focus
			$(this).attr('placeholder', 'mm/dd/yyyy');
			$(this).attr('type', 'text'); // Change the type back to text on blur
		});
	});
</script>

<!-- Page Content -->
<div class="content">
	<div class="container-fluid">

		<div class="row">
			<div class="col-md-8 offset-md-2">

				<!-- Register Content -->
				<div class="account-content" style="margin-bottom: 2em;">
					<div class="row align-items-center justify-content-center">
						<div class="col-md-7 col-lg-6 login-left">
							<img src="assets/img/registration-banner.png" class="img-fluid" alt="Doccure Register">
						</div>
						<div class="col-md-12 col-lg-6 login-right">
							<div class="login-header">
								<h3>Doctor Registration <a href="register.php">Not a Doctor?</a></h3>
							</div>
							<!-- Register Form -->
							<form method="POST">
								<div class="form-group form-focus">
									<input type="text" class="form-control floating" name="fname" value="<?php echo htmlspecialchars($_POST['fname'] ?? '', ENT_QUOTES); ?>" required>
									<label class="focus-label">First Name</label>
								</div>
								<div class="form-group form-focus">
									<input type="text" class="form-control floating" name="lname" value="<?php echo htmlspecialchars($_POST['lname'] ?? '', ENT_QUOTES); ?>" required>
									<label class="focus-label">Last Name</label>
								</div>
								<div class="form-group form-focus">
									<input type="text" class="form-control floating" name="birthday" id="dob" value="<?php echo htmlspecialchars($result['birthday'] ?? '', ENT_QUOTES); ?>" required>
									<label class="focus-label">Date of Birth</label>
								</div>
								<div class="form-group form-focus">
									<input type="text" class="form-control floating" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES); ?>" required>
									<label class="focus-label">Username</label>
								</div>
								<div class="form-group form-focus">
									<select class="form-control floating" name="gender" required>
										<option value="selected" selected hidden>Sex at birth</option>
										<option value="Male">Male</option>
										<option value="Female">Female</option>
									</select>
									<label class="focus-label">Sex</label>
								</div>
								<div class="form-group form-focus">
									<input type="text" class="form-control floating" name="contactnum" value="<?php echo htmlspecialchars($_POST['contactnum'] ?? '', ENT_QUOTES); ?>" required>
									<label class="focus-label">Contact Number</label>
								</div>
								<div class="form-group form-focus">
									<input type="email" class="form-control floating" name="email" id="email" required>
									<label class="focus-label">Email</label>
								</div>
								<small class="error-message" id="email-error" style="color: red; font-size: 13px;"></small>

								<div class="form-group form-focus">
									<input type="password" class="form-control floating" name="password" id="password" required>
									<label class="focus-label">Password</label>
									<div class="input-group-append">
										<span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
									</div>
								</div>
								<small class="error-message" id="password-error" style="color: red; font-size: 13px;"></small>

								<div class="form-group form-focus">
									<input type="password" class="form-control floating" name="cpassword" id="cpassword" required>
									<label class="focus-label">Confirm Password</label>
									<div class="input-group-append">
										<span toggle="#cpassword" class="fa fa-fw fa-eye field-icon toggle-password"></span>
									</div>
								</div>

								<small class="error-message" id="cpassword-error" style="color: red; font-size: 13px;"></small>

								<script>
									document.addEventListener('DOMContentLoaded', function() {
										const passwordFields = document.querySelectorAll('.toggle-password');

										passwordFields.forEach(function(icon) {
											icon.addEventListener('click', function() {
												const targetField = document.querySelector(icon.getAttribute('toggle'));
												const type = targetField.getAttribute('type') === 'password' ? 'text' : 'password';
												targetField.setAttribute('type', type);
												icon.querySelector('i').classList.toggle('fa-eye');
												icon.querySelector('i').classList.toggle('fa-eye-slash');
											});
										});
									});
									document.getElementById('email').addEventListener('input', validateFields);
									document.getElementById('password').addEventListener('input', validateFields);
									document.getElementById('cpassword').addEventListener('input', validateFields);

									function validateFields() {
										var emailInput = document.getElementById('email');
										var passwordInput = document.getElementById('password');
										var cpasswordInput = document.getElementById('cpassword');

										var email = emailInput.value;
										var password = passwordInput.value;
										var cpassword = cpasswordInput.value;

										var emailErrorElement = document.getElementById('email-error');
										var passwordErrorElement = document.getElementById('password-error');
										var cpasswordErrorElement = document.getElementById('cpassword-error');

										var isValidEmail = validateEmail(email, emailErrorElement);
										var isValidPassword = validatePassword(password, passwordErrorElement);
										var isValidCPassword = validateCPassword(password, cpassword, cpasswordErrorElement);

										// Enable or disable the register button based on validation status
										document.getElementById('registerBtn').disabled = !(isValidEmail && isValidPassword && isValidCPassword);
									}

									function validateEmail(email, emailErrorElement) {
										if (email) {
											var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
											if (!emailRegex.test(email)) {
												emailErrorElement.textContent = 'Invalid email format';
												return false;
											} else {
												emailErrorElement.textContent = '';
												return true;
											}
										} else {
											emailErrorElement.textContent = '';
											return false;
										}
									}

									function validatePassword(password, passwordErrorElement) {
										if (password) {
											var passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&-])[A-Za-z\d@$!%*?&-]{8,}$/;
											if (!passwordRegex.test(password)) {
												passwordErrorElement.textContent = 'Password must contain at least 8 characters, 1 number, 1 lowercase letter, 1 uppercase letter, and 1 special character(@ $ ! % * ? & -)';
												return false;
											} else {
												passwordErrorElement.textContent = '';
												return true;
											}
										} else {
											passwordErrorElement.textContent = '';
											return false;
										}
									}

									function validateCPassword(password, cpassword, cpasswordErrorElement) {
										if (cpassword) {
											if (password !== cpassword) {
												cpasswordErrorElement.textContent = 'Passwords do not match.';
												return false;
											} else {
												cpasswordErrorElement.textContent = '';
												return true;
											}
										} else {
											cpasswordErrorElement.textContent = '';
											return false;
										}
									}
								</script>
								<div class="text-right">
									<a class="forgot-link" href="login.php?page=login">Already have an account?</a>
								</div>
								<button class="btn btn-rounded btn-primary btn-block btn-lg login-btn" id="registerBtn" type="submit" name="register_doctor" disabled>Sign Up</button>
							</form>
							<!-- /Register Form -->


						</div>
					</div>
				</div>
				<!-- /Register Content -->

			</div>
		</div>

	</div>

</div>
<!-- /Page Content -->

<!-- include footer -->
<?php require_once('assets/footer.php') ?>