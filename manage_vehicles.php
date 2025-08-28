<?php
require_once 'database.php';
connectDB();

$make = $_POST['make'];
$model = $_POST['model'];
$year = $_POST['year'];
$registrationNumber = $_POST['registration_number'];
$status = $_POST['vehicle_status'];
$initialCost = $_POST['initial_cost'];
$usefulLife = $_POST['useful_life'];

$sql = "INSERT INTO Vehicles (Make, Model, Year, RegistrationNumber, VehicleStatus, InitialCost, UsefulLife) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssissi", $make, $model, $year, $registrationNumber, $status, $initialCost, $usefulLife);

if ($stmt->execute()) {
    echo "<script>alert('Транспортний засіб додано успішно!'); window.location.href='transport.php';</script>";
} else {
    echo "<script>alert('Помилка: " . $stmt->error . "'); window.location.href='transport.php';</script>";
}

$stmt->close();
$conn->close();
?>
