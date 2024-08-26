<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "product_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT cash FROM liquidity LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['cash' => $row['cash']]);
} else {
    echo json_encode(['cash' => 0]);
}

$conn->close();
?>
