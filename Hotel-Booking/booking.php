<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    echo "❌ You must be logged in to make a booking.";
    exit();
}

// Connect to the database
$host = 'localhost';
$dbname = 'hotel_booking';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}

// Collect booking data
$user_email = $_SESSION['email'];
$room_id = $_POST['room_id'] ?? '';
$arrival_date = $_POST['arrival'] ?? '';
$departure_date = $_POST['departure'] ?? '';
$guests = $_POST['guests'] ?? '';

// Validate input
if (empty($room_id) || empty($arrival_date) || empty($departure_date) || empty($guests)) {
    echo "❌ Please fill in all required fields.";
    exit();
}

if ($arrival_date >= $departure_date) {
    echo "❌ Arrival date must be before departure date.";
    exit();
}

if (!is_numeric($guests) || $guests < 1) {
    echo "❌ Number of guests must be at least 1.";
    exit();
}

// Insert into bookings table
try {
    $stmt = $pdo->prepare("
        INSERT INTO bookings (user_email, room_id, arrival_date, departure_date, guests)
        VALUES (:user_email, :room_id, :arrival, :departure, :guests)
    ");

    $stmt->execute([
        ':user_email' => $user_email,
        ':room_id' => $room_id,
        ':arrival' => $arrival_date,
        ':departure' => $departure_date,
        ':guests' => $guests
    ]);

    echo "✅ Booking confirmed!";
} catch (PDOException $e) {
    echo "❌ Failed to book: " . $e->getMessage();
}
?>
