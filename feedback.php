<?php
session_start();
include 'connection.php';

if (isset($_POST['feedback'])) {
  $email = $_POST['email'];
  $name = $_POST['name'];
  $msg = $_POST['message'];
  $sanitized_emailid =  mysqli_real_escape_string($connection, $email);
  $sanitized_name =  mysqli_real_escape_string($connection, $name);
  $sanitized_msg =  mysqli_real_escape_string($connection, $msg);
  $query="insert into user_feedback(name,email,message) values('$sanitized_name','$sanitized_emailid','$sanitized_msg')";
  $query_run= mysqli_query($connection, $query);
  if($query_run)
  {
    //echo '<script type="text/javascript">alert("data saved")</script>';
      header("location:contact.html");
     
  }
  else{
      echo '<script type="text/javascript">alert("data not saved")</script>'; 
  }

}
?>
<?php
session_start();
include 'connection.php';

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_POST['send'])) { // Fixed the button name check
    $email = $_POST['email'];
    $name = $_POST['name'];
    $msg = $_POST['message'];

    // Sanitize inputs
    $sanitized_emailid = mysqli_real_escape_string($connection, $email);
    $sanitized_name = mysqli_real_escape_string($connection, $name);
    $sanitized_msg = mysqli_real_escape_string($connection, $msg);

    // Debugging: Print values to check if they are received
    echo "Name: $sanitized_name <br>";
    echo "Email: $sanitized_emailid <br>";
    echo "Message: $sanitized_msg <br>";

    // Insert query
    $query = "INSERT INTO user_feedback (name, email, message) VALUES ('$sanitized_name', '$sanitized_emailid', '$sanitized_msg')";
    $query_run = mysqli_query($connection, $query);

    if ($query_run) {
        header("Location: contact.html");
        exit();
    } else {
        die("Error: " . mysqli_error($connection));
    }
}
?>
