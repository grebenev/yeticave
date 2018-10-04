<?php

    function include_template($name, $data)
    {
        $name = 'templates/' . $name;
        $result = '';
        if (!file_exists($name)) {
            return $result;
        }
        ob_start();
        extract($data);
        require $name;
        $result = ob_get_clean();
        return $result;
    }


    function transform_format($number)
    {
        $integer = ceil($number);
        if ($integer > 1000) {
            $integer = number_format($integer, 0, '', ' ');
        }
        return $integer .= ' ₽';
    }

    ;

    function time_to_end($lot_time_create, $lot_time_end)
    {// текущий timestamp
        $secs_to_midnight = strtotime($lot_time_end) - strtotime($lot_time_create);
// округление часов деленое на кол-во секунд в часе.
        $hours = floor($secs_to_midnight / 3600);
// округление минут
        $minutes = floor(($secs_to_midnight % 3600) / 60);
        return $hours . ' часов ' . $minutes . ' минут ';
    }

// запросы
    $categories_sql = 'SELECT id, category_name, class_name FROM categories';
    $user_sql = 'SELECT id, user_name, avatar FROM users WHERE id = 1';
    $lots_list_sql = 'SELECT lots.id, creation_date, end_date, lot_name, image, start_price, category_name FROM lots JOIN categories ON categories.id = lots.categories_id ORDER BY creation_date DESC';

// функция вывода ошибок
    function show_error(&$content, $error)
    {
        $content = include_template('error.php', ['error' => $error]);
    }

// функция получения данных из базы
    function get_data_db($link, $sql, $flag)
    {

        if (!$link) {
            $error = mysqli_connect_error();
            show_error($content, $error);
        } else {
            $result = mysqli_query($link, $sql);

            if ($result) {
                if ($flag == 'list') {
                    return $list = mysqli_fetch_all($result, MYSQLI_ASSOC);
                }
                if ($flag == 'item') {
                    return $list = mysqli_fetch_assoc($result);
                }

            } else {
                $error = mysqli_error($link);
                show_error($content, $error);
            }
        }
    }

    /**
     * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
     *
     * @param $link mysqli Ресурс соединения
     * @param $sql string SQL запрос с плейсхолдерами вместо значений
     * @param array $data Данные для вставки на место плейсхолдеров
     *
     * @return mysqli_stmt Подготовленное выражение
     */
    function db_get_prepare_stmt($link, $sql, $data = [])
    {
        $stmt = mysqli_prepare($link, $sql);

        if ($data) {
            $types = '';
            $stmt_data = [];

            foreach ($data as $value) {
                $type = null;

                if (is_int($value)) {
                    $type = 'i';
                } else if (is_string($value)) {
                    $type = 's';
                } else if (is_double($value)) {
                    $type = 'd';
                }

                if ($type) {
                    $types .= $type;
                    $stmt_data[] = $value;
                }
            }

            $values = array_merge([$stmt, $types], $stmt_data);

            $func = 'mysqli_stmt_bind_param';
            $func(...$values);
        }

        return $stmt;

    }




