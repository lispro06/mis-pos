<?php
	include_once("rep.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<html>
<head>
	<title>보고서</title>
<link rel="shortcut icon" href="favicon.ico">
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery-1.4.1.js"></script>
<script type="text/javascript" src="jquery-ui.js"></script>
<script>

// 달력 표시

$(document).ready(function() {
    // 달력 - 종료일
    $('#date_end').datepicker({
        dateFormat: 'yy-mm-dd',
        onSelect: function (dateText, inst) {
<?php
	
	$cTime=time();
	$setTime=date("Y-m-d",$cTime);

?>
            var sCurDate = jQuery.trim('<?php echo $setTime;?>');
            if (sCurDate.length>0) {
                var iCurDate   = parseInt(sCurDate.replace(/-/g, ''));
                var iEndDate  = parseInt(jQuery.trim(dateText).replace(/-/g, ''));
                
                if (iEndDate>iCurDate) {
                    alert('오늘 이전 일자를 선택해 주세요.');
                    $('#date_end').val(sCurDate);
                }else{
					xajax_inquiry(xajax.$('sale_code').value,xajax.$('date_end').value);
				}
            }
        }
    });
    $('#img_date_end').click(function() {$('#date_end').focus();});
});
</script>
<div id="menu" name="menu">
</div>
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
			xajax_inquiry(11001,"<?php echo $setTime;?>");
		}
</script>
</head>
<body>
<div class="exterior">
	<div class="tabA">
		<table border="0" style="text-align:center;width:100%;background-color:<?echo $extColor;?>" border="0" cellspacing="0" cellpadding="0">
		<tr><input type="hidden" name="sale_code" id="sale_code" value="11001" >
		<td style="width:60%;">
			<div id="tabs2" name="tabs2" style="width:100%">
			</div>
		</td>
		<td style="width:10%"></td>
		<td id="img_date_end"><span style="color:#FFFFFF;font-weight:bolder;">◈ 일자검색</span>
		<input type="text" name="date_end" id="date_end" size="10" style="text-align:center;width:70px;" readonly /><input type="button" id="reqBtn" name="reqBtn" onclick="xajax_parent(xajax.$('sale_code').value, xajax.$('date_end').value);" value="조회"></td>
		</tr>
		</table>
		<div id="content" name="content" style="width:99%;margin-right:auto;margin-left:auto; height:100%; padding:4px; border:1 solid #000000;">
		</div>
	</div>
</div>
	<div id="div2" name="div2" style="padding:4px; border:0 solid #000000;"></div>
<script src="rep.js" type="text/javascript"></script>
</body>
</html>