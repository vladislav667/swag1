<?php
$servername = "localhost1";
$database = "web";
$username = "test";
$password = "";
// Создаем соединение
$conn = mysqli_connect($servername, $username, $password, $database);
// Проверяем соединение
if (!$conn) {
    die("Ошибка подключение к базе данных: " . mysqli_connect_error());
}
echo "ОК";
mysqli_close($conn);
?>
