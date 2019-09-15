<?php
	class pageprocesser{
		public function event_onPageLoad($args){
			$u=$args['PDO']->query("users",array(array("key"=>"userkey","value"=>$args["COOKIE"]['userkey'])))[0];
			$p=$args['PDO']->query("gamedata",array(array("key"=>"levelid","value"=>$u['level'])))[0];
			$pass=count($args['PDO']->query("users",array(array("key"=>"level","operation"=>">","value"=>$u['level']))));
			$total=count($args['PDO']->query("users",array(array("key"=>"level","operation"=>">","value"=>$u['level']-1))));
			if(substr($args['PATH'],1,strlen($args['PATH'])-1)=="".(substr($args['PATH'],1,strlen($args['PATH'])-1)+0)){
				//页面第一次加载
				if($p['levelId']==0){
					$u['level']="恭喜通关!";
					$p['title']="恭喜通关!";
					$p['context']="恭喜您通过!<br>您的UserKey:".$args["COOKIE"]['userkey'];
				}
				//var_dump($p);
				requireView("public",array(
					"lv"=>$u['level'],
					"game_title"=>$p['title'],
					"data"=>$p['context'],
					"pass_round"=>($pass*100/$total)."%",
					"next_pass"=>$pass,
					"this_pass"=>$total
				));
				exit;
			}else if($args['PATH']=="/problem"){
				//异步页面加载
				if($p['levelId']==0){
					$u['level']="恭喜通关!";
					$p['title']="恭喜通关!";
					$p['context']="恭喜您通过!<br>您的UserKey:".$args["COOKIE"]['userkey'];
				}
				$d=array(
					'title'=>$p['title'],
					'tongji'=>"LV ".$u['level']." 共有 ".$pass." 名挑战者通过了,通过率为 ".($pass*100/$total)."%"."(".$pass."/".$total.")",
					'lv'=>"LV ".$u['level'],
					'problem'=>$p['context'],
					'level'=>$u['level']
				);
				echo json_encode($d);
			}
			
		}
	}