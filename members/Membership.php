<?php
    include 'dbConnect.php';
    include 'memberHandler.php';

    // Require user to be logged in
    requireLogin();
    
    // Handle logout if requested
    handleLogout();
    
    // Get member data if needed
    $memberData = getMemberData();

    // Better debug info
    $debugMessages = [];

    // Handle membership selection
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['membershipType'])) {
        $selectedType = $_POST['membershipType'];
        $userEmail = isset($_SESSION['memberEmail']) ? $_SESSION['memberEmail'] : null;
        
        // Add debug info
        $debugMessages[] = "Selected membership: $selectedType";
        $debugMessages[] = "User email from session: " . ($userEmail ?: "Not found");
        $debugMessages[] = "Session data: " . print_r($_SESSION, true);

        if (!empty($userEmail)) {
            // Use global $conn if needed
            global $conn;

            // Check if connection is valid
            if (!$conn) {
                $debugMessages[] = "Database connection failed: " . mysqli_connect_error();
            } else {
                // Check if the user exists
                $checkUser = $conn->prepare("SELECT memberID FROM Members WHERE email = ?");
                $checkUser->bind_param("s", $userEmail);
                $checkUser->execute();
                $checkUser->store_result();
                
                if ($checkUser->num_rows > 0) {
                    $debugMessages[] = "User found in database";
                    $checkUser->close();
                    
                    // Only proceed with valid membership types
                    if (in_array($selectedType, ['Monthly', 'Quarterly', 'Annual'])) {
                        $stmt = $conn->prepare("UPDATE Members SET membershipType = ? WHERE email = ?");
                        $stmt->bind_param("ss", $selectedType, $userEmail);
                        $result = $stmt->execute();
                        
                        if ($result) {
                            $debugMessages[] = "Query executed successfully";
                            
                            if ($stmt->affected_rows > 0) {
                                $debugMessages[] = "Update successful, rows affected: " . $stmt->affected_rows;
                                $stmt->close();
                                header("Location: Homepage.php");
                                exit();
                            } else {
                                $debugMessages[] = "No rows updated. Error: " . $conn->error;
                            }
                        } else {
                            $debugMessages[] = "Query failed: " . $conn->error;
                        }
                        $stmt->close();
                    } else if ($selectedType === 'None') {
                        // Handle "None" membership type by setting to NULL in database
                        $stmt = $conn->prepare("UPDATE Members SET membershipType = NULL WHERE email = ?");
                        $stmt->bind_param("s", $userEmail);
                        $result = $stmt->execute();
                        
                        if ($result) {
                            $debugMessages[] = "Query executed successfully for 'None' membership";
                            
                            if ($stmt->affected_rows > 0) {
                                $debugMessages[] = "Update to NULL successful, rows affected: " . $stmt->affected_rows;
                                $stmt->close();
                                
                                // Determine where to redirect based on the referring page
                                $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
                                if (strpos($referer, 'Profile.php') !== false) {
                                    header("Location: Profile.php");
                                } else {
                                    header("Location: Homepage.php");
                                }
                                exit();
                            } else {
                                $debugMessages[] = "No rows updated for 'None'. Error: " . $conn->error;
                            }
                        } else {
                            $debugMessages[] = "Query failed for 'None': " . $conn->error;
                        }
                        $stmt->close();
                    } else {
                        $debugMessages[] = "Invalid membership type: $selectedType";
                    }
                } else {
                    $debugMessages[] = "User with email $userEmail not found in database";
                }
            }
        } else {
            $debugMessages[] = "No user email found in session";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Membership Selection - HustleCore</title>

        <!-- FOR EXTERNAL CSS -->
         <link rel="stylesheet" href="projectStyles.css"> 
        
    </head>
    <body>
        <!-- Debug Information (only visible during development) -->
        <?php if (!empty($debugMessages)): ?>
            <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin: 10px; border: 1px solid #f5c6cb;">
                <h3>Debug Information:</h3>
                <ul>
                    <?php foreach ($debugMessages as $message): ?>
                        <li><?php echo htmlspecialchars($message); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
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
                        <li><a class="active" href="Membership.php">Memberships</a></li>
                    <?php endif; ?>
                    <li><a href="AboutUs.php">About Us</a></li>
                </ul>
            </nav>
        </header>

        <!-- WRAPPER -->
        <div class="wrapper"> </div>

        <!-- BREADCRUMBS -->
        <div class="breadcrumbs">
           <span> &gt; Membership</span> 
        </div>
        
        <!-- MAIN CONTENT -->
        <main class="mainCont">
            <h1 class="title">SELECT MEMBERSHIP PLAN</h1>
        
            <section class="cardClassCont">
                <!-- CARD 1 -->
                <div class="cardClass cardMembers">
                    <div class="cardClassInfo">
                        <h2 class="cardClassTitle">Monthly Membership</h2>
                        <h1>₱1000.00</h1>
                        <hr>
                        <ul class="cardClassDetails cardMembersDetails">
                            <li>✅ Best for: 
                                <p class="cardText">
                                    Casual gym-goers, travelers, or those testing the waters.
                                </p>
                            </li>
                            <li>
                                ✨ Perks: 
                                <ul class="cardPerks">
                                    <li>Basic access to equipment, group classes, and locker rooms.</li>
                                </ul>
                            </li>
                        </ul>       
                        <form method="post">
                            <input type="hidden" name="membershipType" value="Monthly">
                            <button class="enrollBtn" type="submit">Apply Now &rarr;</button>
                        </form>
                    </div>
                </div>

                <!-- CARD 2 -->
                <div class="cardClass cardMembers">
                    <div class="cardClassInfo">
                        <h2 class="cardClassTitle">Quarterly Membership</h2>
                        <h1>₱2500.00</h1>
                        <hr>
                        <ul class="cardClassDetails cardMembersDetails">
                            <li>✅ Best for: 
                                <p class="cardText">
                                    Those who want to commit for a few months without a long-term contract.
                                </p>
                            </li>
                            <li>
                                ✨ Perks:
                                <ul class="cardPerks">
                                    <li>Includes perks from the monthly plan.</li>
                                    <li>Free personal training session and nutrition consultation.</li>
                                </ul>
                            </li>
                        </ul>
                        <form method="post">
                            <input type="hidden" name="membershipType" value="Quarterly">
                            <button class="enrollBtn" type="submit">Apply Now &rarr;</button>
                        </form>
                    </div>
                </div>

                <!-- CARD 3 -->
                <div class="cardClass cardMembers">
                    <div class="cardClassInfo" >
                        <h2 class="cardClassTitle">Annual Membership</h2>
                        <h1>₱8000.00</h1>
                        <hr>
                        <ul class="cardClassDetails cardMembersDetails">
                            <li>✅ Best for: Dedicated fitness enthusiasts or those serious about their health journey.</li>
                            <li>✨ Perks: 
                                <ul class="cardPerks">
                                    <li>Includes perks from the monthly and quarterly plans.</li>
                                    <li> Free exclusive gym merchandise and VIP access to special events.</li>
                                </ul>
                            </li>
                        </ul>
                        <form method="post">
                            <input type="hidden" name="membershipType" value="Annual">
                            <button class="enrollBtn" type="submit">Apply Now &rarr;</button>
                        </form>
                    </div>
                </div>
            </section>
        </main>
        <div class="planSkip">
            <form method="post">
                <input type="hidden" name="membershipType" value="None">
                <button class="moreLink" type="submit">Skip for now &rarr;</button>
            </form>
        </div>
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