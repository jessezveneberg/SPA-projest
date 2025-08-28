<?php
// Подключение к базе данных
include "database.php";
connectDB();

// SQL запрос для выборки данных о машинах и их изображениях
$sql = "SELECT Vehicles.Make, Vehicles.Model, Vehicles.Year, Vehicles.RegistrationNumber, VehicleImages.ImagePath 
        FROM Vehicles
        JOIN VehicleImages ON Vehicles.VehicleID = VehicleImages.VehicleID";

// Выполнение запроса
$result = $conn->query($sql);

// Массив для хранения данных о машинах и их изображениях
$vehicles = array();

if ($result->num_rows > 0) {
    // Добавление данных о машинах и изображениях в массив
    while($row = $result->fetch_assoc()) {
        $vehicles[] = $row;
    }
} else {
    echo "0 results";
}

// Закрытие соединения с базой данных
$conn->close();

// Возвращение данных в формате JSON
echo json_encode($vehicles);
?>
