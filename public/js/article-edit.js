



// /user\/edit(.*)/.test(window.location.href);
// var articleid = RegExp.$1;

// document.write(window.location.href)
// document.write(articleid);

var articleid = R('[name=articleid]').value;

function save() {


	R.post("../save", R.fd('#editForm',{
//		image: R.id('thumb').src.match(/\/images.*/),
		content: editor.html(),
		articleid: articleid,
	}), function(r){
		if (r.status == 200) {
//			oID.value = r._id;
			articleid = r.articleid;
			alert(articleid);
			
		}
//		message(r.msg, 1000);
	}, 'JSON');


}

