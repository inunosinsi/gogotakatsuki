var p = location.search;
if(location.search.length){
	p = p.replace("?", "");
	if(p.indexOf("&")){
		var pp = p.split("&");
	}else{
		var pp = [p];
	}
	for(var i = 0; i < pp.length; i++){
		var ppp = pp[i].split("=");
		params[ppp[0]] = ppp[1];
	}

	//値の確認
	if(typeof(params.k) == "string"){
		var gardens = ["aoitori", "heian", "hiyoshi", "hutaba", "imamura", "maria", "nobiteyuku", "takatsuki"];
		if(gardens.indexOf(params.k) < 0) params.k = null;
	}
}
