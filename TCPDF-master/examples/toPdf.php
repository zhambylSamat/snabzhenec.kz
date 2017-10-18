<?php
//============================================================+
// File name   : example_001.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 001 for TCPDF class
//               Default Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Default Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 001');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');



// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content to print


$data = array();
$company_name = '';
$owner_name = '';
include_once('../../connection.php');
try {
  $stmt = $conn->prepare("SELECT company_name, owner_name FROM company WHERE company_num = :company_num");
  $stmt->bindParam(':company_num', $_GET['company_num'], PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $company_name = $result['company_name'];
  $owner_name = $result['owner_name'];
  $stmt = $conn->prepare("SELECT p.product_num product_num, p.product_name product_name, p.price price, p.addon addon, pc.volume volume FROM product p, product_cart pc, cart c, company com WHERE p.product_num = pc.product_num AND pc.cart_num = c.cart_num AND pc.cart_num = :cart_num AND c.company_num = :company_num AND com.company_num = c.company_num AND pc.deleted = 'n'");
  $stmt->bindParam(':company_num', $_GET['company_num'], PDO::PARAM_STR);
  $stmt->bindParam(':cart_num', $_GET['cart_num'], PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetchAll();
    foreach ($result as $value) {
      $data[$value['product_num']]['name'] = $value['product_name'];
      $data[$value['product_num']]['price'] = $value['price'];
      $data[$value['product_num']]['addon'] = $value['addon'];
      $data[$value['product_num']]['volume'] = $value['volume'];
    }
} catch (PDOException $e) {
  echo "Error : ".$e->getMessage()." !!!";
}

$date = date('d.m.Y');
$random = 0;
for($i = 0; $i < 5; $i++) {
    $random .= strval(mt_rand(0, 9));
}
$random .= date('dmY');

$ht = '<style>
    p{
      margin:50px 50px 0px 50px;
    }
    td{
      border:1px solid lightgray;
    }
    p#customer{
      text-align:justify;
    }
</style>
<div>
  <p id="customer">Компания:_'.$company_name.'_</p>
  <p id="customer">Заказчик:_'.$owner_name.'_</p>
  <p>Дата:__'.$date.'_</p> 
  <p>Счет№:_'.$random.'_</p>
</div>
<center>
  <table class="first" cellpadding="4" cellspacing="6">
    <tr>
      <th width="50" align="center">№</th>
      <th width="250" align="center">Наименование товара</th>
      <th width="100" align="center">Количество</th>
      <th width="100" align="center">Цена</th>
      <th width="100" align="center">Сумма</th>
    </tr>';
    $counter = 1;
    $grandTotal = 0;
    foreach ($data as $value) {
      $subTotal = floatval($value['price'])*floatval($value['volume']);
      $grandTotal += $subTotal;
      $ht .= '<tr>
      <td width="50" align="center">'.($counter++).'</td>
      <td width="250" align="center">'.$value['name'].'</td>
      <td width="100" align="center">'.$value['volume'].' '.$value['addon'].'</td>
      <td width="100" align="center">'.$value['price'].' тг.</td>
      <td width="100" align="center">'.$subTotal.' тг.</td>
    </tr>';
    }
   $ht .= '<tr>
      <td colspan="5">Итого к оплате:'.$grandTotal.' тг.</td>
    </tr>
</table>
</center>
<p>Принял заказ:________________________________________</p>
<p>Доставил заказ:______________________________________</p>';

$html = <<<EOF
<!-- EXAMPLE OF CSS STYLE -->
$ht
EOF;

$pdf->writeHTML($html, true, false, true, false, '');

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($company_name.'_order_'.$random.'.pdf', 'D');

//============================================================+
// END OF FILE
//============================================================+
