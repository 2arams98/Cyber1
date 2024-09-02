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
    $course_id = $_POST['course_id'];
    $quiz_name = $_POST['quiz_name'];
    $quiz_description = $_POST['quiz_description'];

    $sql = "INSERT INTO quizzes (course_id, quiz_name, quiz_description) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $course_id, $quiz_name, $quiz_description);

    if ($stmt->execute()) {
        echo "Quiz created successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
