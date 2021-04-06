<div class="wrapper">
	<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if (count($shipping_methods) > 0) { ?>
	<table cellspacing="0" cellpadding="0" class="delivery-table">
		<tr>
			<th><?php echo $text_prepayment; ?></th>
			<th><?php echo $text_delivery; ?></th>
			<th><?php echo $text_dcost; ?></th>
			<th><?php echo $text_payment; ?></th>
			<th><?php echo $text_choose; ?></th>
		</tr>
		<?php foreach ($shipping_methods as $key => $shipping_method) { ?>
			<?php
			switch ($shipping_method['delivery']) {
				case 'Почта России':
					$delivery = '<img src="/catalog/view/theme/default2/image/rm.png" alt="" />';
					break;
				case 'СДЭК':
					$delivery = '<img src="/catalog/view/theme/default2/image/cdek.png" alt="" />';
					if ($shipping_method['payment'] != 'Предоплата 100%') {
						$shipping_method['payment'] .= '<a href="/usloviya-nalozhennogo-platezha-sdek/" target="_blank" style="font-size:10px;display:block;margin-top:-10px;">Условия наложенного платежа СДЭК</a>';
					}
					break;
				default:
					$delivery = '';
					break;
			}
			$dcost = (is_numeric($shipping_method['dcost'])) ? $shipping_method['dcost'].'ք' : $shipping_method['dcost'];
			?>
				<td><?php echo $shipping_method['payment'] ?></td>
				<td><?php echo $delivery ?></td>
				<td><?php echo $dcost ?></td>
				<td><?php echo $shipping_method['place'] ?></td>
				<td><input type="radio" name="shipping_method" value="<?php echo $shipping_method['code'] ?>" data-options="<?php echo $shipping_method['options'] ?>" <?php if ($code === $shipping_method['code']) echo 'checked' ?>/></td>
			</tr>
		<?php } ?>
	</table>
	<?php } ?>
</div>
<table id="checkout-table-info">
  <tr>
    <th colspan="2"><b>Заказ:</b></th>
  </tr>
  <tr>
    <td>Стоимость товаров:</td>
    <td><?php echo $cost_val; ?></td>
  </tr>
  <tr>
    <td>Доставка:</td>
    <td></td>
  </tr>
  <tr>
    <td>Итого к оплате:</td>
    <td></td>
  </tr>
</table>

<div id="wrapper-cdek" class="wrapper-cdek" style="display: none">
			<p><strong>Выбор вариантов доставки:</strong></p>
			<p>
				<select name="shipping_method">
					<option value="" disabled selected>Выберите подходящий вариант</option>
				</select>
			</p>
			<?php if ($show_has_dressing_room) { ?>
				<input type="checkbox" name="has_dressing_room" id="has_dressing_room">
				<label for="has_dressing_room">Возможность примерки перед покупкой<span id="markup_dressingroom"></span></label>
			<?php } ?>
			<div class="wrapper-cdek__warehouses" style="display: none">
				<select name="warehouse">
					<option value="" disabled selected>Выберите пункт выдачи</option>   
				</select>
			</div>
</div>
<script>
(function () {
	// Cdek
	var cdekWrapper = $('#wrapper-cdek');
	var cdekDeliverySelect = cdekWrapper.find('select[name="shipping_method"]');
	var cdekDeliverySelectHtml = cdekDeliverySelect.html();
	var warehouseSelectWrapper = cdekWrapper.find('.wrapper-cdek__warehouses');
	var warehouseSelect = warehouseSelectWrapper.find('select');
	var cdekWarehouseSelectHtml = warehouseSelect.html()
	var hasDressingRoomCheckbox = $('#has_dressing_room');
	// Delivery choose
	var deliveryRadioBox = $('input[name="shipping_method"]');
	var deliveryBoxStateChangeTimeout;
	// Checkout step buttons
	var prevStepButton = $('#prev-step');
	var nextStepButton = $('#next-step');
	// Checkout delivery table
	var checkoutTable = $("#checkout-table-info");
	
	/**
	 * Enable/disable delivery radio box
	 * @param {Boolean} value
	 */
	var deliveryChooseEnabled = function(value) {
		clearTimeout(deliveryBoxStateChangeTimeout);
		deliveryRadioBox.prop('disabled', !value);
	}

	/**
	 * Enable/disable next step button
	 * @param {Boolean} value
	 */
	var nextStepEnabled = function(value) {
		nextStepButton.prop('disabled', !value);
	}

	/**
	 * Get cdek warehouses
	 * @param {Function} callback
	 */
	var cdekWarehouses = function(callback) {
		var hasDressingRoom = $('#has_dressing_room').prop('checked');
		$.ajax({
			url: '/index.php?route=checkout/shipping_method/cdekWarehouses',
			dataType: 'JSON',
			data: {
				has_dressing_room: hasDressingRoom
			}
		})
			.done(function(data) {
				var count = data.length;
				if (count > 0) {
					var appendWarehouses = data.reduce(function(carry, item) {
						return carry + '<option value="' + item.code + '">' + item.address + '; ' + item.work_time + '</option>';
					}, '');
					warehouseSelect.html(cdekWarehouseSelectHtml + appendWarehouses);
				} else {
					warehouseSelectWrapper.hide();
				}
				if (typeof callback === 'function') {
					callback(count);
				}
			})
			.fail(function() {
				warehouseSelectWrapper.hide();
				if (typeof callback === 'function') {
					callback(0);
				}
			});
	};

	/**
	 * Show cdek wrapper
	 * @param {String} shippingMethod
	 * @param {Array} options
	 */
	var cdekShowWrapper = function(shippingMethod, options) {
		var methods = options.methods;
		nextStepEnabled(false);
		warehouseSelectWrapper.hide();
		cdekWarehouses(function(warehouseCount) {
			cdekWrapper.show();
			var appendOptions = methods.reduce(function (carry, item) {
				return carry + [
					'<option value="',
					item.code,
					'" data-with-warehouse="',
					item.with_warehouse,
					'" data-markup-dressingroom="',
					item.markup_dressingroom,
					'">',
					item.name,
					'</option>'
				].join('');
			}, '');
			cdekDeliverySelect.html(cdekDeliverySelectHtml + appendOptions);
			// Select first option
			if (methods.length === 1) {
				var firstValue = cdekDeliverySelect.find('option:enabled').val();
				cdekDeliverySelect.val(firstValue).trigger('change');
				deliveryChooseEnabled(true)
				// Allow next step if there are no warehouses to select
				if (warehouseCount === 0) {
					nextStepEnabled(true);
				} else {
					warehouseSelectWrapper.show();
				}
			} else {
				cdekDeliverySelect.trigger('change');
			}
		});
	};

	/**
	 * @param {String} shippingMethod
	 * 
	 * @returns {Object}
	 */
	var getSelectedCdekOption = function(shippingMethod) {
		return cdekDeliverySelect.find('option[value="' + shippingMethod + '"]');
	};

	/**
	 * @param {String} shippingMethod
	 * 
	 * @returns {Boolean}
	 */
	var isCdekToDoor = function(shippingMethod) {
		return shippingMethod && !getSelectedCdekOption(shippingMethod).data('with-warehouse');
	};

	// Cdek method select
	cdekDeliverySelect.off('change');
	cdekDeliverySelect.on('change', function() {
		var shippingMethod = $(this).val();
		var noneSelected = !shippingMethod;
		var optionSelected = getSelectedCdekOption(shippingMethod);
		var toDoor = isCdekToDoor(shippingMethod);
		var markupDressingRoom = (shippingMethod && optionSelected.data('markup-dressingroom') > 0)
			? optionSelected.data('markup-dressingroom') : 0;
		// Add markup price for dressing room
		if (markupDressingRoom > 0) {
			$('#markup_dressingroom').text(' (+' + markupDressingRoom + ' рублей)');
		} else {
			$('#markup_dressingroom').text();
		}
		warehouseSelect.val(null);
		nextStepEnabled(false);
		// To door
		if (toDoor) {
			warehouseSelectWrapper.hide();
			nextStepEnabled(true);
		// Has warehouses
		} else if (!noneSelected && warehouseSelect.find('option').length > 1) {
			warehouseSelectWrapper.show();
		// To warehouse, but no warehouses to select
		} else if (!noneSelected) {
			nextStepEnabled(true);
		}
		updateDeliveryTable(shippingMethod, { has_dressing_room: hasDressingRoomCheckbox.prop('checked') });
	});

	// Warehouse select
	warehouseSelect.off('change');
	warehouseSelect.on('change', function() {
		var warehouseCode = $(this).val();
		checkoutTable.LoadingOverlay("show");
		$.ajax({
			url: '/index.php?route=checkout/shipping_method/addCdekWarehouseToShippingMethod',
			data: {
				warehouse_code: warehouseCode
			},
			dataType: 'JSON'
		})
			.always(function() {
				checkoutTable.LoadingOverlay("hide");
				nextStepEnabled(true);
			});
	});

	// Dressing room checkbox
	hasDressingRoomCheckbox.off('change');
	hasDressingRoomCheckbox.on('change', function() {
		updateDeliveryTable(cdekDeliverySelect.val(), { has_dressing_room: $(this).prop('checked') });
		if (!isCdekToDoor(cdekDeliverySelect.val())) {
			nextStepEnabled(false);
			cdekWarehouses();
		}
	});

	/**
	 * Update delivery table
	 * @param {String} method
	 * @param {Object} options
	 */
	var updateDeliveryTable = function(method, options) {
		options = options || {};
		checkoutTable.LoadingOverlay("show");
		var data = {
			shipping_method: method
		};
		if (typeof Object.assign === 'function') {
			data = Object.assign(data, options);
		} else {
			for (var key in options) {
				data[key] = options[key];
			}
		}
		$('#checkout-table-info').load(
			"/index.php?route=checkout/shipping_method/deliveryCostTable #checkout-table-info > *",
			data,
			function() {
				deliveryChooseEnabled(true)
				checkoutTable.LoadingOverlay("hide");
			}
		);
	};

	// Delivery radio box change event
	deliveryRadioBox.off('change');
	deliveryRadioBox.on('change', function() {
		deliveryBoxStateChangeTimeout = setTimeout(function() {
			deliveryChooseEnabled(false)
		}, 150);
		var shippingMethod = $(this).val();
		var options = $(this).data('options') || {};
		// Cdek logic
		var isCdek = (options.isCdek && options.methods.length > 0);
		if (!isCdek) {
			updateDeliveryTable(shippingMethod);
			cdekWrapper.hide();
			nextStepEnabled(true);
		} else {
			cdekShowWrapper(shippingMethod, options);
		}
	});
	// Prev step (activate next step button in case if it was deactivated)
	prevStepButton.off('click.step3');
	prevStepButton.on('click.step3', function() {
		nextStepEnabled(true);
	});
	// Trigger delivery selection
	$('input[name="shipping_method"]:checked').trigger('change');
})();
</script>