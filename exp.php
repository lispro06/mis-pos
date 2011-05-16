<?php
	include_once("exps.php");
	include_once("loging.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>수납관리 프로그램 - 지출부</title>
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
	$selSql2="select * from toto_expc WHERE `exps_code`='4000' order by `exps_name` asc";
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
		$output=$output.'"'.($no).'. '.$row2[3].'",';
	}
		$output=$output.'""];';
	if($_GET["exps_code"]==4001){
		echo '	var goods = ["01. 화장품","02. 잡비",""];';
	}else{
		echo $output;
	}
?>

	var exps_gubns = ["01. 현금","02. 통장","03. 카드(법인)","04. 카드(개인)",""];
</script>
<script>
// 달력 표시

$(document).ready(function() {
	//자동완성
	$("#exps_cate").autocomplete(goods,{
		autoFill: true,
		mustMatch: true,
		selectFirst: true,
		minChars: 0,
		matchContains: true,
		max: 40
	});
	$("#exps_gubn").autocomplete(exps_gubns,{
		autoFill: true,
		mustMatch: true,
		selectFirst: true,
		minChars: 0,
		matchContains: true,
		max: 40
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
					xajax_inquiry(xajax.$('exps_code').value, xajax.$('date_input').value, xajax.$('date_input').value);
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
		window.onload = function() {
			xajax_parent(<?php echo $_GET["exps_code"]?>);
		}
</script>
<div id="menu" name="menu">
</div>
<?php
	// output the xajax javascript. This must be called between the head tags
	$xajax->printJavascript();
?>
</head>
<body>
<div id="pdiv" style="position:absolute;top:100px;left:200px;width:500px;display:none;">preview</div>
<div class="exterior">
	<div class="tabA">
		<table border="0" style="text-align:center;width:100%;background-color:#F0575A" border="0" cellspacing="0" cellpadding="0"><tr>
		<td style="width:50%;">
			<div id="tabs2" name="tabs2" style="width:100%">
			</div>
		</td>
		<td style="width:20%"></td>
		<td id="img_date_input"><span style="color:#FFFFFF;font-weight:bolder;">◈ 일자검색 </span>
		<input type="text" name="date_input" id="date_input" style="text-align:center;width:70px;" readonly />
		<input type="button" onclick="xajax_inquiry(xajax.$('exps_code').value, xajax.$('date_input').value, xajax.$('date_input').value);" value="조회"></td>
		</tr></table>
		<form id="fr" name="fr">
		<div id="input" name="input" class="input">
		<?php echo $thead[4000].$tfoot[4000].'</table>';?>
		</div>
		</form>
		<br /><br />
		<div id="content" name="content" style="width:99%; height:100%; padding:4px; border:1 solid #000000;">
		</div>
		<div id="pv" name="pv" style="text-align:right;width:99%;display:none;">

		</div>
	</div>
</div>
		<div id="div2" style="overflow-y:auto; width:100%; height:50px; padding:4px; border:1 solid #000000;text-align:right;">&#160;</div>
<script src="exp.js" type="text/javascript"></script>
</body>
</html>