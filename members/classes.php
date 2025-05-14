<?php
    include 'dbConnect.php';
    include 'memberHandler.php';

    // Get member data if logged in
    $memberData = null;
    if (isLoggedIn()) {
        $memberData = getMemberData();
    }
    
    handleLogout();


$classes = [
    [
        'name' => 'Yoga',
        'desc' => 'Enhance your flexibility, build strength, and find inner peace with our flowing yoga sequences. Suitable for all levels.',
        'img'   => '<img src="images/yoga.jpg" alt="Yoga" style="width:100%; height: 180px; object-fit: cover; border-radius: 6px 0 0 6px;">'
    ],
    [
        'name' => 'High-Intensity CrossFit',
        'desc' => 'Push your limits with a dynamic mix of weightlifting, gymnastics, and metabolic conditioning. For those seeking a challenging workout.',
        'img'   => '<img src="images/crossfit.jpg" alt="CrossFit" style="width:100%; height: 180px; object-fit: cover; border-radius: 6px 0 0 6px;">'
    ],
    [
        'name' => 'Zumba',
        'desc' => 'Dance your way to fitness with energetic Zumba classes. Burn calories and have fun in a lively group atmosphere.',
        'img'   => '<img src="images/zumba.jpg" alt="Zumba" style="width:100%; height: 180px; object-fit: cover; border-radius: 6px 0 0 6px;">'
    ],
    [
        'name' => 'High-Intensity Interval Training',
        'desc' => 'Maximize your workout in minimal time with High-Intensity Interval Training. Quick, effective, and results-driven.',
        'img'   => '<img src="images/interval.jpg" alt="HIIT" style="width:100%; height: 180px; object-fit: cover; border-radius: 6px 0 0 6px;">'
    ],
    [
        'name' => 'Kickboxing',
        'desc' => 'Boost your cardio and coordination with our kickboxing sessions. Learn techniques and get a full-body workout.',
        'img'   => '<img src="images/kickboxing.jpg" alt="Kickboxing" style="width:100%; height: 180px; object-fit: cover; border-radius: 6px 0 0 6px;">'
    ]
];

$showForm = isset($_GET['enroll']);
$classToEnroll = $showForm ? $_GET['enroll'] : '';

function generateSchedules() {
    $month = 5; // May
    $year = 2025;
    $schedules = [];
    for ($day = 1; $day <= 30; $day++) { // Loop from day 1 to 30
        $hour = rand(9, 18); // Random hour between 9 AM and 6 PM
        $timestamp = strtotime("$year-$month-$day $hour:00:00");
        $schedules[$timestamp] = date("F j, Y - h:00 A", $timestamp);
    }
    asort($schedules); // Sort schedules by timestamp
    return $schedules;
}

$classSchedules = $showForm ? generateSchedules() : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Classes</title>
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
                <li><a class="active" href="classes.php">Classes</a></li>
                <?php if (isLoggedIn()): ?>
                    <li><a href="session.php">Sessions</a></li>
                    <li><a href="enrolled.php">Enrolled Classes</a></li>
                <?php endif; ?>
                <li><a href="AboutUs.php">About Us</a></li>
            </ul>
        </nav>
    </header>

    <!-- WRAPPER -->
    <div class="wrapper"> </div>

    <!-- BREADCRUMBS -->
    <div class="breadcrumbs">
        <span>&gt; Classes</span>
    </div>
    
    <!-- CONTENT -->
    <div class="page-title">Explore Our Classes</div>
    <div class="classes-layout">
        <?php foreach ($classes as $i => $class): ?>
            <div class="class-row<?php echo $i % 2 == 1 ? ' reverse' : ''; ?>">
                <div class="class-img"><?php echo $class['img']; ?></div>
                <div class="class-info">
                    <div class="class-title"><?php echo htmlspecialchars($class['name']); ?></div>
                    <div class="class-desc"><?php echo htmlspecialchars($class['desc']); ?></div>
                    <div class="class-actions">
                        <?php if (isLoggedIn()): ?>
                            <a href="classes.php?enroll=<?php echo urlencode($class['name']); ?>">Enroll in Class</a> 
                        <?php else: ?>
                            <a href="signin.php">Enroll in Class</a> 
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if ($showForm): ?>
        <div class="enroll-form-bg" onclick="if(event.target === this) window.location.href='classes.php';">
            <form class="enroll-form" method="post" action="enrolled.php" onclick="event.stopPropagation();">
                <h2>Enroll in <?php echo htmlspecialchars($classToEnroll); ?></h2>
                <label for="student_name">Your Name</label>
                <input type="text" id="student_name" name="student_name" required>

                <label for="student_email">Email</label>
                <input type="email" id="student_email" name="student_email" required>

                <label for="class_schedule">Select a Schedule</label>
                <select id="class_schedule" name="class_schedule" required>
                    <option value="">-- Select a schedule --</option>
                    <?php foreach ($classSchedules as $timestamp => $schedule): ?>
                        <option value="<?php echo $timestamp; ?>"><?php echo $schedule; ?></option>
                    <?php endforeach; ?>
                </select>

                <input type="hidden" name="class_name" value="<?php echo htmlspecialchars($classToEnroll); ?>">
                <button type="submit">Enroll in this Schedule</button>
                <button type="button" onclick="window.location.href='classes.php';" style="background:#aaa;">Cancel</button>
            </form>
        </div>
    <?php endif; ?>


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