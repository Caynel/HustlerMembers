<?php
    session_start();

    include 'dbConnect.php';
    include 'memberHandler.php';

    requireLogin();

    // Handle cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_index'])) {
    $idx = intval($_POST['cancel_index']);
    if (isset($_SESSION['enrolledClasses'][$idx])) {
        // Move to history before removing
        if (!isset($_SESSION['enrollmentHistory'])) $_SESSION['enrollmentHistory'] = [];
        $_SESSION['enrollmentHistory'][] = $_SESSION['enrolledClasses'][$idx];
        unset($_SESSION['enrolledClasses'][$idx]);
        // Reindex array
        $_SESSION['enrolledClasses'] = array_values($_SESSION['enrolledClasses']);
    }
    header("Location: enrolled.php");
    exit;
}

// Fetch data
$enrolledClasses = isset($_SESSION['enrolledClasses']) ? $_SESSION['enrolledClasses'] : [];
$enrollmentHistory = isset($_SESSION['enrollmentHistory']) ? $_SESSION['enrollmentHistory'] : [];

// Mockup session attendance data for demonstration
function getSessionAttendance($className) {
    $days = [];
    $today = strtotime('today');
    for ($i = 0; $i < 5; $i++) {
        $date = date('M j, Y', strtotime("-$i days", $today));
        $attended = rand(0,1) ? 'Attended' : 'Missed';
        $days[] = [
            'date' => $date,
            'status' => $attended
        ];
    }
    return $days;
}
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
<body class="lightBody">
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

    <!-- BREADCRUMBS -->
    <div class="breadcrumbs">
        <span>&gt; Enrolled Classes</span>
    </div>
    
    <!-- MAIN CONTENT -->
    <div class="enrolledCont">
        <div class="page-title">Your Enrolled Classes</div>
        
        <div class="enrolled-list">
            <?php if (empty($enrolledClasses)): ?>
                <p>You have not enrolled in any classes yet.</p>
            <?php else: ?>
                <?php foreach ($enrolledClasses as $idx => $enrolled): ?>
                    <div class="enrolled-item">
                        <div class="enrolled-main">
                            <div class="enrolled-info">
                                <strong style="font-size:1.1em;"><?php echo htmlspecialchars($enrolled['name']); ?></strong><br>
                                <span>Schedule: <?php echo htmlspecialchars($enrolled['schedule']); ?></span><br>
                                <span>Enrolled by: <?php echo htmlspecialchars($enrolled['student_name']); ?></span><br>
                                <span>Email: <?php echo htmlspecialchars($enrolled['student_email']); ?></span>
                            </div>

                            <div class="enrolled-actions">
                                <button class="btn-detail" type="button" data-index="<?php echo $idx; ?>">View Details</button>
                                <form method="post" action="enrolled.php">
                                    <input type="hidden" name="cancel_index" value="<?php echo $idx; ?>">
                                    <button type="submit" class="btn-cancel">Cancel</button>
                                </form>
                            </div>
                        </div>

                        <div class="details-panel" id="details-<?php echo $idx; ?>">
                            <strong>Additional Details:</strong><br>
                            Class: <?php echo htmlspecialchars($enrolled['name']); ?><br>
                            Schedule: <?php echo htmlspecialchars($enrolled['schedule']); ?><br>
                            Student Name: <?php echo htmlspecialchars($enrolled['student_name']); ?><br>
                            Email: <?php echo htmlspecialchars($enrolled['student_email']); ?><br>
                            <!-- Add more details here if needed -->
                        </div>
                        
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="section-title">Enrollment History</div>
        <div class="history-list">
            <?php if (empty($enrollmentHistory)): ?>
                <p style="color:#bbb;">No previous enrollments.</p>
            <?php else: ?>
                <?php foreach ($enrollmentHistory as $history): ?>
                    <div class="history-item">
                        <strong><?php echo htmlspecialchars($history['name']); ?></strong><br>
                        <span>Schedule: <?php echo htmlspecialchars($history['schedule']); ?></span><br>
                        <span>Enrolled by: <?php echo htmlspecialchars($history['student_name']); ?></span><br>
                        <span>Email: <?php echo htmlspecialchars($history['student_email']); ?></span>
                        <div>
                            <table class="session-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (getSessionAttendance($history['name']) as $session): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($session['date']); ?></td>
                                            <td class="<?php echo $session['status'] === 'Attended' ? 'status-attended' : 'status-missed'; ?>">
                                                <?php echo $session['status']; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.querySelectorAll('.btn-detail').forEach(button => {
            button.addEventListener('click', () => {
                const idx = button.getAttribute('data-index');
                const detailsPanel = document.getElementById('details-' + idx);
                if (detailsPanel.style.display === 'block') {
                    detailsPanel.style.display = 'none';
                    button.textContent = 'View Details';
                } else {
                    detailsPanel.style.display = 'block';
                    button.textContent = 'Hide Details';
                }
            });
        });
    </script>


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
