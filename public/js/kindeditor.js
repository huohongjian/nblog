var editor;
KindEditor.ready(function(K) {
	editor = K.create('#editor', {
		cssPath : 		['../../own/R.css', '../../css/nblog.css', '../../css/docbook.css'],
		uploadJson : 	'../../js/kindeditor/php/upload_json.php',
		fileManagerJson:'../../js/kindeditor/php/file_manager_json.php',
		allowFileManager: true,

		filterMode : false,
		cssData: 'html {background:#FBFBFB; height:100%;}\
				  body{ background: #fff;\
					border:1px solid #CCCCCC;\
					height:auto; min-height:80%;\
					margin:20px 4%;\
					padding:20px 6%;\
					display:block;\
				}\
				body{font: 16px/1.4 "sans serif",tahoma,verdana,helvetica;}\
				p{margin:16px 0;}\
				a:link {color:#0645AD; text-decoration:underline;}\
				a:visited,a:hover,a:active{color:#663366; text-decoration:underline;\
				}',
	});
});





function save() {
	saveArticle(editor.html());  // The function is in the file named "js/edit.js"
}

function wrap(tag, className, isGetText) {
	var select = editor.selectedHtml();
	if (isGetText == true) {
		select = select.replace(/<(?:.|\s)*?>/g, '');
	}
	editor.insertHtml('<'+tag+' class="'+className+'">'+select+'</'+tag+'>');
}


function setUserInput() {
	var select = editor.selectedHtml().replace(/<(?:.|\s)*?>/g, '');
	editor.insertHtml('<strong class="userinput"><code>'+select+'</code></strong>');
}


function clearHTML() {
	var select = editor.selectedHtml().replace(/<(?:.|\s)*?>/g, '');
	editor.insertHtml(select + '<br>');
}





function loadImgPlugin() {
	editor.loadPlugin('image', function() {
		editor.plugin.imageDialog({
			imageUrl : R('input[name=thumb]').value,
			clickFn : function(url, title, width, height, border, align) {
				editor.hideDialog();
				R('input[name=thumb]').value = url;
				R('#thumbimg').setAttribute('src', url);
			}
		});
	});
}


var __onresize = true;
window.onresize = function(){
	if (__onresize)	{
		__onresize = false;
		setTimeout(function(){
			var h= R.id('editor-body').offsetHeight - 2;
			R('.ke-container').style.height = h + 'px';
			__onresize = true;
		}, 100);
	}
}