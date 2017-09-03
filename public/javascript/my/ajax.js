var R = R || {};

(function(_){

	// R.get =function(url, cb, type){_.ajax({"url":url, "onload":cb, "method":"GET", "type":type||"TEXT"})}
	// R.post=function(url, data, cb, type){_.ajax({"url":url, "onload":cb, "data":data, "type":type||"TEXT"})}
	R.ajax=function(cfg){_.xhr.run(cfg)}
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
				"random"	: true,
		        "onload"	: function(res){console.log(res)},
		        "onprogress": function(loaded,total){console.log(loaded+'/'+total)}
		    };
		    for(var k in cfg){p[k]=cfg[k]}
			if(p.random)p.url+=(p.url.indexOf("?")>0?"&":"?")+"r="+Math.random();
			
			var xhr = this.getInstance();
			xhr.open(p.method, p.url, p.async);
			if (R.isJSON(p.data)) {
				xhr.setRequestHeader("Content-type","application/json");
				p.data = JSON.stringify(p.data);
			}
		//	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
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
})(R);