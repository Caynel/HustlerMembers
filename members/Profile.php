<?php

    include 'dbConnect.php';
    include 'memberHandler.php';

    // Require user to be logged in
    requireLogin();

    // Handle logout if requested
    handleLogout();
    
    // Get member data if needed
    $memberData = getMemberData();

    
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
            <span>&gt; Manage Profile</span>
        </div>

        <!-- MAIN CONTENT -->
         <main class="profileCont">
            <h2>Profile</h2>
            <section class="accDetails">
                <h3>Account Details</h3>
                <div class="accBoxCont">
                    <img class="profilePic" src="images/profile.png" alt="Profile Picture">
                    <div class="accBoxDetails">
                        <p><strong><?php echo htmlspecialchars($memberData['firstName']).' '.htmlspecialchars($memberData['lastName']); ?></strong></p>
                        <p>Email: <?php echo htmlspecialchars($memberData['email']); ?></p>
                        <p>Password: ******</p>
                        <div class="editCont">
                            <a class="enrollBtn" id="editProfileBtn" href="editProfile.php">Edit Profile</a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- MANAGE MEMBERSHIP -->
            <section class="manageMembership">
                <h3>Manage Membership</h3>
                <div class="accBoxCont">
                    <div class="currentPlan">
                        <p><strong>Current Plan</strong></p>
                        <div class="plan-type">Monthly Plan</div>
                        <div class="price">1000 php</div>
                        <p class="member-since">Member since Month Year</p>
                    </div>
                    <div class="membershipStatus">
                        <button class="manageBtn">Cancel Membership</button>
                        <br>
                        <button class="manageBtn">Downgrade/Upgrade</button>
                    </div>
                </div>
            </section>
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