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
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            if ($user['is_confirmed']) {
                if ($role == $user['role']) {
                    session_start();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['first_name'] = $user['first_name'];
                    $_SESSION['last_name'] = $user['last_name'];
                    $_SESSION['index_no'] = $user['index_no'];
                    $_SESSION['date_of_birth'] = $user['date_of_birth'];
                    $_SESSION['role'] = $user['role'];
                    
                    if ($role == "Admin") {
                        header("Location: ../admin/admin_dashboard.html");
                    } else {
                        header("Location: ../student/student_dashboard.html");
                    }
                    exit;
                } else {
                    echo "Role does not match.";
                }
            } else {
                echo "Please confirm your email before logging in.";
            }
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "No account found with that email.";
    }

    $stmt->close();
}

$conn->close();
?>
