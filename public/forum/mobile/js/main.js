$(function(){
	$('.usercontent .imgs>img').css('height',$('.usercontent .imgs>img').width()+'px');
	
	
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
});


//导航跳转
$('.navigation>a').click(function(){
	event.preventDefault();
	$(this).addClass('active').siblings('.active').removeClass('active');
	var id=$(this).attr('href');
	$(id).addClass('active').siblings('.active').removeClass('active');
});

//评论 赞
$('.reply').click(function(){
	$(this).prev().fadeIn(500);
});
$(document).bind('click', function(e) {  
    var e = e || window.event; 
    var elem = e.target || e.srcElement;  
    while (elem) {    
        if (elem.id && (elem.className == 'current_r' || elem.className=='reply')) {  
            return;  
        }  
        elem = elem.parentNode;  
    }  
    $('.current_r').fadeOut(500);
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

//点赞  评论
function zan(e){
	$(e).parents('.usercontent').next('.zan_reply').find('.zanhead').append('<img src="../img/headimg.jpg">');
	
	if($(e).parents('.usercontent').next('.zan_reply').find('.zanhead').children().length==0){
		$(e).parents('.usercontent').next('.zan_reply').find('.zanhead').prev().children('img').hide();
	}else{
		$(e).parents('.usercontent').next('.zan_reply').find('.zanhead').prev().children('img').show();
	}
};

function dreply(e){
	$(e).parents('.usercontent').next('.zan_reply').find('.comment').append('<div class="pinghead"><img src="../img/headimg.jpg"><div><p>waiting</p><p>等等</p></div></div>');
	
	if($(e).parents('.usercontent').next('.zan_reply').find('.comment').children('.pinghead').length==0){
		$(e).parents('.usercontent').next('.zan_reply').find('.comment').find('img').hide();
	}else{
		$(e).parents('.usercontent').next('.zan_reply').find('.comment').find('img').show();
	}
};

//表情

