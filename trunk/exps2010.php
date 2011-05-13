<?php
session_start();
if(session_is_registered("session_userid")){
	$logout="<a href='logout.php'>로그아웃</a>";
}else{
	header("Location:index.php");
}
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


//comma를 보여주는 함수
function comma($number){
	$nl=strlen($number);
	if($nl>6){
		$no=substr($number,0,$nl-6).",".substr($number,$nl-6,3).",".substr($number,-3);
	}
	else if($nl>3){
		$no=substr($number,0,$nl-3).",".substr($number,-3);
	}else{
		$no=$number;
	}
	return $no;
}

// 반복되는 구조 변수

$tfoot[4001]='<tfoot>
<tr><input type="hidden" name="no" id="no" value="" ><input type="hidden" name="etc" id="etc" value="" >
<td class="style2"><input type="hidden" name="exps_code" id="exps_code" value="4001" ></td>
<td class="style2">
	<select id="exps_gubn" name="exps_gubn" onchange="CheckEnter(this);" class="clist">
		<option value="0">1. 현금</option>
		<option value="1">2. 통장</option>
		<option value="2">3. 카드(법인)</option>
		<option value="3">4. 카드(개인)</option>
	</select>
</td>
<td class="style2">
	<select id="exps_cate" name="exps_cate" onchange="CheckEnter(this);" class="clist">
		<option value="1503">1. 화장품</option>
		<option value="1604">2. 잡비</option>
	</select>
</td>
<td class="style2"><input type="text" name="exps_cust" id="exps_cust" size="17" Onkeydown="CheckEnter(this)" ></td>
<td class="style2"><input type="text" name="exps_caus" id="exps_caus" size="17" Onkeydown="CheckEnter(this)" ></td>
<td class="style2"><input type="text" name="cash_mony" id="cash_mony" size="15"  onkeypress="Keycode(event,this);" Onkeydown="CheckEnter(this)" onkeyup="SumAmount(this);" style="ime-mode:disabled" ></td>
<td class="style2"><input type="button" onclick="CheckCont()" value="저장" ></td>
</tr>
</tfoot>';

$thead[4000]='<table border="1" style="text-align:center;width:100%">
<thead>
	<tr>
	<td class="style2">선택</td>
	<td class="style2">종류</td>
	<td class="style2">계정과목</td>
	<td class="style2">적요</td>
	<td class="style2">지출처</td>
	<td class="style2">금액</td>
	<td class="style2"></td>
	</tr>
</thead>';
$thead[4001]=$thead[4000];
$tfoot[4000]='<tfoot>
<tr><input type="hidden" name="no" id="no" value="" ><input type="hidden" name="etc" id="etc" value="" >
<td class="style2"><input type="hidden" name="exps_code" id="exps_code" value="4000" ></td>
<td class="style2">
	<select id="exps_gubn" name="exps_gubn" onchange="CheckEnter(this);" class="clist">
		<option value="0">1. 현금</option>
		<option value="1">2. 통장</option>
	</select>
</td>
<td class="style2">';
/*$tfoot[4000]=$tfoot[4000].'<select id="exps_cate" name="exps_cate" onchange="CheckEnter(this);" class="clist">
		<option value="0" selected="selected">선택</option>';

	$selSql2="select * from pearl_expc";
	$result2 = mysql_query($selSql2, $connect); 
	$total = mysql_affected_rows(); // 총 레코드 수
	$num=$total;

	while($total--){
	$row2 = mysql_fetch_row($result2);
		$tfoot[4000]=$tfoot[4000].'<option value="'.$row2[1].'">'.($num-$total).'. '.$row2[3].'</option>';
	}

$tfoot[4000]=$tfoot[4000].'</select>';*/
$tfoot[4000]=$tfoot[4000].'<input type="text" name="exps_cate" id="exps_cate" Onkeydown="CheckEnter(this)" >
</td>
<td class="style2"><input type="text" name="exps_cust" id="exps_cust" size="17" Onkeydown="CheckEnter(this)" ></td>
<td class="style2"><input type="text" name="exps_caus" id="exps_caus" size="17" Onkeydown="CheckEnter(this)" ></td>
<td class="style2"><input type="text" name="cash_mony" id="cash_mony" size="15" onkeypress="Keycode(event,this);" Onkeydown="CheckEnter(this)" onkeyup="SumAmount(this);" style="ime-mode:disabled"></td>
<td class="style2"><input type="button" onclick="CheckCont()" value="저장" ></td>
</tr>
</tfoot>';

$thead[4002]='<table border="1" style="text-align:center;width:100%">
<thead>
	<tr>
	<td class="style2">선택</td>
	<td class="style2">이용카드</td>
	<td class="style2">가맹점</td>
	<td class="style2">내역</td>
	<td class="style2">금액</td>
	<td class="style2"></td>
	</tr>
</thead>';
$thead[4003]=$thead[4002];
$tfoot[4002]='<tfoot><input type="hidden" name="exps_gubn" id="exps_gubn" value="" >
<tr><input type="hidden" name="no" id="no" value="" ><input type="hidden" name="etc" id="etc" value="" >
<td class="style2"><input type="hidden" name="exps_code" id="exps_code" value="" ></td>
<td class="style2">
	<select id="exps_cate" name="exps_cate" onchange="CheckEnter(this);" class="clist">
		<option value="0" selected="selected">선택</option>
		<option value="2201">1. 하나BC</option>
		<option value="2202">2. 현대비자</option>
	</select>
</td>
<td class="style2"><input type="text" name="exps_cust" id="exps_cust" size="17" Onkeydown="CheckEnter(this)" ></td>
<td class="style2"><input type="text" name="exps_caus" id="exps_caus" size="17" Onkeydown="CheckEnter(this)" ></td>
<td class="style2"><input type="text" name="cash_mony" id="cash_mony" size="15"  onkeypress="Keycode(event,this);" Onkeydown="CheckEnter(this)" onkeyup="SumAmount(this);" style="ime-mode:disabled" ></td>
<td class="style2"><input type="button" onclick="CheckCont()" value="저장" ></td>
</tr>
</tfoot>
</table>';
$tfoot[4003]=$tfoot[4002];
/*
	Function: inquiry
	
	inquiry
*/
function inquiry($exps_code,$start_date,$end_date)
{
	global $connect, $tfoot, $thead;
	$objResponse = new xajaxResponse();
	$query="inquiry".$start_date.$end_date;
	$start_date=str_replace("-","",$start_date);// '-' 제거
	$end_date=str_replace("-","",$end_date);// '-' 제거

	$inputDiv=$thead[$exps_code].$tfoot[$exps_code].'</table>';
	$selSql="SELECT * FROM `t_closedsale` where `reg_date` = ".$start_date;
	$result = mysql_query($selSql, $connect); 
	$rows = mysql_fetch_row($result);
	if($rows[25]!="Y"){
		$objResponse->assign('input', 'innerHTML', $inputDiv);
	}else{
		$objResponse->assign('input', 'innerHTML', '<input type="hidden" name="exps_code" id="exps_code" value="4000" >');
	}

	$updated=selectSale($exps_code,$start_date,$end_date);
	$objResponse->assign('div2', 'innerHTML', $query);
	$objResponse->assign('content', 'innerHTML', $updated);
	$objResponse->call('bottom');//div scolling
	return $objResponse;
}
/*
	Function: parent
	
	parent
*/
function parent($exps_code){
	global $thead, $tfoot, $connect, $logout;
	$objResponse = new xajaxResponse();
	$inputDiv=$thead[$exps_code].$tfoot[$exps_code].'</table>';
	$cTime=time();
	$setTime=date("Y-m-d",$cTime);
	$date_input=str_replace("-","",$setTime);// '-' 제거
	$updated=selectSale($exps_code,$date_input,$date_input).'</table>';
	$menuDiv='<a href="pay.php">수납</a>';
	if($_SESSION['session_userid']=="admin"){
		$menuDiv=$menuDiv.' <a href="view.php">검토</a> <a href="report.php">보고서<a> ';
	}
		$menuDiv=$menuDiv.' '.$logout.'<HR>
<a href="pay.php">수입부 입력</a> <a href="exp.php">지출부 입력</a> <a href="static.php">지출부 집계</a> <a href="sum.php">일정산<a><hr />
<a href="exp.php">일지출(피부과)</a> <a href="#" onclick="xajax_parent(4001)">일지출(코스메틱)</a> <a href="#" onclick="xajax_parent(4002)">카드(법인)</a>  <a href="#" onclick="xajax_parent(4003)">카드(원장개인)</a> ';
	$objResponse->assign('date_input', 'value', $setTime);
	$objResponse->assign('content', 'innerHTML', $updated);

	$selSql="SELECT * FROM `t_closedsale` where `reg_date` = ".$date_input;
	$result = mysql_query($selSql, $connect); 
	$rows = mysql_fetch_row($result);
	if($rows[25]!="Y"){
		if($exps_code!=4000)
		$objResponse->assign('input', 'innerHTML', $inputDiv);
	}else{
		$objResponse->assign('input', 'innerHTML', '<input type="hidden" name="exps_code" id="exps_code" value="4000" >');
	}

	$objResponse->assign('menu', 'innerHTML', $menuDiv);
	$objResponse->assign('exps_code', 'value', $exps_code);
	$objResponse->call('pp');
	return $objResponse;
}
/*
	Function: preview
	
	preview
*/
function preview(){
	global $theadC, $tfootC, $thead, $tfoot, $connect;
	$objResponse = new xajaxResponse();
	$updated='<div style="width:100%;text-align:right;background-color:white;"><a href="#" onclick="previewH();">닫기</a></div><table border="1" style="text-align:center;width:100%;background-color:white;">';
	$updated=$updated.'<tr>
	<td class="style2">카테고리</td>
	<td class="style2">항목</td>
	<td class="style2">합계</td>
	</tr>';
	
	//보험
	$selSql="select `cash_mony`,`cscd_mony`,`card_mony`,`yet__mony` from pearl_pay where `sale_code` = '1001' and `insu_code` = '0101' and `date` between '".mktime(0, 0, 0, intval(date('m')), 1, intval(date('Y')))."' and '".mktime(0, 0, 0, intval(date('m'))+1, 0, intval(date('Y')))."' order by no asc";
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
	<td class="style3">보험</td>
	<td class="style3">소계</td>
	<td class="style3">'.comma($sum_ins).'</td>
	</tr>';

	//일반
	$selSql="select `cash_mony`,`cscd_mony`,`card_mony`,`yet__mony` from pearl_pay where `sale_code` = '1001' and `insu_code` = '0102' and `date` between '".mktime(0, 0, 0, intval(date('m')), 1, intval(date('Y')))."' and '".mktime(0, 0, 0, intval(date('m'))+1, 0, intval(date('Y')))."' order by no asc";
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
	<td class="style3">일반</td>
	<td class="style3">소계</td>
	<td class="style3">'.comma($sum_nin).'</td>
	</tr>';

//진료실
	$updated=$updated.'<tr>
	<td class="style2">진료실</td>
	<td class="style2">이름</td>
	<td class="style2">'.comma($sum_ins+$sum_nin).'</td>
	</tr>';
	$updated=$updated.'<tr>
	<td class="style3">진료실</td>
	<td class="style3">소계</td>
	<td class="style3">'.comma($sum_ins+$sum_nin).'</td>
	</tr>';

	//코스메틱
	$selSql="select `cash_mony`,`cscd_mony`,`card_mony`,`yet__mony` from pearl_pay where `sale_code` = '1002' and `date` between '".mktime(0, 0, 0, intval(date('m')), 1, intval(date('Y')))."' and '".mktime(0, 0, 0, intval(date('m'))+1, 0, intval(date('Y')))."' order by no asc";
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
	<td class="style3">코스메틱</td>
	<td class="style3">소계</td>
	<td class="style3">'.comma($sum_cash+$sum_cscd+$sum_card+$sum_yet).'</td>
	</tr>';

	$updated=$updated.'</table>';
	$objResponse->assign('pdiv', 'innerHTML', $updated);
	$objResponse->assign('div2', 'innerHTML', $selSql);
	$objResponse->call('preview');
	return $objResponse;
}
/*
	Function: editBt
	
	editBt
*/
function editBt($no,$exps_code)
{
	global $thead, $tfoot, $connect;;
	$objResponse = new xajaxResponse();
	$selSql="select * from pearl_exp where `no` = ".$no;
	$result = mysql_query($selSql, $connect); 
	$row = mysql_fetch_row($result);
	switch($row[2]){
		case "0" :
			$sel[0]='selected="selected"';
			break;
		case "1" :
			$sel[1]='selected="selected"';
			break;
	}
if($exps_code==4000){
	$query="SELECT `no`, `exps_name` FROM `pearl_expc` WHERE `exps_cate` = '".$row[3]."'";
   	$resultC = mysql_query($query, $connect); 
	$rowc = mysql_fetch_row($resultC);
	$row[3]=$rowc[0].". ".$rowc[1];
}
	$objResponse->assign('no', 'value', $row[0]);
	$objResponse->assign('exps_code', 'value', $row[1]);
	$objResponse->assign('exps_gubn', 'value', $row[2]);
	$objResponse->assign('exps_cate', 'value', $row[3]);
	$objResponse->assign('exps_cust', 'value', $row[4]);
	$objResponse->assign('exps_caus', 'value', $row[5]);
	$objResponse->assign('cash_mony', 'value', $row[6]);
	$objResponse->call('top()');
	$reg_date=substr($row[7],0,4);
	$reg_date=$reg_date."-".substr($row[7],4,2);
	$reg_date=$reg_date."-".substr($row[7],6,2);
	$query="edit";
	$objResponse->assign('date_input', 'value', $reg_date);
	$objResponse->assign('div2', 'innerHTML', $query);
	return $objResponse;
}
/*
	Function: selectSale
	
	seclectSale
*/
function selectSale($exps_code,$start_date,$end_date)
{
	global $thead, $connect;
	$selSql="SELECT * FROM `t_closedsale` where `reg_date` = ".$start_date;
	$resultC = mysql_query($selSql, $connect); 
	$rowc = mysql_fetch_row($resultC);

	$selSql="select * from pearl_exp where `exps_code` = '".$exps_code."' and `reg_date` between '".$start_date."' and '".$end_date."' order by reg_date asc";
	$result = mysql_query($selSql, $connect); 
	$total = mysql_affected_rows(); // 총 레코드 수
	$num=$total;
	$updated=$thead[$exps_code].'<tbody>';
while($total--){
	$row = mysql_fetch_row($result);
	switch($row[2]){
		case "0" :
			$row[2]="현금";
			break;
		case "1" :
			$row[2]="통장";
			break;
	}
 
	
	$selSql2="select `exps_name` from pearl_expc where `exps_cate` = '".$row[3]."'";
	$result2 = mysql_query($selSql2, $connect); 
	$row2 = mysql_fetch_row($result2);

	
	$reg_date=substr($row[7],2,2);
	$reg_date=$reg_date."/".substr($row[7],4,2);
	$reg_date=$reg_date."/".substr($row[7],6,2);
	$updated=$updated.'<tr>
	<td class="style2">'.$reg_date.'</td>';
	if($exps_code==4000 || $exps_code==4001)
		$updated=$updated.'<td class="style2">'.$row[2].'</td>';
	$updated=$updated.'<td class="style2">'.$row2[0].'</td>';
	$updated=$updated.'<td class="cash">'.$row[4].'</td>';
	$updated=$updated.'<td class="style2">'.$row[5].'</td>';
	$updated=$updated.'<td class="style2">'.comma($row[6]).'</td>';
	
	if($rowc[25]!="Y"){
		$updated=$updated.'<td class="style2"><input type="button" value="E" onclick="xajax_editBt('.$row[0].','.$row[1].')" style="width:30px;"><input type="button" value="D" onclick="xajax_deleteId('.$row[0].','.$row[1].', xajax.$(\'date_input\').value,xajax.$(\'date_input\').value)" style="width:30px;"></td>';
	}else{
		$updated=$updated.'<td>마감(요청)</td>';
	}


	$updated=$updated.'</tr>';

	}
	$updated=$updated."</table>";
	return $updated;
}
/*
	Function: deleteId
	
	delete
*/
function deleteId($id,$exps_code,$date_start,$date_end)
{
	global $thead, $tfoot, $connect;
	$objResponse = new xajaxResponse();
	$query="delete from `pearlclinic`.`pearl_exp` where `no`=".$id;
	$execQue = mysql_query($query, $connect);
	$date_start=str_replace("-","",$date_start);// '-' 제거
	$date_end=str_replace("-","",$date_end);// '-' 제거
	$updated=selectSale($exps_code,$date_start,$date_end).'</table>';
	$inputDiv=$thead[$exps_code].$tfoot[$exps_code].'</table>';
	$objResponse->assign('div2', 'innerHTML', $query);
	$objResponse->assign('content', 'innerHTML', $updated);
	$objResponse->assign('input', 'innerHTML', $inputDiv);
	$objResponse->call('bottom');//div scolling
	return $objResponse;
}


/*
	Function: savePay
	
	save
*/
function savePay($no,$exps_code,$exps_gubn,$exps_cate,$exps_cust,$exps_caus,$cash_mony,$reg_date,$etc)
{
	global $thead, $tfoot, $theadE, $tfootE, $connect;
	$objResponse = new xajaxResponse();
	$c_time=time();
	$date_input=str_replace("-","",$reg_date);// '-' 제거
	$cash_mony=str_replace(",","",$cash_mony);// ',' 제거
	//자동완성기능을 쓸 경우 문자열->코드
if($exps_code==4000){
	$ec=explode(". ",$exps_cate);
	$query="SELECT `exps_cate` FROM `pearl_expc` WHERE `exps_name` = '".$ec[1]."'";
   	$resultC = mysql_query($query, $connect); 
	$rowc = mysql_fetch_row($resultC);
	$exps_cate=$rowc[0];
}
	$exps_cust=htmlspecialchars($exps_cust);
	$exps_caus=htmlspecialchars($exps_caus);
if($no){
	$query="UPDATE `pearl_exp` SET `exps_code` = '".$exps_code."', `exps_gubn` = '".$exps_gubn."', `exps_cate` = '".$exps_cate."',`exps_cust` = '".$exps_cust."',`exps_caus` = '".$exps_caus."',`reg_date` = '".$date_input."',`date` = '".$c_time."',`etc` = '".$etc."' WHERE `no` =".$no;
}else{
	$query="INSERT INTO `pearl_exp` ( `no` , `exps_code` , `exps_gubn` , `exps_cate` , `exps_cust` , `exps_caus` , `cash_mony` , `reg_date` , `date` , `etc` ) VALUES ('', '".$exps_code."', '".$exps_gubn."', '".$exps_cate."', '".$exps_cust."', '".$exps_caus."', '".$cash_mony."', '".$date_input."', '".$c_time."', '".$etc."');";
}

    $saveQue = mysql_query($query, $connect);
	$inputDiv=$thead[$exps_code].$tfoot[$exps_code].'</table>';
	$updated=$updated.selectSale($exps_code,$date_input,$date_input).'</table>';

	$objResponse->call('fr.reset()');
	$objResponse->assign('div2', 'innerHTML', $query);
	$objResponse->assign('content', 'innerHTML', $updated);
	$objResponse->call('bottom');//div scolling
	
	return $objResponse;
}

/*
	Section:  Register functions
	
	- <savePay>
	- <deleteId>
	- <preview>
	- <ent>
*/
$reqSavePayBtn =& $xajax->registerFunction('savePay');

$reqDelBtn =& $xajax->registerFunction('deleteId');

$reqPreview =& $xajax->registerFunction('preview');

$reqParent =& $xajax->registerFunction('parent');

$reqInquiry =& $xajax->registerFunction('inquiry');

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