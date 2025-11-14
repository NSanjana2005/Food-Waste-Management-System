<?php
session_start();
ob_start();
include '../connection.php';
include("connect.php");

// Check if the user is logged in
if (!isset($_SESSION['name']) || empty($_SESSION['Did'])) {
    header("location:deliverylogin.php");
    exit();
}

$name = $_SESSION['name'];
$id = $_SESSION['Did'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Delivery</title>
    <link rel="stylesheet" href="delivery.css">
    <link rel="stylesheet" href="../home.css">
    <style>
        .itm {
            background-color: white;
            display: grid;
        }
        .itm img {
            width: 400px;
            height: 400px;
            margin: auto;
        }
        p {
            text-align: center;
            font-size: 28px;
            color: black;
        }
        @media (max-width: 767px) {
            .itm img {
                width: 350px;
                height: 350px;
            }
        }
    </style>
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
            <li><a href="delivery.php">Home</a></li>
            <li><a href="deliverymyord.php" class="active">My Orders</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<script>
    const hamburger = document.querySelector(".hamburger");
    hamburger.onclick = function () {
        document.querySelector(".nav-bar").classList.toggle("active");
    }
</script>

<div class="itm">
    <img src="../img/delivery.gif" alt="Delivery" />
</div>

<div class="get">
    <?php
    $sql = "SELECT fd.Fid, fd.name, fd.phoneno, fd.date, fd.delivery_by, fd.address AS From_address,
                   ad.name AS delivery_person_name, ad.address AS To_address
            FROM food_donations fd
            LEFT JOIN admin ad ON fd.assigned_to = ad.Aid
            WHERE fd.delivery_by = '$id'";
    
    $result = mysqli_query($connection, $sql);

    if (!$result) {
        die("Error executing query: " . mysqli_error($connection));
    }

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    ?>

    <div class="log">
        <a href="delivery.php">Take Orders</a>
        <p>Orders Assigned to You</p>
        <br>
    </div>

    <div class="table-container">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone No</th>
                        <th>Date/Time</th>
                        <th>Pickup Address</th>
                        <th>Delivery Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <td data-label="Name"><?= htmlspecialchars($row['name']) ?></td>
                            <td data-label="Phone"><?= htmlspecialchars($row['phoneno']) ?></td>
                            <td data-label="Date"><?= htmlspecialchars($row['date']) ?></td>
                            <td data-label="Pickup Address"><?= htmlspecialchars($row['From_address']) ?></td>
                            <td data-label="Delivery Address"><?= htmlspecialchars($row['To_address']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($data)): ?>
                        <tr><td colspan="5" style="text-align:center;">No orders assigned yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<br><br>
</body>
</html>
