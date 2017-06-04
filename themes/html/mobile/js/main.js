$('.kectitle #edit').click(function(){
	$(this).hide();
	$('.kectitle #save').show();
	$('.kectitle #cancel').show();
	
	
	$('.edit_score td select').attr('disabled',false);
	$('.edit_score td select').removeClass('nosjx');
	
	$('.edit_score table tr:nth-child(2) td input').focus();
	
});
var arr=new Array();
$('.kectitle #save').click(function(){
$('form').submit()
	
	
	
	
	
	
});
$('.kectitle #cancel').click(function(){
	window.location.reload();//刷新当前页面
	
	
})
//
$('.atdtitle #edit').click(function(){
	$(this).hide();
	$('.atdtitle #save').show();
	$('.atdtitle #cancel').show();
	
	$('.atdkaoqing .choosek').addClass('biank');
	
});
$('.atdtitle #save').click(function(){
$('form').submit()
	
});
$('.atdtitle #cancel').click(function(){
	window.location.reload();//刷新当前页面
})


$('.atdkaoqing').delegate('.biank','click',function(){
	var radioId = $(this).find('label').attr('name');
	$(this).parents('tr').find('label').removeAttr('class') && $(this).find('label').attr('class', 'checked');
})




var v1; 
$('.edit_score td input').focus(function(){
	v1=$(this).val();
	$(this).val('');
	
	$(this).attr('placeholder','输入成绩')
	
	
});
$('.edit_score td input').blur(function(){
	if($(this).val()==''){
		$(this).val(v1);
	}else{
		
	}
	
	
});




$('.tablenav>a').click(function(event){
	
	event.preventDefault();
	var id=$(this).attr('href');
	$(this).addClass('active').siblings('.active').removeClass('active');
	$(id).addClass('active').siblings('.active').removeClass('active');
});