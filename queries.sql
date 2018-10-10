-- Добавление данных

USE yeticave;
INSERT INTO categories SET category_name = 'Доски и лыжи', class_name = 'boards';
INSERT INTO categories SET category_name = 'Крепления', class_name = 'attachment';
INSERT INTO categories SET category_name = 'Ботинки', class_name = 'boots';
INSERT INTO categories SET category_name = 'Одежда', class_name = 'clothing';
INSERT INTO categories SET category_name = 'Инструменты', class_name = 'tools';
INSERT INTO categories SET category_name = 'Разное', class_name = 'other';

INSERT INTO lots SET creation_date = '2018-09-24 21:10:01',
lot_name = '2014 Rossignol District Snowboard',
description = 'Описание лота',
image = 'lot-1.jpg',
start_price = 10999,
end_date = '2018-09-25',
lot_step = 5,
users_id = 1,
winners_id = 1,
categories_id = 1;

INSERT INTO lots SET creation_date = '2018-09-22 21:10:01',
lot_name = 'DC Ply Mens 2016/2017 Snowboard',
description = 'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчкоми четкими дугами. Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется, просто посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла равнодушным.',
image = 'lot-2.jpg',
start_price = 159999,
end_date = '2018-09-25',
lot_step = 3,
users_id = 2,
winners_id = 2,
categories_id = 1;

INSERT INTO lots SET creation_date = '2018-09-22 21:10:01',
lot_name = 'Крепления Union Contact Pro 2015 года размер L/XL',
description = 'Описание лота3',
image = 'lot-3.jpg',
start_price = 8000,
end_date = '2018-09-25',
lot_step = 3,
users_id = 2,
winners_id = 2,
categories_id = 2;

INSERT INTO lots SET creation_date = '2018-09-22 21:10:01',
lot_name = 'Ботинки для сноуборда DC Mutiny Charocal',
description = 'Описание лота4',
image = 'lot-4.jpg',
start_price = 10999,
end_date = '2018-09-25',
lot_step = 3,
users_id = 2,
winners_id = 2,
categories_id = 3;

INSERT INTO lots SET creation_date = '2018-09-22 21:10:01',
lot_name = 'Куртка для сноуборда DC Mutiny Charocal',
description = 'Описание лота5',
image = 'lot-5.jpg',
start_price = 7500,
end_date = '2018-09-25',
lot_step = 3,
users_id = 2,
winners_id = 2,
categories_id = 4;

INSERT INTO lots SET creation_date = '2018-09-22 21:10:01',
lot_name = 'Маска Oakley Canopy',
description = 'Описание лота6',
image = 'lot-6.jpg',
start_price = 5400,
end_date = '2018-09-25',
lot_step = 3,
users_id = 2,
winners_id = 2,
categories_id = 6;

INSERT INTO bets SET bet_date = '2018-09-22 21:10:01',
amount = 1000,
users_id = 1,
lots_id = 1;

INSERT INTO bets SET bet_date = '2018-09-23 23:10:01',
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

SELECT lots.id, lots.lot_name, lots.description, lots.start_price, categories.category_name, lots.image, COUNT(bets.id) AS count_bets FROM lots
JOIN bets ON bets.lots_id = lots.id
JOIN categories ON lots.categories_id = categories.id
GROUP BY lots.id ORDER BY creation_date DESC;


--  показать лот по его id. Получите также название категории, к которой принадлежит лот

SELECT creation_date, lot_name, description, image, start_price, end_date, lot_step, category_name FROM lots
 JOIN categories ON categories.id = lots.id WHERE lots.id = 1;


--  обновить название лота по его идентификатору

UPDATE lots SET lot_name = 'Название лота 3' WHERE id = 1;


--  получить список самых свежих ставок для лота по его идентификатору

SELECT * FROM bets WHERE lots_id = 1 ORDER BY bet_date DESC;
