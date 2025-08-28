<?php
session_start(); // Начинаем сессию

require_once "database.php"; // Подключение к базе данных
connectDB(); // Подключение к базе данных и получение объекта соединения

// Получение данных из формы авторизации
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Проверка наличия данных
if(empty($username) || empty($password)) {
    echo json_encode(array("success" => false, "message" => "All fields are required."));
    exit;
}

// Поиск пользователя в базе данных
$sql = "SELECT * FROM users WHERE Username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Если пользователь найден, проверяем пароль
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['PasswordHash'])) {
        // Если пароль верный, устанавливаем данные пользователя в сессию
        $_SESSION["UserID"] = $user['UserID'];
        $_SESSION["FirstName"] = $user['FirstName'];
        $_SESSION["LastName"] = $user['LastName'];
        $_SESSION["Email"] = $user['Email'];
        $_SESSION["Role"] = $user['Role']; // Устанавливаем роль пользователя в сессию

        // Отправляем успешный JSON-ответ
        echo "<script>alert('Успішно!');</script>";
        header("Location: home.php");
        exit();
    } else {
        // Если пароль неверный, отправляем JSON-ответ с сообщением об ошибке
        echo json_encode(array("success" => false, "message" => "Incorrect password."));
        exit;
    }
} else {
    // Если пользователь не найден, отправляем JSON-ответ с сообщением об ошибке
    echo json_encode(array("success" => false, "message" => "User not found."));
    exit;
}

$stmt->close();
$conn->close();
?>
