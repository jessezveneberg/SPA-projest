<?php
session_start(); // Начинаем сессию
require_once "database.php"; // Подключение к базе данных
 connectDB(); // Подключение к базе данных и получение объекта соединения

// Получение данных из формы регистрации
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$firstName = $_POST['firstName'] ?? '';
$lastName = $_POST['lastName'] ?? '';
$email = $_POST['email'] ?? '';
$role = $_POST['role'] ?? 'user'; // Отримання значення ролі з форми (якщо не передано, встановлюємо роль за замовчуванням 'user')

// Проверка наличия данных
if(empty($username) || empty($password) || empty($firstName) || empty($lastName) || empty($email)) {
    echo json_encode(array("success" => false, "message" => "All fields are required."));
    exit;
}

// Хеширование пароля (рекомендуется использовать более безопасные методы хеширования)
$passwordHash = password_hash($password, PASSWORD_DEFAULT);


$sql = "INSERT INTO Users (Username, PasswordHash, FirstName, LastName, Email, CreatedAt, Role) 
        VALUES (?, ?, ?, ?, ?, NOW(), ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $username, $passwordHash, $firstName, $lastName, $email, $role);


if ($stmt->execute()) {
    // Отправляем ответ об успешной регистрации
    echo "<script>alert('Успішна реєстрація!');</script>";
    header("Location: home.php");
    exit();
} else {
    // Отправляем сообщение об ошибке, если запрос не удался
    echo json_encode(array("success" => false, "message" => "Error: " . $sql . "<br>" . $conn->error));
}

// Закрытие соединения с базой данных
$stmt->close();
$conn->close();
?>
