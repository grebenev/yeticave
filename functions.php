<?php
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


function transform_format ($number) {
    $integer = ceil($number);
    if ($integer > 1000) {
        $integer = number_format($integer, 0, '', ' ');
    }
    return $integer .= ' ₽';
};

function time_to_end ($lot_time_create, $lot_time_end) {
// текущий timestamp
    $secs_to_midnight = strtotime($lot_time_end) - strtotime($lot_time_create);

// округление часов деленое на кол-во секунд в часе.
    $hours = floor($secs_to_midnight / 3600);

// округление минут
    $minutes = floor(($secs_to_midnight % 3600) / 60);

    return $hours .' часов ' . $minutes . ' минут ';
}
