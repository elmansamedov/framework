<?php
namespace app;

use app\controllers\Error403;
use app\controllers\Error404;
use vendor\libs\meta\Meta;

abstract class Controller  extends \vendor\viewController\Controller
{
    public static $error = false;
    protected $meta;
    public $layout = 'main';
    private static $css = [];
    private static $js = [];
    protected $jsOtherFiles = [];
    public static $css_preload = [];
    public static $js_preload = [];
    public static $setPin = false;

    public function __construct()
    {
        $this->meta = new Meta();
        $this->mainScriptNjs();
    }

    private function mainScriptNjs(){
        if(offline == 1){
            $this->setScript('jquery-3.1.1.min');
        }else{
            $this->setOtherScript("//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js");
        }
        if(!empty($this->jsOtherFiles))
            foreach ($this->jsOtherFiles as $jsfile => $vjslink)
                $this->setOtherScript($vjslink);
        $this->setScript('wow');
        $this->setScript('main');
        $this->setStyle('wow');
        $this->setStyle('main');
    }

    private function setFileStyleAndJs($file, $type = 'js', $absolute = false){
        if($absolute){
            self::$$type[] = $file;
            return null;
        }
        $pre = $type . "_preload";
        if(!in_array($file, self::$$type)){
            if(offline == 1) {
                self::$$type[] = '/public/' . $type . '/' . $file . '.' . $type;
                if($file == 'main')
                    self::$$pre[] = '/public/' . $type . '/' . $file . '.' . $type;
            }elseif(defined($file . '_' . $type)) {
                self::$$type[] = '/public/' . $type . '/v_' . constant($file . '_' . $type) . '/' . $file . '.' . $type;
                if($file == 'main')
                    self::$$pre[] = '/public/' . $type . '/v_' . constant($file . '_' . $type) . '/' . $file . '.' . $type;
            }else {
                self::$$type[] = '/public/' . $type . '/' . $file . '.' . $type;
                if($file == 'main')
                    self::$$pre[] = '/public/' . $type . '/' . $file . '.' . $type;
            }
        }
        return null;
    }

    public static function getCss(){
        return self::$css;
    }

    public static function getJs(){
        return self::$js;
    }

    protected function setOtherStyle($file){
        $this->setFileStyleAndJs($file, 'css', true);
    }

    protected function setOtherScript($file){
        $this->setFileStyleAndJs($file, 'js', true);
    }

    protected function setStyle($file){
        $this->setFileStyleAndJs($file, 'css');
    }

    protected function setScript($file){
        $this->setFileStyleAndJs($file, 'js');
    }

    protected function error404(){
        $err = new Error404();
        $err->main();
        exit;
    }

    protected function error403(){
        $err = new Error403();
        $err->main();
        exit;
    }

}
