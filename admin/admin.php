<?php
ob_start();
include("connect.php");
session_start();

if (!isset($_SESSION['name'])) {
    header("location:signin.php");
    exit();
}

// Remove expired food donations
$current_time = date('Y-m-d H:i:s');
$delete_query = "DELETE FROM food_donations WHERE expiry_timestamp IS NOT NULL AND expiry_timestamp <= '$current_time'";
mysqli_query($connection, $delete_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard Panel</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
</head>
<body>
    <nav>
        <div class="logo-name">
            <div class="logo-image"></div>
            <span class="logo_name">ADMIN</span>
        </div>
        <div class="menu-items">
            <ul class="nav-links">
                <li><a href="#"><i class="uil uil-estate"></i><span class="link-name">Dashboard</span></a></li>
                <li><a href="donate.php"><i class="uil uil-heart"></i><span class="link-name">Donations</span></a></li>
                <li><a href="feedback.php"><i class="uil uil-comments"></i><span class="link-name">Feedbacks</span></a></li>
                <li><a href="adminprofile.php"><i class="uil uil-user"></i><span class="link-name">Profile</span></a></li>
            </ul>
            <ul class="logout-mode">
                <li><a href="../logout.php"><i class="uil uil-signout"></i><span class="link-name">Logout</span></a></li>
            </ul>
        </div>
    </nav>

    <section class="dashboard">
        <div class="top">
            <i class="uil uil-bars sidebar-toggle"></i>
            <p class="logo">Food <b style="color: #06C167;">Donate</b></p>
        </div>

        <div class="dash-content">
            <div class="overview">
                <div class="title"><i class="uil uil-tachometer-fast-alt"></i><span class="text">Dashboard</span></div>
                <div class="boxes">
                    <div class="box box1">
                        <i class="uil uil-user"></i>
                        <span class="text">Total users</span>
                        <?php
                        $query = "SELECT count(*) as count FROM login";
                        $result = mysqli_query($connection, $query);
                        $row = mysqli_fetch_assoc($result);
                        echo "<span class=\"number\">".$row['count']."</span>";
                        ?>
                    </div>
                    <div class="box box2">
                        <i class="uil uil-comments"></i>
                        <span class="text">Feedbacks</span>
                        <?php
                        $query = "SELECT count(*) as count FROM user_feedback";
                        $result = mysqli_query($connection, $query);
                        $row = mysqli_fetch_assoc($result);
                        echo "<span class=\"number\">".$row['count']."</span>";
                        ?>
                    </div>
                    <div class="box box3">
                        <i class="uil uil-heart"></i>
                        <span class="text">Total donations</span>
                        <?php
                        $query = "SELECT count(*) as count FROM food_donations";
                        $result = mysqli_query($connection, $query);
                        $row = mysqli_fetch_assoc($result);
                        echo "<span class=\"number\">".$row['count']."</span>";
                        ?>
                    </div>
                </div>
            </div>

            <div class="activity">
                <div class="title"><i class="uil uil-clock-three"></i><span class="text">Recent Donations</span></div>

                <div class="get">
                    <!-- Location Filter -->
                    <form method="get">
                        <label for="loc">Filter by Location:</label>
                        <select name="loc" id="loc" onchange="this.form.submit()">
                            <option value="">All</option>
                            <?php
                            $loc_query = "SELECT DISTINCT location FROM food_donations";
                            $loc_result = mysqli_query($connection, $loc_query);
                            while ($loc_row = mysqli_fetch_assoc($loc_result)) {
                                $selected = (isset($_GET['loc']) && $_GET['loc'] === $loc_row['location']) ? 'selected' : '';
                                echo "<option value='{$loc_row['location']}' $selected>{$loc_row['location']}</option>";
                            }
                            ?>
                        </select>
                    </form>

                    <?php
                    $selected_loc = $_GET['loc'] ?? '';
                    $sql = "SELECT * FROM food_donations WHERE (expiry_timestamp IS NULL OR expiry_timestamp > '$current_time')";
                    if (!empty($selected_loc)) {
                        $sql .= " AND location = '".mysqli_real_escape_string($connection, $selected_loc)."'";
                    }
                    $sql .= " ORDER BY expiry_timestamp ASC";

                    $result = mysqli_query($connection, $sql);
                    if (!$result) {
                        die("Error executing query: " . mysqli_error($connection));
                    }

                    $data = [];
                    while ($row = mysqli_fetch_assoc($result)) {
                        $data[] = $row;
                    }
                    ?>

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
                                        <th>Expiry Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data as $row) { ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['name']) ?></td>
                                            <td><?= htmlspecialchars($row['food']) ?></td>
                                            <td><?= htmlspecialchars($row['category']) ?></td>
                                            <td><?= htmlspecialchars($row['phoneno']) ?></td>
                                            <td><?= htmlspecialchars($row['date']) ?></td>
                                            <td><?= htmlspecialchars($row['address']) ?></td>
                                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                                            <td><?= $row['expiry_timestamp'] ? date('Y-m-d H:i:s', strtotime($row['expiry_timestamp'])) : 'N/A' ?></td>
                                            <td>
                                                <?php if ($row['assigned_to'] == null) { ?>
                                                    <form method="post">
                                                        <input type="hidden" name="order_id" value="<?= $row['Fid'] ?>">
                                                        <button type="submit" name="food">Get Food</button>
                                                    </form>
                                                <?php } else { ?>
                                                    Assigned
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <?php
                    if (isset($_POST['food'])) {
                        $order_id = $_POST['order_id'];
                        $sql = "UPDATE food_donations SET assigned_to = {$_SESSION['Aid']} WHERE Fid = $order_id";
                        $result = mysqli_query($connection, $sql);
                        if (!$result) {
                            die("Error assigning order: " . mysqli_error($connection));
                        }
                        header('Location: ' . $_SERVER['REQUEST_URI']);
                        exit();
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>

    <script src="admin.js"></script>
</body>
</html>
