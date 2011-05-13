<?php
// db관련 파일 인크루드 
	include "conf.php";
	$connect = mysql_connect("localhost", $dbname, $dbpass); 
	$result=mysql_select_db($dbname, $connect);
	if ( !$connect ) { 
		echo " 데이터베이스에 연결할 수 없습니다."; 
	}
    mysql_query("set session character_set_connection=utf8;");
    mysql_query("set session character_set_results=utf8;");
    mysql_query("set session character_set_client=utf8;");
	//해당 월만 검색하는 query
	
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
	Function: savePay
	
	save
*/
function save($no,$rmdy_doct,$bfor_mony,$aftr_mony,$gner_cont,$insu_cont,$reg_date)
{
	global $connect;
	$objResponse = new xajaxResponse();
	$c_time=time();
	$reg_date=str_replace("-","",$reg_date);// '-' 제거
	$bfor_mony=str_replace(",","",$bfor_mony);// ',' 제거
	$aftr_mony=str_replace(",","",$aftr_mony);// ',' 제거
	if($no==""){
		$query="INSERT INTO `toto_balancedoctor` ( `no` , `rmdy_doct` , `bfor_mony` , `aftr_mony` , `gner_cont` , `insu_cont` , `reg_date` , `date` ) VALUES ('', '".$rmdy_doct."', '".$bfor_mony."', '".$aftr_mony."', '".$gner_cont."', '".$insu_cont."', '".$reg_date."', '".$c_time."');";
	}else{
		$query="UPDATE `toto_balancedoctor` SET `rmdy_doct` = '".$rmdy_doct."', `bfor_mony` = '".$bfor_mony."', `aftr_mony` = '".$aftr_mony."', `gner_cont` = '".$gner_cont."',`insu_cont` = '".$insu_cont."', `reg_date` = '".$reg_date."', `date` = '".$c_time."' WHERE `no` =".$no;
		$msg=date("Y-M-D H:i:s",time());
		$objResponse->assign('tt', 'innerHTML', '수정되었습니다. '.$msg);
	}
    $saveQue = mysql_query($query, $connect);;
	$objResponse->assign('btn', 'disabled', "true");
//	$objResponse->assign('tt', 'innerHTML', $query);
//	$objResponse->call('confirm_entry()');
	
	return $objResponse;
}
/*
	Function: inquiry
	
	inquiry
*/
function inquiry($date_input)
{
	global $connect, $tableD;
	$objResponse = new xajaxResponse();
	$setTime=$date_input;
	$date_input=str_replace("-","",$date_input);// '-' 제거
	session_register("reg_date");
	$_SESSION['session_reg_date']=$date_input;

	$selSqlc="SELECT * FROM `toto_closedsale` where `reg_date` = ". $date_input;
	$result = mysql_query($selSqlc, $connect);
	$rows = mysql_fetch_row($result);
	$close = mysql_num_rows($result);


	$updated=$tableD.'
   <tr>
     <td class="style2">진료실</td>
     <td class="style2">정산표상금액'.$rowb[2].'</td>
     <td class="style2">원장님별정산'.$rowb[3].'</td>
     <td class="style2">일반고객수'.$rowb[4].'</td>
     <td class="style2">보험고객수'.$rowb[5].'</td>
   </tr>';


	// 병원이름 출력 2011-03-31
	$docSql="select * from toto_doctor order by `no` asc";
	$docRes = mysql_query($docSql, $connect); 
	$docTot = mysql_num_rows($docRes); // 총 레코드 수
	$docNum=$docTot;
	$docNo=0;	// 출력 번호
while($docTot--){
	$docNo++;
/// 저장 여부 확인 2011-04-01
	$docRow = mysql_fetch_row($docRes);
	$selSqlb="SELECT * FROM `toto_balancedoctor` where `reg_date` = '".$date_input."' and `rmdy_doct` =  '".$docRow[1]."'";
	$resultb = mysql_query($selSqlb, $connect);
	$closeb = mysql_num_rows($resultb);
	$rowb = mysql_fetch_row($resultb);

/// 각 필드의 합
	$t_rowb[5]=$rowb[5]+$t_rowb[5];
	$t_rowb[4]=$rowb[4]+$t_rowb[4];
	$t_rowb[3]=$rowb[3]+$t_rowb[3];
	$t_rowb[2]=$rowb[2]+$t_rowb[2];

		$selSql="select `cash_mony`,`cscd_mony`,`card_mony`,`yet__mony` from toto_pay where `reg_date` between '".$date_input."' and '".$date_input."' and `rmdy_doct` =  '".$docRow[1]."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$num+$total;//최소한의 자료가 있는지 확인(원장 한명의 자료라도 확인 되면 $num은 1보다 크다)
		while($total--){
			$row0 = mysql_fetch_row($result);
			$sum=$sum+$row0[0]+$row0[1]+$row0[2]+$row0[3];//정산 금액
		}
			$sums=$sum+$sums;
		$selSql="select count(*) from toto_pay where `insu_code` = '0102' and  `reg_date` between '".$date_input."' and '".$date_input."' and `rmdy_doct` =  '".$docRow[1]."'";
		$result = mysql_query($selSql, $connect);
		$row1 = mysql_fetch_row($result);
		$insu[0]=$row1[0]+$insu[0];//일반 고객 수

		$selSql="select count(*) from toto_pay where `insu_code` = '0101' and  `reg_date` between '".$date_input."' and '".$date_input."' and `rmdy_doct` =  '".$docRow[1]."'";
		$result = mysql_query($selSql, $connect);
		$row2 = mysql_fetch_row($result);
		$insu[1]=$row2[0]+$insu[1];//보험 고객 수
		if($rowb[7]){
			if($t_rowb[2]==$sums && $t_rowb[3]==$sums && $t_rowb[4]==$insu[0] && $t_rowb[5]==$insu[1]){
				$msg=date("Y-m-d H:i:s",$rowb[7]);
				$objResponse->assign('tt', 'innerHTML', $msg.' 에 저장된 자료와 동일합니다.');
			}else{
				$msg=date("Y-m-d H:i:s",$rowb[7]);
				$objResponse->assign('tt', 'innerHTML', $msg.' 에 저장된 자료와 상이합니다.'.$t_rowb[2].'-'.$sums.'-'.$t_rowb[3].'-'.$sums.'-'.$t_rowb[4].'-'.$insu[0].'-'.$t_rowb[5].'-'.$insu[1]);
			}
		}else{
			$objResponse->assign('tt', 'innerHTML', '아직 저장되지 않았습니다.');
		}
	   // 다수 진료실 정산 구현 2011-03-31
	$updated=$updated.'<tr><input type="hidden" id="no_'.$docNo.'" name="no_'.$docNo.'" value="'.$rowb[0].'">
		 <td class="style2">'.$docRow[2].'</td><input type="hidden" id="rmdy_doct_'.$docNo.'" name="rmdy_doct_'.$docNo.'" value="'.$docRow[1].'">
		 <td class="tdStyle"><input type="text" id="bfor_mony_'.$docNo.'" name="bfor_mony_'.$docNo.'" class="tdStyle" value="'.comma($sum).'"></td>
		 <td class="tdStyle"><input type="text" id="aftr_mony_'.$docNo.'" name="aftr_mony_'.$docNo.'" class="tdStyle" value="'.comma($sum).'"></td>
		 <td class="tdStyle"><input type="text" id="gner_cont_'.$docNo.'" name="gner_cont_'.$docNo.'" class="tdStyle" value="'.$row1[0].'"></td>
		 <td class="tdStyle"><input type="text" id="insu_cont_'.$docNo.'" name="insu_cont_'.$docNo.'" class="tdStyle" value="'.$row2[0].'"></td>
	   </tr>';
			$sum=0;

}

$updated=$updated.'<tr>
     <td class="style2">합계</td>
     <td class="tdStyle"><input type="text" class="tdStyle" value="'.comma($sums).'" id="sum_bfor_mony" name="sum_bfor_mony" disabled="true"></td>
     <td class="tdStyle"><input type="text" class="tdStyle" value="'.comma($sums).'" id="sum_aftr_mony" name="sum_aftr_mony" disabled="true"></td>
     <td class="tdStyle"><input type="text" class="tdStyle" value="'.$insu[0].'" id="sum_gner_cont" name="sum_gner_cont" disabled="true"></td>
     <td class="tdStyle"><input type="text" class="tdStyle" value="'.$insu[1].'" id="sum_insu_cont" name="sum_insu_cont" disabled="true"></td>
   </tr>
 </table>';
	$msg=$_SESSION['sunap']."님은 ".$setTime."일자 자료입력 중입니다.";
	$objResponse->assign('msgDiv', 'innerHTML', $msg);
	$objResponse->assign('divtable', 'innerHTML', $updated);
if($rows[30]=="Y"){
	$objResponse->assign('bfor_mony', 'disabled', TRUE);
	$objResponse->assign('aftr_mony', 'disabled', TRUE);
	$objResponse->assign('gner_cont', 'disabled', TRUE);
	$objResponse->assign('insu_cont', 'disabled', TRUE);
	$objResponse->assign('btn', 'disabled', TRUE);
	$objResponse->assign('btn', 'value', '마감되었습니다');
	$objResponse->assign('tt', 'innerHTML', '');
}else if($closeb){
	$objResponse->assign('btn', 'disabled', FALSE);
	$objResponse->assign('btn', 'value', '수정');
}else if($num){
	$objResponse->assign('bfor_mony', 'disabled', FALSE);
	$objResponse->assign('aftr_mony', 'disabled', FALSE);
	$objResponse->assign('gner_cont', 'disabled', FALSE);
	$objResponse->assign('insu_cont', 'disabled', FALSE);
	$objResponse->assign('btn', 'disabled', FALSE);
	$objResponse->assign('btn', 'value', '저장');
}else{
	$objResponse->assign('bfor_mony', 'disabled', TRUE);
	$objResponse->assign('aftr_mony', 'disabled', TRUE);
	$objResponse->assign('gner_cont', 'disabled', TRUE);
	$objResponse->assign('insu_cont', 'disabled', TRUE);
	$objResponse->assign('btn', 'disabled', TRUE);
	$objResponse->assign('btn', 'value', '자료가 없습니다');
	$objResponse->assign('tt', 'innerHTML', '');
}
	return $objResponse;
}


$reqInquiry =& $xajax->registerFunction('inquiry');
$reqSave =& $xajax->registerFunction('save');

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