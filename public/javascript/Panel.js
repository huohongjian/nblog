/** version 1.0.0
 ** date: 2017-03-22
 ** author: HuoHongJian
 */
if(R===undefined){
	R = function(q,o){return (o||document).querySelector(q)}
	R.id = function(i,o){return r.isDom(q)?q:(o||document).getElementById(q)}
	R.ce = function(t,j){
		var k,l,e=document.createElement(t);if(j){for(k in j){if(j[k]){if(k==='style'){
		for(l in j[k]){e[k][l]=j[k][l]}}else{e[k]=j[k]}}}}return e;
	}
	R.all = function(q,o){return (o||document).querySelectorAll(q)}
	R.isDom = function(o){return !!(o&&typeof window!=='undefined'&&(o===window||o.nodeType))}
}	
Panel = function(params) {
	this.id;
	this.left = this.top = this.width = this.height = 200;
	this.right;
	this.bottom;
	this.isCenter = false;
	this.isMiddle = false;
	
	this.container = R('body');
	this.title = '标题';
	this.titleHeight = 0;
	this.html = '<p>this is a panel.</p>';
	this.movable = true;
	this.display = 'block';

	this.onload;
	this.onmousedown;
	this.onmousemove;
	this.onmouseup;
	this.ontoggle;
	this.onshow;
	this.onhide;
	this.ondestory;
	for(var k in params){if(params[k])this[k]=params[k]}
	this.index = Panel.len++;
	if(this.index===0)this.writeCSS(this);
	this.createPanel(this);
}
Panel.len = 0;
Panel.prototype = {
	constructor: Panel,
	toggle: function(display){
		var p=this;p.display=display||(p.display=='block'?'none':'block');
		p.panel.style.display=p.display;if(p.ontoggle)p.ontoggle();
	},
	show: function(timeout){var p=this;setTimeout(function(){p.toggle('block');if(p.onshow)p.onshow()},timeout||0)},
	hide: function(timeout){var p=this;setTimeout(function(){p.toggle('none'); if(p.onhide)p.onhide()},timeout||0)},
	destory: function(){var p=this;p.container.removeChild(p.panel);p.panel=undefined;if(p.ondestory)p.ondestory()},
	setHtml: function(html){R('._panel_content_', this.panel).innerHTML=html},


	writeCSS: function(p){
		var cur1 = p.movable && p.titleHeight!=0 ? 'move' : 'default',
			cur2 = p.movable && p.titleHeight==0 ? 'move' : 'default',
			h = Math.min(p.titleHeight, 1);
		R('head').appendChild(R.ce('style', {type:'text/css', textContent:`
			.panel {border:1px solid #ddd; position:absolute; margin:auto;}
			.panel > header {cursor:${cur1}; padding:0 10px; cursor:move; border-bottom:${h}px solid #ddd; overflow:hidden;}
			.panel > header ul {float:right; margin:0;}
			.panel > header li {cursor:pointer; margin-left:10px; display:inline-block; list-style-type:none;}
			.panel > div {cursor:${cur2}; display:table; width:100%; text-align:center;}
			.panel > div._panel_content_ > m,
			.panel > div._panel_content_ > *.middle {display:table-cell; vertical-align:middle;}
		`}));
	},
	createPanel: function(p){
		var h = p.titleHeight;
		if (p.isCenter) p.right = p.left = 0;
		if (p.isMiddle) p.bottom = p.top = 0;

		p.panel = R.ce('div', {
			id: p.id,
			className: 'panel',
			style: {
				top: p.top + 'px',
				left: p.left + 'px',
				right: p.right + 'px',
				bottom: p.bottom + 'px',
				width: p.width + 'px',
				height: p.height + 'px',
				display: p.display,
			},
			innerHTML: `
				<header style="height:${h}px; line-height:${h}px;">
					<strong>${p.title}</strong>
					<ul><li>▬</li><li>✖</li></ul>
				</header>
				<div class="_panel_content_" style="height:${p.height-h}px;">${p.html}</div>`,
		});
		R.id(p.container).appendChild(p.panel);
		R('header li:nth-child(1)', p.panel).addEventListener('click', function(e){p.toggle()});
		R('header li:nth-child(2)', p.panel).addEventListener('click', function(e){p.destory()});

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


	
}




