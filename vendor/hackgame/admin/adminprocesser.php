<?php
	
	class adminprocesser{
		public function event_onPageLoad($args){
			
			session_start();
			if($args['PATH']=="/admin"){
				echo "跳转中...需要JavaScript.<script>window.location='admin/'</script>";
			}
			if(substr($args['PATH'],0,strlen("/admin"))!="/admin")return false;
			if(substr($args["PATH"],-1,1)=="/")$args['PATH'].="index";
			$procName="process".str_replace("/","_",substr($args['PATH'],strlen("/admin"),strlen($args['PATH'])));
			if(method_exists("adminprocesser",$procName)){
				
				$this->$procName($args);
				
			}
		}
		public function process_index($args){
			if($_SESSION['admin']!="1"){
				echo "跳转中...需要JavaScript.<script>window.location='login'</script>";
			}
			requireView("admin_index",array());
		}
		public function process_login($args){
			requireView("admin_login",array());
		}
	}