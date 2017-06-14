
function loading(){
	
	$('.usercontent .imgs').each(function(index) {
        if($(this).children().length==1){
			$(this).children('img').css('height','355px');
			$(this).children('img').css('width','280px');
		}
		if($(this).children().length==4){
			$('.usercontent .imgs').children().eq(1).css('margin-right','180px');
		}
    });
	
	//赞和评论图标显示                                                                                       
	$('.zan_reply .zanhead').each(function(){
		if($(this).children().length==0){
			$(this).prev().children('img').hide();
		}
	});
	$('.zan_reply .comment').each(function(){
		if($(this).children('.pinghead').length==0){
			$(this).find('img').hide();
		}
	});
	
	//字数显示
	var detail;  
	var i=0;
	$('.usercontent .contents').each(function(index) {
		if($(this).text().length>65){
			i++;
			detail ="<div>" + $(this).text().substring(0, 65) + "<span style='font-size:25px' class='hide" + i + "'>...</span><span class='hide" + i  
                        + "' style = 'display:none'>" + $(this).text().substring(65 + 1, $(this).text().length)  
                        + "</span> </div><div><a href='#youhui' onclick='showHideCode(" + i + ")'><span class='hide" + i  
                        + "'>查看更多</span><span class='hide" + i + "' style = 'display:none'>隐藏</span></a></div>";
			$(this).html(detail);																																																																																																																																																																																																																																																																																																																																																																																																			
		}
    });
};
loading();

//导航跳转
$('.navigation>a').click(function(){
	event.preventDefault();
	$(this).addClass('active').siblings('.active').removeClass('active');
	var id=$(this).attr('href');
	$(id).addClass('active').siblings('.active').removeClass('active');
});




//div默认文字显示隐藏
function hide(ele) {
	if(ele.innerHTML == "<span>说点什么吧...</span>") {
		ele.innerHTML = "";
	}
}
function show(ele) {
	if(ele.innerHTML == "") {
		ele.innerHTML = "<span>说点什么吧...</span>";
	}
}

//底部搜索
$('#foot a.search').click(function(){
	$('.userdata').hide();
	$('.dynamichead fieldset').show();
	$(this).hide();
	$('#foot a.home').show();
});

//点赞 
var headimg=0;
$('.dcbox').on('click','.date_zan',function(){
		
   headimg++;
   if($(this).is('.yizan')){
        $(this).removeClass('yizan');
		var id= $(this).attr('name');
		$('#'+id).remove();
	   $(this).find('em').text('赞');
	   if($(this).parents('.dynamick').find('.zanhead').children().length==0){
		$(this).parents('.dynamick').find('.zanimg').children('img').hide();
	   }
   }else {
	   $(this).addClass('yizan');
	   $(this).find('em').text('已赞');
	   $(this).attr('name','img'+headimg);
       $(this).parents('.usercontent').next('.zan_reply').find('.zanhead').append('<img id="img'+headimg+'" src="../img/headimg.jpg">');  
	   $(this).parents('.dynamick').find('.zanimg').children('img').show(); 
   }
});
//评论
$('.dcbox').on('click','.date_pl',function(){
	if($(this).is('.haspl')){
		$(this).parents('.dynamick').find('.input_box').remove();
		$(this).removeClass('haspl');
	}else{
		$(this).parents('.dynamick').append('<div class="input_box"><textarea class="input_content"></textarea><a href="javascript:;" class="send">评论</a></div>');
		$(this).parents('.dynamick').find('.input_content').focus();
		$(this).addClass('haspl');
	}
	
});

//点击评论人的评论
var fhname;
$('.dcbox').on('click','.pinghead',function(){
	fhname=$(this).find('.uname').text();
	if($(this).is('.haspl')){
		$(this).parents('.dynamick').find('.input_box').remove();
		$(this).removeClass('haspl');
	}else{
		$(this).parents('.dynamick').append('<div class="input_box"><textarea class="input_content"></textarea><a href="javascript:;" class="send huping">评论</a></div>');
		$(this).parents('.dynamick').find('.input_content').val('回复'+fhname+':');
		$(this).parents('.dynamick').find('.input_content').focus();
		$(this).addClass('haspl');
	}
	
});

$('.dcbox').on('click','.send',function(){	
	$(this).parents('.dynamick').find('.pingimg').children('img').show(); 
	var text=$(this).parent('.input_box').find('.input_content').val();
	if($(this).is('.huping')){
		if(text=='回复'+fhname+':'){
			return false;
		}
	}
	if(text==''){
		return false;
	}else{
		$(this).parents('.dynamick').find('.comment').append('<div class="pinghead"><img src="../img/headimg.jpg"><div><p class="uname">waiting</p><p>'+text+'</p></div></div>');
		$(this).parent('.input_box').remove();
	}
	
});

//表情

function showHideCode(i) {  
  $(".hide" + i).toggle();              
}  




