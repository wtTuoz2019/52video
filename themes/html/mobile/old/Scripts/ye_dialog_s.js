/*
function ye_dialog_show(content)
{
	
	var html='<div id="ceng" style="filter:alpha(opacity=50); -moz-opacity:0.5;opacity:0.5;position:fixed;width:100%;height:100%;top:0px;background:#ccc;z-index:10">&nbsp;</div>';
    html+='<div id="ye_dialog_tip" style="position:fixed;top:50%;margin-top:-60px;width:300px;left:50%;margin-left:-152px;text-align:left;border:2px solid #A6A3A3;z-index:11;">';
	 html+='<div id="title" style="background:#7F7F7F;height:30px;padding:0px 10px;line-height:30px;">信息提示</div>';
	 html+='<div id="content" style="vertical-align:middle;height:60px;background:#fff;padding:10px;">'+content+'</div>';
	 html+='<div style="background:red;height:30px;line-height:30px;text-align:center;" onclick="ye_dialog_remove();">确定</div>';
	 html+='</div>';
	 
	$("body").append(html);
}

function ye_dialog_remove()
{
	$('#ye_dialog_tip').remove();
	$('#ceng').remove();
}
*/
function htmlLoadShow()
{
	
		var html='<div id="htmlLoadShow">';
		html+='<div style="filter:alpha(opacity=50); -moz-opacity:0.5;opacity:0.5;position:fixed;width:100%;height:100%;top:0px;background:#999;z-index:10"">&nbsp;</div>';
		html+='<div style="-moz-border-radius: 15px;-webkit-border-radius: 15px;border-radius: 15px;position:fixed;top:50%;margin-top:-60px;width:150px;height:150px;left:50%;margin-top:-75px;margin-left:-75px;text-align:left;border:2px solid #A6A3A3;z-index:11;background:#666;alpha(opacity=70); -moz-opacity:0.7;opacity:0.7;">';
		html+='<div style="text-align:center;margin-top: 40px;"><img src="/public/images/loadings.gif"></div>';
		html+='<div style="text-align:center;margin:25px auto;color:#fff">\u5904\u7406\u4e2d...</div>';
		html+='</div></div>';
		$("body").append(html);
	
}
function htmlLoadHide()
{
	$("#htmlLoadShow").remove();	
}

/*分页使用加载等待*/
function LoadPageShow()
{
var html='<div id="load"';
	html+=' style="position:fixed;bottom:0px;left:0px;width:100%;height:30px;text-align:center;z-index:100;';
	html+='background:#CCC;">';
	html+='    <div style="display:inline-block;margin:0px auto;line-height:30px;" id="loaded">';
	html+='       <table><tr><td>';
	html+='        <img src="/public/images/loadings.gif" style="width:20px;height:20px;"/></td>';      
	html+='        <td style="font-size:14px;color:#000;line-height:25px;">&nbsp;\u52aa\u529b\u52a0\u8f7d\u4e2d...</td></tr></table>';
	html+=' </div></div>';
	$("body").append(html);
}

function LoadPageHide(number,obj,msg)
{
	if(number)
	{
		
		setTimeout(function(){$("#load").remove();obj.append(msg); },1000);
		
	}else
	{
		
			$("#loaded").html("\u6ca1\u6709\u5566");
		
		setTimeout(function(){$("#load").remove();},1000);
	}
}