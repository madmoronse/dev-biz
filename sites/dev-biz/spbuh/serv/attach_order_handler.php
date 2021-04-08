<?php







			$ORDER_ID = $_REQUEST[order_id];
			$PAY_ORDER_ID = $_REQUEST[pay_order_id];

			// проверить, не сцеплен ли счет с каким-то заказом?
			// проверить, естьли в ситеме такой заказ
			// если ОК, то UPDATE иначе ошибки

			$CHECK_PAYOID=$DB->selectRow('SELECT * FROM `oc_prepay_orders` WHERE `buyer_order_id`=?',$ORDER_ID);
			$CHECK_BUYOID=$DB->selectRow('SELECT * FROM `oc_order` WHERE `order_id`=?',$ORDER_ID);

			if (!$CHECK_PAYOID and $CHECK_BUYOID) {

				// OK
				$UPD=$DB->query('UPDATE `oc_prepay_orders` SET `buyer_order_id`=?,`date_order_attach`=? WHERE `prepay_order_id`=?',$ORDER_ID,time(),$PAY_ORDER_ID);

				$LINE='<a href="/index.php?route=account/order/info&order_id='.$ORDER_ID.'" target="_blank" style="font-weight:600;">'.$ORDER_ID.'</a><a href="/spbuh/attach_order_form/?pay_id='.$PAY_ORDER_ID.'" title="Изменить" class="modalwin"><i class="fa fa-pencil" aria-hidden="true" style="float:right;color:#333;"></i></a>';

				$data = array (
					'error' => false, 
					'msg' => 'Операция прошла успешно', 
					'new_line' => $LINE, 
					'pay_order_id' => $PAY_ORDER_ID,
					'new_style' => '#c8eac5',

				);
				// Отсылаем "клиенту" данные в JSON - формате:
				echo json_encode($data);

			} else {


				//ERROR

				if ($CHECK_PAYOID) {


					if ($_REQUEST[force]=='forceattach') {

							$UPD=$DB->query('UPDATE `oc_prepay_orders` SET `buyer_order_id`=?,`date_order_attach`=? WHERE `prepay_order_id`=?',$ORDER_ID,time(),$PAY_ORDER_ID);
			
							$LINE='<a href="/index.php?route=account/order/info&order_id='.$ORDER_ID.'" target="_blank" style="font-weight:600;">'.$ORDER_ID.'</a><a href="/spbuh/attach_order_form/?pay_id='.$PAY_ORDER_ID.'" title="Изменить" class="modalwin"><i class="fa fa-pencil" aria-hidden="true" style="float:right;color:#333;"></i></a>';
			
							$data = array (
								'error' => false, 
								'msg' => 'Операция прошла успешно', 
								'new_line' => $LINE, 
								'pay_order_id' => $PAY_ORDER_ID,
								'new_style' => '#c8eac5',
			
							);
							// Отсылаем "клиенту" данные в JSON - формате:
							echo json_encode($data);



					} else {

						$data = array ('error' => true, 'msg' => 'К заказу '.$ORDER_ID.' уже прикреплен счет', 'err_realy' => true);
						// Отсылаем "клиенту" данные в JSON - формате:
						echo json_encode($data);

					}



				}

				if (!$CHECK_BUYOID) {
					$data = array ('error' => true, 'msg' => 'Заказ '.$ORDER_ID.' не существует');
					// Отсылаем "клиенту" данные в JSON - формате:
					echo json_encode($data);

				}


			}










	
	
	


?>