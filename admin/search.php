<?php
	include('connection.php');
	try {
		$stmt = $conn->prepare("SELECT * FROM company WHERE company_name LIKE :needle");
		$q = isset($_GET['q']) ? $_GET['q'] : "";
		$q = str_replace("_", " ", $q);
		$needle = '%'.$q.'%';
		$stmt->bindValue(':needle', $needle, PDO::PARAM_STR);
	    $stmt->execute();
	    $result_company = $stmt->fetchAll();
	} catch (PDOException $e) {
		echo "Error : ".$e->getMessage()." !!!";
	}
	if(isset($result_company)){
		foreach ($result_company as $value) {
?>
<li style='cursor: pointer;'>
	<a href="company.php?data_num=<?php echo $value['company_num'];?>"><?php echo $value['company_name'];?></a>
</li>
<?php }} ?>