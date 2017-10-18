<?php 
	include_once('connection.php');
	if(!isset($_SESSION['admin_username'])){
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
	<title>Admin</title>
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/bootstrap.css">
	<link rel="stylesheet" href="../css/bootstrap-theme.css">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap-theme.min.css">
	<link rel="stylesheet/less" type="text/css" href="../css/style.less">
</head>
<body>
<?php if(isset($_SESSION['n']) && $_SESSION['n']=='true'){?>
<section id='alert' style='position:absolute; top:3%; z-index: 100; width: 50%; left:25%;'>
	<?php
		if(isset($_GET['exists'])){
	?>
	<div class="alert alert-warning alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<center><strong>Внимание!</strong> Пользователь с таким именем ( <?php echo $_GET['exists']; ?> ) уже существует.</center>
	</div>
	<?php 
		}else if(isset($_GET['company'])){ 
	?>
	<div class="alert alert-success alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<center>Пользователь <strong>успешно</strong> добавлен.</center>
	</div>
	<?php 
		}else if(isset($_GET['product'])){ 
	?>
	<div class="alert alert-success alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<center>Товар <strong>успешно</strong> добавлен.</center>
	</div>
	<?php 
		}else if(isset($_GET['delete'])){ 
	?>
	<div class="alert alert-info alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<center>Товар <strong>успешно</strong> удален.</center>
	</div>
	<?php 
		}else if(isset($_GET['categoryEdit'])){ 
	?>
	<div class="alert alert-success alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<center><strong>Запрос выполнен.</strong></center>
	</div>
	<?php } ?>
	<?php $_SESSION['n'] = "false"; ?>
	<!-- <div class="alert alert-success alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<strong>Warning!</strong> Better check yourself, you're not looking too good.
	</div> -->
</section>
<?php } ?>
<?php include('header.php');?>
<section id='body'>
	<?php include('products_list.php');?>
</section>

<div class='img-section'>
	<center>
		<div class='img-big-box'>
			<img src="" class='img-responsive'>
			<span class='glyphicon glyphicon-remove remove-img-section'></span>
		</div>
	</center>
</div>
<script src="../js/jquery.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/less.min.js"></script>
<?php include('header-js.php');?>
<script type="text/javascript">
	$(document).on('click','.img-big',function(){
		$attr = $(this).find('img').attr('src');
		console.log($attr);
		$('.img-section').find('img').attr('src',$attr);
		$('.img-section').css('display','block');
	});
	$(document).on('click','.remove-img-section',function(){
		$(this).siblings().attr('src','');
		$(this).parents('.img-section').css('display','none');
	});
	$(document).on('click','.img-section',function(){
		$(this).find('img').attr('src','');
		$(this).css('display','none');
	});
	$(document).on('click','.category-section',function(){
		$(this).parent().next().slideToggle();
	});
	$(document).on('click','.category-box-edit, .category-box-form-cancel',function(){
		if($(this).attr('type')!='submit'){
			$parents = $(this).parents('.category-box'); 
			$parents.find('.category-section').toggle();
			$parents.find('.category-box-edit').toggle();
			$parents.find('.category-box-form').toggle();
		}
	});
	$(document).on('keyup','#search-product',function(){
		$q = $(this).val();
		$("#catalog").load("search_product.php?q="+$q);
	});
</script>
</body>
</html>