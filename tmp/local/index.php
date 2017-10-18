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
	<div class="alert alert-info alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<center>Товар <strong>успешно</strong> удален.</center>
	</div>
	<?php 
		}else if(isset($_GET['cart'])){ 
	?>
	<div class="alert alert-success alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<center><strong>Заказ принят. Ожидайте подтверждение поставщика.</strong></center>
	</div>
	<?php 
		}else if(isset($_GET['edit-cart'])){ 
	?>
	<div class="alert alert-success alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<center><strong>Запрос успешно обработан!</strong></center>
	</div>
	<?php } ?>
	<?php $_SESSION['n'] = "false"; ?>
	<!-- <div class="alert alert-success alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<strong>Warning!</strong> Better check yourself, you're not looking too good.
	</div> -->
</section>
<?php } ?>
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
			    	<li><a href='catalog.php' style='cursor:pointer;' class='catalog-btn'>Каталог</a></li>
			    	<li><a href='index.php' style='cursor:pointer;' class='order-btn'>Заказы</a></li>
			    	<li><a style='cursor:pointer;' href="cart.php" class='cart-btn'>Корзина</a></li>
			    	<li><a href="logout.php" style='color:black; cursor: pointer;' class='hidden-xs'><u><b>Выйти</b></u></a></li>
			    </ul>
		    </div>
		  </div>
	</nav>
</section>

<?php
	$order = array();
	try {
		$stmt = $conn->prepare("SELECT * FROM cart WHERE company_num = :company_num order by deadline asc");
		$stmt->bindParam(':company_num', $_SESSION['user_num'], PDO::PARAM_STR);
	    $stmt->execute();
	   	$result = $stmt->fetchAll();
	   	foreach ($result as $value) {
	   		$deadline = date('d.m.Y',strtotime($value['deadline']));
	   		$date =  date("d.m.Y");
	   		$deadline_day = (int)date('d',strtotime($value['deadline']));
	   		$deadline_month = (int)date('m',strtotime($value['deadline']));
	   		$deadline_year = (int)date('Y',strtotime($value['deadline']));
	   		$date_day = (int)date('d');
	   		$date_month = (int)date('m');
	   		$date_year = (int)date('Y');
	   		$text = "";
	   		$class = "";
	   		$edit = false;
	   		$e = true;
	   		if($value['status'] == 0){
	   			if($deadline_year>$date_year){
	   				$text = "Поставщик еще не принял заказ.";
	   				$edit = true;
	   				$class = "text-warning";
	   			}
	   			else if($deadline_year==$date_year && $deadline_month>$date_month){
	   				$text = "Поставщик еще не принял заказ.";
	   				$edit = true;
	   				$class = "text-warning";
	   			}
	   			else if($deadline_year==$date_year && $deadline_month==$date_month && $deadline_day>=$date_day){
	   				$text = "Поставщик еще не принял заказ.";
	   				$edit = true;
	   				$class = "text-warning";
	   			}
	   			else{
	   				$text = "Заказ просрочен.";	
	   				$class = "text-danger";
	   			}
	   		}
	   		else if($value['status'] == 2){
	   			$e = false;
	   			$text = "Заказ успешно закрыт.";	
	   			$class = "text-success";
	   		}
	   		else if($value['status'] == 1){
	   			if($deadline_year>$date_year){
	   				$text = "Поставщик принял заказ.";
	   				$edit = true;
	   				$class = "text-info";
	   			}
	   			else if($deadline_year==$date_year && $deadline_month>$date_month){
	   				$text = "Поставщик принял заказ.";
	   				$edit = true;
	   				$class = "text-info";
	   			}
	   			else if($deadline_year==$date_year && $deadline_month>=$date_month && $deadline_day>=$date_day){
	   				$text = "Поставщик принял заказ.";
	   				$edit = true;
	   				$class = "text-info";
	   			}
	   			else{
	   				$text = "Заказ успешно закрыт.";	
	   				$class = "text-success";
	   			}
	   		}
	   		$order[$value['cart_num']]['date'] =  date('d.m.Y',strtotime($value['deadline']));	   		
	   		$order[$value['cart_num']]['status'] = $text;	   		
	   		$order[$value['cart_num']]['class'] = $class;	   		
	   		$order[$value['cart_num']]['edit'] = $edit;
	   		$order[$value['cart_num']]['e'] = $e;	   		
	   	}
	} catch (PDOException $e) {
		echo "Error : ".$e->getMessage()." !!!";
	}
?>

<section id='order' style='border:none;'>
	<div class='container'>
		<table class="table table-hover table-bordered">
			<tr>
				<th>#</th>
				<th>Доставка до:</th>
				<th>Статус</th>
			</tr>
			<?php 
				$counter = 1;
				foreach ($order as $key => $value) {
			?>
			
			<tr style='cursor: pointer;'>
				<td><?php echo $counter++; ?></td>
				<td>
					<h4 style='display: inline-block;'><a href="order_detail.php?data_num=<?php echo $key;?>&edit=<?php echo $value['edit'];?>"><?php echo $value['date'];?></a></h4>
					<?php 
						if($value['e']==true){
					?>
					<form class='pull-right' style='margin-top:1%;' onsubmit="return confirm('Подтвердите действие!!!');" action='user_controller.php' method='post'>
						<input type="hidden" name="data_num" value='<?php echo $key;?>'>
						<input type="submit" name="delete_order" class='btn btn-xs btn-danger' value='Удалить заказ'>
					</form>
					<?php }?>
				</td>
				<td class='<?php echo $value['class'];?>'>
					<?php echo $value['status'];?>
				</td>
			</tr>
			<?php } ?>
		</table>
			<?php
				if($counter==1){
					echo "<center><h4>Заказов нет.</h4></center>";
				}
			?>
		
	</div>
</section>
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
	// $(document).on('click','.catalog-btn',function(){
	// 	$("#catalog").slideToggle();
	// });
	// $(document).on('click','.order-btn',function(){
	// 	$('#order').slideToggle();
	// });

	$(document).on('click','.to-cart',function(){
		$data_num = $(this).attr('data-num');
		$data_name = $(this).attr('data-name');
		$this = $(this);
		$('#to-cart').load('to_cart.php?data_num='+$data_num+"&data_name="+$data_name,function(responseTxt, statusTxt, xhr){
			if(statusTxt == "success"){
				$this.removeClass('btn-primary').addClass('btn-success');
				$this.html("Добавлно");
	    	}
	    });
	});
</script>
</body>
</html>