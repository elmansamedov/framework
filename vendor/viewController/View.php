<?php
namespace vendor\viewController;

use vendor\core\Router;

class View{
    /*
     * Текущий Вид*/
    public $view;
    /*
     * Текущий шаблон*/
    public $layout;

    public function __construct($layout = '', $view =''){
        if($layout === false){
            $this->layout = false;
        }else{
            $this->layout = $layout ?: 'main';
        }
        $this->view = $view;
    }

    public function render($vars = []){
		if(!empty($vars) && $vars !== '')
			extract($vars);
        $file_view = ROOT . "/app/view/blocks/" . Router::getRoute()['controller'] . "/{$this->view}.php";
        ob_start();
        if(is_file($file_view)){
            require $file_view;
        }else{
            echo "Шаблон $file_view не найден";
        }
        $content = ob_get_clean();
        if(false !== $this->layout) {
            $file_layout = ROOT . "/app/view/layout/{$this->layout}.php";
			ob_start();
            if (is_file($file_layout)) {
                require $file_layout;
            } else {
                echo "Шаблон не найден $file_layout";
            }
			$html = ob_get_clean();
			//$html = str_replace(array("\r\n", "\r", "\n", "\t"), ' ', preg_replace('/\s{2,}/', ' ', preg_replace('#>\s+<#', '><', $html)));
			echo $html;
        }
    }
}
