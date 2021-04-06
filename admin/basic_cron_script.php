<?php
/**
 * ������ ��� ������� ����� cron �������� ������� � .yml 
 */

    // Configuration
    require_once('config.php');

    function make_request($request) {
        $full_request = HTTP_CATALOG . $request;
        echo "Request URL string is: " . $full_request . "\n";
        $ch = curl_init($full_request);
        
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.4) Gecko/2008102920 AdCentriaIM/1.7 Firefox/3.0.4");
        curl_setopt($ch, CURLOPT_COOKIE, '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        $res = curl_exec($ch);
        
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpcode != 200) {
            $error_msg =  "HTTP request failed with status code " . $httpcode;
            error_log($error_msg);
        }
        else {
            echo "Request fulfilled\n";
            echo $res;
        }
        // Shall we close the connection? This part was missing in the original script.
        // curl_close($ch);
    }

?>
