<!-- include the header first -->
<?php require_once('assets/header.php') ?>

<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-3 col-auto">
                    <h3 class="page-title">Records</h3>
                    <!-- <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Records</li>
                    </ul> -->
                </div>
                <div class="col-sm-9 col-auto">
                    <div>
                        <form method="post" action="assets/records_generate_csv.php">
                            <button type="submit" name="records_generate_file" class="btn btn-rounded btn-primary float-right" style="margin: 2px;">Generate File</button>
                        </form>
                    </div>
                    <div>
                        <form method="post" action="assets/appts_generate_by_doctor_csv.php">
                            <button type="submit" name="patients_generate_file" class="btn btn-rounded btn-primary float-right" style="margin: 2px;">Generate by Doctor</button>
                        </form>
                    </div>
                    <div>
                        <a href="#choose_date" data-toggle="modal" class="btn btn-rounded btn-primary float-right" style="margin: 2px;">Generate by Date</a>
                    </div>
                    <div>
                        <a href="#search_by_name" data-toggle="modal" class="btn btn-rounded btn-primary float-right" style="margin: 2px;">Search by Name</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Fetch Records data from the database -->
                        <?php
                        $sql = "SELECT r.record_id, r.doctor_id, r.user_id, r.description, r.record_date, r.attachment_title, r.attachment_path,
                                        u_patient.fname AS patient_fname, u_patient.lname AS patient_lname,
                                        u_doctor.fname AS doctor_fname, u_doctor.lname AS doctor_lname
                                FROM records r
                                LEFT JOIN users u_patient ON r.user_id = u_patient.id
                                LEFT JOIN users u_doctor ON r.doctor_id = u_doctor.id";
                        $result = $connect->query($sql);
                        ?>

                        <!-- Display Records in the table -->
                        <div class="table-responsive">
                            <table class="datatable table table-hover table-center mb-0">
                                <thead>
                                    <tr>
                                        <th>Record Title</th>
                                        <th>Patient</th>
                                        <th>Doctor</th>
                                        <th>Date</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Check if there are rows in the result set
                                    if ($result->num_rows > 0) {
                                        // Loop through each row and display the data
                                        while ($row = $result->fetch_assoc()) {
                                            $recordName = $row['attachment_title'];
                                            $recordDate = date('M-d-Y', strtotime($row['record_date']));
                                            $recordId = $row['record_id'];
                                            $patientName = $row['patient_fname'] . ' ' . $row['patient_lname'];
                                            $doctorName = $row['doctor_fname'] . ' ' . $row['doctor_lname'];
                                    ?>
                                            <tr>
                                                <td>
                                                    <h2 class="table-avatar">
                                                        <a href="#"><?php echo $recordName; ?></a>
                                                    </h2>
                                                </td>
                                                <td><?php echo $patientName; ?></td>
                                                <td><?php echo $doctorName; ?></td>
                                                <td><?php echo $recordDate; ?></td>
                                                <td class="text-center">
                                                    <div class="actions">
                                                        <a href='<?php echo '../assets/' . $row['attachment_path']; ?>' target="_blank" class='btn btn-sm bg-primary-light'>
                                                            <i class="fas fa-eye"></i> View
                                                        </a>
                                                        <a data-toggle="modal" href="#delete_modal" class="btn btn-sm bg-danger-light delete_record" data-record-id='<?php echo $recordId; ?>'>
                                                            <i class="fe fe-trash"></i> Delete
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        // Display a message if no records are found
                                        echo '<tr><td colspan="5">No Records found</td></tr>';
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

<!-- Delete Modal -->
<div class="modal fade" id="delete_modal" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-content p-2">
                    <h4 class="modal-title">Delete</h4>
                    <p class="mb-4">Are you sure want to delete?</p>
                    <button type="button" class="btn btn-rounded btn-primary" id="deleterecordButton">Yes </button>
                    <button type="button" class="btn btn-rounded btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Attach a click event to all elements with the class 'delete_appt'
    $('.delete_record').click(function() {
        // Get the record ID from the data attribute of the clicked element
        currentrecordId = $(this).data('record-id');

        console.log(currentrecordId);

        // Set the data-record-id attribute of the modal's 'Yes' button
        $('#deleterecordButton').data('record-id', currentrecordId);
    });

    // Use class selector for the "Yes" button
    $('#deleterecordButton').click(function() {
        // Get the record ID from the data-record-id attribute of the 'Yes' button
        currentrecordId = $(this).data('record-id');

        // Make an AJAX request to delete the record
        $.ajax({
            url: 'assets/delete_record.php',
            method: 'POST',
            data: {
                currentrecordId: currentrecordId,
            },
            success: function(response) {
                console.log(response);

                // Handle the response from the server
                alert('Record deleted');

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

<!-- Choose date modal -->
<div class="modal fade" id="choose_date" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-content p-2">
                    <h4 class="modal-title">Choose Start and End Dates</h4>
                    <form id="generateCsvForm" method="post" action="assets/appts_generate_by_date_csv.php">
                        <div class="form-group">
                            <label for="start_date">Start Date:</label>
                            <input type="date" class="form-control" id="start_date" name="start_date">
                        </div>
                        <div class="form-group">
                            <label for="end_date">End Date:</label>
                            <input type="date" class="form-control" id="end_date" name="end_date">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-rounded btn-primary" id="generateCsvButton">Generate CSV</button>
                            <button type="button" class="btn btn-rounded btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Event listener for the form submission
    $('#generateCsvForm').submit(function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Retrieve the selected start and end dates from the datepicker inputs
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();

        // Make an AJAX request to generate the CSV file
        $.ajax({
            url: 'assets/records_generate_by_date_csv.php',
            method: 'POST',
            data: {
                startDate: startDate,
                endDate: endDate
            },
            success: function(response) {
                // Check if the response contains any data
                if (response.trim() !== '') {
                    // Create a blob object from the response data
                    var blob = new Blob([response], {
                        type: 'text/csv'
                    });

                    // Create a temporary URL for the blob object
                    var url = window.URL.createObjectURL(blob);

                    // Create a download link
                    var downloadLink = document.createElement('a');
                    downloadLink.href = url;
                    downloadLink.download = 'records_by_date_export.csv';
                    downloadLink.click();

                    // Release the allocated memory
                    window.URL.revokeObjectURL(url);

                    // Close the modal
                    $('#generateCsvForm').modal('hide');

                    window.location.reload();
                } else {
                    console.error('Empty response received.');
                }
            },

            error: function(xhr, status, error) {
                console.error('Error generating CSV:', error);
            }
        });
    });
</script>
<!-- /Choose date modal -->

<!-- Search by name modal -->
<div class="modal fade" id="search_by_name" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-content p-2">
                    <h4 class="modal-title">Search by Patient Name</h4>
                    <form id="searchByNameForm">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter the patient's first name or surname">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-rounded btn-primary">Search</button>
                            <button type="button" class="btn btn-rounded btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Event listener for the form submission
        $('#searchByNameForm').submit(function(e) {
            e.preventDefault(); // Prevent the default form submission

            // Retrieve the name input value
            var name = $('#name').val();

            // Make an AJAX request to search by name
            $.ajax({
                url: 'assets/search_by_name.php',
                method: 'POST',
                data: {
                    name: name
                },
                success: function(response) {
                    // Update the table content with the response
                    $('.table-responsive').html(response);

                    // Close the modal
                    $('#search_by_name').modal('hide');
                },
                error: function(xhr, status, error) {
                    console.error('Error finding name:', error);
                }
            });
        });
    });
</script>
<!-- /Search by name modal -->

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