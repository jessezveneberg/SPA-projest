<?php
require_once 'database.php';
connectDB();

$vehicleID = $_POST['vehicle_id'];
$depreciationDate = $_POST['depreciation_date'];
$depreciationAmount = $_POST['depreciation_amount'];

$sql = "INSERT INTO Depreciation (VehicleID, DepreciationDate, DepreciationAmount) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isd", $vehicleID, $depreciationDate, $depreciationAmount);

if ($stmt->execute()) {
    echo "<script>alert('Амортизацію успішно встановлено!'); window.location.href='transport.php';</script>";
} else {
    echo "<script>alert('Помилка: " . $conn->error . "'); window.location.href='transport.php';</script>";
}

$stmt->close();
$conn->close();
?>
