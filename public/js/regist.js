

R.move('[name=registForm] input', '[name=password]');
R.focus('[name=login]');
R.enter('[name=captcha]', doRegist);
R('[name=login]').addEventListener('change', function(){
	if (authLogin()) {
		R.post('./checkLoginName', R.fd({login: R('[name=login]').value}), function(data) {
			displayMessage(data);
		});
	}
}, false);



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
	var value = R('[name=login]').value;
	if (!checkLength(value, '登录名请输入3-16位字符!')) {
		return false;
	} else if (!checkContent(value, '登录名只能输入英文、数字、下划线!')) {
		return false;
	} else {
		return true;
	}
}

function authPwd() {
	var value = R.id('pwd').value;
	if (!checkLength(value, '登录密码请输入3-16位字符!')) {
		return false;
	} else if (!checkContent(value, '登录密码只能输入英文、数字、下划线!')) {
		return false;
	} else {
		return true;
	}
}

function authPwd2() {
	if (R.id('pwd2').value != R.id('pwd').value) {
		displayMessage('两次密码输入不一致，请重新输入!');
		return false;
	} else {
		displayMessage();
		return true;
	}
}






function doRegist() {
	if (!authLogin()) {
		return false;
	} else if (!authPwd()) {
		return false;
	} else if (!authPwd2()) {
		return false;
	} else {
		setMaxDigits(131); //131 => n的十六进制位数/2+3
		var key  = new RSAKeyPair("10001", '', PUBLIC_KEY); //10001 => e的十六进制
		R('[name=password]').value = encryptedString(key, md5(R.id('pwd').value));//+'\x01'); //不支持汉字
		document.registForm.submit();
	}


	
}