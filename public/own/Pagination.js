/* author: HuoHongJian
**   date: 2017-03-28
**   func: a navigation bar thougth page parameter
*/
function Pagination(params){
	this.container = 'body';
	this.pagination;
	this.page = 11;
	this.total = 20;
	this.maxPerPage = 7;
	this.displayArrow = true;
	this.displayEllipse = true;

	for(var k in params) {if (params[k]!==undefined) this[k] = params[k]}
	this.index = Pagination.Length++;
	this.run();
}
Pagination.Length = 0;
Pagination.prototype = {
	constructor: Pagination,
	run: function() {
		var p = this, t;
		
		
		if (p.index==0) {
			p.R('head').appendChild(p.C('style', {
				type: 'text/css',
				innerHTML: '\
					.pagination {}\
					.pagination li {margin:0 5px; display:inline-block; list-style-type:none; cursor:pointer;}\
					.pagination .active {font-weight:900;}',
			}));
		}

		var start = Math.max(p.page - Math.floor(p.maxPerPage/2), 1),
			end   = Math.min(start + p.maxPerPage, p.total+1),
			start = Math.max(end - p.maxPerPage, 1),
			
			ul = p.C('ul', {
				id: p.id, 
				className: 'pagination',
			});

		if (start>1) {
			if (p.displayArrow) ul.appendChild(p.createLi('«', 1));
			if (p.displayEllipse) {
				t = p.page - p.maxPerPage;
				if (t<1) t=1;
				ul.appendChild(p.createLi('…', t))
			}
		}
		for (var i=start; i<end; i++) {
			ul.appendChild(p.createLi(i, i, i==p.page));
		}
		if (end<p.total) {
			if (p.displayEllipse) {
				t = p.page + p.maxPerPage;
				if (t>p.total) t = p.total;
				ul.appendChild(p.createLi('…', t));
			}
			if (p.displayArrow) ul.appendChild(p.createLi('»', p.total));
		}

		p.R(p.container).appendChild(ul);

		ul.addEventListener('click', function(e){
			e = e || window.e;
			if (e.target.tagName == 'LI' && p.onclick) {
				p.onclick(e.target.getAttribute('data-index'));
			}
		});
	},

	createLi: function(html, index, isCurrent) {
		var li = this.C('li', {
			className: isCurrent ? 'active' : undefined,
			innerHTML: html,
		});
		li.setAttribute("data-index", index);
		return li;
	},

	R: function(q,o){return (window&&q&&q.nodeType)?q:(o||document).querySelector(q)},
	C: function(t,j){var k,e=document.createElement(t);if(j){for(k in j){if(j[k]){e[k]=j[k]}}}return e},


}