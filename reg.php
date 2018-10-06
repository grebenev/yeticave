<?php

    require_once('db.php');
    require_once('functions.php');

    $title = 'Регистрация';

    //вызовы функции
    $categories_list = get_data_db($link, $categories_sql, 'list');
    $current_user = get_data_db($link, $user_sql, 'list');

    $content = include_template('reg.php', compact('categories_list'));

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $dict = ['email' => 'Почта', 'password' => 'Пароль', 'name' => 'Имя', 'contacts' => 'Контакт', 'no_file' => 'Изображение'];




        $is_register = register($_POST['reg'], $link);

        if ($is_register === true) {
            // редирект
        } else if (count($is_register) > 0) {
            // показывай ошибки которые лежат в $isRegister
            $content = include_template('reg.php', compact('categories_list', 'reg', 'is_register', 'dict'));
        }

//
//        if (count($is_register) > 0) {
//            $content = include_template('reg.php', compact('categories_list', 'reg', 'is_register', 'dict'));
//        }

    }







    $layout_content = include_template('layout.php', compact('content', 'is_auth', 'current_user', 'categories_list', 'title'));
    print($layout_content);
