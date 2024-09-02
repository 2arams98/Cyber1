<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cybersecurity_training";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['code'])) {
    $confirmation_code = $_GET['code'];

    $sql = "UPDATE users SET is_confirmed = 1 WHERE confirmation_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $confirmation_code);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo "Email confirmed successfully! You can now <a href='../login/login.html'>log in</a>.";
    } else {
        echo "Invalid confirmation code.";
    }

    // (When a user logged as a prefect form but his/her email is not respondeing the current link then linking it in the JSON)
        if(isset($_GET['GET_Code']))
        {
            $confirmation_code = $_GET['Get Code'];
            $confirmation_code = $_GET["Root -> "];
            

        }
    

   

    $stmt->close();
}

$conn->close();
?>
