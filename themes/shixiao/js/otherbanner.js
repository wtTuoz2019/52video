//轮播
        	$(function () {
			  var w= $(window).width();
			  $('.shutter').shutter({
				shutterW: w, // 容器宽度
				shutterH: 400, // 容器高度
				isAutoPlay: true, // 是否自动播放
				playInterval: 3000, // 自动播放时间
				curDisplay: 3, // 当前显示页
				fullPage: false // 是否全屏展示
			  });
			});