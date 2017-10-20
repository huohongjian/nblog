

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
}


function setUserInput() {
	$('#editor').trumbowyg('saveRange');
	var select = $('#editor').trumbowyg('getRangeText');
	insertHtmlAtCaret('<strong class="userinput"><code>'+select+'</code></strong>');
}


function clearHTML() {
	$('#editor').trumbowyg('saveRange');
	var select = $('#editor').trumbowyg('getRangeText');
	insertHtmlAtCaret(select + '<br>');
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