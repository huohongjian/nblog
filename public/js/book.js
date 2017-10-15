
var allA = R.all('#catalog a'),
	totL = allA.length,
	curI = -1,
	curA;

function getHashIndex(hash) {
	for (var i=0; i<totL; i++) {
		if (allA[i].getAttribute('href') == hash) {
			return i;
		}
	}
	return -1;
}


if (window.location.hash) {
	renderDocument(window.location.hash);
} else if (R.empty(R.id('content').innerHTML)) {
	gotoCaption(1);
}

window.onhashchange = function() {
	renderDocument(window.location.hash)
};


function renderDocument(hash) {
	if (curA) {
		curA.className = '';
	}
	curA = allA[getHashIndex(hash)];
	curA.className = 'current';

	var o = R.id('content');
	o.innerHTML = '<b>loading...</b>';
	R.post('', {articleid: hash.substr(1)}, function(data) {
		R.id('title').innerHTML		= data.title;
		R.id('username').innerHTML	= '贡献者：'   + data.username;
		R.id('counter').innerHTML	= '阅读：' 	   + data.counter;
		R.id('category').innerHTML	= '类别：' 	   + data.category;
		R.id('newtime').innerHTML	= '更新时间：' + data.newtime;
		if (data.editable) {
			R.id('editable').innerHTML =
			'<a href="../user/edit/' + data.articleid + '">编辑</a>';
		}
		o.innerHTML	= data.content;
	}, 'json');
}


R.id('book-menu').addEventListener('click', function(e) {
	var o = (e||window.e).target;
	if (o.tagName=='LI') {
		switch (o.innerHTML) {
			case '💡': 
				console.log(this.offsetHeight>100)
				if (this.offsetHeight>100) {
					this.style.height = '40px';
				} else {
					this.style.height = 'auto';
				}
				break;
			case '首页':
				window.location.href = '/';
				break;
			case '主页':
				window.location.href = '/user';
				break;
			case '上一章':
				gotoCaption(-1);
				break;
			case '下一章':
				gotoCaption(1);
				break;
		}
	}
}, false);


function gotoCaption(step) {
	var c = getHashIndex(window.location.hash) + step;
	if (c<0) {
		panel('<br><br>已经到顶了!', 1000);
	} else if (c>=totL) {
		panel('<br><br>已经到底了!', 1000);
	} else {
		window.location.hash = allA[c].getAttribute('href');
	}

}
