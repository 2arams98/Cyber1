<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cybersecurity_training";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    parse_str(file_get_contents("php://input"), $params);
    $id = isset($params['id']) ? intval($params['id']) : null;

    if ($id === null) {
        echo "Error: No ID provided.";
        http_response_code(400);
        exit;
    }

    // Fetch file path to delete the file from the server
    $sql = "SELECT file_path FROM materials WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        http_response_code(500);
        exit;
    }

    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        http_response_code(500);
        exit;
    }

    $stmt->bind_result($filePath);
    $stmt->fetch();
    $stmt->close();

    if (!$filePath) {
        echo "Error: Material not found.";
        http_response_code(404);
        exit;
    }

    // Delete the file from the server
    if (file_exists("../uploads/" . $filePath)) {
        if (!unlink("../uploads/" . $filePath)) {
            echo "Error: Unable to delete file.";
            http_response_code(500);
            exit;
        }
    } else {
        echo "Error: File not found.";
        http_response_code(404);
        exit;
    }

    // Delete the entry from the database
    $sql = "DELETE FROM materials WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        http_response_code(500);
        exit;
    }

    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        http_response_code(500);
        exit;
    }

    echo "success";
    http_response_code(200);

    $stmt->close();
}

$conn->close();
?>
