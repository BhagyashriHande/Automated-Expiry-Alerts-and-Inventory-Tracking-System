<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pharmacy";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errorMsg = ""; 

$fullNameValue = "";
$mobileNumberValue = "";
$emailValue = "";
$addressValue = "";

if (isset($_POST['apply'])) {
    $fullName = $_POST['uname'];
    $mobileNumber = $_POST['mobile_number'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $password = $_POST['password'];

    $fullNameValue = $fullName;
    $mobileNumberValue = $mobileNumber;
    $emailValue = $email;
    $addressValue = $address;

    if (!preg_match("/^\d{10}$/", $mobileNumber)) {
        $errorMsg = "Mobile number must be 10 digits long.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg = "Invalid email format.";
    } else {
        $checkQuery = $conn->prepare("SELECT Email FROM userinfo WHERE Email = ?");
        $checkQuery->bind_param("s", $email);
        $checkQuery->execute();
        $checkQuery->store_result();

        if ($checkQuery->num_rows > 0) {
            $errorMsg = "User already exists. Please choose a different email.";
        } else {
            $stmt = $conn->prepare("INSERT INTO userinfo (Full_Name, Mobile_Number, Email, Address, Password) VALUES (?, ?, ?, ?, ?)");
           $stmt->bind_param("sssss", $fullName, $mobileNumber, $email, $address, $password);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                header("Location: login.php");
                exit();
            } else {
                $errorMsg = "Error: " . $stmt->error;
            }

            $stmt->close();
        }
        
        $checkQuery->close();
        
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: url("Image1.jpeg");
            background-size: cover;
            background-position: center;
        }

        .wrapper {
            width: 600px;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: black;
            border-radius: 12px;
            padding: 30px;
            font-size: 15px;
        }

        .wrapper h1 {
            font-size: 36px;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-row {
            margin-bottom: 20px;
            padding-left:20px;
        }

        .form-row label {
            display: block;

            margin-bottom: 8px;
            font-size: 16px;
        }

        .form-row input[type="text"],
        .form-row input[type="password"],
        .form-row textarea {
            width: 90%; 
            padding: 13px;
            background: transparent;
            border: none;
            outline: none;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            color: black;
            font-size: 15px;
            transition: all 0.3s ease; 
        }

        .form-row textarea {
            height: 80px;
            resize: none;
        }

        .form-row input::placeholder,
        .form-row textarea::placeholder {
            color: black;
            font-size: 15px;
        }

        .form-row.password-field {
            position: relative;
        }

        .form-row input[type="text"]:hover,
        .form-row input[type="password"]:hover,
        .form-row textarea:hover,
        .form-row input[type="text"]:focus,
        .form-row input[type="password"]:focus,
        .form-row textarea:focus {
            border-color: rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.1);
            transform: scale(1.03); /* Slightly increase size */
            box-shadow: 0 0 8px rgba(255, 255, 255, 0.5);
        }

        .eye-area {
            position: absolute;
            right: 75px;
            top: 70%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .btn {
    display: block; /* Ensures it behaves as a block-level element */
    width: 60%;
    padding: 13px;
    background: #fff;
    border: none;
    border-radius: 8px;
    color: #333;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s;
    margin: 20px auto; /* Centers the button horizontally */
    text-align: center; /* Ensures text inside the button is centered */
}


        .btn:hover {
            background-color: #ccc;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h1>User Registration</h1>
        <?php if (!empty($errorMsg)): ?>
            <div class="error-message"><?php echo $errorMsg; ?></div>
        <?php endif; ?>
        <form action="registration.php" method="POST">
            <div class="form-row">
                <label for="uname">Full Name:</label>
                <input type="text" id="uname" name="uname" placeholder="Enter Full Name" value="<?php echo $fullNameValue; ?>" required>
            </div>
            <div class="form-row">
                <label for="mobile_number">Mobile Number:</label>
                <input type="text" id="mobile_number" name="mobile_number" placeholder="Enter Mobile Number" value="<?php echo $mobileNumberValue; ?>" required>
            </div>
            <div class="form-row">
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" placeholder="Enter Email" value="<?php echo $emailValue; ?>" required>
            </div>
            <div class="form-row">
                <label for="address">Address:</label>
                <textarea id="address" name="address" placeholder="Enter your full address"><?php echo $addressValue; ?></textarea>
            </div>
            <div class="form-row password-field">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter Password" required>
                <div class="eye-area" onclick="togglePassword()">
                    <i class="fa fa-eye" id="eye"></i>
                    <i class="fa fa-eye-slash" id="eye-slash" style="display: none;"></i>
                </div>
            </div><br>
            <button type="submit" class="btn" name="apply">Register</button>
        </form>
    </div>
    <script>
        function togglePassword() {
            const passwordField = document.getElementById("password");
            const eye = document.getElementById("eye");
            const eyeSlash = document.getElementById("eye-slash");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                eye.style.display = "none";
                eyeSlash.style.display = "block";
            } else {
                passwordField.type = "password";
                eye.style.display = "block";
                eyeSlash.style.display = "none";
            }
        }
    </script>
</body>
</html>
