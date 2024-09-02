<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cybersecurity_training";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$student_id = $_SESSION['user_id']; // assuming student_id is stored in the session
$name = $_POST['name'];
$index_no = $_POST['index_no'];
$quiz_id = $_POST['quiz_id'];
$file = $_FILES['file'];

// Ensure the uploads directory exists
$uploadDir = "../uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$fileName = time() . '_' . basename($file['name']);
$targetFilePath = $uploadDir . $fileName;

if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
    $sql = "INSERT INTO quiz_submissions (student_id, quiz_id, file_path) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $student_id, $quiz_id, $fileName);

    if ($stmt->execute()) {
        echo "Quiz submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Error uploading file.";
}

$conn->close();
?>
