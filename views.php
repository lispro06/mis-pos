<?php
	
/*
	File: helloworld.php

	Test / example page demonstrating the basic xajax implementation.
	
	Title: Hello world sample page.
	
	Please see <copyright.inc.php> for a detailed description, copyright
	and license information.
*/

/*
	@package xajax
	@version $Id: helloworld.php 362 2007-05-29 15:32:24Z calltoconstruct $
	@copyright Copyright (c) 2005-2006 by Jared White & J. Max Wilson
	@license http://www.xajaxproject.org/bsd_license.txt BSD License
*/

/*
	Section: Standard xajax startup
	
	- include <xajax.inc.php>
	- instantiate main <xajax> object
*/
require ('./xajax_core/xajax.inc.php');
$xajax = new xajax();

/*
	- enable deubgging if desired
	- set the javascript uri (location of xajax js files)
*/
//$xajax->configure('debug', true);
$xajax->configure('javascript URI', './');



/*
	Function: end
	
	end
*/
function endBt($date_input)
{
	global $connect;
	$objResponse = new xajaxResponse();

	$date_input=str_replace("-","",$date_input);// '-' 제거
	$query="UPDATE `toto_closedsale` SET `clos_chck` = 'Y' WHERE `reg_date`='".$date_input."';";
    $saveQue = mysql_query($query, $connect);;
	$objResponse->assign('end', 'disabled', true);
	
	return $objResponse;
}
/*
	Function: selDate
	
	select
*/
function selDate($date_input)
{
	global $connect, $tableD;
	$objResponse = new xajaxResponse();
	$reg_date=$date_input;
	$date_input=str_replace("-","",$date_input);// '-' 제거

	$selSql="SELECT * FROM `toto_closedsale` where `reg_date` = ". $date_input;
	$result = mysql_query($selSql, $connect); 
	$rows = mysql_fetch_row($result);
	$rowc=0;
	$year=substr($date_input,0,4);
	$month=substr($date_input,4,2);
	$day=substr($date_input,6,2);
	$foredate=date("Ymd",mktime( 0, 0, 0, $month, $day-1, $year ));
	$selSqlC="SELECT * FROM `toto_closedsale` where `reg_date` = ".$foredate;
	$resultC = mysql_query($selSqlC, $connect);
	$rowc = mysql_fetch_row($resultC);

	$updated=$tableD.'
	  <tr>
		<td colspan="2" class="style2">계정과목</td>
		<td class="style2">피부과</td>
		<td class="style2">코스메틱</td>
		<td class="style2">합계</td>
	  </tr>
	  <tr>
		<td colspan="2" class="style2" align="center">전일이월</td>
		<td class="cashStyle">'.comma($rowc[1]).'</td>
		<td class="cashStyle">'.comma($rowc[15]).'</td>
		<td class="cashStyle">'.comma($rowc[1]+$rowc[15]).'</td>
	  </tr>
	  <tr>
		<td rowspan="6" class="style2" align="center" width="100">현금</td>
		<td width="100" class="style2">일반</td>
		<td class="cashStyle">'.comma($rows[2]).'</td>
		<td class="cashStyle">'.comma($rows[16]).'</td>
		<td class="cashStyle">'.comma($rows[2]+$rows[16]).'</td>
	  </tr>
	  <tr>
		<td><div align="left" class="style2">현금영수증</div></td>
		<td class="cashStyle">'.comma($rows[4]).'</td>
		<td class="cashStyle">'.comma($rows[17]).'</td>
		<td class="cashStyle">'.comma($rows[4]+$rows[17]).'</td>
	  </tr>
	  <tr>
		<td class="style2">보험</div></td>
		<td class="cashStyle">'.comma($rows[3]).'</td>
		<td class="cashStyle">-</td>
		<td class="cashStyle">'.comma($rows[3]).'</td></td>
	  </tr>
	  <tr>
		<td class="style2">보험현금영수증</td>
		<td class="cashStyle">'.comma($rows[5]).'</td>
		<td class="cashStyle">-</td>
		<td class="cashStyle">'.comma($rows[5]).'</td>
	  </tr>
	  <tr>
		<td class="style2">소계</td></td>
		<td class="style4">'.comma($rows[2]+$rows[3]+$rows[4]+$rows[5]).'</td>
		<td class="style4">'.comma($rows[16]+$rows[17]).'</td>
		<td class="style4">'.comma($rows[2]+$rows[3]+$rows[4]+$rows[5]+$rows[16]+$rows[17]).'</td>
	  </tr>
	  <tr>
		<td class="style2">외상회수</td>
		<td class="cashStyle">-</td>
		<td class="cashStyle">-</td>
		<td class="cashStyle">-</td>
	  </tr>
	  <tr>
		<td colspan="2" class="style2" align="center">현금수입계</td>
		<td class="cashStyle">'.comma($rows[2]+$rows[3]+$rows[4]+$rows[5]).'</td>
		<td class="cashStyle">'.comma($rows[16]+$rows[17]).'</td>
		<td class="cashStyle">'.comma($rows[2]+$rows[3]+$rows[4]+$rows[5]+$rows[16]+$rows[17]).'</td>
	  </tr>
	  <tr>
		<td rowspan="5" align="center" class="style2">지출</td>
		<td class="style2">현금일반</td>
		<td class="cashStyle">'.comma($rows[11]).'</td>
		<td class="cashStyle">'.comma($rows[22]).'</td>
		<td class="cashStyle">'.comma($rows[11]+$rows[22]).'</td>
	  </tr>
	  <tr>
		<td class="style2">통장출금</td>
		<td class="cashStyle">'.comma($rows[12]).'</td>
		<td class="cashStyle">'.comma($rows[23]).'</td>
		<td class="cashStyle">'.comma($rows[12]+$rows[23]).'</td>
	  </tr>
		<td class="style2">카드(법인)</td>
		<td class="cashStyle">'.comma($rows[13]).'</td>
		<td class="cashStyle">'.comma($rows[24]).'</td>
		<td class="cashStyle">'.comma($rows[13]+$rows[24]).'</td>
	  </tr>
		<td class="style2">카드(개인)</td>
		<td class="cashStyle">'.comma($rows[14]).'</td>
		<td class="cashStyle">'.comma($rows[25]).'</td>
		<td class="cashStyle">'.comma($rows[14]+$rows[25]).'</td>
	  </tr>
	  <tr>
		<td class="style2">지출계</td>
		<td class="style4">'.comma($rows[11]+$rows[12]+$row[13]+$row[14]).'</td>
		<td class="style4">'.comma($rows[22]+$rows[23]+$row[24]+$row[25]).'</td>
		<td class="style4">'.comma($rows[11]+$rows[12]+$row[13]+$row[14]+$rows[22]+$rows[23]+$row[24]+$row[25]).'</td>
	  </tr>
	  <tr>
		<td colspan="2" class="style2" align="center">원장인출</td>
		<td class="cashStyle">'.comma($rows[10]).'</td>
		<td class="cashStyle">'.comma($rows[21]).'</td>
		<td class="cashStyle">'.comma($rows[10]+$rows[21]).'</td>
	  </tr>
	  <tr>
		<td colspan="2" class="style2" align="center">차일이월</td>
		<td class="cashStyle">'.comma($rows[1]).'</td>
		<td class="cashStyle">'.comma($rows[15]).'</td>
		<td class="cashStyle">'.comma($rows[1]+$rows[15]).'</td>
	  </tr>
	  <tr>
		<td rowspan="4" align="center" class="style2">카드</td>
		<td><div align="left" class="style2">일반</div></td>
		<td class="cashStyle">'.comma($rows[6]).'</td>
		<td class="cashStyle">'.comma($rows[16]).'</td>
		<td class="cashStyle">'.comma($rows[6]+$rows[16]).'</td>
	  </tr>
	  <tr>
		<td class="style2">보험</td>
		<td class="cashStyle">'.comma($rows[7]).'</td>
		<td class="cashStyle">-</td>
		<td class="cashStyle">'.comma($rows[7]).'</td>
	  </tr>
	  <tr>
		<td class="style2">소계</td>
		<td class="cashStyle">'.comma($rows[6]+$rows[7]).'</td><!--카드일반+카드보험-->
		<td class="cashStyle">'.comma($rows[18]).'</td>
		<td class="cashStyle">'.comma($rows[6]+$rows[7]+$rows[18]).'</td>
	  </tr>
	  <tr>
		<td><div align="left" class="style2">외상회수</div></td>
		<td class="cashStyle">-</td>
		<td class="cashStyle">-</td>
		<td class="cashStyle">-</td>
	  </tr>
	  <tr>
		<td colspan="2" align="center" class="style2">통장입금</td>
		<td class="cashStyle">'.comma($rows[8]).'</td>
		<td class="cashStyle">'.comma($rows[19]).'</td>
		<td class="cashStyle">'.comma($rows[8]+$rows[19]).'</td>
	  </tr>
	  <tr>
		<td colspan="2" align="center" class="style2">매출환불(통장)</td>
		<td class="cashStyle">'.comma($rows[9]).'</td>
		<td class="cashStyle">'.comma($rows[20]).'</td>
		<td class="cashStyle">'.comma($rows[9]+$rows[20]).'</td>
	  </tr>
	  <tr>
		<td colspan="2" align="center" class="style2">매출총계</td>
		<td class="style4">'.comma($rows[2]+$rows[3]+$rows[4]+$rows[5]+$rows[6]+$rows[7]+$rows[8]+$row[9]+$row[10]).'</td>
		<td class="style4">'.comma($rows[16]+$rows[17]+$rows[18]+$rows[19]+$row[20]).'</td>
		<td class="style4">'.comma($rows[2]+$rows[3]+$rows[4]+$rows[5]+$rows[6]+$rows[7]+$rows[8]+$row[9]+$row[10]+$rows[16]+$rows[17]+$rows[18]+$rows[19]+$row[20]).'</td>
	  </tr>
	</table>';
	
	if($rows[30]=="Y"){
		$objResponse->assign('end', 'disabled', TRUE);
		$objResponse->assign('end', 'value', '일마감완료');
	}else if($rowc[30]!="Y"){
		$objResponse->assign('end', 'disabled', TRUE);
		$objResponse->assign('end', 'value', '이전 근무일 마감 필요');
	}else if($rows[29]=="Y"){
		$objResponse->assign('end', 'disabled', FALSE);
		$objResponse->assign('end', 'value', '일마감');
	}else{
		$objResponse->assign('end', 'disabled', TRUE);
		$objResponse->assign('end', 'value', '일마감(요청)전');
	}

	$msg=$_SESSION['sunap']."님은 ".$reg_date."일자 자료입력 중입니다.";
	$objResponse->assign('msgDiv', 'innerHTML', $msg);
	$objResponse->assign('selDate', 'value', $reg_date);
	$objResponse->assign('divtable', 'innerHTML', $updated);
	
	return $objResponse;
}
/*
	Function: endView
	
	endView
*/
function endView($end_gubn)
{
	global $connect, $tableD;
	$objResponse = new xajaxResponse();

	switch($end_gubn){
		case 0 :
			$selSql="SELECT * FROM `toto_closedsale` where `ask__chck` = 'Y' and `clos_chck` = '' order by `no` asc";
		break;
		case 1 :
			$selSql="SELECT * FROM `toto_closedsale` where `ask__chck` = 'Y' and `clos_chck` = 'Y' order by `no` asc";
		break;
		case 2 :
			$selSql="SELECT * FROM `toto_closedsale` where `ask__chck` = '' and `clos_chck` = '' order by `no` asc";
		break;
	}
	$result = mysql_query($selSql, $connect); 
	
	$total = mysql_num_rows($result); // 총 레코드 수
	$num=$total;
$list=$tableD.'
  <tr>
    <td class="style2">일자</td>
    <td class="style2">전일이월</td>
    <td class="style2">현금</td>
    <td class="style2">현금영수증</td>
    <td class="style2">카드</td>
    <td class="style2">외상매출</td>
    <td class="style2">매출환불</td>
    <td class="style2">원장인출</td>
    <td class="style2">차일이월</td>
    <td class="style2">현금지출</td>
    <td class="style2">통장지출</td>
    <td class="style2">카드(법인)</td>
    <td class="style2">카드(개인)</td>
    <td class="style2">피부과매출</td>
    <td class="style2">상태</td>
  </tr>';
	$pre=0;
while($total--){
	$rows = mysql_fetch_row($result);

	$reg_date=substr($rows[31],0,4);
	$reg_date=$reg_date."-".substr($rows[31],4,2);
	$reg_date=$reg_date."-".substr($rows[31],6,2);
	if($rows[30]=="Y"){
		$status="마감완료";
	}else if($rows[29]=="Y"){
		$status="마감요청";
	}else{
		$status="작성중";
	}

	$year=substr($rows[31],0,4);
	$month=substr($rows[31],4,2);
	$day=substr($rows[31],6,2);
	$foredate=date("Ymd",mktime( 0, 0, 0, $month, $day-1, $year ));

	$selSqlP="SELECT `d_afdy_mony` FROM `toto_closedsale` where `reg_date`='".$foredate."'";
	$resultP = mysql_query($selSqlP, $connect);
	$rowP = mysql_fetch_row($resultP);

  $list=$list.'<tr>
    <td class="style2"><a href="#" onclick=xajax_selDate("'.$reg_date.'")>'.$reg_date.'</td>
    <td class="tdStyle">'.comma($rowP[0]).'</td>
    <td class="tdStyle">'.comma($rows[2]+$rows[3]+$rows[16]).'</td><!--현금일반+현금보험+현금코스메틱-->
    <td class="tdStyle">'.comma($rows[4]+$rows[5]+$rows[17]).'</td><!--현금영수증일반+현금영수증보험+현금영수증코스메틱-->
    <td class="tdStyle">'.comma($rows[6]+$rows[7]+$rows[18]).'</td><!--카드일반+카드보험+카드코스메틱-->
    <td class="tdStyle">-</td><!--피부과 외상입금+코스메틱 외상입금-->
    <td class="tdStyle">'.comma($rows[9]+$rows[20]).'</td>
    <td class="tdStyle">'.comma($rows[10]+$rows[21]).'</td><!--피부과원장인출+코스메틱원장인출-->
    <td class="tdStyle">'.comma($rows[1]+$rows[17]).'</td><!--피부과이월+코스메틱이월-->
    <td class="tdStyle">'.comma($rows[11]+$rows[22]).'</td>
    <td class="tdStyle">'.comma($rows[12]+$rows[23]).'</td>
    <td class="tdStyle">'.comma($rows[13]).'</td>
    <td class="tdStyle">'.comma($rows[14]).'</td>
    <td class="tdStyle">'.comma($rows[2]+$rows[3]+$rows[4]+$rows[5]+$rows[6]+$rows[7]+$rows[8]+$rows[9]).'</td>
    <td class="tdStyle">'.$status.'</td>
  </tr>';
  	$pre=$rows[1];
}

$list=$list.'</table>';


	$updated=$tableD.'
	  <tr>
		<td colspan="2" class="style2">계정과목</td>
		<td class="style2">피부과</td>
		<td class="style2">코스메틱</td>
		<td class="style2">합계</td>
	  </tr>
	  <tr>
		<td colspan="2" class="style2">전일이월</td>
		<td class="cashStyle">'.comma($rowP[0]).'</td>
		<td class="cashStyle">'.comma($rowc[15]).'</td>
		<td class="cashStyle">'.comma($rowc[1]+$rowc[15]).'</td>
	  </tr>
	  <tr>
		<td rowspan="6" class="style2" width="100">현금</td>
		<td width="100" class="style2">일반</td>
		<td class="cashStyle">'.comma($rows[2]).'</td>
		<td class="cashStyle">'.comma($rows[16]).'</td>
		<td class="cashStyle">'.comma($rows[2]+$rows[16]).'</td>
	  </tr>
	  <tr>
		<td class="style2">현금영수증</td>
		<td class="cashStyle">'.comma($rows[4]).'</td>
		<td class="cashStyle">'.comma($rows[17]).'</td>
		<td class="cashStyle">'.comma($rows[4]+$rows[17]).'</td>
	  </tr>
	  <tr>
		<td class="style2">보험</div></td>
		<td class="cashStyle">'.comma($rows[3]).'</td>
		<td class="cashStyle">-</td>
		<td class="cashStyle">'.comma($rows[3]).'</td></td>
	  </tr>
	  <tr>
		<td class="style2">보험현금영수증</td>
		<td class="cashStyle">'.comma($rows[5]).'</td>
		<td class="cashStyle">-</td>
		<td class="cashStyle">'.comma($rows[5]).'</td>
	  </tr>
	  <tr>
		<td class="style2">소계</td></td>
		<td class="style4">'.comma($rows[2]+$rows[3]+$rows[4]+$rows[5]).'</td>
		<td class="style4">'.comma($rows[16]+$rows[17]).'</td>
		<td class="style4">'.comma($rows[2]+$rows[3]+$rows[4]+$rows[5]+$rows[16]+$rows[17]).'</td>
	  </tr>
	  <tr>
		<td class="style2">외상회수</td>
		<td class="cashStyle">-</td>
		<td class="cashStyle">-</td>
		<td class="cashStyle">-</td>
	  </tr>
	  <tr>
		<td colspan="2" class="style2" align="center">현금수입계</td>
		<td class="cashStyle">'.comma($rows[2]+$rows[3]+$rows[4]+$rows[5]).'</td>
		<td class="cashStyle">'.comma($rows[16]+$rows[17]).'</td>
		<td class="cashStyle">'.comma($rows[2]+$rows[3]+$rows[4]+$rows[5]+$rows[16]+$rows[17]).'</td>
	  </tr>
	  <tr>
		<td rowspan="5" class="style2">지출</td>
		<td class="style2">현금일반</td>
		<td class="cashStyle">'.comma($rows[11]).'</td>
		<td class="cashStyle">'.comma($rows[22]).'</td>
		<td class="cashStyle">'.comma($rows[11]+$rows[22]).'</td>
	  </tr>
	  <tr>
		<td class="style2">통장출금</td>
		<td class="cashStyle">'.comma($rows[12]).'</td>
		<td class="cashStyle">'.comma($rows[23]).'</td>
		<td class="cashStyle">'.comma($rows[12]+$rows[23]).'</td>
	  </tr>
		<td class="style2">카드(법인)</td>
		<td class="cashStyle">'.comma($rows[13]).'</td>
		<td class="cashStyle">'.comma($rows[24]).'</td>
		<td class="cashStyle">'.comma($rows[13]+$rows[24]).'</td>
	  </tr>
		<td class="style2">카드(개인)</td>
		<td class="cashStyle">'.comma($rows[14]).'</td>
		<td class="cashStyle">'.comma($rows[25]).'</td>
		<td class="cashStyle">'.comma($rows[14]+$rows[25]).'</td>
	  </tr>
	  <tr>
		<td class="style2">지출계</td>
		<td class="style4">'.comma($rows[11]+$rows[12]+$row[13]+$row[14]).'</td>
		<td class="style4">'.comma($rows[22]+$rows[23]+$row[24]+$row[25]).'</td>
		<td class="style4">'.comma($rows[11]+$rows[12]+$row[13]+$row[14]+$rows[22]+$rows[23]+$row[24]+$row[25]).'</td>
	  </tr>
	  <tr>
		<td colspan="2" class="style2">원장인출</td>
		<td class="cashStyle">'.comma($rows[10]).'</td>
		<td class="cashStyle">'.comma($rows[21]).'</td>
		<td class="cashStyle">'.comma($rows[10]+$rows[21]).'</td>
	  </tr>
	  <tr>
		<td colspan="2" class="style2">차일이월</td>
		<td class="cashStyle">'.comma($rows[1]).'</td>
		<td class="cashStyle">'.comma($rows[15]).'</td>
		<td class="cashStyle">'.comma($rows[1]+$rows[15]).'</td>
	  </tr>
	  <tr>
		<td rowspan="4" class="style2">카드</td>
		<td><div align="left" class="style2">일반</div></td>
		<td class="cashStyle">'.comma($rows[6]).'</td>
		<td class="cashStyle">'.comma($rows[16]).'</td>
		<td class="cashStyle">'.comma($rows[6]+$rows[16]).'</td>
	  </tr>
	  <tr>
		<td class="style2">보험</td>
		<td class="cashStyle">'.comma($rows[7]).'</td>
		<td class="cashStyle">-</td>
		<td class="cashStyle">'.comma($rows[7]).'</td>
	  </tr>
	  <tr>
		<td class="style2">소계</td>
		<td class="cashStyle">'.comma($rows[6]+$rows[7]).'</td><!--카드일반+카드보험-->
		<td class="cashStyle">'.comma($rows[18]).'</td>
		<td class="cashStyle">'.comma($rows[6]+$rows[7]+$rows[18]).'</td>
	  </tr>
	  <tr>
		<td><div class="style2">외상회수</div></td>
		<td class="cashStyle">-</td>
		<td class="cashStyle">-</td>
		<td class="cashStyle">-</td>
	  </tr>
	  <tr>
		<td colspan="2" class="style2">통장입금</td>
		<td class="cashStyle">'.comma($rows[8]).'</td>
		<td class="cashStyle">'.comma($rows[19]).'</td>
		<td class="cashStyle">'.comma($rows[8]+$rows[19]).'</td>
	  </tr>
	  <tr>
		<td colspan="2" class="style2">매출환불(통장)</td>
		<td class="cashStyle">'.comma($rows[9]).'</td>
		<td class="cashStyle">'.comma($rows[20]).'</td>
		<td class="cashStyle">'.comma($rows[9]+$rows[20]).'</td>
	  </tr>
	  <tr>
		<td colspan="2" class="style2">매출총계</td>
		<td class="style4">'.comma($rows[2]+$rows[3]+$rows[4]+$rows[5]+$rows[6]+$rows[7]+$rows[8]+$row[9]+$row[10]).'</td>
		<td class="style4">'.comma($rows[16]+$rows[17]+$rows[18]+$rows[19]+$row[20]).'</td>
		<td class="style4">'.comma($rows[2]+$rows[3]+$rows[4]+$rows[5]+$rows[6]+$rows[7]+$rows[8]+$row[9]+$row[10]+$rows[16]+$rows[17]+$rows[18]+$rows[19]+$row[20]).'</td>
	  </tr>
	</table>';
	$selSql="SELECT `clos_chck` FROM `toto_closedsale` where `no`=".($rows[0]-1);
	$result = mysql_query($selSql, $connect);
	$row0 = mysql_fetch_row($result);
	if($row0[0]!="Y"){
		$objResponse->assign('end', 'disabled', TRUE);
		$objResponse->assign('end', 'value', '이전 근무일 마감 필요');
	}
	else if($rows[30]=="Y"){
		$objResponse->assign('end', 'disabled', TRUE);
		$objResponse->assign('end', 'value', '일마감완료');
	}else if($rows[29]=="Y"){
		$objResponse->assign('end', 'disabled', FALSE);
		$objResponse->assign('end', 'value', '일마감');
	}
	$msg=$_SESSION['sunap']."님은 ".$reg_date."일자 자료입력 중입니다.";
	$objResponse->assign('msgDiv', 'innerHTML', $msg);
	$objResponse->assign('divtable', 'innerHTML', $updated);
	$objResponse->assign('selDate', 'value', $reg_date);
	$objResponse->assign('wlist', 'innerHTML', $list);
	return $objResponse;
}



$reqEndView =& $xajax->registerFunction('endView');
$reqSelDate =& $xajax->registerFunction('selDate');
$reqEndBt =& $xajax->registerFunction('endBt');

/*
	Section: processRequest
	
	This will detect an incoming xajax request, process it and exit.  If this is
	not a xajax request, then it is a request to load the initial contents of the page
	(HTML).
	
	Everything prior to this statement will be executed upon each request (whether it
	is for the initial page load or a xajax request.  Everything after this statement
	will be executed only when the page is first loaded.
*/
$xajax->processRequest();

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>