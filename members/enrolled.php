<?php
    session_start();

    include 'dbConnect.php';
    include 'memberHandler.php';

    requireLogin();
?>

<?php

// enrolled.php

// Demo enrolled class data
$enrolledClass = [
    'name' => 'High-Intensity CrossFit',
    'schedule' => '05/13/2025 - 7:00 PM',
    'image' => '[ High-Intensity CrossFit Image ]',
    'desc' => 'Push your limits with a dynamic mix of weightlifting, gymnastics, and metabolic conditioning. For those seeking a challenging workout.'
];

// Demo enrollment history data
$enrollmentHistory = [
    ['date' => '05/10/2025', 'session' => 'CrossFit', 'status' => 'Attended'],
    ['date' => '05/09/2025', 'session' => 'CrossFit', 'status' => 'Not Attended'],
    ['date' => '05/08/2025', 'session' => 'CrossFit', 'status' => 'Attended'],
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enrolled Classes - Fitness Portal</title>
    
    <link rel="stylesheet" href="projectStyles.css">

    <script>
        function toggleDetails() {
            var details = document.getElementById('enrolled-details');
            var btn = document.getElementById('toggle-details-btn');
            if (details.style.display === 'block') {
                details.style.display = 'none';
                btn.textContent = 'View Details';
            } else {
                details.style.display = 'block';
                btn.textContent = 'Hide Details';
            }
        }
        function cancelEnrollment() {
            if (confirm('Are you sure you want to cancel your enrollment?')) {
                alert('Enrollment cancelled (demo only).');
                // Here you would submit a form or send a request to cancel enrollment
            }
        }
    </script>
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
                    <li><a href="session.php">Sessions</a></li>
                    <li><a class="active" href="enrolled.php">Enrolled Classes</a></li>
                <?php endif; ?>
                <li><a href="AboutUs.php">About Us</a></li>
            </ul>
        </nav>
        </header>
    
    <!-- WRAPPER -->
    <div class="wrapper"> </div>

    <!-- MAIN CONTENT -->
    <div class="enrollCont">
        <!-- BREADCRUMBS -->
        <div class="breadcrumbs">
            <span>&gt; Enrolled Classes</span>
        </div>
        <div class="enrolled-title">Your Enrolled Classes</div>
        <div class="enrolled-subtitle">
            Here's a list of the classes you're currently enrolled in.
        </div>
        <div class="enrolled-card">
            <div class="enrolled-image"><?php echo $enrolledClass['image']; ?></div>
            <div class="enrolled-class-info">
                <div class="class-title"><?php echo htmlspecialchars($enrolledClass['name']); ?></div>
                <div class="class-meta">
                    <strong>Class:</strong> <?php echo htmlspecialchars($enrolledClass['name']); ?>
                </div>
                <div class="class-meta">
                    <strong>Schedule:</strong> <?php echo htmlspecialchars($enrolledClass['schedule']); ?>
                </div>
            </div>
            <div class="enrolled-actions">
                <button type="button" id="toggle-details-btn" onclick="toggleDetails()">View Details</button>
                <button type="button" class="cancel-btn" onclick="cancelEnrollment()">Cancel Enrollment</button>
            </div>
            <div class="enrolled-details" id="enrolled-details">
                <?php echo htmlspecialchars($enrolledClass['desc']); ?>
            </div>
        </div>
        <div class="enrollment-history-title">Enrollment History</div>
        <table class="enrollment-history-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Session</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($enrollmentHistory as $history): ?>
                <tr>
                    <td><?php echo htmlspecialchars($history['date']); ?></td>
                    <td><?php echo htmlspecialchars($history['session']); ?></td>
                    <td class="<?php echo $history['status'] == 'Attended' ? 'status-attended' : 'status-not'; ?>">
                        <?php echo htmlspecialchars($history['status']); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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
