(function(R){
	var W = window,
		D = W.document,
		_ = W[R] = function(q,o){return _.isDom(q)?q:(o||D).querySelector(q)};

	_.all = function(q,o){return (o||D).querySelectorAll(q)}
	_.id  = function(q,o){return (o||D).getElementById(q)}
	_.ce  = function(t,j){var k,l,e=D.createElement(t);
		if(j){for(k in j){if(j[k]){
			if(k=='attribute'){
				for(l in j[k]){if(j[k][l])e.setAttribute(l,j[k][l])}
			}else if(k=='style'){
				for(l in j[k]){if(j[k][l])e[k][l]=j[k][l]}
			}else{e[k]=j[k]}
		}}}return e;
	}
	_.isDom   = function(o){return !!(o&&window&&o.nodeType)}
	_.isJSON  = function(o){return typeof(o)==='object'&&Object.prototype.toString.call(o).toLowerCase()==='[object object]'&&!o.length} 
	_.isFunc  = function(o){return typeof(o)==='function'}
	_.isChar  = function(s){return /^[a-zA-Z]+$/.test(s)}
	_.isDigit = function(s){return /^[0-9\.-]+$/.test(s)}
	_.isEmpty = function(s){return s==null||s==undefined||s==''||s==0||s==[]||s=={}}
	_.hash 	  = function(s){var h=5381,i=0,I=s.length;for(;i<I;i++){h=h*33+s.charCodeAt(i)}return h%1013}

	_.load = function(file, callback) {
		var script = file.match(/\..*/)=='.js'
					? _.ce('script', {src:file})
					: _.ce('link', {rel:'stylesheet', href:file})
		if(script.readyState){	//IE
			script.onreadystatechange=function(){
				if(script.readyState=='loaded'||script.readyState=='complete'){
					script.onreadystatechange=null;
					callback();
				}
			}
		}else{
			script.onload=function(){callback()}
		}
		_('head').appendChild(script);
	}
	var fns={}, exec=function(n){while(fns[n].e.length>0){fns[n].e.shift()()}};
	_.loadOnce = function(file, callback){
		var i = file.lastIndexOf('/'),
			d = file.lastIndexOf('.'),
			n = file.substring(i+1, d);
		if(!fns[n])fns[n]={e:[]};
		fns[n].e.push(callback);
		if(fns[n].f!==false){
			if(fns[n].f){
				exec(n)
			}else{
				_.load(file, function(){fns[n].f=true; exec(n)});
				fns[n].f=false;
			}
		}
	}

	_.require = function(file){
		var i = file.lastIndexOf('/'),
			d = file.lastIndexOf('.'),
			n = file.substring(i+1, d);
		return function(){
			var a = arguments;
			console.log(a)
			_.loadOnce(file, function(){R[n].apply(null, a)});
		}
	}

		

	// _.rv =_.getRadioValue=function(name){var o=_.gn(name);for(var i=0;i<o.length;i++){if(o[i].checked)return o[i].value}}
	// _.cv =_.getCheckboxValue=function(name){return _.cvs(name).length>0?true:false}
	// _.cvs=_.getCheckboxValues=function(name){var o=_.gn(name),vs=[];for(var k in o){if(o[k].checked)vs.push(o[k].value)}return vs;}
	// _.fvs=_.getFormValues=function(form){
	// 	var f=form?_.ID(form):_('form'),os=_.all('input,textarea',f),rs={};
	// 	for(var i=0;i<os.length;i++){
	// 		var o=os[i], n=o.name||o.id||'_'+i+'_', v=o.value;
	// 		switch(o.type.toUpperCase()){
	// 			case 'RADIO': if(o.checked){rs[n]=v}break;
	// 			case 'CHECKBOX':rs[n]=rs[n]||[];if(o.checked){rs[n].push(v)}break;
	// 			default: rs[n]=v;
	// 	}}return rs;
	// }

})('R')




