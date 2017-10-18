<?php
	include_once('connection.php');
	if(!isset($_SESSION['admin_username'])){
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
	<title>Заказ</title>
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
		if(isset($_GET['change'])){
	?>
	<div class="alert alert-success alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<center><strong>Запрос успешно обработан!</strong></center>
	</div>
	<?php 
		}else if(false){ 
	?>
	<div class="alert alert-success alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<center>Пользователь <strong>успешно</strong> добавлен.</center>
	</div>
	
	<?php } ?>
	<?php $_SESSION['n'] = "false"; ?>
	<!-- <div class="alert alert-success alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<strong>Warning!</strong> Better check yourself, you're not looking too good.
	</div> -->
</section>
<?php } ?>
<?php include_once('header.php');?>

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
	try {
		$stmt = $conn->prepare("SELECT c.deadline deadline, c.cart_num cart_num, c.status status, pc.volume volume, p.product_num product_num, p.product_name product_name, pc.deleted deleted, p.price price, p.addon addon FROM cart c, product_cart pc, product p WHERE c.company_num = :company_num AND pc.cart_num = c.cart_num AND c.cart_num = :cart_num AND p.product_num = pc.product_num order by c.deadline asc, pc.deleted asc");
		$stmt->bindParam(':company_num', $_GET['company_num'], PDO::PARAM_STR);
		$stmt->bindParam(':cart_num', $_GET['data_num'], PDO::PARAM_STR);
	    $stmt->execute();
	   	$result = $stmt->fetchAll();
	   	$grandTotal = 0.0;
	   	foreach ($result as $value) {
	   		$sum = floatval($value['price'])*floatval($value['volume']);
	  		$deadline = $value['deadline'];
	   		$data[$value['product_num']]['deadline'] = $value['deadline'];
	   		$data[$value['product_num']]['status'] = $value['status'];
	   		$data[$value['product_num']]['name'] = $value['product_name'];
	   		$data[$value['product_num']]['volume'] = $value['volume'];
	   		$data[$value['product_num']]['deleted'] = $value['deleted'];
	   		$data[$value['product_num']]['addon'] = $value['addon'];
	   		$data[$value['product_num']]['cart_num'] = $value['cart_num'];
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

<section id='order'>
	<div class='container'>
		<div class='row'>
			<center>
				<div clss='col-md-12 col-sm-12 col-xs-12'>
					<label>Срок заказа:</label>
					<h4 class='edit-deadline' style='display: inline-block;'><b><?php echo  date('d.m.Y',strtotime($deadline));?></b></h4>
				</div>
			</center>
		</div>
		<table class="table table-bordered">
			<tr>
				<th>Название продукта</th>
				<th>Объем</th>
				<th>Цена</th>
				<th>Сумма</th>
			</tr>
		<?php 
			$counter = 1;
			foreach ($data as $key => $value) {
				$class = '';
				if($value['deleted']=='y'){ $class = 'danger';}
		?>
		
		<tr class='<?php echo $class;?> order-info'>
			<td>
				<?php echo "<b>".($counter++)."</b>. ".$value['name'];?>
			</td>
			<td class='form-volume'>
				<span class='original'><?php echo $value['volume'].' '.$value['addon'];?></span>
				<button class='btn btn-xs btn-info edit'>Изменить</button>
				<?php if(isset($_GET['approve']) && $_GET['approve']!='false'){?>
				<form class='edit-form' style='display: none;' onsubmit="return confirm('Подтвердите действие!!!');" action='admin-controller.php' method='post'>
					<input type="number" min='0' step='0.1' name="new_volume" class='change' value="<?php echo $value['volume'];?>">
					<span><?php echo $value['addon'];?> </span>
					<br>
					<input type="submit" class='btn btn-xs btn-success' name="submit_new_volume" value="Сохранить">
					<input type="hidden" name="old_volume" value="<?php echo $value['volume'];?>">
					<input type="hidden" name="addon" value='<?php echo $value['addon'];?>'>
					<input type="hidden" name="data_num" value="<?php echo $key;?>">
					<input type="hidden" name="cart_num" value='<?php echo $value['cart_num'];?>'>
					<input type="hidden" name="product_name" value='<?php echo $value['name'];?>'>
					<input type="hidden" name="approve" value='<?php echo $_GET['approve'];?>'>
					<input type="hidden" name="company_num" value='<?php echo $_GET['company_num'];?>'>
					<!-- <input type="submit" class='btn btn-xs btn-danger' name="delete_this_product_from_order" value='Удалить'> -->
					<input type="hidden" name='old_price' value='<?php echo $value['sum']; ?>'>
					<input type="reset" class='btn btn-xs btn-warning reset' value='Отмена'>
				</form>	
				<?php }?>
			</td>
			<td><span class='price'><?php echo $value['price'];?></span> тг.</td>
			<td><span class='sum'><?php echo $value['sum'];?></span> тг.</td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="4">
				<b>Итог: <span id='grandTotal'><?php echo $grandTotal;?></span> тг.</b>
			</td>
		</tr>
		</table>
		<?php if(isset($_GET['approve']) && $_GET['approve']=='true'){?>
		<form onsubmit="return confirm('Подтвердите действие!!!');" action='admin-controller.php' method='post' class='pull-right' style='margin:10px;'>
			<input type="hidden" name="extra_num" value='<?php echo $_GET['company_num'];?>'>
			<input type="hidden" name="order_num" value='<?php echo $_GET['data_num']; ?>'>
			<input type="submit" name="submit_order" class='btn btn-sm btn-success' value='Принять заказ'>
			<input type="submit" name="remove_order" class='btn btn-sm btn-danger' value='Удалить заказ'>
		</form>
		<?php } else if(isset($_GET['approve']) && $_GET['approve']=='submit') {?>
			<form onsubmit="return confirm('Подтвердите действие!!!');" action='admin-controller.php' method='post' class='pull-right' style='margin:10px;'>
				<input type="hidden" name="extra_num" value='<?php echo $_GET['company_num'];?>'>
				<input type="hidden" name="order_num" value='<?php echo $_GET['data_num']; ?>'>
				<input type="submit" name="close_order" class='btn btn-sm btn-success' value='Закрыть заказ'>
				<input type="submit" name="remove_order" class='btn btn-sm btn-danger' value='Удалить заказ'>
			</form>
		<?php }else {?>
			<h3 class='text-warning pull-right'>Изменение невозможно</h3>
		<?php }?>
	</div>
</section>
<a href="../TCPDF-master/examples/toPdf.php?company_num=<?php echo $_GET['company_num'];?>&cart_num=<?php echo $_GET['data_num'];?>" target="__blank" class='pull-right' style='margin:30px;'>Скачать заказ  в (.pdf) формате.</a>

<script src="../js/jquery.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/less.min.js"></script>
<?php include('header-js.php');?>
<script type="text/javascript">
	$grandTotal = <?php echo $grandTotal;?>;
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
	$(document).on('click','.form-volume .btn',function(){
		if($(this).attr('type')!='submit'){
			$parent = $(this).parents('.form-volume');
			$parent.find('.edit').toggle();
			$parent.find('.edit-form').toggle();
			$parent.find('.original').toggle();
		}
	});
	$(document).on('mouseup keyup','.change',function(){
		$val = $(this).val();
		$val = parseFloat($val);
		$price = $(this).parents('.order-info').find('.price').html();
		$price = parseFloat($price);
		$sum = ($val*$price).toFixed(2);
		$(this).parents('.order-info').find('.sum').html($sum);

		$original = parseFloat($(this).parent().find('input[type=hidden]').val());
		$subGrandTotal = $('body').find("#grandTotal").html();
		$subGrandTotal = parseFloat($subGrandTotal);
		$subGrandTotal = ($subGrandTotal - $original + parseFloat($sum));
		console.log($original);
		console.log($sum);
		console.log($subGrandTotal);
		$('body').find('#grandTotal').html($subGrandTotal.toFixed(2));
	});
	$(document).on('click','.reset',function(){
		$val = $(this).prev().val();
		$(this).parents('.order-info').find('.sum').html($val);
		$('body').find('#grandTotal').html($grandTotal);
	});
</script>
</body>
</html>