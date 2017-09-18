
$('.contentBox h3 .smqr').mouseover(function (){  
	$('.bgqr').show();  
}).mouseout(function (){
	$('.bgqr').hide();  
});

$(function(){
	$('.videotitle a').text();
	$('.nav li>a').each(function() {
		if($(this).text()==$('.videotitle a').text()){
			$(this).parent().addClass('active').siblings('.active').removeClass('active');
		}
	});
});