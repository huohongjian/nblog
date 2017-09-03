/* author: HuoHongJian
**   date: 2017-03-28
**   func: a box that can be stratched
*/
function Stretch(params) {
	this.id;
	this.top = 25;
	this.left = 25;
	this.width = 200;
	this.height = 120;
	this.fixed = false;
	this.container = 'body';
	this.stretch;
	this.display = 'block';
	this.showInfo = true;

	for(var k in params) {if (params[k]!==undefined) this[k] = params[k]}
	this.index = Stretch.Length++;
	this.run(this);
}
Stretch.Length = 0;
Stretch.prototype = {
	constructor: Stretch,
	run: function(p) {
		if (p.index==0) p.writeCSS(p);
		p.stretch = p.createStretch(p.left, p.top, p.width, p.height, p.id, p.display, p.showInfo);
		p.R(p.container).appendChild(p.stretch);
		if (p.onload) p.onload(p.left, p.top, p.width, p.height);
	},
	writeCSS: function(p) {
		p.R('head').appendChild( p.C('style', {
			type: 'text/css',
			innerHTML: `
				.stretch {position:absolute; border:1px solid black;}
				.stretch > div {position:absolute; width:5px; height:5px;border:1px solid #000; background:white; overflow:hidden;}
				.stretch .MA {text-align:center; width:100%; height:100%; background:white; opacity:0.3; filter:alpha(opacity=30); cursor:move; border:0;}
				.stretch .NW {cursor:nw-resize; left:-3px; top:-3px;}
				.stretch .SW {cursor:sw-resize; left:-3px; bottom:-3px;}
				.stretch .NE {cursor:ne-resize; right:-3px; top:-3px;}
				.stretch .SE {cursor:se-resize; right:-3px; bottom:-3px; width:15px; height:15px;}
				.stretch .NO {cursor:n-resize; top:-3px; left:50%; margin-left:-3px;}
				.stretch .SO {cursor:s-resize; bottom:-3px; left:50%; margin-left:-3px;}
				.stretch .EA {cursor:e-resize; right:-3px; top:50%; margin-top:-3px;}
				.stretch .WE {cursor:w-resize; left:-3px; top:50%; margin-top:-3px;}
				.stretch b {padding-top:50px; display:block; font-size:12px;}`,
			})
		);
	},

	createStretch: function(x, y, w, h, id, display, showInfo) {
		var X, Y, W, H, $X, $Y, className, p=this,
		stretch = R.ce('div', {
			id: id,
			className:'stretch',
			style: {
				top: y + 'px',
				left: x + 'px',
				width: w + 'px',
				height: h + 'px',
				display: display,
			},
			innerHTML: `<div class="MA"></div>
						<div class="NW"></div>
						<div class="SW"></div>
						<div class="NE"></div>
						<div class="SE"></div>
						<div class="NO"></div>
						<div class="SO"></div>
						<div class="EA"></div>
						<div class="WE"></div>`,
		});

		if(showInfo)showinfo(x, y, w, h);
		stretch.addEventListener('mousedown',  mousedown, false);
		stretch.addEventListener('touchstart', mousedown, false);
		
		function mousedown(e){
			e = e||window.event;
			if(e.targetTouches)e=e.targetTouches[0];
			className = e.target.className;

			X = x; $X = e.clientX;
			Y = y; $Y = e.clientY;
			W = w;
			H = h;
			document.addEventListener('mousemove', mousemove, false);
			document.addEventListener('touchmove', mousemove, false);
			document.addEventListener('mouseup',   mouseup, false);
			document.addEventListener('touchend',  mouseup, false);
			var r = Math.round;
			if(p.onmousedown) p.onmousedown(r(X), r(Y), r(W), r(H));
		}
		function mousemove(e){
			e = e||window.event;
			if(e.targetTouches && e.targetTouches.length==1){e.preventDefault();e=e.targetTouches[0]}
			var a = e.clientX - $X,
				b = e.clientY - $Y;
			switch (className) {
				case 'NO': 	Y=y+b; H=h-b; break;
				case 'NE': 	Y=y+b; W=w+a; H=h-b; break;
				case 'EA': 	W=w+a; break;
				case 'SE': 	W=w+a; H=h+b; break;
				case 'SO': 	H=h+b; break;
				case 'SW': 	X=x+a; W=w-a; H=h+b; break;
				case 'WE': 	X=x+a; W=w-a; break;
				case 'NW': 	X=x+a; Y=y+b; W=w-a; H=h-b; break;
				default: 	X=x+a; Y=y+b;
			}
			if(p.fixed){H=W*h/w}

			var r = Math.round;
			X=r(X); Y=r(Y); W=r(W); H=r(H);
			stretch.style.left   = X + "px";
			stretch.style.top    = Y + "px";
			stretch.style.width  = W + "px";
			stretch.style.height = H + "px";

			if(showInfo)showinfo(X, Y, W, H);
			if(p.onmousemove) p.onmousemove(X, Y, W, H);
		}
		function mouseup(e){
			p.left=x=X; p.top=y=Y; p.width=w=W; p.height=h=H;
			document.removeEventListener('mousemove', mousemove);
			document.removeEventListener('touchmove', mousemove);
			document.removeEventListener('mouseup',   mouseup);
			document.removeEventListener('touchend',  mouseup);
			var r = Math.round;
			if (p.onmouseup) p.onmouseup(r(X), r(Y), r(W), r(H));
		}
		function showinfo(x, y, w, h){
			p.R('.MA',stretch).innerHTML=`<b>(${x}, ${y}) ${w} × ${h}</b>`;
		}
		return stretch;
	},

	reset: function(x, y, w, h) {
		var p=this;
		p.x = x || p.x;
		p.y = y || p.y;
		p.w = w || p.w;
		p.h = h || p.h
		p.stretch.style.left   = p.x + "px";
		p.stretch.style.top    = p.y + "px";
		p.stretch.style.width  = p.w + "px";
		p.stretch.style.height = p.h + "px";
		if (p.onreset) p.onreset(p.left, p.top, p.width, p.height);
	},

	toggle: function() {var p = this;if(p.display=='none'){p.show()}else{p.hide()}},
	show: function() {
		var p = this;
		p.stretch.style.display = p.display = 'block';
		if (p.onshow) p.onshow(p.left, p.top, p.width, p.height);
	},
	hide: function() {
		var p = this;
		p.stretch.style.display = p.display = 'none';
		if (p.onhide) p.onhide(p.left, p.top, p.width, p.height);
	},
	destory: function() {
		var p = this;
		p.stretch.parentNode.removeChild(p.stretch);
		if (p.ondestory) p.ondestory(p.left, p.top, p.width, p.height);
	},
	fix: function(b) {
		this.fixed = b || !this.fixed;
		return this.fixed;
	},

	R: function(q,o){return (window&&q&&q.nodeType)?q:(o||document).querySelector(q)},
	C: function(t,j){var k,l,e=document.createElement(t);if(j){for(k in j){if(j[k]){
		if(k=='attribute'||k=='att'){for(l in j[k]){if(j[k][l])e.setAttribute(l,j[k][l])}
		}else if(k=='style'){for(l in j[k]){if(j[k][l])e[k][l]=j[k][l]}}else{e[k]=j[k]}}}}return e;
	},
}

