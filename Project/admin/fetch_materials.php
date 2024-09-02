<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cybersecurity_training";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT materials.id, materials.type, materials.file_path, courses.name as course_name FROM materials JOIN courses ON materials.course_id = courses.id";
$result = $conn->query($sql);

$materials = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $materials[] = $row;
    }
}

$conn->close();
echo json_encode($materials);
?>
