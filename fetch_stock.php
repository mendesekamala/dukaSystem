<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "product_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT product_name, product_nickname, date_bought, buying_price, selling_price, quantity FROM products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["product_name"] . "</td>";
        echo "<td>" . $row["date_bought"] . "</td>";
        echo "<td>" . $row["buying_price"] . "</td>";
        echo "<td>" . $row["selling_price"] . "</td>";
        echo "<td>" . $row["quantity"] . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No products found</td></tr>";
}

$conn->close();
?>
