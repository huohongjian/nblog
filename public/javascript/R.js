(function(W){
	var D = W.document;
	W.R =_= function(q,o){return _.isDom(q)?q:(o||D).querySelector(q)}
	_.all = function(q,o){return _.isDom(q)?q:(o||D).querySelectorAll(q)}
	_.id  = function(q,o){return _.isDom(q)?q:(o||D).getElementById(q)}
	_.log = function(x){_.each(x,function(v,k){console.log(k+' => '+v)})}
	_.each= function(x,f){if(_.isJSON(x)){for(var k in x)f(x[k],k,x)}else{for(var i=0,I=x.length;i<I;i++)f(x[i],i,x)}}
	_.ce  = function(t,j){
		var k,l,e=D.createElement(t);if(j){for(k in j){if(j[k]){if(k==='style'){
		for(l in j[k]){e[k][l]=j[k][l]}}else{e[k]=j[k]}}}}return e;
	}
	_.url = function(s){
		var i,j,r={};a=(s||W.location.search.substr(1)).split("&");
		for(i=0;i<a.length;i++){j=a[i].split("=");r[j[0]]=unescape(j[1])}
		return r;
	}
	_.fd=_.formData=function(form,j){
		var fd=this.isEmpty(form)?new FormData:new FormData(_.id(form));
		if(j){for(var k in j)fd.append(k,j[k])}return fd;
	}
	_.concat = function(){var r={},a=arguments;for(var i=0;i<a.length;i++){for(var k in a[i]){r[k]=a[i][k]}}return r;}
	_.rv =_.getRadioValue=function(name){var o=_.gn(name);for(var i=0;i<o.length;i++){if(o[i].checked)return o[i].value}}
	_.cv =_.getCheckboxValue=function(name){return _.cvs(name).length>0?true:false}
	_.cvs=_.getCheckboxValues=function(name){var o=_.gn(name),vs=[];for(var k in o){if(o[k].checked)vs.push(o[k].value)}return vs;}
	_.fvs=_.getFormValues=function(form){
		var f=form?_.ID(form):_('form'),os=_.all('input,textarea',f),rs={};
		for(var i=0;i<os.length;i++){
			var o=os[i], n=o.name||o.id||'_'+i+'_', v=o.value;
			switch(o.type.toUpperCase()){
				case 'RADIO': if(o.checked){rs[n]=v}break;
				case 'CHECKBOX':rs[n]=rs[n]||[];if(o.checked){rs[n].push(v)}break;
				default: rs[n]=v;
		}}return rs;
	}

	_.getScrollTop=function(){return D.documentElement.scrollTop||W.pageYOffset||D.body.scrollTop}
	_.setScrollTop=function(n){D.documentElement.scrollTop=W.pageYOffset=D.body.scrollTop=n}
	_.enterMoveFocus=function(form) {
		var fm = form ? _.id(form) : _('form');
		if (fm) {
			var	os = _.all('input[type=text],input[type=number],input[type=email],input[type=password],'
				   + 'input[type=url],input[type=button], textarea, button', fm);
			fm.onkeyup = function(e){
				if((e||W.e).keyCode==13){
					var o=e.srcElement||e.target;
					for(var i=0;i<os.length-1;i++){
						if(o===os[i]&&o.tagName=='INPUT'){os[i+1].select();break}
		}}}}
		os[0].select();
		return fm;
	}

	_.isJSON  = function(o){return typeof(o)=="object"&&Object.prototype.toString.call(o).toLowerCase()=="[object object]"&& !o.length} 
	_.isDom   = function(o){return !!(o&&window&&o.nodeType)}
	_.isEmail = function(s){return /^[a-zA-Z_0-9]+@[a-zA-Z_0-9]+\.[a-zA-Z_0-9]+$/.test(s)}
	_.isDigit = function(s){return /^[0-9\.-]*$/.test(s)}
	_.isEmpty = function(s){return s==null || s==undefined || s=='' || s==0 || s==[] || s=={}}
	_.isDigChar = function(str,n,m) {
		var reg = RegExp('^[a-zA-Z_0-9]{' + n + ',' + m +'}$');
		if (m===undefined) reg = RegExp('^[a-zA-Z_0-9]{' + n + ',}$');
		if (n===undefined) reg = RegExp('^[a-zA-Z_0-9]$');
		return reg.test(str);
	}
	
	_.get =function(url, cb, type){_.ajax({"url":url, "onload":cb, "method":"GET", "type":type||"TEXT"})}
	_.post=function(url, data, cb, type){_.ajax({"url":url, "onload":cb, "data":data, "type":type||"TEXT"})}
	_.ajax=function(cfg){_.xhr.run(cfg)}
	_.xhr ={xhrs:[],
		getInstance: function(){
			var xhr, ln=this.xhrs.length;
			for(var i=0;i<ln;i++){if(this.xhrs[i].readyState==0||this.xhrs[i].readyState==4){return this.xhrs[i]}}
			try{xhr=new XMLHttpRequest()}catch(e){alert('update browser!')}
			if(typeof xhr.withCredentials===undefined){alert('update browse!')}
			if(ln<2){this.xhrs.push(xhr)}return xhr;
		},
		run:function(cfg){
			var p={
				"method"	: "POST",
				"url"		: "",
				"data"		: null, //FormData or JSON
				"type"		: "JSON",
				"async"		: true,
		        "onload"	: function(res){console.log(res)},
		        "onprogress": function(loaded,total){console.log(loaded+'/'+total)}
		    };
		    for(var k in cfg){p[k]=cfg[k]}
			p.url += (p.url.indexOf("?")>0?"&":"?") + "r="+Math.random();
			
			var xhr = this.getInstance();
			xhr.open(p.method, p.url, p.async);
			if (_.isJSON(p.data)) {
				xhr.setRequestHeader("Content-type","application/json");
				p.data = JSON.stringify(p.data);
			}
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
		//	xhr.setRequestHeader("Content-type", "multipart/form-data");
			xhr.timeout = p.timeout || 30000;
			xhr.send(p.data);
			xhr.ontimeout 	= p.ontimeout 	|| function(e){console.log('request timeout!')};
			xhr.onabort 	= p.onabort		|| function(e){console.log('request abort!')};
			xhr.onerror 	= p.onerror		|| function(e){console.log('request failed!')};
			xhr.onloadstart	= p.onerror		|| function(e){console.log('request start..., xhrs.length:' + _.xhr.xhrs.length)};
			xhr.onload     	= function(e){
				if (this.status == 200) {
					var r = e.target.responseText;
					if(p.type.toUpperCase()=='JSON'){
						if(r==''){r={}}else{try{r=JSON.parse(r)}catch(e){console.error('json parse error:'+e)}}
					}
					p.onload(r);
				}
			}
			xhr.upload.onprogress=function(e){if(e.lengthComputable)p.onprogress(e.loaded,e.total)}
		}
	};



	_.pagination=function(cfg){return new _.Pagination().run(cfg)}
	_.Pagination=function(){}
	_.Pagination.prototype={
		constructor: _.Pagination,
		run:function(cfg){
			var p={
				tp:8,
				lmt:10
			};
			for(var k in cfg){p[k]=cfg[k]}

			var s, e,
				ps=_.url(),
				curPage = ps.page||1,
				L=W.location,
				href = L.origin + L.pathname + '?';
			for(var k in ps){
				if(k!='page')href+=k+'='+ps[k]+'&';
			}

			s=curPage-Math.ceil(p.lmt/2-0.5);
			if(s<1)s=1;
			e=s+p.lmt-1;
			if(e>p.tp){
				s=s-(e-p.tp);
				if(s<1)s=1;
				e=p.tp;
			}
			this.o=_.ce('ul');
			if(s>1){
				this.o.appendChild(this._cl('<<',{'href':href+'page=1'}));
				this.o.appendChild(this._cl('...', {}));
			}
			for(var i=s;i<=e;i++){
				this.o.appendChild(this._cl(i, {"href":href+'page='+i}, i==curPage));
			}
			if(e<p.tp){
				this.o.appendChild(this._cl('...', {}));
				this.o.appendChild(this._cl('>>',{'href':href+'page='+p.tp}));
			}
			return this;
		},
		_cl:function(text, att, isActive){
			var l=_.ce('li'),
				a=_.ce('a',att);
			a.textContent=text;
			l.appendChild(a);
			if(isActive)l.className='active';
			return l;
		},
		appendTo:function(id){
			_.id(id).appendChild(this.o);
			return this;
		}
	}
})(window)




