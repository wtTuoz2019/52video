//导航跳转
$('.res_nav a').click(function(e){
	e.preventDefault();
	$(this).parent().addClass('active').siblings('.active').removeClass('active');
	var id=$(this).attr('href');
	$(id).addClass('active').siblings('.active').removeClass('active');
});
$('.contentBox h3 .smqr').mouseover(function (){  
	$('.bgqr').show();  
}).mouseout(function (){
	$('.bgqr').hide();  
});

