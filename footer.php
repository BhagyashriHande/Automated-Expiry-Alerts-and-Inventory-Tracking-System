<!DOCTYPE html>
<html lang="en">
<head>
    <title>Footer Design</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

    <style>
        footer {
            margin-top: 0px;
        }

        .footer-links {
            display: flex;
            justify-content: space-evenly;
            background-color: black;
            color: white;
            height: 30px;
            align-items: center;
            font-size: 16px;
            margin-left: 90px;
        }

        .footer-links a {
            text-decoration: none;
            color: white;
            transition: color 0.3s ease; /* Add transition effect */
        }

        .footer-links a:hover {
            color: #d3003f;
        }

        .social-media-icons {
            display: flex;
            justify-content: center;
            margin-top: 60px;
        }

        .social-media-icons a {
            margin: 0 50px;
            color: white;
            text-decoration: none;
            font-size: 35px;
            transition: color 0.3s ease; /* Add transition effect */
        }

        .social-media-icons a:hover {
            color: #d3003f;
        }

        /* Customize hover colors for social media icons */
        .social-media-icons a[href="https://www.twitter.com/"]:hover {
            color: skyblue; /* Change color to sky blue on hover */
        }

        .social-media-icons a[href="https://www.instagram.com/"]:hover {
            color: fuchsia; /* Change color to pink on hover */
        }

        .social-media-icons a[href="https://www.facebook.com/"]:hover {
            color: blue; /* Change color to blue on hover */
        }

        .social-media-icons a[href="https://www.linkedin.in/"]:hover {
            color: darkblue; /* Change color to dark blue on hover */
        }
    </style>
</head>
<body>

<footer>

    <div style="background-color: black; padding-top: 30px; text-align: center; color: white; padding-bottom: 60px;  margin-top: 0px;">
        <h1 style="color: white;">Automated Expiry Alerts and Inventory Tracking</h1>
        <h2 style="color: orange;"></h2><br><br>

        <div class="footer-links">
            <a href="homepage.php">HOME</a>
            <a href="Inventory.php">INVENTORY TRACKING</a>
            <a href="Billing.php">BILLING</a>
            <a href="account.php">ACCOUNT</a>
            
        </div>

        <div class="social-media-icons">
            <a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a>
            <a href="https://www.twitter.com/"><i class="fab fa-twitter"></i></a>
            <a href="https://www.instagram.com/"><i class="fab fa-instagram"></i></a>
            <a href="https://www.linkedin.in/"><i class="fab fa-linkedin-in"></i></a>
        </div>
    </div>
</footer>

</body>
</html>