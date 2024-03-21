<!-- Footer -->
<footer class="footer">

    <!-- get user type for dynamic display-->

    <!-- Footer Top -->
    <div class="footer-top">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-6">

                    <!-- Footer Widget -->
                    <div class="footer-widget footer-about">
                        <div class="footer-logo">
                            <img src="assets/img/footer-logo.png" width="201" height="52" style="object-fit: contain">
                        </div>
                        <div class="footer-about-content">
                            <p>
                                Experience easier booking of our doctors, and
                                get all your medical records all at one place.
                            </p>
                            <div class="social-icon">
                                <ul>
                                    <li>
                                        <a href="https://legazpi.gov.ph/health/" target="_blank"><i
                                                class="fas fa-landmark"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- /Footer Widget -->

                </div>

                <?php // Check if there is an active session and user type is set
                if (isset($_SESSION['id']) && isset($_SESSION['usertype'])) :
                    $userType = $_SESSION['usertype'];

                    if ($userType == 'patient') :
                        // Show footer for patients
                ?>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget footer-menu">
                        <h2 class="footer-title">For Patients</h2>
                        <ul>
                            <li><a href="patient-dashboard.php?page=patients&sub=patdash"><i
                                        class="fas fa-angle-double-right"></i> Patient Dashboard</a></li>
                            <li><a href="search.php?page=patients&sub=searchdoc"><i
                                        class="fas fa-angle-double-right"></i> Search for Doctors</a></li>
                        </ul>
                    </div>
                </div>
                <?php
                    elseif ($userType == 'practitioner') :
                        // Show footer for practitioner
                    ?>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget footer-menu">
                        <h2 class="footer-title">For Practitioners</h2>
                        <ul>
                            <li><a href="doctor-dashboard.php?page=doctor&sub=dashboard"><i
                                        class="fas fa-angle-double-right"></i> Doctor Dashboard</a></li>
                            <li><a href="schedule-timings.php?page=doctor&sub=sched"><i
                                        class="fas fa-angle-double-right"></i> Schedule Timing</a></li>
                            <li><a href="my-patients.php?page=doctor&sub=patients"><i
                                        class="fas fa-angle-double-right"></i> Patients List</a></li>
                            <li><a href="reviews.php?page=doctor&sub=reviews"><i class="fas fa-angle-double-right"></i>
                                    Reviews</a></li>
                        </ul>
                    </div>
                </div>
                <?php
                    endif;
                endif; ?>

                <div class="col-lg-6 col-md-6">

                    <!-- Footer Widget -->
                    <div class="footer-widget footer-contact">
                        <h2 class="footer-title">Contact Us</h2>
                        <div class="footer-contact-info">
                            <div class="footer-address">
                                <span><i class="fas fa-map-marker-alt"></i></span>
                                <p>City Hall Compound, Rizal Street, Old Albay District, Legazpi City </p>
                            </div>
                            <p>
                                <i class="fas fa-phone-alt"></i>
                                (+63) 977 183 3638
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-envelope"></i>
                                cholegazpi@gmail.com
                            </p>
                        </div>
                    </div>
                    <!-- /Footer Widget -->

                </div>

            </div>
        </div>
    </div>
    <!-- /Footer Top -->

</footer>
<!-- /Footer -->

</div>
<!-- /Main Wrapper -->

<!-- View Booking Modal -->
<?php if ($_SESSION['subpage'] == 'dashboard' || $_SESSION['subpage'] == 'patients-profile') : ?>
<div class="modal fade custom-modal" id="view_booking">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View Booking</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="viewBookForm">
                    <div class="row form-row">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="viewBookStartTime">Start Time</label>
                                <div class="input-group date" id="add_doctor_schedule_start_time"
                                    data-target-input="nearest">
                                    <input type="text" id="viewBookStartTime" name="view_book_start_time"
                                        class="form-control datetimepicker-input" data-target="#view_book_start_time"
                                        required onkeydown="return false" onpaste="return false;" ondrop="return false;"
                                        autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="viewBookDuration">Duration (mins.)</label>
                                <div class="input-group date" id="add_doctor_schedule_start_time"
                                    data-target-input="nearest">
                                    <input type="text" id="viewBookDuration" name="view_book_duration"
                                        class="form-control datetimepicker-input" data-target="#view_book_duration"
                                        required onkeydown="return false" onpaste="return false;" ondrop="return false;"
                                        autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="viewBookDate">Date</label>
                                <div class="input-group date" id="add_doctor_schedule_start_time"
                                    data-target-input="nearest">
                                    <input type="text" id="viewBookDate" name="view_book_date"
                                        class="form-control datetimepicker-input" data-target="#view_book_date" required
                                        onkeydown="return false" onpaste="return false;" ondrop="return false;"
                                        autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="viewBookSpecialties">Doctor Specialties</label>
                                <div class="row" id="viewBookSpecialtiesContainer"></div>
                            </div>
                        </div>
                        <div class="col-16 col-md-12">
                            <div class="form-group">
                                <label for="viewBookReason">Reason for Appointment</label>
                                <div class="input-group date" id="add_doctor_schedule_start_time"
                                    data-target-input="nearest">
                                    <textarea id="viewBookReason" name="view_book_reason" class="form-control" rows="2"
                                        required style="resize: none;" readonly></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-16 col-md-12">
                            <div class="form-group">
                                <label for="viewBookComment">Doctor's Comments</label>
                                <div class="input-group date" id="add_doctor_schedule_start_time"
                                    data-target-input="nearest">
                                    <textarea id="viewBookComment" name="view_book_comment" class="form-control"
                                        rows="3" required style="resize: none;"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="submit-section text-center">
                        <button type="button" class="btn btn-rounded btn-primary submit-btn"
                            id="completeBookingButton">Complete</button>
                        <button type="button" class="btn btn-rounded btn-primary submit-btn"
                            id="confirmBookingButton">Confirm</button>
                        &nbsp;
                        <button type="button" class="btn btn-rounded btn-danger submit-btn"
                            id="cancelBookingButton">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var doctorComments = '';

    function populateModal(viewStartTime, viewDuration, viewDate, viewReason, doctorComments) {

        // Set the value of the datetime picker
        $('#viewBookStartTime').val(viewStartTime);
        $('#viewBookDuration').val(viewDuration);
        $('#viewBookDate').val(viewDate);
        $('#viewBookReason').val(viewReason);
        $('#viewBookComment').val(doctorComments);

        console.log(viewStartTime + viewDate + viewReason + doctorComments)
    }

    // Event listener for the viewing the booking time slot
    $(document).on('click', '.view_booking', function() {

        // Get the schedule ID from the data attribute
        currentScheduleId = $(this).data('appointment-id');

        console.log(currentScheduleId)

        // Get the values from the row
        var viewStartTime = $(this).closest('tr').find('td:nth-child(3)').text();
        var viewDuration = $(this).closest('tr').find('td:nth-child(4)').text();
        var viewBookDate = $(this).closest('tr').find('td:nth-child(2)').text();
        var viewBookReason = $(this).closest('tr').find('td[data-appointment-reason]').data(
            'appointment-reason');
        doctorComments = $(this).closest('tr').find('td[data-doctor-comments]').data('doctor-comments');

        // for showing specialties chosen
        var specialties = $(this).data('specialties');
        var specialtiesContainer = $('#viewBookSpecialtiesContainer');

        if (specialties) {
            var specialtiesArray = specialties.split(',');
            specialtiesContainer.empty();

            $.each(specialtiesArray, function(index, specialty) {
                specialtiesContainer.append('<div class="col-6">' +
                    '<div class="form-check">' +
                    '<input class="form-check-input" type="checkbox" value="' + specialty +
                    '" name="doctorSpecialties[]" id="doctorSpecialty' + specialty +
                    '" checked disabled>' +
                    '<label class="form-check-label" for="doctorSpecialty' + specialty +
                    '">' + specialty + '</label>' +
                    '</div>' +
                    '</div>');
            });
        } else {
            specialtiesContainer.html('<div class="col-12">No specialties chosen.</div>');
        }

        // Populate the modal with the values
        populateModal(viewStartTime, viewDuration, viewBookDate, viewBookReason, doctorComments);
    });

    // Event listener for confirming the booking
    $('#completeBookingButton').click(function() {

        var appointment_status = "Completed";
        var doctorComments = $('#viewBookComment').val();

        console.log(appointment_status)

        // Send the data to the server for processing
        updateBookingDatabase(currentScheduleId, appointment_status, doctorComments);
    });

    // Event listener for confirming the booking
    $('#confirmBookingButton').click(function() {

        var appointment_status = "Confirmed";
        var doctorComments = $('#viewBookComment').val();

        console.log(appointment_status)

        // Send the data to the server for processing
        updateBookingDatabase(currentScheduleId, appointment_status, doctorComments);
    });

    // Event listener for cancelling the booking
    $('#cancelBookingButton').click(function() {

        var appointment_status = "Cancelled";
        var doctorComments = $('#viewBookComment').val();

        console.log(appointment_status)

        // Send the data to the server for processing
        updateBookingDatabase(currentScheduleId, appointment_status, doctorComments);
    });

    //Function to send data to the server for confirming the booking
    function updateBookingDatabase(currentScheduleId, appointment_status, doctorComments) {
        console.log("currentScheduleId:", currentScheduleId);
        console.log("appointment_status:", appointment_status);
        console.log("doctorComments:", doctorComments);

        $.ajax({
            url: 'assets/update-booking-status.php',
            method: 'POST',
            data: {
                doctorComments: doctorComments,
                currentScheduleId: currentScheduleId,
                appointment_status: appointment_status
            },
            success: function(response) {
                // Handle the response from the server
                alert('Booking updated');

                // Reload the page after successful addition
                location.reload();
            },
            error: function(error) {
                // Handle errors
                console.error('Error confirming booking:', error);
            }
        });
    }
});
</script>

<script>
$(document).ready(function() {
    // Handle click event for "Get Appointments" button
    $('#getAppointments').click(function() {
        // Get the selected date
        var selectedDate = $('#appointment_date').val();
        var user_id = $('#doctor_id').val();

        document.getElementById('printPDF').style.display = 'block';

        // Make AJAX request to get appointments for the selected date
        $.ajax({
            url: 'assets/get-date-doctor-dashboard.php',
            method: 'GET',
            data: {
                appointment_date: selectedDate,
                user_id: user_id
            },
            success: function(response) {
                // Update the table body with the retrieved data
                $('#appointmentsTable').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });
});

document.getElementById('printPDF').addEventListener('click', function() {
    // Retrieve the selected date and user ID
    var selectedDate = $('#appointment_date').val();
    var user_id = $('#doctor_id').val();

    // Check if a date is selected
    if (selectedDate !== '') {
        // Redirect to the PHP script with query parameters to generate the PDF
        window.location.href = 'assets/pdf-doctor-dashboard.php?appointment_date=' + selectedDate +
            '&user_id=' + user_id;
    } else {
        // Display an error message or handle the case when no date is selected
        console.error('Please select a date.');
    }
});
</script>

<?php endif; ?>
<!-- /View Booking Modal -->

<!-- Book Slot modal -->
<?php if ($_SESSION['subpage'] == 'booking') : ?>
<div class="modal fade custom-modal" id="book_time_slot">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Are you sure you want to book this time?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="bookTimeSlotForm">
                    <input type="hidden" id="userId" value="<?php echo $_SESSION['id']; ?>">
                    <input type="hidden" id="doctorId" value="<?php echo $doctorId; ?>">
                    <div class="row form-row">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="bookStartTime">Start Time</label>
                                <div class="input-group date" id="add_doctor_schedule_start_time"
                                    data-target-input="nearest">
                                    <input type="text" id="bookStartTime" name="book_time_slot_start_time"
                                        class="form-control datetimepicker-input"
                                        data-target="#book_time_slot_start_time" required onkeydown="return false"
                                        onpaste="return false;" ondrop="return false;" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="bookDuration">Duration (mins.)</label>
                                <div class="input-group date" id="add_doctor_schedule_start_time"
                                    data-target-input="nearest">
                                    <input type="text" id="bookDuration" name="book_time_slot_end_time"
                                        class="form-control datetimepicker-input" data-target="#book_time_slot_duration"
                                        required onkeydown="return false" onpaste="return false;" ondrop="return false;"
                                        autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="bookDate">Date</label>
                                <div class="input-group date" id="add_doctor_schedule_start_time"
                                    data-target-input="nearest">
                                    <input type="text" id="bookDate" name="book_doctor_schedule_date"
                                        class="form-control datetimepicker-input" data-target="#book_time_slot_date"
                                        required onkeydown="return false" onpaste="return false;" ondrop="return false;"
                                        autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="doctorSpecialties">Doctor Specialties</label>
                                <div class="row">
                                    <?php
                                        $specialtiesArray = explode(',', $doctorSpecialties);
                                        $count = count($specialtiesArray);
                                        $columnCount = ceil($count / 2);

                                        // Split specialties into two columns
                                        for ($i = 0; $i < 2; $i++) {
                                            echo '<div class="col-6">';
                                            for ($j = $i * $columnCount; $j < min(($i + 1) * $columnCount, $count); $j++) {
                                                $specialty = $specialtiesArray[$j];
                                                echo '<div class="form-check">';
                                                echo '<input class="form-check-input" type="checkbox" value="' . $specialty . '" name="doctorSpecialties[]" id="doctorSpecialty' . $specialty . '">';
                                                echo '<label class="form-check-label" for="doctorSpecialty' . $specialty . '">' . $specialty . '</label>';
                                                echo '</div>';
                                            }
                                            echo '</div>';
                                        }
                                        ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-16 col-md-12">
                            <div class="form-group">
                                <label for="bookReason">Reason for Appointment</label>
                                <div class="input-group date" id="add_doctor_schedule_start_time"
                                    data-target-input="nearest">
                                    <textarea id="bookReason" name="book_time_slot_reason" class="form-control" rows="4"
                                        required style="resize: none;"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="submit-section text-center">
                        <button type="button" class="btn btn-rounded btn-primary submit-btn"
                            id="bookTimeSlotButton">Book Timeslot</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    function populateModal(bookStartTime, bookDuration, bookDate) {

        // Set the value of the datetime picker
        $('#bookStartTime').val(bookStartTime);
        $('#bookDuration').val(bookDuration);
        $('#bookDate').val(bookDate);

        console.log(bookStartTime + bookDate)
    }

    // Event listener for the confirming the booking time slot
    $('.book_time_slot').click(function() {
        // Get the schedule ID from the data attribute
        currentScheduleId = $(this).data('schedule-id');

        // Get the values from the row
        var startTime = $(this).closest('tr').find('td:nth-child(1)').text();
        var duration = $(this).closest('tr').find('td:nth-child(3)').text();
        var bookingDate = $(this).data('chosen-date');

        // Populate the modal with the values
        populateModal(startTime, duration, bookingDate);
    });

    // Event listener for the booking the time slot
    $('#bookTimeSlotButton').click(function() {
        // Get the selected start time and duration
        var bookStartTime = $('#bookStartTime').val();
        var bookDuration = $('#bookDuration').val();
        var bookDate = $('#bookDate').val();
        var bookReason = $('#bookReason').val();

        // hidden values
        var userid = $('#userId').val();
        var doctorid = $('#doctorId').val();

        // Get the selected doctor specialties
        var doctorSpecialties = [];
        $('input[name="doctorSpecialties[]"]:checked').each(function() {
            doctorSpecialties.push($(this).val());
        });

        // Send the data to the server for processing
        bookTimeSlotDatabase(doctorid, userid, currentScheduleId, bookStartTime, bookDuration, bookDate,
            bookReason, doctorSpecialties);
    });

    //Function to send data to the server for adding a new time slot
    function bookTimeSlotDatabase(doctorid, userid, currentScheduleId, bookStartTime, bookDuration, bookDate,
        bookReason, doctorSpecialties) {
        $.ajax({
            url: 'assets/book-timeslot.php',
            method: 'POST',
            data: {
                doctorid: doctorid,
                userid: userid,
                currentScheduleId: currentScheduleId,
                bookStartTime: bookStartTime,
                bookDuration: bookDuration,
                bookDate: bookDate,
                bookReason: bookReason,
                doctorSpecialties: doctorSpecialties
            },
            success: function(response) {
                // Handle the response from the server

                console.log('Value of bookDate:', bookDate);
                console.log('Value of bookDate:', response);

                alert('Booking successful!');
                // Reload the page after successful addition
                window.location.href = 'booking-success.php';
            },
            error: function(error) {
                // Handle errors
                console.error('Error adding time slot:', error);
            }
        });
    }

});
</script>

<!-- <script>
        // Handle form submission
        $('#searchDateForm').submit(function(e) {
            e.preventDefault(); // Prevent the form from submitting normally

            console.log('Booking Date Value:', $('#bookingDate').val());

            // Show the time slots section
            $('#timeSlotsSection').show();
        });
    </script> -->

<?php endif; ?>
<!-- /Book Slot modal -->

<!-- CRUD Time Slot modal -->
<?php if ($_SESSION['subpage'] == 'sched') : ?>
<!-- Add Time Slot Modal -->
<div class="modal fade custom-modal" id="add_time_slot">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Time Slots</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addTimeSlotForm">
                    <input type="hidden" id="doctorId" value="<?php echo $_SESSION['id']; ?>">
                    <input type="hidden" id="selectedDayInput" name="day" value="">
                    <div class="row form-row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="addStartTime">Start Time</label>
                                <div class="input-group date" id="add_doctor_schedule_start_time"
                                    data-target-input="nearest">
                                    <div class="input-group-prepend" data-target="#add_doctor_schedule_start_time"
                                        data-toggle="datetimepicker">
                                        <span class="input-group-text" id="basic-addon1"><i
                                                class="fas fa-clock"></i></span>
                                    </div>
                                    <input type="text" id="addStartTime" name="add_doctor_schedule_start_time"
                                        class="form-control datetimepicker-input"
                                        data-target="#add_doctor_schedule_start_time" required onkeydown="return false"
                                        onpaste="return false;" ondrop="return false;" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="addDuration">Duration (mins.)</label>
                                <select id="addDuration" class="form-control" name="duration">
                                    <option value="-">-</option>
                                    <option value="15">15 mins</option>
                                    <option value="30">30 mins</option>
                                    <option value="45">45 mins</option>
                                    <option value="60">60 mins</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="submit-section text-center">
                        <button type="button" class="btn btn-rounded btn-primary submit-btn" id="addTimeSlotButton">Add
                            Timeslot</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize lastSelectedDay from sessionStorage or a default value
    var lastSelectedDay = sessionStorage.getItem('lastSelectedDay') || 'Monday';

    // Set the last selected day based on the stored value
    $('.nav-tabs .nav-link').removeClass('active');
    $('.nav-tabs .nav-link:contains(' + lastSelectedDay + ')').addClass('active');

    // Show the corresponding tab content
    $('.tab-pane').removeClass('show active');
    $('#slot_' + lastSelectedDay.toLowerCase()).addClass('show active');

    // Event listener for tab click
    $('.nav-tabs .nav-link').click(function() {
        // Remove active class from all tabs
        $('.nav-tabs .nav-link').removeClass('active');

        // Add active class to the clicked tab
        $(this).addClass('active');

        // Update lastSelectedDay
        var selectedDay = $(this).text().trim();

        // Store the last selected day in sessionStorage
        sessionStorage.setItem('lastSelectedDay', selectedDay);

        // Set the value of the hidden input field
        $('#selectedDayInput').val(selectedDay);

        // Show the corresponding tab content
        $('.tab-pane').removeClass('show active');
        $('#slot_' + selectedDay.toLowerCase()).addClass('show active');
    });

    // Set the default selected day to Monday in the hidden input field
    $('#selectedDayInput').val(lastSelectedDay);

    // Event listener for the add button in the add time slot modal
    $('#addTimeSlotButton').click(function() {
        // Get the selected start time and duration
        var selectedStartTime = $('#addStartTime').val();
        var selectedDuration = $('#addDuration').val();

        // hidden values
        var doctorid = $('#doctorId').val();
        var selectedday = $('#selectedDayInput').val();

        // Compute the end time
        var selectedEndTime = computeEndTime(selectedStartTime, selectedDuration);

        // Send the data to the server for processing
        addTimeSlotToDatabase(doctorid, selectedday, selectedStartTime, selectedEndTime,
            selectedDuration);
    });

    // Function to compute the end time based on start time and duration
    function computeEndTime(startTime, duration) {
        // Parse the start time using moment.js with 24-hour format and AM/PM
        var parsedStartTime = moment(startTime, 'HH:mm A');

        // Clone the moment object to avoid mutating the original
        var endTime = moment(parsedStartTime).add(parseInt(duration), 'minutes');

        // Format the end time in 24-hour format with AM/PM
        var formattedEndTime = endTime.format('HH:mm A');

        return formattedEndTime;
    }

    // Function to send data to the server for adding a new time slot
    function addTimeSlotToDatabase(doctorid, selectedday, startTime, endTime, duration) {
        $.ajax({
            url: 'assets/doctor-add-timeslot.php',
            method: 'POST',
            data: {
                doctorid: doctorid,
                selectedday: selectedday,
                startTime: startTime,
                endTime: endTime,
                duration: duration
            },
            success: function(response) {
                // Handle the response from the server
                alert('Time slot added successfully');

                // Reload the page after successful addition
                location.reload();
            },
            error: function(error) {
                // Handle errors
                console.error('Error adding time slot:', error);
            }
        });
    }
});
</script>
<!-- /Add Time Slot Modal -->

<!-- Edit Time Slot Modal -->
<div class="modal fade custom-modal" id="edit_time_slot">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Time Slot</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editTimeSlotForm">
                    <div class="hours-info">
                        <div class="row form-row hours-cont">
                            <div class="col-12 col-md-10">
                                <div class="row form-row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="editStartTime">Start Time</label>
                                            <div class="input-group date" id="edit_doctor_schedule_start_time"
                                                data-target-input="nearest">
                                                <div class="input-group-prepend"
                                                    data-target="#edit_doctor_schedule_start_time"
                                                    data-toggle="datetimepicker">
                                                    <span class="input-group-text" id="basic-addon1"><i
                                                            class="fas fa-clock"></i></span>
                                                </div>
                                                <input type="text" id="editStartTime"
                                                    name="edit_doctor_schedule_start_time"
                                                    class="form-control datetimepicker-input"
                                                    data-target="#edit_doctor_schedule_start_time" required
                                                    onkeydown="return false" onpaste="return false;"
                                                    ondrop="return false;" autocomplete="off" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="editDuration">Duration (mins.)</label>
                                            <select id="editDuration" class="form-control" name="duration">
                                                <option value="-">-</option>
                                                <option value="15">15 mins</option>
                                                <option value="30">30 mins</option>
                                                <option value="45">45 mins</option>
                                                <option value="60">60 mins</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="submit-section text-center">
                        <button type="button" class="btn btn-rounded btn-primary submit-btn"
                            id="editsaveChangesButton">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Edit Time Slot Modal -->

<!-- script for edit modal -->
<script>
$(document).ready(function() {
    // Function to populate the modal with values
    function populateModal(startTime, duration) {

        // Convert the start time to a format compatible with the datetime picker
        var formattedStartTime = moment(startTime, 'HH:mm').format('hh:mm A');

        // Set the value of the datetime picker
        $('#editStartTime').val(formattedStartTime.trim());
        $('#editDuration').val(duration.trim());
    }

    // Event listener for the edit button
    $('.edit_schedule').click(function() {
        // Get the schedule ID from the data attribute
        currentScheduleId = $(this).data('schedule-id');

        // Get the values from the row
        var startTime = $(this).closest('tr').find('td:nth-child(1)').text();
        var duration = $(this).closest('tr').find('td:nth-child(3)').text();

        // Populate the modal with the values
        populateModal(startTime, duration);
    });

    // Event listener for the save button in the edit time slot modal
    $('#edit_time_slot').on('click', '#editsaveChangesButton', function() {

        // Get the selected start time, duration, and end time
        var selectedStartTime = $('#editStartTime').val();
        var selectedDuration = $('#editDuration').val();
        var selectedEndTime = computeEndTime(selectedStartTime, selectedDuration);

        // Send the data to the server for updating the database
        saveChangesToDatabase(currentScheduleId, selectedStartTime, selectedEndTime, selectedDuration);
    });
    // Function to save changes to the database using AJAX
    function saveChangesToDatabase(scheduleId, startTime, endTime, duration) {
        $.ajax({
            url: 'assets/doctor-update-timeslot.php',
            method: 'POST',
            data: {
                scheduleId: scheduleId,
                startTime: startTime,
                endTime: endTime,
                duration: duration
            },
            success: function(response) {
                // Handle the response from the server 
                alert('Changes saved successfully');

                // Reload the page after successful save
                location.reload();
            },
            error: function(error) {
                // Handle errors
                console.error('Error saving changes:', error);
            }
        });
    }

    // Function to compute the end time based on start time and duration
    function computeEndTime(startTime, duration) {
        // Parse the start time using moment.js
        var parsedStartTime = moment(startTime, 'HH:mm');

        // Add the duration to the start time
        var endTime = parsedStartTime.add(parseInt(duration), 'minutes');

        // Format the end time
        var formattedEndTime = endTime.format('HH:mm');

        return formattedEndTime;
    }

});
</script>
<!-- Delete Entry Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog"
    aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this timeslot?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-rounded btn-danger" id="confirmDeleteButton">Delete</button>
                <button type="button" class="btn btn-rounded btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- /Delete Entry Modal -->

<!-- script for delete entry -->
<script>
$(document).ready(function() {
    // Event listener for the delete button
    $('.delete_schedule').click(function() {
        // Get the schedule ID from the data attribute
        var scheduleId = $(this).data('schedule-id');

        // Set the schedule ID in the modal
        $('#confirmDeleteButton').data('schedule-id', scheduleId);

        console.log(scheduleId)

        // Open the delete confirmation modal
        $('#deleteConfirmationModal').modal('show');
    });

    // Event listener for the confirm delete button in the modal
    $('#confirmDeleteButton').click(function() {
        // Get the schedule ID from the data attribute
        var scheduleId = $(this).data('schedule-id');

        // Make an AJAX request to delete the timeslot
        $.ajax({
            url: 'assets/doctor-delete-timeslot.php',
            method: 'POST',
            data: {
                scheduleId: scheduleId
            },
            success: function(response) {

                console.log(response)
                // Handle the response from the server
                alert('Timeslot deleted successfully');

                // Reload the page after successful deletion
                location.reload();
            },
            error: function(error) {
                // Handle errors
                console.error('Error deleting timeslot:', error);
            }
        });

        // Close the modal
        $('#deleteConfirmationModal').modal('hide');
    });
});
</script>
<?php endif; ?>
<!-- /CRUD Time Slot modal -->

<!-- for cancelling appointment -->
<?php if ($_SESSION['subpage'] == 'patdash') : ?>
<div class="modal fade" id="cancelAppointmentModal" tabindex="-1" role="dialog"
    aria-labelledby="cancelAppointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelAppointmentModalLabel">Cancel Appointment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to cancel this appointment?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-rounded btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-rounded btn-danger" id="confirmCancelAppointment">Cancel
                    Appointment</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var cancelButtons = document.querySelectorAll(".cancel_booking");
    var confirmCancelAppointmentButton = document.getElementById("confirmCancelAppointment");

    cancelButtons.forEach(function(cancelButton) {
        cancelButton.addEventListener("click", function() {
            // Set the appointment ID in the modal
            var appointmentId = cancelButton.getAttribute("data-appointment-id");
            confirmCancelAppointmentButton.setAttribute("data-appointment-id", appointmentId);
        });
    });

    confirmCancelAppointmentButton.addEventListener("click", function() {
        // Handle the cancel appointment logic here
        var appointmentId = confirmCancelAppointmentButton.getAttribute("data-appointment-id");

        $.ajax({
            url: 'assets/cancel-booking.php',
            method: 'POST',
            data: {
                appointmentId: appointmentId
            },
            success: function(response) {
                // Handle the response from the server
                alert('Booking cancelled successfully');

                // Reload the page after successful deletion
                location.reload();
            },
            error: function(error) {
                // Handle errors
                console.error('Error deleting timeslot:', error);
            }
        });

        $('#cancelAppointmentModal').modal('hide');
    });
});
</script>

<?php endif; ?>

<!-- Add/Edit Medical Record Modal-->
<?php if ($_SESSION['subpage'] == 'patients-profile') : ?>
<!-- Add Medical Records Modal -->
<div class="modal fade custom-modal" id="add_medical_records">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Medical Records</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="medicalRecordsForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="doctorId" value="<?php echo $_SESSION['id'] ?>">
                    <input type="hidden" name="patientId" value="<?php echo $patientId; ?>">

                    <div class="form-group">
                        <label for="recordDescription">Description</label>
                        <textarea class="form-control" name="recordDescription" id="recordDescription"
                            maxlength="100"></textarea>
                        <small class="form-text text-muted"><strong>100</strong> characters only</small>
                    </div>
                    <div class="form-group">
                        <label for="recordInput">Upload File</label>
                        <input type="file" class="form-control" name="recordInput" id="recordInput" required>
                    </div>
                    <div class="submit-section text-center">
                        <button type="button" class="btn btn-rounded btn-primary submit-btn"
                            id="recordAdd">Submit</button>
                        <button type="button" class="btn btn-rounded btn-secondary submit-btn"
                            data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Add Medical Records Modal -->

<script>
$(document).ready(function() {
    $("#recordAdd").click(function(e) {

        //get form data
        var formData = new FormData($("#medicalRecordsForm")[0]);

        // Make an AJAX request
        $.ajax({
            type: "POST",
            url: "assets/insert-records.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {

                console.log(response);
                // Handle the response from the server
                alert('Record uploaded successfully');

                // Reload the page after successful deletion
                location.reload();
            },
            error: function(error) {
                // Handle errors
                console.error("AJAX Error:", error);
            }
        });
    });
});
</script>

<!-- Edit Medical Records Modal -->
<div class="modal fade custom-modal" id="edit_medical_records">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Medical Records</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="medicalRecordsFormEdit" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="doctorId" value="<?php echo $_SESSION['id'] ?>">
                    <input type="hidden" name="patientId" value="<?php echo $patientId; ?>">
                    <input type="hidden" name="editRecordId" id="editRecordId">

                    <div class="form-group">
                        <label for="editrecordDescription">Description</label>
                        <textarea class="form-control" name="editrecordDescription" id="editrecordDescription"
                            maxlength="100"></textarea>
                        <small class="form-text text-muted"><strong>100</strong> characters only</small>
                    </div>
                    <div class="form-group">
                        <label for="recordInput">Upload File</label>
                        <input type="file" class="form-control" name="recordInput" id="recordInput" required>
                    </div>
                    <div class="submit-section text-center">
                        <button type="button" class="btn btn-rounded btn-primary submit-btn"
                            id="recordEdit">Submit</button>
                        <button type="button" class="btn btn-rounded btn-secondary submit-btn"
                            data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Edit Medical Records Modal -->

<script>
$(document).ready(function() {
    $('#edit_medical_records').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var recordId = button.data('recordid');
        var description = button.data('description');

        // Update the modal content with the record_id and description
        $('#editrecordDescription').val(description);
        $('#editRecordId').val(recordId);
    });
});

$(document).ready(function() {
    $("#recordEdit").click(function(e) {
        // Get form data
        var formData = new FormData($("#medicalRecordsFormEdit")[0]);

        // Make an AJAX request
        $.ajax({
            type: "POST",
            url: "assets/update-medical-record.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log(response);
                // Handle the response from the server
                alert('Record updated successfully');

                // Reload the page after a successful update
                location.reload();
            },
            error: function(xhr, status, error) {
                // Handle errors
                console.error("AJAX Error:", status, error);
                console.log(xhr.responseText); // Log the responseText for more details
            }
        });
    });
});
</script>

<?php endif; ?>
<!-- /Add/Edit Medical Record Modal-->

<!-- Patient Information Modal -->
<?php if ($_SESSION['subpage'] == 'patientinfo' && $_SESSION['currpage'] == 'patients' && $_SESSION["isFirstUse"] == true) : ?>

<!-- Greeting Modal -->
<div class="modal fade" id="greetingModal" tabindex="-1" role="dialog" aria-labelledby="greetingModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="greetingModalLabel">Welcome!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>As first time user, we would like to get to know about your health background.<br><br><b>Please fill
                        up the information below.</b></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
<!-- /Greeting Modal -->

<!-- Greet the user after registration -->
<script>
$(document).ready(function() {
    $('#greetingModal').modal('show');
});
</script>

<?php endif; ?>


<!-- /Patient Information Modal -->

<!-- jQuery -->
<script src="assets/js/jquery.min.js"></script>

<!-- Bootstrap Core JS -->
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

<!-- Sticky Sidebar JS -->
<script src="assets/plugins/theia-sticky-sidebar/ResizeSensor.js"></script>
<script src="assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js"></script>

<!-- Select2 JS -->
<script src="assets/plugins/select2/js/select2.min.js"></script>

<!-- Dropzone JS -->
<script src="assets/plugins/dropzone/dropzone.min.js"></script>

<!-- Bootstrap Tagsinput JS -->
<script src="assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.js"></script>

<!-- Profile Settings JS -->
<script src="assets/js/profile-settings.js"></script>

<!-- Slick JS -->
<script src="assets/js/slick.js"></script>

<!-- Custom JS -->
<script src="assets/js/script.js"></script>

</body>

</html>