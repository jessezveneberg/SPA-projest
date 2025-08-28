<?php
session_start();
if (!isset($_SESSION["UserID"])) {
    header("Location: authorization.html");
    exit();
}
// Assuming you have a session variable named $_SESSION["UserRole"] containing the user's role
$role = isset($_SESSION["Role"]) ? $_SESSION["Role"] : '';

require_once 'database.php';
connectDB();

// Fetch data with a join query
$query = "SELECT 
    Vehicles.VehicleID, 
    Vehicles.Make, 
    Vehicles.Model, 
    Vehicles.Year, 
    Vehicles.RegistrationNumber, 
    Vehicles.VehicleStatus, 
    Maintenance.Date AS MaintenanceDate, 
    Maintenance.Description AS MaintenanceDescription, 
    Maintenance.Cost AS MaintenanceCost, 
    Drivers.DriverID, 
    Drivers.FirstName AS DriverFirstName, 
    Drivers.LastName AS DriverLastName, 
    VehicleImages.ImagePath, 
    Fuel.Liters AS FuelLiters, 
    Fuel.CostPerLiter AS FuelCostPerLiter, 
    Insurance.PolicyNumber AS InsurancePolicyNumber, 
    Insurance.StartDate AS InsuranceStartDate, 
    Insurance.EndDate AS InsuranceEndDate, 
    Insurance.Cost AS InsuranceCost, 
    Insurance.Company AS InsuranceCompany,
     Vehicles.InitialCost,
    Vehicles.UsefulLife 
FROM 
    Vehicles 
LEFT JOIN Maintenance ON Vehicles.VehicleID = Maintenance.VehicleID 
LEFT JOIN VehicleDrivers ON Vehicles.VehicleID = VehicleDrivers.VehicleID 
LEFT JOIN Drivers ON VehicleDrivers.DriverID = Drivers.DriverID 
LEFT JOIN VehicleImages ON Vehicles.VehicleID = VehicleImages.VehicleID 
LEFT JOIN Fuel ON Vehicles.VehicleID = Fuel.VehicleID 
LEFT JOIN Insurance ON Vehicles.VehicleID = Insurance.VehicleID";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управління поточним транспортом</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
           body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .section {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: none;
            border-radius: 10px;
            width: 70%;
            max-width: 500px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .close {
            color: #aaa;
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #333;
            cursor: pointer;
        }

        .menu {
            width: 100%;
            background-color: #333;
            overflow: hidden;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .menu a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }

        .menu a:hover {
            background-color: #575757;
        }

        .container {
            padding-top: 60px;
            text-align: left;
            width: 80%;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        .form-group button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #0056b3;
        }

        .section {
    margin-top: 0;
    margin-right: 0;
    margin-bottom: 0;
    margin-left: 0;
    padding-top: 0;
    padding-right: 0;
    padding-bottom: 0;
    padding-left: 0;
}

        .logout {
            position: absolute;
            top: 14px;
            right: 20px;
        }

        .menu .manage-current-transport {
            position: absolute;
            top: 14px;
            right: 120px; /* Adjust this value if needed */
        }
    </style>
</head>
<body>
<script>
    function openEditModal(vehicleData) {
        for (const key in vehicleData) {
            if (vehicleData.hasOwnProperty(key)) {
                const element = document.getElementById(key);
                if (element) {
                    element.value = vehicleData[key];
                }
            }
        }
        document.getElementById('editModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    function submitForm(event) {
        event.preventDefault();
        const formData = $("#editForm").serialize();

        $.ajax({
            url: 'update_data.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                alert(response);
                location.reload();
            },
            error: function(xhr, status, error) {
                alert('Помилка при оновленні даних: ' + error);
            }
        });
    }

    function loadContent(url) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById('content').innerHTML = xhr.responseText;
                // Ensure that the menu bar is always visible after loading content
                document.getElementById('menu').style.display = 'block';
                // Close any open modals after loading content
                closeModal();
                // Update the address bar
                history.pushState(null, '', url);
            }
        };
        xhr.send();
    }
</script>
<div class="menu" id="menu">
        <a href="#" onclick="loadContent('home.php')">Головна</a>
        <a href="#" onclick="loadContent('profile.php')">Профіль</a>
        <a href="#" onclick="loadContent('transport.php')">Транспорт</a>
        <a href="#" onclick="loadContent('amortization.php')">Розрахунок амортизації</a>
        <a href="#" onclick="loadContent('manage_current_transport.php')" class="manage-current-transport">Управління поточним транспортом</a>
        <a href="logout.php" class="logout">Вихід</a>
    </div>
<div class="section">
    <br>
    <h2>Управління поточним транспортом</h2>
    <table>
        <thead>
            <tr>
                <th>Марка</th>
                <th>Модель</th>
                <th>Рік</th>
                <th>Реєстраційний номер</th>
                <th>Статус автомобіля</th>
                <th>Вартість</th>
                <th>Термін експлуатації (роки)</th>
                <th>Дата останнього обслуговування</th>
                <th>Опис останнього обслуговування</th>
                <th>Вартість останнього обслуговування</th>
                <th>Ім'я водія</th>
                <th>Прізвище водія</th>
                <th>Літри палива</th>
                <th>Вартість пального за літр</th>
                <th>Номер поліса страхування</th>
                <th>Дата початку страхування</th>
                <th>Дата закінчення страхування</th>
                <th>Вартість страхування</th>
                <th>Страхова компанія</th>
                <th>Дія</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['Make']; ?></td>
                    <td><?php echo $row['Model']; ?></td>
                    <td><?php echo $row['Year']; ?></td>
                    <td><?php echo $row['RegistrationNumber']; ?></td>
                    <td><?php echo $row['VehicleStatus']; ?></td>
                    <td><?php echo $row['InitialCost']; ?></td>
                    <td><?php echo $row['UsefulLife']; ?></td>
                    <td><?php echo $row['MaintenanceDate']; ?></td>
                    <td><?php echo $row['MaintenanceDescription']; ?></td>
                    <td><?php echo $row['MaintenanceCost']; ?></td>
                    <td><?php echo $row['DriverFirstName']; ?></td>
                    <td><?php echo $row['DriverLastName']; ?></td>
                    <td><?php echo $row['FuelLiters']; ?></td>
                    <td><?php echo $row['FuelCostPerLiter']; ?></td>
                    <td><?php echo $row['InsurancePolicyNumber']; ?></td>
                    <td><?php echo $row['InsuranceStartDate']; ?></td>
                    <td><?php echo $row['InsuranceEndDate']; ?></td>
                    <td><?php echo $row['InsuranceCost']; ?></td>
                    <td><?php echo $row['InsuranceCompany']; ?></td>
                    <?php if ($role == 'Admin'): ?>
    <td><button onclick='openEditModal(<?php echo json_encode($row); ?>)'>Редагувати</button></td>
<?php endif; ?>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Редагування даних</h2>
        <form id="editForm" onsubmit="submitForm(event)">
            <input type="hidden" name="VehicleID" id="VehicleID"><br>
            <input type="hidden" name="MaintenanceID" id="MaintenanceID"><br>
            <label for="Make">Марка:</label>
            <input type="text" name="Make" id="Make"><br>
            <label for="Model">Модель:</label>
            <input type="text" name="Model" id="Model"><br>
            <label for="Year">Рік:</label>
            <input type="number" name="Year" id="Year"><br>
            <label for="RegistrationNumber">Реєстраційний номер:</label>
            <input type="text" name="RegistrationNumber" id="RegistrationNumber"><br>
            <label for="VehicleStatus">Статус автомобіля:</label>
            <select name="VehicleStatus" id="VehicleStatus">
                <option value="active">Готовий</option>
                <option value="maintenance">Обслуговування</option>
                <option value="in_road">У дорозі</option>
                <option value="decommissioned">Виведено з експлутації</option>
            </select><br>
            <label for="InitialCost">Вартість:</label>
    <input type="number" name="InitialCost" id="InitialCost" step="0.01"><br>
    <label for="UsefulLife">Термін експлуатації (роки):</label>
    <input type="number" name="UsefulLife" id="UsefulLife"><br>
            <label for="MaintenanceDate">Дата останнього обслуговування:</label>
            <input type="date" name="MaintenanceDate" id="MaintenanceDate"><br>
            <label for="MaintenanceDescription">Опис останнього обслуговування:</label>
            <input type="text" name="MaintenanceDescription" id="MaintenanceDescription"><br>
            <label for="MaintenanceCost">Вартість останнього обслуговування:</label>
            <input type="number" step="0.01" name="MaintenanceCost" id="MaintenanceCost"><br>
            <label for="DriverFirstName">Ім'я водія:</label>
            <input type="text" name="DriverFirstName" id="DriverFirstName"><br>
            <label for="DriverLastName">Прізвище водія:</label>
            <input type="text" name="DriverLastName" id="DriverLastName"><br>
            <label for="FuelLiters">Літри палива:</label>
            <input type="number" step="0.01" name="FuelLiters" id="FuelLiters"><br>
            <label for="FuelCostPerLiter">Вартість пального за літр:</label>
            <input type="number" step="0.01" name="FuelCostPerLiter" id="FuelCostPerLiter"><br>
            <label for="InsurancePolicyNumber">Номер поліса страхування:</label>
            <input type="text" name="InsurancePolicyNumber" id="InsurancePolicyNumber"><br>
            <label for="InsuranceStartDate">Дата початку страхування:</label>
            <input type="date" name="InsuranceStartDate" id="InsuranceStartDate"><br>
            <label for="InsuranceEndDate">Дата закінчення страхування страхування:</label>
<input type="date" name="InsuranceEndDate" id="InsuranceEndDate"><br>
<label for="InsuranceCost">Вартість страхування:</label>
<input type="number" step="0.01" name="InsuranceCost" id="InsuranceCost"><br>
<label for="InsuranceCompany">Страхова компанія:</label>
<input type="text" name="InsuranceCompany" id="InsuranceCompany"><br>
<input type="submit" value="Зберегти">
</form>
</div>

</div>
 </body>
</html>

