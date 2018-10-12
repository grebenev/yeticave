<?php
    session_start();
    require_once('db.php');
    require_once('functions.php');

    $title = 'Все лоты';


    //вызовы функции
    $categories_list = get_data_db($link, $categories_sql, 'list');
    $content = include_template('all-lots.php', compact('categories_list'));

    // ждем запроса с id категории
    $category_id = $_GET['category'] ?? null;

    if ($category_id) {
        $category_sql = 'SELECT lots.id, creation_date, end_date, lot_name, image, start_price, category_name FROM lots 
JOIN categories ON categories.id = lots.categories_id WHERE lots.categories_id = ? ORDER BY creation_date DESC';

        $stmt = db_get_prepare_stmt($link, $category_sql, [$category_id]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $lots_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $error = '';


        if (count($lots_list) > 0) {
            $content = include_template('all-lots.php', compact('lots_list', 'categories_list'));
        } else {
            $error = 'Нет такой категории';
        }

    } else {
        $error = 'Документ не найден';
    }

    if ($error) {
        http_response_code(404);
        $content = include_template('error.php', compact('error'));
    }


    $layout_content = include_template('layout.php', compact('content', 'categories_list', 'title'));
    print($layout_content);
