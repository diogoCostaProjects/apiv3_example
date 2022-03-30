<?php

$url = $_GET['url'];
$url = explode('/', $url);

if(empty($url[0])): $controller = 'Paginas'; endif;

$controller = ucfirst($url[0]);
$controllerurl = ucfirst($url[0]) . 'Controller';
$action = $url[1];
$param = $url[2];
$param_2 = $url[3];
$param_3 = $url[4];
$param_4 = $url[5];

require_once 'controllers/' . $controller . "/" . $controller . '.controller' . '.php';

$action = str_replace('-', '_', $action);
$acao = New $controllerurl();
$acao->$action($param, $param_2, $param_3, $param_4);
