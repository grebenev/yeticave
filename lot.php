<?php
$lot_show = $_GET['lot']  ??  'error';

require_once('functions.php');
require_once('db.php');


if (!$link) {

    $content = include_template('error.php', ['error' => mysqli_connect_error()]);

} else {
    // Создание запрос на получение лота
    $sql = 'SELECT id, creation_date, lot_name, description, image, start_price, end_date, lot_step FROM lots WHERE lots.id = '.$lot_show.'';

    //Выполнение запроса присвоение результата переменной
    $result = mysqli_query($link, $sql);

    // Если запрос выполнен успешно
    if ($result) {

        $lot_data = mysqli_fetch_assoc($result);
    } else {
        // Нет - получаем текст ошибки
        $content = include_template('error.php', ['error' => mysqli_error($link)]);
    }
}

$content = include_template('lot.php', compact('lot_data'));

$layout_content = include_template('layout.php', compact('content', 'is_auth', 'current_user', 'categories_list', 'title'));

print($layout_content);
