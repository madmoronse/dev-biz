function add_bc(product_id,super_ceni) {
	data = $('.button_options a.button,.options input[type=\'text\'],.options input[type=\'hidden\'],.options input[type=\'radio\']:checked,.options input[type=\'checkbox\']:checked,.options select,.options textarea');
	$.ajax({
	url: 'index.php?route=checkout/cart/add',
	type: 'post',
	data: data.serialize() + '&product_id=' + product_id,
	dataType: 'json',
	beforeSend: function(){
	},
	success: function(json) {
	$('.success, .warning, .attention, information, .error').remove();

	if (json['error']) {
		if (json['error']['warning']) {
			$('#notification').html('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
			$('.warning').fadeIn('slow');
		}
		if (json['error']) {
			if (json['error']['option']) {
				for (i in json['error']['option']) {
					$('.options-close-' + product_id).fadeIn();
					$('.popup-options-' + product_id).fadeIn();
					$('.modal-overlay-' + product_id).fadeIn();
					//$('#option-' + i).after('<span class="error">' + /*json['error']['option'][i]*/ 'Выберите размер!' +  '</span>');
					alert(json['error']['option'][i]);
					//$('#option-' + i).after('<div class="error">' + 'Выберите размер!' +  '</div>');
				}
			}
		}
	}
	if (json['success']) {
		if (json.gifts) {
			Gift.renderPopup(json.gifts);
			return false;
		}
		$('#notification').before('<div class="backg_notif"></div>')
						  .html(NeosCart.renderNotification(json['success'], super_ceni == 2));
		$('#productsincart').html(NeosCart.renderProducts(json['products']))

		$('.success').fadeIn('slow');
		$('#cart-total').html(json['total']);
		$('html, body').animate('none');
		$('.modal-overlay').fadeOut();
		$('.popup-options').fadeOut();
		// Close
		$('.close_success').on('click', function(){
			$('.close').trigger('click');
			$('.backg_notif').remove();
		});
		// Close
		$('.close').on('click', function(){
			$('.backg_notif').remove();
		});

	}
	}
	});
}

function add_bc_featslideblock(product_id) {
					data = $('#option2_'+product_id+' input[type=\'text\'], #option2_'+product_id+' input[type=\'radio\']:checked, #option2_'+product_id+' input[type=\'checkbox\']:checked, #option2_'+product_id+' select, #option2_'+product_id+' textarea');
					$.ajax({
							url: 'index.php?route=checkout/cart/add',
							type: 'post',
							data: data.serialize() + '&product_id=' + product_id,
							dataType: 'json',
							beforeSend: function(){
							},
								success: function(json) {
									$('.success, .warning, .attention, information, .error').remove();
									if (json['error']) {
										if (json['error']['warning']) {
											$('#notification').html('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

											$('.warning').fadeIn('slow');
										}

										for (i in json['error']) {
											$('#option2-' + i).after('<span class="error">' + json['error'][i] + '</span>');
										}
									}

									if (json['error']) {
										if (json['error']['option']) {
											for (i in json['error']['option']) {
												$('#option2-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
											}
										}
									}

									if (json['success']) {
										$('#notification').before('<div class="backg_notif"></div>').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

										$('.success').fadeIn('slow');

										$('#cart-total').html(json['total']);

										$('html, body').animate({ scrollTop: 0 }, 'slow');
									}
								}
						});
}
function add_bc_featured(product_id) {
					data = $('#option1_'+product_id+' input[type=\'text\'], #option1_'+product_id+' input[type=\'radio\']:checked, #option1_'+product_id+' input[type=\'checkbox\']:checked, #option1_'+product_id+' select, #option1_'+product_id+' textarea');
					$.ajax({
							url: 'index.php?route=checkout/cart/add',
							type: 'post',
							data: data.serialize() + '&product_id=' + product_id,
							dataType: 'json',
							beforeSend: function(){
							},
								success: function(json) {
									$('.success, .warning, .attention, information, .error').remove();
									if (json['error']) {
										if (json['error']['warning']) {
											$('#notification').html('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

											$('.warning').fadeIn('slow');
										}

										for (i in json['error']) {
											$('#option1-' + i).after('<span class="error">' + json['error'][i] + '</span>');
										}
									}

									if (json['error']) {
										if (json['error']['option']) {
											for (i in json['error']['option']) {
												$('#option1-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
											}
										}
									}

									if (json['success']) {
										$('#notification').before('<div class="backg_notif"></div>').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

										$('.success').fadeIn('slow');

										$('#cart-total').html(json['total']);

										$('html, body').animate({ scrollTop: 0 }, 'slow');
									}
								}
						});
}
function add_bc_bestseller(product_id) {
					data = $('#option3_'+product_id+' input[type=\'text\'], #option3_'+product_id+' input[type=\'radio\']:checked, #option3_'+product_id+' input[type=\'checkbox\']:checked, #option3_'+product_id+' select, #option3_'+product_id+' textarea');
					$.ajax({
							url: 'index.php?route=checkout/cart/add',
							type: 'post',
							data: data.serialize() + '&product_id=' + product_id,
							dataType: 'json',
							beforeSend: function(){
							},
								success: function(json) {
									$('.success, .warning, .attention, information, .error').remove();
									if (json['error']) {
										if (json['error']['warning']) {
											$('#notification').html('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

											$('.warning').fadeIn('slow');
										}

										for (i in json['error']) {
											$('#option3-' + i).after('<span class="error">' + json['error'][i] + '</span>');
										}
									}

									if (json['error']) {
										if (json['error']['option']) {
											for (i in json['error']['option']) {
												$('#option3-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
											}
										}
									}

									if (json['success']) {
										$('#notification').before('<div class="backg_notif"></div>').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

										$('.success').fadeIn('slow');

										$('#cart-total').html(json['total']);

										$('html, body').animate({ scrollTop: 0 }, 'slow');
									}
								}
						});
}
function add_bc_latest(product_id) {
					data = $('#option4_'+product_id+' input[type=\'text\'], #option4_'+product_id+' input[type=\'radio\']:checked, #option4_'+product_id+' input[type=\'checkbox\']:checked, #option4_'+product_id+' select, #option4_'+product_id+' textarea');
					$.ajax({
							url: 'index.php?route=checkout/cart/add',
							type: 'post',
							data: data.serialize() + '&product_id=' + product_id,
							dataType: 'json',
							beforeSend: function(){
							},
								success: function(json) {
									$('.success, .warning, .attention, information, .error').remove();
									if (json['error']) {
										if (json['error']['warning']) {
											$('#notification').html('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

											$('.warning').fadeIn('slow');
										}

										for (i in json['error']) {
											$('#option4-' + i).after('<span class="error">' + json['error'][i] + '</span>');
										}
									}

									if (json['error']) {
										if (json['error']['option']) {
											for (i in json['error']['option']) {
												$('#option4-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
											}
										}
									}

									if (json['success']) {
										$('#notification').before('<div class="backg_notif"></div>').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

										$('.success').fadeIn('slow');

										$('#cart-total').html(json['total']);

										$('html, body').animate({ scrollTop: 0 }, 'slow');
									}
								}
						});
}
function add_bc_special(product_id) {
					data = $('#option5_'+product_id+' input[type=\'text\'], #option5_'+product_id+' input[type=\'radio\']:checked, #option5_'+product_id+' input[type=\'checkbox\']:checked, #option5_'+product_id+' select, #option5_'+product_id+' textarea');
					$.ajax({
							url: 'index.php?route=checkout/cart/add',
							type: 'post',
							data: data.serialize() + '&product_id=' + product_id,
							dataType: 'json',
							beforeSend: function(){
							},
								success: function(json) {
									$('.success, .warning, .attention, information, .error').remove();
									if (json['error']) {
										if (json['error']['warning']) {
											$('#notification').html('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

											$('.warning').fadeIn('slow');
										}

										for (i in json['error']) {
											$('#option5-' + i).after('<span class="error">' + json['error'][i] + '</span>');
										}
									}

									if (json['error']) {
										if (json['error']['option']) {
											for (i in json['error']['option']) {
												$('#option5-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
											}
										}
									}

									if (json['success']) {
										$('#notification').before('<div class="backg_notif"></div>').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

										$('.success').fadeIn('slow');

										$('#cart-total').html(json['total']);

										$('html, body').animate({ scrollTop: 0 }, 'slow');
									}
								}
						});
}
