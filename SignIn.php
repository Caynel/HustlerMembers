<?php
session_start();

include 'createDatabase.php';
include 'dbTablesSetup.php';
include 'dbConnect.php';

// Admin credentials
$adminName = "admin";
$adminPassword = "12345";
$adminEmail = "admin@hustle.core";
$logErr = "";

// Redirect if already logged in
if (isset($_SESSION['adminLoggedIn']) && $_SESSION['adminLoggedIn'] === true) {
    header("Location: adminDashboard.php");
    exit();
}
    
if (isset($_SESSION['memberLoggedIn']) && $_SESSION['memberLoggedIn'] === true) {
    header("Location: Homepage.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email']; 
    $password = $_POST['password']; 

    // Check for ADMIN login
    if ($email == $adminEmail && $password === $adminPassword) {
        $_SESSION['adminLoggedIn'] = true;
        $_SESSION['adminEmail'] = $email; 
        $_SESSION['adminName'] = 'Admin';

        setcookie("email", $email, time() + (3600 * 1000), "/");

        header("Location: adminDashboard.php");
        exit();
    } else {    //check for members log in
        $sql = "SELECT memberID, memberPassword FROM Members WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['memberPassword'])) {
                $_SESSION['memberLoggedIn'] = true;
                $_SESSION['memberID'] = $row['memberID'];
                $_SESSION['memberEmail'] = $email;

                setcookie("email", $email, time() + (3600 * 1000), "/");

                header("Location: Homepage.php");
                exit();
            } else {
                $logErr = "Invalid email or password. Please try again.";
            }
        } else {
            $logErr = "Invalid email or password. Please try again.";
        }
        
        if (isset($stmt)) {
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Sign In</title>

        <link rel="stylesheet" href="projectStyles.css">
    </head>
    <body class="signBody">
        <div class="signIncontainer">
            <div class="leftSignCont">
                <!-- Background image is set via CSS -->
            </div>
            <div class="rightSignCont">
                <h2>Sign In</h2>

                <?php if (!empty($logErr)): ?>
                    <div class="error-message"><?php echo $logErr; ?></div>
                <?php endif; ?>

                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required />

                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required />
                    
                    <div class="forgotPass">
                        <a href="#">Forgot password?</a>
                    </div>

                    <button type="submit">Submit</button>
                    
                    <p class="signExtraText">
                        Don't have an account? <a class="signExtraLink" href="signUp.php">Sign up here.</a>
                    </p>
                </form>
            </div>
        </div>
    </body>
</html>

<?php
// Close connection if it exists
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>