<!-- include the header first -->
<?php require_once('assets/header.php') ?>

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-12 col-12">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <!-- <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index-2.php?page=home">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Announcements</li>
                    </ol> -->
                </nav>
                <h2 class="breadcrumb-title">Announcements</h2>
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
                <div class="col-md-12 col-lg-8 col-xl-12">
                    <?php
                    // SQL statement for fetching announcements
                    $announcementSql = "SELECT * FROM announcements ORDER BY announcement_id DESC LIMIT 2";
                    $announcementResult = $connect->query($announcementSql);

                    // Check if there are any announcements
                    if ($announcementResult->num_rows > 0) {
                        while ($announcementRow = $announcementResult->fetch_assoc()) {
                            echo '<div class="card">';
                            echo '<div class="card-body text-center">'; // Added text-center class
                            echo '<h4>' . htmlspecialchars($announcementRow['description']) . '</h4>';
                            echo '<img src="admin/' . htmlspecialchars($announcementRow['pic_path']) . '" alt="Announcement Image" class="img-fluid mx-auto">'; // Added mx-auto class
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        // Display a message if no announcements are available
                        echo '<div class="card">';
                        echo '<div class="card-body text-center">'; // Added text-center class
                        echo 'No announcements available';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- /Page Content -->

<!-- include footer -->
<?php require_once('assets/footer.php') ?>