<?php
	include_once("months.php");
	include_once("loging.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>지출부 집계 - 월별지출</title>
  <link rel="shortcut icon" href="logo/favicon.ico">
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery-1.4.1.js"></script>
<script type="text/javascript" src="jquery-ui.js"></script>
<script>

// 달력 표시

<?php
	
	$cTime=time();
	$setTime=date("Y-m-d",$cTime);

?>
$(document).ready(function() {
    $('#img_date_input').click(function() {$('#date_input').focus();});
    // 달력 - 시작일
    $('#date_start').datepicker({
        dateFormat: 'yy-mm-dd',
        onSelect: function (dateText, inst) {
            var sCurDate = jQuery.trim('<?php echo $setTime;?>');
            var sEndDate = jQuery.trim($('#date_end').val());
            if (sEndDate.length>0) {
                var iCurDate   = parseInt(sCurDate.replace(/-/g, ''));
                var iEndDate   = parseInt(sEndDate.replace(/-/g, ''));
                var iStartDate = parseInt(jQuery.trim(dateText).replace(/-/g, ''));
             
                if (iStartDate>iCurDate) {
                    alert('오늘 이전 일자를 선택하세요');
                    $('#date_start').val(sCurDate);
                }else{
					xajax_inquiry(xajax.$('exps_code').value, xajax.$('date_start').value);
				}
            }
        }
    });
    $('#img_date_start').click(function() {$('#date_start').focus();});
    $('#start_month').click(function() {$('#date_start').focus();});
    // 달력 - 종료일
    $('#date_end').datepicker({
        dateFormat: 'yy-mm-dd',
        onSelect: function (dateText, inst) {
            var sCurDate = jQuery.trim('<?php echo $setTime;?>');
            var sStartDate = jQuery.trim($('#date_start').val());
            if (sStartDate.length>0) {
                var iCurDate   = parseInt(sCurDate.replace(/-/g, ''));
                var iStartDate = parseInt(sStartDate.replace(/-/g, ''));
                var iEndDate  = parseInt(jQuery.trim(dateText).replace(/-/g, ''));
                
                if (iStartDate>iEndDate || iEndDate>iCurDate) {
                    alert('올바른 시작일과 오늘 이전 일자를 선택해 주세요.');
                    $('#date_end').val(sCurDate);
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
</head>
<body>
<div id="pdiv" style="position:absolute;top:100px;left:200px;width:500px;display:none;">preview</div>
<div class="exterior">
	<div class="tabA">
		<table border="0" style="text-align:center;width:100%;background-color:<?echo $extColor;?>" border="0" cellspacing="0" cellpadding="0">
		<tr><input type="hidden" name="exps_code" id="exps_code" value="4000" >
		<td style="width:50%;">
			<div id="tabs2" name="tabs2" style="width:100%">
			</div>
		</td>
		<td style="width:20%"></td>
		<td id="img_date_start"><span style="color:#FFFFFF;font-weight:bolder;">◈ 일자검색</span>
		<input type="text" name="date_start" id="date_start" size="0" readonly style="border:0px;width:0px;background-color:<?echo $extColor;?>" /><input type="text" name="start_month" id="start_month" style="width:50px;text-align:center;" readonly />
		<input type="button" id="reqBtn" name="reqBtn" onclick="xajax_parent(xajax.$('exps_code').value, xajax.$('date_start').value);" value="조회"></td>
		<input type="text" name="date_end" id="date_end" size="10" readonly style="display:none"/>
		</tr>
		</table>
		<div id="content" name="content" style="width:99%; height:100%; padding:4px; border:1 solid #000000;">
		</div>
		<div id="pv" name="pv" style="text-align:right;width:100%;display:none;">
		</div>
	</div>
</div>
<div id="div2" style="overflow-y:auto; width:100%; height:50px; padding:4px; border:1 solid #000000;">&#160;</div>
<script>
		window.onload = function() {
			xajax_inquiry('<?php echo $_GET["exps_code"];?>','<?php echo $setTime;?>');
		}
</script>
</body>
</html>