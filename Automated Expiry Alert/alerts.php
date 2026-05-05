<!-- <?php -->
// Database connection
// $conn = new mysqli("localhost", "username", "password", "database_name");

// Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// // Get today's date and calculate upcoming expiry dates
// $current_date = date("Y-m-d");
// $query = "SELECT product_name, expiry_date FROM inventory 
//           WHERE expiry_date BETWEEN '$current_date' AND DATE_ADD('$current_date', INTERVAL 4 DAY)";

// $result = $conn->query($query);

// $notifications = [];
// if ($result->num_rows > 0) {
//     while ($row = $result->fetch_assoc()) {
//         $days_left = (new DateTime($row['expiry_date']))->diff(new DateTime($current_date))->days;
//         $notifications[] = [
//             'product_name' => $row['product_name'],
//             'expiry_date' => $row['expiry_date'],
//             'days_left' => $days_left
//         ];
//     }
// }

// // Send notifications as JSON
// echo json_encode($notifications);
// $conn->close();
// ?>
