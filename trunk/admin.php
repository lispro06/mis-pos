<?php
	include_once("menu.php");

$msgDiv='<div id="msgDiv" name="msgDiv" style="background-color:#FFFF99;position:absolute;left:300px;top:65px;z-index:1;height:20px;width:600px;text-align:center;vertical-align:middle;display:inline;"></div>';

	$extColor="#F0575A";
	$tabColor="#FFE3E2";
	$cTime=time();
	$setTime=date("Y-m-d",$cTime);
	$today=$setTime;
	if(session_is_registered('reg_date')){
		$date_input=$_SESSION['session_reg_date'];//session에 저장된 날짜를 이용함.
		$setTime=substr($date_input,0,4);
		$setTime=$setTime."-".substr($date_input,4,2);
		$setTime=$setTime."-".substr($date_input,6,2);
	}
	include_once("admins.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<html>
<head>
	<title>수납관리 프로그램 - 관리자</title>
<link rel="shortcut icon" href="favicon.ico">
<link href="style.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="jquery-1.4.1.js"></script>
<script type="text/javascript" src="jquery-ui.js"></script>
<script>
// 달력 표시

$(document).ready(function() {
    // 달력 - 입력일
    $('#date_input').datepicker({
        dateFormat: 'yy-mm-dd',
        onSelect: function (dateText, inst) {
            var sEndDate = jQuery.trim('<?php echo $today;?>');
            if (sEndDate.length>0) {
                var iEndDate   = parseInt(sEndDate.replace(/-/g, ''));
                var iStartDate = parseInt(jQuery.trim(dateText).replace(/-/g, ''));
                
                if (iStartDate>iEndDate) {
                    alert('오늘 이전 일자를 선택하세요.');
					$('#date_input').val('<?php echo $setTime;?>');
                }
				else{
					xajax_inquiry(xajax.$('date_input').value);
				}
            }
			
        }
    });
    $('#img_date_input').click(function() {$('#date_input').focus();});
  
});
</script>
<?
	// output the xajax javascript. This must be called between the head tags
	$xajax->printJavascript();
?>
<script>
	/* <![CDATA[ */
		window.onload = function() {
<?
		if($_GET['mode']){
?>
			xajax_<?php echo $_GET['mode'];?>("<?php echo $_GET['tn'];?>");
<?
		}else{
?>
			xajax_inquiry("<?php echo $setTime;?>","<?php echo $setTime;?>");
<?
		}
?>
		}
</script>
<script>
	function tf(val){
		if(val==true)
			return "Y";
		else
			return "N";
	}
    function checkForm() {
        form = document.f;
		var aclStr="index.php";
<?php
	$pgSql="SELECT `name`,`file` FROM  `toto_page`";
	$pgRes = mysql_query($pgSql, $connect);
	$pgOut="";
	while($pgRow = mysql_fetch_row($pgRes)){
		$file=explode(".",$pgRow[1]);
		echo "\n	if(form.".$file[0].".checked==true){\n		aclStr=aclStr+'|".$pgRow[1]."';\n	};";
	}
?>
        xajax_aclUpd(form.no.value,form.user_id.value,form.user_pw.value,aclStr,form.user_ip.value,tf(form.view_skin.checked),tf(form.view_cos.checked),tf(form.view_all.checked));
    }
	function toto_doctor(){
		document.location.replace("admin.php?mode=itemView&tn=toto_doctor");
	}
	function toto_payc(){
		document.location.replace("admin.php?mode=itemView&tn=toto_payc");
	}
	function toto_expc(){
		document.location.replace("admin.php?mode=itemView&tn=toto_expc");
	}
	function toto_bankbookinfo(){
		document.location.replace("admin.php?mode=itemView&tn=toto_bankbookinfo");
	}
	function toto_page(){
		document.location.replace("admin.php?mode=itemView&tn=toto_page");
	}
</script>
<body>
<?php
	echo $menu;
?>
<div class="exterior">
	<div class="tabA">
		<table border="0" style="text-align:center;width:100%;background-color:<?echo $extColor;?>" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td style="width:45%">
		<div id="tabs2" name="tabs2" style="width:100%">
			<table border="0" cellspacing="0" cellpadding="0" style="width:100%">
			  <tr>
				<td height="30" align="center" style="width:2%;"></td>
				<td height="30" align="center" style="width:14%"><a href="./admin.php?mode=acl"><span style="color:#FFFFFF">접속자관리</span></a> </td>
				<td height="30" align="center" style="width:14%"><a href="./admin.php?mode=itemView&tn=toto_doctor"><span style="color:#FFFFFF">진료실</span></a></td>
				<td height="30" align="center" style="width:14%"><a href="./admin.php?mode=itemView&tn=toto_payc"><span style="color:#FFFFFF">수납항목</span></a></td>
				<td height="30" align="center" style="width:14%"><a href="./admin.php?mode=itemView&tn=toto_expc"><span style="color:#FFFFFF">지출항목</span></a></td>
				<td height="30" align="center" style="width:14%"><a href="./admin.php?mode=itemView&tn=toto_bankbookinfo"><span style="color:#FFFFFF">통장관리</span></a></td>
				<td height="30" align="center" style="width:14%"><a href="./admin.php?mode=itemView&tn=toto_page"><span style="color:#FFFFFF">페이지관리</span></a></td>
				<td height="30" align="center" style="font-weight: bolder; width:15%;"><a href="#"><span style="color:#FFFFFF">메일 설정</span></a></td>
			  </tr>	
			</table>
		</div>
		</td>
		<td style="width:30%"></td>
		<td id="img_date_input"><span style="color:#FFFFFF;font-weight:bolder;">◈ 일자검색</span>
		<input type="text" name="date_input" id="date_input" style="text-align:center;width:70px;" value="<?php echo $setTime?>" readonly />
		<input type="button" onclick="xajax_inquiry(xajax.$('date_input').value);" value="로그보기"></td>
		</tr></table>
		<div style="width:99%;" align="right">
		</div>
		<div id="content" name="content" style="width:99%;margin-right:auto;margin-left:auto; height:100%; padding:4px; border:1 solid #000000;">
		</div>
	</div>
</body>
</html>