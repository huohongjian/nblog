

$('#editor').trumbowyg({
	lang: 'zh_cn',
	svgPath: '../../libs/Trumbowyg/dist/ui/icons.svg',
	// autogrow: true,
	semantic: false,
	btns: [
		['viewHTML'],
		['formatting'],
		['strong', 'em', 'del'],
		['superscript', 'subscript'],
		['link'],
		['insertImage'],
		['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
		['unorderedList', 'orderedList'],
		['horizontalRule']
	],
});



function save() {
	var html = $('#editor').trumbowyg('html');
	saveArticle(html);  // The function is in the file named "js/edit.js"
	R.id('editor').focus();
}



function wrap(tag, className, isGetText) {
	var select;
	$('#editor').trumbowyg('saveRange');

	if (isGetText == true) {
		select = $('#editor').trumbowyg('getRangeText');
	} else {
		var range = $('#editor').trumbowyg('getRange');
		select = getHtmlAtCaret(range);
	}
	insertHtmlAtCaret('<'+tag+' class="'+className+'">'+select+'</'+tag+'>');
	R.id('editor').focus();
}


function setUserInput() {
	$('#editor').trumbowyg('saveRange');
	var select = $('#editor').trumbowyg('getRangeText');
	insertHtmlAtCaret('<strong class="userinput"><code>'+select+'</code></strong>');
	R.id('editor').focus();
}


function clearHTML() {
	$('#editor').trumbowyg('saveRange');
	var select = $('#editor').trumbowyg('getRangeText');
	insertHtmlAtCaret(select + '<br>');
	R.id('editor').focus();
}
function pToBr() {
	$('#editor').trumbowyg('saveRange');
	var range = $('#editor').trumbowyg('getRange');
		select = getHtmlAtCaret(range);
	insertHtmlAtCaret(select.replace(/<p>|<\/p>/gi, '<br>'));
	R.id('editor').focus();
}
function margeBr() {
	$('#editor').trumbowyg('saveRange');
	var range = $('#editor').trumbowyg('getRange');
		select = getHtmlAtCaret(range);
	insertHtmlAtCaret(select.replace(/(<br>)+/gi, '<br>'));
	R.id('editor').focus();
}
function preToProgramlisting() {
	$('#editor').trumbowyg('saveRange');
	var range = $('#editor').trumbowyg('getRange');
		select = getHtmlAtCaret(range);
	insertHtmlAtCaret(select.replace(/<pre>/gi, '<pre class="programlisting">'));
	R.id('editor').focus();
}
function preToScreen() {
	$('#editor').trumbowyg('saveRange');
	var range = $('#editor').trumbowyg('getRange');
		select = getHtmlAtCaret(range);
	insertHtmlAtCaret(select.replace(/<pre>/gi, '<pre class="screen">'));
	R.id('editor').focus();
}



function insertCallout(i) {
	var img = '<span><img src="'+R.id('baseURL').value+'/imagelib/callouts/'+i+'.png" alt="'+i+'" border="0"></span>'
	insertHtmlAtCaret(img);
	R.id('editor').focus();
}



var __onresize = true;
window.onresize = function(){
	if (__onresize)	{
		__onresize = false;
		setTimeout(function(){
			var h= R.id('editor-body').offsetHeight - 38;
			R('#editor').style.height = h + 'px';
			__onresize = true;
		}, 100);
	}
}