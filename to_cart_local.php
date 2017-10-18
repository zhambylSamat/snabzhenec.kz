<?php
	session_start();
	$data = explode("!", $_POST['cart_data']);
	$_SESSION['cart'] = json_decode($data[0], true);
	$_SESSION['products_name'] = json_decode($data[1], true);
	$_SESSION['forOne'] = json_decode($data[2], true);
	$_SESSION['addon'] = json_decode($data[3], true);
	print_r($_SESSION['cart']);
	// $_SESSION['cart_data'] = $_POST['cart_data'];
	// echo $_POST['cart_data'];
	// print_r($_POST['l_cart']);
?>