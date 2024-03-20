<!-- include the header first -->
<?php require_once('assets/header.php') ?>

<?php

function connectToDatabase()
{
    $connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $connect;
}

// Function to get services from the database
function getServicesFromDatabase()
{
    $connect = connectToDatabase();

    $services = array();

    $query = "SELECT service_id, service, specialty, icon FROM services";

    $result = mysqli_query($connect, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $services[] = $row;
        }
    }

    // Close the database connection
    mysqli_close($connect);

    return $services;
}

// Function to get features from the database
function getFeaturesFromDatabase()
{
    $connect = connectToDatabase();

    $features = array();

    $query = "SELECT facility_id, description, icon FROM facilities";

    $result = mysqli_query($connect, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $features[] = $row;
        }
    }

    // Close the database connection
    mysqli_close($connect);

    return $features;
}
?>

<!-- Home Banner -->
<section class="section section-search">
    <div class="container-fluid">
        <div class="banner-wrapper">
            <div class="banner-header text-center">
                <?php if (isset($_SESSION['username'])) {
                    echo "<h1>Hello " . $_SESSION['username'] . "!</h1>";
                } else
                    echo "<h1>Welcome to Appointease!</h1>";
                ?>
            </div>
        </div>
    </div>
</section>
<!-- /Home Banner -->

<!-- Clinic and Specialities -->
<section class="section section-specialities">
    <div class="container-fluid">
        <div class="section-header text-center">
            <h2> Services</h2>
            <p class="sub-title">Browse the services that we offer at our Health Office.</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-9">
                <!-- Slider -->
                <div class="specialities-slider slider">

                    <?php
                    // Fetch services from the database and loop through them
                    $services = getServicesFromDatabase(); // Implement this function to get services
                    foreach ($services as $service) :
                    ?>
                        <!-- Slider Item -->
                        <div class="speicality-item text-center">
                            <div class="speicality-img">
                                <?php if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'patient') : ?>
                                    <a href="search.php?page=patients&sub=searchdoc">
                                    <?php endif; ?>
                                    <div class="image-container">
                                        <div class="image-circle" style="background-image: url('<?php echo 'admin/' . $service['icon']; ?>');"></div>
                                        <div class="cloud-dialog" style="max-height: 150px; overflow-y: auto;">
                                            <?php if (!empty($service['specialty'])) : ?>
                                                <h4 class="cloud-text">Specialties</h4>
                                                <ul class="cloud-text">
                                                    <?php
                                                    $specialties = explode(', ', $service['specialty']);
                                                    foreach ($specialties as $specialty) {
                                                        echo '<li>' . htmlspecialchars($specialty) . '</li>';
                                                    }
                                                    ?>
                                                </ul>
                                            <?php else : ?>
                                                <p class="cloud-text" style="margin: 0;">No specialties</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'patient') : ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <p><?php echo $service['service']; ?></p>
                        </div>
                        <!-- /Slider Item -->
                    <?php endforeach; ?>

                </div>
                <!-- /Slider -->
            </div>
        </div>
    </div>
</section>
<!-- Clinic and Specialities -->

<!-- Google Maps Section -->
<section class="section section-features">
    <div class="container-fluid">
        <div class="section-header text-center">
            <h2>Location</h2>
            <p class="sub-title">Come visit us here!</p>
        </div>
        <div class="col-md-12">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3885.373145973814!2d123.73247373853013!3d13.138846854561521!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a103dc9876e80f%3A0x711ddb420d82fce4!2sOffice%20Of%20the%20City%20Health%20Officer%20%40%20Legaspi%20City%20Hall!5e0!3m2!1sen!2sph!4v1705933945012!5m2!1sen!2sph" width="100%" height="340vh" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</section>
<!-- /Google Maps Section -->

<!-- Availabe Features -->
<section class="section section-features">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="section-header text-center">
                    <h2 class="mt-2">Facilities</h2>
                    <p>The Health Office is equipped with facilities that will cater your medical needs.</p>
                </div>
                <div class="features-slider slider">
                    <?php
                    // Fetch features from the database and loop through them
                    $features = getFeaturesFromDatabase(); // Implement this function to get features
                    foreach ($features as $feature) :
                    ?>
                        <!-- Slider Item -->
                        <div class="feature-item text-center">
                            <img src="<?php echo 'admin/' . $feature['icon']; ?>" class="img-fluid" alt="Feature">
                            <p><?php echo $feature['description']; ?></p>
                        </div>
                        <!-- /Slider Item -->
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Available Features -->

<!-- include footer -->
<?php require_once('assets/footer.php') ?>