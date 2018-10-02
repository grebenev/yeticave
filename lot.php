<?php
$lot_show = $_GET['lot'];

//$lot_show = $_GET['lot']  ??  'error'

require_once('db.php');
require_once('functions.php');

// Запросы
$lot_data_sql = 'SELECT lots.id, creation_date, lot_name, description, image, start_price, end_date, lot_step, category_name FROM lots 
 JOIN categories ON categories.id = lots.categories_id WHERE lots.id = ' . $lot_show;

$bet_sql = 'SELECT bets.id, bet_date, amount, user_name FROM bets 
 JOIN users ON users.id = bets.users_id WHERE bets.id = ' . $lot_show;

//вызовы функции
$categories_list = get_data_db($link, $categories_sql, 'list');
$current_user = get_data_db($link, $user_sql, 'list');
$lot_data = get_data_db($link, $lot_data_sql, 'item');
$bet_list = get_data_db($link, $bet_sql, 'list');

if (!isset($lot_data)) {
    http_response_code(404);
    $content = include_template('error.php', ['error' => 'Ошибка 404. Запрашиваемый документ не найден!']);
} else {
    $lot_aside_content = include_template('lot-aside.php', compact('lot_data', 'bet_list'));
    $content = include_template('lot.php', compact('lot_aside_content', 'lot_data', 'categories_list'));

}


$layout_content = include_template('layout.php', compact('content', 'is_auth', 'current_user', 'categories_list', 'title'));
print($layout_content);
