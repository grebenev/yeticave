<?php
    require_once('db.php');
    require_once('functions.php');

//вызовы функции
    $categories_list = get_data_db($link, $categories_sql, 'list');
    $current_user = get_data_db($link, $user_sql, 'list');

//подключение шаблона
    $content = include_template('add.php', compact( 'categories_list'));
    $layout_content = include_template('layout.php', compact('content', 'is_auth', 'current_user', 'categories_list', 'title'));
    print($layout_content);

    $sql = 'INSERT INTO gifs (dt_add, category_id, user_id, title, description, path) VALUES (NOW(), ?, 1, ?, ?, ?)';
