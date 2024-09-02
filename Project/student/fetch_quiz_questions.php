<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cybersecurity_training";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$quiz_id = $_GET['quiz_id'];

$sql = "SELECT id, question_text FROM questions WHERE quiz_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$result = $stmt->get_result();

$questions = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $question_id = $row['id'];
        $sql_answers = "SELECT id, answer_text FROM answers WHERE question_id = ?";
        $stmt_answers = $conn->prepare($sql_answers);
        $stmt_answers->bind_param("i", $question_id);
        $stmt_answers->execute();
        $result_answers = $stmt_answers->get_result();

        $answers = array();
        if ($result_answers->num_rows > 0) {
            while($row_answers = $result_answers->fetch_assoc()) {
                $answers[] = $row_answers;
            }
        }
        $stmt_answers->close();

        $row['answers'] = $answers;
        $questions[] = $row;
    }
}

$stmt->close();
$conn->close();
echo json_encode($questions);
?>
