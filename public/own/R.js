(function(R){
	var W = window,
		D = W.document,
		L = W.location,
		_ = W[R] = function(q,o){return _.isDom(q)?q:(o||D).querySelector(q)};
	_.id  = function(q,o){return (o||D).getElementById(q)}
	_.all = function(q,o){return (o||D).querySelectorAll(q)}
	_.ALL = function(q,o){o=_.all(q,o);
		return {
			each:function(fn){var i=0,I=o.length;for(;i<I;i++){fn(o[i],i,I,o)}},
			map:function(fn){var r=[],i=0,I=o.length;for(;i<I;I++){r.push(fn(o[i],i,I,o))}return r},
		}
	}
	_.one = function(q,o){return _(q,o)}
	_.ONE = function(q,o){o=_(q,o);
		return {
			remove: function(){

			},
			parent: function(tag){
				var p=o.parentNode,t=p.tagName;
				if(t=='HTML'){return null}else if(t==tag.toUpperCase()){return p}else{return _.parent(p,tag)}
			},
			setSelect: function(v){
				_.ALL('option',o).each(function(e){if(e.value==v)return e.selected='true'})
			},
			enter: function(f,b){
				o.addEventListener('keyup',function(e){var e=e||W.e;e.preventDefault();if(e.keyCode==13)f(e)},b||false)
			},

		}
	}

	_.ce  = function(t,j){var k,l,e=D.createElement(t);
		if(j){for(k in j){if(j[k]){
			if(k=='attribute'){
				for(l in j[k]){if(j[k][l])e.setAttribute(l,j[k][l])}
			}else if(k=='style'){
				for(l in j[k]){if(j[k][l])e[k][l]=j[k][l]}
			}else{e[k]=j[k]}
		}}}return e;
	}


	_.inArray=function(search,array){
		for(var i in array){
			if(array[i]==search){return true}
		}
		return false;
	}

// return value "Arguments", "Array", "Boolean", "Date", "Error", "Function", "JSON", "Math", "Number", "Object", "RegExp", "String", FormData
	_.type    = function(o){return Object.prototype.toString.call(o).slice(8,-1)}
	_.isDC 	  = function(s){return /^[a-zA-Z0-9\.-_]+$/.test(s)}
	_.isDom   = function(o){return !!(o&&window&&o.nodeType)}
	_.isJSON  = function(o){return _.type(o)=='Object'&&!o.length}
	_.isChar  = function(s){return /^[a-zA-Z]+$/.test(s)}
	_.isDigit = function(s){return /^[0-9\.-]+$/.test(s)}
	_.isEmpty = function(s){return s==null||s==undefined||s==''||s==0||s==[]||s=={}}
	
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
	_.fd=function(f){return _.isEmpty(f)?new FormData():_.type(f)=='FormData'?f:new FormData(_(f))}
	_.FD=function(f){f=_.fd(f);
		return {
			fd :f,
			app:function(j){for(var k in j){f.append(k,j[k])}return this},
			set:function(j){for(var k in j){f.set(k,j[k])}return this},
			del:function(a){for(var i=0;i<a.length;i++){f.delete(a[i])}return this},
			concat:function(F,app){
				F=_.fd(F);var i=F.entries(),r=i.next();
				if(app){
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
	
	_.enter =function(q,f,b){_(q).addEventListener('keyup',function(e){var e=e||W.e;e.preventDefault();if(e.keyCode==13)f(e)},b||false)}
	_.move  =function(qs,ex){
		ex=_.all(ex);
		_.ALL(qs||'form input').each(function(e,i,s,E){
			_.enter(e,function(){
				while(_.inArray(E[i+1],ex)&&i<s){i++}
				if(i>s-2)i=-1;E[i+1].focus();
			});
		});
	}


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
				'data'		: null, //FormData or JSON
				'type'		: 'JSON',
				'async'		: true,
				'random'	: true,
				'onload'	: function(res){console.log(res)},
				'onprogress': function(z,t){console.log(z+' / '+t)}
		    };
		    for(var k in cfg){p[k]=cfg[k]}
			if(p.random)p.url+=(p.url.indexOf('?')>0?'&':'?')+'r='+Math.random();
			
			x.open(p.method, p.url, p.async);
			// if (_.isJSON(p.data)) {
			// 	x.setRequestHeader('Content-type','application/json');
			// 	p.data = JSON.stringify(p.data);
			// }
		//	x.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');//for formdata
		//	x.setRequestHeader('Content-type', 'multipart/form-data');
			x.timeout = p.timeout || 30000;
			x.send(p.data);
			x.ontimeout  = p.ontimeout 	|| function(e){console.log('request timeout!')};
			x.onabort 	 = p.onabort	|| function(e){console.log('request abort!')};
			x.onerror 	 = p.onerror	|| function(e){console.log('request failed!' + e)};
			x.onloadstart= p.onloadstart|| function(e){console.log('request start...')};
			x.onload     = function(e){
				if(this.status===200){
					var r=e.target.responseText;
					if(p.type.toUpperCase()=='JSON'){
						if(r==''){r={}}else{try{r=JSON.parse(r)}catch(e){console.log('json parse error:'+e)}}
					}p.onload(r);
			}}
			x.upload.onprogress=function(e){if(e.lengthComputable)p.onprogress(e.loaded,e.total)}
	}};
	_.get =function(url,  cb,type){_.ajax({url:url,onload:cb,type:type||'TEXT',method:'GET'})}
	_.post=function(url,d,cb,type){_.ajax({url:url,onload:cb,type:type||'TEXT',data:d})}
	_.ajax=function(cfg){xhr.run(cfg)}

})('R')




