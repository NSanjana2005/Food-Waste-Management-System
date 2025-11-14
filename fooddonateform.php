<?php
session_start();

// Include database connection
include 'connection.php'; // Ensure this file sets the $conn variable

if (isset($_POST['submit'])) {
    $food = trim($_POST['food']);
    $category = $_POST['image-choice'];
    $expiry_hours = intval($_POST['expiry_hours']);
    $quantity = trim($_POST['quantity']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);  // New email field
    $phoneno = trim($_POST['phoneno']);
    $district = $_POST['district'];
    $address = trim($_POST['address']);

    // Validate phone number
    if (!preg_match("/^[0-9]{10}$/", $phoneno)) {
        echo "<script>alert('Invalid phone number.');</script>";
    } else {
        // Calculate expiry timestamp
        $expiry_timestamp = date('Y-m-d H:i:s', strtotime("+$expiry_hours hours"));

        // Insert data into database with expiry time
        $stmt = $connection->prepare("INSERT INTO food_donations (food, category, expiry_hours, expiry_timestamp, quantity, name, email, phoneno, location, address, created_at) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

        if ($stmt === false) {
            die("Error preparing query: " . $connection->error);
        }

        $stmt->bind_param("ssisssssss", $food, $category, $expiry_hours, $expiry_timestamp, $quantity, $name, $email, $phoneno, $district, $address);

        if ($stmt->execute()) {
            echo "<script>alert('Food Donation Added Successfully!');</script>";
            header("location:delivery.html");
            exit;
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Donation Form</title>
    <link rel="stylesheet" href="fooddonate.css"> <!-- Ensure you have a CSS file -->
</head>
<body>
    <div class="container">
        <div class="regformf">
            <form action="" method="post">
                <p class="logo">Food <b style="color: #06C167;">Donate</b></p>
                <div class="input">
                    <label for="food">Food Name:</label>
                    <input type="text" id="food" name="food" required />
                </div>
                <div class="input">
                    <label>Select the Category:</label>
                    <div class="image-radio-group">
                        <input type="radio" id="raw-food" name="image-choice" value="raw-food">
                        <label for="raw-food"><img src="img/raw-food.png" alt="Raw Food"></label>
                        <input type="radio" id="cooked-food" name="image-choice" value="cooked-food" checked>
                        <label for="cooked-food"><img src="img/cooked-food.png" alt="Cooked Food"></label>
                        <input type="radio" id="packed-food" name="image-choice" value="packed-food">
                        <label for="packed-food"><img src="img/packed-food.png" alt="Packed Food"></label>
                    </div>
                </div>
                <div class="input">
                    <label for="expiry">Expiry Time (in hours):</label>
                    <input type="number" id="expiry" name="expiry_hours" min="1" required />
                </div>
                <div class="input">
                    <label for="quantity">Quantity (persons/kg):</label>
                    <input type="text" id="quantity" name="quantity" required />
                </div>
                <b><p style="text-align: center;">Contact Details</p></b>
                <div class="input">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_SESSION['name'] ?? ''); ?>" required />
                </div>
                <div class="input">
                    <label for="email">Email:</label>  <!-- New Email Input -->
                    <input type="email" id="email" name="email" required />
                </div>
                <div class="input">
                    <label for="phoneno">Phone No:</label>
                    <input type="text" id="phoneno" name="phoneno" maxlength="10" pattern="[0-9]{10}" required />
                </div>
                <div class="input">
                    <label for="district">District:</label>
                    <select id="district" name="district">
                        <option value="sangali">Sangali</option>
                        <option value="satara">Satara</option>
                        <option value="Solapur">Solapur</option>
                        <option value="Kolhapur" selected>Kolhapur</option>
                        <option value="Mumbai">Mumbai</option>
                    </select>
                </div>
                <div class="input">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" required />
                </div>
                <div class="btn">
                    <button type="submit" name="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
