<?php
require_once "database.php";
connectDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicleID = $_POST["vehicleID"];
    $make = $_POST["Make"];
    $model = $_POST["Model"];
    $year = $_POST["Year"];
    $registrationNumber = $_POST["RegistrationNumber"];
    $vehicleStatus = $_POST["VehicleStatus"];
    $date = $_POST["Date"];
    $description = $_POST["Description"];
    $cost = $_POST["Cost"];
    $driverFirstName = $_POST["DriverFirstName"];
    $driverLastName = $_POST["DriverLastName"];
    $imagePath = $_POST["ImagePath"];
    $fuelLiters = $_POST["FuelLiters"];
    $fuelCostPerLiter = $_POST["FuelCostPerLiter"];
    $insurancePolicyNumber = $_POST["InsurancePolicyNumber"];
    $insuranceStartDate = $_POST["InsuranceStartDate"];
    $insuranceEndDate = $_POST["InsuranceEndDate"];
    $insuranceCost = $_POST["InsuranceCost"];
    $insuranceCompany = $_POST["InsuranceCompany"];

    $query = "UPDATE Vehicles SET 
        Make = '$make', 
        Model = '$model', 
        Year = '$year', 
        RegistrationNumber = '$registrationNumber', 
        VehicleStatus = '$vehicleStatus', 
        Date = '$date', 
        Description = '$description', 
        Cost = '$cost'
        WHERE VehicleID = '$vehicleID'";

    if ($conn->query($query) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
} else {
    echo "Invalid request";
}
?>
