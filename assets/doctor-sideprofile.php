<?php
$id = $_SESSION['id'];

$sql = "SELECT u.*, d.* FROM users u JOIN doctors d ON u.id = d.doctor_id WHERE u.id = $id";

$result = $connect->query($sql);

$row = $result->fetch_assoc()
?>

<div class="col-md-5 col-lg-4 col-xl-3 theiaStickySidebar">

	<!-- Profile Sidebar -->
	<div class="profile-sidebar">
		<div class="widget-profile pro-widget-content">
			<div class="profile-info-widget">
				<a href="#" class="booking-doc-img">
					<img src="<?php echo isset($row['idpic']) ? $row['idpic'] : 'assets/uploads/idpics/default-id.png'; ?>" alt="User Image">
				</a>
				<div class="profile-det-info">
					<?php
					$fullName = "Dr. " . $row['fname'] . " " . $row['lname'];
					echo "<h3>" . (empty($fullName) ? "Unknown Doctor" : $fullName) . "</h3>";
					?>
					<div class="patient-details">
						<?php
						$specialties = $row['doctor_specialties'];
						echo "<h5 class='mb-0'>" . (empty($specialties) ? "Specialties Not Available" : $specialties) . "</h5>";
						?>
					</div>
				</div>
			</div>
		</div>
		<div class="dashboard-widget">
			<nav class="dashboard-menu">
				<ul>
					<li <?php echo ($_SESSION['subpage'] == 'dashboard') ? "class=\"active\"" : '' ?>>
						<a href="doctor-dashboard.php?page=doctor&sub=dashboard">
							<i class="fas fa-columns"></i>
							<span>Dashboard</span>
						</a>
					</li>
					<li <?php echo ($_SESSION['subpage'] == 'patients') ? "class=\"active\"" : '' ?>>
						<a href="my-patients.php?page=doctor&sub=patients">
							<i class="fas fa-user-injured"></i>
							<span>My Patients</span>
						</a>
					</li>
					<li <?php echo ($_SESSION['subpage'] == 'sched') ? "class=\"active\"" : '' ?>>
						<a href="schedule-timings.php?page=doctor&sub=sched">
							<i class="fas fa-hourglass-start"></i>
							<span>Schedule Timings</span>
						</a>
					</li>
					<li <?php echo ($_SESSION['subpage'] == 'reviews') ? "class=\"active\"" : '' ?>>
						<a href="reviews.php?page=doctor&sub=reviews">
							<i class="fas fa-star"></i>
							<span>Reviews</span>
						</a>
					</li>
					<li <?php echo ($_SESSION['subpage'] == 'profile-settings') ? "class=\"active\"" : '' ?>>
						<a href="doctor-profile-settings.php?page=doctor&sub=profile-settings">
							<i class="fas fa-user-cog"></i>
							<span>Profile Settings</span>
						</a>
					</li>
					<li <?php echo ($_SESSION['subpage'] == 'docchangepass') ? "class=\"active\"" : '' ?>>
						<a href="doctor-change-password.php?page=doctor&sub=docchangepass">
							<i class="fas fa-lock"></i>
							<span>Change Password</span>
						</a>
					</li>
					<li>
						<a href="index-2.php?logout">
							<i class="fas fa-sign-out-alt"></i>
							<span>Logout</span>
						</a>
					</li>
				</ul>
			</nav>
		</div>
	</div>
	<!-- /Profile Sidebar -->

</div>