<?php
	include_once("menu.php");
	include_once("dailys.php");
	include_once("loging.php");
	$cTime=time();
	$setTime=date("Y-m-d",$cTime);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<html>
<head>
	<title>수납관리 프로그램 - 일일정산</title>
  <link rel="shortcut icon" href="logo/favicon.ico">
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
            var sEndDate = jQuery.trim('<?php echo $setTime;?>');
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

function confirm_entry()
{
	input_box=confirm("저장되었습니다. 일일마감요청을 하시겠습니까?");
	if (input_box==true){ 
	// Output when OK is clicked
		xajax_endBt(xajax.$('date_input').value);
	}

	else{
	// Output when Cancel is clicked
	}

}
</script>
</head>
<?php

	if(session_is_registered('reg_date')){
		$date_input=$_SESSION['session_reg_date'];//session에 저장된 날짜를 이용함.
		$setTime=substr($date_input,0,4);
		$setTime=$setTime."-".substr($date_input,4,2);
		$setTime=$setTime."-".substr($date_input,6,2);
	}
	// output the xajax javascript. This must be called between the head tags
	$xajax->printJavascript();
?>
<script>
	/* <![CDATA[ */
		window.onload = function() {
			xajax_inquiry("<?php echo $setTime;?>");
		}
</script>
<body>
<div id="menu" name="menu" style="width:100%">
	<?php echo $menu;?>
<div class="exterior">
	<div class="tabA">
		<table border="0" style="text-align:center;width:100%;background-color:<?echo $extColor;?>" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td style="width:40%">
		<div id="tabs2" name="tabs2" style="width:100%">
			<table border="0" cellspacing="0" cellpadding="0" style="width:100%">
			  <tr>
				<td height="30" align="center" style="width:4%;"></td>
				<td height="30" align="center" style="width:48%"><a href="sum.php"><span style="color:#FFFFFF">원장별정산</span></a> </td>
				<td height="30" align="center" style="font-weight: bolder; width:48%;"><div class="tabS"><A href="daily.php"><span style="color:<?echo $thColor;?>">일일정산</span></A></div></td>
			  </tr>	
			</table>
		</div>
		</td>
		<td style="width:30%"></td>
		<td id="img_date_input"><span style="color:#FFFFFF;font-weight:bolder;">◈ 일자검색</span>
		<input type="text" name="date_input" id="date_input" style="text-align:center;width:70px;" value="<?php echo $setTime?>" readonly />
		<input type="button" onclick="xajax_inquiry(xajax.$('date_input').value);" value="조회"></td>
		</tr></table>
		<div id="detable" name="detable">
		</div>
		<form id="fr" name="fr">
		<div id="divtable" name="divtable" style="padding:10px;background-color:<?echo $tabColor;?>">
		</div>
		</form>
		<div style="width:99%;" align="right">
		<input type="text" id="no" name="no" style="display:none;">
		<input type="button" onclick="xajax_save(xajax.$('no').value,xajax.$('d_afdy_mony').value,xajax.$('d_doct_mony').value,xajax.$('c_afdy_mony').value,xajax.$('c_doct_mony').value,xajax.$('date_input').value);" id="btn" name="btn" value="저장"><input type="button" id="end" name="end" disabled="true" value="일마감요청" onclick="xajax_endBt(xajax.$('date_input').value);" style="display:none;">
		</div>
	</div>
</div>
<div id="tt" name="tt"></div>
<script src="daily.js" type="text/javascript"></script>
</body>
</html>
