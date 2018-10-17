<?php
    require_once 'vendor/autoload.php';

    // Создаем объект класса Swift_SmtpTransport и передаем туда, через вызовы методов имя и пароль
    $transport = new Swift_SmtpTransport("phpdemo.ru", 25);
    $transport->setUsername("keks@phpdemo.ru");
    $transport->setPassword("htmlacademy");

    //содаем объект Swift_Mailer и пердадим в него переменную с объектом Swift_SmtpTransport
    $mailer = new Swift_Mailer($transport);

    //создаем объект Swift_Plugins_Loggers_ArrayLogger логгер что-бы иметь подробную информацию о процессе отправки
    $logger = new Swift_Plugins_Loggers_ArrayLogger();
    $mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

    // этим запросом получаем сводную таблицу о выйграном лоте пользовтелем

    $sql = 'SELECT lots_id, lot_name, users.id as user_id, user_name, email FROM bets JOIN users ON users.id = users_id 
JOIN lots ON lots.id = lots_id WHERE bets.id IN (SELECT  MAX(Id) FROM  bets WHERE bets.lots_id IN
(SELECT lots.id FROM lots WHERE winners_id IS NULL AND end_date < NOW()) GROUP BY lots_id)';

    // создаем массив с результатом из запроса sql
    $result = get_data_db($link, $sql, 'list');


    if (count($result) > 0) {

        foreach ($result as $item) {

            // создаем объект Swift_Message
            $message = new Swift_Message();
            // Тема сообщения
            $message->setSubject("Ваша ставка победила");
            // Отправитель в виде массива email -> имя
            $message->setFrom(['keks@phpdemo.ru' => 'Yeticave']);
            // получатели
            $message->setBcc([$item['email'] => $item['user_name']]);


            $msg_content = include_template('email.php',
                ['user' => $item['user_name'], 'lot' => $item['lots_id'], 'lot_name' => $item['lot_name']]);
            $message->setBody($msg_content, 'text/html');


            $update_sql = 'UPDATE lots SET winners_id = ' . $item['user_id'] . ' WHERE id = ' . $item['lots_id'] . '';
            $update_result = mysqli_query($link, $update_sql);

            if ($update_result) {
                $send_result = $mailer->send($message);
            }
        }


        if ($send_result) {
            print("Рассылка успешно отправлена");
        } else {
            print("Не удалось отправить рассылку: " . $logger->dump());
        }

    }

