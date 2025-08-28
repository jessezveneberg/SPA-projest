<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Розрахунок амортизації</title>
    <style>
        /* Стилі для модального вікна */
        .modal {
            display: none; /* Початково вікно приховане */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; /* Для прокрутки, якщо контент перевищує вікно */
            background-color: rgba(0,0,0,0.4); 
        }
        
        /* Контейнер модального вікна */
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% від верху і з централізацією */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Ширина контенту */
        }

        /* Закриваючий хрестик */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
            position: relative; /* Додали цю властивість */
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
            position: relative; /* Додали цю властивість */
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
<div class="menu" id="menu">
    <a href="#" onclick="loadContent('home.php')">Головна</a>
    <a href="#" onclick="loadContent('profile.php')">Профіль</a>
    <a href="#" onclick="loadContent('transport.php')">Транспорт</a>
    <a href="#" onclick="loadContent('amortization.php')">Розрахунок амортизації</a>
    <a href="#" onclick="loadContent('manage_current_transport.php')" class="manage-current-transport">Управління поточним транспортом</a>
    <a href="logout.php" class="logout">Вихід</a>
</div>
 
 

<div id="content" class="container"> <!-- Додали елемент, куди буде вставлятися завантажений вміст -->
    <form id="amortizationForm">
        <label for="method">Виберіть метод амортизації:</label>
        <select name="method" id="method">
            <option value="linear">Лінійний</option>
            <option value="double_declining">Подвійне зменшення</option>
        </select>
        
        <label for="vehicle_id">Виберіть автомобіль:</label>
        <select name="vehicle_id" id="vehicle_id">
        <?php
        // Підключення до бази даних
        require_once 'database.php';
        connectDB();        // Отримання списку автомобілів
        $sql = "SELECT VehicleID, Make, Model FROM Vehicles";
        $result = $conn->query($sql);

        // Вивід списку автомобілів у випадаючому меню
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $vehicle_id = $row['VehicleID'];
                $make = $row['Make'];
                $model = $row['Model'];
                echo "<option value='$vehicle_id'>$make $model</option>";
            }
        } else {
            echo "<option value='' disabled>Немає доступних автомобілів</option>";
        }
        ?>
        </select>

        <button type="submit">Розрахувати амортизацію</button>
    </form>
</div>
<!-- Модальне вікно для відображення результатів -->

<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="modalResult"></div>
    </div>
</div>
<script>
function loadContent(url) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest'); // Додаємо заголовок для позначення AJAX-запиту
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
 

function closeModal() {
    var modal = document.getElementById("myModal");
    modal.style.display = "none";
}

// При кліку на хрестик закриття модального вікна
document.getElementsByClassName("close")[0].addEventListener('click', closeModal);

// При кліку на кнопку submit форми
document.getElementById('amortizationForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Запобігаємо типовому поведінці форми

    // Створюємо об'єкт FormData для збору даних форми
    var formData = new FormData(this);

    // Відправляємо POST-запит на calculate_amortization.php за допомогою fetch
    fetch('calculate_amortization.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text()) // Отримуємо текстову відповідь
    .then(data => {
        // Показ результатів у модальному вікні
        var modalResult = document.getElementById('modalResult');
        modalResult.innerHTML = data;
        
        // Показ модального вікна
        var modal = document.getElementById("myModal");
        modal.style.display = "block";
    })
    .catch(error => {
        console.error('Помилка:', error);
    });
});
</script>
</body>
</html>

