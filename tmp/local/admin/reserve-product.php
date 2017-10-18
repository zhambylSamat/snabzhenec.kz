<?php
	include('connection.php');
	$selected = isset($_GET['s']) ? $_GET['s'] : "";
	$parent = isset($_GET['p']) ? $_GET['p'] : "";
	if($selected!=""){
		(int)$_SESSION['category_counter'][$parent]--;
		array_push($_SESSION['selected'],$selected);
	}
?>