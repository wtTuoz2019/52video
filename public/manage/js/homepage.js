


//iframe框高度自适应
$(document).ready(function(){
	$("#main").load(function(){ 
		$(this).height(0); //用于每次刷新时控制IFRAME高度初始化 
		var height = $(this).contents().height() + 10; 
		$(this).height( height < 500 ? 500 : height ); 
	});
}); 


$('#headnav>a').click(function(e){
	e.preventDefault();
	$(this).addClass('active').siblings('.active').removeClass('active');
	if($(this).hasClass('backindex')){
		$('#nav').html('');
		$('#main').attr('src',$(this).attr('href'));
	}else{
		$.get($(this).attr('href'),function(html){
			$('#nav').html(html);
			$('#nav a').eq(0)[0].click();
		})
	}
	//获取浏览器链接锚点
	var thisId = window.location.hash;
	
	console.log(thisId);
				
});
	
$('#nav').delegate('a','click',function(e){
	$('#content_loading').show();
	e.preventDefault();
	$('#main').html(1);
	$('#main').attr('src',$(this).attr('href'));
				
		//给当前a添加active
		$(this).addClass('active').siblings('.active').removeClass('active');
				
});

function loadFrame(obj){  
$('#content_loading').hide();
    var url = obj.contentWindow.location.href;
    if(url.indexOf("login")!=-1){  
        window.location.href="/manage/index.php/login";
    } 
}  








//导航点击跳出退出
$('#header .headuser').mouseenter(function(){
	$('#header .exit').fadeIn(500);
}).mouseleave(function (){
    $('#header .exit').fadeOut(500);
});

			