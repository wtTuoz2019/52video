var HTML5_ID_BASE=0;
function html5playerRun(conf){
	var mode = /^\d{0,6}(\%)?$/;
	var width = mode.test(conf.width) ? conf.width : '100%';
	var height = mode.test(conf.height) ? conf.height : '100%';
	HTML5_ID_BASE++;
	this.uuid  /*string*/ = 'html5Media' + HTML5_ID_BASE;
	this.hlsUrl=conf.hlsUrl;
	this.container=conf.mediaid;
	this.autostart=conf.autostart;
	this.volume = conf.volume ? conf.volume : 80;//音量	
	this.adveDeAddr = conf.adveDeAddr ? conf.adveDeAddr : '';//播放前显示图片地址
	var _this=this;		
	//var html='<video id="'+this.uuid+'" controls preload="auto" width="'+width+'" height="'+height+'" poster="'+this.adveDeAddr+'" webkit-playsinline src="'+this.hlsUrl+'" type="application/x-mpegURL"></video>';
	var html="<div class='videoPoster'>";
	html+="<img style='top: 50%;left: 50%;-webkit-transform: translate(-50%,-50%);transform: translate(-50%,-50%);position: absolute;cursor:pointer;' src='http://i.pengxun.cn/zhibo/img/play.png' width='80px'>";
	html+="<img src='"+this.adveDeAddr+"' style='cursor:pointer;border:0;width:100%;' />";
    html+="</div>";
	html+="<video class='video' poster='"+this.adveDeAddr+"' controls='' src='"+this.hlsUrl+"' style='display:none;width:100%;' x-webkit-airplay='allow' webkit-playsinline='yes'></video>";	
	document.getElementById(conf.container).innerHTML=html;
	if(typeof(conf.lssCallBackFunction) == 'function'){
		conf.lssCallBackFunction();}
}