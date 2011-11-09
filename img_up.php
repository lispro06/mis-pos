<?php
	var_dump($_FILES["pictures"]);
	$cnt=0;
	$names[0]="favicon.ico";
	$names[1]="logo.gif";
	$names[2]="logins.gif";
	foreach ($_FILES["pictures"]["error"] as $key => $error) {
		if (!$error) {
			$tmp_name = $_FILES["pictures"]["tmp_name"][$key];
			$name = $_FILES["pictures"]["name"][$key];
	        $res = move_uploaded_file($tmp_name, "logo/".$names[$cnt++]);
		}
	}
	if($res){		
?>
<script>
	alert("업로드가 정상 완료 되었습니다.");
	self.close();
</script>
<?php
	}
?>