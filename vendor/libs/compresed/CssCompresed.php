<?php
namespace vendor\libs\compresed;

class CssCompresed{

    private $newFile = [];
    private $ver = [];

    public function __construct(){
        $this->issetFile();
        if(!empty($this->newFile))
            $this->newFileCompressed();
    }

    public function issetFile(){
        $consts = get_defined_constants();
        krsort($consts);
        $mass = [];
        foreach ($consts as $k => $v){
            if(strpos( (string)$k, '_css') !== false)
                $mass[$k] = $v;
        }
        foreach($mass as $k => $v){
            $trFile = str_replace('_css', '', $k);
            $file = ROOT . '/public/css/v_' . $v;
            if(!file_exists($file . '/' . $trFile . '.css')) {
                $this->newFile[$trFile] = $file;
                $this->ver[$trFile] = $v;
            }
        }
    }

    private function newFileCompressed(){
        foreach($this->newFile as $k => $v){
            $content_file = file_get_contents(ROOT . '/public/css/' . $k . '.css');
            if(!$content_file)continue;
            $content_file = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content_file);
            $content_file = str_replace(array("\r\n", "\r", "\n", "\t"), ' ', $content_file);
            $content_file = preg_replace('/ {2,}/', ' ', $content_file);
            if(!file_exists($v))mkdir(ROOT . '/public/css/v_' . $this->ver[$k]);
            $css_file = fopen ($v . '/' . $k . '.css', "w+");
            fwrite($css_file, $content_file);
            fclose($css_file);
        }
    }
}