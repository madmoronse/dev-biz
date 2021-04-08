<?php



	/* ######################################################################################### */



		require_once '../mylib/DbSimple/config.php';
		require_once '../mylib/DbSimple/Generic.php';

		$DB = DbSimple_Generic::connect("mysql://website:R4d0G4h7N0e6C3w1@localhost/u0040607_outmax");// connect to MySQL DB



		$UTF8=$DB->query('SET NAMES utf8');

		// Error handler ON
		$DB->setErrorHandler('databaseErrorHandler');

		// SQL errors output
		function databaseErrorHandler($message, $info) {

			
			if (!error_reporting()) return;
			
			echo "SQL Error: $message<br><pre>"; 
			print_r($info);
			echo "</pre>";
			exit();
		}



	/* ######################################################################################### */



?>
