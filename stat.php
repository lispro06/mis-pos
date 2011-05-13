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
<td class="style2"><input type="hidden" name="exps_code" id="exps_code" value="4001" ></td>
<td class="style2">
	<select id="exps_gubn" name="exps_gubn" onchange="CheckEnter(this);" class="clist">
		<option value="0">1. 현금</option>
		<option value="1">2. 통장</option>
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
<td class="style2"><input type="text" name="cash_mony" id="cash_mony" size="15"  onkeypress="Keycode(event,this);" Onkeydown="CheckEnter(this)" onkeyup="SumAmount(this);" ></td>
<td class="style2"><input type="button" onclick="CheckCont()" value="저장" ></td>
</tr>
</tfoot>';

$thead[4000]=$tableD.'
<thead>
	<tr>
	<td class="style2">일자</td>
	<td class="style2">분류</td>
	<td class="style2">계정과목</td>
	<td class="style2">적요</td>
	<td class="style2">지출처</td>
	<td class="style2">금액</td>
	</tr>
</thead>';
$thead[4001]=$thead[4000];

$thead[4002]=$tableD.'
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
/*
	Function: parent
	
	parent
*/
function parent($exps_code,$date_input){
	global $thead, $tfoot, $connect, $menu, $tabColor, $extColor, $thColor, $acc;
	$objResponse = new xajaxResponse();
	$setTime=$date_input;
	$date_input=str_replace("-","",$date_input);// '-' 제거
	if(session_is_registered('reg_date')){
		$_SESSION['session_reg_date']=$date_input;//세션에 저장
	}
	$date_start=date("Y-m-d", mktime(0,0,0,substr($date_input,4,2),1,substr($date_input,0,4)));
	$updated=selectSale($exps_code,$date_input,$date_input).'</table>';
	
	$style[1000]=' style="width:4%;background-color:'.$extColor.';"';
	$selTab[4000]=' style="width:24%;"';
	$selTab[4001]=' style="width:24%;"';
	$selTab["inquery"]=' style="width:24%;"';
//	$selTab[$exps_code]=' style="font-weight: bolder;background-color:'.$tabColor.';width:24%;"';
	$selClass[$exps_code]='  class="tabS"';

	$sCol[4001]='color:#FFFFFF;';
	$sCol[4000]='color:#FFFFFF;';
	$sCol[$exps_code]='color:'.$thColor.';';
	$menuDiv='<table border="0" cellspacing="0" cellpadding="0">
      <tr>
		<td height="30" align="center"'.$style[1000].'></td>';
 if($acc['skin'])   
	$menuDiv=$menuDiv.'<td height="30" align="center" '.$selTab[4000].'><div '.$selClass[4000].'><a href="static.php?exps_code=4000"><span style="'.$sCol[4000].'">일별지출(피부과)</span></a></div></td>';
if($acc['cos'])
    $menuDiv=$menuDiv.'<td height="30" align="center" '.$selTab[4001].'><div '.$selClass[4001].'><a href="#" onclick="xajax_parent(4001,\''.$setTime.'\')"><span style="'.$sCol[4001].'">일별지출(코스메틱)</span></a></div></td>';
if($acc['skin'])
	$menuDiv=$menuDiv.'<td height="30" align="center" '.$selTab["inquery"].'><a href="month.php?exps_code=4000"><span style="color:#FFFFFF">월별지출(피부과)</span></a></td>';
if($acc['cos'])
    $menuDiv=$menuDiv.'<td height="30" align="center" '.$selTab["inquery"].'><a href="month.php?exps_code=4001"><span style="color:#FFFFFF">월별지출(코스메틱)</a></td>';

	$menuDiv=$menuDiv.'<td width="100" height="30" align="center" class="box_Bottomline">&nbsp;</td>
      </tr>	
    </table>';
	$objResponse->assign('exps_code', 'value', $exps_code);
	$objResponse->assign('date_start', 'value', $setTime);
	$objResponse->assign('date_end', 'value', $setTime);
	$objResponse->assign('content', 'innerHTML', $updated);
	$objResponse->assign('menu', 'innerHTML', $menu);
	$objResponse->assign('tabs2', 'innerHTML', $menuDiv);
	$msg=$_SESSION['sunap']."님은 ".$setTime."일자 자료입력 중입니다.";
	$objResponse->assign('msgDiv', 'innerHTML', $msg);
	$objResponse->call('pp');
	return $objResponse;
}
/*
	Function: selectSale
	
	seclectSale
*/
function selectSale($exps_code,$start_date,$end_date)
{
	global $thead, $connect;
	$selSql="select * from toto_exp where `exps_code` = '".$exps_code."' and `reg_date` between '".$start_date."' and '".$end_date."' order by reg_date asc";
	$result = mysql_query($selSql, $connect); 
	$total = mysql_num_rows($result); // 총 레코드 수
	$num=$total;
	$updated=$thead[$exps_code].'<tbody>';
while($total--){
	$row = mysql_fetch_row($result);
	
	$selSql2="select `exps_sort`, `exps_name` from toto_expc where `exps_cate` = '".$row[3]."'";
	$result2 = mysql_query($selSql2, $connect); 
	$row2 = mysql_fetch_row($result2);

	
	$reg_date=substr($row[7],2,2);
	$reg_date=$reg_date."-".substr($row[7],4,2);
	$reg_date=$reg_date."-".substr($row[7],6,2);
	$updated=$updated.'<tr>
	<td class="tdStyle">'.$reg_date.'</td>';
	if($exps_code==4000 || $exps_code==4001)
		$updated=$updated.'<td class="tdStyle">'.$row2[0].'</td>';
	$updated=$updated.'<td class="tdStyle">'.$row2[1].'</td>';
	$updated=$updated.'<td class="tdStyle">'.$row[4].'</td>';
	$updated=$updated.'<td class="tdStyle">'.$row[5].'</td>';
	$updated=$updated.'<td class="tdStyle">'.comma($row[6]).'</td>

	</tr>';

	}
	if($num==0){
		$updated=$updated.'<tr><td colspan="6">자료가 없습니다</td></tr>';
	}
	$updated=$updated."</table>";
	return $updated;
}


$reqParent =& $xajax->registerFunction('parent');


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