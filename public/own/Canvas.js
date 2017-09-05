/** This is a function that scale and cut image.
 ** version 1.0.0
 ** date: 2017-03-20
 ** author: HuoHongJian
 */
function Canvas(params) {
	this.id;
	this.width = 300;
	this.height = 300;
	this.MAX_WIDTH = 1024;
	this.rot = 0;
	this.scale = 100;
	this.mime = 'jpeg';
	this.quality = 0.92;
	this.display = 'block';
	this.container = 'body';

	for(var k in params) {if (params[k]!==undefined) this[k] = params[k]}
	this.index = Canvas.Length++;
	this.canvas = this.C('canvas');
	var c = this.R(this.container);
	c.style.position = 'absolute';
	c.appendChild(this.canvas);
}
Canvas.Length = 0;
Canvas.prototype = {
	constructor: Canvas,
	loadImage:function(img) {img.style.display='none';this.loaded(img)},
	loadData: function(base64) {var p=this,img=new Image();img.onload=function(){p.loaded(img)};img.src=base64},
	loadFile: function(file) {
		if (file) {
			var p=this, reader = new FileReader();
			p.ss=file.size;
			reader.onload = function(){p.loadData(this.result)}
			reader.readAsDataURL(file);
		}
	},
	loaded: function(img) {
		var p = this;
		p.image = img;
		p.sw = img.width;
		p.sh = img.height;
		p.ss = p.ss || img.size || img.src.length/1.33;

		p.cw = Math.min(p.sw, p.MAX_WIDTH);
		p.ch = p.cw * p.sh / p.sw;

		if(p.onload) p.onload(p);
		p.render(p.canvas, p.image, p.cw, p.ch, p.rot);
	},

	render: function(canvas, img, width, height, rot) {
		var p = this,
			w = p.cw = Math.round(width),
			h = p.ch = Math.round(height),
			s = p.cs = Math.round(p.ss * p.ch / p.sh),
			r = p.rotatedSide(w, h, rot||0),
		 	context = canvas.getContext("2d");
		canvas.width = r.w;
		canvas.height= r.h;
		context.fillStyle = "#fff";
		context.fillRect(0, 0, canvas.width, canvas.height);
		context.save();
		//改变中心点
			   if(r.r <= Math.PI/2) { context.translate(r.s*h, 0);
		} else if(r.r <= Math.PI)   { context.translate(canvas.width, -r.c*h);
		} else if(r.r<=1.5*Math.PI) { context.translate(-r.c*w, canvas.height);
		} else 				   		{ context.translate(0, -r.s*w);}
		context.rotate(r.r);
		context.drawImage(img, 0, 0, w, h);
		context.restore();

		p.scale = Math.round(p.cw/p.sw*100);
		if (p.onrender) p.onrender(p);
	},

	ZOOM: function(width, height){
		var p = this;
		p.cw = Math.min((width || p.sw), p.MAX_WIDTH);
		p.ch = height || Math.round(p.cw * p.sh / p.sw);
		p.render(p.canvas, p.image, p.cw, p.ch, p.rot);
		if (p.onzoom) p.onzoom(p.scale);
	},
	zoom: function(r, isZoomTo){var p=this,w=isZoomTo?p.sw*r:p.cw*(1+r);this.ZOOM(w)},
	rotate: function(v, isRotateTo){
		var p = this, v = parseInt(v);
		p.rot = isRotateTo ? v : p.rot+v;
		if(p.rot>=360){p.rot -= 360}else if(p.rot<0){p.rot += 360}
		p.render(p.canvas, p.image, p.cw, p.ch, p.rot);
		if (p.onrotate) p.onrotate(p.rot);
	},
	reset: function(){this.rotate(0,true);this.zoom(1,true)},
	
	fillText: function(text, x, y, params){
		var context = this.canvas.getContext("2d");
		context.fillStyle = params.color;
		context.font = `${params.style} normal ${params.weight} ${params.size} Verdana`;
		context.fillText(text, x, y);
	},



	toggle: function() {
		this.canvas.style.display = this.display = this.display == 'none' ? 'block' : 'none';
	},
	show: function() {
		this.canvas.canvas.display = this.display = 'block';
	},
	hide: function() {
		this.canvas.canvas.display = this.display = 'none';
	},
	destory: function() {
		this.canvas.parentNode.removeChild(this.canvas);
	},

	getImageData: function(x, y, w, h) {
		var p = this,
			r = p.rotatedSide(p.cw, p.ch, p.rot),
			dx = x || 0,
			dy = y || 0,
			dw = w || r.w,
			dh = h || r.h,
			x = Math.max(dx, 0),
			y = Math.max(dy, 0),
			w = dw - (x - dx),
			h = dh - (y - dy);
		return p.canvas.getContext('2d').getImageData(x, y, w, h);
	},
	getDataURL: function(x, y, w, h) {
		var cvs, p = this;
		if (x==0 && y==0 && w==p.cw && h==p.ch) {
			cvs = p.canvas;
		} else {
			var imageData = p.getImageData(x, y, w, h);
			cvs = R.ce('canvas');
			cvs.width = imageData.width;
			cvs.height= imageData.height;
			cvs.getContext('2d').putImageData(imageData, 0, 0);
		}
		return cvs.toDataURL("image/"+p.mime, p.quality);
	},
	getBlobData: function(x, y, w, h) {
		var dataURL = this.getDataURL(x, y, w, h),
			mimeString = dataURL.split(',')[0].split(':')[1].split(';')[0], // mime类型
			byteString = atob(dataURL.split(',')[1]), //base64 解码
			arrayBuffer= new ArrayBuffer(byteString.length), //创建缓冲数组
			intArray   = new Uint8Array(arrayBuffer); //创建视图
		for (i=0; i<byteString.length; i++) {
			intArray[i] = byteString.charCodeAt(i);
		}
		return new Blob([intArray], {type: mimeString}); //转成blob
	},


	rotatedSide: function(width, height, rot) {
		var r = Math.round,
			a = Math.abs,
			rotation = Math.PI * (rot||0) / 180;
			c = r(Math.cos(rotation) * 1000) / 1000;
			s = r(Math.sin(rotation) * 1000) / 1000;
		return {
			w: r(a(c*width) + a(s*height)),
			h: r(a(c*height)+ a(s*width)),
			r: rotation, c: c, s: s,
		};
	},

	R: function(q,o){return (window&&q&&q.nodeType)?q:(o||document).querySelector(q)},
	C: function(t,j){var k,l,e=document.createElement(t);if(j){for(k in j){if(j[k]){
		if(k=='attribute'||k=='att'){for(l in j[k]){if(j[k][l])e.setAttribute(l,j[k][l])}
		}else if(k=='style'){for(l in j[k]){if(j[k][l])e[k][l]=j[k][l]}}else{e[k]=j[k]}}}}return e;
	},
}
