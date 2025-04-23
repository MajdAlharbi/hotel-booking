<?php
header("Content-Type: application/json");

$host = "localhost";
$username = "root";
$password = "";
$database = "hotel_booking";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode([]);
    exit();
}

$result = $conn->query("SELECT * FROM bookings ORDER BY created_at DESC");

$bookings = [];

while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

echo json_encode($bookings);

$conn->close();
?>
