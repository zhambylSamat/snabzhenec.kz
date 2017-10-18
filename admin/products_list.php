<?php
	include('connection.php');
	if(!isset($_SESSION['admin_username'])){
		header('location:signin.php');
	}
	$data = array();
	try {
		$stmt = $conn->prepare("SELECT c.category_num cNum, c.category_name cName, p.product_num pNum, p.product_name pName, p.product_description pDescription, p.product_img pImg, p.price price FROM category c, product p WHERE p.category_num = c.category_num order by cName asc");
	    $stmt->execute();
	   	$result = $stmt->fetchAll();
	   	foreach ($result as $value) {
	   		$data[$value['cNum']]['name'] = $value['cName'];
	   		$data[$value['cNum']]['products'][$value['pNum']]['name'] = $value['pName']; 
	   		$data[$value['cNum']]['products'][$value['pNum']]['description'] = $value['pDescription']; 
	   		$data[$value['cNum']]['products'][$value['pNum']]['img'] = $value['pImg']; 
	   		$data[$value['cNum']]['products'][$value['pNum']]['price'] = $value['price']; 
	   	}
	   	$_SESSION['catalog_data'] = $data;
	} catch (PDOException $e) {
		echo "Error : ".$e->getMessage()." !!!";
	}
?>
<div class='container'>
	<div class='row'>
		<div class='col-md-12 col-sm-12 col-xs-12'>
			<center><h3 style='display: inline-block;'>Каталог</h3>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="search" id='search-product' placeholder="Поиск" class='form-control' style='width: 20%; display: inline-block;'></center>
		</div>
	</div>
	<div id='catalog'>
		<?php include("search_product.php");?>
	</div>
</div>
