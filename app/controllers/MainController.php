<?php

namespace app\controllers;

use app\Controller;
class MainController extends Controller
{

    public function mainAction()
    {
        $a = 1;///Test переменная
        $this->set(compact('a'));
        $this->meta->setMetaManual('Title', 'H1', '', '');
        $this->view = 'main';
        $this->getView();
    }
}
