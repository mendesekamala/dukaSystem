<?php
// Database configuration
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

// SQL query to fetch transactions
$sql = "SELECT id, transaction_type, amount, date_made, description FROM transactions ";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["transaction_type"] . "</td>";
        echo "<td>" . $row["amount"] . "</td>";
        echo "<td>" . $row["description"] . "</td>";
        echo "<td>" . $row["date_made"] . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No trannsactions found found</td></tr>";
}

// Close the connection
$conn->close();
?>
