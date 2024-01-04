<?php

function debug($data){
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

function debugReturn($arr){
    return "<pre>" . print_r($arr, true) . "</pre>";
}

/**
 * @param $string
 * @return string
 * Строка в ссылку
 */
function mbToLink($string)
{
    return strtolower(translit(str_replace(" ", "-", preg_replace("#[ ]{2,}#", " ", $string))));
}

/*
 * Перевод на английский
 * */
function translit($str) {
    $rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
    $lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
    return str_replace($rus, $lat, $str);
}

// определение мобильного устройства
function check_mobile_device() {
    $mobile_agent_array = array('ipad', 'iphone', 'android', 'pocket', 'palm', 'windows ce', 'windowsce', 'cellphone', 'opera mobi', 'ipod', 'small', 'sharp', 'sonyericsson', 'symbian', 'opera mini', 'nokia', 'htc_', 'samsung', 'motorola', 'smartphone', 'blackberry', 'playstation portable', 'tablet browser');
    $agent = (string)strtolower($_SERVER['HTTP_USER_AGENT']);
    foreach ($mobile_agent_array as $value) {
        if (strpos($agent, $value) !== false) return true;
    }
    return false;
}

function generate_password($number){
    $arr = array('a','b','c','d','e','f',
        'g','h','i','j','k','l',
        'm','n','o','p','r','s',
        't','u','v','x','y','z',
        'A','B','C','D','E','F',
        'G','H','I','J','K','L',
        'M','N','O','P','R','S',
        'T','U','V','X','Y','Z',
        '1','2','3','4','5','6',
        '7','8','9','0');
    $pass = "";
    for($i = 0; $i < $number; $i++){
        $index = rand(0, count($arr) - 1);
        $pass .= $arr[$index];
    }
    return $pass;
}

/**
 * @param $array
 * @param $assocName
 * @return array
 * Получить обычный массив из ассоциативного
 */
function assocToIntegerArray($array, $assocName):array
{
    $arr = [];
    foreach($array as $a)
    {
        $arr[] = $a[$assocName];
    }
    return $arr;
}
