


$('#editor').trumbowyg({
	lang: 'zh_cn',
	svgPath: '/libs/Trumbowyg/dist/ui/icons.svg',
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
		['horizontalRule'],
		['removeformat'],
		['fullscreen']
	],
});






function save() {
	var html = $('#editor').trumbowyg('html');
	alert(html);
}

function preview() {


//	document.execCommand('insertHTML', false, 'hhj');



}



function wrap(tag, className, isGetText) {
	$('#editor').trumbowyg('saveRange');
	var range = $('#editor').trumbowyg('getRange');
	var select = isGetText == true ?
				 $('#editor').trumbowyg('getRangeText') :
				 getSelectionHtml(range);
	console.log(select)
	console.log(document)
	var tR=document.selection.createRange(); // 获取该焦点的对象
tR.pasteHTML(select);

//	document.execCommand('insertHTML', false, '<'+tag+' class="'+className+'">'+select+'</'+tag+'>');
}


function setUserInput() {
	$('#editor').trumbowyg('saveRange');
	var select = $('#editor').trumbowyg('getRangeText');
	document.execCommand('insertHTML', false, '<strong class="userinput"><code>'+select+'</code></strong>');
}


function clearHTML() {
	$('#editor').trumbowyg('saveRange');
	var select = $('#editor').trumbowyg('getRangeText');
	document.execCommand('insertHTML', false, select);
}

function getSelectionHtml(range) {
	if (!range) {
		var selection = window.getSelection();
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