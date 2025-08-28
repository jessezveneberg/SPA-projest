<?php
require_once 'database.php';
connectDB();

$vehicleID = $_POST['vehicle_id'];
$description = $_POST['description'];
$image = $_FILES['image'];

// Зберігаємо файл на сервері
$targetDir = "img/";
$targetFile = $targetDir . basename($image["name"]);
move_uploaded_file($image["tmp_name"], $targetFile);

$sql = "INSERT INTO VehicleImages (VehicleID, ImagePath, Description) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $vehicleID, $targetFile, $description);

if ($stmt->execute()) {
    echo "<script>alert('Зображення завантажено успішно!'); window.location.href='transport.php';</script>";
} else {
    echo "<script>alert('Помилка: " . $conn->error . "'); window.location.href='transport.php';</script>";
}

$stmt->close();
$conn->close();
?>
