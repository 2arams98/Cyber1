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
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
    $otp = $_POST['otp'];
    $email = $_POST['email'];

    $sql = "UPDATE users SET password = ?, confirmation_code = NULL WHERE confirmation_code = ? AND email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $new_password, $otp, $email);

    if ($stmt->execute()) {
        echo "Password reset successfully! You can now <a href='../login/login.html'>log in</a>.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
