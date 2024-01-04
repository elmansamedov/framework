<?php

use vendor\libs\Config;


class Init
{
    private static $init = null;

    public static function initialize()
    {
        if (self::$init === null) self::$init = new Init();
        return self::$init;
    }

    /*
     * Инициализация программы
     * */
    public function main()
    {
        define("ROOT", $_SERVER['DOCUMENT_ROOT']);
        spl_autoload_register(function ($class) {
            $file = ROOT . '/' . str_replace('\\', '/', $class) . '.php';
            if (is_file($file)) {
                require_once $file;
            }
        });
        require ROOT . "/vendor/libs/functions.php";
        Config::getConfig();
        $protocol = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
        if($protocol != str_replace('://', '', ssl))
        {
            header("Location:" . trim(ssl . domain . $_SERVER['QUERY_STRING'], '/'));
            exit;
        }
    }
}