<?php
session_start();
require_once 'database.php';
connectDB();

if (!isset($_SESSION["UserID"])) {
    header("Location: authorization.html");
    exit();
}


$userID = $_SESSION["UserID"];

$sql = "SELECT Username, PasswordHash, FirstName, LastName, Email FROM Users WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $username = $user['Username'];
    $passwordHash = $user['PasswordHash']; 
    $firstName = $user['FirstName'];
    $lastName = $user['LastName'];
    $email = $user['Email'];
} else {
   
    $username = '';
    $passwordHash = '';
    $firstName = '';
    $lastName = '';
    $email = '';
}

$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = $_POST["password"];
    $newFirstName = $_POST["firstName"];
    $newLastName = $_POST["lastName"];
    $newEmail = $_POST["email"];

    $updateSql = "UPDATE Users SET PasswordHash = ?, FirstName = ?, LastName = ?, Email = ? WHERE UserID = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ssssi", password_hash($newPassword, PASSWORD_DEFAULT), $newFirstName, $newLastName, $newEmail, $userID);

    if ($updateStmt->execute()) {
        $_SESSION["FirstName"] = $newFirstName;
        $_SESSION["LastName"] = $newLastName;
        $_SESSION["Email"] = $newEmail;
        echo "<script>alert('Профіль успішно оновлено');</script>";
        unset($_SESSION['profile_updated']);
        header("Location: home.php");
        exit();
    } else {
        echo "<script>alert('Не вдача при оновленні');</script>";
    }

    $updateStmt->close();
}

$conn->close();
?>