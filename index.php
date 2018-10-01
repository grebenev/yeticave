<?php
// $is_auth = rand(0, 1);
$title = "YetiCave";

date_default_timezone_set("Europe/Moscow");
require_once('db.php');
require_once('functions.php');



if (!$link) {

    $content = include_template('error.php', ['error' => mysqli_connect_error()]);

} else {
    // Создание запрос на получение списка категорий
    $sql = "SELECT id, category_name, class_name FROM categories";

    //Выполнение запроса присвоение результата переменной
    $result = mysqli_query($link, $sql);

    // Если запрос выполнен успешно
    if ($result) {
        // Получаем массив
        $categories_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        // Нет - получаем текст ошибки
        $content = include_template('error.php', ['error' => mysqli_error($link)]);
    }
    // Запрос на получение имени и аватара пользователя
    $sql = "SELECT id, user_name, avatar FROM users WHERE id = 1";
    $user_result = mysqli_query($link, $sql);

    if ($user_result) {
        $current_user = mysqli_fetch_assoc($user_result);
    } else {
        $content = include_template('error.php', ['error' => mysqli_error($link)]);
    }
    // Запрос на получение лотов
    $sql = "SELECT lots.id, creation_date, end_date, lot_name, image, start_price, category_name FROM lots
    JOIN categories ON categories.id = lots.categories_id ORDER BY creation_date DESC";
    $lots_result = mysqli_query($link, $sql);

    if ($lots_result) {
        $lots_list = mysqli_fetch_all($lots_result, MYSQLI_ASSOC);
    }
}

$content = include_template('index.php', compact('lots_list', 'categories_list', 'hours', 'minutes'));
$layout_content = include_template('layout.php', compact('content', 'is_auth', 'current_user', 'categories_list', 'title'));

print($layout_content);

?>
