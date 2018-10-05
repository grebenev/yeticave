<?php
    // $is_auth = rand(0, 1);
    $title = "YetiCave";

    date_default_timezone_set("Europe/Moscow");
    require_once('db.php');
    require_once('functions.php');


    $categories_list = get_data_db($link, $categories_sql, 'list');
    $current_user = get_data_db($link, $user_sql, 'list');
    $lots_list = get_data_db($link, $lots_list_sql, 'list');

    $content = include_template('index.php', compact('lots_list', 'categories_list', 'hours', 'minutes'));
    $layout_content = include_template('layout.php', compact('content', 'is_auth', 'current_user', 'categories_list', 'title'));

    print($layout_content);


