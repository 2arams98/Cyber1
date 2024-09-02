<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cybersecurity_training";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$course_id = $_GET['course_id'];

$sql = "SELECT id, quiz_name, quiz_description FROM quizzes WHERE course_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();

$quizzes = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $quizzes[] = $row;
    }
}

$stmt->close();
$conn->close();
echo json_encode($quizzes);
?>
