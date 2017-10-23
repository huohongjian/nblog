
function Catalog(selector, params) {
	this.query = 'h1,h2,h3:not([class=admontitle]),h4,h5,h6';
	this.css = '../own/Catalog.css';
	this.indent = 20;   //缩进
	this.index = Catalog.LEN++;
	this.create(selector, params);
}
Catalog.LEN = 0;
Catalog.prototype = {
	constructor: Catalog,

	create: function(selector, params){
		var t = this;
		if(t.index==0 && t.css!=''){
			t.R('head').appendChild(t.C('link', {
				rel: 'stylesheet',
				href: t.css,
			}));
		}
		for(var k in params){if(params[k]!==undefined)t[k]=params[k]}
		
		t.container = t.C('div', {
			className: 'catalog',
		});
 		document.body.appendChild(t.container);

		t.container.appendChild(t.C('div', {
			className: 'catalog-bar',
			innerHTML: '目录导航',
		}));

		var content = t.C('div', {
			className: 'catalog-content',
		});
		t.container.appendChild(content);

		var hs = t.R(selector||document).querySelectorAll(t.query),
			pl = parseInt(hs[0].tagName.substring(1)),
			pm = 0, i = 0, I = hs.length;
		for(; i<I; i++){
			hs[i].setAttribute('id', 'catalog-' + i);

			var cl = parseInt(hs[i].tagName.substring(1)),
				cm = pm + (cl - pl) * t.indent;
		//		pl = cl;
		//		pm = cm;

			var item = t.C('a', {
				href : '#catalog-' + i,
				innerHTML: hs[i].innerHTML.replace(/<\/?[^>]+>/g,''),
				style: {
					marginLeft: cm+'px',
				}
			});
			
			content.appendChild(item);
		}


	},


	R: function(q,o){return (window&&q&&q.nodeType)?q:(o||document).querySelector(q)},
	C: function(t,j){var k,l,e=document.createElement(t);if(j){for(k in j){if(j[k]){
		if(k=='attribute'||k=='att'){for(l in j[k]){if(j[k][l])e.setAttribute(l,j[k][l])}
		}else if(k=='style'){for(l in j[k]){if(j[k][l])e[k][l]=j[k][l]}}else{e[k]=j[k]}}}}return e;
	},
};


