<?php

    require_once('db.php');
    require_once('functions.php');

    $title = 'Ренистрация';

    //вызовы функции
    $categories_list = get_data_db($link, $categories_sql, 'list');
    $current_user = get_data_db($link, $user_sql, 'list');

    $content = include_template('reg.php', compact('categories_list'));

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $reg = $_POST['reg'];
        $required = ['email', 'password', 'name', 'contacts', 'file'];
        $dict = ['email' => 'Почта', 'password' => 'Пароль', 'name' => 'Имя', 'contacts' => 'Контакт', 'file' => 'Изображение'];
        $errors = [];

        foreach ($required as $key) {
            if (empty($reg[$key])) {
                $errors[$key] = 'Это поле надо заполнить';
            }
        }

        if(!empty($reg['email'])) {
            $email = mysqli_real_escape_string($link, $reg['email']);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Email должен быть корректным';

            } else {
                $sql = "SELECT id FROM users WHERE email = '$email'";
                $res = mysqli_query($link, $sql);
                if (mysqli_num_rows($res) > 0) {
                    $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
                }
            }
        }



        if (count($errors) > 0) {
            $content = include_template('reg.php', compact('categories_list', 'reg', 'errors', 'dict'));
        }
    }





    $layout_content = include_template('layout.php', compact('content', 'is_auth', 'current_user', 'categories_list', 'title'));
    print($layout_content);
