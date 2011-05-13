<?php
	include_once("function.php");
	include_once("loging.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php echo $hospital;?> - 수입부</title>
<link rel="shortcut icon" href="favicon.ico">
<link href="style.css" rel="stylesheet" type="text/css" />
<SCRIPT type="text/javascript" src="./jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="jquery-1.4.1.js"></script>
<script type="text/javascript" src="jquery-ui.js"></script>
<script type="text/javascript" src="./jquery/jquery.blockUI.js"></script>
<script type="text/javascript" src="./jquery/jquery.validate.js"></script>
<link rel="stylesheet" type="text/css" href="./autocomplete/jquery.autocomplete.css"/>
<script type="text/javascript" src="./autocomplete/jquery.autocomplete.js"></script>
<script>
<?php
	$selSql2="select * from toto_payc order by `rmst_name` asc";
	$result2 = mysql_query($selSql2, $connect); 
	$total = mysql_num_rows($result2); // 총 레코드 수
	$num=$total;
	$output='	var goods = [';

	while($total--){
		if($num-$total<10)
			$no='0'.($num-$total);
		else
			$no=$num-$total;
		$row2 = mysql_fetch_row($result2);
		$output=$output.'"'.($no).'. '.$row2[2].'",';
	}
		$output=$output.'""];';
	echo $output;
?>

	var slit_codes = ["01. 매출","02. 외상입금","03. 환불",""];
	var insu_codes = ["01. 보험","02. 일반",""];
//	동적 진료실 구현 2011-03-31
<?php
	$docSql="select * from toto_doctor order by `no` asc";
	$docRes = mysql_query($docSql, $connect); 
	$docTot = mysql_num_rows($docRes); // 총 레코드 수
	$docNum=$docTot;
	$output='	var rmdy_docts = [';

	while($docTot--){
		if($docNum-$docTot<10)
			$dno='0'.($docNum-$docTot);
		else
			$dno=$docNum-$docTot;
		$docRow = mysql_fetch_row($docRes);
		$output=$output.'"'.($dno).'. '.$docRow[2].'",';
	}
		$output=$output.'""];';
	echo $output;
?>

</script>
<script>
		window.onload = function() {
			xajax_parent(<?php echo $_GET["sale_code"]?>);
		}
// 달력 표시
$(document).ready(function() {
	//자동완성
	$("#slit_code").autocomplete(slit_codes,{
		autoFill: true,
		mustMatch: true,
		selectFirst: true,
		minChars: 0,
		matchContains: true,
		max: 30
	});
	$("#insu_code").autocomplete(insu_codes,{
		autoFill: true,
		mustMatch: true,
		selectFirst: true,
		minChars: 0,
		matchContains: true,
		max: 30
	});
	$("#rmst_code").autocomplete(goods,{
		autoFill: true,
		mustMatch: true,
		selectFirst: true,
		minChars: 0,
		matchContains: true,
		max: 30
	});
	$("#rmdy_doct").autocomplete(rmdy_docts,{
		autoFill: true,
		mustMatch: true,
		selectFirst: true,
		minChars: 0,
		matchContains: true,
		max: 30
	});
    // 달력 - 입력일
    $('#date_input').datepicker({
        dateFormat: 'yy-mm-dd',
        onSelect: function (dateText, inst) {
<?php
	
	$cTime=time();
	$setTime=date("Y-m-d",$cTime);

?>
            var sEndDate = jQuery.trim('<?php echo $setTime;?>');
            if (sEndDate.length>0) {
                var iEndDate   = parseInt(sEndDate.replace(/-/g, ''));
                var iStartDate = parseInt(jQuery.trim(dateText).replace(/-/g, ''));
                
                if (iStartDate>iEndDate) {
                    alert('오늘 이전 일자를 선택하세요.');
					$('#date_input').val('<?php echo $setTime;?>');
                }
				else{
					xajax_inquiry(xajax.$('sale_code').value, xajax.$('date_input').value, xajax.$('date_input').value);
				}
            }
        }
    });
    $('#img_date_input').click(function() {$('#date_input').focus();});
    // 달력 - 시작일
    $('#date_start').datepicker({
        dateFormat: 'yy-mm-dd',
        onSelect: function (dateText, inst) {
            var sEndDate = jQuery.trim($('#date_end').val());
            if (sEndDate.length>0) {
                var iEndDate   = parseInt(sEndDate.replace(/-/g, ''));
                var iStartDate = parseInt(jQuery.trim(dateText).replace(/-/g, ''));
                
                if (iStartDate>iEndDate) {
                    alert('시작일보다 종료일이 과거일 수 없습니다.');
                    $('#date_start').val('');
                }
            }
        }
    });
    $('#img_date_start').click(function() {$('#date_start').focus();});
    // 달력 - 종료일
    $('#date_end').datepicker({
        dateFormat: 'yy-mm-dd',
        onSelect: function (dateText, inst) {
            var sStartDate = jQuery.trim($('#date_start').val());
            if (sStartDate.length>0) {
                var iStartDate = parseInt(sStartDate.replace(/-/g, ''));
                var iEndDate  = parseInt(jQuery.trim(dateText).replace(/-/g, ''));
                
                if (iStartDate>iEndDate) {
                    alert('시작일보다 종료일이 과거일 수 없습니다.');
                    $('#date_end').val('');
                }
            }
        }
    });
    $('#img_date_end').click(function() {$('#date_end').focus();});
});
</script>
<?php
	// output the xajax javascript. This must be called between the head tags
	$xajax->printJavascript();
?>
</head>
<body>
<div id="pdiv" style="position:absolute;top:100px;left:200px;width:400px;display:none;">preview</div>
<div id="menu" name="menu" style="width:100%">
</div>
<div class="exterior">
	<div class="tabA">
		<table border="0" style="text-align:center;width:100%;background-color:<?echo $extColor;?>" border="0" cellspacing="0" cellpadding="0"><tr>
		<td style="width:50%;">
			<div id="tabs2" name="tabs2" style="width:100%">
			</div>
		</td>
		<td style="width:20%"></td>
		<td id="img_date_input"><span style="color:#FFFFFF;font-weight:bolder;">◈ 일자검색 </span><input type="text" name="date_input" id="date_input" style="text-align:center;width:70px;" readonly /><input type="button" onclick="xajax_inquiry(xajax.$('sale_code').value, xajax.$('date_input').value, xajax.$('date_input').value);" value="조회"></td>
		</tr></table>
		<form id="fr" name="fr">
		<div class="input" id="input" name="input">
		<?php echo $tfoot[$_GET["sale_code"]];?>
		</div>
		<div id="div2" style="overflow-y:auto; width:99%;padding:4px; border:1 solid #000000;display:block;text-align:right;"></div>
		</form><br /><br />
<?php
	if($acc['all']){
?>
		<div id="pv" name="pv" style="text-align:right;width:99%;display:none;">
		<input type="button" value="집계미리보기" onClick='xajax_preview(xajax.$("date_input").value,xajax.$("date_input").value);'>
		</div>
<?
	}
?>
		<div id="content" name="content" style="width:99%; height:100%; padding:4px; border:1 solid #000000;">
		</div>
	</div>
</div>
<script src="script.js" type="text/javascript"></script>
</body>
</html>