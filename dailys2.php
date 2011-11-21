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
	$query="UPDATE `toto_closedsale` SET `ask__chck` = 'Y' WHERE `reg_date`='".$date_input."';";
    $saveQue = mysql_query($query, $connect);
	$objResponse->assign('end', 'disabled', true);
	
	return $objResponse;
}
/*
	Function: save
	
	saveBank
*/
function saveBank($bkbk_seqn,$bkbk_idid,$incm_mony,$exps_mony,$tday_mony,$reg_date)
{
	global $connect;
	$date_input=str_replace("-","",$reg_date);// '-' 제거
	$incm_mony=str_replace(",","",$incm_mony);// ',' 제거
	$exps_mony=str_replace(",","",$exps_mony);// ',' 제거
	$tday_mony=str_replace(",","",$tday_mony);// ',' 제거
	$objResponse = new xajaxResponse();
	$datetime=time();
	if($bkbk_seqn){
		$q="UPDATE `toto_bankbook` SET `incm_mony` = '".$incm_mony."', `exps_mony` = '".$exps_mony."', `tday_mony` = '".$tday_mony."' WHERE  `bkbk_seqn` =".$bkbk_seqn;
	}else{
		$q="INSERT INTO `toto_bankbook` VALUES ('', ".$bkbk_idid.", ".$incm_mony.", ".$exps_mony.", ".$tday_mony.", '".$date_input."', '".$datetime."');";
	}
	$saveQue = mysql_query($q, $connect);
	//$objResponse->assign('msgDiv', 'innerHTML', $q);
	return $objResponse;
}
function save($no,$d_afdy_mony,$d_doct_mony,$c_afdy_mony,$c_doct_mony,$reg_date)
{
	global $connect;
	$objResponse = new xajaxResponse();
	if($d_afdy_mony =='' || $c_afdy_mony == '' || $d_afdy_mony < 0 ||  $c_afdy_mony < 0){
		if($d_doct_mony=='' || $c_doct_mony==''){
			$objResponse->call('alert(\'인출금을 입력하세요.\')');
		}else{
			$objResponse->call('alert(\'인출금이 부적절합니다.\')');
		}
	}else{
		$c_time=time();
		$date_input=str_replace("-","",$reg_date);// '-' 제거
		$d_afdy_mony=str_replace(",","",$d_afdy_mony);// ',' 제거
		$c_afdy_mony=str_replace(",","",$c_afdy_mony);// ',' 제거
		$d_doct_mony=str_replace(",","",$d_doct_mony);// ',' 제거
		$c_doct_mony=str_replace(",","",$c_doct_mony);// ',' 제거

//원장 정산 저장 플래그
		$selSql="SELECT `date` FROM `toto_balancedoctor` WHERE `reg_date`=".$date_input;
		$result = mysql_query($selSql, $connect); 
		$row = mysql_fetch_row($result);
		$bdsv_flag=$row[0];

		//피부과 일반 매출
		//김기출 수정 : 외입을 제외한 모든 항목은 모두 일반 매출로 계산하여야함
		//$selSql="select `cash_mony`,`cscd_mony`,`card_mony`, `yet__mony` from toto_pay where `sale_code` = '1001' and `slit_code` = '2001' and `insu_code` = '0102' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$selSql="select `cash_mony`,`cscd_mony`,`card_mony`, `yet__mony` from toto_pay where `sale_code` = '1001' and `slit_code` <> '2002' and `insu_code` = '0102' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$row = mysql_fetch_row($result);
		$gen_cash=$gen_cash+$row[0];
		$gen_cscd=$gen_cscd+$row[1];
		$gen_card=$gen_card+$row[2];
		if($row[3]>0){
			$bank_in=$bank_in+$row[3];
		}
    if($gen_yet>0)
        $gen_yet=$gen_yet+$row[3];
	}
		//피부과 일반 외상입금
		$selSql="select `cash_mony`,`cscd_mony`,`card_mony`, `yet__mony` from toto_pay where `sale_code` = '1001' and `slit_code` = '2002' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$row = mysql_fetch_row($result);
		$gen_cash_del=$gen_cash_del+$row[0];
		$gen_cscd_del=$gen_cscd_del+$row[1];
		$gen_card_del=$gen_card_del+$row[2];
		$gen_yet_del=$gen_yet_del+$row[3];
	}
		//피부과 보험 매출
		//김기출 수정 : 외입을 제외한 모든 항목은 모두 보험 매출로 계산하여야함
		//$selSql="select `cash_mony`,`cscd_mony`,`card_mony`, `yet__mony` from toto_pay where `sale_code` = '1001' and `slit_code` = '2001' and `insu_code` = '0101' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$selSql="select `cash_mony`,`cscd_mony`,`card_mony`, `yet__mony` from toto_pay where `sale_code` = '1001' and `slit_code` <> '2002' and `insu_code` = '0101' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$row = mysql_fetch_row($result);
		$insu_cash=$insu_cash+$row[0];
		$insu_cscd=$insu_cscd+$row[1];
		$insu_card=$insu_card+$row[2];
		if($row[3]>0){
			$bank_in=$bank_in+$row[3];
		}
		$insu_yet=$insu_yet+$row[3];
	}
		//코스메틱 매출
		//김기출 수정 : 외입을 제외한 모든 항목은 모두 보험 매출로 계산하여야함
		//$selSql="select `cash_mony`,`cscd_mony`,`card_mony`,`yet__mony` from toto_pay where `sale_code` = '1002' and `slit_code` = '2001' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$selSql="select `cash_mony`,`cscd_mony`,`card_mony`,`yet__mony` from toto_pay where `sale_code` = '1002' and `slit_code` <> '2002' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$row = mysql_fetch_row($result);
		$cos_cash=$cos_cash+$row[0];
		$cos_cscd=$cos_cscd+$row[1];
		$cos_card=$cos_card+$row[2];
		if($row[3]>0){//통장 입금액 별도 저장 2011-04-15
			$cos_bank=$cos_bank+$row[3];
		}
    if($cos_yet>0)
        $cos_yet=$cos_yet+$row[3];
	}
		//코스메틱 외상입금
		$selSql="select `cash_mony`,`cscd_mony`,`card_mony`,`yet__mony` from toto_pay where `sale_code` = '1002' and `slit_code` = '2002' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$row = mysql_fetch_row($result);
		$cos_cash_del=$cos_cash_del+$row[0];
		$cos_cscd_del=$cos_cscd_del+$row[1];
		$cos_card_del=$cos_card_del+$row[2];
		$cos_yet_del=$cos_yet_del+$row[3];
	}
		//환불 피부과
		//김기출 : 매출환불(통장)은 환불중 미납(통장)이 출력되어야함
		//$selSql="select `cash_mony` from toto_pay where `sale_code` = '1001' and `slit_code` = '2003' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$selSql="select `yet__mony` from toto_pay where `sale_code` = '1001' and `slit_code` = '2003' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$row = mysql_fetch_row($result);
		$d_refd=$d_refd+$row[0];
	}
		//환불 코스메틱
		//김기출 : 매출환불(통장)은 환불중 미납(통장)이 출력되어야함
		//$selSql="select `cash_mony` from toto_pay where `sale_code` = '1001' and `slit_code` = '2003' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$selSql="select `yet__mony` from toto_pay where `sale_code` = '1001' and `slit_code` = '2003' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$row = mysql_fetch_row($result);
		$c_refd=$c_refd+$row[0];
	}
		//현금 지출 피부과
		$selSql="select `cash_mony` from toto_exp where `exps_code` = '4000' and `exps_gubn` = '0' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$row = mysql_fetch_row($result);
		$exp_cash=$exp_cash+$row[0];
	}
		//통장 지출 피부과
		$selSql="select `cash_mony` from toto_exp where `exps_code` = '4000' and `exps_gubn` = '1' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$row = mysql_fetch_row($result);
		$exp_bank=$exp_bank+$row[0];
	}
		//카드(법인) 지출 피부과
		$selSql="select `cash_mony` from toto_exp where `exps_code` = '4000' and `exps_gubn` = '2' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$exp_card_legal=$exp_card_legal+$row[0];
		$row = mysql_fetch_row($result);
	}
		//카드(개인) 지출 피부과
		$selSql="select `cash_mony` from toto_exp where `exps_code` = '4000' and `exps_gubn` = '3' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$row = mysql_fetch_row($result);
		$exp_card_indiv=$exp_card_indiv+$row[0];
	}
		//현금 지출 코스메틱
		$selSql="select `cash_mony` from toto_exp where `exps_code` = '4001' and `exps_gubn` = '0' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$row = mysql_fetch_row($result);
		$exp_cos_cash=$exp_cos_cash+$row[0];
	}
		//통장 지출 코스메틱
		$selSql="select `cash_mony` from toto_exp where `exps_code` = '4001' and `exps_gubn` = '1' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$row = mysql_fetch_row($result);
		$exp_cos_bank=$exp_cos_bank+$row[0];
	}
		//카드(법인) 지출 코스메틱
		$selSql="select `cash_mony` from toto_exp where `exps_code` = '4001' and `exps_gubn` = '2' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$row = mysql_fetch_row($result);
		$exp_cos_card_legal=$exp_cos_card_legal+$row[0];
	}
		//카드(개인) 지출 코스메틱
		$selSql="select `cash_mony` from toto_exp where `exps_code` = '4001' and `exps_gubn` = '3' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$row = mysql_fetch_row($result);
		$exp_cos_card_indiv=$exp_cos_card_indiv+$row[0];
	}

	$d_cash_mony=$gen_cash;//
	$d_cash_insu_mony=$insu_cash;//
	$d_cscd_mony=$gen_cscd;//
	$d_cscd_insu_mony=$insu_cscd;//
	$d_card_mony=$gen_card;//
	$d_card_insu_mony=$insu_card;//
	$d_yet__mony=$bank_in;//2011-04-15
	$d_refd_mony=$d_refd;//
	$d_cout_mony=$exp_cash;//
	$d_bout_mony=$exp_bank;//
	$d_card_legal=$exp_card_legal;//
	$d_card_indiv=$exp_card_indiv;//
	$c_cash_mony=$cos_cash;//
	$c_cscd_mony=$cos_cscd;//
	$c_card_mony=$cos_card;//
	$c_yet__mony=$cos_bank;//2011-04-15
	$c_refd_mony=$c_refd;//
	$c_cout_mony=$exp_cos_cash;//
	$c_bout_mony=$exp_cos_bank;//
	$c_card_legal=$exp_cos_card_legal;//
	$c_card_indiv=$exp_cos_card_indiv;//
	$dmsv_flag="";
	$cssv_flag="";

	if($no!="null" && $no!=""){
		$query="UPDATE `toto_closedsale` SET `d_afdy_mony`='".$d_afdy_mony."' , `d_cash_mony`='".$d_cash_mony."' , `d_cash_insu_mony`='".$d_cash_insu_mony."' , `d_cscd_mony`='".$d_cscd_mony."' , `d_cscd_insu_mony`='".$d_cscd_insu_mony."' , `d_card_mony`='".$d_card_mony."' , `d_card_insu_mony`='".$d_card_insu_mony."' , `d_yet__mony`='".$d_yet__mony."' , `d_refd_mony`='".$d_refd_mony."' , `d_doct_mony`='".$d_doct_mony."' , `d_cout_mony`='".$d_cout_mony."' , `d_bout_mony`='".$d_bout_mony."' , `d_card_legal`='".$d_card_legal."' , `d_card_indiv`='".$d_card_indiv."' , `c_afdy_mony`='".$c_afdy_mony."' , `c_cash_mony`='".$c_cash_mony."' , `c_cscd_mony`='".$c_cscd_mony."' , `c_card_mony`='".$c_card_mony."' , `c_yet__mony`='".$c_yet__mony."' , `c_refd_mony`='".$c_refd_mony."' , `c_doct_mony`='".$c_doct_mony."' , `c_cout_mony`='".$c_cout_mony."' , `c_bout_mony`='".$c_bout_mony."' , `c_card_legal`='".$c_card_legal."' , `c_card_indiv`='".$c_card_indiv."' , `dmsv_flag`='".$dmsv_flag."' , `cssv_flag`='".$cssv_flag."' , `bdsv_flag`='".$bdsv_flag."' , `reg_date`='".$date_input."', `date`='".$c_time."' WHERE `no` =".$no;
	}else{
		$query="INSERT INTO `toto_closedsale` ( `no` , `d_afdy_mony` , `d_cash_mony` , `d_cash_insu_mony` , `d_cscd_mony` , `d_cscd_insu_mony` , `d_card_mony` , `d_card_insu_mony` , `d_yet__mony` , `d_refd_mony` , `d_doct_mony` , `d_cout_mony` , `d_bout_mony` , `d_card_legal` , `d_card_indiv` , `c_afdy_mony` , `c_cash_mony` , `c_cscd_mony` , `c_card_mony` , `c_yet__mony` , `c_refd_mony` , `c_doct_mony` , `c_cout_mony` , `c_bout_mony` , `c_card_legal` , `c_card_indiv` , `dmsv_flag` , `cssv_flag` , `bdsv_flag` , `ask__chck` , `clos_chck` , `reg_date`, `date` ) VALUES ('', '".$d_afdy_mony."', '".$d_cash_mony."', '".$d_cash_insu_mony."', '".$d_cscd_mony."', '".$d_cscd_insu_mony."', '".$d_card_mony."', '".$d_card_insu_mony."', '".$d_yet__mony."', '".$d_refd_mony."', '".$d_doct_mony."', '".$d_cout_mony."', '".$d_bout_mony."', '".$d_card_legal."', '".$d_card_indiv."', '".$c_afdy_mony."', '".$c_cash_mony."', '".$c_cscd_mony."', '".$c_card_mony."', '".$c_yet__mony."', '".$c_refd_mony."', '".$c_doct_mony."', '".$c_cout_mony."', '".$c_bout_mony."', '".$c_card_legal."', '".$c_card_indiv."', '".$dmsv_flag."', '".$dmsv_flag."', '".$bdsv_flag."', '', '', '".$date_input."', '".$c_time."');";
	}

		$objResponse->assign('btn', 'disabled', TRUE);
		$saveQue = mysql_query($query, $connect);
		$objResponse->call('confirm_entry()');
		$objResponse->assign('detable', 'innerHTML', '저장(수정) 하였습니다');
	}

	
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
	if(session_is_registered('reg_date')){
		$_SESSION['session_reg_date']=$date_input;//세션에 저장
	}

	$selSql="SELECT * FROM `toto_closedsale` where `reg_date` = ". $date_input;
	$result = mysql_query($selSql, $connect);
	$close = mysql_num_rows($result);
	$rows = mysql_fetch_row($result);
	//////////////////////////////////////////////////// 이전 날짜의 이월액 구하기 2011-03-13
	$year=substr($date_input,0,4);
	$month=substr($date_input,4,2);
	$day=substr($date_input,6,2);
	$foredate=date("Ymd",mktime( 0, 0, 0, $month, $day-1, $year ));
	$selSqlC="SELECT * FROM `toto_closedsale` where `reg_date`=".$foredate;
	$resultC = mysql_query($selSqlC, $connect);
	$rowc = mysql_fetch_row($resultC);



if($rows[30]=="Y"){
	
	// 피부과 통장
		$selBI="SELECT * FROM `toto_bankbookinfo` where `use_flag` = '01'";
		$resBI = mysql_query($selBI, $connect);
		$totBI = mysql_num_rows($resBI); // 총 레코드 수


		$bank_info='<table>
						<tr>
							<td>
							</td>
							<td width="100" class="style3">전일이월</td>
							<td width="100" class="style3">입금</td>
							<td width="100" class="style3">지출</td>
							<td width="100" class="style3">잔액</td>
						</tr>';

	while($rowBI = mysql_fetch_row($resBI)){
		$bnum++;
		$cnum=$bnum-1;
		$selBank="SELECT * FROM `toto_bankbook` where `reg_date` = '".$foredate."' and bkbk_idid='".$rowBI[0]."'";
		$resBank = mysql_query($selBank, $connect);
		$rowBank[] = mysql_fetch_row($resBank);//전일 내역

		$selBank="SELECT * FROM `toto_bankbook` where `reg_date` = '".$date_input."' and bkbk_idid='".$rowBI[0]."'";
		$resBank = mysql_query($selBank, $connect);
		$rowBankC[] = mysql_fetch_row($resBank);//현재 내역

		//통장 내역 출력
	$bank_info=$bank_info.'<tr><td>'.$rowBI[1].'</td><td class="style3">'.comma($rowBank[$cnum][4]).'</td><td class="style3">'.comma($rowBankC[$cnum][2]).'</td><td class="style3">'.comma($rowBankC[$cnum][3]).'</td><td class="style3">'.comma($rowBankC[$cnum][4]).'</td></tr>';
	$sum[1]=$rowBank[$cnum][4]+$sum[1];//전일 내역 합계
	$sum[2]=$rowBankC[$cnum][2]+$sum[2];//입금 내역 합계
	$sum[3]=$rowBankC[$cnum][3]+$sum[3];//출금 내역 합계
	$sum[4]=$rowBankC[$cnum][4]+$sum[4];//잔액 내역 합계
	}

	// 코스메틱 통장
		$selBI="SELECT * FROM `toto_bankbookinfo` where `use_flag` = '02'";
		$resBI = mysql_query($selBI, $connect);
		$totBI = mysql_num_rows($resBI); // 총 레코드 수
		$bnum=0;
	while($rowBI = mysql_fetch_row($resBI)){
		$bnum++;
		$cnum=$bnum-1;
		$selBank="SELECT * FROM `toto_bankbook` where `reg_date` = '".$foredate."' and bkbk_idid='".$rowBI[0]."'";
		$resBank = mysql_query($selBank, $connect);
		$rowBank2[] = mysql_fetch_row($resBank);//전일 내역

		$selBank="SELECT * FROM `toto_bankbook` where `reg_date` = '".$date_input."' and bkbk_idid='".$rowBI[0]."'";
		$resBank = mysql_query($selBank, $connect);
		$rowBankC2[] = mysql_fetch_row($resBank);//현재 내역

		//통장 내역 출력
	$bank_info=$bank_info.'<tr><td>'.$rowBI[1].'</td><td class="style3">'.comma($rowBank2[$cnum][4]).'</td><td class="style3">'.comma($rowBankC2[$cnum][2]).'</td><td class="style3">'.comma($rowBankC2[$cnum][3]).'</td><td class="style3">'.comma($rowBankC2[$cnum][4]).'</td></tr>';
	$sum[1]=$rowBank2[$cnum][4]+$sum[1];//전일 내역 합계
	$sum[2]=$rowBankC2[$cnum][2]+$sum[2];//입금 내역 합계
	$sum[3]=$rowBankC2[$cnum][3]+$sum[3];//출금 내역 합계
	$sum[4]=$rowBankC2[$cnum][4]+$sum[4];//잔액 내역 합계
	}

	//통장 내역 합계
	$bank_info=$bank_info.'<tr><td>합계</td><td class="style3">'.comma($sum[1]).'</td><td class="style3">'.comma($sum[2]).'</td><td class="style3">'.comma($sum[3]).'</td><td class="style3">'.comma($sum[4]).'</td></tr></table>';

	$updated=$tableD.'
	  <tr>
		<td colspan="2" class="style2">계정과목</td>
		<td class="style2" align="center">피부과</td>
		<td class="style2" align="center">코스메틱</td>
		<td class="style2" align="center">합계</td>
	  </tr>
	  <tr>
		<td colspan="2" class="style2">전일이월</td>
		<td class="cashStyle">'.comma($rowc[1]).'</td>
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
		<td colspan="2" class="style2">현금수입계</td>
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
		<td class="cashStyle">'.comma($rows[8]).'</td>
		<td class="cashStyle">'.comma($rows[19]).'</td>
		<td class="cashStyle">'.comma($rows[8]+$rows[19]).'</td>
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
		<td class="style4">'.comma($rows[2]+$rows[3]+$rows[4]+$rows[5]+$rows[6]+$rows[7]+$rows[8]+$rows[9]).'</td>
		<td class="style4">'.comma($rows[16]+$rows[17]+$rows[18]+$rows[19]+$rows[20]).'</td>
		<td class="style4">'.comma($rows[2]+$rows[3]+$rows[4]+$rows[5]+$rows[6]+$rows[7]+$rows[8]+$rows[9]+$rows[16]+$rows[17]+$rows[18]+$rows[19]++$rows[20]).'</td>
	  </tr>
	</table>';


$updated=$updated.$bank_info;
$updated=$updated.'</table>';

	$objResponse->assign('divtable', 'innerHTML', $updated);
	$objResponse->assign('btn', 'disabled', TRUE);
	$objResponse->assign('btn', 'value', '저장됨');
	
	$objResponse->assign('btn', 'disabled', TRUE);
	$objResponse->assign('btn', 'value', '저장됨');
	$objResponse->assign('end', 'disabled', TRUE);
	$objResponse->assign('end', 'value', '일마감됨');
	$objResponse->assign('tt', 'innerHTML', '마감이 완료되었습니다. 자료수정은 관리자에게 문의하세요.');

}else{//저장된 값이 아닐 경우 현재 저장된 매출, 지출 table에서 자료를 가져옴. 2011-03-13


		//원장 정산 저장 플래그
		$selSql="SELECT `date` FROM `toto_balancedoctor` WHERE `reg_date`=".$date_input;
		$result = mysql_query($selSql, $connect); 
		$row = mysql_fetch_row($result);
		$bdsv_flag=$row[0];


		//피부과 일반 매출
		//김기출 수정 : 외입을 제외한 모든 항목은모두 일반 매출로 계산하여야함
		//$selSql="select `cash_mony`,`cscd_mony`,`card_mony`, `yet__mony` from toto_pay where `sale_code` = '1001' and `slit_code` = '2001' and `insu_code` = '0102' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$selSql="select `cash_mony`,`cscd_mony`,`card_mony`, `yet__mony` from toto_pay where `sale_code` = '1001' and `slit_code` <> '2002' and `insu_code` = '0102' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$row = mysql_fetch_row($result);
		$gen_cash=$gen_cash+$row[0];
		$gen_cscd=$gen_cscd+$row[1];
		$gen_card=$gen_card+$row[2];
		if($row[3]>0){//통장입금액만 별도 저장 2011-04-15
			$bank_in=$bank_in+$row[3];
		}
    if($gen_yet>0)
        $gen_yet=$gen_yet+$row[3];
	}
		//피부과 일반 외상입금
		$selSql="select `cash_mony`,`cscd_mony`,`card_mony`, `yet__mony` from toto_pay where `sale_code` = '1001' and `slit_code` = '2002' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$row = mysql_fetch_row($result);
		$gen_cash_del=$gen_cash_del+$row[0];
		$gen_cscd_del=$gen_cscd_del+$row[1];
		$gen_card_del=$gen_card_del+$row[2];
		$gen_yet_del=$gen_yet_del+$row[3];
	}
		//피부과 보험 매출
		//김기출 수정 : 외입을 제외한 모든 항목은모두 일반 매출로 계산하여야함
		//$selSql="select `cash_mony`,`cscd_mony`,`card_mony`, `yet__mony` from toto_pay where `sale_code` = '1001' and `slit_code` = '2001' and `insu_code` = '0101' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$selSql="select `cash_mony`,`cscd_mony`,`card_mony`, `yet__mony` from toto_pay where `sale_code` = '1001' and `slit_code` <> '2002' and `insu_code` = '0101' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$row = mysql_fetch_row($result);
		$insu_cash=$insu_cash+$row[0];
		$insu_cscd=$insu_cscd+$row[1];
		$insu_card=$insu_card+$row[2];
		if($row[3]>0){
			$bank_in=$bank_in+$row[3];
		}
		$insu_yet=$insu_yet+$row[3];
	}
		//코스메틱 매출
		//김기출 수정 : 외입을 제외한 모든 항목은모두 일반 매출로 계산하여야함
		//$selSql="select `cash_mony`,`cscd_mony`,`card_mony`,`yet__mony` from toto_pay where `sale_code` = '1002' and `slit_code` = '2001' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$selSql="select `cash_mony`,`cscd_mony`,`card_mony`,`yet__mony` from toto_pay where `sale_code` = '1002' and `slit_code` <> '2002' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$row = mysql_fetch_row($result);
		$cos_cash=$cos_cash+$row[0];
		$cos_cscd=$cos_cscd+$row[1];
		$cos_card=$cos_card+$row[2];
		if($row[3]>0){//통장입금액 별도 저장
			$cos_bank=$cos_bank+$row[3];
		}
    if($cos_yet>0)
        $cos_yet=$cos_yet+$row[3];
	}
		//코스메틱 외상입금
		$selSql="select `cash_mony`,`cscd_mony`,`card_mony`,`yet__mony` from toto_pay where `sale_code` = '1002' and `slit_code` = '2002' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$row = mysql_fetch_row($result);
		$cos_cash_del=$cos_cash_del+$row[0];
		$cos_cscd_del=$cos_cscd_del+$row[1];
		$cos_card_del=$cos_card_del+$row[2];
		$cos_yet_del=$cos_yet_del+$row[3];
	}
		//환불 피부과
		//김기출 : 환불표시는 미납(통장)에 있는 내용이 출력되어야 함
		//$selSql="select `cash_mony` from toto_pay where `sale_code` = '1001' and `slit_code` = '2003' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$selSql="select `yet__mony` from toto_pay where `sale_code` = '1001' and `slit_code` = '2003' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$row = mysql_fetch_row($result);
		$d_refd=$d_refd+$row[0];
	}
		//환불 코스메틱
		//김기출 : 환불표시는 미납(통장)에 있는 내용이 출력되어야 함
		//$selSql="select `cash_mony` from toto_pay where `sale_code` = '1001' and `slit_code` = '2003' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$selSql="select `yet__mony` from toto_pay where `sale_code` = '1001' and `slit_code` = '2003' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$row = mysql_fetch_row($result);
		$c_refd=$c_refd+$row[0];
	}
		//현금 지출 피부과
		$selSql="select `cash_mony` from toto_exp where `exps_code` = '4000' and `exps_gubn` = '0' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$row = mysql_fetch_row($result);
		$exp_cash=$exp_cash+$row[0];
	}
		//통장 지출 피부과
		$selSql="select `cash_mony` from toto_exp where `exps_code` = '4000' and `exps_gubn` = '1' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$row = mysql_fetch_row($result);
		$exp_bank=$exp_bank+$row[0];
	}
		//카드(법인) 지출 피부과
		$selSql="select `cash_mony` from toto_exp where `exps_code` = '4000' and `exps_gubn` = '2' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$exp_card_legal=$exp_card_legal+$row[0];
		$row = mysql_fetch_row($result);
	}
		//카드(개인) 지출 피부과
		$selSql="select `cash_mony` from toto_exp where `exps_code` = '4000' and `exps_gubn` = '3' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$row = mysql_fetch_row($result);
		$exp_card_indiv=$exp_card_indiv+$row[0];
	}
		//현금 지출 코스메틱
		$selSql="select `cash_mony` from toto_exp where `exps_code` = '4001' and `exps_gubn` = '0' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$row = mysql_fetch_row($result);
		$exp_cos_cash=$exp_cos_cash+$row[0];
	}
		//통장 지출 코스메틱
		$selSql="select `cash_mony` from toto_exp where `exps_code` = '4001' and `exps_gubn` = '1' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$row = mysql_fetch_row($result);
		$exp_cos_bank=$exp_cos_bank+$row[0];
	}
		//카드(법인) 지출 코스메틱
		$selSql="select `cash_mony` from toto_exp where `exps_code` = '4001' and `exps_gubn` = '2' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$row = mysql_fetch_row($result);
		$exp_cos_card_legal=$exp_cos_card_legal+$row[0];
	}
		//카드(개인) 지출 코스메틱
		$selSql="select `cash_mony` from toto_exp where `exps_code` = '4001' and `exps_gubn` = '3' and `reg_date` between '".$date_input."' and '".$date_input."'";
		$result = mysql_query($selSql, $connect); 
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=$total;
	while($total--){
		$row = mysql_fetch_row($result);
		$exp_cos_card_indiv=$exp_cos_card_indiv+$row[0];
	}
//전일 이월금 구하기
	$rowc=0;
	$year=substr($date_input,0,4);
	$month=substr($date_input,4,2);
	$day=substr($date_input,6,2);
	$foredate=date("Ymd",mktime( 0, 0, 0, $month, $day-1, $year ));
	$selSqlC="SELECT * FROM `toto_closedsale` where `reg_date` = '".$foredate."'";
	$resultC = mysql_query($selSqlC, $connect);
	$rowc = mysql_fetch_row($resultC);
	if($close==0){
		$rows[1]=$gen_cash+$gen_cscd+$insu_cash+$insu_cscd-$exp_cash+$rowc[1];
		$rows[15]=$cos_cash+$cos_cscd-$exp_cos_cash;
	}

	//통장 내역 2011-04-13
	// 피부과 통장
		$selBI="SELECT * FROM `toto_bankbookinfo` where `use_flag` = '01'";
		$resBI = mysql_query($selBI, $connect);
		$totBI = mysql_num_rows($resBI); // 총 레코드 수

		$bank_info='<br /><br />
		<table cellspacing="0" cellpadding="0" border="1">
			<tr>
				<td width="100" class="style2"></td>
				<td width="100" class="style2">전일이월</td>
				<td width="100" class="style2">입금</td>
				<td width="100" class="style2">지출</td>
				<td width="100" class="style2">잔액</td>
			</tr>';

	while($rowBI = mysql_fetch_row($resBI)){
		$bnum++;
		$cnum=$bnum-1;
		$selBank="SELECT * FROM `toto_bankbook` where `reg_date` = '".$foredate."' and bkbk_idid='".$rowBI[0]."'";
		$resBank = mysql_query($selBank, $connect);
		$rowBank[] = mysql_fetch_row($resBank);//전일 내역

		$selBank="SELECT * FROM `toto_bankbook` where `reg_date` = '".$date_input."' and bkbk_idid='".$rowBI[0]."'";
		$resBank = mysql_query($selBank, $connect);
		$totBank=mysql_num_rows($resBank);//레코드가 있는지 확인
		$rowBankC[] = mysql_fetch_row($resBank);//현재 내역
		if(!$totBank)//저장 이전일 경우 잔액을 이전 일로 설정
			$rowBankC[$cnum][4]=$rowBank[$cnum][4];

		// 피부과 통장 내역 출력
	$bank_info=$bank_info.'<tr><form id="fr" name="fr"><td class="style2">'.$rowBI[1].'</td><td class="style4"><input type="hidden" name="skin_bkid_'.$bnum.'" id="skin_bkid_'.$bnum.'" value="'.$rowBankC[$cnum][0].'"><input class="style3" type="text" name="skin_fore_'.$bnum.'" id="skin_fore_'.$bnum.'" value="'.comma($rowBank[$cnum][4]).'" disabled></td><td><input type="text" class="style3" name="skin_in_'.$bnum.'" id="skin_in_'.$bnum.'" onkeypress="Keycode(event,this);" Onkeyup="b_input(this,'.$bnum.',\'skin\')" style="ime-mode:disabled" value="'.comma($rowBankC[$cnum][2]).'" Onkeydown="CheckEnter(this)" ></td><td><input class="style3" type="text" name="skin_out_'.$bnum.'" id="skin_out_'.$bnum.'" onkeypress="Keycode(event,this);" Onkeyup="b_input(this,'.$bnum.',\'skin\')" style="ime-mode:disabled" value="'.comma($rowBankC[$cnum][3]).'" Onkeydown="CheckEnter(this)" ></td><td class="style4"><input type="text" class="style3" name="skin_sum_'.$bnum.'" id="skin_sum_'.$bnum.'" disabled value="'.comma($rowBankC[$cnum][4]).'"></td></tr>';
	$sum[1]=$rowBank[$cnum][4]+$sum[1];//전일 내역 합계
	$sum[2]=$rowBankC[$cnum][2]+$sum[2];//입금 내역 합계
	$sum[3]=$rowBankC[$cnum][3]+$sum[3];//출금 내역 합계
	$sum[4]=$rowBankC[$cnum][4]+$sum[4];//잔액 내역 합계
	}

	// 코스메틱 통장
		$selBI="SELECT * FROM `toto_bankbookinfo` where `use_flag` = '02'";
		$resBI = mysql_query($selBI, $connect);
		$totBI = mysql_num_rows($resBI); // 총 레코드 수
		$bnum=0;
	while($rowBI = mysql_fetch_row($resBI)){
		$bnum++;
		$cnum=$bnum-1;
		$selBank="SELECT * FROM `toto_bankbook` where `reg_date` = '".$foredate."' and bkbk_idid='".$rowBI[0]."'";
		$resBank = mysql_query($selBank, $connect);
		$rowBank2[] = mysql_fetch_row($resBank);//전일 내역

		$selBank="SELECT * FROM `toto_bankbook` where `reg_date` = '".$date_input."' and bkbk_idid='".$rowBI[0]."'";
		$resBank = mysql_query($selBank, $connect);
		
		$totBank=mysql_num_rows($resBank);//레코드가 있는지 확인
		$rowBankC2[] = mysql_fetch_row($resBank);//현재 내역
		if(!$totBank)//저장 이전일 경우 잔액을 이전 일로 설정
			$rowBankC2[$cnum][4]=$rowBank2[$cnum][4];

		//통장 내역 출력
	$bank_info=$bank_info.'<tr><td class="style2">'.$rowBI[1].'</td><td class="style4"><input type="hidden" name="cos_bkid_'.$bnum.'" id="cos_bkid_'.$bnum.'" value="'.$rowBankC2[$cnum][0].'"><input class="style3" type="text" name="cos_fore_'.$bnum.'" id="cos_fore_'.$bnum.'" value="'.comma($rowBank2[$cnum][4]).'" disabled></td><td><input type="text" class="style3" name="cos_in_'.$bnum.'" id="cos_in_'.$bnum.'" onkeypress="Keycode(event,this);" Onkeyup="b_input(this,'.$bnum.',\'cos\')" style="ime-mode:disabled" value="'.comma($rowBankC2[$cnum][2]).'" Onkeydown="CheckEnter(this)" ></td><td><input class="style3" type="text" name="cos_out_'.$bnum.'" id="cos_out_'.$bnum.'" onkeypress="Keycode(event,this);" Onkeyup="b_input(this,'.$bnum.',\'cos\')" style="ime-mode:disabled" value="'.comma($rowBankC2[$cnum][3]).'" Onkeydown="CheckEnter(this)" ></td><td class="style4"><input type="text" class="style3" name="cos_sum_'.$bnum.'" id="cos_sum_'.$bnum.'" disabled value="'.comma($rowBankC2[$cnum][4]).'"></td></form></tr>';
	$sum[1]=$rowBank2[$cnum][4]+$sum[1];//전일 내역 합계
	$sum[2]=$rowBankC2[$cnum][2]+$sum[2];//입금 내역 합계
	$sum[3]=$rowBankC2[$cnum][3]+$sum[3];//출금 내역 합계
	$sum[4]=$rowBankC2[$cnum][4]+$sum[4];//잔액 내역 합계
	}

	//통장 내역 합계
	$bank_info=$bank_info.'<tr><td class="style2">합계</td><td><input class="style6" type="text" value="'.comma($sum[1]).'" id="pre_sum" name="pre_sum" disabled></td><td class="style3"><input class="style6" type="text" id="sum_in" name="sum_in" value="'.comma($sum[2]).'" disabled></td><td class="style3"><input class="style6" type="text" id="sum_out" name="sum_out" value="'.comma($sum[3]).'"  disabled></td><td class="style3"><input class="style6" type="text" value="'.comma($sum[4]).'" id="sum_sum" name="sum_sum" disabled></td></tr></table>';


		$updated=$tableD.'
	  <tr>
		<td colspan="2" class="style2">계정과목</td>
		<td align="center" class="style2">피부과</td>
		<td align="center" class="style2">코스메틱</td>
		<td align="center" class="style2">합계</td>
	  </tr>
	  <tr>
		<td colspan="2" class="style2">전일이월</td>
		<td class="cashStyle">'.comma($rowc[1]).'</td>
		<td class="cashStyle">'.comma($rowc[15]).'</td>
		<td class="cashStyle">'.comma($rowc[1]+$rowc[15]).'</td>
	  </tr>
	  <tr>
		<td width="100" class="style2" rowspan="6">현금</td>
		<td width="100" class="style2">일반</td>
		<td class="cashStyle">'.comma($gen_cash).'</td>
		<td class="cashStyle">'.comma($cos_cash).'</td>
		<td class="cashStyle">'.comma($gen_cash+$cos_cash).'</td>
	  </tr>
	  <tr>
		<td class="style2">현금영수증</td>
		<td class="cashStyle">'.comma($gen_cscd).'</td>
		<td class="cashStyle">'.comma($cos_cscd).'</td>
		<td class="cashStyle">'.comma($gen_cscd+$cos_cscd).'</td>
	  </tr>
	  <tr>
		<td class="style2">보험</div></td>
		<td class="cashStyle">'.comma($insu_cash).'</td>
		<td class="cashStyle">-</td>
		<td class="cashStyle">'.comma($insu_cash).'</td></td>
	  </tr>
	  <tr>
		<td class="style2">보험현금영수증</td>
		<td class="cashStyle">'.comma($insu_cscd).'</td>
		<td class="cashStyle">-</td>
		<td class="cashStyle">'.comma($insu_cscd).'</td>
	  </tr>
	  <tr>
		<td class="style2">소계</td></td>
		<td class="style4">'.comma($gen_cash+$gen_cscd+$insu_cash+$insu_cscd).'</td>
		<td class="style4">'.comma($cos_cash+$cos_cscd).'</td>
		<td class="style4">'.comma($gen_cash+$gen_cscd+$cos_cash+$cos_cscd+$insu_cash+$insu_cscd).'</td>
	  </tr>
	  <tr>
		<td class="style2">외상회수</td>
		<td class="cashStyle">-</td>
		<td class="cashStyle">-</td>
		<td class="cashStyle">-</td>
	  </tr>
	  <tr>
		<td colspan="2" class="style2">현금수입계</td>
		<td class="cashStyle">'.comma($gen_cash+$gen_cscd+$insu_cash+$insu_cscd).'</td>
		<td class="cashStyle">'.comma($cos_cash+$cos_cscd).'</td>
		<td class="cashStyle">'.comma($gen_cash+$gen_cscd+$cos_cash+$cos_cscd+$insu_cash+$insu_cscd).'</td>
	  </tr>
	  <tr>
		<td rowspan="5" class="style2">지출</td>
		<td class="style2">현금일반</td>
		<td class="cashStyle">'.comma($exp_cash).'</td>
		<td class="cashStyle">'.comma($exp_cos_cash).'</td>
		<td class="cashStyle">'.comma($exp_cash+$exp_cos_cash).'</td>
	  </tr>
	  <tr>
		<td class="style2">통장출금</td>
		<td class="cashStyle"><input class="style5" type="text" id="exp_bank" name="exp_bank" value="'.comma($exp_bank).'" disabled></td>
		<td class="cashStyle"><input class="style5" type="text" id="exp_cos" name="exp_cos" value="'.comma($exp_cos_bank).'" disabled></td>
		<td class="cashStyle">'.comma($exp_bank+$exp_cos_bank).'</td>
	  </tr>
	  <tr>
		<td class="style2">카드(법인)</td>
		<td class="cashStyle">'.comma($exp_card_legal).'</td>
		<td class="cashStyle">'.comma($exp_cos_card_legal).'</td>
		<td class="cashStyle">'.comma($exp_card_legal+$exp_cos_card_legal).'</td>
	  </tr>
	  <tr>
		<td class="style2">카드(개인)</td>
		<td class="cashStyle">'.comma($exp_card_indiv).'</td>
		<td class="cashStyle">'.comma($exp_cos_card_indiv).'</td>
		<td class="cashStyle">'.comma($exp_card_indiv+$exp_cos_card_indiv).'</td>
	  </tr>
	  <tr>
		<td class="style2">지출계</td>
		<td class="style4">'.comma($exp_cash+$exp_bank+$exp_card_legal+$exp_card_indiv).'</td>
		<td class="style4">'.comma($exp_cos_cash+$exp_cos_bank+$exp_cos_card_legal+$exp_cos_card_indiv).'</td>
		<td class="style4">'.comma($exp_cash+$exp_cos_cash+$exp_bank+$exp_cos_bank+$exp_card_legal+$exp_cos_card_legal+$exp_card_indiv+$exp_cos_card_indiv).'</td>
	  </tr>
	  <tr>
		<td colspan="2" class="style2">원장인출</td>
		<td class="cashStyle"><input type="text" class="style3" id="d_doct_mony" name="d_doct_mony" onkeypress="Keycode(event,this);"  onkeyup="SumAmount('.($gen_cash+$gen_cscd+$insu_cash+$insu_cscd-$exp_cash+$rowc[1]).',this);" Onkeydown="CheckEnter(this)" style="ime-mode:disabled" value="'.comma($rows[10]).'"></td>
		<td class="cashStyle"><input type="text" class="style3" id="c_doct_mony" name="c_doct_mony" onkeypress="Keycode(event,this);"  onkeyup="SumAmount2('.($cos_cash+$cos_cscd-$exp_cos_cash).',this);" style="ime-mode:disabled" value="'.comma($rows[21]).'"></td>
		<td class="cashStyle" id="doct_mony" name="doct_mony">'.comma($rows[10]+$rows[21]).'</td>
	  </tr>
	  <tr>
		<td colspan="2" class="style2">차일이월</td>
		<td class="cashStyle"><input type="text" class="style3" id="d_afdy_mony" id="d_afdy_mony" value="'.comma($rows[1]).'" disabled></td>
		<td class="cashStyle"><input type="text" class="style3" id="c_afdy_mony" id="c_afdy_mony" value="'.comma($rows[15]).'" disabled></td>
		<td class="cashStyle" id="afdy_mony" name="afdy_mony">'.comma($rows[1]+$rows[15]).'</td>
	  </tr>
	  <tr>
		<td rowspan="4" class="style2">카드</td>
		<td><div align="left" class="style2">일반</div></td>
		<td class="cashStyle">'.comma($gen_card).'</td>
		<td class="cashStyle">'.comma($cos_card).'</td>
		<td class="cashStyle">'.comma($gen_card+$cos_card).'</td>
	  </tr>
	  <tr>
		<td class="style2">보험</td>
		<td class="cashStyle">'.comma($insu_card).'</td>
		<td class="cashStyle">-</td>
		<td class="cashStyle">'.comma($insu_card).'</td>
	  </tr>
	  <tr>
		<td class="style2">소계</td>
		<td class="cashStyle">'.comma($gen_card+$insu_card).'</td>
		<td class="cashStyle">'.comma($cos_card).'</td>
		<td class="cashStyle">'.comma($gen_card+$cos_card+$insu_card).'</td>
	  </tr>
	  <tr>
		<td class="style2">외상회수</td>
		<td class="cashStyle">'.comma($gen_cash_del+$gen_cscd_del+$gen_card_del+$gen_yet__del).'</td>
		<td class="cashStyle">'.comma($cos_cash_del+$cos_cscd_del+$cos_card_del+$cos_yet__del).'</td>
		<td class="cashStyle">'.comma($gen_cash_del+$gen_cscd_del+$gen_card_del+$gen_yet__del+$cos_cash_del+$cos_cscd_del+$cos_card_del+$cos_yet__del).'</td>
	  </tr>
	  <tr>
		<td colspan="2" class="style2">통장입금</td>
		<td class="cashStyle">'.comma($bank_in).'</td>
		<td class="cashStyle">'.comma($cos_bank).'</td>
		<td class="cashStyle">'.comma($bank_in+$cos_bank).'</td>
	  </tr>
	  <tr>
		<td colspan="2" class="style2">매출환불(통장)</td>
		<td class="cashStyle">'.comma($d_refd).'</td>
		<td class="cashStyle">'.comma($c_refd).'</td>
		<td class="cashStyle">'.comma($d_refd+$c_refd).'</td>
	  </tr>
	  <tr>
		<td colspan="2" class="style2">매출총계</td>
		<td class="style4">'.comma($gen_cash+$gen_cscd+$insu_cash+$insu_cscd+$gen_card+$insu_card+$gen_yet+$insu_yet+$gen_cash_del+$gen_cscd_del+$gen_card_del+$gen_yet__del+$d_refd).'</td>
		<td class="style4">'.comma($cos_cash+$cos_cscd+$cos_card+$cos_yet+$cos_cash_del+$cos_cscd_del+$cos_card_del+$cos_yet__del+$cos_bank+$c_refd).'</td>
		<td class="style4">'.comma($gen_cash+$gen_cscd+$cos_cash+$cos_cscd+$insu_cash+$insu_cscd+$gen_card+$cos_card+$insu_card+$gen_yet+$insu_yet+$cos_yet+$gen_cash_del+$gen_cscd_del+$gen_card_del+$gen_yet__del+$cos_cash_del+$cos_cscd_del+$cos_card_del+$cos_yet__del+$cos_bank+$d_refd+$c_refd).'</td>
	  </tr>
	</table>';

	$updated=$updated.$bank_info;
	$updated=$updated.'</table>';

	if($rowc[0]){
		if($rows[1]!=($gen_cash+$gen_cscd+$insu_cash+$insu_cscd-$exp_cash+$rowc[1]-$rows[10])){
			$objResponse->assign('detable', 'innerHTML', '저장금액과 계산 금액이 일치하지 않습니다');
		}else{
			$objResponse->assign('detable', 'innerHTML', '');
		}
	}else{
			$objResponse->assign('detable', 'innerHTML', '');
		}
		if($rows[29]=='Y'){
			$objResponse->assign('btn', 'disabled', FALSE);
			$objResponse->assign('btn', 'value', '수정');
			$objResponse->assign('no', 'value', $rows[0]);
			$objResponse->assign('end', 'disabled', TRUE);
			$objResponse->assign('end', 'value', '일마감요청됨');
			$objResponse->assign('tt', 'innerHTML', '일일마감요청중입니다.');
		}else if($rows[0]){
			$objResponse->assign('btn', 'disabled', FALSE);
			$objResponse->assign('btn', 'value', '수정');
			$objResponse->assign('end', 'disabled', FALSE);
			$objResponse->assign('end', 'value', '일마감요청');
			$objResponse->assign('tt', 'innerHTML', '일일마감요청전입니다. 수정 버튼을 눌러 일일마감요청을 하세요.');
		}else{
			$objResponse->assign('btn', 'disabled', FALSE);
			$objResponse->assign('btn', 'value', '저장');
			$objResponse->assign('end', 'disabled', TRUE);
			$objResponse->assign('end', 'value', '일마감요청');
			$objResponse->assign('tt', 'innerHTML', '일마감 작성중입니다. 작성후 저장을 누르면 일일마감요청을 할 수 있습니다.');
		}
		$objResponse->assign('no', 'value', $rows[0]);
		$objResponse->assign('divtable', 'innerHTML', $updated);
	$msg=$_SESSION['sunap']."님은 ".$setTime."일자 자료입력 중입니다.";
	$objResponse->assign('msgDiv', 'innerHTML', $msg);

}
	return $objResponse;
}


$reqInquiry =& $xajax->registerFunction('inquiry');
$reqSave =& $xajax->registerFunction('save');
$reqSaveBank =& $xajax->registerFunction('saveBank');
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