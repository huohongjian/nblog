
var oStatus = R('select[name="status"]');
var oCategory = R('select[name="category"]');
R.SEL(oStatus).set(oStatus.dataset.val);
R.SEL(oCategory).set(oCategory.dataset.val);

R.ALL('form[name=editForm] input').move();
R('input[name="title"]').focus();
R('#thumbimg').addEventListener('dblclick', setThumbImg, false);


function setThumbImg() {
	var oThumb = R('input[name="thumb"]');
	var max = parseInt(oThumb.dataset.maxnum);
	var rnd = Math.ceil(Math.random()*max);
	var src = '/thumbs/' + rnd + '.jpg';
	oThumb.value = src;
	R.id('thumbimg').src = oThumb.dataset.baseurl + src;
}

function setImage() {
	var src = R('input[name="thumb"]').value;
	if (src=='') {
		src = '/images/noimage.png';
	}
	R.id('thumbimg').src = src;
}




function help() {
	var html = '<br><ul class="text-left">\
				<li>列表显示文章时，别名优先标题显示。</li>\
				<li>单击`选择`微缩图，可上传也可选择微缩图。</li>\
				<li>双击微缩图，可在微缩图库中随机更换。</li>\
				<li>状态\
					<ul>\
						<li>公开：任何人都可浏览、都可搜索</li>\
						<li>隐藏：只有本人可浏览、可搜索</li>\
						<li>删除：本人也不可浏览、不可搜索，但可通过文章管理更改状态</li>\
					</ul>\
				</li>\
				<li>如何添加电子书：<br>首先在用户文章类别管理中，增加`电子书`类别。<br>然后添加文档，选择`电子书`类别即可。</li>\
				<li>两个编辑器的特点：\
					<ul>\
						<li>Trumbowyg：默认编辑器，编辑区域更大</li>\
						<li>WangEditor：可对照源码编辑</li>\
					</ul>\
				</li>\
				</ul>';

	panel(html, false, {
			title: '帮助',
			width: 600,
			height: 400,
		});
}













function saveArticle(content) {
	if (R('[name=title]').value=='') {
		panel('<br><br><br>文章标题不得为空!', 1500);
	} else {
		R.post('', R.FD('form[name=editForm]').app({content: content}).fd, function(data){
			if (data.status == 200) {
				R('[name=articleid]').value = data.articleid;
			}
			panel('<br><br><br>' + data.msg, 1500);
		},'JSON');
	}
}




function previewArticle() {
	var id = R('[name=articleid]').value;
	if (id.length<10) {
		panel('<br><br><br>请保存文章后，再浏览！', 1500);
	} else {
		window.open('../../article/'+id);
	}
}


function addArticle() {
	window.location.href = './new' + window.location.search;
}

function switchEditor(query) {
	window.location.href = './' + R('[name=articleid]').value + query;
}


/* 在光标处插入html */
function insertHtmlAtCaret(html) {
	var sel, range;
	if (window.getSelection) {
		document.execCommand('insertHtml', false, html);
		return;

		// 以下为原版插入，但在trumbowyg中有点问题。
		// IE9 and non-IE
		sel = window.getSelection();
		if (sel.getRangeAt && sel.rangeCount) {
			range = sel.getRangeAt(0);
			range.deleteContents();
			// Range.createContextualFragment() would be useful here but is
			// non-standard and not supported in all browsers (IE9, for one)
			var el = document.createElement("div");
			el.innerHTML = html;
			var frag = document.createDocumentFragment(), node, lastNode;
			while ((node = el.firstChild)) {
				lastNode = frag.appendChild(node);
			} 
			range.insertNode(frag);
			// Preserve the selection
			if (lastNode) {
				range = range.cloneRange();
				range.setStartAfter(lastNode);
				range.collapse(true);
				sel.removeAllRanges();
				sel.addRange(range);
			}
		}
	} else if (document.selection && document.selection.type != "Control") {
		// IE < 9
		document.selection.createRange().pasteHTML(html);
	}
}

/* 获得选择区域的html */
function getHtmlAtCaret(range) {
	var selectionObj = null,
		rangeObj	 = null,
		selectedText = "",		//选择区域的Text
		selectedHtml = "";
	if(window.getSelection){
		if (range) {
			rangeObj = range;
		} else {
			selectionObj = window.getSelection();
			selectedText = selectionObj.toString();
			rangeObj = selectionObj.getRangeAt(0);
		}
		var docFragment = rangeObj.cloneContents();
		var tempDiv = document.createElement("div");
		tempDiv.appendChild(docFragment);
		selectedHtml = tempDiv.innerHTML;
	
	}else if(document.selection){
		selectionObj = document.selection;
		rangeObj = selectionObj.createRange();
		selectedText = rangeObj.text;
		selectedHtml = rangeObj.htmlText;
	}
	return selectedHtml;
}







