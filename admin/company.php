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
	<title>Admin</title>
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/bootstrap.css">
	<link rel="stylesheet" href="../css/bootstrap-theme.css">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap-theme.min.css">
	<link rel="stylesheet/less" type="text/css" href="../css/style.less">
</head>
<body style='background-color: #F0F0F0; background-image: none;'>
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

	if(isset($_GET['data_num']) && $_GET['data_num']!=''){
		$company_catalog = array();

		$company_num =  $_GET['data_num'];;
		$company_name = "";
		$username = "";
		$email = "";
		$default_password = "";
		$max_fail = "";
		$company_description = "";
		$owner = "";

		$items = array();

		$category_counter = array();
		$_SESSION['selected'] = array();
		try {
			$stmt = $conn->prepare("SELECT * FROM company WHERE company_num = :company_num");
			$stmt->bindParam(':company_num', $company_num, PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$company_name = $result['company_name'];
			$phone = $result['phone'];
			$company_description = $result['company_description'];
			$owner = $result['owner_name'];
			$username = $result['username'];
			$default_password = $result['default_password'];
			$max_fail = $result['max_fail'];
			$email = $result['email'];

			$stmt = $conn->prepare("SELECT cat.category_num catNum, cat.category_name catName, p.product_num pNum, p.product_name pName, p.product_img pImg, p.product_description pDescription FROM company c, company_catalog cc, product p, category cat WHERE c.company_num = :company_num AND c.company_num = cc.company_num AND cc.product_num = p.product_num AND p.category_num = cat.category_num");
			$stmt->bindParam(':company_num', $company_num, PDO::PARAM_STR);
	    	$stmt->execute();
	   		$result = $stmt->fetchAll();
	   		foreach ($result as $value) {
	   			$company_catalog[$value['catNum']]['name'] = $value['catName'];
	   			$company_catalog[$value['catNum']]['products'][$value['pNum']]['name'] = $value['pName'];
	   			$company_catalog[$value['catNum']]['products'][$value['pNum']]['img'] = $value['pImg'];
	   			$company_catalog[$value['catNum']]['products'][$value['pNum']]['description'] = $value['pDescription'];
	   		}
	   		// print_r($company_catalog);

	   		$stmt = $conn->prepare("SELECT c.category_num cNum, c.category_name cName, p.product_num pNum, p.product_name pName, p.product_img pImg, p.product_description pDescription FROM category c, product p WHERE p.category_num = c.category_num order by cName asc");
	   		//  AND p.product_num NOT IN (SELECT product_num FROM company_catalog WHERE company_num = :company_num)
	   		// $stmt->bindParam(':company_num', $company_num, PDO::PARAM_STR);
	   		$stmt->execute();
	   		$result = $stmt->fetchAll();
	   		foreach ($result as $value) {
	   			// echo array_key_exists($value['cNum'], $category_counter)." aaa<br>"; 
	   			if(!array_key_exists($value['cNum'], $category_counter)) $category_counter[$value['cNum']] = 1;
	   			else (int)$category_counter[$value['cNum']]++;
	   			// print_r($category_counter);
	   			// echo "<br>";
	   			$items[$value['cNum']]['name'] = $value['cName'];
	   			$items[$value['cNum']]['products'][$value['pNum']]['name'] = $value['pName'];
	   			$items[$value['cNum']]['products'][$value['pNum']]['img'] = $value['pImg'];
	   			$items[$value['cNum']]['products'][$value['pNum']]['description'] = $value['pDescription'];
	   		}
	   		$_SESSION['items'] = $items;
	   		$_SESSION['company_catalog'] = $company_catalog;
	   		$_SESSION['company_num'] = $company_num;
	   		$_SESSION['category_counter'] = $category_counter;
		} catch (PDOException $e) {
			echo "Error : ".$e->getMessage()." !!!";
		}			
	}
	else{
		header("location:index.php");
	}
?>
<?php if(isset($_SESSION['n']) && $_SESSION['n']=='true'){?>
<section id='alert' style='position:absolute; top:3%; z-index: 100; width: 50%; left:25%;'>
	<?php
		if(isset($_GET['company_catalog'])){
	?>
	<div class="alert alert-success alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<center>Процесс <strong>успешно</strong> завершен.</center>
	</div>
	<?php 
		}else if(isset($_GET['deleted'])){ 
	?>
	<div class="alert alert-warning alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<center>Вы <strong>убрали</strong> товар с каталога компаний.</center>
	</div>
	<?php 
		}else if(isset($_GET['reset'])){ 
	?>
	<div class="alert alert-info alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<center><strong>Сброс</strong> пароля прошла успешно. Новый пароль по умолчанию: "Pass123" !!!</center>
	</div>
	<?php 
		}else if(isset($_GET['editCompany'])){ 
	?>
	<div class="alert alert-success alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<center><strong>Данные об компаний изменены.</strong></center>
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
				<a href="#" class='btn btn-md btn-success hidden-xs company-catalog-btn' data-name='company-catalog' style='margin-top:8px;'>Каталог для компаний</a>
				<a href="#" class='btn btn-md btn-info hidden-xs company-order-btn' data-name='company-order' style='margin-top:8px;'>Заказы</a>
				<center><h3></h3><a href="logout.php" class='btn btn-sm btn-warning hidden-sm hidden-md hidden-lg' style='margin-top:10px;'>Выйти</a></center>
		    </div>

		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			    <ul class="nav navbar-nav navbar-right">
			    	<li><a href="logout.php" style='color:black;' class='hidden-xs'><u><b>Выйти</b></u></a></li>
			    	<li><a href="#" class='hidden-md hidden-lg hidden-sm company-catalog-btn' data-name='company-catalog'>Каталог для компаний</a></li>
			    	<li><a href="#" class='hidden-md hidden-lg hidden-sm company-order-btn' data-name='company-order'>Заказы</a></li>
			    	<li><a href='index.php' class="navigation" style='cursor:pointer;' data-name='product_list'>Новые заказы/Главная</a></li>
			    	<li><a href="catalog.php" class="navigation" style='cursor:pointer;' data-name='product_list'>Каталог</a></li>
			        <li class="dropdown">
			          	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Компании <span class="caret"></span></a>
				        <ul class="dropdown-menu">
				        	<li>
				        		<input type="text" class='form-control search' name="search" placeholder="Поиск">
				        		<ol class='company-list'>
					        	<?php include('search.php');?>
					            </ol>
				            </li>
				        </ul>
			        </li>
			    </ul>
		    </div>
		  </div>
	</nav>
</section>
<section id='company-info'>
	<div class='container'>
		<div class='row'>
			<div class='col-md-12 col-sm-12 col-xs-12 company-info'>
				<center>
					<table>
						<tr class='form-info'>
							<td>
								<span>Компания: <b><?php echo $company_name; ?></b></span>&emsp;&emsp;&emsp;
								<br>
								<span>Ответственное лицо: <b><?php echo $owner;?></b></span>
							</td>
						</tr>
						<tr class='form-info'>
							<td>
								<span>Номер телофона: <b><?php echo $phone; ?></b></span>
						</tr>

						<tr class='form-info'>
							<td>
								<span>Username: <b><?php echo $username;?></b></span>&emsp;&emsp;&emsp;
								<br>
								<span>Пароль: <?php echo ($default_password=='default' && $max_fail<5) ? '<b>по умолчанию-> "Pass123"</b>' : "<form style='display:inline-block;' onsubmit='return confirm(\"Подтвердите действие.\");' action='admin-controller.php' method='post'><input type='submit' name='reset_password' class='btn btn-warning btn-xs' value='Сбросить'></form>";?></span>
							</td>
						</tr>
						<tr class='form-info'>
							<td>
								<span>Email: <b><?php echo ($email!='') ? $email : "N/A";?></b></span>&emsp;&emsp;&emsp;
								<br>
								<span><a class='btn-xs btn-info btn'>Изменит данные об компаний</a></b></span>
							</td>
						</tr>
						<tr class='form-info' style='display: none;'>
							<td>
								<form onsubmit="return confirm('Подтвердите действие!!!');" action='admin-controller.php' method='post'>
									<input type="hidden" name="data_num" value='<?php echo $company_num;?>'>
									<div class='form-group' style=''>
										<label for='company-name'>Название компаний:</label>
										<input type="text" class='form-control' name='company_name' id='company-name' value="<?php echo $company_name; ?>" required="">
									</div>
									<div class='form-group'>
										<label for='phone'>Номер телефона</label>
										<input type="text" class='form-control' name="phone" id='phone' placeholder='Номер телефона' value="<?php echo $phone;?>">
										<p class="help-block">Например: "8 777 111 2233, 8 777 333 4455 ..."</p>
									</div>
									<div class='form-group'>
										<label for='owner-name'>Ответственное лицо:</label>
										<input type="text" class='form-control' name='owner_name' id='owner-name' value="<?php echo $owner;?>" required="">
									</div>
									<div class='form-group'>
										<label for='username'>Username:</label>
										<input type="text" class='form-control' name='username' id='username' value="<?php echo $username;?>" required="">
									</div>
									<div class='form-group'>
										<label for='id-email'>Email:</label>
										<input type="email" class='form-control' name='company_email' id='id-email' value="<?php echo $email;?>">
									</div>
									<input style='display: none;' type="submit" class='btn btn-xs btn-success' name="edit_company_data" value='Изменить'>
									<input style='display: none;' type="submit" class='btn btn-xs btn-danger' name="remove_company" value='Удалить компанию'>
									<input type="reset" style='display: none;' class='btn btn-xs btn-warning' value='Отмена'>
								</form>
							</td>
						</tr>
					</table>
				</center>
			</div>
		</div>
	</div>
	<hr>
</section>
<section id='catalog'>
	<div class='container'>
		<div class='row'>
			<div class='col-md-12 col-sm-12 col-xs-12' style='height: 100%;'>
				<button id='add-to-catalog' class='btn btn-sm btn-success'><b>+</b> Добавить товары в каталог. показать\скрыть</button>
			</div>
			<div class='col-md-12 col-sm-12 col-xs-12'>
				<div class='row input-fields-for-catalog' style='display: none; border-bottom:1px dashed lightgray;'>
					<div class='col-md-6 col-sm-6 col-xs-12' style='border-right:1px dashed lightgray;'>
						<center><h4 id='empty-txt'>Корзина пуст<br><br>Выберите товар...</h4></center>
						<form id='mini-cart' action='admin-controller.php' method='post'>
							<ol></ol>
							<center><input type="submit" name="product_list" id='mini-cart-btn' class='btn btn-info btn-sm' style='display: none;' value='Сохранить'></center>
						</form>
					</div>
					<div class='col-md-6 col-sm-6 col-xs-12' style='border-left:1px dashed lightgray;'>
						<input type="text" class='form-control' id='search' placeholder="Поиск...">
						<div id='result'>
							<?php include_once('load_products.php');?>
						</div>
						<div id='hidden'></div>
					</div>
				</div>
			</div>
			<div class='col-md-12 col-sm-12 col-xs-12' style='border-top:1px solid black;'>
				<?php
					$data = array();
					try {
						$stmt = $conn->prepare("SELECT c.category_num cNum, c.category_name cName, p.product_num pNum, p.product_name pName, p.product_description pDescription, p.product_img pImg FROM category c, product p, company_catalog cc WHERE p.category_num = c.category_num AND p.product_num = cc.product_num AND cc.company_num = :company_num order by cName asc");
						$stmt->bindParam(':company_num', $company_num, PDO::PARAM_STR);
					    $stmt->execute();
					   	$result = $stmt->fetchAll();
					   	foreach ($result as $value) {
					   		$data[$value['cNum']]['name'] = $value['cName'];
					   		$data[$value['cNum']]['products'][$value['pNum']]['name'] = $value['pName']; 
					   		$data[$value['cNum']]['products'][$value['pNum']]['description'] = $value['pDescription']; 
					   		$data[$value['cNum']]['products'][$value['pNum']]['img'] = $value['pImg']; 
					   	}
					} catch (PDOException $e) {
						echo "Error : ".$e->getMessage()." !!!";
					}
				?>
				<div class='container'>
					<?php 
						$counter = 1;
						foreach ($data as $key => $value) {
							$value['products'] = array_sort($value['products'],"name");
					?>
					<div class='row category'>
						<div class='col-md-12 col-sm-12 col-xs-12'>
							<h4 class='category-section'><?php echo ($counter++).") <b><i>".$value['name']."</i></b>";?></h4>	
						</div>
						<div class='category-products col-md-11 col-md-offset-1 col-sm-offset-1 col-sm-11 col-xs-12'>
							<div class='row'>
								<?php
									foreach ($value['products'] as $pKey => $pValue) {
								?>
								<div class='col-md-2 col-sm-3 col-xs-6'>
									<center>
										<div class='product-box'>
											<div class='product-box-img <?php echo ($pValue['img']!='') ? 'img-big' : ''; ?>'>
												<img src="<?php echo ($pValue['img']!='') ? '../product_img/'.$pValue['img'] : '../img/alt.png' ;?>">
											</div>
											<p class='product-box-txt'>
												<a href="product_description.php?data_num=<?php echo $pKey;?>"><?php echo $pValue['name']?></a>
												<form onsubmit='return confirm("Вы точно хотите удалить с каталога?");' action='admin-controller.php' method='post'>
													<input type="hidden" name="data_num" value='<?php echo $pKey;?>'>
													<input type="submit" name="delete-from-company-catalog" class='btn btn-xs btn-danger' value='Удалить!'>
												</form>
											</p>
										</div>
									</center>
								</div>
								<?php } ?>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
	$order = array();
	try {
		$stmt = $conn->prepare("SELECT c.*, sum(p.price*pc.volume) total FROM cart c, product_cart pc, product p WHERE c.company_num = :company_num AND pc.product_num=p.product_num AND pc.cart_num = c.cart_num group by c.cart_num order by c.deadline desc");
		$stmt->bindParam(':company_num', $company_num, PDO::PARAM_STR);
	    $stmt->execute();
	   	$result = $stmt->fetchAll();
	   	foreach ($result as $value) {
	   		$deadline = date('d.m.Y',strtotime($value['deadline']))."<br>";
	   		$date =  date("d.m.Y");
	   		$deadline_day = (int)date('d',strtotime($value['deadline']));
	   		$deadline_month = (int)date('m',strtotime($value['deadline']));
	   		$deadline_year = (int)date('Y',strtotime($value['deadline']));
	   		$date_day = (int)date('d');
	   		$date_month = (int)date('m');
	   		$date_year = (int)date('Y');
	   		$class = "";
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
	   		$order[$value['cart_num']]['date'] =  date('d.m.Y',strtotime($value['deadline']));	   		
	   		$order[$value['cart_num']]['status'] = $text;   		
	   		$order[$value['cart_num']]['total'] = $value['total'];   		
	   	}	
	} catch (PDOException $e) {
		echo "Error : ".$e->getMessage()." !!!";
	}
?>

<section id='order'>
	<div class='container'>
		<div class='row'>
			<center>
				<h3>Заказы для "<u><?php echo $company_name;?></u>"</h3>
			</center>
		</div>
		
		<table class="table table-hover table-bordered">
		<tr>
			<th>Доставка до:</th>
			<th>Статус</th>
		</tr>

		<?php 
			$counter = 1;
			foreach ($order as $key => $value) {
		?>
			<tr>
				<td>
					<h4>
						<a href='order_detail.php?data_num=<?php echo $key;?>&company_num=<?php echo $_SESSION['company_num'];?>&approve=<?php if($value['status']=='notApproved') echo 'true'; else if($value['status']=='approved') echo 'submit'; else echo 'false'; ?>'>
							<?php echo $counter++.". ".$value['date'];?>
						</a>
						<br>
						<b>Итог: <?php echo number_format($value['total'],2,',','');?> тг.</b>
					</h4>
				</td>
				<td>
					<?php
						if($value['status']=="notApproved"){
					?>
					<form onsubmit="return confirm('Подтвердите действие!!!');" action='admin-controller.php' method='post'>
						<input type="hidden" name="extra_num" value='<?php echo $company_num;?>'>
						<input type="hidden" name="order_num" value='<?php echo $key; ?>'>
						<input type="submit" name="submit_order" class='btn btn-sm btn-success' value='Принять заказ'>
					</form>
					<?php }else if($value['status']=='fail'){ ?>
						<h4 class='text-danger'>Заказ просрочен.</h4>
					<?php }else if($value['status']=='approved'){ ?>
						<h4 class='text-success' style='display:inline-block;'>Заказ принят.</h4>
						<form onsubmit="return confirm('Заказ доствален?');" style='display:inline-block;' action='admin-controller.php' method='post'>
							<input type="hidden" name="extra_num" value='<?php echo $_SESSION['company_num'];?>'>
							<input type="hidden" name="order_num" value='<?php echo $key; ?>'>
							<input type="submit" name="close_order" class='btn btn-sm btn-success' value='Закрыть заказ'>
						</form>
					<?php }else if($value['status']=='success'){ ?>
						<h4 class='text-success'>Заказ успешно закрыт.</h4>
					<?php }?>
				</td>
			</tr>
		<?php } ?>
		</table>
		<?php
			if($counter==1) echo "<center><h4>Заказов нет.</h4></center>";
		?>
	</div>
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
<script type="text/javascript">
	$counter = 0;
	function checkCounter(counter){
		if(counter>0){
			$("#mini-cart-btn").show();
			$("#empty-txt").hide();
		}
		else{
			$("#mini-cart-btn").hide();	
			$("#empty-txt").show();
		}
	}
	$(document).on('click','#add-to-catalog',function(){
		$(".input-fields-for-catalog").toggle();
	});
	$(document).on('click','.item',function(){
		$(this).next().slideToggle();
	});
	$(document).on('click','.add-to-cart',function(){
		$counter++;
		$data_num = $(this).attr('data_num');
		$parent_num = $(this).attr('parent_num');
		$data_name = $(this).attr('data_name');
		$("#mini-cart").find("ol").prepend("<li><input type='hidden' name='products[]' value='"+$data_num+"' parent_num='"+$parent_num+"'><h4 style='display:inline-block'>"+$data_name+"</h4><a class='btn btn-xs btn-danger pull-right remove-product'>Удалить</a></li>");
		// if($(this).parents('.product').children().length-1==0){
			// $(this).parents('.product').parent().remove();
		// }
		// $(this).parent().remove();
		$(this).after("<h5 class='pull-right text-warning' style='display: inline-block;'><u><i><b>В корзине</b></i></u></h5>");
		$(this).remove();
		$("#hidden").load("reserve-product.php?s="+$data_num+"&p="+$parent_num);
		checkCounter($counter);
	});
	$(document).on('click','.remove-product',function(){
		$counter--;
		$data_num = $(this).parent().find('input').val();
		$parent_num = $(this).parent().find('input').attr('parent_num');
		$(this).parent().remove();
		$("#result").load("load_products.php?r="+$data_num+"&p="+$parent_num);
		checkCounter($counter);
	});
	$(document).on('keyup','#search',function(){
		$value = $(this).val();
		$value = $value.replace(" ","_");
		$this = $(this);
		$("#result").load("load_products.php?q="+$value,function(responseTxt, statusTxt, xhr){
			if(statusTxt == "success"){
			 	$("#search").next().find('ol').children().each(function(){
					console.log($(this).find('ul').children().length);
	    			if($(this).find('ul').children().length==0){
	    				console.log($(this));
	    				$(this).remove(); 
		    			console.log('break line');
					}
				});
	    	}
	    });
	});
	$(document).on('click','.company-catalog-btn',function(){
		$("#catalog").slideToggle();
	});
	$(document).on('click','.company-order-btn',function(){
		$("#order").slideToggle();
	});
</script>
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
	$(document).on('click','.company-info .btn',function(){
		if($(this).attr('type')!='submit'){
			$(this).parents('.company-info').find('.btn').toggle();
			$(this).parents('.company-info').find('.form-info').toggle();
		}
	});
	$(document).on('keyup','.search',function(){
		$q = $(this).val();
		$q = $q.replace(" ","_");
		$('.company-list').load("search.php?q="+$q);
	});
</script>
</body>
</html>