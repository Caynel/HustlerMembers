<?php
    session_start();

    include 'dbConnect.php';
    include 'memberHandler.php';

    requireLogin();

    $className = isset($_GET['class']) ? $_GET['class'] : 'Session';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo htmlspecialchars($className); ?> - Book Session</title>
    <link rel="stylesheet" href="projectStyles.css">
</head>
<body>
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
                    <li><a class="active" href="session.php">Sessions</a></li>
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
        <span>&gt; Session</span>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="session-title"><?php echo htmlspecialchars($className); ?></div>
        <div class="session-desc">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo.
        </div>
        <form action="enrolled.php" method="post">
            <input type="hidden" name="session_name" value="<?php echo htmlspecialchars($className); ?>">
            <button class="book-btn" type="submit">BOOK SESSION</button>
        </form>
    </div>

    <!-- FOOTER -->
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