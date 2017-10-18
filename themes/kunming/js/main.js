$('.hot ul li').mouseover(function (){  
	$(this).find('.saoma').show();  
}).mouseout(function (){
	$(this).find('.saoma').hide();  
});
$('.contentBox h3 .smqr').mouseover(function (){  
	$('.bgqr').show();  
}).mouseout(function (){
	$('.bgqr').hide();  
});
$(function(){
	 var maxwidth=89;
     var text=$('.thjianjie').text();
	 if($('.thjianjie').text().length>maxwidth){
        $('.thjianjie').text($('.thjianjie').text().substring(0,maxwidth));
        $('.thjianjie').html($('.thjianjie').html()+"..."+"<a href='javascript:;' style='color:#18a4ec'>【查看更多】</a>");
      };
	  $('.thjianjie').find("a").click(function(){
      	$(this).parent().text(text);
      })
});