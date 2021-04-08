<?php



if ( isset($_SESSION[customer_id]) ) {




		$SUMM = (int) $_REQUEST[order_summ];

		if ($SUMM>0 and strlen($_REQUEST[buyer_fio])>7 and strlen($_REQUEST[buyer_email])>6 and strlen($_REQUEST[buyer_phone])>10) {



				$DB->query('INSERT INTO `oc_prepay_orders` (

						`date_create`, 
						`partner_id`, 
						`partnet_type`, 
						`order_summ`, 
						`buyer_fio`, 
						`buyer_email`,
						`buyer_phone`,
						`order_status`,
						`buyer_order_id`) 
	
	
					VALUES (?,?,?,?,?,?,?,?,?)',
	
						time(),
						$USER[customer_id],
						$USER[customer_group_id],
						$SUMM,
						$_REQUEST[buyer_fio],
						$_REQUEST[buyer_email],
						$_REQUEST[buyer_phone],
						'новый',
						'не прикреплен'
	
				);

				$NEW_ORDID = mysql_insert_id();// получить последний вставленный ID 

				$NEW_LINE='
					<tr id="order_id_'.$NEW_ORDID.'" style="background:#fadadd;">
						<td>'.$NEW_ORDID.'</td>
						<td>не прикреплен</td>
						<td>'.$_REQUEST[buyer_fio].'</td>
						<td>'.$_REQUEST[buyer_email].'</td>
						<td>'.$_REQUEST[buyer_phone].'</td>
						<td>'.number_format($SUMM, 0, '.', ' ').' руб.</td>
						<td>новый</td>
						<td><span id="order'.$NEW_ORDID.'">http://myrupay.ru/sp/payform.php?order_id='.$NEW_ORDID.'</span><span class="copy_btn" data-clipboard-target="#order'.$NEW_ORDID.'" style="margin-top:3px;">Скопировать</span></td>
			
					</tr>
				';


				//отсылаем автоматом счет на почту

					require_once 'libmail.php';

					$subj='Предоплата за заказ';

					$ORDER="Здравствуйте, уважаемый клиент!\r\n\r\n";		
					$ORDER.="Для внесения предоплаты за ваш заказ, пожалуйста, пройдите по ссылке\r\n\r\n";
					$ORDER.="http://myrupay.ru/sp/payform.php?order_id=".$NEW_ORDID."\r\n\r\n";
					$ORDER.="С уважением, ".$USER[firstname].". \r\n";
		
		
							
							$mailfrom='noreply@myrupay.ru';
							$mailto=$_REQUEST[buyer_email];
		
				
							$m = new Mail("utf-8"); //создали новый объект класса Mail
				
							$m->From($mailfrom); //задаем любой адрес отправителя
							$m->To($mailto,"to_customer");//отсылаем покупателю
							$m->To($USER[email],"to_operator");//отсылаем продавцу
				
							$m->Subject($subj);//тема сообщения
				
							$m->Body($ORDER);//задаем текст сообщения
				
							$m->Send();





				$data = array (
					'error'		=> false, 
					'msg'		=> 'Счет успешно создан',
					'new_line'	=> $NEW_LINE,
					'fade_id'	=> '#order_id_'.$NEW_ORDID
				);

				// Отсылаем "клиенту" данные в JSON - формате:
				echo json_encode($data);
				


		} else {

			$data = array ('error' => true, 'msg' => 'Заполните поля');
			// Отсылаем "клиенту" данные в JSON - формате:
			echo json_encode($data);

		}



			




}





	
	
	


?>