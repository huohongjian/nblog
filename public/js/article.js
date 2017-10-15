function addToBook() {
	R.get('../book', function(data) {
		var html, I = data.length;
		if (I==0) {
			html = '<div class="text-left m30">\
					<p>您还没有电子书。</p>\
					<p>请先添加一本电子书，才可进行下一步操作。</p>\
					<p>如何添加电子书，请到添加页面查看帮助。</p>\
					</div><button onclick="panel(\'\',1)">确定</button>\
					';
		} else {
			html = '<ul class="none text-left" style="padding:20px;">';
			for (var i=0; i<I; i++) {
				html += '<li><label><input type="radio" name="bookid" value="'
					 + data[i][0] + '"/>' + data[i][1] + '</lable></li>';
			}
			html += '</ul><input type="button" value="确认" onclick="doAddToBook()"/>'
		}
		
		panel(html, false, {
			title: '选择电子书',
			width: 400,
			height: 300,
		});
	}, 'json');
}
function doAddToBook() {
	var checked = R('input[name=bookid]:checked');
	if (checked) {
		var id = window.location.href;
		R.post('../book', {
			bookid: 	checked.value,
			articleid: 	id.substring(id.indexOf('/article/')+9),
			title: 		R.id('title').innerHTML,
		}, function(data) {
			panel('<p class="mt50">'+data+'</p>', 1500);
		});
	} else {
		alert('请选择电子书!');
	}
}