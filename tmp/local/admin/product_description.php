<?php
	include_once('connection.php');
	if(!isset($_SESSION['admin_username'])){
		header('location:signin.php');
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
<?php include_once('connection.php');?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
	<title>Admin</title>
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/bootstrap.css">
	<link rel="stylesheet" href="../css/bootstrap-theme.css">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap-theme.min.css">
	<link rel="stylesheet/less" type="text/css" href="../css/style.less">
</head>
<body style='background-color: #F0F0F0; background-image: none;'>
<?php if(isset($_SESSION['n']) && $_SESSION['n']=='true'){?>
<section id='alert' style='position:absolute; top:3%; z-index: 100; width: 50%; left:25%;'>
	<?php
		if(isset($_GET['updated'])){
	?>
	<div class="alert alert-success alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<center>Товар <strong>успешно</strong> изменен.</center>
	</div>
	<?php 
		}else if(false){ 
	?>
	<?php } ?>
	<?php
		if(isset($_GET['size']) && $_GET['size']=='false'){
	?>
	<div class="alert alert-warning alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<center><strong>Максимальный размер изображений должно быть не больше 100кб. "Для экономий памяти на сервере. Совет: загружите изображение в формате .jpg"</strong></center>
	</div>
	<?php }?>
	<?php $_SESSION['n'] = "false"; ?>
	<!-- <div class="alert alert-success alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<strong>Warning!</strong> Better check yourself, you're not looking too good.
	</div> -->
</section>
<?php } ?>
<?php include('header.php');?>
<section id='body'>
	<div class='container'>
		<div class='row'>
			<div class='col-md-4 col-sm-4 col-xs-12'>
				<div class='product-description-img'>
					<img src="<?php echo ($result['pImg']!='') ? '../product_img/'.$result['pImg'] : '../img/alt.png' ;?>">
				</div>
			</div>
			<div class='col-md-8 col-sm-8 col-xs-12'>
				<div class='product-description-txt'>
					<p><i>Категория:</i> <span><u><?php echo $result['cName'];?></u></span></p>
					<p><i>Продукт:</i> <span><u><?php echo $result['pName'];?></u></span></p>
					<p><i>Цена:</i> <span><u><?php echo $result['price']." тг."; ?></u></span> <span><i>за: </i></span><span><u><?php echo $result['addon'];?></u></span></p>
					<p><i>Описание:</i><span style='display: block; padding-left:25px;'><?php echo nl2br($result['pDescription']);?></span></p>
				</div>
				<form onsubmit = "return confirm('Подтвердите действе...')" class='hidden-form' action='admin-controller.php' method='post' enctype="multipart/form-data">
					<input type="hidden" name="product_num" value='<?php echo $result['pNum'];?>'>
					<div class='form-group'>
						<label><i>Категория:</i></label>
							<br>
							<select class="form-control" name='category'>
							  	<?php
							  		try {
										$stmt = $conn->prepare("SELECT * FROM category");
								    	$stmt->execute();
								    	$result_category = $stmt->fetchAll();
									} catch (PDOException $e) {
										echo "Error : ".$e->getMessage()." !!!";
									}
									if(isset($result_category)){
										foreach ($result_category as $value) {
							  	?>
							  	<option value='<?php echo $value['category_num'];?>' <?php if($value['category_num']==$result['cNum']) echo 'selected style="font-weight:bolder; text-decoration:italic;"';?>><?php echo $value['category_name']; if($value['category_num']==$result['cNum']) echo '&nbsp;&nbsp;"выбрано"'?></option>
							  	<?php }} ?>
							</select> 
					</div>
					<div class='form-group'>
						<label for='product'><i>Продукт:</i></label>
						<input type="text" class='form-control' name="product" value='<?php echo $result['pName'];?>' required="">
					</div>
					<table>
						<tr>
							<td>
								<label for='price'>Стоимость за одну единицу товара (тг.)</label>
								<input type="number" min='1' value='<?php echo $result['price'];?>' class='form-control' name="price" id='price' placeholder="тг." required="">
							</td>
							<td style='padding-left:20px;'>
								<label for='addon'>Единица измерения</label>
								<input type="text" class='form-control' id='addon' name="addon" value="<?php echo $result['addon'];?>" placeholder="Прим.: кг." required="">
							</td>
						</tr>
					</table>
					<div class='form-group'>
						<label for='description'><i>Описание:</i></label>
						<textarea class='form-control' id='description' name='description' required="" rows='7'><?php echo $result['pDescription'];?></textarea>
					</div>
					<div class='form-group'>
						<label><?php echo ($result['pImg']!='') ? "<p style='color:red;'>Изображение товара уже выбрано.<br> Если не хотите изменить изображение то оставьте нижнюю поле пустым!</p>" : "<p>Выбрать изображение</p>";?></label>
						<input type="hidden" name="hidden_img" value='<?php echo $result['pImg'];?>'>
						<input type="file" class='form-control' id='image' name="product_image">
					</div>
					<input type="submit" name="edit-product" class='btn btn-success btn-sm' value='Сохранить'>
					<input type="submit" name="delete-product" class='btn btn-danger btn-sm' value='Удалить'>
					<input type='reset' class='btn btn-warning btn-sm cancel' value='Отмена'>
				</form>
				<button class='btn btn-info btn-sm pull-right edit' style='margin-left:10px;'>Изменить</button>
				<!-- <a href="index.php" class='btn btn-sm btn-warning pull-right'>Назад</a> -->
			</div>
		</div>
	</div>
</section>
<script src="../js/jquery.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/less.min.js"></script>
<?php include('header-js.php');?>
<script type="text/javascript">
	$(document).on('click','.edit,.cancel', function(){
		$('.edit').toggle();
		$('.hidden-form').toggle();
		$('.product-description-txt').toggle();
	});
</script>
</body>
</html>