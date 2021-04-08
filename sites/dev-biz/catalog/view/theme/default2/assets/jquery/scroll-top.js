$(window).scroll(function(){
		if ($(window).scrollTop() > 0){
				$("#top").addClass("resize");
		} else {
				$("#top").removeClass("resize");
		}
});