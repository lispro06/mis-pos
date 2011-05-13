<?php
	include_once("menu.php");
	include_once("sums.php");
	include_once("loging.php");
		$cTime=time();
		$setTime=date("Y-m-d",$cTime);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<HTML>
 <head>
  <TITLE><?php echo $hospital;?> - 일정산 </TITLE>
  <META NAME="Generator" CONTENT="EditPlus">
  <META NAME="Author" CONTENT="">
  <META NAME="Keywords" CONTENT="">
  <META NAME="Description" CONTENT="">
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
// 정산 화면으로 이동 2011-03-30
function confirm_entry()
{
	input_box=confirm("일일정산을 시작하겠습니까?");
	if (input_box==true){ 
	// Output when OK is clicked
		document.location.replace("./daily.php");
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
		function tr(num){
			num=num.replace(/,/g,"");//comma 제거
			return parseInt(num);//숫자로 리턴
		}
		function saveSum(){
<?php
	$docSql="select * from t_doctor order by `no` asc";
	$docRes = mysql_query($docSql, $connect); 
	$docTot = mysql_num_rows($docRes); // 총 레코드 수
	$docNum=$docTot;
	$bfor="			var bfor_mony = ";
	$aftr="			var aftr_mony = ";
	$gner="			var gner_cont = ";
	$insu="			var insu_cont = ";
	while($docTot--){
		$docNo++;
		$bfor=$bfor."tr(xajax.$('bfor_mony_".$docNo."').value)+";
		$aftr=$aftr."tr(xajax.$('aftr_mony_".$docNo."').value)+";
		$gner=$gner."tr(xajax.$('gner_cont_".$docNo."').value)+";
		$insu=$insu."tr(xajax.$('insu_cont_".$docNo."').value)+";
		$saveStr="xajax_save(xajax.$('no_".$docNo."').value,xajax.$('rmdy_doct_".$docNo."').value,xajax.$('bfor_mony_".$docNo."').value,xajax.$('aftr_mony_".$docNo."').value,xajax.$('gner_cont_".$docNo."').value,xajax.$('insu_cont_".$docNo."').value,xajax.$('date_input').value);\n";
	}
		$bfor=$bfor."0;\n";
		$aftr=$aftr."0;\n";
		$gner=$gner."0;\n";
		$insu=$insu."0;\n";
		echo $bfor;
		echo $aftr;
		echo $gner;
		echo $insu;
?>
	if(tr(xajax.$('sum_bfor_mony').value)==bfor_mony && tr(xajax.$('sum_aftr_mony').value)==aftr_mony && tr(xajax.$('sum_gner_cont').value)==gner_cont && tr(xajax.$('sum_insu_cont').value)==insu_cont){
		<?php echo $saveStr;?>
	}else{
		alert("합계가 틀렸습니다.");
	}
		confirm_entry();
		}
</script>
<body>
<div id="menu" name="menu" style="width:100%">
	<?php echo $menu;?>
</div>
<div class="exterior">
	<div class="tabA">
		<table border="0" style="text-align:center;width:100%;background-color:<?echo $extColor;?>" border="0" cellspacing="0" cellpadding="0"><tr>
		<td style="width:40%">
	<div id="tabs2" name="tabs2" style="width:100%"><table border="0" cellspacing="0" cellpadding="0" style="width:100%">
		  <tr>
			<td height="30" align="center" style="width:4%;"></td>
			<td height="30" align="center" style="font-weight: bolder; width:48%;"><div class="tabS"><a href="sum.php"><span style="color:<?echo $thColor;?>">원장별정산</span></a></div></td>
			<td height="30" align="center" style="width:48%;"><a href="daily.php"><span style="color:#FFFFFF">일일정산</span></a></td>
		  </tr>	
		</table>
	</div></td>
	<td style="width:30%"></td>
	<td id="img_date_input"><span style="color:#FFFFFF;font-weight:bolder;">◈ 일자검색</span>
	<input type="text" name="date_input" id="date_input" style="text-align:center;width:70px;" value="<?php echo $setTime?>" readonly />
	<input type="button" onclick="xajax_inquiry(xajax.$('date_input').value);" value="조회"></td>
	</tr></table>
		<div id="divtable" name="divtable" style="padding:4px;">
		</div>
		<div style="width:99%;" align="right">
			<input type="button" id="btn" name="btn" onclick="saveSum();" value="저장">
		</div>
	</div>
</div>
<div id="tt" name="tt"></div>
 </BODY>
</HTML>
