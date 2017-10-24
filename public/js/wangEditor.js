
var E = window.wangEditor;
var editor = new E('#editor-title', '#editor-content');

editor.customConfig.debug = true;
editor.customConfig.menus = ['head', 'bold', 'italic', 'underline', 'strikeThrough', 'foreColor', 'backColor', 'link', 'list', 'justify', 'quote', 'emoticon', 'image', 'table', 'video', 'code', 'undo', 'redo'];

editor.customConfig.uploadImgServer = '/upload/image';
editor.customConfig.onchange = function(html){setSource(editor.txt.html())};
editor.create();


editor.txt.html(R.id('editor-source').value);


!function(){
	var refresh = true;
	R.id('editor-source').addEventListener('keyup', function() {
		if (refresh) {
			var self = this;
			refresh = false;
			setTimeout(function() {
				editor.txt.html(self.value);
				refresh = true;
				self.focus();
			}, 1000);
		}
	}, false);
}();

function setSource(html) {
	var reg = /(<\/div>|<\/pre>|<\/p>|<\/h[1-6]>|<br>|<\/ul>|<\/li>)/gi;
	R.id('editor-source').value	= html.replace(reg, '$1\n');
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
function pToBr() {
	var range = editor.selection.getRange(),
		select = getHtmlAtCaret(range);
	editor.cmd.do('insertHTML', select.replace(/<p>|<\/p>/gi, '<br>'));
}
function margeBr() {
	var range = editor.selection.getRange(),
		select = getHtmlAtCaret(range);
	editor.cmd.do('insertHTML', select.replace(/(<br>)+/gi, '<br>'));
}
function preToProgramlisting() {
	var range = editor.selection.getRange(),
		select = getHtmlAtCaret(range);
	editor.cmd.do('insertHTML', select.replace(/<pre>/gi, '<pre class="programlisting">'));
}
function preToScreen() {
	var range = editor.selection.getRange(),
		select = getHtmlAtCaret(range);
	editor.cmd.do('insertHTML', select.replace(/<pre>/gi, '<pre class="screen">'));
}


function insertCallout(i) {
	var img = '<span><img src="'+R.id('baseURL').value+'/imagelib/callouts/'+i+'.png" alt="'+i+'" border="0"></span>'
	editor.cmd.do('insertHTML', img);
}

