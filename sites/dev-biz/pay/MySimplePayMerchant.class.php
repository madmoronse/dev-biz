<?php

require_once('SimplePayAbstractMerchant.class.php');

/**
 * интерфейс для работы с SimplePay.
 * Здесь прописаны все основные настройки, а также определяются обработчики 
 * оповещений, получаемых от SimplePay по факту осуществления платежа, либо при 
 * отказе в его проведении.
 */



class MySimplePayMerchant extends SimplePayAbstractMerchant {

    protected


	    /**
	     * Идентификатор торговой точки в SimplePay
	     * Этот параметр можно узнать по адресу: https://secure.simplepay.pro/merchant/#tab_outlets
	     */

	    //идентификатор торговой точки
            $outlet_id = '2238',




            /**
             * Секретный ключ торговой точки,
             * Этот параметр можно изменить по адресу: https://secure.simplepay.pro/merchant/#tab_outlets
             */

	    //Секретный ключ точки
            $secret_key = '7896a025215e6fbd70e6c98fbbe17557',




            /**
             * Секретный ключ точки для Result, используется для подписи Result-уведомлений
             * Этот параметр можно изменить по адресу: https://secure.simplepay.pro/merchant/#tab_outlets
             */

	    //Секретный ключ точки для Result
            $secret_key_result = 'e9154502641aea96082dece1fe0de7b1',



            /**
             * Адрес Result URL на Вашем сайте.
             */

            $result_url = "http://bizoutmax.ru/pay/result.php",



            /*
             * Алгоритм хеширования подписей. Безопаснее использовать SHA256.
             * Этот параметр можно изменить по адресу: https://secure.simplepay.pro/merchant/#tab_outlets
             */

            $hash_algo = "MD5";




    /*
     * Этот параметр отвечает за опцию SSL Verifypeer. Отключать рекомендуется 
     * только в случае, если у Вас по какой-то причине на сервере нет корневых 
     * сертификатов.
     */

    protected
            $strong_ssl = false;





		/**
		 * обработчик успешного платежа. Можено изменить статус заказа 
		 * в своей системе, активировать услугу.
		 * @param int $order_id В этот параметр будет передан номер заказа в системе магазина
		 * @param array $request_params В этот параметр будет полный набор полученных
		 * в уведомлении параметров.
		 */


		
		function process_success($order_id, $request_params) {

		    // обработчик успешного зачисления платежа здесь
		    // $order_id - ID заказа в Вашей системе
		    // $request_params - параметры оповещения SimplePay sp_amount вроде бы это сумма платежа


			require 'sql_connect.php';



			if ( $CURRENT_ORDER=$DB->selectRow('SELECT * FROM `oc_order` WHERE `order_id`=?',$order_id) ) {

						$INS=$DB->query('INSERT INTO `oc_order_history` (
			
									`order_id`,
									`order_status_id`,
									`notify`,
									`comment`,
									`date_added`) 
			
			
								VALUES (?,?,?,?,?)',
			
									$order_id,
									200,
									0,
									'Поступила оплата SimplePay '.$request_params[sp_amount].' руб.',
									date('Y-m-d H:i:s')
			
						);// заменяем значение на высланное		
			
			}	



			return true;

			//можно делать любые действия
		
		}
		
		
		
		
		
		
		
		/**
		 * обработчик в случае отказа в проведении платежа, либо при его отмене. 
		 * @param int $order_id В этот параметр будет передан номер заказа в системе магазина
		 * @param array $request_params В этот параметр будет полный набор полученных
		 * в уведомлении параметров.
		 */


		
		function process_fail($order_id, $request_params) {
		    // обработчик отказа в зачислении платежа
		    // $order_id - ID заказа в Вашей системе
		    // $request_params - параметры оповещения SimplePay
		

			return true;


			//можно делать любые действия
		
		}



}
