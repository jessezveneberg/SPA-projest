<?php
// Подключение к базе данных
require_once 'database.php';
connectDB();

// Проверка наличия данных
if (isset($_POST['maintenanceID']) && isset($_POST['field']) && isset($_POST['value'])) {
    // Получение данных из запроса
    $maintenanceID = $_POST['maintenanceID'];
    $field = $_POST['field'];
    $value = $_POST['value'];

    // Проверка корректности полей
    $allowedFields = array('Date', 'Description', 'Cost');
    if (!in_array($field, $allowedFields)) {
        echo "Ошибка: Недопустимое поле.";
        exit();
    }

    // Подготовка запроса на обновление данных
    $query = "UPDATE Maintenance SET $field = ? WHERE MaintenanceID = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        echo "Ошибка при подготовке запроса: " . $conn->error;
        exit();
    }

    // Привязка параметров и выполнение запроса
    $stmt->bind_param('si', $value, $maintenanceID);
    $result = $stmt->execute();

    if ($result) {
        echo "Данные успешно обновлены.";
    } else {
        echo "Ошибка при обновлении данных: " . $conn->error;
    }

    // Закрытие запроса и соединения с базой данных
    $stmt->close();
    $conn->close();
} else {
    echo "Ошибка: Не удалось получить данные.";
}
?>
