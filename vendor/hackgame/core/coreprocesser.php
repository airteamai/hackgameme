<?php
	class coreprocesser{
		public function event_onPageLoad($args){
			if(($args['PATH']!="/answer") and (substr($args['PATH'],0,strlen("/admin"))!="/admin") and (!isset($args["COOKIE"]['userkey']) or $args["COOKIE"]['userkey']=="" or count($args['PDO']->query("users",array(array("key"=>"userkey","value"=>$args["COOKIE"]['userkey']))))==0)){
				requireView("public",array(
					"lv"=>"0",
					"game_title"=>"输入名字",
					"data"=>"勇者,输入你的名字,开始挑战旅程",
					"next_pass"=>"??",
					"pass_round"=>"100%",
					"next_pass"=>"??",
					"this_pass"=>"??",
					"DisenableGoAjax"=>"Yes"
				));
				exit;
			}
			
		}
	}