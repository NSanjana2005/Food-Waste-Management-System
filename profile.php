<?php 
session_start(); 
include("connection.php"); // Ensure database connection is included

// Redirect to signup if the user is not logged in
if (!isset($_SESSION['name']) || empty($_SESSION['name'])) {     
    header("Location: signup.php");     
    exit(); 
}  
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<header>
    <div class="logo">Food <b style="color: #06C167;">Donate</b></div>
    <div class="hamburger">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
    </div>
    <nav class="nav-bar">
        <ul>
            <li><a href="home.html">Home</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="contact.html">Contact</a></li>
            <li><a href="profile.php" class="active">Profile</a></li>
        </ul>
    </nav>
</header>

<script>
document.querySelector(".hamburger").onclick = function() { 
    document.querySelector(".nav-bar").classList.toggle("active"); 
}
</script>

<div class="profile">
    <div class="profilebox">
        <p class="headingline" style="text-align: left;font-size:30px;">Profile</p>
        <br>
        <div class="info" style="padding-left:10px;">
            <p>Name  : <?php echo htmlspecialchars($_SESSION['name']); ?> </p><br>
            <p>Email : <?php echo htmlspecialchars($_SESSION['email']); ?> </p><br>
            <a href="logout.php" style="float: left;margin-top: 6px; border-radius:5px; background-color: #06C167; color: white;padding: 10px;">Logout</a>
        </div>
        <br><br>
        <hr>
        <br>
        <p class="heading">Your Donations</p>
        <div class="table-container">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Food</th>
                            <th>Category</th>
                            <th>Phone No</th>
                            <th>Date/Time</th>
                            <th>Address</th>
                            <th>Quantity</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $email = $_SESSION['email'];
                    $query = "SELECT * FROM food_donations WHERE email='$email' ORDER BY expiry_timestamp ASC";
                    $result = mysqli_query($connection, $query);

                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['name']) . "</td> 
                                    <td>" . htmlspecialchars($row['food']) . "</td>
                                    <td>" . htmlspecialchars($row['category']) . "</td>
                                    <td>" . htmlspecialchars($row['phoneno']) . "</td>
                                    <td>" . htmlspecialchars($row['date']) . "</td>
                                    <td>" . htmlspecialchars($row['address']) . "</td>
                                    <td>" . htmlspecialchars($row['quantity']) . "</td>
                             
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No donations found</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>
