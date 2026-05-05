
<?php
$login = false;
$showerror = false;

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pharmacy";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $_POST["email"];
    $pass = $_POST["pass"];

    if (!empty($email) && !empty($pass)) {
        $sql = "SELECT * FROM userinfo WHERE Email = ? AND Password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $pass);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            $num = $result->num_rows;

            if ($num == 1) {
                $login = true;
                session_start();
                $row = $result->fetch_assoc();
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $row['User_ID'];
                $_SESSION['email'] = $email;

                if (isset($_SESSION['redirect'])) {
                    $redirect = $_SESSION['redirect'] . '.php';
                    unset($_SESSION['redirect']);
                    header("location: $redirect");
                } else {
                    header("location: Home.php ");
                }
                exit();
            } else {
                $showerror = true; 
            }
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $showerror = true;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
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
            background-position: center;
            background-size: cover;
        }

        .wrapper {
            width: 420px;
            background: transparent;
            border: 2px solid rgba(255, 255, 255, .2);
            backdrop-filter: blur(9px);
            color: black;
            border-radius: 12px;
            padding: 30px 30px;
        }

        .wrapper h1 {
            font-size: 36px;
            text-align: center;
            margin-bottom: 20px;
        }

        .error-message {
            color: red;
            text-align: center;
            font-weight: bold;
            margin-top: 10px;
        }

        .input-box {
            position: relative;
            width: 100%;
            height: 50px;
            margin: 30px 0;
        }

        .input-box.password-input {
            margin-bottom: 40px;
        }

        .input-box input {
            width: 100%;
            height: 100%;
            background: transparent;
            border: none;
            outline: none;
            border: 2px solid rgba(255, 255, 255, .2);
            border-radius: 40px;
            font-size: 16px;
            color: black;
            padding: 20px 45px 20px 20px;
        }

        .input-box input::placeholder {
            color: black;
        }

        .input-box i {
            position: absolute;
            right: 20px;
            top: 30%;
            transform: translate(-50%);
            font-size: 20px;
        }


        .btn {
            width: 100%;
            height: 45px;
            background: #fff;
            border: none;
            outline: none;
            border-radius: 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, .1);
            cursor: pointer;
            font-size: 16px;
            color: #333;
            font-weight: 600;
            margin-top: 20px;
        }

        .register-link {
            font-size: 14.5px;
            text-align: center;
            margin: 20px 0 15px;
        }

        .register-link p a {
            color: #FF0000;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link p a:hover {
            text-decoration: underline;
            color: #FF0000;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <form action="login.php" method="POST">
            <!-- <center>
                <img src="images/logo2.png" width="320" height="70">
            </center> -->
            <h1>User Login</h1>

            <!-- Display error message only if $showerror is true -->
            <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && $showerror): ?>
            <div class="error-message">
                Invalid credentials. Please sign up first.
            </div>
            <?php endif; ?>

            <div class="input-box">
                <input type="text" id="email" name="email" placeholder="Enter Email" required>
                <i class="fas fa-envelope"></i>
            </div>
            <div class="input-box password-input">
                <input type="password" id="pass" name="pass" placeholder="Password" required>
                <div class="eye-area">
                    <div class="eye-box" onclick="togglePasswordVisibility()">
                        <i class="fa-regular fa-eye" id="eye"></i>
                        <i class="fa-regular fa-eye-slash" id="eye-slash" style="display: none;"></i>
                    </div>
                </div>
            </div>

            <!-- <div class="remember-forgot">
                <label><input type="checkbox"> Remember Me</label>
                <a href="#">Forgot Password</a>
            </div> -->
            <button type="submit" name="apply" class="btn">Login</button>
            <div class="register-link">
                <p>Don't have an account? <a href="registration.php">Sign up here</a></p>
            </div>
        </form>
    </div>
    <script>
        function togglePasswordVisibility() {
            var passField = document.getElementById("pass");
            var eyeOpen = document.getElementById("eye");
            var eyeSlash = document.getElementById("eye-slash");

            if (passField.type === "password") {
                passField.type = "text";
                eyeOpen.style.display = "none";
                eyeSlash.style.display = "block";
            } else {
                passField.type = "password";
                eyeOpen.style.display = "block";
                eyeSlash.style.display = "none";
            }
        }
    </script>
</body>
</html>
