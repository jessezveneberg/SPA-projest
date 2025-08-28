<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'database.php';
    connectDB();

    $VehicleID = $_POST['VehicleID'];

    // Update Vehicles table
    $Make = $_POST['Make'];
    $Model = $_POST['Model'];
    $Year = $_POST['Year'];
    $RegistrationNumber = $_POST['RegistrationNumber'];
    $VehicleStatus = $_POST['VehicleStatus'];
    $InitialCost = $_POST['InitialCost']; // Додано
    $UsefulLife = $_POST['UsefulLife']; // Додано
    $sql1 = "UPDATE Vehicles SET Make='$Make', Model='$Model', Year='$Year', RegistrationNumber='$RegistrationNumber', VehicleStatus='$VehicleStatus', InitialCost='$InitialCost', UsefulLife='$UsefulLife' WHERE VehicleID='$VehicleID'";

    // Update Maintenance table
    $MaintenanceDate = $_POST['MaintenanceDate'];
    $MaintenanceDescription = $_POST['MaintenanceDescription'];
    $MaintenanceCost = $_POST['MaintenanceCost'];
    $sql2 = "UPDATE Maintenance SET Date='$MaintenanceDate', Description='$MaintenanceDescription', Cost='$MaintenanceCost' WHERE VehicleID='$VehicleID'";

    // Update Drivers table
    $DriverFirstName = $_POST['DriverFirstName'];
    $DriverLastName = $_POST['DriverLastName'];
    $sql3 = "UPDATE Drivers SET FirstName='$DriverFirstName', LastName='$DriverLastName' WHERE DriverID=(SELECT DriverID FROM VehicleDrivers WHERE VehicleID='$VehicleID')";

    // Update Fuel table
    $FuelLiters = $_POST['FuelLiters'];
    $FuelCostPerLiter = $_POST['FuelCostPerLiter'];
    $sql4 = "UPDATE Fuel SET Liters='$FuelLiters', CostPerLiter='$FuelCostPerLiter' WHERE VehicleID='$VehicleID'";

    // Update Insurance table
    $InsurancePolicyNumber = $_POST['InsurancePolicyNumber'];
    $InsuranceStartDate = $_POST['InsuranceStartDate'];
    $InsuranceEndDate = $_POST['InsuranceEndDate'];
    $InsuranceCost = $_POST['InsuranceCost'];
    $InsuranceCompany = $_POST['InsuranceCompany'];
    $sql5 = "UPDATE Insurance SET PolicyNumber='$InsurancePolicyNumber', StartDate='$InsuranceStartDate', EndDate='$InsuranceEndDate', Cost='$InsuranceCost', Company='$InsuranceCompany' WHERE VehicleID='$VehicleID'";

    // Execute SQL queries
    $result1 = $conn->query($sql1);
    $result2 = $conn->query($sql2);
    $result3 = $conn->query($sql3);
    $result4 = $conn->query($sql4);
    $result5 = $conn->query($sql5);

    // Check query results
    if ($result1 === TRUE && $result2 === TRUE && $result3 === TRUE && $result4 === TRUE && $result5 === TRUE) {
        echo "Запис успішно оновлено";
    } else {
        echo "Помилка оновлення: " . $conn->error;
    }
}
?>
