
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
//	$('.zan_reply .zanhead').each(function(){
//		if($(this).children().length==0){
//			$(this).prev().children('img').hide();
//		}
//	});
//	$('.zan_reply .comment').each(function(){
//		if($(this).children('.pinghead').length==0){
//			$(this).find('img').hide();
//		}
//	});
	
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


//导航跳转
$('.navigation>a').click(function(event){
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

var svalue='';
$('#search').click(function(){
	svalue=$('#s').val();
	data['pageNum']=1;
	$('.dcbox').html('');
	automatic(data)
	$('.userdata').show();
	$('.dynamichead fieldset').hide();
	$('#foot a.search').show();
	$('#foot a.home').hide();
});


//
////评论
//$('.dcbox').on('click','.date_pl',function(){
//	if($(this).is('.haspl')){
//		$(this).parents('.dynamick').find('.input_box').remove();
//		$(this).removeClass('haspl');
//	}else{
//		$(this).parents('.dynamick').append('<div class="input_box"><textarea class="input_content"></textarea><a href="javascript:;" class="send">评论</a></div>');
//		$(this).parents('.dynamick').find('.input_content').focus();
//		$(this).addClass('haspl');
//	}
//	
//});

////点击评论人的评论
//var fhname;
//$('.dcbox').on('click','.pinghead',function(){
//	fhname=$(this).find('.uname').text();
//	if($(this).is('.haspl')){
//		$(this).parents('.dynamick').find('.input_box').remove();
//		$(this).removeClass('haspl');
//	}else{
//		$(this).parents('.dynamick').append('<div class="input_box"><textarea class="input_content"></textarea><a href="javascript:;" class="send huping">评论</a></div>');
//		$(this).parents('.dynamick').find('.input_content').val('回复'+fhname+':');
//		$(this).parents('.dynamick').find('.input_content').focus();
//		$(this).addClass('haspl');
//	}
//	
//});


//表情

function showHideCode(i) {  
  $(".hide" + i).toggle();              
} 

function comment(id,e,name){
		if($(e).is('.haspl')){
		$(e).parents('.dynamick').find('.input_box').remove();
		$(e).removeClass('haspl');
	}else{
		$(e).parents('.dynamick').append('<div class="input_box"><textarea class="input_content"></textarea><a href="javascript:;" class="send" onclick="reply('+id+',this)">评论</a></div>');
		$(e).parents('.dynamick').find('.input_content').focus();
		if(name!='')
		$(e).parents('.dynamick').find('.input_content').val('回复'+name+':');
		$(e).addClass('haspl');
	}
	
	} 
function reply(id,e,name){
	$(e).parents('.dynamick').find('.pingimg').children('img').show(); 
	var text=$(e).parent('.input_box').find('.input_content').val();
	if(name!=''){
		if(text=='回复'+name+':'){
			 tip({msg:'请输出内容'});
			return false;
		}
	}
	if(text==''){
			 tip({msg:'请输出内容'});
		return false;
	}else{
		
		$.post(url+'/forum/reply',{'data':data,'content':text,'id':id},function(d){
					if(d.status==1){
					$(e).parents('.dynamick').find('.comment').append('<div class="pinghead"><img src="'+headpic+'"><div><p class="uname">'+nickname+'</p><p>'+text+'</p></div><span class="resdel" onclick="resdel(this)">删除</span></div>');
		$(e).parent('.input_box').remove();
					}else{
						flag=false;
						}
				},'json')
		
		
	}
	
	
	}

	function automatic(arr){
		if(flag){
				flag=false;
				$.post(url+'/forum/morelist?page='+arr['pageNum'],{'data':arr,'s':svalue},function(d){
					if(d.status==1){
						$('.dcbox').append(escapes(d.message));	
						data['pageNum']++ ;
						flag=true;
							loading();
					}else{
						flag=false;
						}
				},'json')
		}
			} 
			function escapes(data){
				
				var strVar = "";
				if(!data){
					return '';
				}
				for(var i=0;i<data.length;i++){
			//		 strVar +='<div class="dialog"><div class="user"><dl><dt><img src="'+string[i]['pic']+'">  </dt> <dd> <P class="username">'+string[i]['name']+'</P><div class="info">'+string[i]['message']+'</div> </dd> </dl></div>';
					 strVar+='<div class="dynamick"><div class="left userhead"><img src="'+data[i]['pic']+'"/></div><div class="right usercontent"><p class="username">'+data[i]['name']+'</p><a href="/forum/topics_info/id-'+data[i]['id']+'?'+token+'"><p class="contents">'+data[i]['content']+'</p></a><div class="imgs">'
					 if(data[i]['photos'].length){
						 	for(var j=0;j<data[i]['photos'].length;j++){
					 strVar+=' <img src="'+data[i]['photos'][j]['thumb']+'" data-src="'+imageurl+data[i]['photos'][j]['pic']+'" data-gid="g7" onload="preViewImg(this, event);" />';
							}
					 }
					 
					  strVar+='</div><div class="time_cz"><span>'+data[i]['createtime']+'</span>';
					  
					  if(data[i]['uid']==uid)
					 strVar+='<span class="commentdel" onclick="commentdel(this,'+data[i]['id']+')">删除</span>';
					  
					  strVar+='<div class="current">';
					  if(data[i]['mezan']){
								   strVar+='<span class="date_zan yizan"  onclick="zan('+data[i]['id']+',this)"><i></i><em>已赞</em></span>'  
						  }else{
					   strVar+='<span class="date_zan"  onclick="zan('+data[i]['id']+',this)"><i></i><em>赞</em></span>'}
					   strVar+='<span class="date_pl" onclick="comment('+data[i]['id']+',this,\'\')"><i></i><em>评论</em></span></div></div></div><div class="zan_reply"><div class="dianzan">';
					   if(data[i]['zanlist'].length){
						      strVar+='<div class="zanimg" ><img style="display:block" src="/public/forum/mobile/img/zan.png" /></div><div class="zanhead">';
						   	for(var j=0;j<data[i]['zanlist'].length;j++){
					 		strVar+='<img id="'+data[i]['zanlist'][j]['uid']+'" src="'+data[i]['zanlist'][j]['pic']+'">';
							}
							 strVar+='</div>';
					   }else{
						    strVar+='<div class="zanimg"><img src="/public/forum/mobile/img/zan.png" style="display:none" /></div><div class="zanhead"></div>'; 
						   }
					   
					   strVar+='</div><div class="comment">';
					   
					    if(data[i]['comment'].length){
					   strVar+='<div class="pingimg"><img src="/public/forum/mobile/img/ping.png" /></div>';   					for(var j=0;j<data[i]['comment'].length;j++){
					   strVar+='<div class="pinghead"><img src="'+data[i]['comment'][j]['pic']+'"><div><p class="uname">'+data[i]['comment'][j]['name']+'</p><p>'+data[i]['comment'][j]['content']+'</p></div>';
					   if(data[i]['comment'][j]['uid']==uid)
					    strVar+='<span class="resdel" onclick="resdel(this,'+data[i]['comment'][j]['id']+')">删除</span>';
					   
					    strVar+='</div>';
					   }
						}
					   strVar+='</div></div><div class="clear"></div></div>';
			    	
				}
				return strVar;
			}

function zan(id,e){
	
	if($(e).parents('.dynamick').find('.zanhead').find('img#'+uid).length>=1){
		
		$.post(url+'/forum/zan',{'id':id},function(d){
					if(d.status==1){
						
				 $(e).removeClass('yizan');
		$(e).find('em').text('赞');
	  $(e).parents('.dynamick').find('.zanhead').find('img#'+uid).remove();
	 if( $(e).parents('.dynamick').find('.zanhead').find('img').length<1)
	  $(e).parents('.dynamick').find('.zanimg').children('img').hide(); 		
				
						}
				},'json')
	  
		}else{
		$.post(url+'/forum/zan',{'id':id},function(d){
			if(d.status==1){
		
		 $(e).addClass('yizan');
	   $(e).find('em').text('已赞');
       $(e).parents('.dynamick').find('.zanhead').append('<img id="'+uid+'" src="'+headpic+'">');  
	$(e).parents('.dynamick').find('.zanimg').children('img').show(); 
	
					}
				},'json')
			}
	

	}

window.preViewImg = (function(){
	var imgsSrc = {};
	function reviewImage(dsrc, gid) {
		
	    if (typeof window.WeixinJSBridge != 'undefined') {
			
	        WeixinJSBridge.invoke('imagePreview', {
	            'current' : dsrc,
	            'urls' : imgsSrc[gid]
	        });
	    }else{
	    	alert("请在微信中查看", null, function(){});
	    }
	}
	function init(thi, evt){
		var dsrc = thi.getAttribute("data-src");
		var gid = thi.getAttribute("data-gid");
	

		if(dsrc && gid){
			imgsSrc[gid] = imgsSrc[gid]||[];
			imgsSrc[gid].push(dsrc);
			thi.addEventListener("click", function(){
				reviewImage(dsrc, gid);
			}, false);
		}
	}
	return init;
})();

//删除
function commentdel(e,id){
		ajaxpost({
		name:'确定删除？',
		url:url+"/forum/topics_del",
		data:{id: id,data:data},
		tip:1,
		success:function(){
			$(e).parents('.dynamick').remove();
		}
	});
	
	
}


function commentinfodel(e,id){
		ajaxpost({
		name:'确定删除？',
		url:url+"/forum/topics_del",
		data:{id: id,data:data},
		tip:1,
		success:function(){
			  window.location.href=url+"/forum/index?"+token;
		}
	});
	
	
}
//删除
function minecommentdel(e,id){
		ajaxpost({
		name:'确定删除？',
		url:url+"/forum/topics_del",
		data:{id: id,data:minedata},
		tip:1,
		success:function(){
			$(e).parents('.dynamick').remove();
		}
	});
	
	
}

function resdel(e,id){
		ajaxpost({
		name:'确定删除？',
		url:url+"/forum/comment_del",
		data:{id: id,data:data},
		tip:1,
		success:function(){
			$(e).parents('.pinghead').remove();
		}
	});
	

}

function cansezan(e,id){
		ajaxpost({
		name:'确定取消赞？',
		url:url+"/forum/zan",
		data:{id: id},
		tip:1,
		success:function(){
			$(e).parents('.dynamick').remove();
		}
	});
	

}



function minemore(arr){
	
				flag=false;
				$.post(url+'/forum/minemorelist?page='+arr['pageNum'],{'data':arr},function(d){
					if(d.status==1){
						
						
						$('#minemore').before(mineescapes(d.message));	
						minedata['pageNum']++ ;
					
							if(d.message.length<5||!d.message.length)$('#minemore').hide();
					}
				},'json')
		}
			
			function mineescapes(data){
				
				var strVar = "";
				if(!data){
					return '';
				}
				for(var i=0;i<data.length;i++){
strVar+='<div class="dynamick"><div class="left userhead"><img src="'+data[i]['pic']+'"/></div><div class="right usercontent"><p class="username">'+data[i]['name']+'</p><a href="/forum/topics_info/id-'+data[i]['id']+'?'+token+'"><p class="contents">'+data[i]['content']+'</p></a><div class="imgs"></div><div class="time_cz"><span>'+data[i]['createtime']+'</span><div class="current2"><span class="date_zan2">  <i></i> <em>赞（'+data[i]['zannum']+'）</em></span> <span class="date_pl2">  <i></i> <em>评论（'+data[i]['commentnum']+'）</em></span></div></div></div><img src="/themes/html/mobile/img/del.png" class="delpost"  onclick="minecommentdel(this,'+data[i]['id']+')"/> </div>';
				}
				return strVar;
			}


function likemore(arr){
	
				$.post(url+'/forum/likemorelist?page='+arr['pageNum'],{'data':arr},function(d){
					if(d.status==1){
						
						
						$('#likemore').before(likeescapes(d.message));	
						likedata['pageNum']++ ;
						
							if(d.message.length<5||!d.message.length)$('#likemore').hide();
					}
				},'json')
		
			} 
			function likeescapes(data){
				
				var strVar = "";
				if(!data){
					return '';
				}
				for(var i=0;i<data.length;i++){
			strVar+='<div class="dynamick"><div class="left userhead"><img src="'+data[i]['pic']+'"/></div><div class="right usercontent"><p class="username">'+data[i]['name']+' </p><a href="/forum/topics_info/id-'+data[i]['id']+'?'+token+'"><p class="contents">'+data[i]['content']+'</p></a><div class="imgs"></div><div class="time_cz"><span>'+data[i]['createtime']+'</span><div class="current2"> <span class="date_zan2" onclick="cansezan(this,'+data[i]['id']+')"> <i></i><em>取消赞</em> </span> </div></div></div></div>';

			    	
				}
				return strVar;
			}


