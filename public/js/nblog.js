

// 两端对齐
// R.ALL('[class=justify]').each(function(o){
// 	o.style.letterSpacing = "-.14em"; 
// 	o.innerHTML = o.innerHTML.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '').split("").join(" ").replace(/\s{3}/g, " &nbsp; ");
// });


// if (R.one('header.a input[name=search]')) {
// 	R.ONE('header.a input[name=search]').enter(function(e){
// 		window.location.href = '/article/search/' + e.target.value;
// 	});
// //	R.one('header.a input[name=search]').focus();
// }



(function(){
	var o=R.id('gotop');
	if(o){
		var scr = R.SCR(o.dataset.query);

		o.onclick = function(){
			var a = scr.get();
			var int = setInterval(function(){
				a = a>200 ? a-200 : 0;
				if(a==0) clearInterval(int);
				scr.set(a);
			},20);
		};

		scr.ele().onscroll = function() {
			if(scr.get() > 100){
				o.style.opacity = 0.4;
			}else{
				o.style.opacity = 0;
			}
		};
	}
})();


