
var oStatus = R('select[name="status"]');
var oCategory = R('select[name="category"]');
R.ONE(oStatus).setSelect(oStatus.dataset.val);
R.ONE(oCategory).setSelect(oCategory.dataset.val);


KindEditor.lang({
	bsdh1:  'BSD标题',
	bsdbox: 'BSD提示框',
	bsdfmt: 'BSD特殊格式',
	bsdclear: '清除当前样式',
	bsdhelp:'BSD样式使用帮助', 
});

KindEditor.plugin('bsdclear', function(K) {
	var self = this, name = 'bsdclear';
	self.clickToolbar(name, function() {
		self.insertHtml(self.selectedHtml().replace(/<(?:.|\s)*?>/g,""));
	});
});

KindEditor.plugin('bsdhelp', function(K) {
	var self = this, name = 'bsdhelp';
	self.clickToolbar(name, function() {

		alert('建设中');
	});
});

KindEditor.plugin('bsdh1', function(K) {
	var self = this, name = 'bsdh1';
	function click(value) {

		
		var body = self.edit.doc.body,
			scrollTop = body.scrollTop;


		self.exec('formatblock', '<' + value + '>').hideMenu();

		var range = self.cmd.range;
		range.startContainer.className = 'title';
		body.scrollTop = scrollTop;

		return;


		var cmd = self.cmd;
//		self.select().exec('formatblock', '<h2>').hideMenu();
		cmd.wrap(value).select();
		cmd.select();
		self.hideMenu();
	}
	self.clickToolbar(name, function() {
		var menu = self.createMenu({
			name : name,
			width : 150
		});
		menu.addItem({
			title : '章节主标题',
			click : function() {click('h2');}
		});
		menu.addItem({
			title : '章节二级标题',
			click : function() {click('h3');}
		});
		menu.addItem({
			title : '章节三级标题',
			click : function() {click('h4');}
		});		
		menu.addItem({
			title : '章节四级标题',
			click : function() {click('h5');}
		});
		menu.addItem({
			title : '章节五级标题',
			click : function() {click('h6');}
		});
		menu.addItem({
			title : '正文',
			click : function() {
				self.select().exec('formatblock', '<p>').hideMenu();
				self.cmd.range.startContainer.removeAttribute('class');
			}
		});

	});
});


KindEditor.plugin('bsdbox', function(K) {
	var self = this, name = 'bsdbox';
	function click(value) {
		var cmd = self.cmd;
		cmd.wrap(value);
		cmd.select();
		self.hideMenu();

		/*去掉框内首空行*/
		var c = self.cmd.range.startContainer;
		c.innerHTML = c.innerHTML.replace(/<p><br.*><\/p>/i, '');
	}
	self.clickToolbar(name, function() {
		var menu = self.createMenu({
			name : name,
			width : 150
		});
		menu.addItem({
			title : '框内标题',
			click : function() {
				click('<h3 class="admontitle"></h3>');

			// 	return;

			// 	var _ua = navigator.userAgent.toLowerCase(),
			// 		_IE = _ua.indexOf('like gecko') > -1 && _ua.indexOf('opera') == -1;
				
			// 	if (_IE) {	/* IE 11.0 tested. */
			// 		self.insertHtml('<h3 class="admontitle">'+self.selectedHtml().replace(/<\/?p>/gi,'') +'</h3>').hideMenu();
			// 	} else {
			// 		self.exec('formatblock', '<h3>').hideMenu();
			// 		self.cmd.range.startContainer.className = 'admontitle';
			// 	}
			}
		});
		menu.addItem({
			title : '重要提示框',
			click : function() {click('<div class="important"></div>');}
		});
		menu.addItem({
			title : '注意提示框',
			click : function() {click('<div class="note"></div>');}
		});
		menu.addItem({
			title : '警告提示框',
			click : function() {click('<div class="warning"></div>');}
		});
		menu.addItem({
			title : '示例提示框',
			click : function() {click('<div class="example"></div>');}
		});
		menu.addItem({
			title : '技巧提示框',
			click : function() {click('<div class="tip"></div>');}
		});
		menu.addItem({
			title : '小心提示框',
			click : function() {click('<div class="caution"></div>');}
		});
		menu.addItem({
			title : '命令输出框',
			click : function() {click('<div class="screen"></div>');}
		});
		menu.addItem({
			title : '命令行格式',
			click : function() {
				// self.insertHtml('<b>'+self.selectedHtml() +'</b>').hideMenu();
				// return;

				click('<b></b>');}
		});

	});
});


KindEditor.plugin('bsdfmt', function(K) {
	var self = this, name = 'bsdfmt';
	function click(value) {
		var cmd = self.cmd;
		cmd.wrap(value).select();
		cmd.select();
		self.hideMenu();
	}
	self.clickToolbar(name, function() {
		var menu = self.createMenu({
			name : name,
			width : 150
		});
		menu.addItem({
			title : '作者',
			click : function() {click('<span class="authorgroup"></span>');}
		});
		menu.addItem({
			title : '软件包',
			click : function() {click('<span class="package"></span>');}
		});
		menu.addItem({
			title : '文件名',
			click : function() {click('<code class="filename"></code>');}
		});		

	});
});





var editor;
KindEditor.ready(function(K) {
	editor = K.create('textarea[name=content]', {
		cssPath : 		['../../own/R.css',
						 '../../css/nblog.css',
						 '../../css/docbook.css'
						],
		uploadJson : 		'../../js/kindeditor/php/upload_json.php',
		fileManagerJson : 	'../../js/kindeditor/php/file_manager_json.php',
		allowFileManager : true,

		filterMode : false,
		cssData: 'html {background:#FBFBFB; height:100%;}\
				  body{ background: #fff;\
					border:1px solid #CCCCCC;\
					height:auto; min-height:80%;\
					margin:20px 4%;\
					padding:20px 6%;\
				}',
		items : ['source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste',
		'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
		'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
		'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
		'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
		'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage',
		'flash', 'media', 'insertfile', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
		'anchor', 'link', 'unlink', '|', 'about','/',
		'bsdh1', 'bsdbox', 'bsdfmt', 'bsdclear', 'bsdhelp'],
	});

	K('#thumbimg').dblclick(setThumbImg);
	K('input[name=save]').click(save);
});

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
		src = '/images/noimage.jpg';
	}
	R.id('thumbimg').src = src;
}
if (R('input[name="thumb"]').value=='') {
	setImage();
}



function panelHelp() {
	if (window._panelHelp && window._panelHelp.panel) {
		window._panelHelp.show();
	} else {
		window._panelHelp = new Panel({
			title: '帮助',
			width: 500,
			height: 300,
			html: '<ul class="text-left">\
				<li>列表显示文章时，别名优先标题显示。</li>\
				<li>单击`选择`微缩图，可上传也可选择微缩图。</li>\
				<li>双击微缩图，可在微缩图库中随机更换。</li>\
				<li>电子书格式，正在建设中...</li>\
			',
		});
	}
}


function preview() {
	var id = R('[name=articleid]').value;
	if (id.length<10) {
		panelMsg('请保存文章后，再浏览！');
	} else {
		window.location.href = '../../article/'+id;
	}
}




function save() {
	R.post('', R.FD('form[name=editForm]').app({content: editor.html()}).fd, function(data){
		if (data.status == 200) {
			R('[name=articleid]').value = data.articleid;
		}
		panelMsg(data.msg);
	},'JSON');
}

R.ALL('form[name=editForm] input').move();
R('input[name="title"]').focus();
