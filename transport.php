<?php
session_start();

// Перевіряємо, чи авторизований користувач
if (!isset($_SESSION["UserID"])) {
    header("Location: authorization.html");
    exit();
}

require_once 'database.php';
connectDB();
$role = $_SESSION["Role"] ?? 'user'; // Default to 'user' role if not set

// Отримуємо всіх водіїв із бази даних
$driversResult = $conn->query("SELECT * FROM Drivers");

// Отримуємо всі машини з бази даних
$vehiclesResult = $conn->query("SELECT * FROM Vehicles");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управління транспортом</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
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
        .form-group input, .form-group select {
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
            margin-top: 40px;
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
<div class="menu">
        <a href="#" onclick="loadContent('home.php')">Головна</a>
        <a href="#" onclick="loadContent('profile.php')">Профіль</a>
        <a href="#" onclick="loadContent('transport.php')">Транспорт</a>
        <a href="#" onclick="loadContent('amortization.php')">Розрахунок амортизації</a>
        <a href="#" onclick="loadContent('manage_current_transport.php')" class="manage-current-transport">Управління поточним транспортом</a>
        <a href="logout.php" class="logout">Вихід</a>
    </div>


    <div class="container" id="content">
        <div class="section">
            <h2>Призначення водія</h2>
            <form method="POST" action="assing_driver.php">
                <div class="form-group">
                    <label for="vehicle">Оберіть транспорт:</label>
                    <select id="vehicle" name="vehicle_id" required>
                        <?php while ($vehicle = $vehiclesResult->fetch_assoc()): ?>
                            <option value="<?php echo $vehicle['VehicleID']; ?>">
                                <?php echo $vehicle['Make'] . ' ' . $vehicle['Model']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="driver">Оберіть водія:</label>
                    <select id="driver" name="driver_id" required>
                        <?php while ($driver = $driversResult->fetch_assoc()): ?>
                            <option value="<?php echo $driver['DriverID']; ?>">
                                <?php echo $driver['FirstName'] . ' ' . $driver['LastName']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>  <?php if ($role == 'Admin'): ?>
                <div class="form-group">
                    <button type="submit">Призначити водія</button>
                </div>
                <?php endif; ?>
            </form>
        </div>

        <div class="section">
            <h2>Оберіть зображення автомобіля</h2>
            <form method="POST" action="upload_images.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="vehicleImage">Для автомобіля:</label>
                    <select id="vehicleImage" name="vehicle_id" required>
                        <?php
                        // Скидання курсора результатів вибірки для повторного використання
                        $vehiclesResult->data_seek(0); 
                        while ($vehicle = $vehiclesResult->fetch_assoc()): ?>
                            <option value="<?php echo $vehicle['VehicleID']; ?>">
                                <?php echo $vehicle['Make'] . ' ' . $vehicle['Model']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="image">Оберіть зображення:</label>
                    <input type="file" id="image" name="image" accept="image/*" required>
                </div>
                <div class="form-group">
                    <label for="description">Опишіть зображення:</label>
                    <input type="text" id="description" name="description">
                </div> <?php if ($role == 'Admin'): ?>
                <div class="form-group">
                    <button type="submit">Завантажити зображення</button>
                </div>
                <?php endif; ?>
            </form>
        </div>

        <div class="section">
    <h2>Управління автомобілями</h2>
    <form method="POST" action="manage_vehicles.php">
        <div class="form-group">
            <label for="make">Марка:</label>
            <input type="text" id="make" name="make" required>
        </div>
        <div class="form-group">
            <label for="model">Модель:</label>
            <input type="text" id="model" name="model" required>
        </div>
        <div class="form-group">
            <label for="year">Рік випуску:</label>
            <input type="number" id="year" name="year" min="1960" value="1960" required>
        </div>
        <div class="form-group">
            <label for="registrationNumber">Реєстраційний номер:</label>
            <input type="text" id="registrationNumber" name="registration_number" required>
        </div>
        <div class="form-group">
            <label for="vehicleStatus">Статус:</label>
            <select id="vehicleStatus" name="vehicle_status" required>
                <option value="active">Активний</option>
                <option value="maintenance">В обслуговуванні</option>
                <option value="in_road">У дорозі</option>
                <option value="decommissioned">Виведено з експлуатації</option>
            </select>
        </div>
        <div class="form-group">
            <label for="initialCost">Вартість:</label>
            <input type="number" id="initialCost" name="initial_cost" min="0" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="usefulLife">Був у експлуатації (роки):</label>
            <input type="number" id="usefulLife" name="useful_life" min="1" required>
        </div>
        <?php if ($role == 'Admin'): ?>
        <div class="form-group"> 
            <button type="submit">Додати транспорт</button>
        </div>
        <?php endif; ?>
    </form>
</div>



    
        <div class="section">
    <h2>Додати запис обслуговування</h2>
    <form method="POST" action="add_maintenance.php">
        <div class="form-group">
            <label for="vehicleMaintenance">Оберіть транспорт:</label>
            <select id="vehicleMaintenance" name="vehicle_id" required>
                <?php
                $vehiclesResult->data_seek(0); 
                while ($vehicle = $vehiclesResult->fetch_assoc()): ?>
                    <option value="<?php echo $vehicle['VehicleID']; ?>">
                        <?php echo $vehicle['Make'] . ' ' . $vehicle['Model']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="maintenanceDate">Дата:</label>
            <input type="date" id="maintenanceDate" name="date" required>
        </div>
        <div class="form-group">
            <label for="description">Опис:</label>
            <input type="text" id="description" name="description" required>
        </div>
        <div class="form-group">
            <label for="cost">Вартість:</label>
            <input type="number" step="0.01" id="cost" name="cost" min="0.00" required>
        </div> <?php if ($role == 'Admin'): ?>
        <div class="form-group">
            <button type="submit">Додати запис</button>
        </div>
    </form>
    <?php endif; ?>

</div>
<div class="section">
<h2>Заправка машини</h2>
<form method="POST" action="refuel_vehicle.php">
    <div class="form-group">
        <label for="vehicleRefuel">Оберіть транспорт:</label>
        <select id="vehicleRefuel" name="vehicle_id" required>
            <?php
            $vehiclesResult->data_seek(0); 
            while ($vehicle = $vehiclesResult->fetch_assoc()): ?>
                <option value="<?php echo $vehicle['VehicleID']; ?>">
                    <?php echo $vehicle['Make'] . ' ' . $vehicle['Model']; ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="refuelDate">Дата заправки:</label>
        <input type="date" id="refuelDate" name="date" required>
    </div>
    <div class="form-group">
        <label for="liters">Літри:</label>
        <input type="number" step="0.01" id="liters" name="liters" min="10" value="1" required>
    </div>
    <div class="form-group">
        <label for="costPerLiter">Вартість за літр:</label>
        <input type="number" step="0.01" id="costPerLiter" name="cost_per_liter" value="1.00" min="1.00" required>
        </div> <?php if ($role == 'Admin'): ?>
    <div class="form-group">
        <button type="submit">Заправити транспорт</button>
    </div>
</form>                <?php endif; ?>

</div>
<div class="section">
    <h2>Страхування автомобіля</h2>
    <form method="POST" action="add_insurance.php"  onsubmit="return validateForm()">
        <div class="form-group">
            <label for="vehicleInsurance">Оберіть транспорт:</label>
            <select id="vehicleInsurance" name="vehicle_id" required>
                <?php
                $vehiclesResult->data_seek(0); 
                while ($vehicle = $vehiclesResult->fetch_assoc()): ?>
                    <option value="<?php echo $vehicle['VehicleID']; ?>">
                        <?php echo $vehicle['Make'] . ' ' . $vehicle['Model']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="insurancePolicyNumber">Номер поліса страхування:</label>
            <input type="text" id="insurancePolicyNumber" name="policy_number" required>
        </div>
        <div class="form-group">
            <label for="insuranceStartDate">Дата початку страхування:</label>
            <input type="date" id="insuranceStartDate" name="start_date" required>
        </div>
        <div class="form-group">
            <label for="insuranceEndDate">Дата закінчення страхування:</label>
            <input type="date" id="insuranceEndDate" name="end_date" required>
        </div>
        <div class="form-group">
            <label for="insuranceCost">Вартість страхування:</label>
            <input type="number" step="0.01" id="insuranceCost" name="cost" min="0.00" value="0.00" required>
        </div>
        <div class="form-group">
            <label for="insuranceCompany">Страхова компанія:</label>
            <input type="text" id="insuranceCompany" name="company" required>
        </div> <?php if ($role == 'Admin'): ?>
        <div class="form-group">
            <button type="submit">Додати страхування</button>
        </div>
    </form>                <?php endif; ?>

</div>



    </div>
    <script>
  
function validateForm() {
    var startDate = document.getElementById('start_date').value;
    var endDate = document.getElementById('end_date').value;

    if (endDate < startDate) {
        alert("Дата закінчення страховки не може бути меншою ніж дата початку.");
        return false;
    }
    return true;
}
 

        function loadContent(url) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', url, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById('content').innerHTML = xhr.responseText;

                    history.pushState(null, '', url);
                }
            };
            xhr.send();
        }
    </script>
</body>
</html>

