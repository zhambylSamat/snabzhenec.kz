<?php 
	include_once('connection.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
	<title>Sign in</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/bootstrap-theme.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
</head>
<body>
<?php if(isset($_SESSION['n']) && $_SESSION['n']=='true'){?>
<section id='alert' style='position:absolute; top:3%; z-index: 100; width: 50%; left:25%;'>
	<?php
		if(isset($_GET['left']) && $_GET['left']=='block'){
	?>
	<div class="alert alert-danger alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<center>Превысили лимит входа! Аккаунт <strong>( <?php echo $_GET['name']; ?> ) заблокирован</strong>.</center>
	</div>
	<?php 
		}else if(isset($_GET['left']) && $_GET['left']!='block'){ 
	?>
	<div class="alert alert-danger alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<center>Осталось количество попыток <strong><?php echo $_GET['left'];?></strong>. </center>
	</div>
	<?php 
		}else if(isset($_GET['blocked'])){ 
	?>
	<div class="alert alert-danger alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<center>Превысили лимит входа! Аккаунт <strong>( <?php echo $_GET['name']; ?> ) заблокирован</strong>.</center>
	</div>
	<?php 
		}else if(isset($_GET['noUser'])){ 
	?>
	<div class="alert alert-danger alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<center>Аккаунт  таким именем не сушествует.</strong>.</center>
	</div>
	<?php } ?>
	<?php $_SESSION['n'] = "false"; ?>
	<!-- <div class="alert alert-success alert-dismissible" role="alert" style='margin:0; box-shadow: 0px 0px 50px black;'>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<strong>Warning!</strong> Better check yourself, you're not looking too good.
	</div> -->
</section>
<?php } ?>
	<section id='sign-in'>
		<div class='container'>
			<div class='row'>
				<div class='col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1'>
					<form style='margin-top:30%;' action='user_controller.php' method='post'>
						<h4>
							<center><b>Войти в систему</b></center>
						</h4>
						<div class='form-group'>
							<label for='login'>Имя пользователя</label>
							<input type="text" name="username" id='login' class='form-control' placeholder='Имя пользователя' autofocus required="">
						</div>
						<div class='form-group'>
							<label for='password'>Пароль</label>
							<input type="password" name="password" id='password' class='form-control' placeholder="Пароль" required="">
						</div>
						<input type="submit" name="signin" value='Отправить' class='btn btn-success btn-sm pull-right'>
					</form>
				</div>
			</div>
		</div>
	</section>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/less.min.js"></script>
</body>
</html>