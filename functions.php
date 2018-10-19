<?php
    // установка локальной зоны
    date_default_timezone_set("Europe/Moscow");

    //запрос для вывода категорий по всем вложенным страницам
    $categories_sql = 'SELECT id, category_name, class_name FROM categories';

    /**
     * Вставляет переменные в шаблон
     * @param integer $name название шаблона
     * @param array $data данные для шаблона
     * @return string  возвращает щаблон с данными
     */
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

    /**
     * Фрпматирование, округление, деление по разрядам чисел, добавление знака ₽
     * @param integer $number Входное число
     * @return string  отформатированная сторка
     */
    function transform_format($number)
    {
        $integer = ceil($number);
        if ($integer > 1000) {
            $integer = number_format($integer, 0, '', ' ');
        }
        return $integer .= ' ₽';
    }

    // функция времени существования лота
    /**
     * Вычисление сколько осталось времени до входящей даты
     * @param datetime $lot_time_end Входное время
     * @return string  возвращаемое время
     */
    function time_to_end($lot_time_end)
    {
        if (strtotime($lot_time_end) < time()) {
            return '00:00';
        }
        $dt_end = new DateTime($lot_time_end);
        $remain = $dt_end->diff(new DateTime());
        if ($remain->d > 0) {
            return $remain->d . ' дней';
        } else {
            if ($remain->h > 0) {
                return $remain->h . ' часов';
            } else {
                if ($remain->i > 0) {
                    return $remain->i . ' мин.';
                } else {
                    return $remain->s . ' сек.';
                }
            }
        }
    }

    // функция вывода ошибок
    /**
     * Вывод ошибок
     * @param  string $content контент
     * @param string $error ошибки
     */
    function show_error(&$content, $error)
    {
        $content = include_template('error.php', ['error' => $error]);
    }


    /**
     * Подключение к БД
     * @param  mysqli $link Ресурс соединения
     * @param string $sql SQL - запрос
     * @return object  Объект соединения
     */
    function get_link_db($link, $sql)
    {
        if (!isset($link)) {
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


    /**
     * Получения данных из БД
     * @param  mysqli $link Ресурс соединения
     * @param string $sql SQL - запрос
     * @param array $flag Ключи: list - маиив данных, item - одна запись из таблицы
     * @return array  Список или одну запись
     */
    function get_data_db($link, $sql, $flag)
    {

        $link_result = get_link_db($link, $sql);

        if ($flag === 'list') {
            return $list = mysqli_fetch_all($link_result, MYSQLI_ASSOC);
        }
        if ($flag === 'item') {
            return $list = mysqli_fetch_assoc($link_result);
        }
    }

    /**
     * Создает подготовленное выражение на основе готового SQL запроса и переданных данных     *
     * @param  mysqli $link Ресурс соединения
     * @param string $sql SQL запрос с плейсхолдерами вместо значений
     * @param array $data Данные для вставки на место плейсхолдеров     *
     * @return mysqli_stmt Подготовленное выражение
     */
    function db_get_prepare_stmt($link, $sql, $data = [])
    {
        $stmt = mysqli_prepare($link, $sql);
        if ($stmt === false) {
            die("<pre>MYSQL ERROR:" . mysqli_error($link) . PHP_EQL . $sql . "</pre>");
        }

        if ($data) {
            $types = '';
            $stmt_data = [];

            foreach ($data as $value) {
                $type = null;

                if (is_int($value)) {
                    $type = 'i';
                } else {
                    if (is_string($value)) {
                        $type = 's';
                    } else {
                        if (is_double($value)) {
                            $type = 'd';
                        }
                    }
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

    /**
     * Валидации
     * @param array $data массив данных для валидации
     * @param  mysqli $link Ресурс соединения
     * @return string  имя файла или ошибку валидации
     */
    function validate_register($data, $link)
    {
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

    /**
     * Регистрации
     * @param array $data массив данных для валидации
     * @param  mysqli $link Ресурс соединения
     * @return string  true или ошибку валидации
     */
    function register($data, $link)
    {

        $validate = validate_register($data, $link);

        if (!is_array($validate)) {

            $data['path'] = $validate;
            $password = password_hash($data['password'], PASSWORD_DEFAULT);
            $sql = 'INSERT INTO users (registration_date, email, user_name, password, avatar, contacts)
            VALUES (NOW(), ?, ?, ?, ?, ?)';
            $stmt = db_get_prepare_stmt($link, $sql,
                [$data['email'], $data['name'], $password, $data['path'], $data['contacts']]);
            $res = mysqli_stmt_execute($stmt);

            return true;
        } else {
            return $validate;
        }
    }

    /**
     * Формат времени для списка ставок
     * @param datetime $time_in время в любом формате
     * @return string  Форматированное время
     */

    function time_left($time_in)
    {
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

    /**
     * Определяет количество ставок пользователя
     * @param array $bets_array массив ставок
     * @param  integer $user_id ID пользователя
     * @return integer  количество ставок
     */
    function count_users_bets($bets_array, $user_id)
    {
        $count = 0;

        foreach ($bets_array as $key) {

            if ($key['users_id'] === $user_id) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Разрезает массив
     * @param array $array массив лотов
     * @param  integer $items_on_page Колтчество лотов на странице
     * @param  integer $current_page Текущая страница
     * @return array  Часть массива
     */
    function get_array_slice($array, $items_on_page, $current_page)
    {
        $offset = ($current_page - 1) * $items_on_page;
        $result = array_slice($array, $offset, $items_on_page);
        return $result;
    }

    /**
     * Проверяет пользователя и его пароль
     * @param  mysqli $link Ресурс соединения
     * @param  integer $login Полученный логин
     * @param  integer $password Полученный пароль
     * @return array  Ошибку проверки
     */
    function user_verify ($link, $login, $password) {

        $email = mysqli_real_escape_string($link, $login);
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($link, $sql);
        $error =[];

        $user = $result ? mysqli_fetch_array($result, MYSQLI_ASSOC) : null;

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;
            } else {
                $error['password'] = 'Неверный пароль';
              return $error;
            }
        } else {
            $error['email'] = 'Такой пользователь не найден';
           return $error;
        }
    }





