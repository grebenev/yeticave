<?php
    session_start();
    require_once('db.php');
    require_once('functions.php');

    $title = 'Регистрация';

    //вызовы функции
    $categories_list = get_data_db($link, $categories_sql, 'list');

    $content = include_template('reg.php', compact('categories_list', 'reg'));

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $reg = $_POST['reg'];
        $dict = [
            'email' => 'Почта',
            'password' => 'Пароль',
            'name' => 'Имя',
            'contacts' => 'Контакт',
            'file' => 'Изображение'
        ];


        $is_register = register($_POST['reg'], $link);

        if ($is_register === true) {
            // редирект
            header("Location: /login.php");

        } else {
            if (count($is_register) > 0) {
                $content = include_template('reg.php', compact('categories_list', 'reg', 'is_register', 'dict'));
            }
        }
    }

    $layout_content = include_template('layout.php', compact('content', 'categories_list', 'title'));
    print($layout_content);
