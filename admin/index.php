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
<body style='background-color: #F0F0F0; background-image: url("img/bg-site.png");'>
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
	<?php 
		}else if(isset($_GET['orderApprove'])){ 
	?>
	<div class="alert alert-success alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<center><strong>Заказ принят.</strong></center>
	</div>
	<?php 
		}else if(isset($_GET['remove_order'])){ 
	?>
	<div class="alert alert-warning alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<center><strong>Заказ удален.</strong></center>
	</div>
	<?php 
		}else if(isset($_GET['remove_company'])){ 
	?>
	<div class="alert alert-warning alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<center><strong>Удалено.</strong></center>
	</div>
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
<?php
	$order = array();
	$result = '';
	try {
		$stmt = $conn->prepare("SELECT com.company_num company_num, com.company_name company_name, c.cart_num cart_num, c.deadline deadline, c.status status, sum(p.price*pc.volume) subtotal FROM cart c, company com, product_cart pc, product p WHERE c.company_num = com.company_num AND pc.product_num=p.product_num AND pc.cart_num = c.cart_num group by c.cart_num order by c.deadline asc");
	    $stmt->execute();
	   	$res = $stmt->fetchAll();

	   	foreach ($res as $value) {
	   		$deadline_day = (int)date('d',strtotime($value['deadline']));
	   		$deadline_month = (int)date('m',strtotime($value['deadline']));
	   		$deadline_year = (int)date('Y',strtotime($value['deadline']));
	   		$date_day = (int)date('d');
	   		$date_month = (int)date('m');
	   		$date_year = (int)date('Y');
	   		if($value['status'] == 0){
	   			if($deadline_year>$date_year){
	   				$text = "notApproved";
	   			}
	   			else if($deadline_year==$date_year && $deadline_month>$date_month){
	   				$text = "notApproved";
	   			}
	   			else if($deadline_year==$date_year && $deadline_month==$date_month && $deadline_day>=$date_day){
	   				$text = "notApproved";
	   			}
	   			else{
	   				$text = "fail";	
	   			}
	   		}
	   		else if($value['status'] == 1){
	   			if($deadline_year>$date_year){
	   				$text = "approved";
	   			}
	   			else if($deadline_year==$date_year && $deadline_month>$date_month){
	   				$text = "approved";
	   			}
	   			else if($deadline_year==$date_year && $deadline_month==$date_month && $deadline_day>=$date_day){
	   				$text = "approved";
	   			}
	   			else{
	   				$text = "success";
	   			}
	   		}
	   		else if($value['status'] == 2){
	   			$text = "success";
	   		}
	   		$result[$value['cart_num']]['company_num'] = $value['company_num'];
	   		$result[$value['cart_num']]['name'] = $value['company_name'];
	   		$result[$value['cart_num']]['date'] =  date('d.m.Y',strtotime($value['deadline']));	   		
	   		$result[$value['cart_num']]['status'] = $text;   		
	   		$result[$value['cart_num']]['subtotal'] = $value['subtotal'];
	   	}
	} catch (PDOException $e) {
		echo "Error : ".$e->getMessage()." !!!";
	}
?>
<section id='body'>
	<div class='container'>
		<div class='row'>
			<div class='col-md-12 col-sm-12 col-xs-12'>
				<center><h4>Заказы (Активные)</h4></center>
			</div>
			<div class='col-md-12 col-sm-12 col-xs-12'>
				<table class="table table-hover">
					<tr>
						<th>Компания</th>
						<th>Доставка до:</th>
						<th>Статус</th>
					</tr>
					<?php
						$count = 1;
						if($result!=''){
						foreach ($result as $key => $value) {
							if($value['status']!='success' && $value['status']!='fail'){
					?>
					<tr>
						<td><h4><a href='order_detail.php?data_num=<?php echo $key;?>&approve=<?php if($value['status']=='notApproved') echo 'true'; else if($value['status']=='approved') echo 'submit'; else if($value['status']=='success') echo 'false'; ?>&company_num=<?php echo $value['company_num'];?>'><?php echo ($count++).". ".$value['name'];?></a></h4></td>
						<td>
							<?php echo $value['date'];?>
							<br>
							<?php echo "<b>Сумма: ".number_format($value['subtotal'],2,',','')." тг.</b>";?>
						</td>
						<td>
							<?php 
							if($value['status']=="notApproved"){
							?>
							<form onsubmit="return confirm('Подтвердите действие!!!');" action='admin-controller.php' method='post'>
								<input type="hidden" name="extra_num" value='<?php echo $value['company_num'];?>'>
								<input type="hidden" name="order_num" value='<?php echo $key; ?>'>
								<input type="submit" name="submit_order" class='btn btn-sm btn-success' value='Принять заказ'>
							</form>
							<?php }else if($value['status']=='approved'){?>
							<h4 class='text-success' style='display:inline-block;'>Заказ принят.</h4>
							<form onsubmit="return confirm('Заказ доствален?');" style='display:inline-block;' action='admin-controller.php' method='post'>
								<input type="hidden" name="extra_num" value='<?php echo $value['company_num'];?>'>
								<input type="hidden" name="order_num" value='<?php echo $key; ?>'>
								<input type="submit" name="close_order" class='btn btn-sm btn-success' value='Закрыть заказ'>
							</form>
							<?php } ?>
						</td>
					</tr>
					<?php }}} ?>
				</table>
				<?php
					if($count==1) echo "<center><h4>Заказов нет.</h4></center>";
				?>
			</div>
		</div>
	</div>
	<!-- <?php include('products_list.php');?> -->
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
	// $(document).on('keyup','#search-product',function(){
	// 	$q = $(this).val();
	// 	$("#catalog").load("search_product.php?q="+$q);
	// });
</script>
</body>
</html>