<?php
    session_start ();

    if (!isset($_SESSION['user'])){
        http_response_code(403);
        header("Location: /add.php");
    }
    require_once ('db.php');
    require_once ('functions.php');


    $title = 'Добавление лота';

//вызовы функции
    $categories_list = get_data_db ($link, $categories_sql, 'list');
//    $current_user = get_data_db($link, $user_sql, 'list');


    $content = include_template ('add.php', compact ('categories_list'));

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $lot = $_POST['lot'];


        $required = ['name', 'message', 'category', 'price', 'step', 'date'];
        $dict = ['name' => 'Наименование', 'message' => 'Описание', 'category' => 'Категория', 'price' => 'Начальная цена', 'step' => 'Шаг ставки', 'date' => 'Дата окончания торгов', 'file' => 'Изображение'];
        $errors = [];


        foreach ($required as $key) {
            if (empty($lot[$key])) {
                $errors[$key] = 'Это поле надо заполнить';
            }
        }

        // проверка файла

        if (!empty($_FILES['jpg_image']['name'])) {
            $tmp_name = $_FILES['jpg_image']['tmp_name'];

            $finfo = finfo_open (FILEINFO_MIME_TYPE);
            $file_type = finfo_file ($finfo, $tmp_name);


            if ($file_type !== "image/jpeg  ") {
                $errors['file'] = 'Загрузите картинку в формате JPEG';

            } else {
                $filename = uniqid () . '.jpg';
                move_uploaded_file ($tmp_name, 'img/' . $filename);
                $lot['path'] = $filename;
            }

        } else {
            $errors['file'] = 'Вы не загрузили файл';
        }


        if (count ($errors) > 0) {
            $content = include_template ('add.php', compact ('categories_list', 'lot', 'errors', 'dict'));
        } else {

            // Если не ошибок добавляем в базу

            $sql = 'INSERT INTO lots (creation_date, categories_id, lot_name, description, image, start_price, lot_step, users_id, end_date)
            VALUES (NOW(), ?, ?, ?, ?, ?, ?, 1, ?)';

            $stmt = db_get_prepare_stmt ($link, $sql, [$lot['category'], $lot['name'], $lot['message'],
                $lot['path'], $lot['price'], $lot['step'], $lot['date']]);
            $res = mysqli_stmt_execute ($stmt); // выполняем подготовленное выражение

            if ($res) {
                $lot_id = mysqli_insert_id ($link); //присваивает последний, добавленный id

                header ("Location: lot.php?lot=" . $lot_id); //пренаправляет на последний id
            } else {
                $content = include_template ('error.php', ['error' => mysqli_error ($link)]);
            }


        }
    }

    $layout_content = include_template ('layout.php', compact ('content', 'categories_list', 'title'));
    print($layout_content);
