<?php

    include 'dbConnect.php';
    include 'memberHandler.php';

    // Get member data if logged in
    $memberData = null;
    if (isLoggedIn()) {
        $memberData = getMemberData();
    }
    
    handleLogout();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>About Us</title>
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
                        <li><a href="session.php">Sessions</a></li>
                        <li><a href="enrolled.php">Enrolled Classes</a></li>
                    <?php endif; ?>
                    <li><a class="active" href="AboutUs.php">About Us</a></li>
                </ul>
            </nav>
        </header>

        <!-- WRAPPER -->
        <div class="wrapper"> </div>
        
            <!-- BREADCRUMBS -->
        <div class="breadcrumbs">
            <span>&gt; About</span>
        </div>

        <main class="mainCont">
            <section class="aboutUs">
                <article class="aboutText">
                    <h2>About Us</h2>
                    <p>
                        At HustleCore, we are more than just a fitness community. We are a movement driven by passion, 
                        dedication, and the pursuit of personal excellence. Our goal is to provide a space where people 
                        of all fitness levels can challenge themselves and achieve their goals.
                    </p>
                    <p>
                        Whether you're a seasoned athlete or just starting your fitness journey, we believe in empowering 
                        every individual to push their limits and transform their lives.
                    </p>
                </article>
                
                <aside>
                    <img src="images/dedlift.jpg" alt="A person about to lift a barbell.">
                </aside>
            </section>

            <section class="aboutDescrip">
                <h2>Our Legacy</h2>
                <p>
                    HustleCore was built on the belief that fitness is more than just a workout, it’s a lifestyle, 
                    a mindset, and a commitment to becoming the best version of yourself. What started as 
                    a dream has grown into a powerhouse of motivation and transformation, 
                    driven by a community that thrives on sweat, determination, and an unbreakable will.
                </p>
            </section>

            <h2>Meet the Crew</h2>
            <section class="teamCont">
                <div class="aboutCard">
                    <img class="cardClassImg aboutCrew" src="images/profile.png" alt="People doing yoga.">
                    <div class="cardClassInfo">
                        <h2 class="cardClassTitle aboutCrewName">Dagoy, Cyra U.</h2>
                        <ul class="cardClassDetails">
                            <li>Role</li>
                            <li>Number</li>
                            <li>Email Address</li>
                        </ul>
                    </div>
                </div>

                <!-- CARD 2 -->
                <div class="aboutCard">
                    <img  class="cardClassImg aboutCrew" src="images/profile.png" alt="People doing HIIT.">
                    <div class="cardClassInfo">
                        <h2 class="cardClassTitle aboutCrewName">Duran, Chava</h2>
                        <ul class="cardClassDetails">
                            <li>Role</li>
                            <li>Number</li>
                            <li>Email Address</li>
                        </ul>
                    </div>
                </div>

                <!-- CARD 3 -->
                <div class="aboutCard">
                    <img class="cardClassImg aboutCrew" src="images/profile.png" alt="Guy doing handstand for crossfit.">
                    <div class="cardClassInfo">
                        <h2 class="cardClassTitle aboutCrewName">Porol, Ecie Jean</h2>
                        <ul class="cardClassDetails">
                            <li>Role</li>
                            <li>Number</li>
                            <li>Email Address</li>
                        </ul>
                    </div>
                </div>
            </section>

            <?php if (isLoggedIn()): ?>
                <section class="join">
                    <h2>Welcome to the HustleCore family!</h2>
                    <p class="joinText">
                        Push your limits, break barriers, and transform your fitness journey with us.
                        No matter your level, we've got the energy, equipment, and unwavering support to fuel your success. 
                        Let's hustle, together!
                    </p>
                </section>
            <?php else: ?>
                <section class="join">
                    <h2>Join Us</h2>
                    <p class="joinText">
                        Push limits, break barriers, and transform your fitness journey
                        with us. No matter your level, we’ve got the energy, 
                        equipment, and support to fuel your success.

                        Ready to hustle? Join HustleCore today!
                    </p>
                    <a class="aboutBtn" href="SignUp.php">&rarr;</a>
                </section>
            <?php endif; ?>
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