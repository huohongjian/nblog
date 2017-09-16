

R.enterMove();



function displayMessage(msg, title) {
	title = title || '错误信息';
	var cls = title=='错误信息' ? 'red' : 'green';
	R('.message').innerHTML = msg ? '\
		<label class="' + cls + '">' + title + '：</label>\
		<span class="' + cls + '">' + msg + '</span>' : '';
}


function checkLength(value, error) {
	var l = value.length;
	if (l<3 || l>16) {
		displayMessage(error);
		return false;
	} else {
		displayMessage();
		return true;
	}
}

function checkContent(value, error) {
	if (!R.isDC(value)) {
		displayMessage(error)
		return false;
	} else {
		displayMessage();
		return true;
	}
}


function authLogin() {
	var value = R('[name="login"]').value;
	if (!checkLength(value, '登录名请输入3-16位字符!')) {
		return false;
	} else if (!checkContent(value, '登录名只能输入英文、数字、下划线!')) {
		return false;
	} else {
		return true;
	}
}

function authPassword() {
	var value = R('[name="password"]').value;
	if (!checkLength(value, '登录密码请输入3-16位字符!')) {
		return false;
	} else if (!checkContent(value, '登录密码只能输入英文、数字、下划线!')) {
		return false;
	} else {
		return true;
	}
}

function authPassword2() {
	if (R('[name="password2"]').value != R('[name="password"]').value) {
		displayMessage('两次密码输入不一致，请重新输入!');
		return false;
	} else {
		displayMessage();
		return true;
	}
}

R('[name="login"]').addEventListener('change', authLogin, false);
R('[name="password"]').addEventListener('change', authPassword, false);
R('[name="password2"]').addEventListener('change', authPassword2, false);



function hasSameUser() {
	var e = R('[name="login"]');
	var v = e.value;
	var baseURL = R.baseURL('regist');
	R.get(baseURL+'hasSameUser/'+v, function(data) {
		if (data=='1') {
			displayMessage('有同名用户，请更改登录名!')
		} else {
			displayMessage();
		}
	});
}


function rsa() {
	if (!authLogin()) {
		return false;
	} else if (!authPassword()) {
		return false;
	} else if (!authPassword2()) {
		return false;
	} else if (R('[name="name"]').value=='') {
		displayMessage('用户名不得为空!')
		return false;
	} else {
		R('[name="password2"]').value = '';
		var pw = R('[name="password"]');
		setMaxDigits(131); //131 => n的十六进制位数/2+3
		var key  = new RSAKeyPair("10001", '', PUBLIC_KEY); //10001 => e的十六进制
		pw.value = encryptedString(key, md5(pw.value));//+'\x01'); //不支持汉字
		document.regist.submit();
	}


	
}