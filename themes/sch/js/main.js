$('ul.mbul li').mouseover(function (){  
	$(this).find('.saoma').show();  
}).mouseout(function (){
	$(this).find('.saoma').hide();  
});
$('.contentBox h3 .smqr').mouseover(function (){  
	$('.bgqr').show();  
}).mouseout(function (){
	$('.bgqr').hide();  
});
$('.teacherk li').mouseover(function (){  
	$(this).addClass('active').siblings('.active').removeClass('active'); 
});


$('.idx_nav>a').click(function(e){
	e.preventDefault();
	$(this).addClass('active').siblings('.active').removeClass('active');
	var id=$(this).attr('href');
	$(id).addClass('active').siblings('.active').removeClass('active');
});
