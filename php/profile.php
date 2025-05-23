<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'connect.php';

$user_id = $_SESSION['user_id'];
$update_success = '';
$update_error = '';

// Получение данных пользователя
$stmt = $conn->prepare("SELECT username, email FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Обновление профиля
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $new_username = $_POST['username'] ?? '';
    $new_email = $_POST['email'] ?? '';

    if (!empty($new_username) && !empty($new_email)) {
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE user_id = ?");
        $stmt->bind_param("ssi", $new_username, $new_email, $user_id);
        if ($stmt->execute()) {
            $update_success = "Профиль успешно обновлён!";
            $_SESSION['username'] = $new_username;
            $user['username'] = $new_username;
            $user['email'] = $new_email;
        } else {
            $update_error = "Ошибка при обновлении: " . $conn->error;
        }
        $stmt->close();
    } else {
        $update_error = "Все поля обязательны!";
    }
}

// Получение избранных курсов
$favorites = [];
$stmt = $conn->prepare("SELECT f.course_id, c.title, c.description, c.price FROM favorites f JOIN courses c ON f.course_id = c.course_id WHERE f.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $favorites[] = $row;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Poppins:wght@700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <nav class="nav">
            <ul class="nav-list">
                <li><a href="../index.html" class="nav-link">Вернуться на главную</a></li>
                <li><a href="logout.php" class="nav-link">Выход</a></li>
                <li class="theme-toggle">
                    <button id="theme-toggle-btn" class="btn-theme">Темная тема</button>
                </li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="section profile-section">
            <h2 class="profile-title">Твой личный кабинет</h2>
            <div class="profile-card">
                <div class="profile-header">
                    <h3>Привет, <?php echo htmlspecialchars($user['username']); ?>! 🚀</h3>
                    <p>Управляй своим профилем и следи за избранным.</p>
                </div>
                <div class="profile-details">
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                </div>
                <h3 class="section-subtitle">Редактировать профиль</h3>
                <?php if ($update_success): ?>
                    <p class="success-message"><?php echo $update_success; ?></p>
                <?php elseif ($update_error): ?>
                    <p class="error-message"><?php echo $update_error; ?></p>
                <?php endif; ?>
                <form method="post" class="profile-form">
                    <input type="hidden" name="update_profile" value="1">
                    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required placeholder="Имя пользователя">
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required placeholder="Email">
                    <button type="submit" class="btn btn-primary btn-update">Сохранить изменения</button>
                </form>
            </div>

            <div class="favorites-section">
                <h3 class="section-subtitle">Избранные курсы 🌟</h3>
                <?php if (empty($favorites)): ?>
                    <p class="empty-message">Ты пока не добавил курсы в избранное. Перейди в раздел "Курсы" и выбери что-то интересное!</p>
                <?php else: ?>
                    <div class="favorites-grid" id="favorites-grid">
                        <?php foreach ($favorites as $course): ?>
                            <div class="favorite-card" data-id="<?php echo $course['course_id']; ?>">
                                <h4><?php echo htmlspecialchars($course['title']); ?></h4>
                                <p><?php echo htmlspecialchars($course['description']); ?></p>
                                <p><strong>Цена:</strong> <?php echo htmlspecialchars($course['price']); ?> руб.</p>
                                <button class="btn btn-remove" data-id="<?php echo $course['course_id']; ?>">Удалить</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <footer class="footer">
        <p>© 2025 Учебное заведение. Все права защищены.</p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/script.js"></script>
</body>
</html>