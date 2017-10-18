<script type="text/javascript">
	$(document).on('click','.new-item-btn',function(){
		$data_name = $(this).attr('data-name');
		if($data_name=='new-company'){
			$("."+$data_name).slideToggle();
		}
		else if($data_name=='new-product'){
			$('.'+$data_name).slideToggle();
		}
	});
	$(document).on('change','#product-image',function(){
		$img_size = $(this)[0].files[0].size;
    	$type = $(this).val().split('.').pop().toLowerCase();
    	if($.inArray($type, ['gif','png','jpg','jpeg'])>=0) {
	        if($img_size>307200){
	        	alert('Ошибка! Максимальный размер изображении 300КБ ~ (307200 байт). Размер загруженного изображения = '+$img_size+' байт.');
	        	if($(this).val()!=''){
	        		$(this).val('');
	        	}
	        }
    	}
    	else{
    		alert('Ошибка! Расширение файла должно быть (.gif, .png, .jpg, .jpeg) правильно выбрано!');
        	if($(this).val()!=''){
        		$(this).val('');
        	}
    	}
	});
	$(document).on('keyup','#category-name',function(){
		console.log("1111");
		if($(this).val()==''){
			$(this).prev().prev().attr('required','');
		}
		else{
			$(this).prev().prev().removeAttr('required');	
		}
	});
	$(document).on('click','.navigation',function(){
		$data_name = $(this).attr('data-name');
		if($data_name=='product_list'){
			$("#body").load('products_list.php');
		}
	});
	$(document).on('keyup','.search',function(){
		$q = $(this).val();
		$q = $q.replace(" ","_");
		$('.company-list').load("search.php?q="+$q);
	});
</script>