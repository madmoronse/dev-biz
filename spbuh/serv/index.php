<?php

	session_start();

	if ( !isset($_SESSION['customer_id']) ) {


		header('Location: /login/');



	} else {


		require_once 'sql_connect.php';


		//т.е. $_SESSION[customer_id]==57 и всё

		if ( $USER=$DB->selectRow('SELECT * FROM `oc_customer` WHERE `customer_id`=?',$_SESSION['customer_id']) and ($_SESSION['customer_id']==57 or $_SESSION['customer_id']==11581 or $_SESSION['customer_id']==7827 or $_SESSION['customer_id']==4075 or $_SESSION['customer_id'] == 15398) ) {

		


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
		
		
				if ($AURL[1]=='spbuh' and preg_match("/^[0-9]+$/i", $AURL[2])) {
		
					require_once 'serv/prepay_orders_list_sql.php';die;
		
				}
		

				if ($AURL[1]=='spbuh' and $AURL[2]=='omorders') {

					if (strlen($AURL[3])==0 and !preg_match("/^[0-9]+$/i", $AURL[3])) $AURL[3]=1;

					require_once 'serv/prepay_omorders_list_sql.php';die;
		
				}



				if ($AURL[1]=='spbuh' and $AURL[2]=='for_tiu') {require_once 'serv/for_tiu.php';die;}

				//if ($AURL[1]=='spbuh' and $AURL[2]=='for_opt') {require_once 'serv/for_opt.php';die;}

				if ($AURL[1]=='spbuh' and $AURL[2]=='for_drop') {require_once 'serv/for_drop.php';die;}

				//if ($AURL[1]=='spbuh' and $AURL[2]=='vk_upload_v5') {require_once 'serv/vk_api_v5.php';die;}

				//if ($AURL[1]=='spbuh' and $AURL[2]=='vk_upload_v6') {require_once 'serv/vk_api_v6.php';die;}

				//if ($AURL[1]=='spbuh' and $AURL[2]=='vk_upload_v8') {require_once 'serv/vk_api_v8.php';die;}

				//if ( $AURL[1]=='spbuh' and $AURL[2]=='set_sex') {require_once 'serv/set_sex.php';die;} // анализ продаж

				//if ($AURL[1]=='spbuh' and $AURL[2]=='update_names') {require_once 'serv/update_names.php';die;}



				/*
				if ($AURL[1]=='spbuh' and $AURL[2]=='phpinfo') {

					phpinfo();

					die;
				}
				*/

				if ($AURL[2]=='attach_order_form') {
		
		
					require_once 'attach_order_form.php';die;
		
		
				}
		
		
				if ($AURL[2]=='attach_order_handler') {
		
		
					require_once 'serv/attach_order_handler.php';die;
		
		
				}



		


		} else {


			print 'System error';

		}



	}

	


?>
