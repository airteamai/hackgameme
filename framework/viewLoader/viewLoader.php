<?php
	function requireView($viewName,$data=array()){
		$viewName=str_replace(".","",$viewName);
	if(!file_exists(APP_ROOT."/view/".$viewName.".html")){
		if(!$returnData)echo "[Framework][Fatal]Can not find view\"".$viewName."\"(".APP_ROOT."/view/".$viewName.".html".")";
		else return "[Framework][Fatal]Can not find view\"".$viewName."\"";
	}
	$view=file_get_contents(APP_ROOT."/view/".$viewName.".html");
	foreach($data as $k=>$v){$view=str_replace("{{".$k."}}",$v,$view);}
	echo $view;
	}