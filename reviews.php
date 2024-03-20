<!-- Include databse connection and other checking of session variables-->
<?php require_once('assets/header.php') ?>

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
	<div class="container-fluid">
		<div class="row align-items-center">
			<div class="col-md-12 col-12">
				<nav aria-label="breadcrumb" class="page-breadcrumb">
					<!-- <ol class="breadcrumb">
						<li class="breadcrumb-item"><a href=" index-2.php?page=home">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Reviews</li>
					</ol> -->
				</nav>
				<h2 class="breadcrumb-title">Reviews</h2>
			</div>
		</div>
	</div>
</div>
<!-- /Breadcrumb -->

<!-- Page Content -->
<div class="content">
	<div class="container-fluid">

		<div class="row">
			<!-- side profile already uses the value $row array to pass values -->
			<?php require_once('assets/doctor-sideprofile.php') ?>

			<div class="col-md-7 col-lg-8 col-xl-9">
				<div class="doc-review review-listing">

					<!-- Review Listing -->
					<ul class="comments-list">
						<?php
						// Get id of the current doctor
						$currentDoctorId = $_SESSION['id'];

						// Database query to fetch reviews and user details for the current doctor
						$query = "SELECT r.*, u.fname, u.lname, u.idpic 
								FROM reviews r
								INNER JOIN users u ON r.user_id = u.id
								WHERE r.doctor_id = $currentDoctorId";

						$result = mysqli_query($connect, $query);

						// Check if there are reviews
						if (mysqli_num_rows($result) > 0) {
							while ($row = mysqli_fetch_assoc($result)) {
								// Display each review
								echo '<li>';
								// adding styling to make the card cover the whole body
								echo '<div class="comment" style="display:flex;">';
								echo '<img class="avatar rounded-circle" alt="User Image" src="' . $row['idpic'] . '">';
								echo '<div class="comment-body" style="flex:1;">';	
								echo '<div class="meta-data">';
								echo '<span class="comment-author">' . $row['fname'] . ' ' . $row['lname'] . '</span>';
								echo '<span class="comment-date">' . date('F d, Y', strtotime($row['review_date'])) . '</span>';

								// Display star rating based on the stored rating value
								echo '<div class="review-count rating">';
								for ($i = 1; $i <= 5; $i++) {
									$starClass = ($i <= $row['rating']) ? 'fas fa-star filled' : 'fas fa-star';
									echo '<i class="' . $starClass . '"></i>';
								}
								echo '</div>';
								echo '</div>';
								echo '<p class="comment-content">' . $row['comment'] . '</p>';
								echo '</div>';
								echo '</div>';
								echo '</li>';
							}
						} else {
							// Display a message if there are no reviews
							echo '<li>No reviews available for this doctor.</li>';
						}
						?>

						<!-- /Comment List -->
					</ul>
					<!-- /Comment List -->

				</div>
			</div>
		</div>
	</div>

</div>
<!-- /Page Content -->

<!-- include footer -->
<?php require_once('assets/footer.php'); ?>