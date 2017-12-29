/* author: HuoHongJian
**   date: 2017-12-25
**   func: a navigation bar thougth page parameter
*/
function Pagination(params){
	this.id;
	this.ul;
	this.container = 'body';
	this.perItem = 10;	//每页记录数
	this.totItem = -1;	//总记录数

	this.perPage = 9;	//每页页码数
	this.totPage = 1;	//总页码
	this.curPage = 1;	//当前页码

	this.run(params);
}
Pagination.Length = 0;
Pagination.prototype = {
	constructor: Pagination,
	hide: function(){this.ul.style.display='none';},
	show: function(){this.ul.style.display='inline';},
	go: function(page){
		var t = this;
		t.curPage = Math.min(Math.max(page||1, 1), t.totPage);
		t.start = Math.max(t.curPage - Math.ceil(t.perPage / 2), 1);
		t.createLis();
	},
	skip: function(step){
		this.start = Math.min(Math.max(this.start + step||0, 1), this.totPage);
		this.createLis();
	},

	run: function(params) {
		var t = this;
		for(var k in params) {
			t[k] = isNaN(params[k]) ? params[k] : parseInt(params[k]);
		}
		if(t.totItem>0){
			t.totPage = Math.ceil(t.totItem / t.perItem);
		}
		t.start = (Math.ceil(t.curPage/t.perPage)-1) * t.perPage + 1;
		if (++Pagination.Length==1) {
			t.R('head').appendChild(t.C('style', {
				type: 'text/css',
				innerHTML: '\
					ul.pagination {margin:0; padding:0; display:inline; border:1px solid #ccc; border-radius:3px;}\
					ul.pagination li {padding:0 6px; display:inline; list-style-type:none; cursor:pointer;}\
					ul.pagination li:not(:last-child){border-right:1px solid #ccc;}\
					ul.pagination li:hover{color:#fff; background-color:#053864;}\
					ul.pagination li:first-child{border-top-left-radius:3px;border-bottom-left-radius:3px;}\
				   	ul.pagination li:last-child{border-top-right-radius:3px;border-bottom-right-radius:3px;}\
					ul.pagination li.active {background-color:#08599E; font-weight:700; color:#fff;}',
			}));
		}
		t.ul = t.C('ul', {id: t.id, className: 'pagination'});
		t.ul.addEventListener('click', function(e){
			var o = (e || window.e).target;
			if (o.tagName == 'LI') {
				var i = o.innerText;
				if (i=='<<') {
					t.skip(-t.totPage);
				} else if (i=='>>') {
					t.skip(t.totPage);
				} else if (i=='...') {
					if (o.previousSibling.innerText=='<<') {
						t.skip(-t.perPage);
					} else {
						t.skip(t.perPage);
					}
				} else {
					for(var j=0; j<t.ul.children.length; j++){
						var li = t.ul.children[j];
						if(li.innerText == i){
							li.setAttribute('class', 'active');
						}else{
							li.removeAttribute('class');
						}
					}
					t.curPage = parseInt(i);
					if (t.onclick) t.onclick(t.curPage); 
				}
			}
		});
		t.createLis();
		t.R(t.container).appendChild(t.ul);
	},
	
	createLis: function(){
		var t = this;
		t.end   = Math.min(t.start + t.perPage - 1, t.totPage);
		t.start = Math.max(t.end - t.perPage + 1, 1);
		t.ul.innerHTML = '';

		if(t.start>1){	
			t.ul.appendChild(t.C('LI', {innerHTML:'<<'}));
			t.ul.appendChild(t.C('LI', {innerHTML:'...'}));
		}
		for (var i=t.start; i<=t.end; i++) {
			if(i==t.curPage){
				t.ul.appendChild(t.C('LI', {innerHTML: i, className: 'active'}));
			}else{
				t.ul.appendChild(t.C('LI', {innerHTML: i}));
			}
		}
		if(t.end<t.totPage){
			t.ul.appendChild(t.C('LI', {innerHTML:'...'}));
			t.ul.appendChild(t.C('LI', {innerHTML:'>>'}));
		}
	},
	R: function(q,o){return (window&&q&&q.nodeType)?q:(o||document).querySelector(q)},
	C: function(t,j){var k,e=document.createElement(t);if(j){for(k in j){if(j[k]){e[k]=j[k]}}}return e},
}
