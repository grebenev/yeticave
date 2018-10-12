<?php
    session_start();
    require_once('db.php');
    require_once('functions.php');

    $title = 'Все лоты';


    //вызовы функции
    $categories_list = get_data_db($link, $categories_sql, 'list');
    $content = include_template('all-lots.php', compact('categories_list'));

    // ждем запроса с id категории
    $category_id = $_GET['category'] ?? 1;
    $cur_page = $_GET['page'] ?? 1; // если данных не пришло page = 1
    $page_items = 6; // кол-во лотов на странице


    if ($category_id) {
        //общее количество лотов
        $result = mysqli_query($link, 'SELECT COUNT(*) as count_lots FROM lots WHERE categories_id ='.$category_id.'');
        // в переменную $items_count записали общее количество записей в lots
        $items_count = mysqli_fetch_assoc($result)['count_lots'];
        // делим общее число записей на число страниц резкльтат округляем и присваиваем $pages_count
        $pages_count = ceil($items_count / $page_items);
        // вычисляем смещение это текущая страница - 1 * на число лотов на странице (6)
        $offset = ($cur_page - 1) * $page_items;


        $category_sql = 'SELECT lots.id, creation_date, end_date, lot_name, image, start_price, category_name FROM lots 
JOIN categories ON categories.id = lots.categories_id WHERE lots.categories_id = ? ORDER BY creation_date DESC LIMIT ' . $page_items . ' OFFSET ' . $offset.'';

        $stmt = db_get_prepare_stmt($link, $category_sql, [$category_id]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $lots_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
        var_dump(count($lots_list));
        $error = '';

        // создаем постой числовой  массив с числами от 1 до $pages_count
        $pages = range(1, $pages_count);

        if (count($lots_list) > 0) {
            $content = include_template('all-lots.php', compact('lots_list', 'categories_list', 'pages', 'pages_count', 'cur_page', 'category_id'));
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
