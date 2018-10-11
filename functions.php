<?php
    // установка локали
    date_default_timezone_set("Europe/Moscow");

    //запрос для вывода категорий по всем вложенным страницам
    $categories_sql = 'SELECT id, category_name, class_name FROM categories';

    // функция шаблонизации
    function include_template($name, $data) {
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

    // функция форматирования цены
    function transform_format($number) {
        $integer = ceil($number);
        if ($integer > 1000) {
            $integer = number_format($integer, 0, '', ' ');
        }
        return $integer .= ' ₽';
    }

    // функция времени существования лота
    function time_to_end($lot_time_end) {// текущий timestamp
        $time_now = time();
        $secs_to_end = strtotime($lot_time_end) - $time_now;
        // округление часов деленое на кол-во секунд в часе. 3600с - это 1 час
        $hours = floor($secs_to_end / 3600);

        //округление минут
        $minutes = floor(($secs_to_end % 3600) / 60);
        return $hours . ':' . $minutes . '';
    }

    // функция вывода ошибок
    function show_error(&$content, $error) {
        $content = include_template('error.php', ['error' => $error]);
    }

    // функция подключения к базе
    function get_link_db($link, $sql) {
        if (!$link) {
            $error = mysqli_connect_error();
            show_error($content, $error);
        } else {
            $result = mysqli_query($link, $sql);
            if (!$result) {
                $error = mysqli_error($link);
                show_error($content, $error);
            } else {
                return $result;
            }
        }
    }

    // функция получения данных из базы
    function get_data_db($link, $sql, $flag) {

        $link_result = get_link_db($link, $sql);

        if ($link_result) {
            if ($flag == 'list') {
                return $list = mysqli_fetch_all($link_result, MYSQLI_ASSOC);
            }
            if ($flag == 'item') {
                return $list = mysqli_fetch_assoc($link_result);
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
    function db_get_prepare_stmt($link, $sql, $data = []) {
        $stmt = mysqli_prepare($link, $sql);
        if ($stmt == false) {
            die("<pre>MYSQL ERROR:" . mysqli_error($link) . PHP_EQL . $sql . "</pre>");
        }

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

    // функция валидации
    function validate_register($data, $link) {
        $required = ['email', 'password', 'name', 'contacts'];
        $errors = [];

        foreach ($required as $key) {
            if (empty($data[$key])) {
                $errors[$key] = 'Это поле надо заполнить';
            }
        }
        if (!empty($data['email'])) {
            $email = mysqli_real_escape_string($link, $data['email']);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Email должен быть корректным';

            } else {
                $sql = "SELECT id FROM users WHERE email = '$email'";
                $res = mysqli_query($link, $sql);
                if (mysqli_num_rows($res) > 0) {
                    $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
                }
            }
        }
        if (!empty($_FILES['jpg_image']['name'])) {
            $tmp_name = $_FILES['jpg_image']['tmp_name'];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finfo_file($finfo, $tmp_name);

            if (!in_array($file_type, ['image/jpeg', 'image/png'])) {
                $errors['file'] = 'Загрузите картинку в формате JPEG или PNG';

            } else {
                $filename = uniqid() . '.jpg';
                move_uploaded_file($tmp_name, 'img/' . $filename);
            }
        } else {
            $filename = '';
        }

        if (empty($errors)) {

            return $filename;
        } else {
            return $errors;
        }
    }

    // функция регистрации
    function register($data, $link) {

        $validate = validate_register($data, $link);

        if (!is_array($validate)) {

            $data['path'] = $validate;
            $password = password_hash($data['password'], PASSWORD_DEFAULT);
            $sql = 'INSERT INTO users (registration_date, email, user_name, password, avatar, contacts)
            VALUES (NOW(), ?, ?, ?, ?, ?)';
            $stmt = db_get_prepare_stmt($link, $sql, [$data['email'], $data['name'], $password, $data['path'], $data['contacts']]);
            $res = mysqli_stmt_execute($stmt);

            return true;
        } else {
            return $validate;
        }
    }

    // функция форматирования времени для списка ставок
    function time_left($time_in) {
        $time = strtotime($time_in);

        $month = date('n', $time); // присваиваем месяц от timestamp
        $day = date('j', $time);
        $year = date('Y', $time);

        $hour = date('G', $time);
        $min = date('i', $time);
        $date = $day . '.' . $month . '.' . $year . '  в ' . $hour . ':' . $min;
        $diff = time() - $time; // от текущего времени отнимает время ставки (в секундах)

        if ($diff < 59) { // если разница меньше 59сек
            return $diff . " сек. назад"; //то возвращаем разницу с "сек. назад"
        } elseif ($diff / 60 > 1 and $diff / 60 < 59) { // если от 1 до 60 минут
            return round($diff / 60) . " мин. назад"; //то возвращаем разницу
        } elseif ($diff / 3600 > 1 and $diff / 3600 < 23) { // если от 1 до 23 часов
            return round($diff / 3600) . " час. назад";
        } else {
            return $date;
        }
    }

    // функция определения количества ставок от юзера
    function count_users_bets($bets_array, $user_id) {
        $count = 0;

        foreach ($bets_array as $key ) {

           if($key['users_id'] == $user_id) {
               $count++;
           }
        }
        return $count;
    }




