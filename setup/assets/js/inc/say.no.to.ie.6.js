var IE6 = (navigator.userAgent.indexOf("MSIE 6")>=0) ? true : false;
if(IE6){
	window.attachEvent("onload", function(){
		var now = new Date();
		var time = now.getTime();
		var div = document.createElement('a');
		var id = 'saynotoie6_div_' + time;
		div.setAttribute('id',id);
		div.setAttribute('href','http://www.savethedevelopers.org/');
		div.setAttribute('target','_blank');
		div.style.display = 'block';
		div.style.color = '#1d1d1d';
		div.style.textAlign = 'left';
		div.style.fontFamily = 'Arial,sans-serif';
		div.style.fontSize = '11px';
		div.style.background = 'url(http://www.savethedevelopers.org/images/popDown.gif)';
		div.style.padding = '0';
		div.style.position = 'absolute';
		div.style.top = '0';
		div.style.right = '40px';
		div.style.zIndex = '999999';
		div.style.width = '330px';
		div.style.height = '63px';
		div.style.marginTop = '-80px';
		div.style.filter = 'alpha(opacity=95)';
		document.body.appendChild(div);
		
		var stdorg_animate = function(){
			var myDiv = document.getElementById(id);
			var value = parseInt(myDiv.style.marginTop);
			myDiv.style.marginTop = value + 1 + 'px';
			if(parseInt(myDiv.style.marginTop) < -1){
				var timer = setTimeout(stdorg_animate,30 * 80/Math.abs(value) * .27);
			}else{
				var timer = setTimeout(stdorg_hide,5000);
			}
		};
		
		var stdorg_hide = function(){
			var myDiv = document.getElementById(id);
			var value = parseInt(myDiv.style.marginTop);
			myDiv.style.marginTop = value - 1 + 'px';
			if(parseInt(myDiv.style.marginTop) > -80){
				var timer = setTimeout(stdorg_hide,1.4 * 80/Math.abs(value) * 3.70);
			}
		};
		
		setTimeout(stdorg_animate,1000);
	});
}