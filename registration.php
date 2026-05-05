<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// DB connection setup
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventory_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errorMsg = "";

if (isset($_POST['register'])) {
    $name = $_POST['uname'];
    $mobile = $_POST['mobile_number'];
    $email = $_POST['email'];
    $medical = $_POST['medical'];
    $address = $_POST['address'];
    $raw_password = $_POST['password'] ?? '';
if ($raw_password !== '') {
    $hashed_password = str_repeat('*', strlen($raw_password));

} else {
    $errorMsg = "Password field is empty.";
}
if (strlen($raw_password) < 6) {
    $errorMsg = "Password must be at least 6 characters.";
}

    // Basic validation
    if (!preg_match("/^\d{10}$/", $mobile)) {
        $errorMsg = "Mobile number must be exactly 10 digits.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg = "Invalid email format.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT Email FROM userinfo WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errorMsg = "This email is already registered.";
        } else {
            // Insert user info
            $stmt = $conn->prepare("INSERT INTO userinfo (Full_Name, Mobile_Number, Email, medical,  Address, Password) VALUES (?, ?, ?,?, ?, ?)");
            $stmt->bind_param("ssssss", $name, $mobile, $email, $medical, $address, $hashed_password);


            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $errorMsg = "Registration failed: " . $stmt->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="form-box">
    <h2>Create Account</h2>
    <?php if (!empty($errorMsg)) echo "<div class='error'>$errorMsg</div>"; ?>
    <form action="registration.php" method="POST">
      <div class="input-group">
        <input type="text" name="uname" required placeholder=" " />
        <label>Full Name</label>
      </div>
      <div class="input-group">
        <input type="text" name="mobile_number" required placeholder=" " />
        <label>Mobile Number</label>
      </div>
      <div class="input-group">
        <input type="text" name="email" required placeholder=" " />
        <label>Email</label>
      </div>
      <div class="input-group">
        <input type="text" name="medical" required placeholder=" " />
        <label>Name of Store</label>
      </div>
      <div class="input-group">
        <textarea name="address" rows="3" required placeholder=" "></textarea>
        <label>Address</label>
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


      <button type="submit" name="register" class="btn">Register</button>

      <p style="text-align:center;margin-top:15px;">Already registered? <a href="login.php">Login</a></p>
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
