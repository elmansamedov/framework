<?php
namespace app\controllers;

use app\Controller;

class Error404 extends Controller
{
    public function __construct()
    {
        Controller::$error = true;
        parent::__construct();
    }

    public function main(){
        http_response_code(404);
        echo "404";
        $this->view = "/main/error404";
        $this->meta->setMetaManual("404 такой страницы несуществует", "Страница не найдена");
        $this->getView();
        exit;
    }
}