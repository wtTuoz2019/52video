var urldomin='/content/functioninfo/id-';
	
	$(function() {
		
		var h=$('#phonetemplate').parent().css('height')
		$('#phonetemplate').css('height',h)
		
		var w=parseFloat($('#phonetemplate').css('width'));
		h=parseFloat(h);
		$('#phonetemplate #myiframe').css('left',(w/2-165)+'px')
		$('#phonetemplate #myiframe').css('top',(h/2-290)+'px')
		
		
		/*修改里面的内容*/ 
		//$('#fakehead .logo-img').append('<img src="'+imageurl+''+$('#logo').val()+'" />');
		$('#fakebody').append('<img src="'+imageurl+''+$('#image').val()+'"/>');
		//$('#fakeimg').append('<img src="'+imageurl+''+$('#titleimage').val()+'" />');
		$('#myiframe .scroll-bar marquee').text($('#notice').val());
		//if($('#logo').val()==''){
			//$('#fakehead .logo-img img').attr("src",'/themes/html/mobile/img/fakehead.jpg');
			//$('#fakehead .logo-img').empty();
		//}
		if($('#image').val()==''){
			$('#fakebody img').attr("src",'/themes/html/mobile/img/fakebody.jpg');
		}
		//if($('#titleimage').val()==''){
			//$('#fakeimg img').attr("src",'/themes/html/mobile/img/fakeimg.jpg');
			//$('#fakeimg').empty();
		//}
		if($('#notice').val()==''){
			$('#myiframe .scroll-bar').hide();
		}
		var i=0;
		
		
		$('#functions>div').each(function(index,ele) {
			var classname='';
			var id=$(this).children('input').val();
			url=urldomin+id;
			if(index==0)classname='active';
			$('#fakebutton').append('<button class="funcions'+id+' '+classname+'">'+$(this).children('button').text()+'</button>');
			
			
			
			$('#iframek').append(' <div class="tab-pane '+classname+'" id="funcions'+id+'"></div>');
			$.get(url,{},function(html){
				$("#funcions"+id).append(html);
				
			})
            
        });
		
		$('#fakebutton>button').css('width',100/$('#fakebutton>button').length+'%');
		
		$('#fakebutton').on('click','button',function(){
			$(this).addClass('active').siblings('.active').removeClass('active');
			$('#iframek>div').eq($(this).index()).addClass('active').siblings('.active').removeClass('active');
		});
		
		
		timename=setTimeout(function(){
			$("#myiframe").mCustomScrollbar();
			
		},1000);
	});
	  
	$(document).on('input propertychange', 'textarea', function() {
	  if($(this).attr('name')=='zidingyi[notice]'){
		 if($(this).val()==''){
			$('#myiframe .scroll-bar').hide();
		 }else{
			$('#myiframe .scroll-bar').show();	
		 }
		 
	  	 $('#myiframe .scroll-bar marquee').text($(this).val());
	  }
	});
	function imagechange(e){
		if($(e).attr("name")=='image'){
			if($(e).val()==''){
				$('#fakebody img').attr("src",'/themes/html/mobile/img/fakebody.jpg');
			}else{
				$('#fakebody img').attr("src",''+imageurl+''+$(e).val()+'');
			}
		}/*else if($(e).attr("name")=='zidingyi[logo]'){
			if($(e).val()==''){
				$('#fakehead .logo-img').empty();
			}else{
				$('#fakehead .logo-img').append('<img src="'+imageurl+''+$(e).val()+'" />');
			}
		}else if($(e).attr("name")=='zidingyi[titleimage]'){
			if($(e).val()==''){
				$('#fakeimg').empty();
			}else{
				$('#fakeimg').append('<img src="'+imageurl+''+$(e).val()+'" />');
			}
		}*/
	}
	/*$('#functions').on('DOMNodeInserted','button',function(){
		
	});*/
	function addcontent(id){
		url=urldomin+id;
		if($('.funcions'+id).length!=0){
			$('.funcions'+id).text($('#button'+id+'>button').text());
			
			$.get(url,{},function(html){
				$("#funcions"+id).empty();
				$("#funcions"+id).append(html);
			});
		}else{
			
			$('#myiframe #fakebutton').append('<button class="funcions'+id+'">'+$('#button'+id+'>button').text()+'</button>');
			$('#iframek').append(' <div class="tab-pane" id="funcions'+id+'"></div>');
			$('#fakebutton>button').css('width',100/$('#fakebutton>button').length+'%');
			
			$.get(url,{},function(html){
				$("#funcions"+id).append(html);
			});
			if($(".funcions"+id+"").index()==0){
				$(".funcions"+id+"").addClass('active');
				$("#funcions"+id+"").addClass('active');
			}
		}
		
	}
	//点击红包更换图片
	$('input[name="hall"]').click(function(){
		console.log(this);
		$(this).parent().addClass('checked').siblings('.checked').removeClass('checked');
		if($(this).parent().index()==0){
			$('#iframek img.tocomment').attr('src','/themes/html/mobile/img/tocomment2.jpg')
		}else{
			$('#iframek img.tocomment').attr('src','/themes/html/mobile/img/tocomment.jpg')
		}
	});




/*自己随便给的时间*/

		  	
			
 			var InterValObj;
		  	function SetRemainTime() { 
		var starttime=(new Date($('#starttime').val())).getTime();
			var endtime=(new Date($('#starttime').val())).getTime()+parseInt($('#time').val())*60000;
				var nowday=(new Date(new Date())).getTime();
				var SysSecond=starttime-nowday;
			
				if (SysSecond > 0) { 
					SysSecond = (SysSecond - 1)/1000; 
					var second = Math.floor(SysSecond % 60);             // 计算秒     
					var minite = Math.floor((SysSecond / 60) % 60);      //计算分 
					var hour = Math.floor((SysSecond / 3600) % 24);      //计算小时 
					var day = Math.floor((SysSecond / 3600) / 24);        //计算天
					if(day<10){
			 			day='0'+day;
					}
					if(hour<10){
			 			hour='0'+hour;
					}
					if(minite<10){
			 			minite='0'+minite;
					}
					if(second<10){
			 			second='0'+second;
					}
					
					var d1=String(day).substring(0,1);
					var d2=String(day).substring(1,2);
					var h1=String(hour).substring(0,1);
					var h2=String(hour).substring(1,2);
					var m1=String(minite).substring(0,1);
					var m2=String(minite).substring(1,2);
					var s1=String(second).substring(0,1);
					var s2=String(second).substring(1,2);
					$(".remain").html("<p>距离直播还有：</p><p id=\"remainTime\"></p><span>"+d1+ "</span><span>"+d2+"</span>天<span>" + h1 + "</span><span>"+h2+"</span>时<span>" + m1 + "</span><span>"+m2+"</span>分<span>" + s1 + "</span><span>"+s2+"</span>秒").show(); 
				} else {//剩余时间小于或等于0的时候，就停止间隔函数
				
				$('.remain').hide();	
				//	clearInterval(InterValObj); 
					if(endtime<nowday){
					$('.remain').html('<p>已结束</p>').show();	
						}
					
					//这里可以添加倒计时时间为0后需要执行的事件 
				} 
			}
		  	
			function daojishi(){
				
			
			InterValObj = setInterval(SetRemainTime, 1000); //间隔函数，1秒执行 	
				}
		
		
		
		
		