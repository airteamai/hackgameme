<?php
error_reporting(E_ERROR);
define("DEBUG",false);
define("APP_ROOT",__DIR__);
require_once APP_ROOT."/framework/loader.php";
//var_dump(packegeParser::parse_by_packegeId("vendor.hackgame.core"));
global $pdo;
$pdo=new SPDO;
$pdo->open("localhost",3306,"root","","hackgame");
call_event("onPageLoad",array("PDO"=>$pdo,"GET"=>$_GET,"POST"=>$_POST,"SERVER"=>$_SERVER,"PATH"=>$_SERVER['PATH_INFO'],"COOKIE"=>$_COOKIE));
//var_dump($pdo->update("test",array(array("key"=>"name","value"=>"jason")),array("name"=>"jason12435364")));