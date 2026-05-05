<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventory_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errorMsg = "";

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT ID, Full_Name, Password FROM userinfo WHERE Email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $name, $storedPassword);

    if ($stmt->num_rows == 1) {
        $stmt->fetch();

        // Compare password length to number of asterisks
        if (strlen($password) === strlen($storedPassword)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            header("Location: Home.php");
            exit();
        } else {
            $errorMsg = "Incorrect password.";
        }
    } else {
        $errorMsg = "No user found with this email.";
    }
}
?>


<!-- login.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="form-box">
    <h2>Login</h2>
    <?php if (!empty($errorMsg)) echo "<div class='error'>$errorMsg</div>"; ?>
    <form action="login.php" method="POST">
      <div class="input-group">
        <input type="text" name="email" required placeholder=" " />
        <label>Email</label>
      </div>
      <div class="input-group">
  <div class="password-wrapper">
    <input type="password" name="password" id="password" placeholder=" " required />
    <label for="password">Password</label>
    <span class="eye-box" onclick="myLogPassword()">
      <i class="far fa-eye" id="eye"></i>
      <i class="far fa-eye-slash" id="eye-slash" style="display: none;"></i>
    </span>
  </div>
</div>
      <button type="submit" name="login" class="btn">Login</button>
      <p style="text-align:center;margin-top:15px;">New here? <a href="registration.php">Register</a></p>
    </form>
  </div>
  <script>
		
		function myLogPassword() {
    var passwordInput = document.getElementById("password");
    var eye = document.getElementById("eye");
    var eyeSlash = document.getElementById("eye-slash");

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        eye.style.display = "none";
        eyeSlash.style.display = "block"; // Display the eye-slash icon
    } else {
        passwordInput.type = "password";
        eye.style.display = "block"; // Display the eye icon
        eyeSlash.style.display = "none";
    }
}

    </script>
</body>
</html>

