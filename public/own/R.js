(function(R){
	var W = window,
		D = W.document,
		L = W.location,
		_ = W[R] = function(q,o){return _.isDom(q)?q:(o||D).querySelector(q)};
	_.id  = function(q,o){return (o||D).getElementById(q)}
	_.all = function(q,o){return (o||D).querySelectorAll(q)}
	_.ALL = function(q,o){var a=o===true||typeof q!='string'?q:_.all(q,o);
		return {
			each:function(fn){var i=0,I=a.length;for(;i<I;i++){fn(a[i],i,I,a)}},
			map:function(fn){var r=[],i=0,I=a.length;for(;i<I;I++){r.push(fn(a[i],i,I,a))}return r},
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
	_.parent  = function(q,tag){var o=_(q),p=o.parentNode,t=p.tagName;
		if(t=='HTML'){return null}else if(t==tag.toUpperCase()){return p}else{return _.parent(p,tag)}
	}

	_.isLast=_.isEnd=function(o,a){return o===a[a.length-1]?true:false}
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
	_.url = function(s){
		var j,r={};a=(s||L.search.substr(1)).split('&'),i=0,I=a.length;
		for(;i<I;i++){j=a[i].split('=');if(j[1])r[j[0]]=j[1]}return r;
	}
	_.setURL = function(params, url){
		var k,r='',s=[],u=params,url=url||L.href;s[0]=url;
		if (/\?/.test(url)){s=url.split('?');u=_.concat(_.url(s[1]),u||{})}
		for(k in u){if(u[k])r+='&'+k+'='+u[k]}return s[0]+'?'+r.substr(1);
	}
	_.baseURL = function(search, start){return L.href.substr(start||0, L.href.indexOf(search))}
	

	_.fd=_.formData=function(f,j,a){
		var i,k,d,t,F=new FormData(),J={},A=[],s=function(o){
			if(!_.isEmpty(o)){
				t=_.type(o);
				if(t=='Array'){A=o}
				else if(t=='Object'){J=o}
				else{o=_(o);if(_.type(o)=='HTMLFormElement'){F=new FormData(o)}}
			}
		};
		s(a);s(j);s(f);
		for(k in J){F.set(k,J[k])}
		for(i=0;i<A.length;i++){if(F.has(A[i]))F.delete(A[i])}
		return F;
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
	
	_.focus =function(q,o){_(q,o).focus()}
	_.select=function(q,o){_(q,o).select()}
	_.enter =function(q,f,b){_(q).addEventListener('keyup',function(e){var e=e||W.e;e.preventDefault();if(e.keyCode==13)f(e)},b||false)}
	_.move  =function(qs,ex){
		ex=_.all(ex);
		_.ALL(qs||'form input').each(function(e,i,s,E){
			_.enter(e,function(){
				while(_.inArray(E[i+1],ex)&&i<s){i++;}
				if(i>s-2)i=-1;E[i+1].select();
			});
		});
	}
	_.setSelectValue=function(q,v){_.ALL('option',_(q)).each(function(o){if(o.value==v)return o.selected='true'})}


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
			if (_.isJSON(p.data)) {
				x.setRequestHeader('Content-type','application/json');
				p.data = JSON.stringify(p.data);
			}
		//	x.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
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




