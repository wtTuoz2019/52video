jQuery.duxnotice = {   
	success:function(msg,time) {
		this.notice(msg,time,'success');
	},
	warning:function(msg,time) {          
		this.notice(msg,time,'warning');      
	},
	failure:function(msg,time) {          
		this.notice(msg,time,'failure');        
	},
	hide:function() {        
		$('.duxnotice').slideUp("speed",function(){
			$('.duxnotice').remove();
		});    
	},
	notice:function(msg,time,style) {
		var time = arguments[1] ? arguments[1] : 5000,
		obj = this;
		tips = '.duxnotice';
		$('.duxnotice').remove();
		$('#head').after('<div class="duxnotice duxnotice_'+style+'">'+msg+'</div>'); 
		$(tips).slideDown();
		$(tips).click( function(){obj.hide();});		
		var _defautlTop = $(tips).offset().top;
		var _defautlLeft = $(tips).offset().left;
		var _position = $(tips).css('position');
		var _top = $(tips).css('top');
    	var _left = $(tips).css('left');
    	var _zIndex = $(tips).css('z-index');
		$(window).scroll(function(){
			if($(this).scrollTop() > _defautlTop){
				if($.browser.msie && $.browser.version=="6.0"){
					$(tips).css({'position':'absolute','top':eval(document.documentElement.scrollTop),'left':_defautlLeft,'z-index':99999});
					$("html,body").css({'background-image':'url(about:blank)','background-attachment':'fixed'});
				}else{
					$(tips).css({'position':'fixed','top':0,'left':_defautlLeft,'z-index':99999});
				}
			}else{
				$(tips).css({'position':_position,'top':_top,'left':_left,'z-index':_zIndex});
			}
    	});
		$('html, body').animate({scrollTop:$(window).scrollTop()-1});
		
		if(time){
			setTimeout(function(){
				obj.hide();
				},time);
		}
	}
};