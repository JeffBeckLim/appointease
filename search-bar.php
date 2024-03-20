<!-- Include databse connection and other checking of session variables-->
<?php require_once('assets/header.php') ?>

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
	<div class="container-fluid">
		<div class="row align-items-center">
			<div class="col-md-8 col-12">
				<nav aria-label="breadcrumb" class="page-breadcrumb">
					<!-- <ol class="breadcrumb">
						<li class="breadcrumb-item"><a href=" index-2.php?page=home">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Services</li>
					</ol> -->
				</nav>
				<h2 class="breadcrumb-title">Search Services</h2>
			</div>
		</div>
	</div>
</div>
<!-- /Breadcrumb -->

<!-- Page Content -->
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title mb-0">Please select a service you need</h4>
					</div>
					<div class="card-body">
						<form method="post" action="search.php?page=patients&sub=searchdoc">
							<div class="input-group mb-3">
								<!-- Dropdown menu for selecting services -->
								<select class="custom-select" id="serviceSelect" name="select_service[]">
									<option value="">-</option>
									<?php
									// Fetch available services from the database
									$sql = "SELECT DISTINCT doctor_services FROM doctors WHERE doctor_services IS NOT NULL AND doctor_services != '' AND doctor_status = 'Active'";
									$result = $connect->query($sql);

									// Check if there are any results
									if ($result->num_rows > 0) {
										while ($row = $result->fetch_assoc()) {
											echo '<option value="' . $row['doctor_services'] . '">' . $row['doctor_services'] . '</option>';
										}
									}
									?>
								</select>
								<!-- /Dropdown menu for selecting services -->
								<div class="input-group-append">
									<button class="btn btn-primary" type="submit" id="searchBtn"  name="searchBtn">Search</button>
								</div>
								<input type="hidden" name="selected_services" id="selectedServices">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- pass selected service to search.php -->
<script>
    $(document).ready(function() {
        $('#searchBtn').click(function() {
            var selectedServices = $('#serviceSelect').val();
            $('#selectedServices').val(selectedServices);
        });
    });
</script>

<!-- /Page Content -->


<!-- include footer -->
<?php require_once('assets/footer.php') ?>