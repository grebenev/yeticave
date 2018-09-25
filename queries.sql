-- Добавление данных

USE yeticave;
INSERT INTO categories SET category_name = 'Доски и лыжи';
INSERT INTO categories SET category_name = 'Крепления';
INSERT INTO categories SET category_name = 'Ботинки';
INSERT INTO categories SET category_name = 'Одежда';
INSERT INTO categories SET category_name = 'Инструменты';
INSERT INTO categories SET category_name = 'Разное';

INSERT INTO lots SET creation_date = '2018-09-24 21:10:01',
lot_name = 'Имя лота',
description = 'Описание лота',
image = 'imgage-1.jpg',
start_price = 100,
end_date = '2018-09-24',
lot_step = 5,
users_id = 1,
winners_id = 1,
categories_id = 1;

INSERT INTO lots SET creation_date = '2018-09-22 21:10:01',
lot_name = 'Имя лота2',
description = 'Описание лота2',
image = 'imgage-2.jpg',
start_price = 200,
end_date = '2018-09-25',
lot_step = 3,
users_id = 2,
winners_id = 2,
categories_id = 2;

INSERT INTO bet SET bet_date = '2018-09-22 21:10:01',
amount = 1000,
users_id = 1,
lots_id = 1;

INSERT INTO bet SET bet_date = '2018-09-23 23:10:01',
amount = 2000,
users_id = 2,
lots_id = 2;

INSERT INTO users SET registration_date = '2018-09-22 21:10:01',
email = '3304040@gmail.com',
user_name = 'Константин',
password = '123456',
avatar = 'avatar.jpg',
contacts = 'тел 79183304040';

INSERT INTO users SET registration_date = '2018-09-22 21:10:02',
email = 'serjio@gmail.com',
user_name = 'Сергей',
password = '7654321',
avatar = 'avatar-2.jpg',
contacts = 'тел 7918123456';

-- SQL- запросы

-- получить все категории

SELECT * FROM categories;

/*  получить самые новые, открытые лоты. Каждый лот должен включать название,
 стартовую цену, ссылку на изображение, цену, количество ставок, название категории */






--  показать лот по его id. Получите также название категории, к которой принадлежит лот

SELECT creation_date, lot_name, description, image, start_price, end_date, lot_step, category_name FROM lots
 JOIN categories ON categories.id = lots.id WHERE lots.id = 1;

--  обновить название лота по его идентификатору

UPDATE lots SET lot_name = 'Название лота 3' WHERE id = 1;

--  получить список самых свежих ставок для лота по его идентификатору

SELECT * FROM bet WHERE lots_id = 1 ORDER BY bet_date ASC;
