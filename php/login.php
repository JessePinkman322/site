<?php
ob_start();
session_start();
include 'connect.php';

$login_error = '';
$register_success = '';
$register_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] === 'login') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!empty($username) && !empty($password)) {
            $stmt = $conn->prepare("SELECT user_id, username, password FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['username'] = $row['username'];
                    header("Location: ../php/profile.php");
                    exit();
                } else {
                    $login_error = "Неверный пароль!";
                }
            } else {
                $login_error = "Пользователь не найден!";
            }
            $stmt->close();
        } else {
            $login_error = "Все поля обязательны!";
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'register') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $email = $_POST['email'] ?? '';

        if (!empty($username) && !empty($password) && !empty($email)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashed_password, $email);

            if ($stmt->execute()) {
                $register_success = "Регистрация успешна! Теперь вы можете войти.";
            } else {
                $register_error = "Ошибка при регистрации: " . $conn->error;
            }
            $stmt->close();
        } else {
            $register_error = "Все поля обязательны для заполнения!";
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход и регистрация</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Poppins:wght@700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <nav class="nav">
            <ul class="nav-list">
                <li><a href="../index.html" class="nav-link">Вернуться на главную</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="section login-section">
            <h2>Вход</h2>
            <?php if ($login_error): ?>
                <p style="color: #EF4444; font-weight: 600; margin-bottom: 1rem;"><?php echo $login_error; ?></p>
            <?php endif; ?>
            <form method="post" class="login-form">
                <input type="hidden" name="action" value="login">
                <input type="text" name="username" required placeholder="Имя пользователя">
                <input type="password" name="password" required placeholder="Пароль">
                <button type="submit" class="btn btn-primary">Войти</button>
            </form>

            <h2 style="margin-top: 2rem;">Регистрация</h2>
            <?php if ($register_success): ?>
                <p style="color: #1E3A8A; font-weight: 600; margin-bottom: 1rem;"><?php echo $register_success; ?></p>
            <?php elseif ($register_error): ?>
                <p style="color: #EF4444; font-weight: 600; margin-bottom: 1rem;"><?php echo $register_error; ?></p>
            <?php endif; ?>
            <form method="post" class="login-form">
                <input type="hidden" name="action" value="register">
                <input type="text" name="username" required placeholder="Имя пользователя">
                <input type="password" name="password" required placeholder="Пароль">
                <input type="email" name="email" required placeholder="Email">
                <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
            </form>
        </section>
    </main>
    <footer class="footer">
        <p>© 2025 Учебное заведение. Все права защищены.</p>
    </footer>
</body>
</html>
<?php ob_end_flush(); ?>