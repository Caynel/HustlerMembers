<?php

    include 'dbConnect.php';
    include 'memberHandler.php';

    // Require user to be logged in
    requireLogin();

    // Handle logout if requested
    handleLogout();
    
    // Get member data if needed
    $memberData = getMemberData();

    // Edit profile is only accessible through profile.php
    if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], 'Profile.php') === false) {
        header("Location: profile.php");
        exit();
    }
    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profile Page</title>
        <link rel="stylesheet" href="projectStyles.css"> 
    </head>
    <body class="light">
        <header>
            <div class="upperNav">
                <a class="logo" href="Homepage.php"><img src="images/logo.jpg" alt="Logo" /></a>
                <div>
                    <?php if (isLoggedIn()): ?>
                        <!-- PROFILE/ACC & LOGOUT (Only shows if logged in) -->
                        <a class="profileUpperNav" href="Profile.php">
                            <img src="images/profile.png" alt="Default Icon">Profile
                        </a>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <button type="submit" name="logout">Log out</button>
                        </form>
                    <?php else: ?>
                        <!-- SIGN IN & OUT (Only show if not logged in) -->
                        <a class="signBtns" href="signIn.php">Log In</a>
                        <a class="signBtns" href="signUp.php">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
            <nav>
                <ul>
                    <li><a href="Homepage.php">Home</a></li>
                    <li><a href="classes.php">Classes</a></li>
                    <?php if (isLoggedIn()): ?>
                        <li><a href="session.php">Sessions</a></li>
                        <li><a href="enrolled.php">Enrolled Classes</a></li>
                        <li><a href="Membership.php">Memberships</a></li>
                    <?php endif; ?>
                    <li><a href="AboutUs.php">About Us</a></li>
                </ul>
            </nav>
        </header>

         <!-- WRAPPER -->
         <div class="wrapper"> </div>

         <!-- BREADCRUMBS -->
        <div class="breadcrumbs">
            &gt; <a href="profile.php">Manage Profile </a><span>&gt; Edit Profile</span>
        </div>

        <!-- MAIN CONTENT -->
         <main class="profileCont">
            <h2>Edit Profile</h2>
                <!-- EDIT PROFILE FIRST NAME, LAST NAME, DISABLE CHANGING EMAIL, IF PASSWORD IS TO BE CHANGED THERE SHOULD BE CONFIRM PASSWORD -->
            <section class="accDetails">
                <?php
                    // Handle form submission
                    $successMsg = $errorMsg = '';
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editProfile'])) {
                        $firstName = trim($_POST['firstName']);
                        $lastName = trim($_POST['lastName']);
                        $password = $_POST['password'];
                        $confirmPassword = $_POST['confirmPassword'];

                        // Validate names
                        if (empty($firstName) || empty($lastName)) {
                            $errorMsg = "Error! First and Last Name cannot be empty. Please try again.";
                        } elseif (!empty($password) && $password !== $confirmPassword) {
                            $errorMsg = "Error! Passwords do not match. Please try again.";
                        } else {
                            // Update query
                            $updateSql = "UPDATE Members SET firstName=?, lastName=?" . (!empty($password) ? ", password=?" : "") . " WHERE memberID=?";
                            $stmt = $conn->prepare($updateSql);
                            if (!empty($password)) {
                                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                                $stmt->bind_param("sssi", $firstName, $lastName, $hashedPassword, $memberData['memberID']);
                            } else {
                                $stmt->bind_param("ssi", $firstName, $lastName, $memberData['memberID']);
                            }
                            if ($stmt->execute()) {
                                $successMsg = "Profile updated successfully!";
                                // Refresh member data
                                $memberData = getMemberData();
                            } else {
                                $errorMsg = "Error updating profile.";
                            }
                            $stmt->close();
                        }
                    }
                ?>
                <?php if ($successMsg): ?>
                    <div class="successEditMsg"><?php echo $successMsg; ?></div>
                <?php elseif ($errorMsg): ?>
                    <div class="errorEditMsg"><?php echo $errorMsg; ?></div>
                <?php endif; ?>
                <form method="post" action="">
                    <div class="form-group">
                        <label for="firstName">First Name:</label>
                        <input class="editProfInput" type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($memberData['firstName']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name:</label>
                        <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($memberData['lastName']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($memberData['email']); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="password">New Password:</label>
                        <input type="password" id="password" name="password" placeholder="New Password">
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirm New Password:</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm New Password">
                    </div>
                    <button type="submit" name="editProfile" class="enrollBtn">Save Changes</button>
                </form>
         </main>
        <footer>
            <div class="leftFooter">
                <div class="footerDescrip">
                    <a class="logo" href="Homepage.php"><img src="images/logo.jpg" alt="Logo" /></a>
                    <p>
                        <span>Hustlecore</span> is a fitness community that empowers individuals to achieve their 
                        health and wellness goals through innovative training programs, 
                        expert coaching, and a supportive environment.
                    </p>
                </div>
                <p class="copyright">&copy; 2025 All rights reserved. <span>HustleCore</span></p> 
            </div>
            <div class="middleFooter">
                    <h4>Sitemap</h4>
                    <ul>
                        <li><a href="Homepage.php">Home</a></li>
                        <li><a href="classes.php">Classes</a></li>
                        <li><a href="aboutUs.php">About Us</a></li>
                        <?php if (isLoggedIn()): ?>
                            <li><a href="session.php">Sessions</a></li>
                            <li><a href="enrolled.php">Enrolled Classes</a></li>
                            <li><a href="Profile.php">Profile</a></li>
                        <?php else: ?>
                            <li><a href="signIn.php">Sign In</a></li>
                            <li><a href="signUp.php">Sign Up</a></li>
                        <?php endif; ?>
                    </ul>
            </div>
            <div class="rightFooter">
                <h4>Contact Us</h4>
                <ul>
                    <li>0967 470 9856</li>
                    <li>hustlecoreAdmin@gmail.com</li>
                </ul>
            </div>

        </footer>
    </body>
</html>