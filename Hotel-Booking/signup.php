<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "hotel_booking";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("❌ Database connection failed.");
}

$fullname = trim($_POST['fullname']);
$email = trim($_POST['email']);
$password = $_POST['password'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "❌ Invalid email format.";
    exit;
}

if (strlen($password) < 6) {
    echo "❌ Password must be at least 6 characters.";
    exit;
}

$check = $conn->prepare("SELECT email FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "❌ This email is already registered.";
    $check->close();
    $conn->close();
    exit;
}
$check->close();

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $fullname, $email, $hashed_password);

if ($stmt->execute()) {
    echo "Success! Your account has been created.";
} else {
    echo "❌ Failed to register. Try again.";
}

$stmt->close();
$conn->close();
?>
