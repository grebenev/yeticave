<?php
    session_start();
    require_once('db.php');
    require_once('functions.php');

    //вызовы функции показа списка категорий
    $categories_list = get_data_db($link, $categories_sql, 'list');
    $content = include_template('search.php', compact('categories_list'));
    $error = '';

    $search = trim($_GET['search']) ?? '';

    $cur_page = intval($_GET['page'] ?? 1);
    $offset = intval($_GET['offset'] ?? 0);

    // проверяем пустой ли запрос
    if (!strlen($search)) {
        $pages_count = 0;
        $error = 'Не ввели поисковый запрос';

    } else {
        // если не пустой создаем sql - запрос
        $search_sql = "SELECT lots.id, creation_date, end_date, lot_name, image, start_price, category_name FROM lots
        JOIN categories ON categories.id = lots.categories_id WHERE MATCH (lot_name, description) AGAINST (?)
        ORDER BY creation_date DESC";

        // проверка подключения к БД
        $result = get_link_db($link, $search_sql);

        // подготавливаем и выполняем запрос
        if(!$result) {
            $stmt = db_get_prepare_stmt($link, $search_sql, [$search]);
            mysqli_stmt_execute($stmt);
            $link_result = mysqli_stmt_get_result($stmt);
        }

        // проверяем кол-во записей по запросу
        $all_rows = mysqli_num_rows($link_result);

        // если есть записи предаем их в массив
        if ($all_rows) {
            $search_list = mysqli_fetch_all($link_result, MYSQLI_ASSOC);

            // пагинация поиска
            $page_items = 6;

            //вызываем функцию обрезания массива / на входе массив, кол-во элементов на странице, текущая страница
            $slice_list = get_array_slice($search_list, $page_items, $cur_page);

            $pages_count = ceil($all_rows / $page_items);
            $pages = range(1, $pages_count);

        } else {
            $error = 'Ничего не найдено';
        }

    }
    if (!$error) {
        $title = $search;
        $content = include_template('search.php', compact('categories_list', 'slice_list', 'search', 'error', 'pages', 'pages_count', 'cur_page'));
    } else {
        $title = $error;
        $content = include_template('search.php', compact('categories_list', 'error'));
    }

    $layout_content = include_template('layout.php', compact('content', 'categories_list', 'title'));
    print($layout_content);
