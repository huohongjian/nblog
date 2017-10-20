
var E = window.wangEditor;
var editor = new E('#editor-title', '#editor-content');

editor.customConfig.debug = true;
editor.customConfig.menus = ['head', 'bold', 'italic', 'underline', 'strikeThrough', 'foreColor', 'backColor', 'link', 'list', 'justify', 'quote', 'emoticon', 'image', 'table', 'video', 'code', 'undo', 'redo'];

editor.customConfig.uploadImgServer = '/upload/image';
editor.customConfig.onchange = function(html){setSource(editor.txt.html())};
editor.create();


editor.txt.html(R.id('editor-source').value.replace(/(\n)+/g, ''));

//if(editor.txt.html()!='') {
//	setSource(editor.txt.html());
//}

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
	var reg = /(<\/div>|<\/pre>|<\/p>)/gi,
		REG = /(<\/h[1-6]>|<br>)/gi;
	R.id('editor-source').value	= html.replace(reg, '$1\n\n').replace(REG, '$1\n');
}




function save() {
	var html = editor.txt.html();
	saveArticle(html);
}


function wrap(tag, className, isGetText) {
	var select;
	if (isGetText == true) {
		select = editor.selection.getSelectionText();
	} else {
		var range = editor.selection.getRange();
		select = getHtmlAtCaret(range);
	}
	editor.cmd.do('insertHTML', '<'+tag+' class="'+className+'">'+select+'</'+tag+'>');
}


function setUserInput() {
	var select = editor.selection.getSelectionText();
	editor.cmd.do('insertHTML', '<strong class="userinput"><code>'+select+'</code></strong>');
}


function clearHTML() {
	var select = editor.selection.getSelectionText();
	editor.cmd.do('insertHTML', select + '<br>');
}


