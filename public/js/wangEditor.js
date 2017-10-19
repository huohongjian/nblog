
var E = window.wangEditor;
var editor = new E('#editor-title', '#editor-content');

editor.customConfig.debug = true;
editor.customConfig.menus = ['head', 'bold', 'italic', 'underline', 'strikeThrough', 'foreColor', 'backColor', 'link', 'list', 'justify', 'quote', 'emoticon', 'image', 'table', 'video', 'code', 'undo', 'redo'];

editor.customConfig.uploadImgServer = '/upload/image';
editor.customConfig.onchange = function(html){setSource(editor.txt.html())};
editor.create();


if(editor.txt.html()!='') {
	setSource(editor.txt.html());
}

!function(){
	var refresh = true;
	R.id('editor-source').addEventListener('keyup', function() {
		if (refresh) {
			var self = this;
			refresh = false;
			setTimeout(function() {
				editor.txt.html(self.value.replace(/(\n)+/g, ''));
				refresh = true;
				self.focus();
			}, 1000);
		}
	}, false);
}();

function setSource(html) {
	var reg = /(<\/div>|<\/pre>|<\/p>|<\/h[1-6]>|<br>)/gi,
		REG = /(<\/div>|<\pre>|<\/p>|<h[1-6])/gi;
	R.id('editor-source').value	= html.replace(reg, '$1\n').replace(REG, '\n$1');
}




function save() {
	var html = editor.txt.html();
	alert(html);
}

function preview() {
	var dom = editor.selection.getSelectionStartElem()[0];
	console.log(dom);
	var range = editor.selection.getRange();
	console.log(range);


	alert(getSelectionHtml(range));

}


function wrap(tag, className, isGetText) {
	var select = isGetText == true ?
				 editor.selection.getSelectionText() :
				 getSelectionHtml();
	editor.cmd.do('insertHTML', '<'+tag+' class="'+className+'">'+select+'</'+tag+'>');
}


function setUserInput() {
	var select = editor.selection.getSelectionText();
	editor.cmd.do('insertHTML', '<strong class="userinput"><code>'+select+'</code></strong>');
}


function clearHTML() {
	var select = editor.selection.getSelectionText();
	editor.cmd.do('insertHTML', select);
}








function getSelectionHtml(range) {
	var selection = null;
	if (!range) {
		selection = window.getSelection();
		if (selection.rangeCount === 0) {
			return;
		} else {
			range = selection.getRangeAt(0);
		}
	}
	var selectedText = range.toString();
	var docFragment  = range.cloneContents();
	var tempDiv = document.createElement("div");
	tempDiv.appendChild(docFragment);
	return tempDiv.innerHTML;
}