<?php
    session_start();

    if (!isset($_SESSION['user'])) {
        http_response_code(403);
        header("Location: /add.php");
    }
    require_once('db.php');
    require_once('functions.php');


    $title = 'Добавление лота';

    //вызовы функции
    $categories_list = get_data_db($link, $categories_sql, 'list');

    $content = include_template('add.php', compact('categories_list'));

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['lot']) and !empty($_POST['lot'])) {
            $lot = $_POST['lot'];
        }


        $required = ['name', 'message', 'category', 'price', 'step', 'date'];
        $dict = [
            'name' => 'Наименование',
            'message' => 'Описание',
            'category' => 'Категория',
            'price' => 'Начальная цена',
            'step' => 'Шаг ставки',
            'date' => 'Дата окончания торгов',
            'file' => 'Изображение'
        ];
        $errors = [];

        // проверка числа
        $required_int = ['price', 'step'];

        foreach ($required_int as $key) {
            if (!filter_var($lot[$key], FILTER_VALIDATE_INT)) {
                $errors[$key] = 'Это не число';
            }
        }

        // проверка на заполнение
        foreach ($required as $key) {
            if (empty($lot[$key])) {
                $errors[$key] = 'Это поле надо заполнить';
            }
        }

        // проверка файла
        if (!empty($_FILES['jpg_image']['name'])) {
            $tmp_name = $_FILES['jpg_image']['tmp_name'];

            $file_type = mime_content_type($tmp_name);

            if (!in_array($file_type, ['image/jpeg', 'image/png'])) {
                $errors['file'] = 'Загрузите картинку в формате JPEG или PNG';

            } else {
                $filename = uniqid() . '.jpg';
                move_uploaded_file($tmp_name, 'img/' . $filename);
                $lot['path'] = $filename;
            }

        } else {
            $errors['file'] = 'Вы не загрузили файл';
        }

        //проверка даты
        if (isset($lot['date']) and (strtotime($lot['date']) < (time() + (60 * 60 * 24)))) {
            $errors['date'] = 'Дата должна быть больше';
        }


        if (count($errors) > 0) {
            $content = include_template('add.php', compact('categories_list', 'lot', 'errors', 'dict'));
        } else {

            // Если не ошибок добавляем в базу
            if(isset($_SESSION['user']['id']) and !empty($_SESSION['user']['id'])) {
                $user_id = $_SESSION['user']['id'];
            }

            $sql = 'INSERT INTO lots (creation_date, categories_id, lot_name, description, image, start_price, lot_step, users_id, end_date)
            VALUES (NOW(), ?, ?, ?, ?, ?, ?, ' . $user_id . ', ?)';
            $lot_data = $lot['date'] . ' ' . date("H:i:s");

            $stmt = db_get_prepare_stmt($link, $sql, [
                $lot['category'],
                $lot['name'],
                $lot['message'],
                $lot['path'],
                $lot['price'],
                $lot['step'],
                $lot_data
            ]);
            $result = mysqli_stmt_execute($stmt); // выполняем подготовленное выражение

            if ($result) {
                $lot_id = mysqli_insert_id($link); //присваивает последний, добавленный id

                header("Location: lot.php?lot=" . $lot_id); //пренаправляет на последний id
            } else {
                $content = include_template('error.php', ['error' => mysqli_error($link)]);
            }
        }
    }

    $layout_content = include_template('layout.php', compact('content', 'categories_list', 'title'));
    print($layout_content);
