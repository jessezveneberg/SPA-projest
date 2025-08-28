<?php
require_once 'database.php';
connectDB();

$vehicleID = $_POST['vehicle_id'];
$driverID = $_POST['driver_id'];

$sql = "INSERT INTO VehicleDrivers (VehicleID, DriverID) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $vehicleID, $driverID);

if ($stmt->execute()) {
    echo "<script>alert('Водія призначено успішно!'); window.location.href='transport.php';</script>";
} else {
    echo "<script>alert('Помилка: " . $conn->error . "'); window.location.href='transport.php';</script>";
}

$stmt->close();
$conn->close();
?>
