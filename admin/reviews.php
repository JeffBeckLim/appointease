<!-- include the header first -->
<?php require_once('assets/header.php') ?>

<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-7 col-auto">
                    <h3 class="page-title">Reviews</h3>
                    <!-- <ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Reviews</li>
					</ul> -->
                </div>
                <div class="col-sm-5 col">
                    <form method="post" action="assets/reviews_generate_csv.php">
                        <button type="submit" name="reviews_generate_file"
                            class="btn btn-rounded btn-primary float-right mt-2">Generate File</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="datatable table table-hover table-center mb-0">
                                <thead>
                                    <tr>
                                        <th>Patient Name</th>
                                        <th>Doctor Name</th>
                                        <th>Ratings</th>
                                        <th>Description</th>
                                        <th>Date</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
									// SQL query to fetch reviews with patient and doctor names
									$reviewsQuery = "SELECT r.*, p.fname AS patient_fname, p.lname AS patient_lname, d.fname AS doctor_fname, d.lname AS doctor_lname, r.review_date
													FROM reviews r
													JOIN users p ON r.user_id = p.id
													JOIN users d ON r.doctor_id = d.id
													ORDER BY r.review_date DESC";

									$reviewsResult = mysqli_query($connect, $reviewsQuery);

									if ($reviewsResult) {
										if (mysqli_num_rows($reviewsResult) == 0) {
											echo '<tr><td colspan="6" class="text-center">No reviews found.</td></tr>';
										} else {
											while ($reviewRow = mysqli_fetch_assoc($reviewsResult)) {
												$patientName = $reviewRow['patient_fname'] . ' ' . $reviewRow['patient_lname'];
												$doctorName = $reviewRow['doctor_fname'] . ' ' . $reviewRow['doctor_lname'];
												$ratings = $reviewRow['rating'];
												$comment = $reviewRow['comment'];
												$date = date('d M Y', strtotime($reviewRow['review_date']));
												$reviewId = $reviewRow['review_id'];

												echo "<tr>";
												echo "<td>";
												echo "<h2 class='table-avatar'>";
												echo "<a href='profile.php?patient_id={$reviewRow['user_id']}'>$patientName</a>";
												echo "</h2>";
												echo "</td>";
												echo "<td>";
												echo "<h2 class='table-avatar'>";
												echo "<a href='profile.php?doctor_id={$reviewRow['doctor_id']}'>$doctorName</a>";
												echo "</h2>";
												echo "</td>";
												echo "<td>";
												// Display stars based on the rating
												for ($i = 1; $i <= 5; $i++) {
													if ($i <= $ratings) {
														echo "<i class='fe fe-star text-warning'></i>";
													} else {
														echo "<i class='fe fe-star-o text-secondary'></i>";
													}
												}
												echo "</td>";
												echo "<td>$comment</td>";
												echo "<td>$date</td>";
												echo "<td class='text-center'>";
												echo "<div class='actions'>";
												echo "<a class='btn btn-sm bg-danger-light delete_review' data-toggle='modal' href='#delete_modal' data-review-id='$reviewId'>";
												echo "<i class='fe fe-trash'></i> Delete";
												echo "</a>";
												echo "</div>";
												echo "</td>";
												echo "</tr>";
											}
										}
									} else {
										// Handle error if the query fails
										echo '<tr><td colspan="6" class="text-center">Error fetching reviews: ' . mysqli_error($connect) . '</td></tr>';
									}
									?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Wrapper -->


    <!-- Delete Modal -->
    <div class="modal fade" id="delete_modal" aria-hidden="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-content p-2">
                        <h4 class="modal-title">Delete</h4>
                        <p class="mb-4">Are you sure want to delete?</p>
                        <!-- Add an ID to the Yes button to easily target it in JavaScript -->
                        <button type="button" class="btn btn-rounded btn-primary" id="deleteReviewButton">Yes</button>
                        <button type="button" class="btn btn-rounded btn-danger" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Attach a click event to all elements with the class 'delete_review'
$('.delete_review').click(function() {
    // Get the review ID from the data attribute of the clicked element
    currentReviewId = $(this).data('review-id');

    console.log(currentReviewId);

    // Set the data-review-id attribute of the modal's 'Yes' button
    $('#deleteReviewButton').data('review-id', currentReviewId);
});

// Use class selector for the "Yes" button
$('#deleteReviewButton').click(function() {
    // Get the review ID from the data-review-id attribute of the 'Yes' button
    currentReviewId = $(this).data('review-id');

    // Make an AJAX request to delete the review
    $.ajax({
        url: 'assets/delete_reviews.php',
        method: 'POST',
        data: {
            currentReviewId: currentReviewId,
        },
        success: function(response) {
            console.log(response);

            // Handle the response from the server
            alert('Review deleted');

            // Reload the page after successful deletion
            location.reload();
        },
        error: function(error) {
            // Handle errors
            console.error('Error deleting:', error);
        }
    });
});
</script>

<script>
$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('.datatable')) {
        $('.datatable').DataTable().destroy();
    }
    $('.datatable').DataTable({

    });
});
</script>
<!-- /Delete Modal -->

<!-- jQuery -->
<script src="assets/js/jquery-3.2.1.min.js"></script>

<!-- Bootstrap Core JS -->
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

<!-- Slimscroll JS -->
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Datatables JS -->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/datatables.min.js"></script>

<!-- Custom JS -->
<script src="assets/js/script.js"></script>

</body>

<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/reviews.php by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:52 GMT -->

</html>