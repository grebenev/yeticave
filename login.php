<?php
    session_start();
    require_once('db.php');
    require_once('functions.php');

    $title = 'Вход';

    $categories_list = get_data_db($link, $categories_sql, 'list');

    $content = include_template('login.php', compact('categories_list'));


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $login = $_POST['login'];

        $required = ['email', 'password'];
        $dict = ['email' => 'Почта', 'password' => 'Пароль'];
        $errors = [];

        foreach ($required as $key) {
            if (empty($login[$key])) {
                $errors[$key] = 'Это поле надо заполнить';
            }
        }

        if (!count($errors)) {

            $email = mysqli_real_escape_string($link, $login['email']);
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $res = mysqli_query($link, $sql);

            $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

            if ($user) {
                if (password_verify($login['password'], $user['password'])) {
                    $_SESSION['user'] = $user;
                } else {
                    $errors['password'] = 'Неверный пароль';
                }
            } else {
                $errors['email'] = 'Такой пользователь не найден';
            }
        }


        if (count($errors) > 0) {
            $content = include_template('login.php', compact('categories_list', 'login', 'errors', 'dict'));
        } else {
            header("Location: /login.php");
            exit();
        }

    } else {
        if (isset($_SESSION['user'])) {

            $username = $_SESSION['user']['user_name'];
            header("Location: /");

        } else {
            $content = include_template('login.php', compact('categories_list'));
        }
    }

    $layout_content = include_template('layout.php', compact('content', 'categories_list', 'title'));
    print($layout_content);
