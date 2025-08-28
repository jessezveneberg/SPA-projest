<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 0 auto;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
            margin-right: 10px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        /* Стили для слайдера */
        .slider {
            width: 80%;
            margin: 20px auto;
            overflow: hidden;
            position: relative;
            display: flex; /* Добавлено */
            justify-content: center; /* Добавлено */
        }
        .carousel {
            display: flex;
            transition: transform 0.5s ease;
            position: relative;
        }
        .vehicle {
            flex: 0 0 auto;
            width: 300px; /* Ширина элемента карусели */
            margin-right: 20px; /* Расстояние между элементами карусели */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        .vehicle img {
            width: 100%;
        }
        .prev, .next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 10px;
            cursor: pointer;
            z-index: 1;
        }
        .prev {
            left: 10px;
        }
        .next {
            right: 10px;
        }
    </style>
</head>
<body>
    <div class="container" id="content">
        <h1>Система обліку ТЗ на підприємстві</h1>
        <button onclick="loadRegistrationPage()" class="btn">Реєстрація</button>
        <button onclick="loadAuthorizationPage()" class="btn">Авторизація</button>
        <!-- Слайдер с машинами -->
        <div class="slider" id="vehiclesSlider"></div>
    </div>

    <script>
        let currentSlide = 0;

        function loadRegistrationPage() {
            fetch('registration.html')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('content').innerHTML = html;
                })
                .catch(error => {
                    console.error('Error loading registration page:', error);
                });
        }

        function loadAuthorizationPage() {
            fetch('authorization.html')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('content').innerHTML = html;
                })
                .catch(error => {
                    console.error('Error loading authorization page:', error);
                });
        }

        function fetchVehicles() {
            fetch('vehicles.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const vehiclesSlider = document.getElementById('vehiclesSlider');

                    // Очищаем контейнер перед добавлением новых элементов
                    vehiclesSlider.innerHTML = '';

                    data.forEach((vehicle, index) => {
                        const vehicleContainer = document.createElement('div');
                        vehicleContainer.classList.add('vehicle');

                        const image = document.createElement('img');
                        image.src = vehicle.ImagePath;
                        vehicleContainer.appendChild(image);

                        const title = document.createElement('h2');
                        title.textContent = vehicle.Make + ' ' + vehicle.Model;
                        vehicleContainer.appendChild(title);

                        const description = document.createElement('p');
                        description.textContent = 'Рік випуску: ' + vehicle.Year + ', Реєстраційний номер: ' + vehicle.RegistrationNumber;
                        vehicleContainer.appendChild(description);

                        // Добавляем кнопки переключения
                        const prevButton = document.createElement('div');
                        prevButton.classList.add('prev');
                        prevButton.innerHTML = '&#10094;';
                        prevButton.addEventListener('click', () => changeSlide(-1));

                        const nextButton = document.createElement('div');
                        nextButton.classList.add('next');
                        nextButton.innerHTML = '&#10095;';
                        nextButton.addEventListener('click', () => changeSlide(1));

                        vehicleContainer.appendChild(prevButton);
                        vehicleContainer.appendChild(nextButton);

                        // Скрываем все элементы, кроме первого
                        if (index !== 0) {
                            vehicleContainer.style.display = 'none';
                        }

                        // Добавляем контейнер карусели в основной контейнер слайдера
                        vehiclesSlider.appendChild(vehicleContainer);
                    });
                })
                .catch(error => {
                    console.error('Error fetching vehicles:', error);
                });
        }

        function changeSlide(direction) {
            const vehicles = document.querySelectorAll('.vehicle');
            if (direction === -1 && currentSlide > 0) {
                vehicles[currentSlide].style.display = 'none';
                vehicles[currentSlide - 1].style.display = 'inline-block';
                currentSlide--;
            } else if (direction === 1 && currentSlide < vehicles.length - 1) {
                vehicles[currentSlide].style.display = 'none';
                vehicles[currentSlide + 1].style.display = 'inline-block';
                currentSlide++;
            }
        }

        fetchVehicles();
    </script>
</body>
</html>
