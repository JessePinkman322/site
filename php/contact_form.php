<?php
include 'connect.php';
$message_sent = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $_POST['message'];
    $user_id = 1; // Заглушка, заменить на ID пользователя
    $sql = "INSERT INTO contacts (user_id, message) VALUES ('$user_id', '$message')";
    if ($conn->query($sql) === TRUE) {
        $message_sent = true;
    } else {
        echo "<p style='color: red;'>Ошибка: " . $conn->error . "</p>";
    }
}
$conn->close();
?>
<?php if ($message_sent): ?>
    <p style="color: #1E3A8A; font-weight: 600; margin-bottom: 1rem;">Сообщение успешно отправлено! Мы свяжемся с вами в ближайшее время.</p>
<?php endif; ?>
<form method="post">
    <textarea name="message" required placeholder="Ваше сообщение"></textarea>
    <button type="submit" class="btn btn-primary">Отправить</button>
</form>