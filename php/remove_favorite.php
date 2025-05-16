<?php
session_start();
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Пользователь не авторизован.';
} else {
    include 'connect.php';
    $course_id = $_POST['id'] ?? 0;

    if ($course_id > 0) {
        $stmt = $conn->prepare("DELETE FROM favorites WHERE course_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $course_id, $_SESSION['user_id']);
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Курс удалён из избранного.';
        } else {
            $response['message'] = 'Ошибка при удалении: ' . $conn->error;
        }
        $stmt->close();
    } else {
        $response['message'] = 'Неверный ID.';
    }
    $conn->close();
}

echo json_encode($response);
?>