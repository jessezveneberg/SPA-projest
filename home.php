<?php
session_start(); // Начинаем сессию

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION["UserID"])) {
    // Если пользователь не авторизован, перенаправляем его на страницу авторизации
    header("Location: authorization.html");
    exit();
}

// Получаем данные пользователя из сессии
$userID = $_SESSION["UserID"];

$firstName = $_SESSION["FirstName"];
$lastName = $_SESSION["LastName"];
$email = $_SESSION["Email"];
$role = $_SESSION["Role"]; // Добавляем роль пользователя из сессии

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .menu {
            width: 100%;
            background-color: #333;
            overflow: hidden;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }
        .menu a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }
        .menu a:hover {
            background-color: #575757;
        }
        .container {
            padding-top: 60px; /* Отступ, чтобы контент не перекрывался меню */
            text-align: center;
        }
        .welcome-message {
            margin-top: 20px;
        }
        .logout {
            position: absolute;
            top: 14px;
            right: 20px;
        }
    </style>
</head>
<body>
<div class="menu">
        <a href="#" onclick="loadContent('home.php')">Головна</a>
        <a href="#" onclick="loadContent('profile.php')">Профіль</a>
        <a href="#" onclick="loadContent('transport.php')">Транспорт</a>
        <a href="#" onclick="loadContent('amortization.php')">Розрахунок амортизації</a>
        <a href="#" onclick="logout()" class="logout">Вихід</a>
    </div>

    <div class="container" id="content">
        <div class="welcome-message">
            <h1>Ласкаво просимо, <?php echo $firstName . ' ' . $lastName; ?>!</h1>
            <p>Ваш email: <?php echo $email; ?></p>
        </div>
    </div>
    <script>
    function loadContent(url) {
    fetch(url)
    .then(response => {
        if (!response.ok) {
            throw new Error('Помилка');
        }
        return response.text();
    })
    .then(data => {
        document.getElementById('content').innerHTML = data;
        // Изменяем URL без перезагрузки страницы
        history.pushState(null, '', url);
    })
    .catch(error => {
        console.error('Помилка:', error);
    });
}


        function logout() {
            fetch('logout.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Помилка');
                }
                // Redirect to the login page after logout
                window.location.href = 'index.php';
            })
            .catch(error => {
                console.error('Помилка:', error);
            });
        }

        // Load the home page content by default
        window.onload = function() {
            loadContent('home.php');
        };
    </script>
</body>
</html>
