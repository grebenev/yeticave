<?php
$is_auth = rand(0, 1);

date_default_timezone_set("Europe/Moscow");

// timestamp для полуночи
$timestamp_midnight = strtotime('tomorrow');

// текущий timestap
$secs_to_midnight = $timestamp_midnight - time();

// округление часов деленое на кол-во секунд в часе.
$hours = floor($secs_to_midnight / 3600);

// округление минут
$minutes = floor(($secs_to_midnight % 3600) / 60);


require_once('functions.php');
require_once('data.php');

$content = include_template('index.php', compact('items_list', 'categories_list', 'hours', 'minutes'));
$layout_content = include_template('layout.php', compact('content', 'is_auth', 'user_name', 'user_avatar', 'categories_list', 'title'));


print( $layout_content);
?>
