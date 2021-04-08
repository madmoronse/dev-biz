<?php
include('voting.php');
if(SVOTING == 'mysql') {
  $sqlc[$obVot->votusers] = "CREATE TABLE `$obVot->votusers` (`day` INT(2), `voter` VARCHAR(15), `item` VARCHAR(200) NOT NULL DEFAULT '') CHARACTER SET utf8 COLLATE utf8_general_ci";
  $sqlc[$obVot->votitems] = "CREATE TABLE `$obVot->votitems` (`item` VARCHAR(200) PRIMARY KEY NOT NULL DEFAULT '', `vote` INT(10) NOT NULL DEFAULT 0, `nvotes` INT(9) NOT NULL DEFAULT 1) CHARACTER SET utf8 COLLATE utf8_general_ci";
  foreach($sqlc AS $tab=>$sql) {
    if($obVot->sqlExecute($sql) !== false) echo "<h4>The '$tab' table was created</h4>";
  }
}
else echo 'Set "mysql" value to SVOTING, in voting.php';