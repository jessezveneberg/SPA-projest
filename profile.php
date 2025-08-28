<?php
session_start();
require_once 'database.php';
connectDB();

if (!isset($_SESSION["UserID"])) {
    header("Location: authorization.html");
    exit();
}

// Получение данных пользователя из базы данных
$userID = $_SESSION["UserID"];

$sql = "SELECT Username, PasswordHash, FirstName, LastName, Email FROM Users WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $username = $user['Username'];
    $passwordHash = $user['PasswordHash']; // Пароль в хэшированном виде, не рекомендуется отображать на странице
    $firstName = $user['FirstName'];
    $lastName = $user['LastName'];
    $email = $user['Email'];
} else {
    // Обработка ситуации, если пользователь не найден в базе данных
    // Можно установить значения по умолчанию или вывести сообщение об ошибке
    $username = '';
    $passwordHash = '';
    $firstName = '';
    $lastName = '';
    $email = '';
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<h2>Профіль користувача</h2>

<form action="update_profile.php" method="post">
    <label for="username">Ім'я користувача:</label>
    <input type="text" id="username" name="username" value="<?php echo $username; ?>" readonly>

    <label for="password">Пароль:</label>
    <input type="password" id="password" name="password" value="<?php echo $passwordHash; ?>" required>

    <label for="firstName">Ім'я:</label>
    <input type="text" id="firstName" name="firstName" value="<?php echo $firstName; ?>" required>

    <label for="lastName">Прізвище:</label>
    <input type="text" id="lastName" name="lastName" value="<?php echo $lastName; ?>" required>

    <label for="email">Електронна пошта:</label>
    <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>

    <input type="submit" value="Зберегти зміни">
 
</form>

</body>
</html>
