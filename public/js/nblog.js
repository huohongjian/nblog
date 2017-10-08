

// 两端对齐
// R.ALL('[class=justify]').each(function(o){
// 	o.style.letterSpacing = "-.14em"; 
// 	o.innerHTML = o.innerHTML.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '').split("").join(" ").replace(/\s{3}/g, " &nbsp; ");
// });


if (R.one('header.a input[name=search]')) {
	R.ONE('header.a input[name=search]').enter(function(e){
		window.location.href = '/article/search/' + e.target.value;
	});
//	R.one('header.a input[name=search]').focus();
}



(function(){
	var o=R.id('gotop');
	if(o){
		o.onclick = function(){
			var a = R.getScrollTop();
			var int = setInterval(function(){
				a = a>200 ? a-200 : 0;
				if(a==0) clearInterval(int);
				R.setScrollTop(a);
			},20);
		};

		window.onscroll = function() {
			if(R.getScrollTop() > 100){
				o.style.opacity = 0.4;
			}else{
				o.style.opacity = 0;
			}
		};
	}
})();


