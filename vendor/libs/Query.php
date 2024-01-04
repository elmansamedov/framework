<?php

namespace vendor\libs;

use vendor\core\Router;

class Query
{

    private $query = [];
    private $file = [];

    /**
     * @param $query
     * @param $file
     */
    public function __construct($query, $file){
        if(isset($query)){
            foreach($query as $k => $v){
                $this->query[$k] = trim((string)$v);
            }
        }
        $this->file = $file;
    }

    /**
     * @return void
     * Инициализация POST запроса
     */
    public static function init(){
        if(!empty($_POST) || !empty($_FILES)){
            Router::addRouteInQuery($_SERVER['QUERY_STRING']);
            if(isset($_POST['query'])) {
                $query = $_POST['query'];
                $files = !empty($_FILES)? $_FILES : [];
                unset($_POST['query']);
                unset($_POST['undefined']);
                $obQuey = new Query(query: $_POST, file: $files);
                if(method_exists($obQuey, $query)){
                    $obQuey->$query();
                }
                exit;
            }

        }
    }
}
