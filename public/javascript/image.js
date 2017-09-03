/** This is a function that scale and cut image.
 ** version 1.0.0
 ** date: 2017-03-20
 ** author: HuoHongJian
 */
(function(image){
	var p={ sx:0, sy:0, sw:0, sh:0, ss:0,		// orig
			cx:0, cy:0, cw:0, ch:0, cs:0,		// render
			dx:25, dy:25, dw:150, dh:100, ds:0,	// cropBox
			fixed: false,						// cropBox 宽高比是否固定
			MAX_WIDTH: 1024,
			container: 'image',
			quality: 0.92,
			rot: 0,
			zoom: 1,
			hideCropBox: true,
		}, _=window[image||'image']={};
	_.get = function(k){return k?p[k]:p}
	_.set = function(k,v){p[k]=v}
	_.getImageData = function(){
		var f = p.hideCropBox,
			r = rotatedSide(p.cw, p.ch, p.rot),
			dx = f ? p.cx : p.dx,
			dy = f ? p.cy : p.dy,
			dw = f ? r.w : p.dw,
			dh = f ? r.h : p.dh,
			x = Max(dx, 0),
			y = Max(dy, 0),
			w = dw - (x - dx),
			h = dh - (y - dy);
		return p.canvas.getContext('2d').getImageData(x, y, w, h);
	}
	_.getDataURL = function(){
		var cvs;
		if (p.dw==p.cw && p.dh==p.ch) {
			cvs = p.canvas;
		} else {
			var imageData = _.getImageData();
			cvs = R.ce('canvas');
			cvs.width = imageData.width;
			cvs.height= imageData.height;
			cvs.getContext('2d').putImageData(imageData, 0, 0);
		}
		return cvs.toDataURL("image/jpeg", p.quality);
	}
	_.getBlobData = function() {
		var dataURL = this.getDataURL(),
			mimeString = dataURL.split(',')[0].split(':')[1].split(';')[0], // mime类型
			byteString = atob(dataURL.split(',')[1]), //base64 解码
			arrayBuffer= new ArrayBuffer(byteString.length), //创建缓冲数组
			intArray   = new Uint8Array(arrayBuffer); //创建视图
		for (i=0; i<byteString.length; i++) {
			intArray[i] = byteString.charCodeAt(i);
		}
		return new Blob([intArray], {type: mimeString}); //转成blob
	}

	_.loadImage= function(img, params) {img.style.display='none';init(img, params)}
	_.loadData = function(base64, params) {var img=new Image();img.onload=function(){init(img, params)};img.src=base64}
	_.loadFile = function(file, params) {
		if (file) {
			var reader = new FileReader();
			reader.onload = function(){_.loadData(this.result, params)}
			reader.readAsDataURL(file);
		}
	}

	_.render = function(canvas, img, rot, width, height) {
		var context = canvas.getContext("2d"),
			w = Mr(width),
			h = Mr(height),
			r = rotatedSide(w, h, rot||0);
		canvas.width = r.w;
		canvas.height= r.h;

		context.fillStyle = "#fff";
		context.fillRect(0, 0, canvas.width, canvas.height);

		context.save();
		//改变中心点
			   if(r.r <= PI/2) { context.translate(r.s*h, 0);
		} else if(r.r <= PI)   { context.translate(canvas.width, -r.c*h);
		} else if(r.r<=1.5*PI) { context.translate(-r.c*w, canvas.height);
		} else 				   { context.translate(0, -r.s*w);}
		context.rotate(r.r);
		context.drawImage(img, 0, 0, w, h);
		context.restore();

		p.zoom = p.cw/p.sw;
		R('#imageInfo').innerHTML=`
				<p><i>WIDTH:</i>${p.sw}</p>
				<p><i>HEIGHT:</i>${p.sh}</p>
				<p><i>SIZE(K):</i>${Mr(p.ss/1024)}</p>
				<p><i>width:</i>${Mr(p.cw)}</p>
				<p><i>height:</i>${Mr(p.ch)}</p>
				<p><i>size(k):</i>${Mr(p.cs/1024)}</p>
				<p><i>rotate:</i>${p.rot}°</p>
				<p><i>zoom:</i>${Mr(p.zoom*100)}%</p>`;
		if (p.onrender) p.onrender(p);
	}
	_.crop  = function() {p.hideCropBox=p.cropBox.toggle()}
	_.setCropBoxFixed = function(b) {p.cropBox.fixed=b}
	_.ZOOM = function(width, height){
		p.cw = Min((width || p.sw), p.MAX_WIDTH);
		p.ch = height || Mr(p.cw * p.sh / p.sw);
		_.render(p.canvas, p.image, p.rot, p.cw, p.ch);
		if(p.onzoom)p.onzoom(p.zoom);
	}
	_.zoom  = function(r, isZoomTo){var w=isZoomTo?p.sw*r:p.cw*(1+r);_.ZOOM(w)}
	_.rotate = function(v, isRotateTo){
		p.rot = isRotateTo ? int(v) : p.rot+int(v);
		if(p.rot>=360){p.rot -= 360}else if(p.rot<0){p.rot += 360}
		_.render(p.canvas, p.image, p.rot, p.cw, p.ch);
		if(p.onrotate)p.onrotate(p.rot);
	}
	_.reset = function(){_.rotate(0,true); _.zoom(1,true)}
	
	_.fillText = function(text, x, y, params){
		var context = p.canvas.getContext("2d");
		context.fillStyle = params.color;
		context.font = `${params.style} normal ${params.weight} ${params.size} Verdana`;
		context.fillText(text, x, y);
	}


	function init(img, params) {
		params = params||{};
		for(var k in params){
			if(params[k]!==undefined) p[k]=params[k];
		}
		p.image = img;
		p.sw = img.width;
		p.sh = img.height;
		p.ss = img.size || img.src.length;
		p.cw = Min(p.sw, p.MAX_WIDTH);
		p.ch = p.cw * p.sh / p.sw;
		p.cs = p.ss * p.ch / p.sh;

		var $c = R.ID(p.container);
		$c.style.position = 'absolute';
		$c.innerHTML = `
			<style type="text/css">
				#imageInfo {position:absolute; font-size:12px; line-height:0.5; padding:5px;}
				#imageInfo p:nth-child(4) {margin-top:15px;}
				#imageInfo i {width:50px; text-align:right; display:inline-block; margin-right:2px;}
			</style>
			<div id="imageInfo"></div>`;

		p.cropBox = new DragBox({
			container: $c,
			id: 'cropBox',
			hide: p.hideCropBox,
			showPos: true,
			fixed: p.fixed,
			x:p.dx, y:p.dy, w:p.dw, h:p.dh,
			onmouseup: function(x, y, w, h){p.dx=x; p.dy=y; p.dw=w; p.dh=h},
		});

		p.canvas = R.ce('canvas');
		$c.appendChild(p.canvas);

		_.render(p.canvas, p.image, p.rot, p.cw, p.ch);
		if(p.onload)p.onload(p);
	}

// ************  below is functions  ********************
	var Mr=Math.round, Mf=Math.floor, Mc=Math.ceil, Ma=Math.abs, Max=Math.max, Min=Math.min, PI=Math.PI,
		int=parseInt, log=console.log, D=document;	
	function rotatedSide(width, height, rot) {
		var rotation = PI * (rot||0) / 180;
			c = Mr(Math.cos(rotation) * 1000) / 1000;
			s = Mr(Math.sin(rotation) * 1000) / 1000;
		return {
			w: Mr(Ma(c*width) + Ma(s*height)),
			h: Mr(Ma(c*height)+ Ma(s*width)),
			r: rotation, c: c, s: s,
		};
	}
	

	function R(q,o){return (o||document).querySelector(q)}
	R.all = function(q,o){return (o||D).querySelectorAll(q)}
	R.id  = function(i){return D.getElementById(i)}
	R.ID  = function(x){return R.isDom(x)?x:R.id(x)}
	R.ce  = function(t,j){
		var k,l,e=D.createElement(t);if(j){for(k in j){if(j[k]){if(k==='style'){
		for(l in j[k]){e[k][l]=j[k][l]}}else{e[k]=j[k]}}}}return e;
	}
	R.isDom = function(o){return !!(o&&typeof window!=='undefined'&&(o===window||o.nodeType))}


	function DragBox(params){
		this.x = 25;
		this.y = 25;
		this.w = 150;
		this.h = 100;
		this.fixed = false;
		this.id;
		this.container;
		this.box;
		this.hide = false;
		this.showPos = true;
		for(var k in params){if(params[k])this[k]=params[k]}
		this.index = DragBox.len++;
		if(this.container)this.load();
	};
	DragBox.len = 0;
	DragBox.prototype = {
		load: function() {
			var p=this, o = R.ID(this.container);
			if(p.index===0) p.createStyle();
			p.box = p.createBox(p.x, p.y, p.w, p.h, p.id, p.hide, p.showPos);
			o.appendChild(p.box);
			if(p.onload)p.onload(p.x, p.y, p.w, p.h);
		},
		appendTo: function(container) {this.load(container)},
		createStyle: function() {
			R('head').appendChild( R.ce('style', {
				type: 'text/css',
				innerHTML: `
					.BOX {position:absolute; border:1px solid black;}
					.BOX > div {position:absolute; width:5px; height:5px;border:1px solid #000; background:white; overflow:hidden;}
					.BOX .MA {text-align:center; width:100%; height:100%; background:white; opacity:0.3; filter:alpha(opacity=30); cursor:move; border:0;}
					.BOX .NW {cursor:nw-resize; left:-3px; top:-3px;}
					.BOX .SW {cursor:sw-resize; left:-3px; bottom:-3px;}
					.BOX .NE {cursor:ne-resize; right:-3px; top:-3px;}
					.BOX .SE {cursor:se-resize; right:-3px; bottom:-3px; width:15px; height:15px;}
					.BOX .NO {cursor:n-resize; top:-3px; left:50%; margin-left:-3px;}
					.BOX .SO {cursor:s-resize; bottom:-3px; left:50%; margin-left:-3px;}
					.BOX .EA {cursor:e-resize; right:-3px; top:50%; margin-top:-3px;}
					.BOX .WE {cursor:w-resize; left:-3px; top:50%; margin-top:-3px;}
					.BOX b {padding-top:50px; display:block; font-size:12px;}`,
				})
			);
		},
		createBox: function(x, y, w, h, id, hide, showPos) {
			var X, Y, W, H, $X, $Y, className, p=this,
			box = R.ce('div', {className:'BOX', id:id});
			box.style.display= hide ? 'none' : 'block';
			box.style.left   = x + "px";
			box.style.top    = y + "px";
			box.style.width  = w + "px";
			box.style.height = h + "px";
			box.innerHTML = `
				<div class="MA"></div>
				<div class="NW"></div>
				<div class="SW"></div>
				<div class="NE"></div>
				<div class="SE"></div>
				<div class="NO"></div>
				<div class="SO"></div>
				<div class="EA"></div>
				<div class="WE"></div>`;
			if(showPos)showpos(x, y, w, h);
			box.addEventListener('mousedown',  mousedown, false);
			box.addEventListener('touchstart', mousedown, false);
			
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
				if(p.onmousedown) p.onmousedown(Mr(X), Mr(Y), Mr(W), Mr(H));
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

				X=Mr(X); Y=Mr(Y); W=Mr(W); H=Mr(H);
				box.style.left   = X + "px";
				box.style.top    = Y + "px";
				box.style.width  = W + "px";
				box.style.height = H + "px";
				if(showPos)showpos(X, Y, W, H);
				if(p.onmousemove) p.onmousemove(X, Y, W, H);
			}
			function mouseup(e){
				x=X; y=Y; w=W; h=H;
				document.removeEventListener('mousemove', mousemove);
				document.removeEventListener('touchmove', mousemove);
				document.removeEventListener('mouseup',   mouseup);
				document.removeEventListener('touchend',  mouseup);
				if(p.onmouseup) p.onmouseup(Mr(X), Mr(Y), Mr(W), Mr(H));
			}
			function showpos(x, y, w, h){
				R('.MA',box).innerHTML=`<b>(${x}, ${y}) ${w} × ${h}</b>`;
			}
			return box;
		},

		reset: function(x, y, w, h) {
			var p=this;
			p.x = x || p.x;
			p.y = y || p.y;
			p.w = w || p.w;
			p.h = h || p.h
			p.box.style.left   = p.x + "px";
			p.box.style.top    = p.y + "px";
			p.box.style.width  = p.w + "px";
			p.box.style.height = p.h + "px";
		},

		toggle: function() {
			this.box.style.display = this.hide ? 'block' : 'none';
			this.hide = !this.hide;
			return this.hide;
		},
		destory: function() {
			this.box.parentNode.removeChild(this.box);
		},


	}



}('image')) //variable


