<?php
session_start();
include '../connection.php';

$msg = 0;

if (isset($_POST['sign'])) {
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    $sql = "SELECT * FROM admin WHERE email='$email'";
    $result = mysqli_query($connection, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['email'] = $row['email'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['location'] = $row['location'];
            $_SESSION['Aid'] = $row['Aid'];
            header("Location: admin.php");
            exit();
        } else {
            $msg = 1; // Incorrect password
        }
    } else {
        $msg = 2; // Account does not exist
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="formstyle.css">
    <script src="signin.js" defer></script>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
</head>
<body>
    <div class="container">
        <div class="regform">
            <form id="form" method="post">
                <span class="title">Login</span>
                <br><br>

                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" required>
                    <div class="error"></div>
                </div>

                <label class="textlabel" for="password">Password</label>
                <div class="password">
                    <input type="password" name="password" id="password" required />
                    <i class="uil uil-eye-slash showHidePw" id="showpassword"></i>
                </div>

                <?php if ($msg == 1): ?>
                    <p class="error" style="color:red;">Incorrect password.</p>
                <?php elseif ($msg == 2): ?>
                    <p class="error" style="color:red;">Account does not exist.</p>
                <?php endif; ?>

                <button type="submit" name="sign">Login</button>

                <div class="login-signup">
                    <span class="text">Don't have an account?
                        <a href="signup.php" class="text login-link">Register</a>
                    </span>
                </div>
            </form>
        </div>
    </div>
    <script src="login.js"></script>
</body>
</html>
