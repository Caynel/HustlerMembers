<?php

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection if not already included
if (!function_exists('mysqli_connect') && !isset($conn)) {
    include_once 'dbConnect.php';
}

// Check if user is logged in
function isLoggedIn() {
    return (isset($_SESSION['memberLoggedIn']) && $_SESSION['memberLoggedIn'] === true) || 
           (isset($_SESSION['adminLoggedIn']) && $_SESSION['adminLoggedIn'] === true);
}

// Restrict mmber pages
function restrictToMembers() {
    if (!isLoggedIn()) {
        // Check if email cookie exists and try to auto-login
        if (isset($_COOKIE['email'])) {
            $email = $_COOKIE['email'];
            global $conn;
            
            // Check if this is the admin email
            if ($email === "admin@hustle.core") { 
                header("Location: signIn.php");
                exit();
            } else {
                // Try to auto-login the member
                $sql = "SELECT memberID FROM Members WHERE email = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows === 1) {
                    $row = $result->fetch_assoc();
                    
                    // Set session variables
                    $_SESSION['memberLoggedIn'] = true;
                    $_SESSION['memberID'] = $row['memberID'];
                    $_SESSION['memberEmail'] = $email;
                    
                } else {
                    header("Location: signIn.php?redirect=" . urlencode($_SERVER['PHP_SELF']));
                    exit();
                }
                
                $stmt->close();
            }
        } else {
            // No session or cookie - redirect to login
            header("Location: signIn.php?redirect=" . urlencode($_SERVER['PHP_SELF']));
            exit();
        }
    }
}

// Function to check current page type
function isRestrictedPage() {
    $currentPage = basename($_SERVER['PHP_SELF']);
    $restrictedPages = ['session.php', 'enrolled.php', 'Profile.php', 'membership.php'];
    
    return in_array($currentPage, $restrictedPages);
}


// Function to redirect to login if not logged in
function requireLogin() {
    restrictToMembers();
}

// Get member information if available
function getMemberData() {
    global $conn;
    
    // Make sure we have a connection
    if (!isset($conn)) {
        include_once 'dbConnect.php';
    }
    
    if (isset($_SESSION['memberID'])) {
        $memberID = $_SESSION['memberID'];
        
        $sql = "SELECT * FROM Members WHERE memberID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $memberID);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $memberData = $result->fetch_assoc();
            $stmt->close();
            return $memberData;
        }
        
        $stmt->close();
    }
    
    return null;
}


// Handle logout if the form was submitted
function handleLogout() {
    if (isset($_POST['logout'])) {
        // Clear all session variables
        $_SESSION = array();
        
        // Destroy the session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 42000, '/');
        }
        
        // Clear the email cookie if it exists
        if (isset($_COOKIE['email'])) {
            setcookie('email', '', time() - 3600, '/');
        }
        
        // Destroy the session completely
        session_destroy();
        
        // Redirect to sign in page
        header("Location: signIn.php");
        exit();
    }
}


// FOR ENROLLING IN CLASSES
// Store enrolled class in session
function enrollClass($className) {
    $_SESSION['enrolled_class'] = $className;
}

// Remove enrolled class from session
function cancelEnrolledClass() {
    unset($_SESSION['enrolled_class']);
}

// Get enrolled class from session
function getEnrolledClass() {
    return isset($_SESSION['enrolled_class']) ? $_SESSION['enrolled_class'] : null;
}
?>