<?php
	include_once('connection.php');
	if(!isset($_SESSION['admin_username'])){
		header('location:signin.php');
	}
	if(isset($_POST['submit-company'])){
		try {
			$username = mb_strtolower($_POST['username']);
			$stmt = $conn->prepare("SELECT * FROM company WHERE username = :username");
			$stmt->bindParam(':username', $username, PDO::PARAM_STR);
			
			$stmt->execute();
			$count = $stmt->rowCount();
			if($count==1){
				$_SESSION['n'] = "true";
				header('location:index.php?exists='.$username);
			}
			else if($count==0){
				$company_num = uniqid('COM', true);
			    $company_name = $_POST['company_name'];
			    $owner_name = $_POST['owner_name'];
			    $username = mb_strtolower($_POST['username']);
			    $password = md5("Pass123");
			    $company_description = $_POST['company_description'];
			    $email = $_POST['email'];
			    $phone = $_POST['phone'];
				$stmt = $conn->prepare("INSERT INTO company (company_num, company_name, owner_name, username, password, company_description, email, phone) VALUES(:company_num, :company_name, :owner_name, :username, :password, :company_description, :email, :phone)");
		   
			    $stmt->bindParam(':company_num', $company_num, PDO::PARAM_STR);
			    $stmt->bindParam(':company_name', $company_name, PDO::PARAM_STR);
			    $stmt->bindParam(':owner_name', $owner_name, PDO::PARAM_STR);
			    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
			    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
			    $stmt->bindParam(':company_description', $company_description, PDO::PARAM_STR);
			    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
			    $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
			       
			    $stmt->execute();
			    $_SESSION['n'] = "true";
			    header('location:index.php?company=success');
			}
		} catch(PDOException $e) {
	        echo "Error: " . $e->getMessage();
	        // $_SESSION['n'] = "true";
	        // header('location:index.php?company=error');
	    }
	}
	else if(isset($_POST['submit-product'])){
		try {
			$category_num = $_POST['category'];
			$new_category = $_POST['new_category'];
			$new_product = $_POST['new_product'];
			$product_description = $_POST['product_description'];
			$price = $_POST['price'];
			$addon = $_POST['addon'];
			if($new_category!=""){
				$stmt = $conn->prepare("INSERT INTO category (category_num, category_name) VALUES(:category_num, :category_name)");
				$stmt->bindParam(':category_num', $category_num, PDO::PARAM_STR);
		    	$stmt->bindParam(':category_name', $new_category, PDO::PARAM_STR);
		    	$category_num = uniqid('CAT', true);
				$stmt->execute();
			}
			$product_img = '';
			$size = 'true';
			if(file_exists($_FILES['product_image']['tmp_name'])){
				echo "file exists";
				$product_img = $_FILES['product_image']['name'];
				$photo_path = "../product_img/";
				$photo_path = $photo_path.basename($product_img);
				$tmp_name = $_FILES['product_image']['tmp_name'];
				$file_size = $_FILES['product_image']['size'];
				if(!checkFile($photo_path, $tmp_name, $file_size)){
					echo "checkFile is true";
					$photo_img = '';
				}
				else{
					$size = 'false';
				}
			}

			$stmt = $conn->prepare("INSERT INTO product (product_num, category_num, product_name, product_description, product_img, price, addon) VALUES(:product_num, :category_num, :product_name, :product_description, :product_img, :price, :addon)");
   
		    $stmt->bindParam(':product_num', $product_num, PDO::PARAM_STR);
		    $stmt->bindParam(':category_num', $category_num, PDO::PARAM_STR);
		    $stmt->bindParam(':product_name', $new_product, PDO::PARAM_STR);
		    $stmt->bindParam(':product_description', $product_description, PDO::PARAM_STR);
		    $stmt->bindParam(':product_img', $product_img, PDO::PARAM_STR);
		    $stmt->bindParam(':price', $price, PDO::PARAM_STR);
		    $stmt->bindParam(':addon', $addon, PDO::PARAM_STR);

		    $product_num = uniqid('PRO',true);
		       
		    $stmt->execute();
		    $_SESSION['n'] = "true";
		    header('location:index.php?product=success&size='.$size);
			
		} catch(PDOException $e) {
	        echo "Error: " . $e->getMessage();
	        // header('location:index.php?company=error');
	    }
	}

	if(isset($_POST['delete-product'])){
		try {
			$stmt = $conn->prepare("DELETE FROM product WHERE product_num = :product_num");
			
			$stmt->bindParam(':product_num',$product_num,PDO::PARAM_STR);

			$product_num = $_POST['product_num'];

			$stmt->execute();
			$_SESSION['n'] = "true";
			header('location:index.php?delete=success');
		} catch (PDOException $e) {
			echo "Error ".$e->getMessge()." !!!";
		}
	}

	if(isset($_POST['edit-product'])){
		try {
			$product_img = '';
			$size = 'true';
			if(file_exists($_FILES['product_image']['tmp_name'])){
				echo "file exists<br>";
				$product_img = $_FILES['product_image']['name'];
				$photo_path = "../product_img/";
				$photo_path = $photo_path.basename($product_img);
				$tmp_name = $_FILES['product_image']['tmp_name'];
				$file_size = $_FILES['product_image']['size'];
				if(!checkFile($photo_path, $tmp_name, $file_size)){
					echo "checkFile is true<br>";
					$photo_img = '';
				}
				else{
					$size = 'false';
				}
			}
			else{
				$product_img = $_POST['hidden_img'];
			}

			$stmt = $conn->prepare("UPDATE product SET product_name = :product_name, category_num = :category_num, product_description = :product_description, product_img = :product_img, price = :price, addon = :addon WHERE product_num = :product_num");
	   
		    $stmt->bindParam(':product_num', $product_num, PDO::PARAM_STR);
		    $stmt->bindParam(':category_num', $category_num, PDO::PARAM_STR);
		    $stmt->bindParam(':product_name', $product_name, PDO::PARAM_STR);
		    $stmt->bindParam(':product_description', $product_description, PDO::PARAM_STR);
		    $stmt->bindParam(':product_img', $product_img, PDO::PARAM_STR);
		    $stmt->bindParam(':price', $price, PDO::PARAM_STR);
		    $stmt->bindParam(':addon', $addon, PDO::PARAM_STR);

		    $product_num = $_POST['product_num'];
		    $category_num = $_POST['category'];
		    $product_name = $_POST['product'];
		    // $product_description = $_POST['surname'];
		    $product_description = $_POST['description'];
		    $price = $_POST['price'];
		    $addon = $_POST['addon'];
		    
		    echo $_FILES['product_image']['size']."<br>";
		    echo $size."<br>";
			echo $photo_path."<br>";
		    $stmt->execute();
		    $_SESSION['n'] = "true";
		    header('location:product_description.php?data_num='.$product_num."&updated=success&size=".$size);
		} catch(PDOException $e) {
	        echo "Error: " . $e->getMessage();
	    }
	}

	function checkFile($photo_path, $tmp_name, $filesize){
		$file_test = false;
	    $imageFileType = pathinfo($photo_path,PATHINFO_EXTENSION);
		if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif" || $imageFileType == "JPG" || $imageFileType == "PNG" || $imageFileType == "JPEG" || $imageFileType == "GIF"){
			if($filesize<102400){
			// if(false){
				$file_test = false;
			}
	        else if(!file_exists($photo_path)) {
	            if(move_uploaded_file($tmp_name, $photo_path)){
	                $file_test = true;
	                echo "success<br>";
	            }
	            echo "not success<br>";
	        }
	        else{
	        	echo "alredy<br>";
	        	$file_test = true;
	        }
	    	echo $imageFileType."<br>";
	    }
	    echo 'finish<br>';
	    return $file_test;
	}
	if(isset($_POST['product_list'])){
		echo "success enter<br>";
		try {
			$query = "INSERT INTO company_catalog (company_catalog_num, company_num, product_num) VALUES";
			$data = $_POST['products'];
		    $qPart = array_fill(0, count($data), "(?, ?, ?)");
		    $query .= implode(",",$qPart);
		    $stmt = $conn->prepare($query);
		    $j = 1;
		    $company_num = $_SESSION['company_num'];
		    foreach ($data as $value) {
		    	$company_catalog_num = uniqid('COMCAT', true);
		    	$stmt->bindValue($j++, $company_catalog_num, PDO::PARAM_STR);
		    	$stmt->bindValue($j++, $company_num, PDO::PARAM_STR);
		    	$stmt->bindValue($j++, $value, PDO::PARAM_STR);
		    }
		    $stmt->execute();
		    $_SESSION['n'] = "true";
		    header('location:company.php?data_num='.$company_num."&company_catalog=success");
		} catch(PDOException $e) {
	        echo "Error: " . $e->getMessage();
	    }
	}
	if(isset($_POST['delete-from-company-catalog'])){
		try {
			$stmt = $conn->prepare("DELETE FROM company_catalog WHERE company_num = :company_num AND product_num = :product_num");
			
			$stmt->bindParam(':company_num',$company_num,PDO::PARAM_STR);
			$stmt->bindParam(':product_num',$product_num,PDO::PARAM_STR);
			$company_num = $_SESSION['company_num'];
			$product_num = $_POST['data_num'];

			$stmt->execute();
			$_SESSION['n'] = "true";
			header('location:company.php?data_num='.$company_num."&deleted=true");
		} catch (PDOException $e) {
			echo "Error ".$e->getMessge()." !!!";
		}
	}
	if(isset($_POST['reset_password'])){
		try {
			$stmt = $conn->prepare("UPDATE company SET password = :password, default_password = 'default', max_fail = 0 WHERE company_num = :company_num");
	   
		    $stmt->bindParam(':company_num', $company_num, PDO::PARAM_STR);
		    $stmt->bindParam(':password', $password, PDO::PARAM_STR);

		    $company_num = $_SESSION['company_num'];
		    $password = md5("Pass123");
		       
		    $stmt->execute();
		    $_SESSION['n'] = "true";
		    header('location:company.php?data_num='.$company_num."&reset=true");
		} catch(PDOException $e) {
	        echo "Error: " . $e->getMessage();
	    }
	}
	if(isset($_POST['change_category_name'])){
		try {
			$stmt = $conn->prepare("UPDATE category SET category_name = :category_name WHERE category_num = :category_num");
	   
		    $stmt->bindParam(':category_name', $category_name, PDO::PARAM_STR);
		    $stmt->bindParam(':category_num', $category_num, PDO::PARAM_STR);

		    $category_name = $_POST['category_name'];
		    $category_num = $_POST['category_num'];
		       
		    $stmt->execute();
		    $_SESSION['n'] = "true";
		    header('location:index.php?categoryEdit=true');
		} catch(PDOException $e) {
	        echo "Error: " . $e->getMessage();
	    }
	}
	if(isset($_POST['submit_order'])){
		try {
			$stmt = $conn->prepare("UPDATE cart SET status = 1 WHERE cart_num = :cart_num");
		    $stmt->bindParam(':cart_num', $_POST['order_num'], PDO::PARAM_STR);
		       
		    $stmt->execute();

		    $stmt=$conn->prepare("SELECT p.product_name name, pc.volume volume FROM product_cart pc, product p WHERE p.product_num = pc.product_num AND pc.cart_num = :cart_num");
		    $stmt->bindParam(':cart_num', $_POST['order_num'], PDO::PARAM_STR);
		    $stmt->execute();
		    $result = $stmt->fetchAll();

		    $stmt=$conn->prepare("SELECT * FROM company WHERE company_num = :company_num");
		    $stmt->bindParam(':company_num', $_POST['extra_num'], PDO::PARAM_STR);
		    $stmt->execute();
		    $email = $stmt->fetch(PDO::FETCH_ASSOC);

		    $_SESSION['n'] = "true";
		    $data['result'] = $result;
		    $data['email'] = $email['email'];
		    sendMessageToEmail('submit_order',$data);
		    header('location:index.php?orderApprove=true&data_num='.$_POST['extra_num']);
		} catch(PDOException $e) {
	        echo "Error: " . $e->getMessage();
	    }
	}
	if(isset($_POST['close_order'])){
		try {
			$stmt = $conn->prepare("UPDATE cart SET status = 2 WHERE cart_num = :cart_num");
		    $stmt->bindParam(':cart_num', $_POST['order_num'], PDO::PARAM_STR);
		    $stmt->execute();

		    // $stmt=$conn->prepare("SELECT p.product_name name, pc.volume volume FROM product_cart pc, product p WHERE p.product_num = pc.product_num AND pc.cart_num = :cart_num");
		    // $stmt->bindParam(':cart_num', $_POST['order_num'], PDO::PARAM_STR);
		    // $stmt->execute();
		    // $result = $stmt->fetchAll();

		    // $stmt=$conn->prepare("SELECT * FROM company WHERE company_num = :company_num");
		    // $stmt->bindParam(':company_num', $_POST['extra_num'], PDO::PARAM_STR);
		    // $stmt->execute();
		    // $email = $stmt->fetch(PDO::FETCH_ASSOC);

		    $_SESSION['n'] = "true";
		    // $data['result'] = $result;
		    // $data['email'] = $email['email'];
		    // sendMessageToEmail('submit_order',$data);
		    header('location:index.php?orderApprove=true&data_num='.$_POST['extra_num']);
		} catch(PDOException $e) {
	        echo "Error: " . $e->getMessage();
	    }
	}
	if(isset($_POST['edit_company_data'])){
		try {
			$owner_name = $_POST['owner_name'];
		    $company_name = $_POST['company_name'];
		    $username = mb_strtolower($_POST['username']);
		    $company_num = $_POST['data_num'];
		    $email = $_POST['company_email'];
		    $phone = $_POST['phone'];
			$stmt = $conn->prepare("UPDATE company SET owner_name = :owner_name, username = :username, company_name = :company_name, email = :email, phone = :phone WHERE company_num = :company_num");
		    $stmt->bindParam(':owner_name', $owner_name, PDO::PARAM_STR);
		    $stmt->bindParam(':company_name', $company_name, PDO::PARAM_STR);
		    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
		    $stmt->bindParam(':company_num', $company_num, PDO::PARAM_STR);
		    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
		    $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);

		    $stmt->execute();
		    $_SESSION['n'] = "true";
		    header('location:company.php?editCompany=true&data_num='.$company_num);
		} catch(PDOException $e) {
	        echo "Error: " . $e->getMessage();
	    }
	}
	if(isset($_POST['signin'])){
		try {
			$username = mb_strtolower($_POST['username']);
			$password = $_POST['password'];
			$stmt = $conn->prepare("SELECT * FROM admin WHERE username = :username");
			$stmt->bindParam(':username', $username, PDO::PARAM_STR);
			$stmt->execute();
		   	$result = $stmt->fetch(PDO::FETCH_ASSOC);
		   	$count = $stmt->rowCount();
		   	if($count==1 && $result['max_fail']<=4 && $result['password']==md5($password)){
		   		$stmt = $conn->prepare("UPDATE admin SET max_fail = 0 WHERE username = :username");
			    $stmt->bindParam(':username', $result['username'], PDO::PARAM_INT);
			    $stmt->execute();
		   		$_SESSION['admin_username'] = $result['username'];
		   		header("location:index.php");
		   	}
		   	else if($count == 1 && $result['max_fail']<=4 && $result['password'] != md5($password)){
		   		$stmt = $conn->prepare("UPDATE admin SET max_fail = :max_fail WHERE username = :username");
			    $stmt->bindParam(':max_fail', $max_fail, PDO::PARAM_STR);
			    $stmt->bindParam(':username', $username, PDO::PARAM_INT);
			    $company_num = $result['username'];
			    $max_fail = (int)$result['max_fail']+1;
			    $stmt->execute();
			    $left = ($max_fail==5) ? "block" : (5-$max_fail);
			    $_SESSION['n'] = 'true';
			    header('location:signin.php?left='.$left."&name=".$result['username']);
		   	}
		   	else if($count==1 && $result['max_fail']>=5){
		   		$_SESSION['n'] = 'true';
		   		header('location:signin.php?blocked=true&name='.$result['username']);
		   	}
		   	else{
		   		$_SESSION['n'] = 'true';
		   		header('location:signin.php?noUser=true');
		   	}
		} catch (PDOException $e) {
			echo "Error : ".$e->getMessage()." !!!";
		}
	}
	if(isset($_POST['remove_order'])){
		try {
			$stmt = $conn->prepare("SELECT p.product_name name, pc.volume volume, p.price price, p.addon addon FROM product_cart pc, product p WHERE pc.cart_num = :cart_num AND p.product_num = pc.product_num");
			$stmt->bindParam(':cart_num', $_POST['order_num'],PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt->fetchAll();

			$stmt = $conn->prepare("DELETE FROM product_cart WHERE cart_num = :cart_num");
			$stmt->bindParam(':cart_num',$_POST['order_num'],PDO::PARAM_STR);
			$stmt->execute();

			$stmt = $conn->prepare("DELETE FROM cart WHERE cart_num = :cart_num");
			$stmt->bindParam(':cart_num',$_POST['order_num'],PDO::PARAM_STR);

			$stmt->execute();

			$_SESSION['n'] = 'true';
			$data['result'] = $result;
			sendMessageToEmail('delete_order',$data);
		    header('location:index.php?remove_order=true');	
		} catch (PDOException $e) {
			echo "Error : ".$e->getMessage()." !!!";
		}
	}
	if(isset($_POST['submit_new_volume'])){
		try {
			$stmt = $conn->prepare("UPDATE product_cart SET volume = :volume WHERE product_num = :product_num AND cart_num = :cart_num");
		    $stmt->bindParam(':cart_num', $_POST['cart_num'], PDO::PARAM_STR);
		    $stmt->bindParam(':product_num', $_POST['data_num'], PDO::PARAM_STR);
		    $stmt->bindParam(':volume', $_POST['new_volume'], PDO::PARAM_STR);
		    $stmt->execute();
		    $_SESSION['n'] = 'true';
		    $data['product_name'] = $_POST['product_name'];
		    $data['product_volume'] = $_POST['old_volume'];
		    $data['new_volume'] = $_POST['new_volume'];
		    sendMessageToEmail('edit_order_product',$data);
		    header('location:order_detail.php?data_num='.$_POST['cart_num'].'&change=true&approve='.$_POST['approve'].'&company_num='.$_POST['company_num']);	
		} catch (PDOException $e) {
			echo "Error : ".$e->getMessage()." !!!";
		}
	}


	function sendMessageToEmail($status,$data){
		include('connection.php');
		// create_order edit_order_product delte_order_product edit_order_deadline delete_order
		if($status=="submit_order"){
			$message = "
	        <html>
	        <head>
	        <title>Поставщик принял заказ!</title>
	        </head>
	        <body>
	        <center><h3>Поставщик принял заказ!</h3></center>";
	        $message.="<center><table>
	        <tr>
	        <th>Наименование товара</th>
	        <th>Объем</th>
	        </tr>";
	        
			foreach ($data['result'] as $value) {
				$message.="<tr>
		        <td>
		        <h4>".$value['name']."</h4>
		        </td>
		        <td>
		        <h4>".$value['volume']."</h4>
		        </td>
		        </tr>";
	    	}
	    	$message.="</table></center></body></html>";
		}
		else if($status=="delete_order"){
			$data = array_sort($data,'name',SORT_ASC);
			$message = "
	        <html>
	        <head>
	        <title>Поставщик полностью удалил заказ!</title>
	        </head>
	        <body>
	        <center><h3>Поставщик полностью удалил заказ!</h3></center>";
	        $message.="<center><table>
	        <tr>
	        <th>Наименование товара</th>
	        <th>Объем</th>
	        <th>Цена</th>
	        <th>Сумма</th>
	        </tr>";
	        $grandTotal = 0;
			foreach ($data['result'] as $value) {
				$grandTotal += floatval($value['price'])*floatval($value['volume']);
				$message.="<tr>
		        <td>
		        <h4>".$value['name']."</h4>
		        </td>
		        <td>
		        <h4>".$value['volume']." ".$value['addon']."</h4>
		        </td>
		       	<td>
		        <h4>".$value['price']." тг.</h4>
		        </td>
		        <td>
		        <h4>".number_format((floatval($value['price'])*floatval($value['volume'])),2,'.','')." тг.</h4>
		        </td>
		        </tr>";
	    	}
	    	$grandTotal = number_format($grandTotal, 2, '.', '');
	    	$message .="<tr><td colspan='4'>Итог: ".$grandTotal." тг.</td></tr>";
	        $message.="</table></center></body></html>";
	    }
	    else if($status=="edit_order_product"){
			$message = "
	        <html>
	        <head>
	        <title>Заказ изменен!</title>
	        </head>
	        <body>
	        <center><h3>Поставщик изменил объем заказа!</h3></center>
	        <center><h3>Измененный товар: \"".$data['product_name']."\"</h3></center>
	        <center><h3>Объем с \"".$data['product_volume']."\" на ".$data['new_volume'].".</h3></center>";
	        $message.="</body></html>";
		}
		try {
			$stmt = $conn->prepare("SELECT * FROM admin");
			$stmt->execute();
		   	$result = $stmt->fetch(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			echo "Error : ".$e->getMessage()." !!!";
		}
		$to = $data['email'];
        $subject = "Request from service.kz";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From: <roteza.kz/service(Service)>' . "\r\n";

        mail($to,$subject,$message,$headers);
	}
	function array_sort($array, $on, $order=SORT_ASC){
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
?>