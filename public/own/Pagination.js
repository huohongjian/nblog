/* author: HuoHongJian
**   date: 2017-09-28
**   func: a navigation bar thougth page parameter
*/
function Pagination(params){
	this.id;
	this.ul;
	this.container = 'body';
	this.curPage = 1;	//当前页数
	this.perPage = 9;	//显示页码个数
	this.totPage = 9;	//总页数
	this.perItem = 10;	//每页记录数
	this.totItem = -1;	//总记录数
	this.start   = 1; 	//开始页码
	this.run(params);
}
Pagination.Length = 0;
Pagination.prototype = {
	constructor: Pagination,

	hide: function(){this.ul.style.display='none';},
	show: function(){this.ul.style.display='inline';},
	reset: function(params) {
		this.set(params);
		this.setLi(this.start);
		this.setCurPage(this.curPage);
	},

	run: function(params) {
		this.set(params);

		if (++Pagination.Length==1) {
			this.R('head').appendChild(this.C('style', {
				type: 'text/css',
				innerHTML: '\
					.pagination {margin:0; padding:0; display:inline;}\
					.pagination li {margin:0 6px; display:inline; list-style-type:none; cursor:pointer;}\
					.pagination .active {font-weight:900; color:#990000;}',
			}));
		}

		this.ul = this.C('ul', {
			id: this.id, 
			className: 'pagination',
		});
		this.R(this.container).appendChild(this.ul);

		this.ul.appendChild(this.C('LI', {innerHTML:'<<'}));
		this.ul.appendChild(this.C('LI', {innerHTML:'...'}));
		for (var i=0; i<this.perPage; i++) {
			this.ul.appendChild(this.C('LI', {innerHTML: this.start + i}));
		}
		this.ul.appendChild(this.C('LI', {innerHTML:'...'}));
		this.ul.appendChild(this.C('LI', {innerHTML:'>>'}));

		this.setLi(this.start);
		this.setCurPage(this.curPage);
		
		
		var self = this;
		self.ul.addEventListener('click', function(e){
			var o = (e || window.e).target;
			if (o.tagName == 'LI') {
				var i = o.innerText;
				if (i=='<<') {
					self.start = 1;
					self.setLi(1);
				} else if (i=='>>') {
					self.start = Math.max(self.totPage-self.perPage+1, 1);
					self.setLi(self.start);
				} else if (i=='...') {
					if (o.previousSibling.innerText=='<<') {
						self.start = Math.max(self.start-self.perPage, 1);
					} else {
						var end = Math.min(self.start + self.perPage * 2, self.totPage);
						self.start = Math.max(end - self.perPage+1, 1);
					}
					self.setLi(self.start);
				} else {
					i = parseInt(i);
					self.curPage = i;
					if (self.onclick) self.onclick(i); 
				}
				self.setCurPage(self.curPage);
			}
		});
	},

	set: function(params) {
		for(var k in params) {
			this[k] = isNaN(params[k]) ? params[k] : parseInt(params[k]);
		}
		if(this.totItem>=0){
			this.totPage = Math.ceil(this.totItem / this.perItem);
		}
	},

	setLi: function(start) {
		var end = Math.min(start+this.perPage-1, this.totPage);

		var lis = this.ul.children;
		lis[0].style.display = 
		lis[1].style.display = start<=1 ? 'none' : 'inline';

		for (var i=0; i<this.perPage; i++) {
			lis[i+2].style.display = start + i>end ? 'none' : 'inline';
			lis[i+2].innerHTML = start + i;
		}
		lis[this.perPage+2].style.display = 
		lis[this.perPage+3].style.display = end>=this.totPage ? 'none' : 'inline';
	},

	setCurPage: function(curpage) {
		var i, lis = this.ul.children;
		for(i=2; i<lis.length-2; i++) {
			lis[i].className = lis[i].innerText == curpage ? 'active' : '';
		}
		this.curPage = curpage;
	},

	R: function(q,o){return (window&&q&&q.nodeType)?q:(o||document).querySelector(q)},
	C: function(t,j){var k,e=document.createElement(t);if(j){for(k in j){if(j[k]){e[k]=j[k]}}}return e},
}