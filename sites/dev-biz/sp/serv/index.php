<?php

	session_start();

	if ( !isset($_SESSION[customer_id]) ) {


		header('Location: /login/');




	} else {

		require_once 'sql_connect.php';

		if ( $USER=$DB->selectRow('SELECT * FROM `oc_customer` WHERE `customer_id`=? AND (`customer_group_id`=? OR `customer_group_id`=?)',$_SESSION[customer_id],2,4) ) {



				$ROOTP=$_SERVER['DOCUMENT_ROOT'].'/';
				$m=explode("/",$_SERVER['DOCUMENT_ROOT']);
				$ABSP = $m[0].'/'.$m[1].'/'.$m[2].'/';
				$RELP='http://'.$_SERVER['SERVER_NAME'].'/';
				$RURL=$_SERVER['REQUEST_URI'];// Requested URL
			
			
				if ($RURL=='/') $RURL='/sp/';
			
			
				$RURL='/'.$RURL.'/';// нормализуем URL
				$RURL=str_replace("//","/",$RURL);// если есть двойные слэши - заменяем их на один? теперь точно знаем что покраям стоят слэши
			
			
			
				$AURL=explode("/",$RURL);
		
		
		
				
		
				if (strlen($AURL[2])==0) $AURL[2]=1;
		
		
				if (preg_match("/^[0-9]+$/i", $AURL[2])) {
		
					require_once 'serv/prepay_orders_list_sql.php';die;
		
				}
		
		
				if ($AURL[2]=='create_order_form') {
		
		
					require_once 'create_order_form.php';die;
		
		
				}
		
		
				if ($AURL[2]=='create_order_handler') {
		
		
					require_once 'serv/create_order_handler.php';die;
		
		
				}





		} else {


			print 'System error';

		}


	}

	


?>