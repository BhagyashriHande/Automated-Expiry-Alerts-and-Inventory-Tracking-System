<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';  

$owner_email = "handebhagyashri123@gmail.com";

$conn = new mysqli("localhost", "root", "", "inventory_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT productName, expiryDate FROM inventory";

$result = $conn->query($sql);

$notifiedFile = 'notified_products.json';
$notifiedProducts = [];

if (file_exists($notifiedFile)) {
    $notifiedProducts = json_decode(file_get_contents($notifiedFile), true);
}

if (!is_array($notifiedProducts)) {
    $notifiedProducts = [];
}

echo "Number of products found: " . $result->num_rows . "<br>"; 

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productName = $row['productName'];
        $expiryDate = $row['expiryDate'];
        $uniqueProductKey = $productName . '_' . $expiryDate; 

        if (isset($notifiedProducts[$uniqueProductKey])) {
            echo "Email already sent for product: $productName with expiry date: $expiryDate.<br>";
            continue; 
        }

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'handebhagyashri6@gmail.com'; 
            $mail->Password = 'efsb esqy tnrq sluo'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('handebhagyashri6@gmail.com', 'Inventory');
            $mail->addAddress($owner_email);
            $mail->isHTML(true);

            $today = new DateTime();
         $expiryDT = new DateTime($expiryDate);
         $today->setTime(0, 0, 0);
         $expiryDT->setTime(0, 0, 0);
         $daysLeft = (int)(($expiryDT->getTimestamp() - $today->getTimestamp()) / 86400);

            // Set email subject and body based on days left
            if ($daysLeft > 0 && $daysLeft <= 4) {
                $mail->Subject = "Product Expiry Alert";
                $mail->Body = "<h1>Expiry Alert</h1>
                              <p>The product <strong>$productName</strong> expires in <strong>$daysLeft day(s)</strong>. Expiry Date: <strong>$expiryDate</strong>.</p>";
                $mail->AltBody = "The product '$productName' expires in $daysLeft day(s). Expiry Date: $expiryDate.";
            } elseif ($daysLeft == 0) {
                $mail->Subject = "Product Expiry Alert";
                $mail->Body = "<h1>Expiry Alert</h1>
                              <p>The product <strong>$productName</strong> expires <strong>today</strong>! Expiry Date: <strong>$expiryDate</strong>.</p>";
                $mail->AltBody = "The product '$productName' expires today! Expiry Date: $expiryDate.";
            } else {
                $mail->Subject = "Expired Product Alert";
                $mail->Body = "<h1>Expired Product Alert</h1>
                              <p>The product <strong>$productName</strong> has already expired. Expiry Date was: <strong>$expiryDate</strong>.</p>";
                $mail->AltBody = "The product '$productName' has already expired. Expiry Date was: $expiryDate.";
            }

            if ($mail->send()) {
                echo "Email sent successfully for product: $productName.<br>";

                // Mark this product-expiry pair as notified
                $notifiedProducts[$uniqueProductKey] = true;

                // Save the updated list to the JSON file
                file_put_contents($notifiedFile, json_encode($notifiedProducts));
            } else {
                echo "Failed to send email for product: $productName.<br>";
            }

        } catch (Exception $e) {
            echo "Mailer Error for product $productName: " . $mail->ErrorInfo . "<br>";
        }
    }
} else {
    echo "No products found expiring within or exactly in 4 days.";
}

$conn->close();

?>