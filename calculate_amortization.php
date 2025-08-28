<?php
// Перевірка, чи були передані необхідні дані через POST-запит
if (isset($_POST['method'], $_POST['vehicle_id'])) {
    // Підключення до бази даних
    require_once 'database.php';
    connectDB();

    // Отримання даних з форми
    $method = $_POST['method'];
    $vehicle_id = $_POST['vehicle_id'];

    // Отримання даних про вибраний транспортний засіб з бази даних
    $sql_vehicle = "SELECT InitialCost, UsefulLife FROM Vehicles WHERE VehicleID = ?";
    $stmt_vehicle = $conn->prepare($sql_vehicle);
    $stmt_vehicle->bind_param("i", $vehicle_id);
    $stmt_vehicle->execute();
    $result_vehicle = $stmt_vehicle->get_result();

    if ($result_vehicle->num_rows > 0) {
        $row_vehicle = $result_vehicle->fetch_assoc();
        $initial_cost = $row_vehicle['InitialCost'];
        $useful_life = $row_vehicle['UsefulLife'];

        // Розрахунок амортизації в залежності від вибраного методу
        $depreciation_rate = 0;
        switch ($method) {
            case 'linear':
                if ($useful_life != 0) {
                    $depreciation_rate = $initial_cost / $useful_life;
                }
                break;
            case 'double_declining':
                // Розрахунок амортизації для методу подвійного зменшення
                $depreciation_rate = (2 / $useful_life) * $initial_cost;
                break;
            default:
                $depreciation_rate = 0;
        }

        // Вставка результату розрахунку амортизації в таблицю Depreciation
        $sql_insert = "INSERT INTO Depreciation (VehicleID, DepreciationDate, DepreciationAmount) VALUES (?, NOW(), ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("id", $vehicle_id, $depreciation_rate);
        $stmt_insert->execute();

        if ($stmt_insert->affected_rows > 0) {
            echo "Сума амортизації: $depreciation_rate грн.";
        } else {
            echo "Помилка: Неможливо вставити результат амортизації в базу даних.";
        }

        // Закриття підготовлених запитів та з'єднання з базою даних
        $stmt_insert->close();
    } else {
        echo "Помилка: Автомобіль з таким ID не знайдено.";
    }

    $stmt_vehicle->close();
    $conn->close();
} else {
    echo "Помилка: Недостатньо даних для розрахунку амортизації.";
}
?>
