



/user\/edit(.*)/.test(window.location.href);
var articleid = RegExp.$1;

document.write(window.location.href)
document.write(articleid);

function save() {


	R.post("save", R.fd('#editForm',{
//		image: R.id('thumb').src.match(/\/images.*/),
		content: editor.html(),
	}), function(r){

		alert(r);
		if (r.status == 200) {
//			oID.value = r._id;
//			R('[name=status]').value = r._st;
			
		}
//		message(r.msg, 1000);
	}, 'JSON');


}