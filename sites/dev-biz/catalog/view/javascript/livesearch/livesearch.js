$(function(){
	var i = (!!$("#livesearch").length ? $("#livesearch") : $("<ul id='livesearch'></ul>") ), s = $("#header [name=search]");
	function repositionLivesearch() { i.css({ top: (s.offset().top+s.outerHeight()), left:s.offset().left, width: s.outerWidth() }); }
	$(window).resize(function(){ repositionLivesearch(); });
	s.keyup(function(e){
		switch (e.keyCode) {
			case 13:
				$(".active", i).length && (window.location = $(".active a", i).attr("href"));
				return false;
			break;
			case 40:
				($(".active", i).length ? $(".active", i).removeClass("active").next().addClass("active") : $("li:first", i).addClass("active"))
				return false;
			break;
			case 38:
				($(".active", i).length ? $(".active", i).removeClass("active").prev().addClass("active") : $("li:last", i).addClass("active"))
				return false;
			break;
			default:
				var query = s.val();
				if (query.length > 2) {
					$.getJSON(
            "index.php?route=product/search/livesearch&search=" + query,
						function(data) {
							i.empty();
							$.each(data, function( k, v ) { i.append("<li><a href='"+v.href+"' "+v.class+"><img src='"+v.img+"' alt='"+v.name+"'><span>"+v.name+(v.model ? "<small>"+v.model+"</small>" : '')+"</span><em>"+(v.price ? v.price : '')+"</em></a></li>") });
							i.remove(); $("body").prepend(i); repositionLivesearch();
						}
					);
				} else {
					i.empty();
				}
		}
	}).blur(function(){ setTimeout(function(){ i.hide() },500); }).focus(function(){ repositionLivesearch(); i.show(); });
});