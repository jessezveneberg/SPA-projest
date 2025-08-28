<?php
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION["UserID"])) {
    header("Location: authorization.html");
    exit();
}

// Подключаемся к базе данных
require_once 'database.php';
connectDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем данные из формы
    $vehicleID = $_POST['vehicle_id'];
    $date = $_POST['date'];
    $liters = $_POST['liters'];
    $costPerLiter = $_POST['cost_per_liter'];

    // Подготавливаем SQL запрос для вставки данных о заправке машины
    $sql = "INSERT INTO Fuel (VehicleID, Date, Liters, CostPerLiter) VALUES ('$vehicleID', '$date', '$liters', '$costPerLiter')";

    // Выполняем запрос
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Авто успішно заправлено!'); window.location.href='transport.php';</script>";
    } else {
        echo "Ошибка: " . $sql . "<br>" . $conn->error;
    }
}
?>
