

//首页锚点
$('#page-index a').click(function(){
	$(this).parent().addClass('active').siblings('.active').removeClass('active');
	
});

$(document).scroll(function () {
    if ($(window).scrollTop()<745) {
       	$('#page-index li:first-child').addClass('active').siblings('.active').removeClass('active');
    }else if($(window).scrollTop()>745 && $(window).scrollTop()<1440){
		$('#page-index li:nth-child(2)').addClass('active').siblings('.active').removeClass('active');
	}else if(1440<$(window).scrollTop()){
		$('#page-index li:last-child').addClass('active').siblings('.active').removeClass('active');
	}
});

$(function(){
	$('.videotitle a').text();
	$('.nav .fnav>li>a').each(function() {
		if($(this).text()==$('.videotitle a').text()){
			$(this).parent().addClass('active').siblings('.active').removeClass('active');
		}
	});
});
//
//导航跳转
$('.hotnav>a').click(function(e){
	e.preventDefault();
	$(this).addClass('active').siblings('.active').removeClass('active');
	var id=$(this).attr('href');
	$(id).addClass('active').siblings('.active').removeClass('active');
});
$('.contentBox h3 .smqr').mouseover(function (){  
	$('.bgqr').show();  
}).mouseout(function (){
	$('.bgqr').hide();  
});




