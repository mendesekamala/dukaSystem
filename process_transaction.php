<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "product_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$transaction_type = $_POST['transaction_type'];
$amount = $_POST['amount'];
$description = $_POST['description'];
$date_made = date('Y-m-d H:i:s');

// Insert transaction into transactions table
$sql = "INSERT INTO transactions (transaction_type, amount, date_made, description)
VALUES ('$transaction_type', '$amount', '$date_made', '$description')";

if ($conn->query($sql) === TRUE) {
    // Update cash in liquidity table
    if ($transaction_type == 'expenses' || $transaction_type == 'drawings') {
        $update_sql = "UPDATE liquidity SET cash = cash - $amount LIMIT 1";
    } elseif ($transaction_type == 'add_capital') {
        $update_sql = "UPDATE liquidity SET cash = cash + $amount LIMIT 1";
    }

    if ($conn->query($update_sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}

$conn->close();
?>
