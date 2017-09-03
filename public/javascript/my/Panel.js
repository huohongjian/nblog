/** version 1.0.0
 ** date: 2017-03-22
 ** author: HuoHongJian
 */	
Panel = function(params) {
	this.id;
	this.left;
	this.top;
	this.width = 300;
	this.height = 200;
	this.right;
	this.bottom;
	this.textAlign = 'center';
	this.background = '#fff';
	
	this.container = 'body';
	this.title = '信息';
	this.titleHeight = 39;
	this.html = '<p>this is a panel.</p>';
	this.movable = true;
	this.display = 'block';

	for(var k in params){if(params[k]!==undefined)this[k]=params[k]}
	this.index = Panel.LEN++;
	if(this.index===0)this.writeCSS(this);
	this.createPanel(this);
}
Panel.LEN = 0;
Panel.prototype = {
	constructor: Panel,
	toggle: function(display){
		var p=this;p.display=display||(p.display=='block'?'none':'block');
		p.panel.style.display=p.display;
	},
	show: function(t){var p=this;setTimeout(function(){p.toggle('block');if(p.onshow)p.onshow()},t||0)},
	hide: function(t){var p=this;setTimeout(function(){p.toggle('none'); if(p.onhide)p.onhide()},t||0)},
	twinkle: function(t){this.show();if(t>0)this.hide(t)},
	destory: function(){var p=this;p.panel.parentNode.removeChild(p.panel);p.panel=undefined;if(p.ondestory)p.ondestory()},
	setHtml: function(html){this.R('._panel_content_', this.panel).innerHTML=html},
	getRect: function(){return this.panel.getBoundingClientRect()},

	writeCSS: function(p){
		var cur1 = p.movable && p.titleHeight!=0 ? 'move' : 'default',
			cur2 = p.movable && p.titleHeight==0 ? 'move' : 'default',
			h = Math.min(p.titleHeight, 1);
		p.R('head').appendChild(p.C('style', {type:'text/css', textContent:'\
			.panel {border:1px solid #ddd; position:absolute;}\
			.panel > header {cursor:' + 'cur1; padding:0 10px; cursor:move; border-bottom:' + h + 'px solid #ddd; overflow:hidden;}\
			.panel > header ul {float:right; margin:0;}\
			.panel > header li {cursor:pointer; margin-left:10px; display:inline-block; list-style-type:none;}\
			.panel > div._panel_content_ {cursor:' + 'cur2' + '; display:table; width:100%; text-align:' + p.textAlign + ';}\
			.panel > div._panel_content_ > m,\
			.panel > div._panel_content_ > .middle {display:table-cell; vertical-align:middle;}\
		'}));
	},

	createPanel: function(p){
		var h = p.titleHeight;
		if (p.left === undefined) p.left = (document.documentElement.clientWidth  - p.width)  / 2;
		if (p.top  === undefined) p.top  = (document.documentElement.clientHeight - p.height) / 2;

		p.panel = p.C('div', {
			id: p.id,
			className: 'panel',
			style: {
				top: p.top + 'px',
				left: p.left + 'px',
				width: p.width + 'px',
				height: p.height + 'px',
				display: p.display,
				background: p.background,
			},
			innerHTML: '\
				<header style="height:' + h + 'px; line-height:' + h + 'px;">\
					<strong>' + p.title + '</strong>\
					<ul><li>▬</li><li>✖</li></ul>\
				</header>\
				<div class="_panel_content_" style="height:' + (p.height-h) + 'px;">' + p.html + '</div>',
		});
		p.R(p.container).appendChild(p.panel);
		p.R('header li:nth-child(1)', p.panel).addEventListener('click', function(e){p.toggle()});
		p.R('header li:nth-child(2)', p.panel).addEventListener('click', function(e){p.destory()});

		if(p.onload) p.onload();
		if(p.movable) p.addMouseEvent(p.titleHeight==0 ? p.panel : R('header', p.panel), p.panel);
	},

	addMouseEvent: function(pos, dom){
		dom = dom || pos;
		pos.addEventListener('mousedown',  mousedown, false);
		pos.addEventListener('touchstart', mousedown, false);

		var p=this, x, y, X, Y, l=p.left, t=p.top;
		function mousedown(e){
			e = e||window.event;
			if(e.targetTouches)e=e.targetTouches[0];
			X = e.clientX; 	x = parseInt(dom.style.left);
			Y = e.clientY; 	y = parseInt(dom.style.top);
			document.addEventListener('mousemove', mousemove, false);
			document.addEventListener('touchmove', mousemove, false);
			document.addEventListener('mouseup',   mouseup, false);
			document.addEventListener('touchend',  mouseup, false);
			if(p.onmousedown) p.onmousedown(x, y);
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
			if(p.onmousemove) p.onmousemove(l, t);
		}
		function mouseup(e){
			p.left = x = l;
			p.top  = y = t;
			document.removeEventListener('mousemove', mousemove);
			document.removeEventListener('touchmove', mousemove);
			document.removeEventListener('mouseup',   mouseup);
			document.removeEventListener('touchend',  mouseup);
			if(p.onmouseup) p.onmouseup(x, y);
		}
	},

	R: function(q,o){return (window&&q&&q.nodeType)?q:(o||document).querySelector(q)},
	C: function(t,j){var k,l,e=document.createElement(t);if(j){for(k in j){if(j[k]){
		if(k=='attribute'||k=='att'){for(l in j[k]){if(j[k][l])e.setAttribute(l,j[k][l])}
		}else if(k=='style'){for(l in j[k]){if(j[k][l])e[k][l]=j[k][l]}}else{e[k]=j[k]}}}}return e;
	},
	
}




