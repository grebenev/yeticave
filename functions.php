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
