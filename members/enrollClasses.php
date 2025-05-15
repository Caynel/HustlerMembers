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

    // enrollClasses.php is only accessible through classes.php
    if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], 'classes.php') === false) {
        header("Location: classes.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
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
                    <li><a href="membership.php">Memberships</a></li>
                <?php endif; ?>
                <li><a href="AboutUs.php">About Us</a></li>
            </ul>
        </nav>
    </header>

    <!-- WRAPPER -->
    <div class="wrapper"> </div>

    <!-- BREADCRUMBS -->
    <div class="breadcrumbs">
        &gt; <a href="classes.php">Classes </a><span> &gt; Enroll Class</span> 
    </div>
    
    <!-- CONTENT -->
    <main class="mainCont">
        <h2>Available Classes</h2>
        <section class="enrollClassesCont">
            <?php
                // Query classes from Activities table
                $classNameFilter = '';
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['className'])) {
                    $classNameFilter = $_POST['className'];
                }

                $sql = "SELECT activityID, activityName, instructor, schedule, duration, capacity FROM Activities WHERE activityType = 'class'";
                if (!empty($classNameFilter)) {
                    $safeClassName = $conn->real_escape_string($classNameFilter);
                    $sql .= " AND activityName = '$safeClassName'";
                }
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0): 
            ?>

            <!-- TABLE THAT DISPLAYS THE ACTIVITIES -->
            <table>
                <thead>
                    <tr>
                        <th>Class Name</th>
                        <th>Instructor</th>
                        <th>Schedule</th>
                        <th>Duration</th>
                        <th>Capacity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['activityName']); ?></td>
                            <td><?php echo htmlspecialchars($row['instructor']); ?></td>
                            <td><?php echo date("F j, Y - h:i A", strtotime($row['schedule'])); ?></td>
                            <td><?php echo (int)$row['duration']; ?> mins</td>
                            <td><?php echo (int)$row['capacity']; ?></td>
                            
                            <td>
                                <a href="?enroll=<?php echo $row['activityID']; ?>" class="enroll-btn" style="padding:6px 14px; background:#007bff; color:#fff; border-radius:4px; text-decoration:none;">Enroll</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p>No classes available at the moment.</p>
            <?php endif; ?>
        </section>
    </main>

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