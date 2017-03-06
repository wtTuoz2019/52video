
//操作按钮鼠标移入移出
$("td.operation").mouseover(function (){  
    $(this).children('ul').show();  
}).mouseout(function (){
    $(this).children('ul').hide();  
});  



//视频服务  二维码显示关闭
$('.videok .close').click(function(){
	$(this).parent().hide(500);
	
});
$('.videok .videok_qr').mouseenter(function(){
	$(this).parents('.videok').find('.Qr').show(500);
});
$('.videok').mouseleave(function(){
	$(this).find('.Qr').hide(500);
});

//开启关闭
function showdiv(e){
	console.log($(e).parent().next());
	$(e).parent().next().show();
}
function hidediv(e){
	$(e).parent().next().hide();
}
