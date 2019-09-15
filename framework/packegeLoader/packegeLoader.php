<?php
	function load_packege($packegeName){
		$packegeInfo=packegeParser::parse_by_packegeId($packegeName);
		if($packegeInfo===null or $packegeInfo===false)return false;
		foreach($packegeInfo->autoload as $v){
			load_packege_file($packegeName,explode(".",$v)[1].".php");
		}
	}
	function load_packege_file($packegeName,$file){
		$packegeInfo=packegeParser::parse_by_packegeId($packegeName);
		if($packegeInfo===null or $packegeInfo===false)return false;
		$tmp=explode(".",$packegeName);
		if(!file_exists(APP_ROOT."/vendor/".$tmp[1]."/".$tmp[2]."/".$file.".php")){
			return false;
		}
		require_once(APP_ROOT."/vendor/".$tmp[1]."/".$tmp[2]."/".$file.".php");
	}