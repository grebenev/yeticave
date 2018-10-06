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
        if($stmt == false) {
            die("<pre>MYSQL ERROR:" .mysqli_error($link) . PHP_EQL . $sql . "</pre>");
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


    function check_file($file_name) {

        if (!empty($_FILES[$file_name]['name'])) {
            $tmp_name = $_FILES[$file_name]['tmp_name'];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finfo_file($finfo, $tmp_name);


            if ($file_type !== "image/jpeg") {
                $err['no_file'] = 'Загрузите картинку в формате JPEG';
                return $err;

            } else {
                $filename = uniqid() . '.jpg';
                move_uploaded_file($tmp_name, 'img/' . $filename);
                return $filename;
            }

        } else {
            $err['no_file'] = 'Нет файла';
            return $err;
        }
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
        if(!empty($data['email'])) {
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

//        if (!empty($_FILES['jpg_image']['name'])) {
//            $tmp_name = $_FILES['jpg_image']['tmp_name'];
//
//            $finfo = finfo_open(FILEINFO_MIME_TYPE);
//            $file_type = finfo_file($finfo, $tmp_name);
//
//
//            if ($file_type !== "image/jpeg") {
//                $errors['file'] = 'Загрузите картинку в формате JPEG';
//
//            } else {
//                $filename = uniqid() . '.jpg';
//                move_uploaded_file($tmp_name, 'img/' . $filename);
//                $data['path'] = $filename;
//            }
//        }
//        if (check_file('jpg_image')) {
//
//        }
        $result = check_file('jpg_image');

        var_dump($result);

        if(is_array($result)) {
            $errors = $result;
        }

        if (empty($errors)) {
            return true;
        } else {
            return $errors;
        }
    }
    // функция регистрации
    function register($data, $link) {

        $validate = validate_register($data, $link);

        if ($validate === true) {
            // Регистрируй
            $password = password_hash($data['password'], PASSWORD_DEFAULT);
            $sql = 'INSERT INTO users (registration_date, email, user_name, password, avatar, contacts)
            VALUES (NOW(), ?, ?, ?, ?, ?)';
            $stmt = db_get_prepare_stmt($link, $sql, [$data['email'], $data['name'], $password, $data['path'], $data['contacts']]);
            $res = mysqli_stmt_execute($stmt);


        } else {
            return $validate;
        }
    }





