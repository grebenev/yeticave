<?php
    session_start();
    $title = "YetiCave";


    date_default_timezone_set("Europe/Moscow");
    require_once('db.php');
    require_once('functions.php');

    $lots_list_sql = 'SELECT lots.id, creation_date, end_date, lot_name, image, start_price, category_name FROM lots JOIN categories ON categories.id = lots.categories_id ORDER BY creation_date DESC';


    $categories_list = get_data_db($link, $categories_sql, 'list');

    $lots_list = get_data_db($link, $lots_list_sql, 'list');

    $content = include_template('index.php', compact('lots_list', 'categories_list'));
    $layout_content = include_template('layout.php', compact('content', 'categories_list', 'title'));

    print($layout_content);


