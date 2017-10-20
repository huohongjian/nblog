

function saveArticle(content) {
	R.post('', R.FD('form[name=editForm]').app({content: content}).fd, function(data){
		if (data.status == 200) {
			R('[name=articleid]').value = data.articleid;
		}
		panelMsg(data.msg);
	},'JSON');
}




function previewArticle() {
	var id = R('[name=articleid]').value;
	if (id.length<10) {
		panelMsg('请保存文章后，再浏览！');
	} else {
		window.open('../../article/'+id);
	}
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







