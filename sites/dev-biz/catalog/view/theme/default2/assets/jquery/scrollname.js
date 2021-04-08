function run_scroll() {
	var $link = $(this),
	    target = $link.width() - $link.parent().width();
	if (target > 0) {
		$link.stop().animate({
				'margin-left': -target
			},
			20 * target, 'linear'
		);
	}
}

function reset_scroll() {
	var $link = $(this);
	$link.stop().animate({
			'margin-left': 0
		},
		250
	);
}

function name_scroll() {

	$('.name a').hover(run_scroll,reset_scroll);
	$('.category-list ul li a').hover(run_scroll,reset_scroll);
	
}