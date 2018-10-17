<?php
    session_start();

    require_once('db.php');
    require_once('functions.php');

    $title = 'Мои ставки';

    if (isset($_SESSION['user']['id']) and !empty($_SESSION['user']['id'])) {
        $user_id = $_SESSION['user']['id'];
    }


    //вызовы функции
    $categories_list = get_data_db($link, $categories_sql, 'list');

    $bet_sql = 'SELECT bets.id, bet_date, end_date, amount, lots.id as lot_id, winners_id, lot_name, image, category_name, contacts  FROM bets
JOIN lots ON lots.id = bets.lots_id
JOIN categories ON categories.id = categories_id
JOIN users ON users.id = bets.users_id
WHERE bets.users_id = ' . $user_id . '  ORDER BY bet_date ASC';

    $bet_list = get_data_db($link, $bet_sql, 'list');
//    var_dump($bet_list);

    $content = include_template('my-lots.php', compact('categories_list', 'bet_list', 'user_id'));
    $layout_content = include_template('layout.php', compact('content', 'categories_list', 'title'));
    print($layout_content);
