<?php
namespace vendor\viewController;

abstract class Controller{
    public $view;
    public $layout;
    public $vars = [];

    public function getView(){
        $vObj = new View($this->layout, $this->view);
        $vObj->render($this->vars);
    }

    public function set($vars){
        $this->vars = $vars;
    }

}
