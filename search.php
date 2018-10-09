<?php
    session_start();
    require_once('db.php');
    require_once('functions.php');

    $title = 'Поиск';

    //вызовы функции показа списка категорий
    $categories_list = get_data_db($link, $categories_sql, 'list');
    $content = include_template('search.php', compact('categories_list'));

    $error = '';

    $search = trim($_GET['search']) ?? '';
    $save_search = mysqli_real_escape_string($link, $search); // безопасно

    // проверяем пустой запрос
    if (!strlen($search)) {

        $error = 'Не ввели поисковый запрос';
        $content = include_template('search.php', compact('categories_list', 'error'));

    } else {
        // если не пусто создаем sql - запрос
        $search_sql = "SELECT lots.id, creation_date, end_date, lot_name, image, start_price, category_name FROM lots
JOIN categories ON categories.id = lots.categories_id WHERE MATCH (lot_name, description) AGAINST ('$save_search')";

        // вызов функции подключения к бд
        $link_result = get_link_db($link, $search_sql);

        if ($link_result) {
            $search_list = mysqli_fetch_all($link_result, MYSQLI_ASSOC);
            if (count($search_list) > 0) {

            } else {
                $error = 'Ничего не найдено';
            }
            $content = include_template('search.php', compact('categories_list', 'search_list', 'save_search', 'error'));
        }
    }


    // создаем запрос

//    $search_sql = "SELECT lots.id, creation_date, end_date, lot_name, image, start_price, category_name FROM lots
//JOIN categories ON categories.id = lots.categories_id WHERE lots.lot_name LIKE '%$save_search%' or description LIKE '%$save_search%'";


    $layout_content = include_template('layout.php', compact('content', 'categories_list', 'title'));
    print($layout_content);
