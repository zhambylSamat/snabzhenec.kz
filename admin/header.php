<?php
	include('connection.php');
	if(!isset($_SESSION['admin_username'])){
		header('location:signin.php');
	}
?>
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
				<a href="#" class='btn btn-md btn-success hidden-xs new-item-btn' data-name='new-company' style='margin-top:8px;'><b>+</b> Добавить компанию</a>
				<a href="#" class='btn btn-md btn-info hidden-xs new-item-btn' data-name='new-product' style='margin-top:8px;'><b>+</b> Добавить продукт</a>
				<center><a href="logout.php" class='btn btn-sm btn-warning hidden-sm hidden-md hidden-lg' style='margin-top:10px;'>Выйти</a></center>
		    </div>

		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			    <ul class="nav navbar-nav navbar-right">
			    	<li><a href="logout.php" style='color:black;' class='hidden-xs'><u><b>Выйти</b></u></a></li>
			    	<li><a href="#" class='hidden-md hidden-lg hidden-sm new-item-btn' data-name='new-company'><b>+</b> Добавить компанию</a></li>
			    	<li><a href="#" class='hidden-md hidden-lg hidden-sm new-item-btn' data-name='new-product'><b>+</b> Добавить продукт</a></li>
			    	<li><a href="index.php" class="navigation" style='cursor:pointer;' data-name='product_list'>Новые заказы/Главная</a></li>
			    	<li><a href="catalog.php" class="navigation" style='cursor:pointer;' data-name='product_list'>Каталог</a></li>
			    	<!-- <li><a class="navigation" style='cursor:pointer;' data-name='product_list'>Список продукт/Главная</a></li> -->
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

<section id='new-item'>
	<div class='container'>
		<div class='row'>
			<div class='col-md-12 col-sm-12 col-xs-12'>
				<div class='new-item new-company' data-hide='y'>
					<form action='admin-controller.php' method='post'>
						<div class='row'>
							<div class='col-md-6 col-sm-6 col-xs-12'>
								<div class='form-group'>
									<label for='company-name'>Название компании</label>
									<input type="text" class='form-control' name="company_name" id='company-name' placeholder='Название компании' required="">
								</div>
								<div class='form-group'>
									<label for='owner-name'>Имя покупателя</label>
									<input type="text" class='form-control' name="owner_name" id='owner-name' placeholder="Имя покупателя" required="">
								</div>
								<div class='form-group'>
									<label for='email'>Существующая почта компаний</label>
									<input type="email" class='form-control' name="email" id='email' placeholder="E-mail">
								</div>
								<div class='form-group'>
									<label for='phone'>Номер телефона</label>
									<input type="text" class='form-control' name="phone" id='phone' placeholder='Номер телефона'>
									<p class="help-block">Например: "8 777 111 2233, 8 777 333 4455 ..."</p>
								</div>
								<div class='form-group'>
									<label for='username'>Имя пользователь</label>
									<input type="text" class='form-control' name="username" id='username' placeholder='Придумайте' required="">
									<p class="help-block">Например: "Rixos.Almaty"</p>
									<p class="help-block">Пароль по умолчанию: "Pass123"</p>
								</div>
							</div>
							<div class='col-md-6 col-sm-6 col-xs-12'>
								<label>Описание:</label>
								<textarea name='company_description' class="form-control" rows="7"></textarea>
							</div>
							<div class='col-md-12 col-sm-12 col-xs-12'>
								<input type="submit" class='btn btn-default btn-sm' name="submit-company" value='Сохранить' style='margin:15px;'>
							</div>
						</div>
					</form>
				</div>
				<div class='new-item new-product' data-hide='y'>
					<form action='admin-controller.php' method='post' enctype="multipart/form-data">
						<div class='row'>
							<div class='col-md-6 col-sm-6 col-xs-12'>
								<div class='form-group'>
									<label for='category-name'>Укажите категорию товара</label>
									<br>
									<select class="form-control" name='category' style='display: inline-block; width: 40%;' required="">
									  <option value=''>Выберите</option>
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
									  <option value='<?php echo $value['category_num'];?>'><?php echo $value['category_name']?></option>
									  <?php }} ?>
									</select>
									<span>&nbsp;или&nbsp;</span>
									<input type="text" class='form-control' name="new_category" id='category-name' placeholder="Придумайте" style='display: inline-block; width: 40%;'>
								</div>
								<div class='form-group'>
									<label for='product-name'>Название продукта</label>
								<input type="text" class='form-control' name="new_product" id='product-name' placeholder="Название продукта" required="">
								</div>
									<table>
										<tr>
											<td>
												<label for='price'>Стоимость за одну единицу товара (тг.)</label>
												<input type="number" min='0.1' value='1' step='0.1' class='form-control' name="price" id='price' placeholder="тг." required="">
											</td>
											<td style='padding-left:20px;'>
												<label for='addon'>Единица измерения</label>
												<input type="text" class='form-control' id='addon' name="addon" value="" placeholder="Прим.: кг." required="">
											</td>
										</tr>
									</table>
								<div class='form-group'>
									<label for='product-image'>Выбрать изображение</label>
									<input type="file" class='form-control' id='product-image' name="product_image" value="">
								</div>
							</div>
							<div class='col-md-6 col-sm-6 col-xs-12'>
								<div class='form-group'>
									<label>Описание</label>
									<textarea class="form-control" name='product_description' rows="7"></textarea>
								</div>
							</div>
							<div class='col-md-12 col-sm-12 col-xs-12'>
								<input type="submit" class='btn btn-default btn-sm' name="submit-product" value='Сохранить' style='margin:15px;'>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>