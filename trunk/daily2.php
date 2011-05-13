<?php
	include_once("menu.php");
	include_once("dailys2.php");
	include_once("loging.php");
	$cTime=time();
	$setTime=date("Y-m-d",$cTime);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<html>
<head>
	<title>수납관리 프로그램 - 일일정산</title>
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
		function ck_sum(){

<?php
// 피부과 통장 input box 계산 설정
	$selBI="SELECT * FROM `toto_bankbookinfo` where `use_flag` = '01'";
	$resBI = mysql_query($selBI, $connect);
	$totBI = mysql_num_rows($resBI); // 총 레코드 수
	$total = $totBI;
	while($totBI--){
		$skinNo++;
?>
			///////bkid 처리
			var sb<?php echo $skinNo;?>=xajax.$('skin_bkid_<?php echo $skinNo;?>').value;
			var si<?php echo $skinNo;?>=xajax.$('skin_in_<?php echo $skinNo;?>').value;
			var i<?php echo $skinNo;?>=si<?php echo $skinNo;?>.replace(/,/g,"");
			i<?php echo $skinNo;?>=ret_zero(i<?php echo $skinNo;?>);

			
			var so<?php echo $skinNo;?>=xajax.$('skin_out_<?php echo $skinNo;?>').value;
			var o<?php echo $skinNo;?>=so<?php echo $skinNo;?>.replace(/,/g,"");
			o<?php echo $skinNo;?>=ret_zero(o<?php echo $skinNo;?>);

			
			var sum<?php echo $skinNo;?>=xajax.$('skin_sum_<?php echo $skinNo;?>').value;
			var s_s<?php echo $skinNo;?>=sum<?php echo $skinNo;?>.replace(/,/g,"");
			ss<?php echo $skinNo;?>=parseInt(s_s<?php echo $skinNo;?>);
			ss<?php echo $skinNo;?>=ret_zero(ss<?php echo $skinNo;?>);//피부과1 잔액

<?
			$pI=$pI."parseInt(o".$skinNo.")";
			if($skinNo<$total)
				$pI=$pI."+";
			else
				$pI=$pI.";";
	}

			echo "\n			var sum=".$pI."\n";
// 코스메틱 통장 input box 계산 설정
	$selBI="SELECT * FROM `toto_bankbookinfo` where `use_flag` = '02'";
	$resBI = mysql_query($selBI, $connect);
	$totBI = mysql_num_rows($resBI); // 총 레코드 수
	$total = $totBI;
	$pI='';
	while($totBI--){
		$cosNo++;
?>
			///////bkid 처리
			var cb<?php echo $cosNo;?>=xajax.$('cos_bkid_<?php echo $cosNo;?>').value;
			var ci<?php echo $cosNo;?>=xajax.$('cos_in_<?php echo $cosNo;?>').value;
			var ci<?php echo $cosNo;?>=ci<?php echo $cosNo;?>.replace(/,/g,"");
			ci<?php echo $cosNo;?>=ret_zero(ci<?php echo $cosNo;?>);

			
			var cos<?php echo $cosNo;?>=xajax.$('cos_out_<?php echo $cosNo;?>').value;
			var co<?php echo $cosNo;?>=cos<?php echo $cosNo;?>.replace(/,/g,"");
			co<?php echo $cosNo;?>=ret_zero(co<?php echo $cosNo;?>);

			
			var csum<?php echo $cosNo;?>=xajax.$('cos_sum_<?php echo $cosNo;?>').value;
			var c_s<?php echo $cosNo;?>=csum<?php echo $cosNo;?>.replace(/,/g,"");
			cs<?php echo $cosNo;?>=parseInt(c_s<?php echo $cosNo;?>);
			cs<?php echo $cosNo;?>=ret_zero(cs<?php echo $cosNo;?>);//코스메틱1 잔액

<?
			$pI=$pI."parseInt(co".$cosNo.")";
			if($cosNo<$total)
				$pI=$pI."+";
			else
				$pI=$pI.";";
	}

			echo "\n			var csum=".$pI."\n";
?>
			var e_b=xajax.$('exp_bank').value;
			var e_c=xajax.$('exp_cos').value;
			var eb=e_b.replace(/,/g,"");
			var ec=e_c.replace(/,/g,"");
			
			eb=parseInt(eb);
			ec=parseInt(ec);
			eb=ret_zero(eb);//피부과 통장 출금액(from 지출부 입력)
			ec=ret_zero(ec);//코스메틱 통장 출금액(from 지출부 입력)
		
			if(eb==sum && csum==ec){
<?php
// 피부과 통장
	$selBI="SELECT * FROM `toto_bankbookinfo` where `use_flag` = '01'";
	$resBI = mysql_query($selBI, $connect);
	$totBI = mysql_num_rows($resBI); // 총 레코드 수
	$skinNo=0;
	while($totBI--){
		$rowBI=mysql_fetch_row($resBI);
		$skinNo++;
?>
				xajax_saveBank(sb<?php echo $skinNo?>,<?php echo $rowBI[0]?>,i<?php echo $skinNo?>,o<?php echo $skinNo?>,ss<?php echo $skinNo?>,xajax.$('date_input').value);
<?php
	}
?>
<?php
// 코스메틱 통장 저장 루틴
	$selBI="SELECT * FROM `toto_bankbookinfo` where `use_flag` = '02'";
	$resBI = mysql_query($selBI, $connect);
	$totBI = mysql_num_rows($resBI); // 총 레코드 수
	$cosNo=0;
	while($totBI--){
		$rowBI=mysql_fetch_row($resBI);
		$cosNo++;
?>
				xajax_saveBank(cb<?php echo $cosNo?>,<?php echo $rowBI[0]?>,ci<?php echo $cosNo?>,co<?php echo $cosNo?>,cs<?php echo $cosNo?>,xajax.$('date_input').value);
<?php
	}
?>
				xajax_save(xajax.$('no').value,xajax.$('d_afdy_mony').value,xajax.$('d_doct_mony').value,xajax.$('c_afdy_mony').value,xajax.$('c_doct_mony').value,xajax.$('date_input').value);
			}else{
				alert("통장 출금액이 일치하지 않습니다.");
			}
		}
		

		function cal_sum(){

<?php
	$skinNo=0;
	$cosNo=0;
	$pI='';
// 피부과 통장 input box 계산 설정
	$selBI="SELECT * FROM `toto_bankbookinfo` where `use_flag` = '01'";
	$resBI = mysql_query($selBI, $connect);
	$totBI = mysql_num_rows($resBI); // 총 레코드 수
	$total = $totBI;
	while($totBI--){
		$skinNo++;
?>
			///////입금 처리
			var si<?php echo $skinNo;?>=xajax.$('skin_in_<?php echo $skinNo;?>').value;
			var i<?php echo $skinNo;?>=si<?php echo $skinNo;?>.replace(/,/g,"");
			i<?php echo $skinNo;?>=ret_zero(i<?php echo $skinNo;?>);

			//지출 처리
			var so<?php echo $skinNo;?>=xajax.$('skin_out_<?php echo $skinNo;?>').value;
			var o<?php echo $skinNo;?>=so<?php echo $skinNo;?>.replace(/,/g,"");
			o<?php echo $skinNo;?>=ret_zero(o<?php echo $skinNo;?>);

<?
			$pI=$pI."parseInt(i".$skinNo.")";
			$pO=$pO."parseInt(o".$skinNo.")";
			if($skinNo<$total){
				$pI=$pI."+";
				$pO=$pO."+";
			}else{
				$pI=$pI.";";
				$pO=$pO.";";
			}
	}
			echo "\n			var isum1=".$pI."\n";
			echo "\n			var osum1=".$pO."\n";
// 코스메틱 통장 input box 계산 설정
	$pI='';
	$pO='';
	$selBI="SELECT * FROM `toto_bankbookinfo` where `use_flag` = '02'";
	$resBI = mysql_query($selBI, $connect);
	$totBI = mysql_num_rows($resBI); // 총 레코드 수
	$total = $totBI;
	while($totBI--){
		$cosNo++;
?>
			///////코스메틱 입금 처리
			var ci<?php echo $cosNo;?>=xajax.$('cos_in_<?php echo $cosNo;?>').value;
			var ci<?php echo $cosNo;?>=ci<?php echo $cosNo;?>.replace(/,/g,"");
			ci<?php echo $cosNo;?>=ret_zero(ci<?php echo $cosNo;?>);

			// 코스메틱 지출 처리
			var cos<?php echo $cosNo;?>=xajax.$('cos_out_<?php echo $cosNo;?>').value;
			var co<?php echo $cosNo;?>=cos<?php echo $cosNo;?>.replace(/,/g,"");
			co<?php echo $cosNo;?>=ret_zero(co<?php echo $cosNo;?>);

<?
			$pI=$pI."parseInt(ci".$cosNo.")";
			$pO=$pO."parseInt(co".$cosNo.")";
			if($cosNo<$total){
				$pI=$pI."+";
				$pO=$pO."+";
			}else{
				$pI=$pI.";";
				$pO=$pO.";";
			}
	}

			echo "\n			var isum2=".$pI."\n";
			echo "\n			var osum2=".$pO."\n";
			echo "\n			var pre_sum=xajax.$('pre_sum').value;\n";
			echo "\n			var p_s=pre_sum.replace(/,/g,'');\n";
?>
			document.getElementById('sum_in').value=comma(isum1+isum2);
			document.getElementById('sum_out').value=comma(osum1+osum2);
			document.getElementById('sum_sum').value=comma(parseInt(p_s)+isum1+isum2-osum1-osum2);
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
		<div id="divtable" name="divtable" style="padding:10px;background-color:<?echo $tabColor;?>">
		</div>
		<div style="width:99%;" align="right">
		<input type="text" id="no" name="no" style="display:none;">
		<input type="button" onclick="ck_sum()" id="btn" name="btn" value="저장"><input type="button" id="end" name="end" disabled="true" value="일마감요청" onclick="xajax_endBt(xajax.$('date_input').value);" style="display:none;">
		</div>
	</div>
</div>
<div id="tt" name="tt"></div>
<script src="daily2.js" type="text/javascript"></script>
</body>
</html>
