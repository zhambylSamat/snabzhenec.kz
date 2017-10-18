<?php
	include_once('connection.php');
	if(!isset($_SESSION['user_num'])){
		header('location:signin.php');
	}
	else if(!isset($_GET['data_num'])){
		header('location:index.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
	<title>Заказ - <?php echo $_SESSION['username'];?></title>
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
		if(isset($_GET['edit'])){
	?>
	<div class="alert alert-success alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<center><strong>Запрос успешно обработан!</strong></center>
	</div>
	<?php 
		}else if(isset($_GET['company'])){ 
	?>
	<div class="alert alert-info alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<center>Товар <strong>успешно</strong> удален.</center>
	</div>
	<?php 
		}else if(isset($_GET['edit_comment'])){ 
	?>
	<div class="alert alert-info alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
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
			    	<li><a href="catalog.php?" class='catalog-btn'>Каталог</a></li>
			    	<li><a href="index.php?" class='order-btn'>Заказы</a></li>
			    	<li><a href="cart.php" class='cart-btn'>Корзина</a></li>
			    	<li><a href="logout.php" style='color:black;' class='hidden-xs'><u><b>Выйти</b></u></a></li>
			    </ul>
		    </div>
		  </div>
	</nav>
</section>

<?php
function array_sort($array, $on, $order=SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}


	$data = array();
	$deadline = '';
	$comment = '';
	try {
		$stmt = $conn->prepare("SELECT c.deadline deadline, c.comment comment, c.status status, pc.volume volume, p.product_num product_num, p.product_name product_name, p.addon addon, p.price price FROM cart c, product_cart pc, product p WHERE c.company_num = :company_num AND pc.cart_num = c.cart_num AND c.cart_num = :cart_num AND p.product_num = pc.product_num AND pc.deleted = 'n' order by c.deadline asc");
		$stmt->bindParam(':company_num', $_SESSION['user_num'], PDO::PARAM_STR);
		$stmt->bindParam(':cart_num', $_GET['data_num'], PDO::PARAM_STR);
	    $stmt->execute();
	   	$result = $stmt->fetchAll();
	   	$grandTotal = 0.0;
	   	foreach ($result as $value) {
	   		$sum = floatval($value['price'])*floatval($value['volume']);
	  		$deadline = $value['deadline'];
	  		$comment = $value['comment'];
	   		$data[$value['product_num']]['deadline'] = $value['deadline'];
	   		$data[$value['product_num']]['status'] = $value['status'];
	   		$data[$value['product_num']]['name'] = $value['product_name'];
	   		$data[$value['product_num']]['volume'] = $value['volume'];
	   		$data[$value['product_num']]['addon'] = $value['addon'];
	   		$data[$value['product_num']]['price'] = number_format(floatval($value['price']),2,',','');
	   		$data[$value['product_num']]['sum'] = number_format($sum,2,',','');
	   		$grandTotal += $sum;
	   	}
	   	$grandTotal = number_format($grandTotal,2,',','');
	   	$data = array_sort($data, 'name', SORT_ASC);
	} catch (PDOException $e) {
		echo "Error : ".$e->getMessage()." !!!";
	}
?>

<section id='order' style='border:none;'>
	<div class='container'>
		<div class='row' style='border-bottom:1px solid lightgray; background-color:#eee;'>
			<center>
				<div clss='col-md-12 col-sm-12 col-xs-12'>
					<form class='form-inline' onsubmit="return confirm('Подтвердите действие!!!');" action='user_controller.php' method='post'>
						<label>Срок заказа:</label>
						<input style='display: none;' type="date" name='deadline' class='form-control edit-deadline' required="" value='<?php echo $deadline;?>'>
						<input type="hidden" name="cart_num" value='<?php echo $_GET['data_num'];?>'>
						<h4 class='edit-deadline' style='display: inline-block;'><b><?php echo  date('d.m.Y',strtotime($deadline));?></b></h4>
						<input style='display: none;' type="submit" name="edit_deadline" class='btn btn-success btn-xs' value="Сохранить">
						<input type="reset" style='display: none;' class='btn btn-xs btn-warning' value='Отмена'>
						<a class='btn btn-xs btn-info'>Изменить</a>
					</form>
				</div>
			</center>
		</div>
		<div class='row' style='border-bottom:1px solid lightgray; background-color:#eee;'>
			<div class='col-md-4 col-sm-4 col-xs-4'>
				<b>Название товара</b>
			</div>
			<div class='col-md-4 col-sm-4 col-xs-4'>
				<b>Объем</b>
			</div>
			<div class='col-md-4 col-sm-4 col-xs-4'>
				<b>Действии</b>
			</div>
		</div>
		<?php 
			$counter = 1;
			foreach ($data as $key => $value) {
		?>
		<div class='order-list'>
			<div class='row' style='border-bottom:1px solid lightgray; background-color:#eee;'>
				<form onsubmit = "return confirm('Подтвердите действие!!!');" action='user_controller.php' method='post'>
					<div class='col-md-4 col-sm-4 col-xs-4 product_name'>
						<h4><?php echo ($counter++).") ".$value['name'];?></h4>
					</div>
					<div class='col-md-4 col-sm-4 col-xs-4 product_volume'>
						<input type="hidden" name="data_num" value='<?php echo $key;?>'>
						<input type="hidden" name="cart_num" value='<?php echo $_GET['data_num']?>'>
						<input type="hidden" name="product_name" value='<?php echo $value['name'];?>'>
						<input type="hidden" name="product_volume" value='<?php echo $value['volume'];?>'>
						<input type="number" min='0.1' step='0.1' style='display: none;' name="new_product_volume" required="" class='form-control edit-form' value='<?php echo $value['volume'];?>'>
						<h4 class='edit-form'><?php echo $value['volume'];?> <?php echo $value['addon'];?></h4>
						<input type="hidden" class='superSubTotal' value = '<?php echo $value['sum'];?>'>
						<p>Цена \ <b>Сумма:</b> <?php echo "<span class='price'>".$value['price']."</span> \\ <b><span class='sum'>".$value['sum'].'</span> тг.</b>';?></p>
						<!-- <p>Сумма: </p> -->
					</div>
					<div class='col-md-4 col-sm-4 col-xs-4 product-form'>
						<?php if($_GET['edit']){ ?>
						<a class='btn btn-xs btn-info edit-order'>Изменить</a>
						<input  style='display: none;' type="submit" name="edit_order" class='btn btn-success btn-xs' value='Сохранить'>
						<input style='display: none;' type="submit" name="delete_product_order" class='btn btn-xs btn-danger remove-product-order' value='Удалить'>
						<input type="reset" style='display: none;' class='btn btn-xs btn-warning cancel-edit-order' value='Отмена'>
						<?php }else{ ?>
						<h5 class='text-danger'>Изменение невозможно!</h5>
						<?php } ?>
					</div>
				</form>
			</div>
		</div>
		<?php } ?>
		<div class='row comment' style=' background-color:#eee;'>
			<form onsubmit = "return confirm('Подтвердите действие!!!');" action='user_controller.php' method='post'>
				<div class='col-md-8 col-sm-4 col-xs-4'>
					<label>Примечание \ Комментарии</label>
					<textarea class='form-control' rows='10' readonly='false' name='comment'><?php echo $comment; ?></textarea>
				</div>
				<div class='col-md-4 col-sm-4 col-xs-4'>
					<?php if($_GET['edit']){ ?>
					<a class='btn btn-xs btn-info edit-comment'>Изменить</a>
					<input type="hidden" name="cart_num" value='<?php echo $_GET['data_num'];?>'>
					<input  style='display: none;' type="submit" name="edit_comment" class='btn btn-success btn-xs' value='Сохранить'>
					<input type="reset" style='display: none;' class='btn btn-xs btn-warning cancel-edit-order' value='Отмена'>
					<?php }else{ ?>
					<h5 class='text-danger'>Изменение невозможно!</h5>
					<?php } ?>
				</div>
			</form>
		</div>
		<div class='row' style=' background-color:#eee;'>
			<b>Итог: <span class='grandTotal'><?php echo $grandTotal;?></span> тг.</b>
			<a href="index.php" class='btn btn-success btn-sm pull-right' style='margin:10px;'>Главная</a>
		</div>
	</div>
</section>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/less.min.js"></script>
<script type="text/javascript">
	$superGrandTotal = <?php echo $grandTotal;?>;
	$(document).on('click','.order-list .btn',function(){
		if($(this).attr('type')!='submit'){
			$(this).parents('.order-list').find('.btn').toggle();
			$(this).parents('.order-list').find('.edit-form').toggle();
		}
	});
	$(document).on('click','.form-inline .btn',function(){
		if($(this).attr('type')!='submit'){
			$(this).parents('.form-inline').find('.btn').toggle();
			$(this).parents('.form-inline').find('.edit-deadline').toggle();
		}
	});
	$(document).on("change keyup mouseup",'.edit-form',function(){
		$val = $(this).val();
		$price = $(this).parent().find('.price').html();
		$sum = parseFloat($val)*parseFloat($price);
		$sum = $sum.toFixed(2);
		$subSum = $(this).parent().find('.sum').html();
		$subGrandTotal = $('.grandTotal').html(); 
		$(this).parent().find('.sum').html($sum);
		$grandTotal = parseFloat($subGrandTotal) + parseFloat($sum) - parseFloat($subSum);
		$(".grandTotal").html($grandTotal); 
	});
	$(document).on('click','.cancel-edit-order',function(){
		$(this)
		$(".grandTotal").html($superGrandTotal); 
		$superSubTotal = $(this).parent().prev().find('.superSubTotal').val();
		$(this).parent().prev().find('.sum').html($superSubTotal);
	});
	$(document).on('click','.comment .btn',function(){
		var attr = $(this).parents('.comment').find('textarea').attr('readonly');
		if (typeof attr !== typeof undefined) {
			$(this).parents('.comment').find('textarea').removeAttr('readonly');
		}
		else{
			$(this).parents('.comment').find('textarea').attr('readonly','');
		}
		$(this).parents('.comment').find('.btn').toggle();
	});
</script>
</body>
</html>