<?php
    session_start();
    if(isset($_GET['lot']) and !empty($_GET['lot'])) {
        $lot_show = intval($_GET['lot']);
    }

    require_once('db.php');
    require_once('functions.php');

    // Запросы
    $lot_data_sql = 'SELECT lots.id, creation_date, lot_name, description, image, start_price, end_date, lot_step, users_id, category_name FROM 
lots 
    JOIN categories ON categories.id = lots.categories_id WHERE lots.id = ' . $lot_show;

    $bet_sql = 'SELECT bets.id, bet_date, amount, users_id, user_name  FROM bets 
    JOIN users ON users.id = bets.users_id WHERE lots_id = ' . $lot_show . '  ORDER BY bet_date DESC';

    $max_price_sql = 'SELECT MAX(amount) FROM bets WHERE lots_id = ' . $lot_show . '';

    //вызовы функции список категорий, данные для лота, список ставок, максимальная ставка
    $categories_list = get_data_db($link, $categories_sql, 'list');
    $lot_data = get_data_db($link, $lot_data_sql, 'item');
    $bet_list = get_data_db($link, $bet_sql, 'list');
    $max_price = get_data_db($link, $max_price_sql, 'item');
    $error = '';

    // узнаем id залогиненого пользователя
    if (isset($_SESSION['user']) and !empty($_SESSION['user'])) {
        $user_id = $_SESSION['user']['id'];
        // вызов функции посчета ставок по залогиненому id в отдельном лоте
        $total_count = count_users_bets($bet_list, $user_id);
    }

    // выделяем из возвращенного массива цену и шаг
    if(isset($lot_data)) {
        $start_price = $lot_data['start_price'];
        $step = $lot_data['lot_step'];
        $max = $max_price['MAX(amount)'];
    }

    $current_price = 0;

    if ($max > $start_price) {
        $current_price = $max;
    } else {
        $current_price = $start_price;
    }

    if(isset($_SESSION['user']['id']) and !empty($_SESSION['user']['id'])) {
        $user_id = $_SESSION['user']['id'];
    }


    // проверяем данные в массиве POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['cost']) and !empty($_POST['cost'])) {
            $cost = $_POST['cost'];
        }

        if (empty($cost)) {
            $error_bet = 'Надо заполнить';

        } else {//если не пуст
            $error_bet = '';
            if (!filter_var($cost, FILTER_VALIDATE_INT)) {
                $error_bet = 'Это не число';
                $flag = 0;
            } else {
                $flag = 1;
            }

            if ($cost < $current_price + $step and $flag) { //проверяем что больше чем цена + шаг
                $error_bet = 'Увеличте ставку';
            }
        }


        if (!$error_bet) {
            $bet_sql = 'INSERT INTO bets (bet_date, amount, users_id, lots_id) VALUES (NOW(), ?, ' . $user_id . ', 
                ' . $lot_show . ')';

            //подготавливаем выражение и выполняем
            $stmt = db_get_prepare_stmt($link, $bet_sql, [$cost]);
            $result = mysqli_stmt_execute($stmt);

            if ($result) {
                header("Location: lot.php?lot=" . $lot_show);

            } else {
                $content = include_template('error.php', ['error' => mysqli_error($link)]);
            }
        }

    }


    if (!isset($lot_data)) {
        http_response_code(404);
        $error = 'Ошибка 404';

    } else {

        $content = include_template('lot.php',
            compact('lot_data', 'bet_list', 'categories_list', 'error_bet', 'total_count', 'current_price'));

    }
    if ($error) {
        $content = include_template('error.php', compact('error'));
    }

    if(isset($lot_data['lot_name'])) {
        $title = $lot_data['lot_name'];
    }

    $layout_content = include_template('layout.php', compact('content', 'categories_list', 'title'));
    print($layout_content);
