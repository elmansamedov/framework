<?php
namespace vendor\libs;


class Json{

    public static function result($result){
        echo json_encode(['error' => false, 'result' => $result]);
    }

    public static function resultImage($result, $images){
        echo json_encode(['error' => false, 'result' => $result, 'images' => $images]);
    }

    public static function html($result, $htmlObj, $htmlData){
        echo json_encode(['error' => false, 'result' => $result, 'htm' => $htmlObj, 'htmlData' => $htmlData]);
    }

    public static function resultDisableButton($result)
    {
        echo json_encode(['error' => false, 'result' => $result, "disablebutton" => true]);
    }

    public static function resultEditeButton($result, $button){
        echo json_encode(['error' => false, 'result' => $result, 'editeButton' => $button]);
    }

    public static function img($result, $img){
        echo json_encode(['error' => false, 'result' => $result, 'img' => $img]);
    }

    public static function textImg($result, $text, $img){
        echo json_encode(['error' => false, 'result' => $result, 'text' => $text, 'img' => $img]);
    }

    public static function text($result, $text){
        echo json_encode(['error' => false, 'result' => $result, 'text' => $text]);
    }

    public static function error($result){
        echo json_encode(['error' => true, 'result' => $result]);
        exit;
    }
    public static function errorImg($result, $imgname){
        echo json_encode(['error' => true, 'result' => $result, 'images' => $imgname]);
        exit;
    }

    public static function hide($result, $hide){
        echo json_encode(['error' => false, 'result' => $result, 'hide' => $hide]);
    }

    public static function refer($result, $refer){
        echo json_encode(['error' => false, 'result' => $result, 'refer' => $refer]);
    }

    public static function closeId($result, $close){
        echo json_encode(['error' => false, 'result' => $result, 'remid' => $close]);
    }

    public static function closeClass($result, $close){
        echo json_encode(['error' => false, 'result' => $result, 'remclass' => $close]);
    }
}