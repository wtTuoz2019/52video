$('.hot ul li').mouseover(function (){  
	$(this).find('.saoma').show();  
}).mouseout(function (){
	$(this).find('.saoma').hide();  
});
$('.smqr').mouseover(function (){  
	$('.bgqr').show();  
}).mouseout(function (){
	$('.bgqr').hide();  
});
$(function(){
	 var maxwidth=70;
     var text=$('.shjianjie').text();
	 if($('.shjianjie').text().length>maxwidth){
        $('.shjianjie').text($('.shjianjie').text().substring(0,maxwidth));
        $('.shjianjie').html($('.shjianjie').html()+"..."+"<a href='javascript:;' style='color:#18a4ec'>【查看更多】</a>");
      };
	  $('.shjianjie').find("a").click(function(){
      	$(this).parent().text(text);
      })
});