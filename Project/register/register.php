<?php
require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cybersecurity_training";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $role = $_POST['role'];
    $date_of_birth = $_POST['date_of_birth'];
    $index_no = $_POST['index_no'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $confirmation_code = md5(uniqid(rand(), true));

    // Check if email already exists
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Error: Email already registered. Please use a different email.";
    } else {
        $stmt->close();

        $sql = "INSERT INTO users (first_name, last_name, role, date_of_birth, index_no, email, password, confirmation_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $first_name, $last_name, $role, $date_of_birth, $index_no, $email, $password, $confirmation_code);

        if ($stmt->execute()) {
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'asrashmika@gmail.com';  // Your Gmail address
                $mail->Password = 'czst wdih pcvd zubj';      // Your App Password or Gmail password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                //Recipients
                $mail->setFrom('cybershieldacademy2024@gmail.com', 'CyberShield Academy');
                $mail->addAddress($email);

                //Content
                $mail->isHTML(true);
                $mail->Subject = 'Email Confirmation';
                $mail->Body    = "Please click the link below to confirm your email address:<br><a href='http://localhost/Project/register/confirm.php?code=$confirmation_code'>Confirm Email</a>";

                $mail->send();
                echo 'Registration successful! Please check your email to confirm your account.';
            } catch (Exception $e) {
                echo "Error sending confirmation email: {$mail->ErrorInfo}";
            }
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $stmt->close();
    $conn->close();
}
?>
