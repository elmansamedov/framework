<?php
namespace vendor\core;
use app\controllers\Error404;

class Pagination{

    private $count;//Колличество всего в базе
    private $limit = []; //Лимиты в массиве (0 - Для статики) 1- Для прокрутки Ajax
    private static $lim;
    private static $links;
    private $start; //Старт выбокри из базы
    private $obj; //Результативная база
    private $id = [];//id базы
    private $sstr;//Строка id с разделителем :
    private $view;//массив id что показываем сейчас :
    private $view_id;//массив id что показываем сейчас :
    private $base;
    /*
     * Стартуем
     * Принимаем параметры для сбора БД, указываем лимиты
     * */
    public function __construct($base, $where = null, $order = null, $id = [], $aj = null, $limit = [], $onlyId = null){
        if(empty($limit)) {
            $limit[0] = pagination;
            $limit[1] = pagination;
        }
        $this->base = $base;
        if($aj === null) {
            $where = $where !== ''? "WHERE $where": $where;
            $this->limit = $limit;
            self::$lim = $limit[0];
            $this->count = $this->getCount($base, $where, $id);
            $this->newPag();
            $this->start = $this->getLim();
            $this->id = \R::getAll("SELECT id FROM $base $where $order", $id);
            $this->newStringIn();
            if($onlyId === null)
                $this->obj = $this->setObj($base);
            else
                $this->obj = $this->view;
        }else{
            $this->id = $aj;
            $this->ajax();
        }
    }

    public function getSstr(){
        return $this->sstr;
    }

    public function getView(){
        return $this->view;
    }

    public function getObj(){
        return $this->obj;
    }

    private function getCount($base, $where, $id){
        return \R::count($base, $where, $id);
    }

    private function setObj($base){
        return \R::loadAll($base, $this->view);
    }

    private function ajax(){
        $limit = pagination;
        if(!empty($this->id))
        {
            $this->id = explode(':', $this->id);
            $i = 0;
            foreach($this->id as $k => $v){
                if($i <= $limit)
                    $this->view[] = $v;
                if($i > $limit){
                    $this->sstr .= $v . ':';
                }
                $i++;
            }
            $this->sstr = rtrim($this->sstr, ':');
            $this->obj = $this->setObj($this->base);
        }

    }

    public function rotate(){
        ksort($this->obj);
    }

    private function newStringIn(){
        $i = 0;
        foreach($this->id as $k){
            if($i >= $this->start) {
                if($i >= $this->limit[0]+$this->start)
                    $this->sstr .= $k['id'] . ':';
                if($this->start != 0 && $i >= $this->start && $i < $this->start+$this->limit[0]){
                    $this->view[] = $k['id'];
                    $this->view_id[]['id'] = $k['id'];
                }elseif($i < $this->limit[0]) {
                    $this->view[] = $k['id'];
                    $this->view_id[]['id'] = $k['id'];
                }
            }else
                if($i > $this->start) {
                    $this->view[] = $k['id'];
                    $this->view_id[]['id'] = $k['id'];
                }
            $i++;
        }
        if($this->sstr !== null)
            $this->sstr = rtrim($this->sstr, ':');
    }

    /*
     * Внесение ссылок пагинации в массив обьекта*/
    public function newPag(){
        if($this->count > $this->limit[0])self::$links = $this->count;
        $cols = $this->count/$this->limit[0];
        if(isset(Router::getRoute()['page']) && Router::getRoute()['page'] >= $cols){
            $contr= new Error404();
            $contr->main();
            exit;
        }
    }

    /*
     * возвращение для базы данных с какой страницы начинать поиск*/
    public function getLim(){
        if(isset(Router::getRoute()['page'])){
            return ((int)Router::getRoute()['page'] * $this->limit[0]);
        }else{
            return 0;
        }
    }

    /*
     * Вывод пагинации*/
    public static function getPag($dop = false){
        if (empty(self::$links)) {
            return false;
        } else {
            $page = isset(Router::getRoute()['page']) ? (int)Router::getRoute()['page'] : null;
            $r = url;
            if(isset(Router::getRoute()['page']))
                $r = preg_replace("#(page_[0-9]+)$#", '', $r);
            $r = trim($r, '/');
            $count = self::$links / self::$lim;
            $count = ceil($count);
            $notPage = $r;
            if ($dop) $notPage .= $dop;
            $link = $notPage . '/page_';
            $active = " class='not_active_pag' ";
            $prev = $page - 3;
            $next = $page + 3;
            $prTrue = false;
            $nextTrue = false;
            echo "<div class='row pagin just_center'>";
            if (0 == $page) $active = " class='active_pag' ";
            echo "<a $active href=" . $notPage . ">&#171;</a>";
            $active = " class='not_active_pag' ";
            for ($i = 0; $i < $count; $i++) {
                if ($i == 0) continue;
                if ($i == $page) $active = " class='active_pag' ";
                if ($prev > $i) {
                    if (!$prTrue) {
                        echo "<a $active href=" . $link . ceil($prev / 2) . ">...</a>";
                        $prTrue = true;
                    } else continue;
                } elseif ($next < $i) {
                    if (!$nextTrue) {
                        echo "<a $active href=" . $link . ceil((ceil($count) - ($count - $i) / 2) - 1) . ">...</a>";
                        $nextTrue = true;
                    } else continue;
                } else if ($i == 0) {
                    continue;
                } else if ($i == $count - 1) {
                    continue;
                } else echo "<a $active href=" . $link . $i . ">$i</a>";
                $active = " class='not_active_pag' ";
            }
            $end = $count--;
            echo "<a $active href=" . $link . $count . ">&#187;</a>";
            echo "</div>";
        }
    }
}