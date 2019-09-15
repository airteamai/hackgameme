<?php

class packegeParser{
	public function parse($packegeName){
		if(!file_exists(APP_ROOT."/vendor/".$packegeName.'/packege.json')){
			return false;
		}
		return json_decode(file_get_contents(APP_ROOT."/vendor/".$packegeName.'/packege.json'));
	}
	public function parse_by_packegeId($packegeId){
		$tmp=explode(".",$packegeId);
		if($tmp[0]!="vendor"){
			return false;
		}
		if(count($tmp)<3){
			return false;
		}
		return self::parse($tmp[1]."/".$tmp[2]);
	}
	public function depends_check($packegeId,$nonCheck=array()){
		if(DEBUG)echo "Checking:".$packegeId.PHP_EOL;
		$packData=self::parse_by_packegeId($packegeId);
		if($packData===NULL or $packData===false){if(DEBUG)echo "Can not found the depends:[".$packegeId."],please check.".PHP_EOL;return false;}
		foreach($packData->depends as $v){
			if(in_array($v,$nonCheck)){continue;}
			if(DEBUG)echo "Checking:".$v.PHP_EOL;
			$tmp=self::parse_by_packegeId($v);
			if($tmp===NULL or $tmp===false){
				if(DEBUG)echo "Can not found the depends:[".$v."],please check.".PHP_EOL;
				return false;
			}
			//var_dump($tmp);
			foreach($tmp->depends as $s){
				if(!in_array($s,$nonCheck)){
					if(self::depends_check($s,array_merge($nonCheck,array($packegeId)))==false)return false;
				}
			}
		}
		return true;
	}
	public function detect_packeges(){
		$arr=scandir(APP_ROOT."/vendor/");
		$rsl=array();
		foreach($arr as $v){
			if($v=="." or $v=="..")continue;
			$arr1=scandir(APP_ROOT."/vendor/".$v."/");
			foreach($arr1 as $v1){
				if($v1=="." or $v1=="..")continue;
				$tmp=self::parse($v."/".$v1);
				if($tmp!==NULL and $tmp!==false){
					$rsl[]=$tmp->id;
				}
			}
		}
		
		return $rsl;
	}
}