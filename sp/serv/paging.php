<?php 

function universal_link_bar($page,$count,$pages_count,$addr,$OTNP) { 

   // @universal_link_bar($page,$totalRows,ceil($totalRows/$pageSize),$ADDR,$RELP) - подключение где надо

   $show_link=2;	

   if ($page<=$pages_count) {

	$beg= $page - $show_link; if ($beg<2) $beg=2;
	$end= $beg  + $show_link*2; if ($end>=$pages_count) {$end=$pages_count;$beg=$end-5;if ($beg<2) $beg=2;}

	$np=$page-1;

	$PLST='';

	if (($page-1)<1) {$PLST=$PLST.'<a href="'.$OTNP.$addr.'1/" class="current">1</a>';}
	else {$PLST=$PLST.'<a href="'.$OTNP.$addr.'1/">1</a>';}

	if ($pages_count==1) $PLST='<a href="'.$OTNP.$addr.'1/" class="current">1</a> ';

	if ($beg>2) $PLST=$PLST.'<span>&#8230;</span>';

	for ($i=$beg;$i<=$end;$i++) {
		$vp=$i;
		if($i==$page) {if($i<$pages_count) $PLST=$PLST.'<a href="'.$OTNP.$addr.$i.'/" class="current">'.$i.'</a>';}
		else  {if ($i<$pages_count) $PLST=$PLST.'<a href="'.$OTNP.$addr.$vp.'/">'.$i.'</a>';}
	}

	if ($end<($pages_count-1)) $PLST=$PLST.'<span>&#8230;</span>';

	if ($pages_count>1) {

		$ep=$pages_count;
		$np=$page+1;

		if ($page==$pages_count) {$PLST=$PLST.'<a href="'.$OTNP.$addr.$pages_count.'/" class="current">'.$pages_count.'</a>';}
		else {$PLST=$PLST.'<a href="'.$OTNP.$addr.$ep.'/">'.$pages_count.'</a>';}
	}

	return $PLST; 

   } else {return false;}

}

?>
