

// 两端对齐
// R.ALL('[class=justify]').each(function(o){
// 	o.style.letterSpacing = "-.14em"; 
// 	o.innerHTML = o.innerHTML.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '').split("").join(" ").replace(/\s{3}/g, " &nbsp; ");
// });


R.ONE('header input[name=search]').enter(function(e){
	window.location.href = '/article/search/' + e.target.value;
});
R.one('header input[name=search]').focus();


