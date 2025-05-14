<?php

session_start();

// Include these files
include 'createDatabase.php';    
include 'dbConnect.php';        
include 'dbTablesSetup.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Redirect if already logged in
if (isset($_SESSION['adminLoggedIn']) && $_SESSION['adminLoggedIn'] === true) {
    header("Location: adminDashboard.php");
    exit();
} else if (isset($_SESSION['memberLoggedIn']) && $_SESSION['memberLoggedIn'] === true) {
    header("Location: Homepage.php");
    exit();
}

$errors = [];
$submitClicked = false;

if (isset($_POST['submitMember'])) {
    $submitClicked = true;
    
    // Validate and sanitize inputs
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $firstName = htmlspecialchars(trim($_POST['firstName']));
    $lastName = htmlspecialchars(trim($_POST['lastName']));
    $plainPassword = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // If email has been used already
    $checkEmail = "SELECT email FROM Members WHERE email = ?";
    $stmt = $conn->prepare($checkEmail);
    
    if (!$stmt) {
        $errors[] = "Database error: " . $conn->error;
    } else {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            $errors[] = "Email already registered. Please use a different email or sign in.";
        }
    }

    // Password must have at least 8 characters
    if (strlen($plainPassword) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    // CHeck if passwords match
    if ($plainPassword !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
        $sqlInsertMember = "INSERT INTO Members (email, firstName, lastName, memberPassword) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sqlInsertMember);
        
        if (!$stmt) {
            $errors[] = "Database error: " . $conn->error;
        } else {
            $stmt->bind_param("ssss", $email, $firstName, $lastName, $hashedPassword);

            if ($stmt->execute()) {
                $newMemberId = $conn->insert_id;
                
                // Set session variables
                $_SESSION['memberID'] = $newMemberId;
                $_SESSION['memberLoggedIn'] = true;
                $_SESSION['memberEmail'] = $email;

                // Set cookie
                setcookie("email", $email, time() + (3600 * 1000), "/");
                
                // Close the database connection before redirecting
                $stmt->close();
                $conn->close();
                
                // New users are redirected to membership page instead of homepage
                header("Location: Membership.php");
                exit();
            } else {
                $errors[] = "Error signing up: " . $stmt->error;
                $stmt->close();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Sign Up - Hustle Core</title>

        <link rel="stylesheet" href="projectStyles.css">
    </head>
    <body class="signBody">
        <div class="signContainer">
            <div class="leftSignCont">
                <!-- header img set through css bg-img -->
            </div>
            <div class="rightSignCont">
                <h2>Sign Up</h2>
                
                <?php if (!empty($errors) && $submitClicked): ?>
                    <div class="error-container">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Enter your email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" />

                    <label for="firstName">First Name</label>
                    <input type="text" name="firstName" id="firstName" placeholder="Enter your first name" required value="<?php echo isset($_POST['firstName']) ? htmlspecialchars($_POST['firstName']) : ''; ?>" />

                    <label for="lastName">Last Name</label>
                    <input type="text" name="lastName" id="lastName" placeholder="Enter your last name" required value="<?php echo isset($_POST['lastName']) ? htmlspecialchars($_POST['lastName']) : ''; ?>" />

                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter your password" required />
                    
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Re-enter your password" required />

                    <button type="submit" name="submitMember">Submit</button>
                    
                    <p class="signExtraText">
                    Have an account? <a class="signExtraLink" href="signIn.php">Sign in here.</a>
                    </p>
                </form>
            </div>
        </div>
    </body>
</html>

<?php
// Close database connection if it's still open
if (isset($conn) && $conn) {
    $conn->close();
}
?>