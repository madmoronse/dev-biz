/*
 * Category Menu Pro v1.1
 * for OpenCart 1.5.5 - 1.5.6
 *
 * Copyright 2013, iDiY
 * Support: idiy.webmaster@gmail.com
 *
 */

$(document).ready(function() {
	
	var width = $('ul.popup-category').outerWidth();
	$('ul.popup-category li ul').css('left', width-2);
	$('ul.popup-category.column_right li ul').css('left', -width-2);

	$('li.active > a.toggle-btn, li.active > a.category-link').addClass('open');
	$('ul.collapsible-category li.active > ul, ul.accordion-category li.active > ul').slideToggle(200, "linear");
	$('ul.collapsible-category .toggle-link, ul.accordion-category .toggle-link').parent().find('> .category-link').removeAttr('href');
	$('ul.collapsible-category .toggle-btn, ul.accordion-category .toggle-btn').removeAttr('href');

	$('ul.collapsible-category a.category-link, ul.collapsible-category a.toggle-btn').click(function() {
		$(this).toggleClass('open').parent().find('> ul').slideToggle(200, "linear");
	});

	$('ul.accordion-category a.category-link, ul.accordion-category a.toggle-btn').click(function() {
		$(this).toggleClass('open').closest('li').find('> ul').slideToggle(200, "linear");
		$(this).closest('ul').find('.open').not(this).toggleClass('open').closest('li').find('> ul').slideToggle(200, "linear");
	});

	//$('.category-3319').find('> ul').show( "slow" );
	//$('.category-4').find('> ul').show( "slow" );
	//$('.category-1789').find('> ul').show( "slow" );
	//$('.category-2988').find('> ul').show( "slow" );
	//$('.category-2558').find('> ul').show( "slow" );
	//$('.category-2780').find('> ul').show( "slow" );
	
});