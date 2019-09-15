<?php
	class answerprocesser{
		private function guid(){
			if (function_exists('com_create_guid')){
				return com_create_guid();
			}else{
				mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
				$charid = strtoupper(md5(uniqid(rand(), true)));
				$hyphen = chr(45);// "-"
				$uuid = chr(123)// "{"
						.substr($charid, 0, 8).$hyphen
						.substr($charid, 8, 4).$hyphen
						.substr($charid,12, 4).$hyphen
						.substr($charid,16, 4).$hyphen
						.substr($charid,20,12)
						.chr(125);// "}"
				return $uuid;
			}
		}
		public function event_onPageLoad($args){
			
			if($args['PATH']!="/answer")return false;
			$u=$args['PDO']->query("users",array(array("key"=>"userkey","value"=>$args["COOKIE"]['userkey'])))[0];
			if(count($u)==0){
				//First submit
				if($args['GET']['ans']!=""){
				$uuid=$this->guid();
				setcookie("userkey",$uuid,time(NULL)+60*60*24*365);//保存100年
				$args['PDO']->insert("users",array("userkey"=>$uuid,"level"=>"1","name"=>$args['GET']['ans']));
				$return=array("code"=>1,"msg"=>"Success");
				}else{
					$return=array("code"=>-1,"msg"=>"名字不能为空!");
				}
			}else{
				$problem=$args['PDO']->query("gamedata",array(array("key"=>"levelid","value"=>$u['level'])))[0];
				$args['PDO']->update("gamedata",array(array("key"=>"levelid","value"=>$u['level'])),array("try"=>$u['try']+1));
				$errorMessages=array(
					"恭喜你答对了一个错误答案",
					"是不是看错了?好像不是这个答案",
					"答错了<br>第三答题委提醒您:<br>答题千万条,规范第一条.<br>答题不规范,正解两行泪",
					
					
				);
				$return=array("code"=>-1,"msg"=>$errorMessages[rand(0,count($errorMessages)-1)]);
				if($problem['answer']!="" and $problem['answer']===$args['GET']['ans']){
					$return=array("code"=>1,"msg"=>"Success");
					$args['PDO']->update("gamedata",array(array("key"=>"levelid","value"=>$u['level'])),array("pass"=>$u['pass']+1));
					$args['PDO']->update("users",array(array("key"=>"userkey","value"=>$args["COOKIE"]['userkey'])),array("level"=>$u['level']+1));
				}
				
			}
			
			echo json_encode($return);
			exit;
		}
	}