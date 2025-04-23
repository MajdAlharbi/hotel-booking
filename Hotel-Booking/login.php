<?php
session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "hotel_booking";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("❌ Connection failed.");
}

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    if (password_verify($password, $row['password'])) {
        $_SESSION['email'] = $email;

        if (str_starts_with($email, 'adm')) {
            echo "success:admin";
         } else {
            echo "success:user";
         }
         
    } else {
        echo "Wrong password.";
    }
} else {
    echo "Email not found.";
}

$stmt->close();
$conn->close();
?>
