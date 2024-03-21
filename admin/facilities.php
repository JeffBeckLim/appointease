<!-- include the header first -->
<?php require_once('assets/header.php') ?>

<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-7 col-auto">
                    <h3 class="page-title">Facilities</h3>
                    <!-- <ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Facilities</li>
					</ul> -->
                </div>
                <div class="col-sm-5 col">
                    <a href="#Add_Facilities_details" data-toggle="modal"
                        class="btn btn-rounded btn-primary float-right mt-2">Add</a>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">

                        <!-- Fetch facilities data from the database -->
                        <?php
						$sql = "SELECT * FROM facilities";
						$result = $connect->query($sql);
						?>

                        <!-- Display facilities in the table -->
                        <div class="table-responsive">
                            <table class="datatable table table-hover table-center mb-0">
                                <thead>
                                    <tr>
                                        <th>Facilities</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
									// Check if there are rows in the result set
									if ($result->num_rows > 0) {
										// Loop through each row and display the data
										while ($row = $result->fetch_assoc()) {
											$facilityName = $row['description'];
											$facilityIcon = $row['icon'];
											$facilityId = $row['facility_id'];
									?>
                                    <tr>
                                        <td>
                                            <h2 class="table-avatar">
                                                <a href="#" class="avatar avatar-sm mr-2">
                                                    <img class="avatar-img" src="<?php echo $facilityIcon; ?>"
                                                        alt="<?php echo $facilityName; ?>">
                                                </a>
                                                <a href="#"><?php echo $facilityName; ?></a>
                                            </h2>
                                        </td>
                                        <td class="text-center">
                                            <div class="actions">
                                                <a data-toggle="modal" href="#delete_modal"
                                                    class="btn btn-sm bg-danger-light delete_facility"
                                                    data-facility-id='<?php echo $facilityId;?>'>
                                                    <i class="fe fe-trash"></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
										}
									} else {
										// Display a message if no facilitys are found
										echo '<tr><td colspan="2">No facilities found</td></tr>';
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
<div class="modal fade" id="Add_Facilities_details" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Facilities</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addFacilitiesForm">
                    <div class="row form-row">
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Facilities</label>
                                <input type="text" class="form-control" name="facilityName" required>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Image</label>
                                <input type="file" class="form-control" name="facilityIcon" required>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-rounded btn-primary btn-block" onclick="addFacilities()">Save
                        Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function addFacilities() {
    var formData = new FormData($('#addFacilitiesForm')[0]);

    $.ajax({
        url: 'assets/add_facilities.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            console.log(response);

            alert('Service added successfully');

            // Close the modal
            $('#Add_Facilities_details').modal('hide');

            window.location.reload();
        },
        error: function(error) {
            console.error('Error adding facilities:', error.responseText);
            alert('Error adding facilities. Please try again.');
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
                    <button type="button" class="btn btn-rounded btn-primary" id="deletefacilityButton">Yes </button>
                    <button type="button" class="btn btn-rounded btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
// Attach a click event to all elements with the class 'delete_appt'
$('.delete_facility').click(function() {
    // Get the facility ID from the data attribute of the clicked element
    currentfacilityId = $(this).data('facility-id');

    console.log(currentfacilityId);

    // Set the data-facility-id attribute of the modal's 'Yes' button
    $('#deletefacilityButton').data('facility-id', currentfacilityId);
});

// Use class selector for the "Yes" button
$('#deletefacilityButton').click(function() {
    // Get the facility ID from the data-facility-id attribute of the 'Yes' button
    currentfacilityId = $(this).data('facility-id');

    // Make an AJAX request to delete the facility
    $.ajax({
        url: 'assets/delete_facility.php',
        method: 'POST',
        data: {
            currentfacilityId: currentfacilityId,
        },
        success: function(response) {
            console.log(response);

            // Handle the response from the server
            alert('Facility deleted');

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