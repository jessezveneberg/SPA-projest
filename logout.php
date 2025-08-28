<?php
session_start(); // Начинаем сессию

// Уничтожаем сессию
session_unset();
session_destroy();

// Перенаправляем пользователя на страницу авторизации
header("Location: authorization.html");
exit();
?>
