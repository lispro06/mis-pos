<?php
	include_once("menu.php");
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


// 반복되는 구조 변수


$thead[11001]=$tableD.'
  <tr>
    <td width="70" class="style2">진료실</td>
    <td width="80" class="style2">매출</td>
    <td width="100" class="style2">누적매출</td>
    <td width="55" class="style2">일반고객수</td>
    <td width="55" class="style2">누적일반고객수</td>
    <td width="108" class="style2">보험고객수</td>
    <td width="100" class="style2">누적보험고객수</td>
  </tr>';

$thead[11002]=$tableD.'
  <tr>
    <td width="70" class="style2">병원</td>
    <td width="80" class="style2">매출</td>
    <td width="100" class="style2">누적매출</td>
    <td width="108" class="style2">보유현금누계</td>
    <td width="100" class="style2">CCRATE</td>
  </tr>';

$thead[1001]=$tableD.'
<thead>
	<tr>
	<td class="style2">DATE</td>
	<td class="style2">수입</td>
	<td class="style2">이익</td>
	<td class="style2">현금수입</td>
	<td class="style2">현금영수증 수입</td>
	<td class="style2">카드 수입</td>
	<td class="style2">통장 수입</td>
	<td class="style2">매출환불(통장)</td>
	<td class="style2">현금지출</td>
	<td class="style2">통장지출</td>
	<td class="style2">카드(법인)지출</td>
	<td class="style2">카드(개인)지출</td>
	<td class="style2">보유현금</td>
	</tr>
</thead>';

$thead[1002]=$tableD.'
<thead>
	<tr>
	<td class="style2">DATE</td>
	<td class="style2">수입</td>
	<td class="style2">이익</td>
	<td class="style2">현금수입</td>
	<td class="style2">현금영수증 수입</td>
	<td class="style2">카드수입</td>
	<td class="style2">통장수입</td>
	<td class="style2">비고</td>
	</tr>
</thead>';
// 반올림 함수
function round_up ($value, $precision=2) {
  $amt = explode(".", $value);
  if(strlen($amt[1]) > $precision) {
    $next = (int)substr($amt[1],$precision);
    $amt[1] = (float)(".".substr($amt[1],0,$precision));
    if($next != 0) {
      $rUp = "";
      for($x=1;$x<$precision;$x++) $rUp .= "0";
      $amt[1] = $amt[1] + (float)(".".$rUp."1");
    }
  }
  else {
    $amt[1] = (float)(".".$amt[1]);
  }
  return $amt[0]+$amt[1];
}
/*
	Function: inquiry
	
	inquiry
*/
function inquiry($sale_code,$end_date)
{
	global $menu2,$thead,$menu,$extColor,$tabColor,$thColor;
	$objResponse = new xajaxResponse();

	$style[1000]=' style="width:4%;background-color:'.$extColor.';"';
	$selTab[11001]=' style="width:24%;"';
	$selTab[11002]=' style="width:24%;"';
	$selTab[1001]=' style="width:24%;"';
	$selTab[1002]=' style="width:24%;"';
//	$selTab[$exps_code]=' style="font-weight: bolder;background-color:'.$tabColor.';width:24%;"';
	$selClass[$sale_code]='  class="tabS"';
	$sCol[11001]='color:#FFFFFF;';
	$sCol[11002]='color:#FFFFFF;';
	$sCol[1001]='color:#FFFFFF;';
	$sCol[1002]='color:#FFFFFF;';
	$sCol[$sale_code]='color:'.$thColor.';';

	$setTime=$end_date;
	if(session_is_registered('reg_date')){
		$date_input=str_replace("-","",$end_date);// '-' 제거
		$_SESSION['session_reg_date']=$date_input;
	}
	$menuDiv='<table border="0" cellspacing="0" cellpadding="0">
      <tr>
		<td height="30" align="center"'.$style[1000].'></td>
        <td width="150" height="30" align="center" '.$selTab[11001].'><div '.$selClass[11001].'><a href="report.php"><span style="'.$sCol[11001].'">일매출전체(피부과)</span></a></div></td>
        <td width="150" height="30" align="center" '.$selTab[1001].'><div '.$selClass[1001].'><a href="#" onclick="xajax_inquiry(1001,\''.$end_date.'\')"><span style="'.$sCol[1001].'">일매출병원(피부과)</span></a></div></td>
        <td width="150" height="30" align="center" '.$selTab[11002].'><div '.$selClass[11002].'><a href="#" onclick="xajax_inquiry(11002,\''.$end_date.'\')"><span style="'.$sCol[11002].'">일매출전체(코스메틱)</span></a></div></td>
        <td width="150" height="30" align="center" '.$selTab[1002].'><div '.$selClass[1002].'><a href="#" onclick="xajax_inquiry(1002,\''.$end_date.'\')"><span style="'.$sCol[1002].'">일매출전체(코스메틱)</span></a></div></td>
        <td width="100" height="30" align="center" class="box_Bottomline">&nbsp;</td>
      </tr>	
    </table>';

	$m=substr($end_date,5,2);
	$start_date=date("Y-m-d", mktime(0,0,0,$m,1,substr($end_date,0,4)));

	$query="inquiry".$start_date.$end_date.' '.$m;
	$objResponse->assign('date_start', 'value', $start_date);
	$objResponse->assign('date_end', 'value', $end_date);
	$objResponse->assign('sale_code', 'value', $sale_code);

	$start_date=str_replace("-","",$start_date);// '-' 제거
	$end_date=str_replace("-","",$end_date);// '-' 제거

	if($sale_code>10000){
		$updated=$thead[$sale_code].'<tbody>';
		$sale_code=$sale_code-10000;
		$updated=$updated.selectAll($sale_code,$start_date,$end_date).'</table>';
	}else{
		$updated=selectSale($sale_code,$start_date,$end_date).'</table>';
	}
//	$objResponse->assign('div2', 'innerHTML', $date_input);
	$objResponse->assign('content', 'innerHTML', $updated);
	$objResponse->call('bottom');//div scolling
	$objResponse->assign('menu', 'innerHTML', $menu);
	$msg=$_SESSION['sunap']."님은 ".$setTime."일자 자료입력 중입니다.";
	$objResponse->assign('msgDiv', 'innerHTML', $msg);
	$objResponse->assign('tabs2', 'innerHTML', $menuDiv);
	return $objResponse;
}
/*
	Function: selectSale
	
	seclectSale
*/
//일매출 병원을 처리하기 위한, 수입/지출 내역 처리 함수
function selectSale($sale_code,$start_date,$end_date)
{
	global $thead, $connect;
	$selSql="select `reg_date` from toto_pay where `sale_code` = '".$sale_code."' and `reg_date` between '".$start_date."' and '".$end_date."' order by reg_date asc";
	$result = mysql_query($selSql, $connect); 
	$total = mysql_num_rows($result); // 총 레코드 수
	$num=$total;

while($total--){
	$row = mysql_fetch_row($result);
	if($row[0]==$cur_date){
	}else{
		$reg_date=substr($row[14],0,4);
		$reg_date=$reg_date."-".substr($row[14],4,2);
		$reg_date=$reg_date."-".substr($row[14],6,2);
		$reged_date=$reged_date."|".$row[0];//입력된 reg_date 구하기
	}
		$cur_date=$row[0];
	}
	$reg_arr=explode("|",$reged_date);
	$arr_size=count($reg_arr);
	$num=0;
	for($ac=$start_date;$ac<=$end_date;$ac++){
		$numOfList++;
		$selSql="select * from toto_pay where `sale_code` = '".$sale_code."' and `reg_date` = '".$ac."' order by reg_date asc";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;

		while($total--){
			$row = mysql_fetch_row($result);
			$income=$income+$row[8]+$row[9]+$row[10]+$row[11];
			$cash_income=$cash_income+$row[8];
			$cscd_income=$cscd_income+$row[9];
			$card_income=$card_income+$row[10];
			$yet_income=$yet_income+$row[11];
			$t_income=$t_income+$row[8]+$row[9]+$row[10]+$row[11];//총합
			$t_cash_income=$t_cash_income+$row[8];
			$t_cscd_income=$t_cscd_income+$row[9];
			$t_card_income=$t_card_income+$row[10];
			$t_yet_income=$t_yet_income+$row[11];
			}
		$exps_code=$sale_code+2999;
		$selSql2="select `cash_mony` from toto_exp where `exps_code` = '".$exps_code."' and `reg_date` = '".$ac."' and exps_gubn='0'";
		$result2 = mysql_query($selSql2, $connect); 
		$total2 = mysql_num_rows($result2); // 총 레코드 수

		while($total2--){
			$row2 = mysql_fetch_row($result2);
			$cash_outcome=$cash_outcome+$row2[0];
			$t_cash_outcome=$t_cash_outcome+$row2[0];
			}
		$selSql2="select `cash_mony` from toto_exp where `exps_code` = '".$exps_code."' and `reg_date` = '".$ac."' and exps_gubn='1'";
		$result2 = mysql_query($selSql2, $connect); 
		$total2 = mysql_num_rows($result2); // 총 레코드 수

		while($total2--){
			$row2 = mysql_fetch_row($result2);
			$cscd_outcome=$cscd_outcome+$row2[0];
			$t_cscd_outcome=$t_cscd_outcome+$row2[0];
			}
		$selSql2="select `cash_mony` from toto_exp where `exps_code` = '".$exps_code."' and `reg_date` = '".$ac."' and exps_gubn='2'";
		$result2 = mysql_query($selSql2, $connect); 
		$total2 = mysql_num_rows($result2); // 총 레코드 수

		while($total2--){
			$row2 = mysql_fetch_row($result2);
			$card_legal=$card_legal+$row2[0];
			$t_card_legal=$t_card_legal+$row2[0];
			}
		$selSql2="select `cash_mony` from toto_exp where `exps_code` = '".$exps_code."' and `reg_date` = '".$ac."' and exps_gubn='3'";
		$result2 = mysql_query($selSql2, $connect); 
		$total2 = mysql_num_rows($result2); // 총 레코드 수

		while($total2--){
			$row2 = mysql_fetch_row($result2);
			$card_indiv=$card_indiv+$row2[0];
			$t_card_indiv=$t_card_indiv+$row2[0];
			}
		$outcome=$cash_outcome+$cscd_outcome+$card_legal+$card_indiv;
		$t_outcome=$t_cash_outcome+$t_cscd_outcome+$t_card_legal+$t_card_indiv;
		$reg_date=substr($ac,0,4);
		$reg_date=$reg_date."-".substr($ac,4,2);
		$reg_date=$reg_date."-".substr($ac,6,2);
		$reged_date=$reged_date."|".$row[0];//입력된 reg_date 구하기
		if($numOfList%5){
			$ts="tdStyle";
		}else{
			$ts="fifthStyle";
		}
		$updated=$updated.'<tr>
		<td class="'.$ts.'">'.$reg_date.'</td>';
		$updated=$updated.'<td class="'.$ts.'">'.comma($income).'</td>';
		$updated=$updated.'<td class="'.$ts.'">'.comma($income-$outcome).'</td>';
		$updated=$updated.'<td class="'.$ts.'">'.comma($cash_income).'</td>';
		$updated=$updated.'<td class="'.$ts.'">'.comma($cscd_income).'</td>';
		$updated=$updated.'<td class="'.$ts.'">'.comma($card_income).'</td>';
		$updated=$updated.'<td class="'.$ts.'">'.comma($yet_income).'</td>';
		if($sale_code=="1001"){
			$updated=$updated.'<td class="'.$ts.'">0</td>';
			$updated=$updated.'<td class="'.$ts.'">'.comma($cash_outcome).'</td>';
			$updated=$updated.'<td class="'.$ts.'">'.comma($cscd_outcome).'</td>';
			$updated=$updated.'<td class="'.$ts.'">'.comma($card_legal).'</td>';
			$updated=$updated.'<td class="'.$ts.'">'.comma($card_indiv).'</td>';
		}
			$updated=$updated.'<td class="'.$ts.'">'.comma($cash_income+$cscd_income-$cash_outcome).'</td>
		</tr>';
		$income=0;
		$cash_income=0;
		$cscd_income=0;
		$card_income=0;
		$yet_income=0;
		$cash_outcome=0;
		$cscd_outcome=0;
		$card_legal=0;
		$card_indiv=0;
		$outcome=0;
	}

		$summation=$summation.'<tr><td class="summ">합계</td>';
		$summation=$summation.'<td class="summ">'.comma($t_income).'</td>';
		$summation=$summation.'<td class="summ">'.comma($t_income-$t_outcome).'</td>';
		$summation=$summation.'<td class="summ">'.comma($t_cash_income).'</td>';
		$summation=$summation.'<td class="summ">'.comma($t_cscd_income).'</td>';
		$summation=$summation.'<td class="summ">'.comma($t_card_income).'</td>';
		$summation=$summation.'<td class="summ">'.comma($t_yet_income).'</td>';
		if($sale_code=="1001"){
			$summation=$summation.'<td class="summ">0</td>';
			$summation=$summation.'<td class="summ">'.comma($t_cash_outcome).'</td>';
			$summation=$summation.'<td class="summ">'.comma($t_cscd_outcome).'</td>';
			$summation=$summation.'<td class="summ">'.comma($t_card_legal).'</td>';
			$summation=$summation.'<td class="summ">'.comma($t_card_indiv).'</td>';
		}
		$summation=$summation.'<td class="summ">'.comma($t_cash_income+$t_cscd_income).'</td></tr>';

		$summation=$summation.'<tr><td class="summ">평균</td>';
		$summation=$summation.'<td class="summ">'.comma(ceil($t_income/$arr_size)).'</td>';
		$summation=$summation.'<td class="summ">'.comma(ceil(($t_income-$t_outcome)/$arr_size)).'</td>';
		$summation=$summation.'<td class="summ">'.comma(ceil($t_cash_income/$arr_size)).'</td>';
		$summation=$summation.'<td class="summ">'.comma(ceil($t_cscd_income/$arr_size)).'</td>';
		$summation=$summation.'<td class="summ">'.comma(ceil($t_card_income/$arr_size)).'</td>';
		$summation=$summation.'<td class="summ">'.comma(ceil($t_yet_income/$arr_size)).'</td>';
		if($sale_code=="1001"){
			$summation=$summation.'<td class="summ">0</td>';
			$summation=$summation.'<td class="summ">'.comma(ceil($t_cash_outcome/$arr_size)).'</td>';
			$summation=$summation.'<td class="summ">'.comma(ceil($t_cscd_outcome/$arr_size)).'</td>';
			$summation=$summation.'<td class="summ">'.comma(ceil($t_card_legal/$arr_size)).'</td>';
			$summation=$summation.'<td class="summ">'.comma(ceil($t_card_indiv/$arr_size)).'</td>';
		}
		$summation=$summation.'<td class="summ">'.comma(ceil(($t_cash_income+$t_cscd_income)/$arr_size)).'</td></tr>';

	$updated=$thead[$sale_code].'<tbody>'.$summation.$updated.$summation.'</tbody></table>';

	return $updated;
}


/*
	Function: selectAll
	
	seclectAll
*/
//일매출 전체를 처리하기 위한 수입부 처리 함수
function selectAll($sale_code,$start_date,$end_date)
{
	global $thead, $connect, $tableD;
	$selSql="select `reg_date` from toto_pay where `sale_code` = '".$sale_code."' and `reg_date` between '".$start_date."' and '".$end_date."' order by reg_date asc";
	$result = mysql_query($selSql, $connect); 
	$total = mysql_num_rows($result); // 총 레코드 수
	$num=$total;//코스 메틱 내역이 있는지 확인
	$docSql="select * from t_doctor order by `no` asc";
	$docRes = mysql_query($docSql, $connect); 
	$docTot = mysql_num_rows($docRes); // 총 레코드 수
	$docNum=$docTot;
	if($total){//코스메틱
		if($sale_code==1002){
			$docRow = mysql_fetch_row($docRes);
			$hosp_name = $docRow[3];
			while($total--){
				$row = mysql_fetch_row($result);
				if($row[0]==$cur_date){
				}else{
					$reg_date=substr($row[14],0,4);
					$reg_date=$reg_date."-".substr($row[14],4,2);
					$reg_date=$reg_date."-".substr($row[14],6,2);
					$reged_date=$reged_date."|".$row[0];//입력된 reg_date 구하기
				}
					$cur_date=$row[0];
				}
				$reg_arr=explode("|",$reged_date);
				$arr_size=count($reg_arr);
				$num=0;
				for($ac=$start_date;$ac<=$end_date;$ac++){//피부과 자료 정리
					$numOfList++;
					$selSql="select sum(cash_mony+cscd_mony+card_mony+yet__mony) from toto_pay where `sale_code` = '".$sale_code."' and `reg_date` = '".$ac."' order by reg_date asc";
					$result = mysql_query($selSql, $connect); 
					$total = mysql_num_rows($result); // 총 레코드 수
					$num=$total;

					while($total--){
						$row = mysql_fetch_row($result);
						$income=$row[0];
						$income_sum=$income_sum+$row[0];//총합
					}


				}

					$updated=$updated.'<tr>
					<td class="tdStyle">'.$hosp_name.'</td>';
					$updated=$updated.'<td class="tdStyle">'.comma($income).'</td>';
				if($sale_code==1001)//병원 합계 일 때 만 지출 계산
					$updated=$updated.'<td class="tdStyle">'.comma($outcome).'</td>';
					$updated=$updated.'<td class="tdStyle">'.comma($income_sum).'</td>';
				if($sale_code==1001)//병원 합계 일 때 만 지출 계산
					$updated=$updated.'<td class="tdStyle">'.comma($outcome).'</td>';
					$updated=$updated.'<td class="tdStyle">'.comma($cash_income+$cscd_income).'</td>';
					$updated=$updated.'<td class="tdStyle">'.round_up(($card_income/$income),3).'</td></tr>';
					$updated=$updated.'<tr>
					<td class="tdStyle">합계</td>';
					$updated=$updated.'<td class="tdStyle">'.comma($income).'</td>';
				if($sale_code==1001)//병원 합계 일 때 만 지출 계산
					$updated=$updated.'<td class="tdStyle">'.comma($outcome).'</td>';
					$updated=$updated.'<td class="tdStyle">'.comma($income_sum).'</td>';
				if($sale_code==1001)//병원 합계 일 때 만 지출 계산
					$updated=$updated.'<td class="tdStyle">'.comma($outcome).'</td>';
					$updated=$updated.'<td class="tdStyle">'.comma($cash_income+$cscd_income).'</td>';
					$updated=$updated.'<td class="tdStyle">'.round_up(($card_income/$income),3).'</td></tr>';
				$updated=$updated.'</table>';
		}else{//피부과
			while($docTot--){
				$docNo++;
				$docRow = mysql_fetch_row($docRes);
				$income_sum=0;
				$insu_sum=0;
				$gner_sum=0;

				$selSql="select `reg_date` from toto_pay where `sale_code` = '".$sale_code."' and `slit_code`='2001' and rmdy_doct = '".$docRow[1]."' and `reg_date` between '".$start_date."' and '".$end_date."' order by reg_date asc";
				$result = mysql_query($selSql, $connect); 
				$total = mysql_num_rows($result); // 총 레코드 수
				$num=$total;

				while($total--){
					$row = mysql_fetch_row($result);
					if($row[0]==$cur_date){
					}else{
						$reg_date=substr($row[14],0,4);
						$reg_date=$reg_date."-".substr($row[14],4,2);
						$reg_date=$reg_date."-".substr($row[14],6,2);
						$reged_date=$reged_date."|".$row[0];//입력된 reg_date 구하기
					}
						$cur_date=$row[0];
					}
					$reg_arr=explode("|",$reged_date);
					$arr_size=count($reg_arr);
					$num=0;
					for($ac=$start_date;$ac<=$end_date;$ac++){//피부과 자료 정리
						$numOfList++;
						$selSql="select sum(cash_mony+cscd_mony+card_mony+yet__mony) from toto_pay where `sale_code` = '".$sale_code."' and rmdy_doct = '".$docRow[1]."' and `reg_date` = '".$ac."' order by reg_date asc";
						$result = mysql_query($selSql, $connect); 
						$total = mysql_num_rows($result); // 총 레코드 수
						$num=$total;

						while($total--){
							$row = mysql_fetch_row($result);
							$income=$row[0];
							$income_sum=$income_sum+$row[0];//총합
						}

						$selSql2="select count(*) from toto_pay where `sale_code` = '".$sale_code."' and `reg_date` = '".$ac."' and rmdy_doct = '".$docRow[1]."' and insu_code='0101'";
						$result2 = mysql_query($selSql2, $connect); 
						$row2 = mysql_fetch_row($result2);
						$insu_num = $row2[0];// 보험 고객수
						$insu_sum = $insu_sum+$row2[0];

						$selSql2="select count(*) from toto_pay where `sale_code` = '".$sale_code."' and `reg_date` = '".$ac."' and rmdy_doct = '".$docRow[1]."' and insu_code='0102'";
						$result2 = mysql_query($selSql2, $connect); 
						$row2 = mysql_fetch_row($result2);
						$gner_num = $row2[0];// 일반 고객수
						$gner_sum = $gner_sum+$row2[0];

						$reg_date=substr($ac,0,4);
						$reg_date=$reg_date."-".substr($ac,4,2);
						$reg_date=$reg_date."-".substr($ac,6,2);
						$reged_date=$reged_date."|".$row[0];//입력된 reg_date 구하기
						if($numOfList%5){
							$ts="tdStyle";
						}else{
							$ts="tdStyle";
						}
					}
						$updated=$updated.'<tr>
						<td class="'.$ts.'">'.$docRow[2].'</td>';
						$updated=$updated.'<td class="'.$ts.'">'.comma($income).'</td>';
						$updated=$updated.'<td class="'.$ts.'">'.comma($income_sum).'</td>';
						$updated=$updated.'<td class="'.$ts.'">'.comma($gner_num).'</td>';
						$updated=$updated.'<td class="'.$ts.'">'.comma($gner_sum).'</td>';
						$updated=$updated.'<td class="'.$ts.'">'.comma($insu_num).'</td>';
						$updated=$updated.'<td class="'.$ts.'">'.comma($insu_sum).'</td>';
						$updated=$updated.'</tr>';

						//합계를 위한 계산 2011-04-11
			$t_income=$income+$t_income;
			$t_income_sum=$income_sum+$t_income_sum;
			$t_gner_num=$gner_num+$t_gner_num;
			$t_gner_sum=$gner_sum+$t_gner_sum;
			$t_insu_num=$insu_num+$t_insu_num;
			$t_insu_sum=$insu_sum+$t_insu_sum;
			}//진료실 개별 출력 끝
		$summation=$summation.'<tr><td class="summ">합계</td>';
		$summation=$summation.'<td class="summ">'.comma($t_income).'</td>';
		$summation=$summation.'<td class="summ">'.comma($t_income_sum).'</td>';
		$summation=$summation.'<td class="summ">'.comma($t_gner_num).'</td>';
		$summation=$summation.'<td class="summ">'.comma($t_gner_sum).'</td>';
		$summation=$summation.'<td class="summ">'.comma($t_insu_num).'</td>';
		$summation=$summation.'<td class="summ">'.comma($t_insu_sum).'</td></tr>';
		$summation=$summation.'<tr><td class="summ">평균</td>';
		$summation=$summation.'<td class="summ">'.comma(ceil($t_income/$docNum)).'</td>';
		$summation=$summation.'<td class="summ">'.comma(ceil($t_income_sum/$docNum)).'</td>';
		$summation=$summation.'<td class="summ">'.comma(ceil($t_gner_num/$docNum)).'</td>';
		$summation=$summation.'<td class="summ">'.comma(ceil($t_gner_sum/$docNum)).'</td>';
		$summation=$summation.'<td class="summ">'.comma(ceil($t_insu_num/$docNum)).'</td>';
		$summation=$summation.'<td class="summ">'.comma(ceil($t_insu_sum/$docNum)).'</td></tr>';
		}
	}else{
			$updated="자료가 없습니다.";
	}
	return $summation.$updated.$summation;
}

$reqInquiry =& $xajax->registerFunction('inquiry');

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