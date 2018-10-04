<?php
    require_once('db.php');
    require_once('functions.php');

//вызовы функции
    $categories_list = get_data_db($link, $categories_sql, 'list');
    $current_user = get_data_db($link, $user_sql, 'list');


// получение данных из формы
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $array_lot = $_POST['lot']; //получаем гобальный массив из POST

        $filename = uniqid() . '.jpg'; //переименовываем в уникальное имя файла
        $array_lot['path'] = $filename; // создаем ключ в голбальном массиве и присваиваем ему уникальное имя файла

        move_uploaded_file($_FILES['jpg_image']['tmp_name'], 'uploads/' . $filename); /* функция ищет ключ jpg_image  в
  нем ключ с именем tmp_name - в двумерном файловом массиве $_FILES и  переноит его в uploads/ переименовывает в
 $filename*/

        $sql = 'INSERT INTO lots (creation_date, category_id, lot_name, description, image, start_price, lot_step, users_id) 
VALUES (NOW(), ?, ?, ?, ?, ?, ?, 1)';

        // функция-помощник

        $stmt = db_get_prepare_stmt($link, $sql, [$array_lot['category'], $array_lot['name'], $array_lot['message'],
             $array_lot['path'], $array_lot['price'], $array_lot['step']]);
        $res = mysqli_stmt_execute($stmt);

    }

//подключение шаблона
    $content = include_template('add.php', compact('categories_list'));
    $layout_content = include_template('layout.php', compact('content', 'is_auth', 'current_user', 'categories_list', 'title'));
    print($layout_content);
