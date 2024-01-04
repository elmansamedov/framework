<?php

namespace vendor\libs\meta;


use vendor\core\Router;

class Meta{

    protected static $title;
    protected static $description = null;
    protected static $keywords = null;
    protected static $head;
    protected static $text;
    protected static $canonical = false;
    protected static $opg = false;
    protected static $robots = false;
    protected static $robots_type = '';
    protected static $robots_type_val = '';

    public function setRobotsType($type, $val = "noindex,nofollow"){
        self::$robots_type = $type;
        self::$robots_type_val = $val;
    }

    public static function getRobotsType(){
        if(self::$robots_type !== '')
            return '<meta name="' . self::$robots_type . '" content="' . self::$robots_type_val . '"/>';
        return null;
    }

    /*
     * Вернуть title
     * */
    public static function getTitle(){
        return self::mb_ucfirst(self::$title);
    }

    public static function mb_ucfirst($str, $encoding='UTF-8'){
        $str = mb_ereg_replace('^[\ ]+', '', $str ?? '');
        $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).
            mb_substr($str, 1, mb_strlen($str), $encoding);
        return $str;
    }

    public static function getRobots(){
        if(self::$robots)
            return '<meta name="robots" content="noindex, nofollow">';
        else return null;
    }

    public static function setRobots($param = true){
        self::$robots = $param;
    }
    /*
     * Вернуть description
     * */
    public static function getDescription(){
        return self::$description;
    }
    /*
     * Вернуть keywords
     * */
    public static function getKeywords(){
        if(self::$keywords !== null)
            return '<meta name="keywords" content="' . self::$keywords . '">';
        else return null;
    }
    /*
     * Вернуть Заголовок в h1
     * */
    public static function getHead(){
        $head = '<h1></h1>';
    if(self::$head !== null) {
        $head = "<h1>" . self::$head . "</h1>";
     }
        return $head;
    }

    public static function getOpgImg(){
        if(self::$opg === false){
            return ssl . domain . '/public/images/logo.jpg';
        }else{
            return ssl . domain . self::$opg;
        }
    }

    public static function opgImg($img){
        self::$opg = $img;
    }
    /*
     * Вернуть фото opengraph
     * */
    public static function getCanoncial(){
        if(self::$canonical){
            return "<link rel='canonical' href='" . self::$canonical . "'>";
        }elseif(isset(Router::getRoute()['page'])){
            $canonAddress = preg_replace("#/page\_[0-9]+#", '', url);
            return "<link rel='canonical' href='$canonAddress'>";
        }return "<link rel='canonical' href='" . url . "'>";;
    }

    public static function setCanonicalManual($url){
        self::$canonical = $url;
    }

    public function setMetaManual($title, $head = null, $desc = null, $key = null){
        self::$title = $title;
        self::$description = $desc;
        self::$head = $head;
        self::$keywords = $key;
    }
}
