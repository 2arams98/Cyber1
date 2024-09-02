<?php
session_start();

if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'Student') {
    $student_details = [
        'first_name' => $_SESSION['first_name'],
        'last_name' => $_SESSION['last_name'],
        'index_no' => $_SESSION['index_no'],
        'date_of_birth' => $_SESSION['date_of_birth']
    ];
    echo json_encode($student_details);
} else {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
}
?>
