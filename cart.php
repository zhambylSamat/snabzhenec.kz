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
	<title>Корзина - <?php echo $_SESSION['username'];?></title>
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
			    	<li><a href="catalog.php?" class='catalog-btn'>Каталог</a></li>
			    	<li><a href="index.php?" class='order-btn'>Заказы</a></li>
			    	<li><a href="cart.php" class='cart-btn'>Корзина</a></li>
			    	<li><a href="logout.php" style='color:black;' class='hidden-xs'><u><b>Выйти</b></u></a></li>
			    </ul>
		    </div>
		  </div>
	</nav>
</section>

<section id='cart'>
	<?php
		if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])){
	?>
	<center><h2>Корзина пусто</h2></center>
	<?php }else {?>
	<div class='container'>
		<div class='row'>
			<div class='col-md-12 col-sm-12 col-xs-12'>
				<form onsubmit="return agreement();" action='user_controller.php' method='post'>
					<table class='table table-bordered' style=' background-color:#eee;'>
						<tr>
							<td colspan="3">
								<div class='form-group'>
									<label for='date'>Дата доставки:</label>
									<input style='width: 50%;' type="date" class='form-control' name="deadline" id='date' pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" required="">
								</div>
							</td>
						</tr>
						<tr>
							<th style='width: 30%;'>Наименование товара</th>
							<th style='width: 40%;'>Объем</th>
							<th style='width: 20%;'>Цена</th>
						</tr>
					<?php
						$counter = 1;
						$grandTotal = 0;
						foreach ($_SESSION['cart'] as $value) {
							if(!empty($value)){
								$index = array_search($value, $_SESSION['cart']);
					?>
					<tr>
						<td>
							<label for='product_name'><?php echo ($counter++).") ".str_replace("_", ' ', $_SESSION['products_name'][$index]);?></label>
						</td>
						<td>
							<div class='form-group'>
								<input type="hidden" name="product_num[]" value='<?php echo $value;?>'>
								<input type="number" autocomplete="off" min='0.1' step='0.1' value='1' name="product_volume[]" class='form-control volume' style='width:90%;display: inline-block;' required="">
								<span>kg</span>
								<p data-num='<?php echo $value;?>' title='Удалить товар' class='btn btn-xs btn-default glyphicon glyphicon-remove btn-delete-from-cart' style='margin-left:5px; color:#D9534F;'></p>
							</div>
						</td>
						<td>
							<input type="hidden" name='addon[]' value='<?php echo $_SESSION['addon'][$index];?>'>
							<input type="text" name="subtotal[]" class='input-subtotal form-control' value='<?php echo $_SESSION['forOne'][$index];?>'>
							Цена за "<?php echo $_SESSION['addon'][$index];?>": <span class='price' data-price='<?php echo $_SESSION['forOne'][$index];?>'><?php echo $_SESSION['forOne'][$index];?></span> тг.<br>
							Итог: <span class='subtotal'><?php echo $_SESSION['forOne'][$index];?></span> тг.
						</td>
					</tr>
					<?php }} ?>
					<tr>
						<td>
							<center><p><i>Ознакомился(-ась) с <a id='agreement' style='cursor: pointer;'>условиями договора.</a></i>&nbsp;&nbsp;<input type="checkbox" name="accepted" id='acceptOrder'></p></center>
						</td>
						<td>
							<div class='pull-right'>
								<h4><span class='btn btn-xs btn-primary sum'>Посчитать</span> Полный итог: <u id='grandTotal'>0</u> тг.</h4>
								<input type="hidden" name="grandTotal" id='input-grandTotal' value=''>
							</div>
						</td>
						<td>
							<center><input type="submit" name="product_cart" class='btn btn-sm btn-success'></center>
						</td>
					</tr>
					</table>
					<div class='form-group' style='background-color: white; border-radius: 3px; padding:2%;'>
						<label id='comment'>Примечание \ Комментарии</label>
						<textarea id='comment' name='comment' class='form-control' rows='10' placeholder="Оставьте примечание \ комментарии..."></textarea>
					</div>
				</form>	
			</div>
			<div class='col-md-12 col-sm-12 col-xs-12' id='agreement-txt' style='border:1px solid lightgray; border-radius: 5px; background-color: white; display: none;'>
				<p>
				<center><h3><b>"ДОГОВОР ПУБЛИЧНОЙ ОФЕРТЫ"</b></h3></center>
<br>
<center><h4><b>1. Общие положения</b></h4></center><br>
<b>1.1.</b> Внимательно ознакомьтесь с текстом публичной оферты, и если Вы не согласны с каким-либо пунктом оферты, Вам предлагается отказаться от покупки Товаров или использования Услуг, предоставляемых Продавцом.<br>
<b>1.2.</b> В соответствии со статьей 395 Гражданского Кодекса Республики Казахстан (далее - ГК РК) данный документ является публичной офертой, и в случае принятия изложенных ниже условий физическое лицо, производящее акцепт этой оферты, осуществляет оплату Товара Продавца в соответствии с условиями настоящего Договора. В соответствии с пунктом 3 статьи 396 ГК РК, оплата Товара Покупателем является акцептом оферты, что считается равносильным заключению Договора на условиях, изложенных в оферте.<br>
<b>1.3.</b> В настоящей оферте, если контекст не требует иного, нижеприведенные термины имеют следующие значения:<br>
• «Оферта» — публичное предложение Продавца, адресованное любому физическому/юридическому лицу, заключить с ним договор купли-продажи (далее — «Договор») на существующих условиях, содержащихся в Договоре, включая все его приложения.<br>
• «Продавец» — компания, реализующая Товар, представленный на интернет-сайте snabzhenec.kz<br>
• «Покупатель» — физическое или юридическое лицо, заключившее с Продавцом Договор на условиях, содержащихся в Договоре.<br>
• «Акцепт» — полное и безоговорочное принятие Покупателем условий Договора.<br>
• «Товар» — перечень наименований ассортимента, представленный на интернет-сайте snabzhenec.kz<br>
• «Заказ» — отдельные позиции из ассортиментного перечня Товара, указанные Покупателем при размещении заявки на интернет-сайте snabzhenec.kz.<br>
• «Сайт» – совокупность электронных документов (файлов) Продавца, доступных в сети Интернет по адресу snabzhenec.kz<br><br>
<center><h4><b>2. Предмет договора</b></h4></center><br>
<b>2.1.</b> Продавец продает Товар в соответствии с действующим прейскурантом, опубликованным на интернет-сайте Продавца, а Покупатель производит оплату и принимает Товар в соответствии с условиями настоящего Договора.<br>
<b>2.2.</b> Настоящий Договор и все приложения к нему являются официальными документами Продавца и неотъемлемой частью Оферты.<br><br>
<center><h4><b>3. Размещение Заказа</b></h4></center><br>
<b>3.1.</b> Заказ Товара осуществляется Покупателем через интернет-сайт snabzhenec.kz.<br>
<b>3.2.</b> При регистрации (размещении) Заказа на интернет-сайте Продавца, Покупатель обязуется предоставить регистрационную информацию о себе.<br>
<b>3.3.</b> Принятие Покупателем условий настоящего Договора осуществляется посредством внесения Покупателем соответствующих данных в регистрационную форму на интернет-сайте.<br>
Покупатель имеет право редактировать регистрационную информацию о себе. Продавец обязуется не сообщать регистрационные данные Покупателя на Интернет-сайте snabzhenec.kz, а также иную информацию, касающуюся личных данных Покупателя, лицам, не имеющим отношения к исполнению Заказа. Утвердив Заказ выбранного Товара, Покупатель предоставляет необходимую информацию для доставки товара.<br>
<b>3.4.</b> Покупатель несет ответственность за содержание и достоверность информации, предоставленной при размещении Заказа.<br>
<b>3.5.</b> Все информационные материалы, представленные на сайте snabzhenec.kz, носят справочный характер и не могут в полной мере передавать достоверную информацию об определенных свойствах и характеристиках Товара, таких как: цена, цвет, форма, размер и упаковка. В случае возникновения у Покупателя вопросов, касающихся свойств и характеристик Товара, перед размещением Заказа ему необходимо обратиться за консультацией или послать запрос на адрес электронной почты ans-eshenbaev.kz@mail.ru.<br><br>
<center><h4><b>4 Сроки исполнения Заказа</b></h4></center><br>
<b>4.1.</b> Срок, в который Продавец обязуется исполнить Заказ, составляет от одного рабочего дня. Срок исполнения Заказа зависит от наличия заказанных позиций Товара на складе Продавца и времени, необходимого на обработку Заказа. Срок исполнения Заказа в исключительных случаях может быть оговорен с Покупателем индивидуально в зависимости от характеристик и количества заказанного Товара. В случае отсутствия части Заказа на складе Продавца, в том числе по причинам, не зависящим от последнего, Продавец вправе аннулировать указанный Товар из Заказа Покупателя. Продавец обязуется уведомить Покупателя об изменении комплектности его Заказа путем направления сообщения на электронный адрес, указанный при регистрации на интернет-сайте, или дополнительным письменным пояснением на товарном чеке при непосредственном получении Заказа Покупателем.<br>
<b>4.2.</b> Заказ считается исполненным в момент его передачи Покупателю. Расписываясь в товарной накладной предоставляемой курьером курьерской службы Продавца, Покупатель подтверждает исполнение Заказа.<br>
<b>4.3.</b> Стоимость и условия доставки Заказа Покупатель уточняет на интернет-сайте Продавца.<br>
<b>4.4.</b> В случае предоставления Покупателем недостоверной информации о его контактных данных или составе Заказа, Продавец за ненадлежащее исполнение Заказа ответственности не несет.<br><br>
<center><h4><b>5. Оплата Заказа</b></h4></center><br>
<b>5.1.</b> Оплата исполненного Заказа по выбору Покупателя может осуществляться:<br>
<i>1)</i> путем передачи Покупателем наличных денежных средств сотруднику курьерской службы доставки Продавца. Подтверждением оплаты исполненного Заказа является товарный чек;<br>
<i>2)</i> путем перечисления денежных средств на расчетный счет Продавца;<br>

<b>5.2.</b> Цена на каждую позицию Товара отображается на сайте snabzhenec.kz. В случае изменения цены на заказанные позиции Товара, Продавец обязуется в кратчайшие сроки проинформировать Покупателя о таком изменении. Покупатель вправе подтвердить либо аннулировать Заказ. В случае отсутствия связи с Покупателем Заказ считается аннулированным в течение 14 (четырнадцати) календарных дней с момента размещения.<br>
<b>5.3.</b> Цены на любые позиции Товара, указанные на сайте, могут быть изменены Продавцом в одностороннем порядке без уведомления Покупателя.<br>
<b>5.4.</b> Оплата Покупателем самостоятельно размещенного Заказа на интернет-сайте означает согласие с Условиями настоящего Договора. День оплаты Заказа является датой заключения Договора оферты между Продавцом и Покупателем.<br><br>
<center><h4><b>6 Возврат Заказа</b></h4></center><br>
<b>6.1.</b> В течение четырнадцати дней с момента передачи товара надлежащего качества, Покупатель вправе обменять купленный товар на аналогичный товар другого размера, формы, габарита, фасона, расцветки, комплектации и т.п., произведя, в случае разницы в цене, необходимый перерасчет с продавцом. При отсутствии необходимого для обмена товара у Продавца Покупатель вправе возвратить приобретенный товар Продавцу и получить уплаченную за него денежную сумму, в этом случае Покупатель возмещает Продавцу накладные расходы за доставку Товара.<br>
<b>6.2.</b> В случае доставки Продавцом Товара ненадлежащего качества, Покупатель обязуется предоставить Товар Продавцу в порядке и на условиях предусмотренным в интернет-сайте Продавца, в максимально короткие сроки с момента покупки для осуществления проверки качества Товара.<br>
<b>6.3.</b> Право собственности на Заказ, а также риск его повреждения переходят к Покупателю с момента передачи Товара Покупателю.<br><br>
<center><h4><b>7. Прочие условия</b></h4></center><br>
<b>7.1.</b> Настоящим Покупатель соглашается с обязательными условиями настоящего Договора публичной оферты.<br>
<b>7.2.</b> В соответствии с условиями настоящего Договора публичной оферты Продавец имеет право отказать в размещении Заказа лицам, выражающим несогласие с условиями настоящего Договора.<br>
<b>7.3.</b> Продавец оставляет за собой право вносить изменения в настоящее Соглашение, всвязи с чем Покупатель обязуется регулярно отслеживать изменения в Соглашении, опубликованном на сайте snabzhenec.kz<br><br><br><br><br><br><br><br><br><br><br>
				</p>
			</div>
		</div>
	</div>
	<?php } ?>
</section>
<section id='to-cart'></section>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/less.min.js"></script>
<script type="text/javascript">
	$(document).on('click','.btn-delete-from-cart',function(){
		if(confirm("Вы точно хотите удалить товар?")){
			$data_num = $(this).attr('data-num');
			console.log($data_num);
			$('#to-cart').load("to_cart.php?added=true&data_num="+$data_num,function(responseTxt, statusTxt, xhr){
				if(statusTxt == "success"){
					console.log(responseTxt+" asdf");
					if (typeof(Storage) !== "undefined") {
					   	localStorage.setItem('cart_data', responseTxt);
					}
		    	}
		    }	);
			$(this).parents('tr').remove();
		}
	});
	$(document).on('mouseup keyup','.volume',function(){
		$quantity = $(this).val();
		$price = $(this).parents('tr').find('.price').attr('data-price');
		$total = parseFloat($quantity)*parseFloat($price);
		$total = $total.toFixed(2);
		$(this).parents('tr').find(".subtotal").html($total);
		$(this).parents('tr').find('.input-subtotal').val($total);
	});
	$(document).on('click','.sum',function(){
		$grandTotal = 0;
		$(".subtotal").each(function() {
		    $grandTotal += parseFloat($(this).html());
		});
		$grandTotal = $grandTotal.toFixed(2);
		$("#grandTotal").html($grandTotal);
		$("#input-grandTotal").val($grandTotal);
	});
	$(document).on('click','#agreement',function(){
		$("#agreement-txt").slideToggle();
	});
	function agreement(){
		var res = false;
		$grandTotal = 0;
		$(".subtotal").each(function() {
		    $grandTotal += parseFloat($(this).html());
		});
		$grandTotal = $grandTotal.toFixed(2);
		$("#grandTotal").html($grandTotal);
		$("#input-grandTotal").val($grandTotal);
		if(document.getElementById('acceptOrder').checked){ //checked
			if(confirm('Подтвердите действие!!!')) {
				if (typeof(Storage) !== "undefined") {
				   	localStorage.setItem('cart_data', '');
				}
				res = true;
			}
		}
		else{
			alert("Вы должны согласиться с условиями договора!");
		}
		return res;
	}
</script>
</body>
</html>