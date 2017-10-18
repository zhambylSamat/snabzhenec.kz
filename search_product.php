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
	include("connection.php");
	$q = isset($_GET['q']) ? $_GET['q'] : "";
	$q = str_replace("_", " ", $q);
	// $data = $_SESSION['catalog_data'];
	$data = array();
	foreach ($_SESSION['catalog_date_client'] as $key => $value) {
		$data[$key]['name'] = $value['name'];
		$counter = 0;
		foreach ($value['products'] as $pKey => $pValue) {
			if(($q=="" || strpos(mb_strtolower($pValue['name']), mb_strtolower($q)) !== false)){
				$data[$key]['products'][$pKey]['name'] = $pValue['name']; 
		   		$data[$key]['products'][$pKey]['description'] = $pValue['description']; 
		   		$data[$key]['products'][$pKey]['img'] = $pValue['img']; 
		   		$data[$key]['products'][$pKey]['price'] = $pValue['price']; 
		   		$data[$key]['products'][$pKey]['addon'] = $pValue['addon']; 
		   		$counter++;
	   		}
		}
		if ($counter==0) {
   			unset($data[$key]);
   		}
   	} 
	$counter = 1;
	foreach ($data as $key => $value) {
		$value['products'] = array_sort($value['products'],"name",SORT_ASC);
?>
<div class='row category'>
	<div class='col-md-12 col-sm-12 col-xs-12'>
		<h4 class='category-section'><?php echo ($counter++).") <b><i>".$value['name']."</i></b>";?></h4>	
	</div>
	<div class='category-products col-md-11 col-md-offset-1 col-sm-offset-1 col-sm-11 col-xs-12' <?php if($q!='') echo "style='display:block;'"; ?>>
		<div class='row'>
			<?php
				$local_counter = 0;
				$check_by = 4;
				foreach ($value['products'] as $pKey => $pValue) {
			?>
			<div class='col-md-2 col-sm-3 col-xs-6'>
				<center>
					<div class='product-box' style=' background-color:#eee;'>
						<div class='product-box-img <?php echo ($pValue['img']!='') ? 'img-big' : ''; ?>'>
							<img src="<?php echo ($pValue['img']!='') ? 'product_img/'.$pValue['img'] : 'img/alt.png' ;?>">
						</div>
						<p class='product-box-txt'>
							<a style='cursor:pointer;' href='product_description.php?data_num=<?php echo $pKey;?>'>
								<?php echo $pValue['name']?>
								<br>
								<?php echo $pValue['price']." тг.";?>
							</a>
							<br>
							<?php
								if(isset($_SESSION['cart']) && in_array($pKey, $_SESSION['cart'])){
							?>
							<button class='btn btn-xs btn-success to-cart' data-addon='<?php echo $pValue['addon'];?>' data-price='<?php echo $pValue['price']; ?>' data-num='<?php echo $pKey;?>' added='true' data-name='<?php echo $pValue['name'];?>'>Добавлно</button>
							<?php }else{ ?>
							<button class='btn btn-xs btn-primary to-cart' data-addon='<?php echo $pValue['addon'];?>' data-price='<?php echo $pValue['price']; ?>' data-num='<?php echo $pKey;?>' added='false' data-name='<?php echo $pValue['name'];?>'>Добавить в корзину</button>
							<?php } ?>
						</p>
					</div>
				</center>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
<?php } ?>