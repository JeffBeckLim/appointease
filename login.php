<?php require_once('assets/header.php') ?>

<!-- Page Content -->
<div class="content">
	<div class="container-fluid">

		<div class="row">
			<div class="col-md-8 offset-md-2">

				<!-- Login Tab Content -->
				<div class="account-content" style="margin-bottom: 2em;">
					<div class="row align-items-center justify-content-center">
						<div class="col-md-7 col-lg-6 login-left">
							<img src="assets/img/login-banner.png" class="img-fluid" alt="Doccure Login">
						</div>
						<div class="col-md-12 col-lg-6 login-right">
							<div class="login-header">
								<h3>Login to <span>Appointease</span></h3>
							</div>
							<form method="POST">
								<div class="form-group form-focus">
									<input type="text" class="form-control floating" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES); ?>" required>
									<label class="focus-label">Username</label>
								</div>
								<div class="form-group form-focus">
									<input type="password" class="form-control floating" name="password" value="<?php echo htmlspecialchars($_POST['password'] ?? '', ENT_QUOTES); ?>" required >
									<label class="focus-label">Password</label>
								</div>
								<div class="text-right">
									<a class="forgot-link" href="forgot-password.php">Forgot Password ?</a>
								</div>
								<button class="btn btn-rounded btn-primary btn-block btn-lg login-btn" type="submit" name="login">Login</button>
								
								<div class="text-center dont-have">Don’t have an account? <a href="register.php?page=register" style="color: #00529A">Register</a></div>
							</form>
						</div>
					</div>
				</div>
				<!-- /Login Tab Content -->
			</div>
		</div>
	</div>
</div>
<!-- /Page Content -->

<?php require_once('assets/footer.php') ?>