<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
	<title></title>
	<style type="text/css">
		h3{
		  border:1px solid black;
		}
	</style>
</head>
<body>
<div id="content">
    <section id='toPdf'>
		<div>
			<p style='float:right;'>Заказик:___________________</p>
			<p>Дата:_______________________</p>	
			<p>Счет№:______________________</p>
		</div>
		<center>
			<table>
				<tr>
					<th id='one'>№</th>
					<th id='two'>Наименование товара</th>
					<th id='three'>Количество</th>
					<th id='four'>Цена</th>
					<th id='five'>Сумма</th>
				</tr>
				<tr>
					<td>1</td>
					<td>asdfs</td>
					<td>24</td>
					<td>444tg</td>
					<td>4444</td>
				</tr>
				<tr>
					<td>1</td>
					<td>asdfs</td>
					<td>24</td>
					<td>444tg</td>
					<td>4444</td>
				</tr>
				<tr>
					<td>1</td>
					<td>asdfs</td>
					<td>24</td>
					<td>444tg</td>
					<td>4444</td>
				</tr>
			</table>
		</center>
	</section>
</div>
<div id="editor"></div>
<button id="cmd">Generate PDF</button>

<!--Add External Libraries - JQuery and jspdf-->
<script src="js/jquery-1.12.3.min.js"></script>
<script src="js/jspdf.js"></script>
<script type="text/javascript">
	
	var doc = new jsPDF();
	var specialElementHandlers = {
	    '#editor': function (element, renderer) {
	        return true;
	    }
	};

	$('#cmd').click(function () {   
	    doc.fromHTML($('#content').html(), 15, 15, {
	        'width': 170,
	            'elementHandlers': specialElementHandlers
	    });
	    doc.save('sample-file.pdf');
	});

// This code is collected but useful, click below to jsfiddle link.

</script>
</body>
</html>
