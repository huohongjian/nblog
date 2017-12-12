/** version 1.0.0
 ** date: 2017-12-10
 ** author: HuoHongJian
 */	
Panel = function(cfg) {
	var t=this;
	t.width = 300;
	t.height = 200;
	t.title = '信息';
	t.titleHeight = 38;
	t.display = 'block';
	t.zIndex = 9;
	t.movable = true;
	t.textAlign = 'center';
	for(var k in cfg) {
		t[k] = cfg[k];	
	}
}
Panel.LEN = 0;
Panel.prototype = {
	constructor: Panel,
	toggle: function(display){
		var t=this;t.display=display||(t.display=='block'?'none':'block');
		t.panel.style.display=t.display;return t;
	},
	show: function(time){var t=this;setTimeout(function(){t.toggle('block');if(t.onshow)t.onshow()},time||0);return t},
	hide: function(time){var t=this;setTimeout(function(){t.toggle('none'); if(t.onhide)t.onhide()},time||0);return t},
	twinkle: function(time){this.show();if(time>0)this.hide(time);return this},
	destory: function(){var t=this;t.panel.parentNode.removeChild(t.panel);t.panel=undefined;if(t.ondestory)t.ondestory();return t},
	setHtml: function(html){this.R('._panel_content_', this.panel).innerHTML=html;return this},

	writeCSS: function(){
		this.R('head').appendChild(this.C('style', {type:'text/css', textContent:'\
			.panel {width:300px; height:200px; background:#fff; position:fixed; border:1px solid #ddd;}\
			.panel > header {height:38px; line-height:38px; cursor:move; padding:0 10px; border-bottom:1px solid #ddd; overflow:hidden;}\
			.panel > header ul {float:right; margin:8px;}\
			.panel > header li {cursor:pointer; margin-left:15px; display:inline-block; list-style-type:none;}\
			.panel > ._panel_content_ {display:table; width:100%;}\
			.panel > ._panel_content_ > m,\
			.panel > ._panel_content_ > .middle {display:table-cell; vertical-align:middle;}\
		'}));
	},

	create: function(){
		var t=this;
		t.index = Panel.LEN++;
		if(t.index===0)t.writeCSS();

		var E=document.documentElement,
			h=t.titleHeight;
		if(t.right===undefined){
			if(t.left===undefined) t.left=(E.clientWidth  - t.width)  / 2;
		}else{
			t.left=E.clientWidth-t.width-t.right;
		}
		if (t.bottom===undefined){
			if(t.top===undefined) t.top=(E.clientHeight - t.height) / 2;
		} else {
			t.top=E.clientHeight-t.height-t.bottom;
		}

		var html = '<div class="_panel_content_" style="text-align:' + t.textAlign + '">' + t.html + '</div>';
		if(t.titleHeight>0){
			html = '<header><strong>' + t.title + '</strong><ul><li>▬</li><li>✖</li></ul></header>' + html;
		}
		t.panel = t.C('div', {
			id: t.id,
			className: 'panel',
			innerHTML: html,
			style:{
				top: t.top + 'px',
				left: t.left + 'px',
				width: t.width + 'px',
				height: t.height + 'px',
				zIndex: t.zIndex,
				display: t.display,
			},
		});

		t.R('header li:nth-child(1)', t.panel).addEventListener('click', function(e){t.toggle()});
		t.R('header li:nth-child(2)', t.panel).addEventListener('click', function(e){t.destory()});
		if(t.movable) t.addMouseEvent(t.titleHeight==0 ? t.panel : R('header', t.panel), t.panel);
		t.R(t.container||'body').appendChild(t.panel);
		if(t.onload) t.onload();
		return t;
	},

	addMouseEvent: function(pos, dom){
		dom = dom || pos;
		pos.addEventListener('mousedown',  mousedown, false);
		pos.addEventListener('touchstart', mousedown, false);

		var t=this, x, y, X, Y, l=t.left, t=t.top;
		function mousedown(e){
			e = e||window.event;
			if(e.targetTouches)e=e.targetTouches[0];
			X = e.clientX; 	x = parseInt(dom.style.left);
			Y = e.clientY; 	y = parseInt(dom.style.top);
			document.addEventListener('mousemove', mousemove, false);
			document.addEventListener('touchmove', mousemove, false);
			document.addEventListener('mouseup',   mouseup, false);
			document.addEventListener('touchend',  mouseup, false);
			if(t.onmousedown) t.onmousedown(x, y);
		}
		function mousemove(e){
			e = e||window.event;
			if(e.targetTouches && e.targetTouches.length==1){e.preventDefault();e=e.targetTouches[0]}
			var a = e.clientX - X,
				b = e.clientY - Y;
				l = x + a;
				t = y + b;
			dom.style.left = l + "px";
			dom.style.top  = t + "px";
			if(t.onmousemove) t.onmousemove(l, t);
		}
		function mouseup(e){
			t.left = x = l;
			t.top  = y = t;
			document.removeEventListener('mousemove', mousemove);
			document.removeEventListener('touchmove', mousemove);
			document.removeEventListener('mouseup',   mouseup);
			document.removeEventListener('touchend',  mouseup);
			if(t.onmouseup) t.onmouseup(x, y);
		}
	},

	R: function(q,o){return (window&&q&&q.nodeType)?q:(o||document).querySelector(q)},
	C: function(t,j){var k,l,e=document.createElement(t);if(j){for(k in j){if(j[k]){
		if(k=='attribute'||k=='att'){for(l in j[k]){if(j[k][l])e.setAttribute(l,j[k][l])}
		}else if(k=='style'){for(l in j[k]){if(j[k][l])e[k][l]=j[k][l]}}else{e[k]=j[k]}}}}return e;
	},
}

var panel = {
	pools: [],
	create: function(cfg) {
		var i = cfg && cfg.id || 0;
		if(!this.pools[i] || !this.pools[i].panel){
			this.pools[i] = (new Panel(cfg)).create();
		}
		return this.pools[i];
	},
}

