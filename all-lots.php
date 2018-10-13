<?php
    session_start();
    require_once('db.php');
    require_once('functions.php');

    //вызовы функции
    $categories_list = get_data_db($link, $categories_sql, 'list');
    $content = include_template('all-lots.php', compact('categories_list'));

    // ждем запроса с id категории
    $category_id = intval($_GET['category'] ?? 1);
    $cur_page = intval($_GET['page'] ?? 1); // если данных не пришло page = 1
    $page_items = 6; // кол-во лотов на странице


    if ($category_id) {
        // добываем нужное нам имя из таблицы категорий
        $category_result = mysqli_query($link, 'SELECT category_name FROM categories WHERE id ='.$category_id.'');
        $category_name = mysqli_fetch_assoc($category_result)['category_name'];
        $title = 'Все лоты в категории «'.$category_name.'»';

        $lots_sql= 'SELECT * FROM lots WHERE categories_id ='.$category_id.'';

        // проверяеам подключение функцией get_link_db и в $items_array выводим массив карточек(лотов)
        $link_result = get_link_db($link, $lots_sql);
        if ($link_result) {
            $items_array = mysqli_fetch_all($link_result);

            // функция пагинации /на входе массив карточек(лотов), кол-во карточек на странице, текущая страница из GET
            $pagination_result = get_pagination($items_array, $page_items, $cur_page);

            $pages_count = $pagination_result['pages_count'];
            $offset = $pagination_result['offset'];
            $pages = $pagination_result['pages'];
        }



        $category_sql = 'SELECT lots.id, creation_date, end_date, lot_name, image, start_price, category_name FROM lots 
JOIN categories ON categories.id = lots.categories_id WHERE lots.categories_id = ? ORDER BY creation_date DESC LIMIT ' . $page_items . ' OFFSET ' . $offset.'';

        $stmt = db_get_prepare_stmt($link, $category_sql, [$category_id]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $lots_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $error = '';


        if (count($lots_list) > 0) {
            $content = include_template('all-lots.php', compact('lots_list', 'categories_list', 'pages', 'pages_count', 'cur_page', 'category_id', 'category_name'));
        } else {
            $error = 'Нет запрашиваемого документа';
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
