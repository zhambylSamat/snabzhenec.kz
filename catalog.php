<?php
	include_once('connection.php');
	if(!isset($_SESSION['user_num'])){
		header('location:signin.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
	<title><?php echo "";?></title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/bootstrap-theme.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
	<link rel="stylesheet/less" type="text/css" href="css/style.less">
</head>
<body>
<section>
	<nav class="navbar navbar-default">
		  <div class="container">
		    <div class="navbar-header">
			    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
			        <span class="sr-only">Toggle navigation</span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			    	<span class="icon-bar"></span>
			    </button>
			    <center><h4 style="font-size: 1em;">Ответственное лицо: <b><?php echo $_SESSION['username'];?></b></h4></center>
			    <center><h4 style="font-size: 1em;">Имя пользователя: <b><?php echo $_SESSION['owner_name'];?></b></h4></center>
			    <center><h4 style="font-size: 1em;">Поставщик: <b><?php echo $_SESSION['name_surname']." ".$_SESSION['phone'];?></b></h4></center>
				<center><a href="logout.php" class='btn btn-sm btn-warning hidden-sm hidden-md hidden-lg' style='margin-top:10px;'>Выйти</a></center>
		    </div>

		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			    <ul class="nav navbar-nav navbar-right">
			    	<li><a href='#' style='cursor:pointer;' class='catalog-btn'>Каталог</a></li>
			    	<li><a href='index.php' style='cursor:pointer;' class='order-btn'>Заказы</a></li>
			    	<li><a style='cursor:pointer;' href="cart.php" class='cart-btn'>Корзина</a></li>
			    	<li><a href="logout.php" style='color:black; cursor: pointer;' class='hidden-xs'><u><b>Выйти</b></u></a></li>
			    </ul>
		    </div>
		  </div>
	</nav>
</section>

<section id='catalog-box' style=''>
<div class='container'>
	<div class='row'>
		<div class='col-md-12 col-sm-12 col-xs-12'>
			<center><h3 style='display: inline-block;'>Каталог</h3>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="search" id='search-product' placeholder="Поиск" class='form-control' style='width: 20%; display: inline-block;'></center>
		</div>
		<div class='col-md-12 col-sm-12 col-xs-12'>
			<?php
				$data = array();
				try {
					$stmt = $conn->prepare("SELECT c.category_num cNum, c.category_name cName, p.product_num pNum, p.product_name pName, p.product_description pDescription, p.product_img pImg, p.price price, p.addon addon FROM category c, product p, company_catalog cc WHERE p.category_num = c.category_num AND p.product_num = cc.product_num AND cc.company_num = :company_num order by cName asc");
					$stmt->bindParam(':company_num', $_SESSION['user_num'], PDO::PARAM_STR);
				    $stmt->execute();
				   	$result = $stmt->fetchAll();
				   	foreach ($result as $value) {
				   		$data[$value['cNum']]['name'] = $value['cName'];
				   		$data[$value['cNum']]['products'][$value['pNum']]['name'] = $value['pName']; 
				   		$data[$value['cNum']]['products'][$value['pNum']]['description'] = $value['pDescription']; 
				   		$data[$value['cNum']]['products'][$value['pNum']]['img'] = $value['pImg']; 
				   		$data[$value['cNum']]['products'][$value['pNum']]['price'] = $value['price'];
				   		$data[$value['cNum']]['products'][$value['pNum']]['addon'] = $value['addon'];
				   	}
				   	$_SESSION['catalog_date_client'] = $data;
				} catch (PDOException $e) {
					echo "Error : ".$e->getMessage()." !!!";
				}
			?>
			<!-- <div class='container'> -->
				
			<!-- </div> -->
		</div>
		<div id='catalog'>
			<?php include('search_product.php'); ?>
		</div>
	</div>
</div>
</section>

<section id='to-cart' style='display:none;'>
</section>

<div class='img-section'>
	<center>
		<div class='img-big-box'>
			<p></p>
			<img src="" class='img-responsive'>
			<span class='glyphicon glyphicon-remove remove-img-section'></span>
		</div>
	</center>
</div>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/less.min.js"></script>
<script type="text/javascript">
	$(document).on('click','.img-big',function(){
		$attr = $(this).find('img').attr('src');
		console.log($attr);
		$('.img-section').find('img').attr('src',$attr);
		$('.img-section').css('display','block');
	});
	$(document).on('click','.remove-img-section',function(){
		$(this).prev().attr('src','');
		$(this).parents('.img-section').css('display','none');
	});
	$(document).on('click','.img-section',function(){
		$(this).find('img').attr('src','');
		$(this).css('display','none');
	});
	$(document).on('click','.category-section',function(){
		$(this).parent().next().slideToggle();
	});
</script>
<script type="text/javascript">
	$(document).on('click','.to-cart',function(){
		$data_num = $(this).attr('data-num');
		$data_name = $(this).attr('data-name');
		$data_name = $data_name.replace(" ","_");
		$price = $(this).attr('data-price');
		$added = $(this).attr('added');
		$addon = $(this).attr('data-addon');
		if($added == 'false'){
			$this = $(this);
			$('#to-cart').load('to_cart.php?addon='+$addon+'&price='+$price+'&added=false&data_num='+$data_num+"&data_name="+$data_name,function(responseTxt, statusTxt, xhr){
				if(statusTxt == "success"){
					var res = responseTxt.split("!");
					$this.attr('added','true');
					$this.removeClass('btn-primary').addClass('btn-success');
					$this.html("Добавлено");
					if (typeof(Storage) !== "undefined") {
					   	localStorage.setItem('cart_data', responseTxt);
					}
		    	}
		    });
		}
		else if($added == 'true'){
			$this = $(this);
			$('#to-cart').load('to_cart.php?added=true&data_num='+$data_num+"&data_name="+$data_name,function(responseTxt, statusTxt, xhr){
				if(statusTxt == "success"){
					var res = responseTxt.split("!");
					$this.attr('added','false');
					$this.removeClass('btn-success').addClass('btn-primary');
					$this.html("Добавить в корзину");
					if (typeof(Storage) !== "undefined") {
					   	localStorage.setItem('cart_data', responseTxt);
					}
		    	}
		    });
		}
	});

	$(document).on('keyup','#search-product',function(){
		$q = $(this).val();
		$q = $q.replace(" ","_");
		console.log($q);
		$("#catalog").load("search_product.php?q="+$q);
	});
</script>