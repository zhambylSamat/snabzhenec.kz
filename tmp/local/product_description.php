<?php
	include_once('connection.php');
	if(!isset($_SESSION['user_num'])){
		header('location:signin.php');
	}
	else if(!isset($_GET['data_num'])){
		header('location:index.php');
	}
	try {
		$stmt = $conn->prepare("SELECT c.category_num cNum, c.category_name cName, p.product_num pNum, p.product_name pName, p.product_description pDescription, p.product_img pImg, p.price price, p.addon addon FROM category c, product p WHERE p.category_num = c.category_num AND p.product_num = :product_num");
		$stmt->bindParam(':product_num', $_GET['data_num'], PDO::PARAM_STR);
	    $stmt->execute();
	   	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	} catch (PDOException $e) {
		echo "Error : ".$e->getMessage()." !!!";
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
	<title><?php echo $_SESSION['username'];?></title>
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
			    	<li><a href="index.php?catalog" class='catalog-btn'>Каталог</a></li>
			    	<li><a href="index.php?catalog" class='order-btn'>Заказы</a></li>
			    	<li><a href="cart.php" class='cart-btn'>Корзина</a></li>
			    	<li><a href="logout.php" style='color:black;' class='hidden-xs'><u><b>Выйти</b></u></a></li>
			    </ul>
		    </div>
		  </div>
	</nav>
</section>

<section id='body'>
	<div class='container'>
		<div class='row'>
			<div class='col-md-6 col-sm-6 col-xs-12'>
				<div class='product-description-img'>
					<img src="<?php echo ($result['pImg']!='') ? 'product_img/'.$result['pImg'] : 'img/alt.png' ;?>">
				</div>
			</div>
			<div class='col-md-6 col-sm-6 col-xs-12'>
				<div class='product-description-txt'>
					<p><i>Категория:</i> <span><u><?php echo $result['cName'];?></u></span></p>
					<p><i>Продукт:</i> <span><u><?php echo $result['pName'];?></u></span></p>
					<p><i>Цена:</i> <span><u><?php echo $result['price']." тг."; ?></u></span> <span><i>за: </i></span><span><u><?php echo $result['addon'];?></u>
					<p><i>Описание:</i><span style='display: block; padding-left:25px;'><?php echo nl2br($result['pDescription']);?></span></p>
				</div>
			</div>
		</div>
	</div>
</section>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/less.min.js"></script>
</body>
</html>