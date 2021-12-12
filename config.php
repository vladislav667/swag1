<?php
$db_server = "localhost";
$db_name  = "webserver";
$db_user  = "root";
$db_password  = "123456789";
try {
    // Открываем соединение, указываем адрес сервера, имя бд, имя пользователя и пароль,
    // также сообщаем серверу в какой кодировке должны вводится данные в таблицу бд.
    $db = new PDO("mysql:host=$db_server;dbname=$db_name", $db_user, $db_password,array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES utf8"));
    // Устанавливаем атрибут сообщений об ошибках (выбрасывать исключения)
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Переносим данные из полей формы в переменные.
    $book_title=$_POST['book_title'];
    $book_author=$_POST['book_author'];
    $book_publish_year=$_POST['book_publish_year'];
    $book_price=$_POST['book_price'];
 
    // Переносим данные (отмеченные жанры) из полей формы в массив
    $book_genres = array();
    
    if(!empty($_POST['book_genre'])){
        foreach($_POST['book_genre'] as $genre_selected){
            $book_genres[] = $genre_selected;
        }
    }
    
    // Используем Prepared statements (заранее скомпилированное SQL-выражение) для защиты от SQL-инъекций.
    // Создаем ассоциативный массив для подстановки данных в запрос.
    $data = array(
        'title' => "$book_title",
        'author' => "$book_author",
        'year' => "$book_publish_year",
        'price' => "$book_price",
    );
 
    // Запрос на создание записи в таблице
    // Если есть хоть один отмеченный жанр в форме, то составляем запрос, внося все отмеченные жанры,
    // иначе название жанра не вносим в таблицу.
    if(sizeof($book_genres) > 0){
        $sql = "INSERT INTO books(title, author, publish_year, genre, price)".
    " VALUES(:title, :author, :year, '" . implode(',', $book_genres) . "', :price)";
    } else {
        $sql = "INSERT INTO books(title, author, publish_year, price)".
    " VALUES(:title, :author, :year, :price)";
    }
    // Перед тем как выполнять запрос предлагаю убедится, что он составлен без ошибок.
    //echo $sql;
    
    // Подготовка запроса (замена псевдо переменных :title, :author и т.п. на реальные данные)
    $statement = $db->prepare($sql);
    // Выполняем запрос
    $statement->execute($data);
    
    echo "Запись успешно создана!";
}
 
catch(PDOException $e) {
    echo "Ошибка при создании записи в базе данных: " . $e->getMessage();
}
 
// Закрываем соединение
$db = null;
?>