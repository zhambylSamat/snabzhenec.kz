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
	// $data = $_SESSION['catalog_data'];
	$data = array();
	foreach ($_SESSION['catalog_data'] as $key => $value) {
		$data[$key]['name'] = $value['name'];
		$counter = 0;
		foreach ($value['products'] as $pKey => $pValue) {
			if(($q=="" || strpos(mb_strtolower($pValue['name']), mb_strtolower($q)) !== false)){
				$data[$key]['products'][$pKey]['name'] = $pValue['name']; 
		   		$data[$key]['products'][$pKey]['description'] = $pValue['description']; 
		   		$data[$key]['products'][$pKey]['img'] = $pValue['img']; 
		   		$data[$key]['products'][$pKey]['price'] = $pValue['price']; 
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
	<div class='col-md-12 col-sm-12 col-xs-12 category-box' style='border-bottom:1px solid lightgray;'>
		<h4 class='category-section'>
			<?php echo $counter.") <b><i>".$value['name']."</i></b>";?>
		</h4>
		<button class='category-box-edit btn btn-xs btn-warning' style='margin-left:5px; display: inline-block;'>Изменить</button>
		<form onsubmit="return confirm('Подтвердите действие!!!');" action='admin-controller.php' method='post' class='form-inline category-box-form' style='display: none;'>
			<input type="hidden" name="category_num" value='<?php echo $key;?>'>
			<h4 style='display: inline-block;'><?php echo ($counter++).") ";?></h4>
			<input type="text" class='form-control' name="category_name" required="" value='<?php echo $value['name'];?>'>
			<input type="submit" name="change_category_name" class='btn btn-sm btn-success' value='Изменить'>
			<input type="reset" class='btn btn-warning btn-sm category-box-form-cancel' value='Отмена'>
		</form>
	</div>
	<div class='category-products col-md-11 col-md-offset-1 col-sm-offset-1 col-sm-11 col-xs-12' <?php if($q!="") echo "style='display:block;";?>>
		<div class='row'>
			<?php
				$local_counter = 0;
				foreach ($value['products'] as $pKey => $pValue) {
			?>
			<div class='col-md-2 col-sm-3 col-xs-6'>
				<center>
					<div class='product-box'>
						<div class='product-box-img <?php echo ($pValue['img']!='') ? 'img-big' : ''; ?>'>
							<img src="<?php echo ($pValue['img']!='') ? '../product_img/'.$pValue['img'] : '../img/alt.png' ;?>">
						</div>
						<p class='product-box-txt'>
							<a href="product_description.php?data_num=<?php echo $pKey;?>"><?php echo $pValue['name']?><br><?php echo $pValue['price']." тг.";?></a>
						</p>
						<form onsubmit="return confirm('Подтвердите действие!!!');" action='admin-controller.php' method='post'>
							<input type="hidden" name="product_num" value='<?php echo $pKey; ?>'>
							<input type="submit" name="delete-product" class='btn btn-danger btn-xs' value='Удалить'>
						</form>
					</div>
				</center>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
<?php } ?>