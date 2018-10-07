<?php

require_once('db.php');
require_once('functions.php');

$title = 'Вход';

$categories_list = get_data_db($link, $categories_sql, 'list');
$current_user = get_data_db($link, $user_sql, 'list');

$content = include_template('login.php', compact('categories_list'));

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login= $_POST;

    $required = ['email', 'password'];
    $errors = [];
    foreach ($required as $key) {
        if (empty($form[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }

    $email = mysqli_real_escape_string($link, $login['email']);
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $res = mysqli_query($link, $sql);

    $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

    if (!count($errors) and $user) {
        if (password_verify($login['password'], $user['password'])) {
            $_SESSION['user'] = $user;
        } else {
            $errors['password'] = 'Неверный пароль';
        }
    }

    if (count($errors) > 0) {
        $content = include_template('login.php', compact('categories_list', 'login', 'errors', 'dict'));
    }
}

$layout_content = include_template('layout.php', compact('content', 'is_auth', 'current_user', 'categories_list', 'title'));
print($layout_content);
