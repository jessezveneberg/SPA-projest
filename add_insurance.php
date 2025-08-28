<?php
session_start();
if (!isset($_SESSION["UserID"])) {
    header("Location: authorization.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'database.php';
    connectDB();

    // Получаем данные из формы
    $vehicleID = $_POST['vehicle_id'];
    $policyNumber = $_POST['policy_number'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $cost = $_POST['cost'];
    $company = $_POST['company'];
    if ($endDate < $startDate) {
        echo "<script>alert('Ізакінчення страхування не може бути меншою ніж дата початку!'); window.location.href='transport.php';</script>";

 
        exit();
    }

    // SQL запрос для вставки данных страховки в базу данных
    $query = "INSERT INTO Insurance (VehicleID, PolicyNumber, StartDate, EndDate, Cost, Company)
              VALUES ('$vehicleID', '$policyNumber', '$startDate', '$endDate', '$cost', '$company')";

    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Інформацію про страхування успішно додано!'); window.location.href='transport.php';</script>";
    } else {
        echo "Помилка: " . $conn->error;
    }

    $conn->close();
} else {
    // Если форма не отправлена методом POST, перенаправляем на главную страницу
    header("Location: home.php");
    exit();
}
?>
