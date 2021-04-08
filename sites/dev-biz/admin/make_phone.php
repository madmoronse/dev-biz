<?php 
$result = "";
function add_eight($matches) 
  {
    // ��� ������: $matches[0] -  ������ ��������� �������
    // $matches[1] - ��������� ������ ��������,
    // ����������� � ������� ������, � ��� �����...
	//$res = preg_replace('/^.{1}/', '', $matches[0]);
	$res = mb_substr($matches[0],1);
	$res ="\n7".$res;
    	return $res;
  }
if (isset($_POST['textarea1'])){	
	//echo $_POST['textarea1'];
	$result = preg_replace('/[^0-9,\n]/', '', $_POST['textarea1']);
	$result = preg_replace('/^8/', '7', $result);
	$result = preg_replace('/\n8/', "\n7", $result);

	$result = preg_replace_callback('/\n\d{10}\n/', "add_eight", $result);
	$result = preg_replace_callback('/\n\d{10}$/', "add_eight", $result);

	$result = preg_replace('/\n\d{1,10}\n/', "\n", $result);
	$result = preg_replace('/\n7\d{9}\n/', "\n", $result);
	//$result = preg_replace('/\b(\d{1,10}|\d{12,})\b/', "",  $result);
	//$result = preg_replace('/(?<=\s)(\S{1,10}|\S{12,})(?=\s)/', "",  $result);

	
	//echo $result;
}
?><html>
<head>
</head>
<body>
<form action="http://bizoutmax.ru/admin/make_phone.php" name="form1" method=post>
<table border=1 align="center">
<tr height=20>
<td><input type="submit" value="Click to Correct Numbers"></td>
<td colspan=2>&nbsp;</td>
</tr>
<tr>
<td width=120><textarea cols=60 rows=10000 placeholder="Put Numbers here" name=textarea1><?php if (isset($_POST['textarea1'])){echo $_POST['textarea1'];}?></textarea></td>
<td width=15>&nbsp;</td>
<td><textarea cols=60 rows=10000 placeholder="Result will Apear"><?=$result?></textarea></td>
</tr>
</table>

</form>

</body>
</html>

<?php
//echo '<pre>';
//print_r($_POST);
//echo 'hello world';
//echo '</pre>';


?>