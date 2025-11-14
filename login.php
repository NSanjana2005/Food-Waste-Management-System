<?php
// Start session only if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include 'connection.php';

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_POST['sign'])) {
    // Sanitize user inputs
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sanitized_emailid = mysqli_real_escape_string($connection, $email);
    $sanitized_password = mysqli_real_escape_string($connection, $password);

    // Check if user exists
    $sql = "SELECT * FROM login WHERE email = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "s", $sanitized_emailid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Verify password
        if (password_verify($sanitized_password, $row['password'])) {
            // Store session variables
            $_SESSION['email'] = $row['email'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['gender'] = $row['gender'];
            
            // Redirect to home page
            header("Location: home.html");
            exit();
        } else {
            echo "<h1><center>Login Failed: Incorrect password</center></h1>";
        }
    } else {
        echo "<h1><center>Account does not exist</center></h1>";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($connection);
?>
