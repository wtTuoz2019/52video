$('.kectitle #edit').click(function(){
	$(this).hide();
	$('.kectitle #save').show();
	$('.kectitle #cancel').show();
	
	
	$('.edit_score td input').attr('disabled',false);
	$('.edit_score td input').css('color','#E5E5E5');
	
	$('.edit_score table tr:nth-child(2) td input').focus();
	
});
$('.kectitle #save').click(function(){
	$(this).hide();
	$('.kectitle #cancel').hide();
	$('.kectitle #edit').show();
	
	$('.edit_score td input').attr('disabled',true);
	$('.edit_score td input').css('color','#222');
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
	$(this).hide();
	$('.atdtitle #cancel').hide();
	$('.atdtitle #edit').show();
	
	$('.atdkaoqing .choosek').removeClass('biank');
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




$('.tablenav>a').click(function(){
	console.log(1);
	event.preventDefault();
	var id=$(this).attr('href');
	$(this).addClass('active').siblings('.active').removeClass('active');
	$(id).addClass('active').siblings('.active').removeClass('active');
});