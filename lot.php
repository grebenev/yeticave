<?php
    session_start();
    $lot_show = intval($_GET['lot']);

    $cookie_name = "save_id";
    $url_id = $lot_show;
    $expire = strtotime("+3 minutes");
    $path = "/";

    if (isset($_COOKIE['save_id'])) {
        $url_id = $lot_show;
    }
    setcookie($cookie_name, $url_id, $expire, $path);

    require_once('db.php');
    require_once('functions.php');

    // Запросы
    $lot_data_sql = 'SELECT lots.id, creation_date, lot_name, description, image, start_price, end_date, lot_step, category_name FROM lots 
    JOIN categories ON categories.id = lots.categories_id WHERE lots.id = ' . $lot_show;

    $bet_sql = 'SELECT bets.id, bet_date, amount, user_name FROM bets 
    JOIN users ON users.id = bets.users_id WHERE lots_id = ' . $lot_show;

    //вызовы функции
    $categories_list = get_data_db($link, $categories_sql, 'list');
    $lot_data = get_data_db($link, $lot_data_sql, 'item');
    $bet_list = get_data_db($link, $bet_sql, 'list');
    $error = '';

    // выделяем из возвращенного массива цену и шаг
    $start_price = $lot_data['start_price'];
    $step = $lot_data['lot_step'];

    // проверяем данные в массиве POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $cost = $_POST['cost'];

        if(empty($cost)){
            $error_bet = 'Надо заполнить';

        } else {//если не поустое
            $error_bet = '';

            if (!filter_var($cost, FILTER_VALIDATE_INT)) {
                $error_bet = 'Это не число';

            } else {//если число

                if($cost >= $start_price + $step) { //проверяем что больше чем цена + шаг

                    $user_id = $_SESSION['user']['id'];
                    $bet_sql = 'INSERT INTO bets (bet_date, amount, users_id, lots_id) VALUES (NOW(), ?, ' .$user_id.', 
                '.$lot_show.')';

                    //подготавливаем выражение и выполняем
                    $stmt = db_get_prepare_stmt($link, $bet_sql, [$cost]);
                    $res = mysqli_stmt_execute($stmt);

                } else {
                    $error_bet = 'Увеличте ставку';
                }


            }
        }


    }



    if (!isset($lot_data)) {
        http_response_code(404);
        $error = 'Ошибка 404';

    } else {

        $content = include_template('lot.php', compact('lot_data', 'bet_list', 'lot_data', 'categories_list', 'error_bet'));

    }
    if($error) {
        $content = include_template('error.php', compact('error'));
    }

    $title = $lot_data['lot_name'];

    $layout_content = include_template('layout.php', compact('content', 'categories_list', 'title'));
    print($layout_content);
