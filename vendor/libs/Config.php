<?php
namespace vendor\libs;

use vendor\libs\compresed\CssCompresed;
use vendor\libs\compresed\JsCompresed;
use vendor\libs\email\Email;

class Config
{
    private static $cfg = null;


    /*
     * Экземпляр конфига + Запуск настроек
     * */
    public static function getConfig()
    {
        if(self::$cfg === null)
        {
            self::$cfg = new Config();
            self::$cfg->initConfig();
            self::$cfg->setErrors();
            self::$cfg->setDb();
            self::$cfg->setFiles();
            return self::$cfg;
        }
        return self::$cfg;
    }

    private function setDb(){
        require ROOT . "/vendor/libs/db/DataBase.php";
    }

    private function setFiles(){
        if(offline != 1){
            new CssCompresed();
            new JsCompresed();
        }
    }

    /*
     * Инициализация
     * */
    private function initConfig()
    {
        $configs = array_diff(scandir(ROOT . "/config"), ['..', '.']);
        foreach($configs as $keycfg => $valcfg)
        {
            $cfq = parse_ini_file(ROOT . "/config/{$valcfg}");
            foreach($cfq as $key => $val)
                define($key, $val);
        }
        define("url", ssl . domain . "/" . trim((string)$_SERVER['QUERY_STRING'], '/'));
        $http_acc = isset($_SERVER['HTTP_ACCEPT'])? $_SERVER['HTTP_ACCEPT'] : "";
        if(strpos((string)$http_acc, 'image/webp') !== false)
            define('WEBP', true);
        else
            define('WEBP', false);
        return true;
    }

    /*
     * Настройки ошибок
     * */
    private function setErrors()
    {
        if(offline)
        {
            error_reporting(-1);
        }
        else
        {
            error_reporting(E_ALL);
            ini_set("display_errors", true);
            set_error_handler(function($no, $str, $file, $line){
                $mess = $str. " in_file: " . $file . " on line: " . $line;
                Email::email("", adminEmail, "Ошибка", $mess);
            });
        }
    }
}