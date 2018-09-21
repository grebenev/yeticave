<?php
$is_auth = rand(0, 1);

$user_name = 'Константин'; // укажите здесь ваше имя
$user_avatar = 'img/user.jpg';
$title = "YetiCave";

//  массив категорий
$categories_list = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];

// массив товаров
$items_list = [
    [
        'name' => '2014 Rossignol District Snowboard',
        'category' => 'Доски и лыжи',
        'price' => '10999',
        'image_url' => 'img/lot-1.jpg'
    ],
    [
        'name' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => 'Доски и лыжи',
        'price' => '159999',
        'image_url' => 'img/lot-2.jpg'
    ],
    [
        'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => 'Крепления',
        'price' => '8000',
        'image_url' => 'img/lot-3.jpg'
    ],
    [
        'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => 'Ботинки',
        'price' => '10999',
        'image_url' => 'img/lot-4.jpg'
    ],
    [
        'name' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => 'Одежда',
        'price' => '7500',
        'image_url' => 'img/lot-5.jpg'
    ],
    [
        'name' => 'Маска Oakley Canopy',
        'category' => 'Разное',
        'price' => '5400',
        'image_url' => 'img/lot-6.jpg'
    ]
];


date_default_timezone_set("Europe/Moscow");

// timestamp для полуночи
$timestamp_midnight = strtotime('tomorrow');

// текущий timestap
$secs_to_midnight = $timestamp_midnight - time();

// округление часов деленое на кол-во секунд в часе.
$hours = floor($secs_to_midnight / 3600);
// округление минут
$minutes = floor(($secs_to_midnight % 3600) / 60);



//функция
function transform_format ($number) {
  $integer = ceil($number);
  if ($integer > 1000) {
    $integer = number_format($integer, 0, '', ' ');
  }
  return $integer .= ' ₽';
};

require_once('functions.php');

$content = include_template('index.php', compact('items_list', 'categories_list', 'hours', 'minutes'));
$layout_content = include_template('layout.php', compact('content', 'is_auth', 'user_name', 'user_avatar', 'categories_list', 'title'));


print( $layout_content);
?>
