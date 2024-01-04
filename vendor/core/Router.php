<?php
namespace vendor\core;

use app\controllers\Error404;

class Router
{


    private static $routes = [];
    private static $route = [];

    protected static function matchRoute($url){
        foreach(self::$routes as $pattern => $route){
            if(preg_match("#$pattern#", (string)$url, $matches)){
                foreach($matches as $k => $v){
                    if(is_string($k)){
                        $route[$k] = $v;
                    }
                }
                if(isset($route['page'])){
                    $route['page'] = str_replace('page_', '', $route['page']);
                    if($route['page'] == 0) {
                        $error = new Error404();
                        $error->main();
                        exit;
                    }
                    if($route['page'] === ''){
                        unset($route['page']);
                    }elseif(!intval($route['page']))
                        unset($route['page']);
                }
                if(!isset($route['controller']) || $route['controller'] === '')
                    $route['controller'] = 'main';
                if(!isset($route['action']) || $route['action'] === '')
                    $route['action'] = 'main';

                $route['controller'] = self::lowerCamelCase($route['controller']);
                self::$route = $route;
//                debug($route);

                return true;
            }
        }
        return false;
    }

    public static function addRouteInQuery($url){
        return self::matchRoute(self::removeQueryString($url));
    }

    /*
     * Если в url присутствует main перенаправлять
     * */
    private static function quoteMainOtherString()
    {
        if(url !== "") {
            if (strpos(url, "main") !== false) {
                header("HTTP/1.1 301 Moved Permanently");
                header("Location:" . trim((string)str_replace("main", "", url)));
                exit;
            }
            if (strpos(url, "index.php") !== false) {
                header("HTTP/1.1 301 Moved Permanently");
                header("Location:" . trim((string)str_replace("index.php", "", url)));
                exit;
            }
        }
    }

    /*
     * Определение страницы для показа*/
    public static function dispatch($url)
    {
        $url = self::removeQueryString($url);
        if (self::matchRoute($url)) {
            self::quoteMainOtherString();
            $controller = 'app\controllers\\' . self::upperCamelCase(self::$route['controller']) . 'Controller';

            if(class_exists($controller)){

                $cObj = new $controller(self::$route);
                $action = self::lowerCamelCase(self::$route['action'] . 'Action');
                $methods = get_class_methods($cObj);
                $met = false;
                foreach($methods as $key => $meth){
                    if($meth == $action)
                        $met = true;
                }
                if(method_exists($cObj, $action) && $met){
                    $cObj->$action();
                }else{
                    $c = new Error404();
                    $c->main();
                    exit;
                }
            }else {
                $c = new Error404();
                $c->main();
                exit;
            }
        }else{
            self::$route['controller'] = "Error404";
            $c = new Error404();
            $c->main();
            exit;
        }
    }

    /*
     * Добавление маршрутов
     * */
    public static function add($regexp, $route =[])
    {
        self::$routes[$regexp] = $route;
    }

    /*
     * Таблица маршрутов*/
    public static function getRoutes()
    {
        return self::$routes;
    }

    /*
     * Текущие маршруты*/
    public static function getRoute()
    {
        return self::$route;
    }

    /*
     * приведене имени контроллера в надлежащий вид*/
    public static function upperCamelCase($name){
        return $name = str_replace(' ', '', ucwords(str_replace('-', ' ', $name)));
    }
    /*
     * приведене имени метода в надлежащий вид*/
    public static function lowerCamelCase($name){
        return lcfirst(self::upperCamelCase($name));
    }

    public static function mbstrtoLoverCmCase($name){
        $str = str_replace(' ', '-', lcfirst(str_replace(' ', '-', translit(str_replace(' - ', '-', mb_strtolower($name))))));
        return preg_replace('#[^-a-z0-9]#', '', $str);
    }

    protected static function removeQueryString($url){
        if($url){
            $params = explode('&', $url, 2);
            if(false === strpos($params[0], '=')){
                return rtrim($params[0], '/');
            }else{
                return '';
            }
        }
    }
}
