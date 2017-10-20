


$('#editor').trumbowyg({
	lang: 'zh_cn',
	svgPath: '../../libs/Trumbowyg/dist/ui/icons.svg',
	// autogrow: true,
	// semantic: false
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
	$('#editor').trumbowyg('saveRange');
	var range = $('#editor').trumbowyg('getRange');
	var select = isGetText == true ?
				 $('#editor').trumbowyg('getRangeText') :
				 getHtmlAtCaret(range);
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
	insertHtmlAtCaret(select);
}

