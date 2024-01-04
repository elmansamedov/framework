<?php

session_start();

require "../vendor/core/Init.php";

Init::initialize()->main();

use vendor\core\Router;

Router::add("^(/(?P<controller>[a-z-0-9]+)?(/(?P<action>[a-z-0-9]+))?(/page_(?P<page>[0-9]+))?)?$");


\vendor\libs\Query::init();

Router::dispatch($_SERVER['QUERY_STRING']);
