<!-- include the header first -->
<?php require_once('assets/header.php') ?>

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-12 col-12">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <!-- <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href=" index-2.php?page=home">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Finances</li>
                    </ol> -->
                </nav>
                <h2 class="breadcrumb-title">Finances</h2>
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
                    // SQL statement for fetching finances
                    $financesSql = "SELECT * FROM finances ORDER BY finance_id DESC LIMIT 1";
                    $financesResult = $connect->query($financesSql);

                    // Check if there are any finances
                    if ($financesResult->num_rows > 0) {
                        while ($financesRow = $financesResult->fetch_assoc()) {
                            echo '<div class="card">';
                            echo '<div class="card-body text-center">'; 
                            echo '<h4>' . htmlspecialchars($financesRow['description']) . '</h4>';
                            echo '<img src="' . htmlspecialchars($financesRow['pic_path']) . '" alt="finances Image" class="img-fluid mx-auto">';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="card">';
                        echo '<div class="card-body text-center">';
                        echo 'No finances available';
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