<!-- include the header first -->
<?php require_once('assets/header.php') ?>
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-7 col-auto">
                    <h3 class="page-title">Services</h3>
                    <!-- <ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Services</li>
					</ul> -->
                </div>
                <div class="col-sm-5 col">
                    <a href="#Add_Service_details" data-toggle="modal"
                        class="btn btn-rounded btn-primary float-right mt-2">Add</a>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">

                        <!-- Fetch services data from the database -->
                        <?php
						$sql = "SELECT * FROM services";
						$result = $connect->query($sql);
						?>

                        <!-- Display services in the table -->
                        <div class="table-responsive">
                            <table class="datatable table table-hover table-center mb-0">
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Specialties</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
									// Check if there are rows in the result set
									if ($result->num_rows > 0) {
										// Loop through each row and display the data
										while ($row = $result->fetch_assoc()) {
											$serviceName = $row['service'];
											$serviceIcon = $row['icon'];
											$serviceId = $row['service_id'];
											$specialty = $row['specialty'];
									?>
                                    <tr>
                                        <td>
                                            <h2 class="table-avatar">
                                                <a href="#" class="avatar avatar-sm mr-2">
                                                    <img class="avatar-img" src="<?php echo $serviceIcon; ?>"
                                                        alt="<?php echo $serviceName; ?>">
                                                </a>
                                                <a href="#"><?php echo $serviceName; ?></a>
                                            </h2>
                                        </td>
                                        <td>
                                            <h2 class="table-avatar">
                                                <a href="#"><?php echo $specialty; ?></a>
                                            </h2>
                                        </td>
                                        <td class="text-center">
                                            <div class="actions">
                                                <!-- Add data-service-id attribute to store the service ID -->
                                                <a href="#" class='btn btn-sm bg-info-light edit_service'
                                                    data-service-id='<?php echo $serviceId; ?>'
                                                    data-service-name='<?php echo $serviceName; ?>'
                                                    data-service-specialties='<?php echo $specialty; ?>'>
                                                    <i class='fe fe-pencil'></i> Edit
                                                </a>
                                                &nbsp;
                                                <a href="#delete_modal"
                                                    class="btn btn-sm bg-danger-light delete_service"
                                                    data-toggle="modal" data-service-id='<?php echo $serviceId; ?>'>
                                                    <i class="fe fe-trash"></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
										}
									} else {
										// Display a message if no services are found
										echo '<tr><td colspan="2">No services found</td></tr>';
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
<div class="modal fade" id="Add_Service_details" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Service</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addServiceForm">
                    <div class="row form-row">
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Service Name</label>
                                <input type="text" class="form-control" name="serviceName" required>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Picture</label>
                                <input type="file" class="form-control" name="serviceIcon" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label>Specialties</label>
                        <input class="form-control" type="text" name="specialties" required>
                        <small class="form-text text-muted">Note: Type in comma separated list</small>
                    </div>
                    <button type="button" class="btn btn-rounded btn-primary btn-block" onclick="addService()">Save
                        Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
function addService() {
    // Create a FormData object to handle file uploads
    var formData = new FormData($('#addServiceForm')[0]);

    // Make an AJAX request to handle form submission
    $.ajax({
        url: 'assets/add_service.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            // Handle the response from the server
            console.log(response);

            alert('Service added successfully');

            // Close the modal
            $('#Add_Service_details').modal('hide');

            window.location.reload();
        },
        error: function(error) {
            // Handle errors
            console.error('Error adding service:', error.responseText);
            alert('Error adding service. Please try again.');
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
                    <button type="button" class="btn btn-rounded btn-primary" id="deleteServiceButton">Yes </button>
                    <button type="button" class="btn btn-rounded btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
// Attach a click event to all elements with the class 'delete_appt'
$('.delete_service').click(function() {
    // Get the service ID from the data attribute of the clicked element
    currentServiceId = $(this).data('service-id');

    console.log(currentServiceId);

    // Set the data-service-id attribute of the modal's 'Yes' button
    $('#deleteServiceButton').data('service-id', currentServiceId);
});

// Use class selector for the "Yes" button
$('#deleteServiceButton').click(function() {
    // Get the service ID from the data-service-id attribute of the 'Yes' button
    currentServiceId = $(this).data('service-id');

    // Make an AJAX request to delete the service
    $.ajax({
        url: 'assets/delete_service.php',
        method: 'POST',
        data: {
            currentServiceId: currentServiceId,
        },
        success: function(response) {
            console.log(response);

            // Handle the response from the server
            alert('Service deleted');

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
<!-- /Delete Modal -->

<!-- Edit Modal -->
<div class="modal fade" id="edit_modal" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Service</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editServiceForm">
                    <div class="row form-row">
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Service Name</label>
                                <input type="text" class="form-control" name="serviceName" id="editServiceName"
                                    required>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Picture</label>
                                <input type="file" class="form-control" name="serviceIcon" id="editServiceIcon"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label>Specialties</label>
                        <input class="form-control" type="text" name="specialties" id="editSpecialties" required>
                        <small class="form-text text-muted">Note: Type in comma separated list</small>
                    </div>
                    <button type="button" class="btn btn-rounded btn-primary btn-block" onclick="updateService()">Save
                        Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
var serviceId = 0

// Function to populate the edit modal with service data
function populateModal(serviceData) {
    // Set input field values based on service data
    $('#editServiceId').val(serviceData.id);
    $('#editServiceName').val(serviceData.name);
    $('#editSpecialties').val(serviceData.specialties);

    // Show the modal
    $('#edit_modal').modal('show');
}

// Function to handle edit service button click
$('.edit_service').click(function() {
    serviceId = $(this).data('service-id');
    var serviceName = $(this).data('service-name');
    var serviceSpecialties = $(this).data('service-specialties');

    // Call populateModal function with the extracted data
    populateModal({
        id: serviceId,
        name: serviceName,
        specialties: serviceSpecialties
    });
});

function updateService() {
    // Create a FormData object to handle file uploads
    var formData = new FormData($('#editServiceForm')[0]);

    // Append currentServiceId to formData
    formData.append('serviceId', serviceId);

    // Make an AJAX request to handle form submission
    $.ajax({
        url: 'assets/update_service.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            // Handle the response from the server
            console.log(response);

            alert('Service updated successfully');

            // Close the modal
            $('#edit_modal').modal('hide');

            window.location.reload();
        },
        error: function(error) {
            // Handle errors
            console.error('Error updating service:', error.responseText);
            alert('Error updating service. Please try again.');
        }
    });
}
</script>

<script>
$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('.datatable')) {
        $('.datatable').DataTable().destroy();
    }
    $('.datatable').DataTable({
        sorting: true // Enable sorting
    });
});
</script>
</div>
<!-- /Edit Modal -->

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