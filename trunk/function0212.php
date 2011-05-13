<?php
	include_once "menu.php";
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


$tfoot[1002]=$tableD.'
<thead>
	<tr>
	<td class="style2">분류</td>
	<td class="style2">고객명</td>
	<td class="style2">현금</td>
	<td class="style2">현금영수증</td>
	<td class="style2">카드</td>
	<td class="style2">미납(통장)</td>
	<td class="style2" style="width:100px;">&nbsp;&nbsp;합계&nbsp;&nbsp;</td>
	<td class="style2">비고</td>
	<td class="style2"></td>
	</tr>
</thead>
<tfoot><input type="hidden" name="no" id="no" value="" >
<tr><input type="hidden" name="sale_code" id="sale_code" value="1002" >
<td class="tdStyle">
	<select id="slit_code" name="slit_code" onchange="CheckEnter(this);" class="clist">
		<option value="2001">1. 매출</option>
		<option value="2002">2. 외상입금</option>
		<option value="2003">3. 환불</option>
	</select>
</td>
<input type="hidden" name="insu_code" id="insu_code" value="" >
<input type="hidden" name="rmst_code" id="rmst_code" value="" >
<input type="hidden" name="rmdy_doct" id="rmdy_doct" value="" >
<td class="tdStyle"><input type="text" name="customer" id="customer" size="7" Onkeydown="CheckEnter(this)" ></td>
<td class="tdStyle"><input type="text" name="cash" id="cash" size="7" onkeypress="Keycode(event,this);" Onkeydown="CheckEnter(this)" onkeyup="SumAmount(this);" style="ime-mode:disabled"></td>
<td class="tdStyle"><input type="text" name="cash_r" id="cash_r" size="7" onkeypress="Keycode(event,this);" Onkeydown="CheckEnter(this)" onkeyup="SumAmount(this);" style="ime-mode:disabled"></td>
<td class="tdStyle"><input type="text" name="card" id="card" size="7" onkeypress="Keycode(event,this);" Onkeydown="CheckEnter(this)" onkeyup="SumAmount(this);" style="ime-mode:disabled"></td>
<td class="tdStyle"><input type="text" name="nopay" id="nopay" size="7" onkeypress="Keycode(event,this);" Onkeydown="CheckEnter(this)" onkeyup="SumAmount(this);" style="ime-mode:disabled"></td>
<td class="tdStyle" id="sum" name="sum"></td>
<td class="tdStyle"><input type="text" name="etc" id="etc" size="5" Onkeydown="CheckEnter(this)" ></td>
<td class="tdStyle"><input type="button" onclick="CheckCont()" value="저장" ></td>
</tr>
</tfoot>
</table>';

$thead[1001]=$tableD.'
<thead>
	<tr>
	<!--<td class="style2">일자</td>-->
	<td class="style2">분류</td>
	<td class="style2">구분</td>
	<td class="style2">주요진단명</td>
	<td class="style2">진료실</td>
	<td class="style2">고객명</td>
	<td class="style2" style="width:100px;">현금</td>
	<td class="style2">현금영수증</td>
	<td class="style2">카드</td>
	<td class="style2">미납(통장)</td>
	<td class="style2">&nbsp;&nbsp;합계&nbsp;&nbsp;</td>
	<td class="style2">비고</td>
	<td class="style2"></td>
	</tr>
</thead>';

// 반복되는 구조 변수
$thead[1002]=$tableD.'
<thead>
	<tr>
	<td class="style2">분류</td>
	<td class="style2">고객명</td>
	<td class="style2" style="width:100px;">현금</td>
	<td class="style2">현금영수증</td>
	<td class="style2">카드</td>
	<td class="style2">미납(통장)</td>
	<td class="style2" width="70px;">&nbsp;&nbsp;합계&nbsp;&nbsp;</td>
	<td class="style2">비고</td>
	<td class="style2"></td>
	</tr>
</thead>';

$tfoot[1001]=$tableD.'
<thead>
	<tr>
	<td class="style2">분류</td>
	<td class="style2">구분</td>
	<td class="style2">주요진단명</td>
	<td class="style2">진료실</td>
	<td class="style2">고객명</td>
	<td class="style2">현금</td>
	<td class="style2">현금영수증</td>
	<td class="style2">카드</td>
	<td class="style2">미납(통장)</td>
	<td class="style2" style="width:100px;">&nbsp;&nbsp;합계&nbsp;&nbsp;</td>
	<td class="style2">비고</td>
	<td class="style2"></td>
	</tr>
</thead>
<tfoot><input type="hidden" name="no" id="no" value="" >
<tr><input type="hidden" name="sale_code" id="sale_code" value="1001" >
<td class="tdStyle">
<input type="text" name="slit_code" id="slit_code" Onkeydown="CheckEnter(this)" class="clist">
</td>
<td class="tdStyle">
<input type="text" name="insu_code" id="insu_code" Onkeydown="CheckEnter(this)" class="clist">
</td>
<td class="tdStyle">
<input type="text" name="rmst_code" id="rmst_code" Onkeydown="CheckEnter(this)" size="14" class="style1">
</td>
<td class="tdStyle">
<input type="text" name="rmdy_doct" id="rmdy_doct" Onkeydown="CheckEnter(this)" class="clist">
</td>
<td class="tdStyle"><input type="text" name="customer" id="customer" size="7" Onkeydown="CheckEnter(this)" ></td>
<td class="tdStyle"><input type="text" name="cash" id="cash" size="7" onkeypress="Keycode(event,this);" Onkeydown="CheckEnter(this)" onkeyup="SumAmount(this);" style="ime-mode:disabled"></td>
<td class="tdStyle"><input type="text" name="cash_r" id="cash_r" size="7" onkeypress="Keycode(event,this);" Onkeydown="CheckEnter(this)" onkeyup="SumAmount(this);" style="ime-mode:disabled"></td>
<td class="tdStyle"><input type="text" name="card" id="card" size="7" onkeypress="Keycode(event,this);" Onkeydown="CheckEnter(this)" onkeyup="SumAmount(this);" style="ime-mode:disabled"></td>
<td class="tdStyle"><input type="text" name="nopay" id="nopay" size="7" onkeypress="Keycode(event,this);" Onkeydown="CheckEnter(this)" onkeyup="SumAmount(this);" style="ime-mode:disabled"></td>
<td class="tdStyle" id="sum" name="sum"></td>
<td class="tdStyle"><input type="text" name="etc" id="etc" size="5" Onkeydown="CheckEnter(this)" ></td>
<td class="tdStyle"><input type="button" onclick="CheckCont()" value="저장" id="ipBt" name="ipBt"></td>
</tr>
</tfoot>
</table>';

//메뉴
function menu($sale_code){
	$style[1000]=' style="width:4%;background-color:#F0575A;"';
	$style[1001]=' style="width:48%;background-color:#F0575A;"';
	$style[1002]=' style="width:48%;background-color:#F0575A;"';
	$sCol[1001]='color:#FFFFFF;';
	$sCol[1002]='color:#FFFFFF;';
	$sCol[$sale_code]='color:#783723;';
	$style[$sale_code]=' style="font-weight: bolder;background-color:#FFE3E2;"';
	$menuTab='<table border="0" cellspacing="0" cellpadding="0" style="width:100%">
		  <tr>
			<td height="30" align="center"'.$style[1000].'></td>
			<td height="30" align="center"'.$style[1001].'><a href="#" onclick="document.location.href(\'pay.php\')"><span style="'.$sCol[1001].'">일수입(피부과)</span></a></td>
			<td height="30" align="center"'.$style[1002].'><a href="#" onclick="xajax_cosmetic()"><span style="'.$sCol[1002].'">일수입(코스메틱)<span></a></td>
		  </tr>	
		</table>';

	return $menuTab;
}
/*
	Function: parent
	
	parent
*/
function parent(){
	global $thead, $tfoot, $connect, $menu;
	$objResponse = new xajaxResponse();
	if(session_is_registered('reg_date')){
		$date_input=$_SESSION['session_reg_date'];//session에 저장된 날짜를 이용함.
		$setTime=substr($date_input,0,4);
		$setTime=$setTime."-".substr($date_input,4,2);
		$setTime=$setTime."-".substr($date_input,6,2);
	}else{
		$cTime=time();
		$setTime=date("Y-m-d",$cTime);
		$date_input=str_replace("-","",$setTime);// '-' 제거
	}
	$updated=selectSale(1001,$date_input,$date_input);
	$objResponse->assign('date_input', 'value', $setTime);

	$selSql="SELECT * FROM `t_closedsale` where `reg_date` = ".$date_input;
	$result = mysql_query($selSql, $connect); 
	$rows = mysql_fetch_row($result);
	if($rows[30]=="Y"){
		$objResponse->assign('ipBt', 'disabled', TRUE);
	}else{
		$objResponse->assign('ipBt', 'disabled', FALSE);
	}
	$objResponse->assign('content', 'innerHTML', $updated);
	$objResponse->assign('menu', 'innerHTML', $menu);
	$objResponse->assign('tabs2', 'innerHTML', menu(1001));
	$objResponse->call('pp');
	return $objResponse;
}
/*
	Function: cosmetic
	
	cosmetic
*/
function cosmetic()
{
	global $thead, $tfoot, $menu;
	$objResponse = new xajaxResponse();
	$query="코스메틱";
	if(session_is_registered('reg_date')){
		$date_input=$_SESSION['session_reg_date'];//session에 저장된 날짜를 이용함.
		$setTime=substr($date_input,0,4);
		$setTime=$setTime."-".substr($date_input,4,2);
		$setTime=$setTime."-".substr($date_input,6,2);
	}else{
		$cTime=time();
		$setTime=date("Y-m-d",$cTime);
		$date_input=str_replace("-","",$setTime);// '-' 제거
	}
	$updated=selectSale(1002,$date_input,$date_input);
	$inputDiv=$tfoot[1002];
	$menuDiv=menu(1002);
	if($rows[30]=="Y"){
		$objResponse->assign('ipBt', 'disabled', TRUE);
	}else{
		$objResponse->assign('ipBt', 'disabled', FALSE);
	}
	$objResponse->assign('menu', 'innerHTML', $menu);
	$objResponse->assign('tabs2', 'innerHTML', menu(1002));

	$objResponse->assign('date_input', 'value', $setTime);
	$objResponse->assign('content', 'innerHTML', $updated);
	$objResponse->assign('input', 'innerHTML', $inputDiv);
	$objResponse->call('bottom');//div scolling
	return $objResponse;
}
/*
	Function: editBt
	
	editBt
*/
function editBt($no,$sale_code)
{
	global $thead, $tfoot, $connect;;
	$objResponse = new xajaxResponse();
	$selSql="select * from pearl_pay where `no` = ".$no;
	$result = mysql_query($selSql, $connect); 
	$row = mysql_fetch_row($result);
	switch($row[2]){
		case "2001" :
			$sel[2001]='selected="selected"';
			break;
		case "2002" :
			$sel[2002]='selected="selected"';
			break;
		case "2003" :
			$sel[2003]='selected="selected"';
			break;
	}
	switch($row[3]){
		case "0101" :
			$sel[0101]='selected="selected"';
			break;
		case "0102" :
			$sel[0102]='selected="selected"';
			break;
	}
if($sale_code==1002){
	$inputDiv='<table border="1" style="text-align:center;width:990px;">
<thead>
	<tr>
	<td class="style2">분류</td>
	<td class="style2">고객명</td>
	<td class="style2">현금</td>
	<td class="style2">현금영수증</td>
	<td class="style2">카드</td>
	<td class="style2">미납(통장)</td>
	<td class="style2">&nbsp;&nbsp;합계&nbsp;&nbsp;</td>
	<td class="style2">비고</td>
	<td class="style2"></td>
	</tr>
</thead>
<tfoot><input type="hidden" name="no" id="no" value="'.$no.'" >
<tr><input type="hidden" name="sale_code" id="sale_code" value="'.$sale_code.'" >
<td class="style2">
	<select id="slit_code" name="slit_code" onchange="CheckEnter(this);" class="clist">
		<option value="2001" '.$sel[2001].'>1. 매출</option>
		<option value="2002" '.$sel[2002].'>2. 외상입금</option>
		<option value="2003" '.$sel[2003].'>3. 환불</option>
	</select>
</td>
<input type="hidden" name="insu_code" id="insu_code" value="" >
<input type="hidden" name="rmst_code" id="rmst_code" value="" >
<input type="hidden" name="rmdy_doct" id="rmdy_doct" value="" >
<td class="style2"><input type="text" name="customer" id="customer" size="7" Onkeydown="CheckEnter(this)" value='.$row[7].'></td>
<td class="cash"><input type="text" name="cash" id="cash" size="7" onkeypress="Keycode(event,this);" Onkeydown="CheckEnter(this)" onkeyup="SumAmount(this);" style="ime-mode:disabled" value='.comma($row[8]).'></td>
<td class="cscd"><input type="text" name="cash_r" id="cash_r" size="7" onkeypress="Keycode(event,this);" Onkeydown="CheckEnter(this)" onkeyup="SumAmount(this);" style="ime-mode:disabled" value='.comma($row[9]).'></td>
<td class="card"><input type="text" name="card" id="card" size="7" onkeypress="Keycode(event,this);" Onkeydown="CheckEnter(this)" onkeyup="SumAmount(this);" style="ime-mode:disabled" value='.comma($row[10]).'></td>
<td class="style2"><input type="text" name="nopay" id="nopay" size="7" onkeypress="Keycode(event,this);" Onkeydown="CheckEnter(this)" onkeyup="SumAmount(this);" style="ime-mode:disabled" value='.comma($row[11]).'></td>
<td class="style2" id="sum" name="sum">'.comma($row[8]+$row[9]+$row[10]+$row[11]).'</td>
<td class="style2"><input type="text" name="etc" id="etc" size="5" Onkeydown="CheckEnter(this)" value='.$row[16].'></td>
<td class="style2"><input type="button" onclick="CheckCont()" value="저장" ></td>
</tr>
</tfoot>
</table>';
	$objResponse->assign('input', 'innerHTML', $inputDiv);
}else{
	$query="SELECT * FROM `pearl_payc` order by `rmst_name` asc";
   	$resultC = mysql_query($query, $connect); 
	$totalC = mysql_affected_rows(); // 총 레코드 수
	$numC=0;
	while($totalC--){
		$numC++;
	$rowc = mysql_fetch_row($resultC);
		if($rowc[1]==$row[4]){
			if($numC<10)
				$no='0'.$numC;
			else
				$no=$numC;
			$row[4]=$no.". ".$rowc[2];
		}
	}

	$query="SELECT * FROM `pearl_code` where `cate`='slit_code' order by `name` asc";
   	$resultC = mysql_query($query, $connect); 
	$totalC = mysql_affected_rows(); // 총 레코드 수
	$numC=0;
	while($totalC--){
		$numC++;
	$rowc = mysql_fetch_row($resultC);
		if($rowc[1]==$row[2]){
			if($numC<10)
				$no='0'.$numC;
			else
				$no=$numC;
			$row[2]=$no.". ".$rowc[2];
		}
	}

	$query="SELECT * FROM `pearl_code` where `cate`='insu_code' order by `name` asc";
   	$resultC = mysql_query($query, $connect); 
	$totalC = mysql_affected_rows(); // 총 레코드 수
	$numC=0;
	while($totalC--){
		$numC++;
	$rowc = mysql_fetch_row($resultC);
		if($rowc[1]==$row[3]){
			if($numC<10)
				$no='0'.$numC;
			else
				$no=$numC;
			$row[3]=$no.". ".$rowc[2];
		}
	}

	$query="SELECT * FROM `t_doctor` order by `doct_name` asc";
   	$resultC = mysql_query($query, $connect); 
	$totalC = mysql_affected_rows(); // 총 레코드 수
	$numC=0;
	while($totalC--){
		$numC++;
	$rowc = mysql_fetch_row($resultC);
		if($rowc[1]==$row[5]){
			if($numC<10)
				$no='0'.$numC;
			else
				$no=$numC;
			$row[5]=$no.". ".$rowc[2];
		}
	}

	$objResponse->assign('no', 'value', $row[0]);
	$objResponse->assign('sale_code', 'value', $row[1]);
	$objResponse->assign('slit_code', 'value', $row[2]);
	$objResponse->assign('insu_code', 'value', $row[3]);
	$objResponse->assign('rmst_code', 'value', $row[4]);
	$objResponse->assign('rmdy_doct', 'value', $row[5]);
	$objResponse->assign('customer', 'value', $row[7]);
	$objResponse->assign('cash', 'value', comma($row[8]));
	$objResponse->assign('cash_r', 'value', comma($row[9]));
	$objResponse->assign('card', 'value', comma($row[10]));
	$objResponse->assign('nopay', 'value', comma($row[11]));
	$objResponse->assign('sum', 'innerHTML', comma($row[8]+$row[9]+$row[10]+$row[11]));
	$objResponse->assign('etc', 'value', $row[16]);
}
	$reg_date=substr($row[14],0,4);
	$reg_date=$reg_date."-".substr($row[14],4,2);
	$reg_date=$reg_date."-".substr($row[14],6,2);
	$objResponse->assign('date_input', 'value', $reg_date);
//	$objResponse->assign('div2', 'innerHTML', $query);
	$objResponse->call('top');//div scolling
	return $objResponse;
}
/*
	Function: preview
	
	preview
*/
function preview($start_date,$end_date){
	global $thead, $tfoot, $connect;
	$objResponse = new xajaxResponse();
	$updated='<div style="width:400px;text-align:right;background-color:white;"><a href="#" onclick="previewH();">닫기</a></div><table border="1" style="text-align:center;width:400px;background-color:white;">';
	$updated=$updated.'<tr>
	<td class="style2">카테고리</td>
	<td class="style2">항목</td>
	<td class="style2">합계</td>
	</tr>';
	
	$start_date=str_replace("-","",$start_date);// '-' 제거
	$end_date=str_replace("-","",$end_date);// '-' 제거

	//보험
	$selSql="select `cash_mony`,`cscd_mony`,`card_mony`,`yet__mony` from pearl_pay where `sale_code` = '1001' and `insu_code` = '0101' and `reg_date` between '".$start_date."' and '".$end_date."'";
	$result = mysql_query($selSql, $connect); 
	$total = mysql_affected_rows(); // 총 레코드 수
	$num=$total;
while($total--){
	$row = mysql_fetch_row($result);
	$sum_cash=$sum_cash+$row[0];
	$sum_cscd=$sum_cscd+$row[1];
	$sum_card=$sum_card+$row[2];
	$sum_yet=$sum_yet+$row[3];
}
	$sum_ins=$sum_cash+$sum_cscd+$sum_card+$sum_yet;
	$updated=$updated.'<tr>
	<td class="style2">보험</td>
	<td class="style2">현금</td>
	<td class="style2">'.comma($sum_cash).'</td>
	</tr>';
	$updated=$updated.'<tr>
	<td class="style2">보험</td>
	<td class="style2">현금영수증</td>
	<td class="style2">'.comma($sum_cscd).'</td>
	</tr>';
	$updated=$updated.'<tr>
	<td class="style2">보험</td>
	<td class="style2">카드</td>
	<td class="style2">'.comma($sum_card).'</td>
	</tr>';
	$updated=$updated.'<tr>
	<td class="style2">보험</td>
	<td class="style2">미납</td>
	<td class="style2">'.comma($sum_yet).'</td>
	</tr>';
	$updated=$updated.'<tr>
	<td class="style2">보험</td>
	<td class="style2">소계</td>
	<td class="style2">'.comma($sum_ins).'</td>
	</tr>';

	//일반
	$selSql="select `cash_mony`,`cscd_mony`,`card_mony`,`yet__mony` from pearl_pay where `sale_code` = '1001' and `insu_code` = '0102' and `reg_date` between '".$start_date."' and '".$end_date."'";
	$result = mysql_query($selSql, $connect); 
	$total = mysql_affected_rows(); // 총 레코드 수
	$num=$total;
	$sum_cash=0;
	$sum_cscd=0;
	$sum_card=0;
	$sum_yet=0;
while($total--){
	$row = mysql_fetch_row($result);
	$sum_cash=$sum_cash+$row[0];
	$sum_cscd=$sum_cscd+$row[1];
	$sum_card=$sum_card+$row[2];
	$sum_yet=$sum_yet+$row[3];
}
	$sum_nin=$sum_cash+$sum_cscd+$sum_card+$sum_yet;
	$updated=$updated.'<tr>
	<td class="style2">일반</td>
	<td class="style2">현금</td>
	<td class="style2">'.comma($sum_cash).'</td>
	</tr>';
	$updated=$updated.'<tr>
	<td class="style2">일반</td>
	<td class="style2">현금영수증</td>
	<td class="style2">'.comma($sum_cscd).'</td>
	</tr>';
	$updated=$updated.'<tr>
	<td class="style2">일반</td>
	<td class="style2">카드</td>
	<td class="style2">'.comma($sum_card).'</td>
	</tr>';
	$updated=$updated.'<tr>
	<td class="style2">일반</td>
	<td class="style2">미납</td>
	<td class="style2">'.comma($sum_yet).'</td>
	</tr>';
	$updated=$updated.'<tr>
	<td class="style2">일반</td>
	<td class="style2">소계</td>
	<td class="style2">'.comma($sum_nin).'</td>
	</tr>';

//진료실
	$updated=$updated.'<tr>
	<td class="style2">진료실</td>
	<td class="style2">이선영</td>
	<td class="style2">'.comma($sum_ins+$sum_nin).'</td>
	</tr>';
	$updated=$updated.'<tr>
	<td class="style2">진료실</td>
	<td class="style2">소계</td>
	<td class="style2">'.comma($sum_ins+$sum_nin).'</td>
	</tr>';

	//코스메틱
	$selSql="select `cash_mony`,`cscd_mony`,`card_mony`,`yet__mony` from pearl_pay where `sale_code` = '1002' and `reg_date` between '".$start_date."' and '".$end_date."'";
	$result = mysql_query($selSql, $connect); 
	$total = mysql_affected_rows(); // 총 레코드 수
	$num=$total;
	$sum_cash=0;
	$sum_cscd=0;
	$sum_card=0;
	$sum_yet=0;
while($total--){
	$row = mysql_fetch_row($result);
	$sum_cash=$sum_cash+$row[0];
	$sum_cscd=$sum_cscd+$row[1];
	$sum_card=$sum_card+$row[2];
	$sum_yet=$sum_yet+$row[3];
}
	$updated=$updated.'<tr>
	<td class="style2">코스메틱</td>
	<td class="style2">현금</td>
	<td class="style2">'.comma($sum_cash).'</td>
	</tr>';
	$updated=$updated.'<tr>
	<td class="style2">코스메틱</td>
	<td class="style2">현금영수증</td>
	<td class="style2">'.comma($sum_cscd).'</td>
	</tr>';
	$updated=$updated.'<tr>
	<td class="style2">코스메틱</td>
	<td class="style2">카드</td>
	<td class="style2">'.comma($sum_card).'</td>
	</tr>';
	$updated=$updated.'<tr>
	<td class="style2">코스메틱</td>
	<td class="style2">미납</td>
	<td class="style2">'.comma($sum_yet).'</td>
	</tr>';
	$updated=$updated.'<tr>
	<td class="style2">코스메틱</td>
	<td class="style2">소계</td>
	<td class="style2">'.comma($sum_cash+$sum_cscd+$sum_card+$sum_yet).'</td>
	</tr>';

	$updated=$updated.'</table>';
	$objResponse->assign('pdiv', 'innerHTML', $updated);
//	$objResponse->assign('div2', 'innerHTML', $selSql);
	$objResponse->call('preview');
	return $objResponse;
}
/*
	Function: inquiry
	
	inquiry
*/
function inquiry($sale_code,$start_date,$end_date)
{
	global $thead, $tfoot, $connect;
	$objResponse = new xajaxResponse();
	$query="inquiry".$sale_code;
	$start_date=str_replace("-","",$start_date);// '-' 제거
	$end_date=str_replace("-","",$end_date);// '-' 제거
	$updated=selectSale($sale_code,$start_date,$end_date);
	$inputDiv=$tfoot[$sale_code];

	$selSql="SELECT * FROM `t_closedsale` where `reg_date` = ".$start_date;
	$result = mysql_query($selSql, $connect); 
	$rows = mysql_fetch_row($result);
	if($rows[25]!="Y"){
		if($sale_code==1001){
			$objResponse->call('fr.reset()');
		}else{
			$objResponse->assign('input', 'innerHTML', $inputDiv);
		}
	}else{
		$objResponse->assign('input', 'innerHTML', '<input type="hidden" name="sale_code" id="sale_code" value="1001" >');
	}
	if($rows[30]=="Y"){
		$objResponse->assign('ipBt', 'disabled', TRUE);
	}else{
		$objResponse->assign('ipBt', 'disabled', FALSE);
	}
//	$objResponse->assign('div2', 'innerHTML', $query);
	$objResponse->assign('content', 'innerHTML', $updated);
	$objResponse->call('bottom');//div scolling
	return $objResponse;
}

/*
	Function: selectSale
	
	seclectSale
*/
function selectSale($saleCode,$start_date,$end_date)
{
	global $thead, $tfoot,$connect;

	session_register("reg_date");
	$_SESSION['session_reg_date']=$start_date;

	$selSqlC="SELECT * FROM `t_closedsale` where `reg_date` = ".$start_date;
	$resultC = mysql_query($selSqlC, $connect); 
	$rowc = mysql_fetch_row($resultC);//마감 여부

	$selSql="select * from pearl_pay where `sale_code` = '".$saleCode."' and `reg_date` between '".$start_date."' and '".$end_date."' order by `no` desc";
	$result = mysql_query($selSql, $connect); 
	$total = mysql_affected_rows(); // 총 레코드 수
	$num=$total;
	$updated=$thead[$saleCode].'<tbody>';
while($total--){
	$row = mysql_fetch_row($result);
	switch($row[2]){
		case "2001" :
			$row[2]="매출";
			break;
		case "2002" :
			$row[2]="외상입금";
			break;
		case "2003" :
			$row[2]="환불";
			break;
	}
	switch($row[3]){
		case "0101" :
			$row[3]="보험";
			break;
		case "0102" :
			$row[3]="일반";
			break;
	}
	
	$selSql2="select `rmst_name` from pearl_payc where `rmst_code` = '".$row[4]."'";
	$result2 = mysql_query($selSql2, $connect); 
	$row2 = mysql_fetch_row($result2);
	$row[4]=$row2[0];

	$query="SELECT `doct_name` FROM `t_doctor` where `doct_numb`='".$row[5]."'";
   	$resultC = mysql_query($query, $connect); 
	$rowc = mysql_fetch_row($resultC);
	$row[5]=$rowc[0];

	$row[15]=date("m/j",$row[15]);//실제 입력일
	$sum=$row[8]+$row[9]+$row[10]+$row[11];
	$reg_date=substr($row[14],2,2);
	$reg_date=$reg_date."/".substr($row[14],4,2);
	$reg_date=$reg_date."/".substr($row[14],6,2);
	$updated=$updated.'<tr>
	<!--<td class="tdStyle">'.$reg_date.'</td>-->
	<td class="tdStyle">'.$row[2].'</td>';

if($saleCode=="1001"){
	$updated=$updated.'<td class="tdStyle">'.$row[3].'</td>
	<td class="tdStyle">'.$row[4].'</td>
	<td class="tdStyle">'.$row[5].'</td>';
	}

	$updated=$updated.'<td class="tdStyle">'.$row[7].'</td>
	<td class="tdStyle">'.comma($row[8]).'</td>
	<td class="tdStyle">'.comma($row[9]).'</td>
	<td class="tdStyle">'.comma($row[10]).'</td>
	<td class="tdStyle">'.comma($row[11]).'</td>
	<td class="tdStyle">'.comma($sum).'</td>
	<td class="tdStyle">'.$row[16].'</td>';

	if($rowc[30]!="Y"){
		$updated=$updated.'<td class="tdStyle"><input type="button" value="E" onclick="xajax_editBt('.$row[0].','.$row[1].')" style="width:30px;"><input type="button" value="D" onclick="xajax_deleteId('.$row[0].','.$row[1].', xajax.$(\'date_input\').value,xajax.$(\'date_input\').value)" style="width:30px;"></td>';
	}else{
		$updated=$updated.'<td>마감(요청)</td>';
	}

	
	$updated=$updated.'</tr>';

	}
	$updated=$updated.'</tbody></table>';
	return $updated;
}
/*
	Function: deleteId
	
	delete
*/
function deleteId($id,$saleCode,$date_start,$date_end)
{
	global $thead, $tfoot, $connect;
	$objResponse = new xajaxResponse();
	$query="delete from `pearl_pay` where `no`=".$id;
	$execQue = mysql_query($query, $connect);

	$date_start=str_replace("-","",$date_start);// '-' 제거
	$date_end=str_replace("-","",$date_end);// '-' 제거
	$updated=selectSale($saleCode,$date_start,$date_end);
	$inputDiv=$tfoot[$saleCode];

//	$objResponse->assign('div2', 'innerHTML', $query.$date_start);
	$objResponse->assign('content', 'innerHTML', $updated);
//	$objResponse->assign('input', 'innerHTML', $inputDiv);
	$objResponse->call('bottom');//div scolling
	return $objResponse;
}


/*
	Function: savePay
	
	save
*/
function savePay($no,$sale_code,$slit_code,$insu_code,$rmst_code,$rmdy_doct,$cust_name,$cash_mony,$cscd_mony,$card_mony,$yet__mony,$slit_desc,$etc,$date_input)
{
	global $thead, $tfoot, $connect;
	$objResponse = new xajaxResponse();
	$c_time=time();
	$cust_cnum="111";
	$cash_mony=str_replace(",","",$cash_mony);// ',' 제거
	$cscd_mony=str_replace(",","",$cscd_mony);// ',' 제거
	$card_mony=str_replace(",","",$card_mony);// ',' 제거
	$yet__mony=str_replace(",","",$yet__mony);// ',' 제거
	$setTime=$date_input;
	$date_input=str_replace("-","",$date_input);// '-' 제거
	//자동완성기능을 쓸 경우 문자열->코드
if($sale_code=="1001"){
	$ec=explode(". ",$rmst_code);// 번호가 넘어오더라도, 번호 앞을 무시하고, 뒤만 참고하므로, 순서는 상관없다
	$query="SELECT `rmst_code` FROM `pearl_payc` WHERE `rmst_name` = '".$ec[1]."'";
   	$resultC = mysql_query($query, $connect); 
	$rowc = mysql_fetch_row($resultC);
	$rmst_code=$rowc[0];
	
	$ec=explode(". ",$slit_code);// 번호가 넘어오더라도, 번호 앞을 무시하고, 뒤만 참고하므로, 순서는 상관없다
	$query="SELECT `code` FROM `pearl_code` WHERE `name` = '".$ec[1]."'";
   	$resultC = mysql_query($query, $connect); 
	$rowc = mysql_fetch_row($resultC);
	$slit_code=$rowc[0];

	$ec=explode(". ",$insu_code);// 번호가 넘어오더라도, 번호 앞을 무시하고, 뒤만 참고하므로, 순서는 상관없다
	$query="SELECT `code` FROM `pearl_code` WHERE `name` = '".$ec[1]."'";
   	$resultC = mysql_query($query, $connect); 
	$rowc = mysql_fetch_row($resultC);
	$insu_code=$rowc[0];

	$ec=explode(". ",$rmdy_doct);// 번호가 넘어오더라도, 번호 앞을 무시하고, 뒤만 참고하므로, 순서는 상관없다
	$query="SELECT `doct_numb` FROM `t_doctor` WHERE `doct_name` = '".$ec[1]."'";
   	$resultC = mysql_query($query, $connect); 
	$rowc = mysql_fetch_row($resultC);
	$rmdy_doct=$rowc[0];
}
	if($no){
		$query="UPDATE `pearl_pay` SET `sale_code` = '".$sale_code."', `slit_code` = '".$slit_code."', `insu_code` = '".$insu_code."', `rmst_code` = '".$rmst_code."', `rmdy_doct` = '".$rmdy_doct."', `cust_cnum` = '111', `cust_name` = '".$cust_name."', `cash_mony` = '".$cash_mony."', `cscd_mony` = '".$cscd_mony."', `card_mony` = '".$card_mony."', `yet__mony` = '".$yet__mony."', `slit_desc` = '', `sort_numb` = '', `reg_date` = '".$date_input."', `date` = '".$c_time."', `etc` = '".$etc."' WHERE `no` =".$no;
	}else{
		$query="INSERT INTO `gachool`.`pearl_pay` (`no`, `sale_code`, `slit_code`, `insu_code`, `rmst_code`, `rmdy_doct`, `cust_cnum`, `cust_name`, `cash_mony`, `cscd_mony`, `card_mony`, `yet__mony`, `slit_desc`, `sort_numb`, `reg_date`, `date`, `etc`) VALUES (NULL, '".$sale_code."', '".$slit_code."', '".$insu_code."', '".$rmst_code."', '".$rmdy_doct."', '".$cust_cnum."', '".$cust_name."', '".$cash_mony."', '".$cscd_mony."', '".$card_mony."', '".$yet__mony."', '".$slit_desc."', '".$sum."', '".$date_input."', '".$c_time."', '".$etc."');";
	}
    $saveQue = mysql_query($query, $connect);

	$updated=selectSale($sale_code,$date_input,$date_input);

	$objResponse->call('fr.reset()');
//	$objResponse->assign('div2', 'innerHTML', $query);
	$objResponse->assign('content', 'innerHTML', $updated);
	$objResponse->call('bottom');//div scolling
	
	return $objResponse;
}

/*
	Section:  Register functions
	
	- <savePay>
	- <deleteId>
	- <cosmetic>
	- <preview>
*/
$reqSavePayBtn =& $xajax->registerFunction('savePay');

$reqDelBtn =& $xajax->registerFunction('deleteId');

$reqCosmetic =& $xajax->registerFunction('cosmetic');

$reqInquiry =& $xajax->registerFunction('inquiry');
$reqInquiry->setParameter(0, XAJAX_INPUT_VALUE, 'sale_code');
$reqInquiry->setParameter(1, XAJAX_INPUT_VALUE, 'date_start');
$reqInquiry->setParameter(2, XAJAX_INPUT_VALUE, 'date_end');

$reqPreview =& $xajax->registerFunction('preview');

$reqParent =& $xajax->registerFunction('parent');

$reqEditBt =& $xajax->registerFunction('editBt');
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