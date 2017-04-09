var commenturl="http://comment.shanyueyun.net";
	//调用微信JS api 支付
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
		var dsrc = thi.getAttribute("src");
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
	function jsApiCall(jsApiParameters)
	{
			
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			jsApiParameters,
			function(res){
				//WeixinJSBridge.log(res.err_msg);
				  if(res.err_msg == "get_brand_wcpay_request:ok"){
                  //alert(res.err_code+res.err_desc+res.err_msg);
                  $("#mymodal-data1").hide();
				  $(".modal-backdrop").hide();
                   }
				
			}
		);
	}

	function callpay(jsApiParameters)
	{
		if (typeof WeixinJSBridge == "undefined"){
			
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall(jsApiParameters);
		}
	}
	
	

	
	function deleteImg(e)
			{		  
				$(e).parent().remove();
			}
				  
    		$(".content_mid").click(function(){
				$(".conmidlist").toggle();
			});
			$("#btn").click(function(){
				 commentflag='save';
				$('#editors').attr('placeholder','');
		      $("#mymodal").modal("toggle");
		    }); 
		$('#modal_cance').click(function(e) {
            $("video").css({'height':'200px'});
        });$(document).ready(function(){ 
		   $('#input-type-submit').click(function(){
			 
				var this_ = this;
				var mes = $('.modal-body #editors').val();
				if(flag==1){
					return false;
				}
				flag==1;
			
				if(mes.length < 1){
				
					$('#messageto').html('请输入大于1字符评论').show(300).delay(3000).hide(300);
			    	return;
			    }
			$('.rightuser .comment .commentarea').css('padding-bottom','0');
			   if(commentflag=='save'){
				   
				   var formdata='';
				   formdata['fid']
if($('.choosep :input').length){
 
 $('.choosep :input').each(function(index, element) {
  formdata+=$(element).attr('name')+'_'+$(element).val()+'|';
		
    }); 
}console.log(formdata);
				$.ajax({
				  url: "/index.php/comment/save",
				  type: "POST",
				  data: {
					fid: data['fid'],
					message: mes,
					progress:$('#progressinput').val(),
					progressend:$('#progressendinput').val(),
			        uid: data['uid'],
					formdata:formdata
				  },
				  dataType: "json",
				  success: function( d ) {
					$('#editors').val('');
					if(d.status == 1){
						$('#editors').val('');
			           	flag=0;
						automatic(arr);
						
						$('#modal_cance').click();
						commentsucess(d.message);
						
					}else{
			           
						flag=0
			        }
				  }
				});	
			   }else if(commentflag=='reply'){
				 var  pid=$('input#pid').val();
				  var  toname=$('input#toname').val();
			$.post("/index.php/comment/reply_add",
	  	{	
			id:arr['fid'],
			pid:pid,
			toname:toname,
			mes:mes
	 		 },
		  function(d){
			if(d.status == 1){
		var namestring='';
		if(toname!=''){
			namestring='回复<span>'+toname+'：</span>';
			}
			$('ul #commentlist'+pid).append('<li><div class="left"><img src="'+headpic+'" style="width: 35px;height: 37px;"/></div><div class="comlistarea right"><span class="username">'+nickname+'</span><span class="resdel" onClick="resdel(this,'+d.message+')">删除</span><p>'+namestring+mes+'</p></div></li>');
			
		  }
	  },'json');
				   }
				  $('#editors').val('');
			           	flag=0;
						
						$('#modal_cance').click();
			}) 
		});
	function commentsucess(d){return false;};	
	function currentshow(){
		$('span#right').unbind();
		$('span#right').bind('click',function(e){
				 e.stopPropagation();
  				$(this).prev("#current").slideToggle();	
				})
		
		}
			
		
	//	
//		function escapes(data){
//				var strVar = "";
//				if(!data){
//					return '';
//				}
//				for(var i=0;i<data.length;i++){
//			//		 strVar +='<div class="dialog"><div class="user"><dl><dt><img src="'+string[i]['pic']+'">  </dt> <dd> <P class="username">'+string[i]['name']+'</P><div class="info">'+string[i]['message']+'</div> </dd> </dl></div>';
//					 strVar+='<li class="clearfix"><dl><dt ><p class="user-img"><img src="'+string[i]['pic']+'"></p></dt><dd><p class="user-box"><span class="user-name">'+string[i]['name']+'</span></p><p class="time" style="color: #888888;">'+string[i]['time']+'</p><p class="user-say">'+string[i]['message']+'</p></dd></dl></li>';
//			    	
//				}
//				return strVar;
//			}
	
		function automatic(arr){
		$.getJSON(commenturl + "/index.php/comment/pc_auto?callback=?",arr, function(data) { 
			
				} );	
			
		//		$.post('/index.php/comment/pc_auto',{data: arr},function(d){
//					if(d.status==1){
//						$('#container').html(escapes(d.message.info));
//						listflag['id'] = d.message.info[0]['id'];
//						$('#more').show();
//						$('#refresh').hide();
//						newcommentflag=false;
//						commentHandle();
//						currentshow();
//						
//					}else{
//						$('#refresh').hide();
//						}
//				},'json')
			}
			function commentshow(){
				
		$.getJSON(commenturl + "/index.php/comment/pc_show?mobile="+mobile+"&callback=?",arr, function(data) { 
			
				} );	
			
	
			}
		automatic(arr);
		function showhandle(d){
			if(d.status==1){
						//if(typeof(d.count)!="undefined"){
//							$('#commentnum').text(d.count);
//							}
						$('#container_show').html(escapes(d.message));
					
						
						commentHandle();
						currentshow();
						
					}
			}
		function autohandle(d){
		
			if(d.status==1){
						if(typeof(d.count)!="undefined"){
							$('#commentnum').text(d.count);
							}
						$('#container').html(escapes(d.message));
						listflag['id'] = d.message[0]['id'];
						$('#more').show();
						$('#refresh').hide();
						newcommentflag=false;
						commentHandle();
						currentshow();
						
					}else{
						$('#refresh').hide();
						}
			
			}
			function getnewcommentflag(arr){
				$.post(url+'/index.php/comment/pc_autoflag',{data: listflag},function(d){
					if(d.status==1){
						newcommentflag=true;
						$('#refresh').show();
					}else{
						$('#refresh').hide();
						}
				},'json')
			}
			
	function qwert(){
		
				if(newcommentflag==false){
					$.getJSON(commenturl + "/index.php/comment/pc_autoflag?callback=?",listflag, function(data) { 
			
				} );
					
				
				}
			}
function refreshflag(d){
	
	if(d.status==1){
						newcommentflag=true;
						$('#refresh').show();
					}else{
						$('#refresh').hide();
						}
	
	}
function test(d){
	
	if(d.status==1){
						$('#commentlist').prepend(escapes(d.message));
						arr['id'] = d.message[0]['id'];
					}
	}

function commentHandle(){
			$("div#orderList").each(function(index, element) {
				if($(element).parent().parent().parent().find('#img'+uid).length){
				$(element).parent().parent().parent().find('.zan i').text('取消');

					}
				
			
               });
				
			}
			
			$('body').click(function(e) {
       		 hide();
    		});
			function show(e){
				 e.stopPropagation();
			$(e).prev("#current").slideToggle();	
				}
		
			function hide(){
				$('div#current').each(function(index, element) {
                     if($(element).is(":visible")){
						 $(element).slideToggle();
						 }
                });
				
				
				}
		$("span#right").each(function(index, element) {
                $(element).click(function(){
				
				$("span#right").eq(index).prev("#current").slideToggle();
			})
            });
			//弹出评论
			function comment(e){
				$('.choosep').show();
				$("video").css({'height':'0px'});
				$('#editors').attr('placeholder','');
				 $("#mymodal").find('#toname').val('');
				 commentflag='save';
		     	 $("#mymodal").modal("toggle");
				  if(typeof($(e).attr('pid'))!='undefined'){
				 $("#mymodal").find('#pid').val($(e).attr('pid'))
				  commentflag='reply';
				   $('.comment-area').hide()
				   	$('.choosep').hide();
				  }
				 if(typeof($(e).attr('toname'))!='undefined'){
					 
				 $("#mymodal").find('#toname').val($(e).attr('toname'));
				 $('#editors').attr('placeholder','回复'+$(e).attr('toname')+':');
				 }
				 
				 
				 var a=$(e).offset().top-730;
				 $('.contentBox #mymodal').css('top',a+'px')
				 
				
				}
				//教研评论
			function jiaoyancomment(e){
				$("video").css({'height':'0px'});
				
				$('#editors').attr('placeholder','');
				 $("#mymodal").find('#toname').val('');
				 commentflag='save';
		     	 $("#mymodal").modal("toggle");
				 $('.comment-area').show()
				
				}
		   $("li#excom").click(function(){
				showMask();
				$(this).closest(".commentarea").find("#excomment").show()
		   });
		   $("#mask").click(function(){
				$("#mask,#excomment,#current").hide();
		   });
		

	function getcomment(){
		$.post('/index.php/comment/pc_list',{data: data},function(d){
			if(d.status==1){
		
				if(d.message.pageindex!=0){
					//	console.log(d.message.pageindex);
				$('#container').append(escapes(d.message.info));	
					commentHandle();
					currentshow();
						
				}else{
				
					$('#more').text('没有更多评论');
					}
			}
		},'json')
		
		}
	function escapes(string){
				var strVar = "";
				if(!data){
					return '';
				}
				for(var i=0;i<string.length;i++){
					data['pageIndex']=string[i]['id'];
					
					if(string[i]['status']=='0'){
						continue;
						}
					 strVar +='<div class="commentarea clearfix"><div class="commentarea_left left"><img  src="'+string[i]['pic']+'" style="width: 34px;height: 34px;margin-top: 10px;"/></div><div class="commentarea_right right"><h6>'+string[i]['name']+'</h6>';
					 if(string[i]['progress']!=0){
				
			strVar += "<a  onclick=\"playseek(this) \" class=\"playseek\" data-time=\""+string[i]['progress']+"\"  end-time=\""+string[i]['progressend']+"\" style=\"float:right\"><span class=\"fa  fa-video-camera\"></span><span class=\"f-ib\">"+formatDate(new Date(string[i]['progress']*1000))+"</span></a>";
					}
					 if(parseFloat(string[i]['price'])>0){
						  strVar+='<div class="talk-content"><div class="talk-gave"><i class="mlinkfont talk-gave-i"><img src="/public//images/redpack.png" style="width:45px;height:45px;"></i><span class="talk-gave-span">'+string[i]['message']+'</span></div><div class="talk-your">打赏了一个<font color="#d7493d">'+string[i]['price']+'</font>元红包</div></div>';
					 }else{
					strVar+='<div id="mirror" class="comtop"><p id="comtopwben">'+string[i]['message']+'</p></div>';					 
						 }	
					 
					  strVar+='<div id="comtoph6"></div><div class="commid"><div class="date" style="position: relative;"><date>'+string[i]['time']+'</date>';
					if(string[i]['uid']==uid){
                    strVar+= '<span class="commentdel" onClick="commentdel(this,'+string[i]['id']+')">删除</span>';
					}
					 strVar+='<div class="current" id="current"><ul><li> <span class="glyphicon white-heart zan" onclick="goodplus('+string[i]['id']+',this)"><i>赞</i></span></li><li id="excom" pid="'+string[i]['id']+'" onClick="comment(this)"><span class="glyphicon white-comment"><i>评论</i></span></li></ul></div><span class="right" id="right"><img src="/themes/html/mobile/img/comicon.png" style="width: 20px;"></span></div></div></div><div class="commentarea_right  right combtm">';	
					 var praiseflag=string[i]['praiselist']?"":"hide";
					 strVar+='<div class="dianzan"><div class="left"><span class="glyphicon blue-heart '+praiseflag+'"></span></div><div class="userlist right">';
                if(string[i]['praiselist']){
					for(var j=0;j<string[i]['praiselist'].length;j++){	
						strVar+='<img src="'+string[i]['praiselist'][j]['picture']+'" id="img'+string[i]['praiselist'][j]['uid']+'" />';
					}
                    }
	strVar+='</div></div>';
        			strVar+='<div class="pinglun clearfix"  style="clear: both;">';
					 var resflag=string[i]['res'].length?"":"hide";
						strVar+='<div class="left"><span class="glyphicon blue-comment '+resflag+'"></span></div><div class="commentlist right" id="orderList"><ul id="commentlist'+string[i]['id']+'">'; 
					  if(string[i]['res']){
					
						var		namestring='';
						for(var k=0;k<string[i]['res'].length;k++){	
							if(string[i]['res'][k]['toname']!=''){
						namestring='回复<span>'+string[i]['res'][k]['toname']+'：</span>';
								}
					 var delstring='';
					if(string[i]['res'][k]['uid']==uid){
                      delstring='<span class="resdel" onClick="resdel(this,'+string[i]['res'][k]['id']+')">删除</span>';}
						strVar+='<li><div class="left"><img src="'+string[i]['res'][k]['pic']+'" style="width: 35px;height: 37px;"/></div><div class="comlistarea right"><span class="username">'+string[i]['res'][k]['nickname']+'</span>'+delstring+'<p pid="'+string[i]['id']+'" toname="'+string[i]['res'][k]['nickname']+'" onclick="comment(this)">'+namestring+string[i]['res'][k]['message']+'</p></div></li>';
							}
						 
						  }
					strVar+='</ul>'; 
					strVar+='</div></div></div> </div>';
					}
				return strVar;
			}
		
		
		
		
					
				
			//全文
		    /*var wben= $("#comtopwben").text();
   		    $("#comtopwben").html(wben.substring(0,49));
            $("#comtoph6").click(function(){
	            $("#comtopwben").html(wben);
	            $("#comtoph6").html("收起"); 
            });*/
		function getTotal(){
				var slideHeight=60;
				$('div#mirror').each(function(index,element){
					var disHeight=$(element).height();
				
					if(disHeight >= slideHeight){
						$(element).css({'height':slideHeight+'px','overflow':'hidden'});
						$('div#mirror').eq(index).next('#comtoph6').append('<a href="javascript:">全文</a>');
					}else{
					
						$(element).next('#comtoph6').remove();
						}
					$(element).next('#comtoph6').find('a').click(function(){
						var curHeight=$(element).height();
						if(curHeight == slideHeight){
							$(element).animate({
								height:disHeight
							},1000);
							$(element).next('#comtoph6').find('a').text('隐藏');
						}
						else{
							$(element).animate({
								height:slideHeight
							},1000);
							$(element).next('#comtoph6').find('a').text('全文');	
						}
						return false;
					});
				});	
		}
function reply(e,pid){
		var pid=pid;
		var mesage=$('#message'+pid).val();
		$('#message'+pid).val('');
			$('#message'+pid).parent().hide();
	  $.post("/user/index.php/comment/reply_add",
	  {	
		id:'{$fid}',
		pid:pid,
		mes:mesage
	  },
	  function(d){
		if(d.status == 1){
		//	alert( d.message);
			$('#commentlist'+pid).append('<li><div class="left"><img src="'+headpic+'" style="width: 35px;height: 37px;"/></div><div class="comlistarea right"><div class="username">'+nickname+'</div><p>'+mesage+'</p></div></li>');
			
		  }else{
			art.dialog({
				title: '提示',
				content: '评论需先登录！',
				button: [
					{
						name: '登录',
						callback: function () {
							window.open("http://live.shanyueyun.com/login/login.html");
						}
					},
					{
						name: '取消'
					}
				]
			});
		}
	  },'json');
	}
function commentdel(e,id){
	if(confirm('确定删除吗？')){
		
		 $.post('/index.php/comment/commentdel',{type:'comment',id:id},function(data){
			
			if(data.status==1){
				$(e).parent().parent().parent().parent().remove();
			}
		},'json');
		}
	
	}
function resdel(e,id){
	if(confirm('确定删除吗？')){
		
		 $.post('/index.php/comment/resdel',{type:'comment',id:id},function(data){
			
			if(data.status==1){
				$(e).parent().parent().remove();
				
			}
		},'json');
		}
	
	}
 function goodplus(fid,e){ 

		if($(e).find('i').text()=='赞'){
		   $.post('/index.php/change/laud',{type:'comment',fid:fid},function(data){
			
			if(data.status==1){
				$(e).find('i').text('取消');
				
				$('.userlist').eq($('.zan').index($(e))).append('<img src="'+headpic+'" id="img'+uid+'" />');		
			   $('.dianzan').eq($('.zan').index($(e))).find('.left span').removeClass('hide');
			}else{
			art.dialog({
				title: '提示',
				content: '点赞需先登录！',
				button: [
					{
						name: '登录',
						callback: function () {
							window.open("http://live.shanyueyun.com/login/login.html");
						}
					},
					{
						name: '取消'
					}
				]
			});
		}
		},'json')
		
		}else{
		 $.post('/index.php/change/canselaud',{type:'comment',fid:fid},function(data){
			
			if(data.status==1){
				$(e).find('i').text('赞');
				
				$('.userlist').eq($('.zan').index($(e))).find('#img'+uid).remove();
				if(!$('.userlist').eq($('.zan').index($(e))).find('img').length){
					$('.dianzan').eq($('.zan').index($(e))).find('.left span').addClass('hide');
					}
				
			   
			}
		},'json')
			
			}
		 
		}

	function showMask(){     
				$("#mask").css("height",$(document).height());     
				$("#mask").css("width",$(document).width());     
				$("#mask").show();     
			}  