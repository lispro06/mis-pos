<?php
		//2011-03-17
			$dateTime=time();
			$logSql="INSERT INTO  `toto_log` (  `no` ,  `user_id` ,  `user_ip` ,  `view_pg` ,  `reg_time` ,  `reg_date` ) VALUES ('',  '".$_SESSION['sunap']."',  '".$_SERVER['REMOTE_ADDR']."',  '".$cLoc."',  '".$dateTime."',  '".date("Ymd",$dateTime)."');";
			$def = mysql_query($logSql, $connect);
?>