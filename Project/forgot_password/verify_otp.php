<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cybersecurity_training";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp = $_POST['otp'];
    $email = $_POST['email'];

    $sql = "SELECT id FROM users WHERE confirmation_code = ? AND email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $otp, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>window.location.href = 'reset_password.html?email=$email&otp=$otp';</script>";
    } else {
        echo "Invalid OTP.";
    }

    $stmt->close();
    $conn->close();
}
?>
