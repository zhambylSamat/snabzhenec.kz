<?php

	include('connection.php');
	$query = isset($_GET['q']) ? $_GET['q'] : "";
	// $query = $_GET['q']=="" ? $_GET['q'] : "N/A";
	echo $query;
	$removed = isset($_GET['r']) ? $_GET['r'] : "";
	if($removed!=""){
		(int)$_SESSION['category_counter'][$_GET['p']]++;
		$index = array_search($removed,$_SESSION['selected']);
		unset($_SESSION['selected'][$index]);
	}
	// print_r($_SESSION['selected']);
	$items = $_SESSION['items'];
	$company_catalog = $_SESSION['company_catalog'];
	// print_r($_SESSION['selected']);
	echo "<ol>";
	foreach ($items as $key => $value) {
		$value['products'] = array_sort($value['products'],'name');
		if($_SESSION['category_counter'][$key]>0){
?>
	<li>
		<h4 class='item' style='cursor:pointer;'><?php echo $value['name'];?></h4>
		<ul class='product' style='display: none;'>
			<?php
				foreach ($value['products'] as $pKey => $pValue) {
					if(($query=="" || strpos(mb_strtolower($pValue['name']), mb_strtolower($query)) !== false)){

			?>
			<li style='border-bottom:1px solid lightgray; padding:5px 10px;'>
				<h5 style='display:inline-block;'><?php echo $pValue['name']; ?></h5>
				<?php if(in_array($pKey, $_SESSION['selected'])){ ?>
				<h5 class='pull-right text-warning' style='display: inline-block;'><u><i><b>В корзине</b></i></u></h5>
				<?php }else if(!isset($company_catalog[$key]['products'][$pKey])){ ?>
				<a class='btn btn-success btn-xs pull-right add-to-cart' data_num = '<?php echo $pKey;?>' data_name = '<?php echo $pValue['name'];?>' parent_num="<?php echo $key;?>">Добавить</a>
				<?php }else{ ?>
				<h5 class='pull-right text-success' style='display: inline-block;'><u><i><b>Уже в каталоге</b></i></u></h5>
				<?php } ?>
			</li>
			<?php }} ?>
		</ul>
	</li>
<?php }} echo "</ol>"; ?>