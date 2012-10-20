<?php
	include_once("menu.php");
	include_once("is_cont.php");
	include_once("loging.php");
		$cTime=time();
		$setTime=date("Y-m-d",$cTime);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<HTML>
 <head>
  <TITLE>수납관리 프로그램 - 손익계산표 </TITLE>
  <META NAME="Generator" CONTENT="EditPlus">
  <META NAME="Author" CONTENT="">
  <META NAME="Keywords" CONTENT="">
  <META NAME="Description" CONTENT="">
<link rel="shortcut icon" href="favicon.ico">
<link href="style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="ajax-tooltip/css/ajax-tooltip.css" media="screen" type="text/css">
<link rel="stylesheet" href="ajax-tooltip/css/ajax-tooltip-demo.css" media="screen" type="text/css">
<script type="text/javascript" src="ajax-tooltip/js/ajax-dynamic-content.js"></script>
<script type="text/javascript" src="ajax-tooltip/js/ajax.js"></script>
<script type="text/javascript" src="ajax-tooltip/js/ajax-tooltip.js"></script>
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
					xajax_save(xajax.$('date_input').value);
				}
            }
			
        }
    });
    $('#img_date_input').click(function() {$('#date_input').focus();});
  
});
// refresh 2012-09-05
function refresh_entry()
{
			xajax_inquiry(xajax.$('date_input').value);
	
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
			xajax_inquiry(0);//전체 업데이트 되도록 지정일 없이 현재 달에 맞춰 출력하게 함.2012-09-18
			//xajax_save("<?php echo $setTime;?>");
		}
		function tr(num){
			num=num.replace(/,/g,"");//comma 제거
			return parseInt(num);//숫자로 리턴
		}
		function saveIs(){
			xajax_inquiry();
		}
		function scrollDiv(){
			document.getElementById("focusBox").focus();
		}
	
</script>
<body>
<div id="menu" name="menu" style="width:100%">
	<?php echo $menu;?>
</div>
<div class="exterior">
	<div class="tabA">
		<table border="0" style="text-align:center;width:100%;background-color:<?php echo $extColor;?>" border="0" cellspacing="0" cellpadding="0"><tr>
		<td style="width:40%">
	<div id="tabs2" name="tabs2" style="width:100%"><table border="0" cellspacing="0" cellpadding="0" style="width:100%">
		  <tr>
			<td height="30" align="center" style="width:4%;"></td>
			<td height="30" align="center" style="font-weight: bolder; width:48%;"><div class="tabS"><a href="is_view.php"><span style="color:<?echo $thColor;?>">손익계산표</span></a></div></td>
			<td height="30" align="center" style="width:48%;"><a href="exp_all.php?exps_code=4000"><span style="color:#FFFFFF">그룹항목열람</span></a></td>
		  </tr>	
		</table>
	</div></td>
	<td style="width:30%"></td>
	<td id="img_date_input"><span style="color:#FFFFFF;font-weight:bolder;">◈ 일자검색</span>
	<input type="text" name="date_input" id="date_input" style="text-align:center;width:70px;" value="<?php echo $setTime?>" readonly />
	<input type="button" onclick="xajax_save(xajax.$('date_input').value);" value="조회"></td>
	</tr></table>
		<div id="divtable" name="divtable" style="padding:4px;">
		</div>
		<div style="width:99%;" align="right">
			<input type="button" id="btn" name="btn" onclick="saveIs();" value="저장" style="display:none">
		</div>
	</div>
</div>
<div id="tt" name="tt"></div>
 </BODY>
</HTML>
