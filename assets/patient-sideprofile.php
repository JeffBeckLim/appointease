<!-- get values of user Data -->
<?php
$id = $_SESSION['id'];
$result = mysqli_query($connect, "SELECT * FROM users WHERE id = '$id'")->fetch_assoc();
?>

<!-- Profile Sidebar -->
<div class="col-md-5 col-lg-4 col-xl-3 theiaStickySidebar">
    <div class="profile-sidebar">
        <div class="widget-profile pro-widget-content">
            <div class="profile-info-widget">
                <a href="#" class="booking-doc-img">
                    <img src="<?php echo isset($result['idpic']) ? $result['idpic'] : 'assets/uploads/idpics/default-id.png'; ?>" alt="User Image">
                </a>
                <div class="profile-det-info">
                    <?php
                    $fullName = $result['fname'] . " " . $result['lname'];
                    echo "<h3>" . (empty($fullName) ? "Unknown User" : $fullName) . "</h3>";
                    ?>
                    <div class="patient-details">
                        <h5>
                            <i class="fas fa-birthday-cake"></i>
                            <?php
                            $birthday = $result['birthday'];
                            if (!empty($birthday)) {
                                echo date("d M Y", strtotime($birthday)) . ", " . calculateAge($birthday) . " years";
                            } else {
                                echo "Date of Birth Not Available";
                            }
                            ?>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="dashboard-widget">
            <nav class="dashboard-menu">
                <ul>
                    <li <?php echo ($_SESSION['subpage'] == 'patdash') ? "class=\"active\"" : '' ?>>
                        <a href="patient-dashboard.php?page=patients&sub=patdash">
                            <i class="fas fa-columns"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li <?php echo ($_SESSION['subpage'] == 'profset') ? "class=\"active\"" : '' ?>>
                        <a href="profile-settings.php?page=patients&sub=profset">
                            <i class="fas fa-user-cog"></i>
                            <span>Profile Settings</span>
                        </a>
                    </li>
                    <li <?php echo ($_SESSION['subpage'] == 'changepass') ? "class=\"active\"" : '' ?>>
                        <a href="change-password.php?page=patients&sub=changepass">
                            <i class="fas fa-lock"></i>
                            <span>Change Password</span>
                        </a>
                    </li>
                    <li>
                        <a href="index-2.php?logout">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

    </div>
</div>
<!-- /Profile Sidebar -->