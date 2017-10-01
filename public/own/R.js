(function(R){
	var W = window,
		D = W.document,
		L = W.location,
		_ = W[R] = function(q,o){return _.isDom(q)?q:(o||D).querySelector(q)};
	_.id  = function(q,o){return (o||D).getElementById(q)}
	_.all = function(q,o){return (o||D).querySelectorAll(q)}
	_.ALL = function(q,o){o=_.all(q,o);
		return {
			all: o,
			each:function(fn){var i=0,I=o.length;for(;i<I;i++){fn(o[i],i,I)}},
			map:function(fn){var r=[],i=0,I=o.length;for(;i<I;I++){r.push(fn(o[i],i,I))}return r},
			move:function(ex){ex=_.all(ex);
				this.each(function(a,i,I){
					_.ONE(a).enter(function(){
						while(_.ARR(ex).has(o[i+1])&&i<I){i++}
						if(i>I-2)i=-1;
						o[i+1].getAttribute('type').toUpperCase()=='TEXT'?o[i+1].select():o[i+1].focus();
					});
				});
			},
		}
	}
	_.one = function(q,o){return _(q,o)}
	_.ONE = function(q,o){o=_(q,o);
		return {
			one: o,
			get:function(k){return o.getAttribute(k)},
			set:function(k,v){o.setAttribute(k,v)},
			remove: function(){

			},
			parent: function(tag){
				var p=o.parentNode,t=p.tagName;
				if(t=='HTML'){return null}else if(t==tag.toUpperCase()){return p}else{return _.parent(p,tag)}
			},
			setSelect: function(v){
				_.ALL('option',o).each(function(e){if(e.value==v)return e.selected='true'})
			},
			enter: function(f){
				o.addEventListener('keyup',function(e){var e=e||W.e;e.preventDefault();if(e.keyCode==13)f(e)},false)
			},

		}
	}
	_.ce = function(t,j){var k,l,e=D.createElement(t);
		if(j){for(k in j){if(j[k]){
			if(k=='attribute'){
				for(l in j[k]){if(j[k][l])e.setAttribute(l,j[k][l])}
			}else if(k=='style'){
				for(l in j[k]){if(j[k][l])e[k][l]=j[k][l]}
			}else{e[k]=j[k]}
		}}}return e;
	}
	_.CHK = function(q,o){
		return {
			val: function(){var r=[];_.ALL(q,o).each(function(a){if(a.checked)r.push(a.value)});return r;},
			to: function(Q){
				_(Q).addEventListener('change',function(){
					var v=this.checked;
					_.ALL(q,o).each(function(a){a.checked=v});
			},false)},
		}
	}

	_.ARR = function(arr){
		return {
			arr: arr,
			has:function(v){for(var i=0;i<arr.length;i++){if(arr[i]==v)return true}return false},
			map:function(f){var A=[];this.each(function(a,i,I,A){a.push(f(a,i,I,A))});return A},
			each:function(f){var i,I=arr.length;for(i=0;i<I;i++){f(arr[i],i,I,arr)}},
		}
	}


// return value "Arguments", "Array", "Boolean", "Date", "Error", "Function", "Math", "Number", "Object", "RegExp", "String", FormData
	_.type    = function(o){return Object.prototype.toString.call(o).slice(8,-1)}
	_.isDC 	  = function(s){return /^[a-zA-Z0-9\.-_]+$/.test(s)}
	_.isDom   = function(o){return !!(o&&window&&o.nodeType)}
	_.isJSON  = function(o){return _.type(o)=='Object'&&!o.length}
	_.isChar  = function(s){return /^[a-zA-Z]+$/.test(s)}
	_.isDigit = function(s){return /^[0-9\.-]+$/.test(s)}
	_.empty   = function(s){return s==null||s==undefined||s==''||s==0||s==[]||s=={}}
	
	_.hash 	  = function(s){var h=5381,i=0,I=s.length;for(;i<I;i++){h=h*33+s.charCodeAt(i)}return h%1013}
	_.concat  = function(){var r={},a=arguments,i=0,I=a.length,k;for(;i<I;i++){for(k in a[i]){r[k]=a[i][k]}}return r}
	

	_.url=function(){return L.href}
	_.URL=function(hrefLike){var m,n,p,h=hrefLike||L.href;
		return {
			url :h,
			base:function(){p=h.indexOf('?');return p>0?h.substr(0,p):h},
			all:function(){
				var j={},a=h.split(/[\?|&]/),i=1,I=a.length;
				for(;i<I;i++){
					p=a[i].indexOf('=');
					j[a[i].substr(0,p)]=a[i].substr(p+1);
				}
				return j;
			},
			get:function(k){return h.match(new RegExp('(/?|&)'+k+'=([^&]*)(&|$)'))[2]},
			set:function(k,v){
				m=k+'='+v;
				if(h.indexOf('?')>0){
					n=h.match(new RegExp("(/?|&)("+ k +"=[^&]*)(&|$)"));
					return n==null ? h+'?'+m : h.replace(n[2],m);
				}else{
					return h+'?'+m;
				}
			},
		}
	}
	_.fd=function(f){return _.empty  (f)?new FormData():_.type(f)=='FormData'?f:new FormData(_(f))}
	_.FD=function(f){f=_.fd(f);
		return {
			fd :f,
			app:function(j){for(var k in j){f.append(k,j[k])}return this},
			set:function(j){for(var k in j){f.set(k,j[k])}return this},
			del:function(a){for(var i=0;i<a.length;i++){f.delete(a[i])}return this},
			concat:function(F,isApp){
				F=_.fd(F);var i=F.entries(),r=i.next();
				if(isApp){
					while(!r.done){
						f.append(r.value[0],r.value[1]);
						r=i.next();
					}
				}else{
					while(!r.done){
						f.set(r.value[0],r.value[1]);
						r=i.next();
					}
				}
				return this;
			},
			json:function(){
				var k,v,j={},i=f.entries(),r=i.next();
				while(!r.done){
					k=r.value[0];
					v=r.value[1];
					if(j[k]){
						if(_.type(j[k])!='Array'){
							j[k]=[j[k]]
						}
						j[k].push(v)
					}else{
						j[k]=v;
					}
					
					r=i.next()
				}return j;
			},
		}
	}
	



	_.load = function(file,cb){
		var e = /\.js$/.test(file) ? _.ce('script',{src:file})
			  :/\.css$/.test(file) ? _.ce('link',{rel:'stylesheet', href:file})
			  : undefined;
		if(e){
			if(e.readyState){	//IE
				e.onreadystatechange=function(){
					if(e.readyState=='loaded'||e.readyState=='complete'){
						e.onreadystatechange=null;if(cb)cb();
			}}}else{e.onload=function(){if(cb)cb()}}
			_('head').appendChild(e);
		}
	}
	_.getScrollTop=function(){return D.documentElement.scrollTop||W.pageYOffset||D.body.scrollTop}
	_.setScrollTop=function(n){D.documentElement.scrollTop=W.pageYOffset=D.body.scrollTop=n}


	var xhr={
		xhrs:[],
		getInstance: function(){
			var x,i=0,I=this.xhrs.length;
			for(;i<I;i++){if(this.xhrs[i].readyState==0||this.xhrs[i].readyState==4){return this.xhrs[i]}}
			try{x=new XMLHttpRequest()}catch(e){alert('update browser!')}
			if(typeof x.withCredentials===undefined){alert('update browse!')}
			if(I<2){this.xhrs.push(x)}return x;
		},
		run:function(cfg){
			var x=this.getInstance(),
				p={
				'method'	: 'POST',
				'url'		: '',
				'data'		: null,
				'type'		: 'TEXT',
				'async'		: true,
				'random'	: true,
				'onload'	: function(res){console.log(res)},
				'onprogress': function(z,t){console.log(z+' / '+t)}
		    };
		    for(var k in cfg){if(cfg[k])p[k]=cfg[k]}
			if(p.random)p.url+=(p.url.indexOf('?')>0?'&':'?')+'r='+Math.random();
			
			x.open(p.method, p.url, p.async);

			if (_.isJSON(p.data)) {
				x.setRequestHeader('Content-type','application/json');
				p.data=JSON.stringify(p.data);
			}
			x.timeout = p.timeout || 30000;
			x.send(p.data);
			x.ontimeout  = p.ontimeout 	|| function(e){console.log('request timeout!')};
			x.onabort 	 = p.onabort	|| function(e){console.log('request abort!')};
			x.onerror 	 = p.onerror	|| function(e){console.log('request failed!' + e)};
			x.onloadstart= p.onloadstart|| function(e){console.log('request start...')};
			x.onload = function(e){
				if(this.status===200){
					var r=e.target.responseText;
					if(p.type.toUpperCase()=='JSON'){
						if(r==''){r={}}else{try{r=JSON.parse(r)}catch(e){console.log('json parse error:'+e)}}
					}
					p.onload(r);
				}else{
					console.log('Some error araise. This response staus is '+this.status);
				}
			};
			x.upload.onprogress=function(e){if(e.lengthComputable)p.onprogress(e.loaded,e.total)}
	}};
	_.ajax=function(cfg){xhr.run(cfg)}
	_.get =function(url,  cb,type){_.ajax({url:url,onload:cb,type:type,method:'GET'})}
	_.post=function(url,d,cb,type){_.ajax({url:url,onload:cb,type:type,method:'POST',data:d})}
	_.put =function(url,d,cb,type){_.ajax({url:url,onload:cb,type:type,method:'PUT', data:d})}
	_.delete=function(url,d,cb,type){_.ajax({url:url,onload:cb,type:type,method:'DELETE', data:d})}
})('R')




