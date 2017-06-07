//div默认文字显示隐藏
function hide(ele) {
	if(ele.innerHTML == "<span>有什么新鲜事想告诉大家？</span>") {
		ele.innerHTML = "";
	}
}
function show(ele) {
	if(ele.innerHTML == "") {
		ele.innerHTML = "<span>有什么新鲜事想告诉大家？</span>";
	}
}


//帖子照片显示大小
$(function(){
	
	$('.postcontent .postimg').each(function(){
		if($(this).children('img').length>1){
			$(this).children('img').css('width','150px');
			$(this).children('img').css('height','150px');
		}else{
			$(this).children('img').css('width','300px');
			$(this).children('img').css('height','300px');
		}
	});
	
	
});

//个人中心的点击切换
$('#nav a').click(function(){
	event.preventDefault();
	var id=$(this).attr('href');
	$(this).addClass('active').siblings('.active').removeClass('active');
	$(id).addClass('active').siblings('.active').removeClass('active');
	
});

//收到的评论 和发出的评论切换

$('.messagenav>a').click(function(){
	event.preventDefault();
	var id=$(this).attr('href');
	$(this).addClass('active').siblings('.active').removeClass('active');
	$(id).addClass('active').siblings('.active').removeClass('active');
});



//关闭登录
function closelg(){
	$('#pop_up').remove();
}