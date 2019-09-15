<?php
	class SPDO{
		private $sqlh;
		public function open($host="localhost",$port=3306,$user="root",$pass="",$database="test"){
			$sqlh=mysqli_connect($host,$user,$pass,$database,$port);
			if(!$sqlh){
				echo "Failed to connect to mysql server.";
			}
			$this->sqlh=$sqlh;
		}
		public function query_raw($sql,$rtype=MYSQLI_BOTH,$outOnErr=false){
			$res=mysqli_query($this->sqlh,$sql);
			if($res!==TRUE and $res!==FALSE and $res!==null){
				$rs=mysqli_fetch_all($res,$rtype); 
				mysqli_free_result($res);
				return $rs;
			}
			if($res===false and $outOnErr==true){
				echo "Mysql Error:".mysqli_error($sqlh);
			}
			return $res;
		}
		public function escape($data){
			return mysqli_real_escape_string($this->sqlh,$data);
		}
		public function query($table,$factor,$other="",$rtype=MYSQLI_BOTH,$outOnErr=false){
			foreach($factor as $k=>$v){
				$factor[$k]['value']=$this->escape($factor[$k]['value']);
			}
			$whereSQL="true";
			foreach($factor as $v){
				if(!isset($v['relation']))$v['relation']="AND";
				if(!isset($v['operation']))$v['operation']="=";
				$whereSQL.=" ".$v['relation']."`".$v['key']."`".$v['operation']."'".$v['value']."'";
			}
			return $this->query_raw("SELECT * FROM ".$table." WHERE ".$whereSQL." ".$other,$rtype,$outOnErr);
		}
		public function insert($table,$values,$other=""){
			foreach($values as $k=>$v){
				$values[$k]=$this->escape($values[$k]);
			}
			$keyTable="";
			$valueTable="";
			foreach($values as $k=>$v){
				$keyTable.="`$k`,";
				$valueTable.="'$v',";
			}
			$keyTable=substr($keyTable,0,strlen($keyTable)-1);
			$valueTable=substr($valueTable,0,strlen($valueTable)-1);
			return $this->query_raw("INSERT INTO `$table` ($keyTable) VALUES ($valueTable) ".$other);
		}
		public function delete($table,$factor,$other="",$outOnErr=false){
			foreach($factor as $k=>$v){
				$factor[$k]['value']=$this->escape($factor[$k]['value']);
			}
			$whereSQL="true";
			foreach($factor as $v){
				if(!isset($v['relation']))$v['relation']="AND";
				if(!isset($v['operation']))$v['operation']="=";
				$whereSQL.=" ".$v['relation']."`".$v['key']."`".$v['operation']."'".$v['value']."'";
			}
			return $this->query_raw("DELETE FROM ".$table." WHERE ".$whereSQL." ".$other,MYSQLI_BOTH,$outOnErr);
		}
		public function update($table,$factor,$values,$other="",$rtype=MYSQLI_BOTH,$outOnErr=false){
			foreach($values as $k=>$v){
				$values[$k]=$this->escape($values[$k]);
			}
			foreach($factor as $k=>$v){
				$factor[$k]['value']=$this->escape($factor[$k]['value']);
			}
			$whereSQL="true";
			foreach($factor as $v){
				if(!isset($v['relation']))$v['relation']="AND";
				if(!isset($v['operation']))$v['operation']="=";
				$whereSQL.=" ".$v['relation']."`".$v['key']."`".$v['operation']."'".$v['value']."'";
			}
			$data="";
			foreach($values as $k=>$v){
				$data.=" `$k` = '$v' ,";
			}
			$data=substr($data,0,strlen($data)-1);
			//return ("UPDATE ".$table." SET ".$data." WHERE ".$whereSQL." ".$other);
			return $this->query_raw("UPDATE ".$table." SET ".$data." WHERE ".$whereSQL." ".$other,$rtype,$outOnErr);
		}
	}