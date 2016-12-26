//验证码
fleshVerify();
function fleshVerify()
{
var timenow = new Date().getTime();
$('#verifyImg').attr('src',app+'/verification/verify_img?'+timenow);
}
//表单提交
function saveform(config){
	$('#form').duxform(function(){
	if(typeof config.callback == "function"){
		config.callback();
	}
	savebutton(0);
	$('#form').ajaxSubmit({
		dataType: "json",
		success: function(json) {
		savebutton(1);
		if (json.status == 1) {
			if(typeof config.success == "function"){
			config.success(json.message);
			}
		} else {
			if(typeof config.failure == "function"){
			config.failure(json.message);
			}
		}
		}
	});
	return false;
	});
}

//ajax提交
function ajaxpost(config){
	$.duxnotice.warning('系统正在处理您的请求，请稍后！');
	$.ajax({
			type: 'POST',
			url: config.url,
			data: config.data,
			dataType: 'json',
			success: function(json) {
				if (json.status == 1) {
					if(typeof config.success == "function"){
					config.success(json.message);
					}
				} else {
					if(typeof config.failure == "function"){
					config.failure(json.message);
					}
				}
			}
	});
}

//ajax提交含有确认提示
function ajax_confirm(config){
	art.dialog.through({
	    content: config.name,
	    lock: true,
	    icon: 'warning',
	    button: [{
			name: '确认操作',
			callback: function() {
			$.duxnotice.warning('系统正在处理您的请求，请稍后！');
			$.ajax({
			type: 'POST',
			url: config.url,
			data: config.data,
			dataType: 'json',
			success: function(json) {
				
				if (json.status == 1) {
					if(typeof config.success == "function"){
						config.success(json.message);
					}
				} else {
					if(typeof config.failure == "function"){
						config.failure(json.message);
					}
				}
			}
		});
		},
		focus: true
		},
		{
			name: '取消',
			callback: function() {
				  if(typeof config.cancel == "function"){
					config.cancel();
				}
			}
		}]
	});
	
}

//按钮锁定
function savebutton(type){
	var type;
	if(type==1){
		txt=$(":submit").text();
		txt=txt.replace("中...","");
		$(":submit").text(txt);
		$(":submit").removeClass('button_ds');
		$(":submit").removeAttr("disabled");
	}else{
		$(":submit").text($(":submit").text()+'中...');
		$(":submit").addClass('button_ds');
		$(":submit").attr("disabled", "disabled");
	}
}
