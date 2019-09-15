<?php
function ssort($oba, $obb){
        if($oba['level'] < $obb['level']){
            return 1;
        }
		return -1;
}
function call_event($eventName,$args){
	global $eventList;
	foreach($eventList[$eventName] as $v){
		if(DEBUG)echo "Call:".$v['processer']."(From:".$v['fromPackege'].")".PHP_EOL;
		$processer=explode(".",$v['processer']);
		//var_dump($v['fromPackege']);
		load_packege($v['fromPackege']);
		load_packege_file($v['fromPackege'],$processer[3]);
		$eventProcessClass=new $processer[3];
		$xname=$processer[4];
		$eventProcessClass->$xname($args);
	}
}
global $eventList;
$eventList=array();
$packeges=packegeParser::detect_packeges();
foreach($packeges as $v){
	$data=packegeParser::parse_by_packegeId($v);
	$events=(json_decode(json_encode($data->eventListener),true));
	foreach($events as $k=>$v2){
		foreach($v2 as $v1){
			if(!isset($eventList[$k]))$eventList[$k]=array();
			$eventList[$k][]=array_merge($v1,array("fromPackege"=>$data->id));
		}
	}
}
foreach($eventList as $k=>$v){
	usort($eventList[$k],'ssort');
}
