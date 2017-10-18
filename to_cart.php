<?php
	include('connection.php');
	if(!isset($_GET['data_num'])){
		header('location:catalog.php');
	}
	if(!isset($_SESSION['cart'])){
		$_SESSION['cart'] = array();
		$_SESSION['products_name'] = array();
		$_SESSION['forOne'] = array();
		$_SESSION['addon'] = array();
	}
	if(isset($_GET['added']) && $_GET['added']=='false'){
		// $_SESSION['product_cart'][$_GET['data_num']]=$_GET['data_name']; 
		array_push($_SESSION['cart'], $_GET['data_num']);
		array_push($_SESSION['products_name'], $_GET['data_name']);
		array_push($_SESSION['forOne'], $_GET['price']);
		array_push($_SESSION['addon'], $_GET['addon']);
		// print_r($_SESSION['cart']);
		echo json_encode($_SESSION['cart']);
		echo "!";
		echo json_encode($_SESSION['products_name']);
		echo "!";
		echo json_encode($_SESSION['forOne']);
		echo "!";
		echo json_encode($_SESSION['addon']);
	}
	else if(isset($_GET['added']) && $_GET['added']=='true'){
		$index = array_search($_GET['data_num'], $_SESSION['cart']);
		unset($_SESSION['cart'][$index]);
		unset($_SESSION['products_name'][$index]);
		unset($_SESSION['forOne'][$index]);
		unset($_SESSION['addon'][$index]);
		// unset($_SESSION['product_cart'][$_GET['data_num']]);
		// print_r($_SESSION['cart']);
		echo json_encode($_SESSION['cart']);
		echo "!";
		echo json_encode($_SESSION['products_name']);
		echo "!";
		echo json_encode($_SESSION['forOne']);
		echo "!";
		echo json_encode($_SESSION['addon']);
	}
?>