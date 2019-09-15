<?php
	$modules=array(
		"packegeParser/parser.php"
		,"packegeParser/eventMounter.php"
		,"packegeLoader/packegeLoader.php"
		,"DatabaseInterface/PDO.php"
		,"viewLoader/viewLoader.php"
	);
	foreach($modules as $v){
		require_once(__DIR__ ."/" . $v);
	}