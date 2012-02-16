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

$tfoot[4001]='<tfoot>
<tr><input type="hidden" name="no" id="no" value="" ><input type="hidden" name="etc" id="etc" value="" >
<input type="hidden" name="exps_code" id="exps_code" value="4001" >
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
<td class="style2"><input id="saveBt" name="saveBt" type="button" onclick="CheckCont()" value="저장" ></td>
</tr>
</tfoot>';

$thead[4000]=$tableD.'
<thead>
	<tr>
	<td class="style2">종류</td>
	<td class="style2">계정과목</td>
	<td class="style2">적요</td>
	<td class="style2">지출처</td>
	<td class="style2">금액</td>
	<td class="style2" width="100px;"></td>
	</tr>
</thead>';
$thead[4001]=$thead[4000];
$tfoot[4000]='<tfoot>
<tr><input type="hidden" name="no" id="no" value="" ><input type="hidden" name="etc" id="etc" value="" >
<input type="hidden" name="exps_code" id="exps_code" value="4000" >
<td class="style2"><input type="text" id="exps_gubn" name="exps_gubn" Onkeydown="CheckEnter(this);">
</td>
<td class="style2">';
$tfoot[4000]=$tfoot[4000].'<input type="text" name="exps_cate" id="exps_cate" Onkeydown="CheckEnter(this)" >
</td>
<td class="style2"><input type="text" name="exps_cust" id="exps_cust" size="17" Onkeydown="CheckEnter(this)" ></td>
<td class="style2"><input type="text" name="exps_caus" id="exps_caus" size="17" Onkeydown="CheckEnter(this)" ></td>
<td class="style2"><input type="text" name="cash_mony" id="cash_mony" size="15" onkeypress="Keycode(event,this);" Onkeydown="CheckEnter(this)" onkeyup="SumAmount(this);" style="ime-mode:disabled"></td>
<td class="style2"><input id="saveBt" name="saveBt" type="button" onclick="CheckCont()" value="저장" ></td>
</tr>
</tfoot>';
function menu($exps_code){
	global $tabColor,$extColor,$thColor,$acc;
	$style[1000]=' style="width:4%;background-color:'.$extColor.';"';
	$style[4001]=' style="width:48%;background-color:'.$extColor.';"';
	$style[4000]=' style="width:48%;background-color:'.$extColor.';"';
	$sCol[4000]='color:#FFFFFF;';
	$sCol[4001]='color:#FFFFFF;';
	$sCol[$exps_code]='color:'.$thColor.';';
//	$style[$exps_code]=' style="font-weight: bolder;background-color:'.$tabColor.';width:48%;"';
	$selClass[$exps_code]='  class="tabS"';
	$menuDiv='<table border="0" cellspacing="0" cellpadding="0" style="width:100%">
      <tr>
		<td height="30" align="center"'.$style[1000].'></td>';
if($acc['skin'])
	$menuDiv=$menuDiv.'<td height="30" align="center" '.$style[4000].'><div '.$selClass[4000].'><a href="#" onclick="document.location.replace(\'exp.php?exps_code=4000\')"><span style="'.$sCol[4000].'">일지출(피부과)</span></a></div></td>';
if($acc['cos'])
    $menuDiv=$menuDiv.'<td height="30" align="center" '.$style[4001].'><div '.$selClass[4001].'><a href="#" onclick="document.location.replace(\'exp.php?exps_code=4001\')"><span style="'.$sCol[4001].'">일지출(코스메틱)</span></a></div></td>
      </tr>	
    </table>';
	return $menuDiv;
}
/*
	Function: inquiry
	
	inquiry
*/
function inquiry($exps_code,$start_date,$end_date)
{
	global $connect, $tfoot, $thead;
	$objResponse = new xajaxResponse();
	$query="inquiry".$start_date.$end_date;
	$setTime=$start_date;
	$start_date=str_replace("-","",$start_date);// '-' 제거
	$end_date=str_replace("-","",$end_date);// '-' 제거

	$inputDiv=$thead[$exps_code].$tfoot[$exps_code].'</table>';
	$selSql="SELECT * FROM `toto_closedsale` where `reg_date` = ".$start_date;
	$result = mysql_query($selSql, $connect); 
	$rows = mysql_fetch_row($result);
	if($rows[29]=="Y"){
		$objResponse->assign('div2', 'innerHTML', '마감요청중입니다.');
	}else{
		$objResponse->assign('div2', 'innerHTML', '');
	}
	if($rows[30]=="Y"){
		$objResponse->assign('saveBt', 'disabled', true);
		$objResponse->assign('saveBt', 'value', '마감됨');
		$objResponse->assign('div2', 'innerHTML', '마감되었습니다.');
	}else{
		$objResponse->assign('saveBt', 'disabled', false);
		$objResponse->assign('saveBt', 'value', '저장');
		$objResponse->assign('exps_code', 'value', $exps_code);
	}

	$objResponse->call('fr.reset()');
	$updated=selectSale($exps_code,$start_date,$end_date);
	$objResponse->assign('content', 'innerHTML', $updated);
	$objResponse->call('bottom');//div scolling
	$msg=$_SESSION['sunap']."님은 ".$setTime."일자 자료입력 중입니다.";
	$objResponse->assign('msgDiv', 'innerHTML', $msg);
	return $objResponse;
}
/*
	Function: parent
	
	parent
*/
function parent($exps_code){
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
	$updated=selectSale($exps_code,$date_input,$date_input).'</table>';

	$objResponse->assign('date_input', 'value', $setTime);
	$objResponse->assign('content', 'innerHTML', $updated);

	$selSql="SELECT * FROM `toto_closedsale` where `reg_date` = ".$date_input;
	$result = mysql_query($selSql, $connect); 
	$rows = mysql_fetch_row($result);
	if($rows[29]=="Y"){
		$objResponse->assign('div2', 'innerHTML', '마감요청중입니다.');
	}else{
		$objResponse->assign('div2', 'innerHTML', '');
	}
	if($rows[30]!="Y"){
		$objResponse->call('fr.reset()');
		$objResponse->assign('exps_code', 'value', $exps_code);
	}else{
		$objResponse->assign('saveBt', 'disabled', true);
		$objResponse->assign('saveBt', 'value', '마감됨');
		$objResponse->assign('div2', 'innerHTML', '마감되었습니다.');
	}
	$objResponse->assign('menu', 'innerHTML', $menu);
	$objResponse->assign('tabs2', 'innerHTML', menu($exps_code));
	$msg=$_SESSION['sunap']."님은 ".$setTime."일자 자료입력 중입니다.";
	$objResponse->assign('msgDiv', 'innerHTML', $msg);
	$objResponse->call('pp');
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
	$selSql="select * from toto_exp where `no` = ".$no;
	$result = mysql_query($selSql, $connect); 
	$row = mysql_fetch_row($result);
	switch($row[2]){
		case "0" :
			$sel[0]='selected="selected"';
			break;
		case "1" :
			$sel[1]='selected="selected"';
			break;
		case "2" :
			$sel[2]='selected="selected"';
			break;
		case "3" :
			$sel[3]='selected="selected"';
			break;
	}
	$query="SELECT * FROM `toto_code` where `cate`='exps_gubn' order by `name` desc";
   	$resultC = mysql_query($query, $connect); 
	$totalC = mysql_num_rows($resultC); // 총 레코드 수
	$numC=0;
	while($totalC--){
		$numC++;
	$rowc = mysql_fetch_row($resultC);
		if($rowc[1]==$row[2]){
			$row[2]=$numC.". ".$rowc[2];
		}
	}
	$query="SELECT * FROM `toto_expc` where `exps_code`=".$exps_code." order by `exps_name` asc";
   	$resultC = mysql_query($query, $connect); 
	$totalC = mysql_num_rows($resultC); // 총 레코드 수
	$numC=0;
	while($totalC--){
		$numC++;
	$rowc = mysql_fetch_row($resultC);
		if($rowc[1]==$row[3]){
			$row[3]=$numC.". ".$rowc[3];
		}
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
//	$objResponse->assign('div2', 'innerHTML', $query);
	return $objResponse;
}
/*
	Function: selectSale
	
	seclectSale
*/
function selectSale($exps_code,$start_date,$end_date)
{
	global $thead, $connect,$_SESSION, $acc;
	$selSql="SELECT * FROM `toto_closedsale` where `reg_date` = ".$start_date;
	$resultC = mysql_query($selSql, $connect); 
	$rowc = mysql_fetch_row($resultC);

	if(session_is_registered('reg_date')){
		$_SESSION['session_reg_date']=$start_date;//세션에 저장
	}
//모두 보기 권한이 없을 경우 자신이 입력한 내용만 볼 수 있다.2011-03-30
if(!$acc['all']){
	$addCond=" and `reg_user` = '".$_SESSION['sunap']."'";
}
	$selSql="select * from toto_exp where `exps_code` = '".$exps_code."' and `reg_date` between '".$start_date."' and '".$end_date."'".$addCond." order by reg_date asc";
	$result = mysql_query($selSql, $connect); 
	$total = mysql_num_rows($result); // 총 레코드 수
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
		case "2" :
			$row[2]="카드(법인)";
			break;
		case "3" :
			$row[2]="카드(개인)";
			break;
	}
 
	
	$selSql2="select `exps_name` from toto_expc where `exps_cate` = '".$row[3]."'";
	$result2 = mysql_query($selSql2, $connect); 
	$row2 = mysql_fetch_row($result2);

	
	$reg_date=substr($row[7],2,2);
	$reg_date=$reg_date."/".substr($row[7],4,2);
	$reg_date=$reg_date."/".substr($row[7],6,2);
	$updated=$updated.'<tr>';
	if($exps_code==4000 || $exps_code==4001)
		$updated=$updated.'<td class="tdStyle">'.$row[2].'</td>';
	$updated=$updated.'<td class="tdStyle">'.$row2[0].'</td>';
	$updated=$updated.'<td class="tdStyle">'.$row[4].'</td>';
	$updated=$updated.'<td class="tdStyle">'.$row[5].'</td>';
	$updated=$updated.'<td class="tdStyle">'.comma($row[6]).'</td>';
	
	if($rowc[30]!="Y"){
		$updated=$updated.'<td class="style2"><input type="button" value="E" onclick="xajax_editBt('.$row[0].','.$row[1].')" style="width:30px;"><input type="button" value="D" onclick="xajax_deleteId('.$row[0].','.$row[1].', xajax.$(\'date_input\').value,xajax.$(\'date_input\').value)" style="width:30px;"></td>';
	}else{
		$updated=$updated.'<td>마감(요청)</td>';
	}


	$updated=$updated.'</tr>';

	}
	if($num==0){
		$updated=$updated.'<tr><td colspan="6">자료가 없습니다</td></tr>';
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
	$query="delete from `toto_exp` where `no`=".$id;
	$execQue = mysql_query($query, $connect);
	$date_start=str_replace("-","",$date_start);// '-' 제거
	$date_end=str_replace("-","",$date_end);// '-' 제거
	$updated=selectSale($exps_code,$date_start,$date_end).'</table>';
//	$objResponse->assign('div2', 'innerHTML', $query);
	$objResponse->assign('content', 'innerHTML', $updated);
	$objResponse->call('fr.reset()');
	$objResponse->call('bottom');//div scolling
	return $objResponse;
}


/*
	Function: savePay
	
	save
*/
function savePay($no,$exps_code,$exps_gubn,$exps_cate,$exps_cust,$exps_caus,$cash_mony,$reg_date,$etc)
{
	global $thead, $tfoot, $theadE, $tfootE, $connect, $_SESSION;
	$objResponse = new xajaxResponse();
	$c_time=time();
	$date_input=str_replace("-","",$reg_date);// '-' 제거
	$cash_mony=str_replace(",","",$cash_mony);// ',' 제거
	//자동완성기능을 쓸 경우 문자열->코드

	$ec=explode(". ",$exps_gubn);// 번호가 넘어오더라도, 번호 앞을 무시하고, 뒤만 참고하므로, 순서는 상관없다
	$queryC="SELECT `code` FROM `toto_code` WHERE `name` = '".$ec[1]."' and `cate`='exps_gubn'";
   	$resultC = mysql_query($queryC, $connect); 
	$rowc = mysql_fetch_row($resultC);
	$exps_gubn=$rowc[0];

	$ec=explode(". ",$exps_cate);// 번호가 넘어오더라도, 번호 앞을 무시하고, 뒤만 참고하므로, 순서는 상관없다
	$query1="SELECT `exps_cate` FROM `toto_expc` WHERE `exps_name` = '".$ec[1]."' and `exps_code`='".$exps_code."'";
   	$resultC = mysql_query($query1, $connect); 
	$rowc = mysql_fetch_row($resultC);
	$exps_cate=$rowc[0];

	$exps_cust=htmlspecialchars($exps_cust);
	$exps_caus=htmlspecialchars($exps_caus);
if($no){
	$query="UPDATE `toto_exp` SET `exps_code` = '".$exps_code."', `exps_gubn` = '".$exps_gubn."', `exps_cate` = '".$exps_cate."',`exps_cust` = '".$exps_cust."',`exps_caus` = '".$exps_caus."',`cash_mony` = '".$cash_mony."',`reg_date` = '".$date_input."',`date` = '".$c_time."',`etc` = '".$etc."' WHERE `no` =".$no;
}else{
	$query="INSERT INTO `toto_exp` ( `no` , `exps_code` , `exps_gubn` , `exps_cate` , `exps_cust` , `exps_caus` , `cash_mony` , `reg_date` , `date` , `etc`, `reg_user` ) VALUES ('', '".$exps_code."', '".$exps_gubn."', '".$exps_cate."', '".$exps_cust."', '".$exps_caus."', '".$cash_mony."', '".$date_input."', '".$c_time."', '".$etc."', '".$_SESSION['sunap']."');";
}

    $saveQue = mysql_query($query, $connect);
	$inputDiv=$thead[$exps_code].$tfoot[$exps_code].'</table>';
	$updated=$updated.selectSale($exps_code,$date_input,$date_input).'</table>';

	$objResponse->call('fr.reset()');
//	$objResponse->assign('div2', 'innerHTML', $query);
	$objResponse->assign('exps_code', 'value', $exps_code);
	$objResponse->assign('content', 'innerHTML', $updated);
	$objResponse->call('sFocus()');//focus 이동
	
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