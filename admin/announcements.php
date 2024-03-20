<!-- include the header first -->
<?php require_once('assets/header.php') ?>

<!-- Page Wrapper -->
<div class="page-wrapper">
	<div class="content container-fluid">

		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col-sm-7 col-auto">
					<h3 class="page-title">Announcements</h3>
					<!-- <ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Announcements</li>
					</ul> -->
				</div>
				<div class="col-sm-5 col">
					<a href="#Add_announcements_details" data-toggle="modal" class="btn btn-rounded btn-primary float-right mt-2">Add</a>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-body">

						<!-- Fetch announcements data from the database -->
						<?php
						$sql = "SELECT * FROM announcements";
						$result = $connect->query($sql);
						?>

						<!-- Display announcements in the table -->
						<div class="table-responsive">
							<table class="datatable table table-hover table-center mb-0">
								<thead>
									<tr>
										<th>Announcements</th>
										<th class="text-center">Actions</th>
									</tr>
								</thead>
								<tbody>
									<?php
									// Check if there are rows in the result set
									if ($result->num_rows > 0) {
										// Loop through each row and display the data
										while ($row = $result->fetch_assoc()) {
											$announcementName = $row['description'];
											$announcementIcon = $row['pic_path'];
											$announcementId = $row['announcement_id'];
									?>
											<tr>
												<td>
													<h2 class="table-avatar">
														<a href="#" class="avatar avatar-sm mr-2">
															<img class="avatar-img" src="<?php echo $announcementIcon; ?>" alt="<?php echo $announcementName; ?>">
														</a>
														<a href="#"><?php echo $announcementName; ?></a>
													</h2>
												</td>
												<td class="text-center">
													<div class="actions">
														<a data-toggle="modal" href="#delete_modal" class="btn btn-rounded btn-sm bg-danger-light delete_announcement" data-announcement-id='<?php echo $announcementId;?>'>
															<i class="fe fe-trash"></i> Delete
														</a>
													</div>
												</td>
											</tr>
									<?php
										}
									} else {
										// Display a message if no announcements are found
										echo '<tr><td colspan="2">No announcements found</td></tr>';
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
</div>
<!-- /Page Wrapper -->

<!-- Add Modal -->
<div class="modal fade" id="Add_announcements_details" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add announcements</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addannouncementsForm">
                    <div class="row form-row">
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>announcements</label>
                                <input type="text" class="form-control" name="announcementName" required>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Image</label>
                                <input type="file" class="form-control" name="announcementIcon" required>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-rounded btn-primary btn-block" onclick="addannouncements()">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function addannouncements() {
    var formData = new FormData($('#addannouncementsForm')[0]);

    $.ajax({
        url: 'assets/add_announcement.php', 
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            console.log(response);

            alert('Announcement added successfully');

            // Close the modal
            $('#Add_announcements_details').modal('hide');

            window.location.reload();
        },
        error: function(error) {
            console.error('Error adding announcements:', error.responseText);
            alert('Error adding announcements. Please try again.');
        }
    });
}
</script>

<!-- /Add Modal -->

<!-- Delete Modal -->
<div class="modal fade" id="delete_modal" aria-hidden="true" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<div class="form-content p-2">
					<h4 class="modal-title">Delete</h4>
					<p class="mb-4">Are you sure want to delete?</p>
					<button type="button" class="btn btn-rounded btn-primary" id="deleteannouncementButton">Yes </button>
					<button type="button" class="btn btn-rounded btn-danger" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	// Attach a click event to all elements with the class 'delete_appt'
	$('.delete_announcement').click(function() {
		// Get the announcement ID from the data attribute of the clicked element
		currentannouncementId = $(this).data('announcement-id');

		console.log(currentannouncementId);

		// Set the data-announcement-id attribute of the modal's 'Yes' button
		$('#deleteannouncementButton').data('announcement-id', currentannouncementId);
	});

	// Use class selector for the "Yes" button
	$('#deleteannouncementButton').click(function() {
		// Get the announcement ID from the data-announcement-id attribute of the 'Yes' button
		currentannouncementId = $(this).data('announcement-id');

		// Make an AJAX request to delete the announcement
		$.ajax({
			url: 'assets/delete_announcement.php',
			method: 'POST',
			data: {
				currentannouncementId: currentannouncementId,
			},
			success: function(response) {
				console.log(response);

				// Handle the response from the server
				alert('Announcement deleted');

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

</div>
<!-- /Delete Modal -->
</div>
<!-- /Main Wrapper -->

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

<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/specialities.php by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:51 GMT -->

</html>