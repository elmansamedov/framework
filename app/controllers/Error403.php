<?php

namespace app\controllers;

use app\Controller;

class Error403 extends \app\Controller
{
    public function __construct()
    {
        Controller::$error = true;
        parent::__construct();
    }

    public function main(){
        http_response_code(403);
        $this->view = "/main/error403";
        $this->meta->setMetaManual("403 доступ запрещен", "Доступ запрещен");
        $this->getView();
        exit;
    }
}