<?php
require_once ROOT . '/vendor/libs/db/rb-mysql.php';

\R::setup( "mysql:host=" . db_host . "; dbname=" . db_base, db_user, db_password);
$freeze = offline == 1? false : true;
R::freeze($freeze); //true для сервера false для разработки изменение структуры редбином
//R::fancyDebug(true);///debud запросов
