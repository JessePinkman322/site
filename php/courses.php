<?php
session_start();
include 'connect.php';

$add_success = '';
$add_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_favorites'])) {
    if (!isset($_SESSION['user_id'])) {
        $add_error = "Пожалуйста, войдите в аккаунт, чтобы добавить курс в избранное.";
    } else {
        $course_id = $_POST['course_id'];
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("INSERT IGNORE INTO favorites (user_id, course_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $course_id);
        if ($stmt->execute()) {
            $add_success = "Курс добавлен в избранное!";
        } else {
            $add_error = "Ошибка: " . $conn->error;
        }
        $stmt->close();
    }
}

$sql = "SELECT * FROM courses";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $image_index = 1;
    while($row = $result->fetch_assoc()) {
        echo "<div class='course-card'>";
        echo "<img src='img/course" . $image_index . ".jpg' alt='" . htmlspecialchars($row["title"]) . "'>";
        echo "<h3>" . htmlspecialchars($row["title"]) . "</h3>";
        echo "<p>" . htmlspecialchars($row["description"]) . "</p>";
        echo "<p><strong>Цена:</strong> " . htmlspecialchars($row["price"]) . " руб.</p>";
        echo "<p><strong>Длительность:</strong> 3 месяца</p>";
        echo "<p><strong>Формат:</strong> Онлайн</p>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='course_id' value='" . $row["course_id"] . "'>";
        echo "<input type='hidden' name='add_to_favorites' value='1'>";
        echo "<button type='submit' class='course-button'>Добавить в избранное</button>";
        echo "</form>";
        echo "</div>";
        $image_index = $image_index == 1 ? 2 : 1;
    }
}
$conn->close();

if ($add_success) {
    echo "<p class='success-message'>" . $add_success . "</p>";
}
if ($add_error) {
    echo "<p class='error-message'>" . $add_error . "</p>";
}
?>