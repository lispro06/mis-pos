<?php
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
	Function: inquiry
	
	inquiry
*/
function inquiry($start_date)
{
	global $connect;
	$objResponse = new xajaxResponse();
	$setTime=$start_date;
	if(session_is_registered('reg_date')){
		$_SESSION['session_reg_date']=str_replace("-","",$start_date);//세션에 저장
	}
	$start_date=str_replace("-","",$start_date);// '-' 제거
	$selSql="SELECT * FROM `toto_log` where `reg_date` = '".$start_date."' ORDER BY `reg_time` ASC";
	$result = mysql_query($selSql, $connect);
	$total = mysql_num_rows($result); // 총 레코드 수
	$num=0;
	
	$table='<center><table border="1" style="text-align:center;width:99%;background-color:#FFFFFF;border-color:#CA2F32;" cellspacing="0" cellpadding="0">';
	$thead='<tr>
				<td>순번</td>
				<td>접속 아이디</td>
				<td>접속 ip</td>
				<td>열람 페이지</td>
				<td>접속시간</td>
			</tr>';
	if($total){
		while($total--){
			$num++;
			$rows = mysql_fetch_row($result);
			$updated=$updated.'<tr>
						<td>'.$num.'</td>
						<td>'.$rows[1].'</td>
						<td>'.$rows[2].'</td>
						<td>'.$rows[3].'</td>
						<td>'.date("H:i:s",$rows[4]).'</td>
					</tr>';
		}
	}else{
		$updated=$updated.'<tr><td colspan="5">자료가 없습니다.</td></tr>';
	}
		$updated=$table.$thead.$updated.'</table>';
	$msg=$_SESSION['sunap']."님은 ".$setTime."일자 자료입력 중입니다.";
	$objResponse->assign('msgDiv', 'innerHTML', $msg);
	$objResponse->assign('content', 'innerHTML', $updated);
	return $objResponse;
}
function itemView($table_name){
	global $connect;
	$objResponse = new xajaxResponse();
	$selSql="SELECT * FROM `".$table_name."`";
	$result = mysql_query($selSql, $connect);
	$total = mysql_num_rows($result); // 총 레코드 수
	$num=0;

	$colSql="SHOW COLUMNS FROM `".$table_name."`";
	$colRes = mysql_query($colSql, $connect);
	$colTot = mysql_num_rows($colRes); // 총 레코드 수

if($colTot!=5)	// 다른 table을 열람할 수 없게 함(카테고리, 진료실 관련 테이블의 column 수는 5개로 동일 함.
	exit;

	while($colTot--){
		$colRows = mysql_fetch_row($colRes);
		$colName[] = $colRows[0];
	}
	
	$table='<center><table border="1" style="text-align:center;width:99%;background-color:#FFFFFF;border-color:#CA2F32;" cellspacing="0" cellpadding="0">';
	$thead='<tr>
				<td height="30px;">'.$colName[0].'</td>
				<td>'.$colName[1].'</td>
				<td>'.$colName[2].'</td>
				<td>'.$colName[3].'</td>
				<td>'.$colName[4].'</td>
				<td></td>
			</tr>';
	if($total){
		while($total--){
			$num++;
			$rows = mysql_fetch_row($result);
			$updated=$updated.'<tr>
						<td height="30px;">'.$num.'</td>
						<td><input type="text" id="two_'.$num.'" name="two_'.$num.'" value="'.$rows[1].'" style="text-align:center"></td>
						<td><input type="text" id="three_'.$num.'" name="three_'.$num.'" value="'.$rows[2].'" style="text-align:center"></td>
						<td><input type="text" id="four_'.$num.'" name="four_'.$num.'" value="'.$rows[3].'" style="text-align:center"></td>
						<td><input type="text" id="five_'.$num.'" name="five_'.$num.'" value="'.$rows[4].'" style="text-align:center"></td>
						<td><input type="button" value="수정" onclick="xajax_itemUpd(\''.$table_name.'\',\''.$colName[0].'\',\''.$colName[1].'\',\''.$colName[2].'\',\''.$colName[3].'\',\''.$colName[4].'\',\''.$rows[0].'\',xajax.$(\'two_'.$num.'\').value,xajax.$(\'three_'.$num.'\').value,xajax.$(\'four_'.$num.'\').value,xajax.$(\'five_'.$num.'\').value);">  <input type="button" value="삭제" onclick="xajax_itemDel(\''.$table_name.'\',\''.$rows[0].'\');"></td>
					</tr>';
		}
	}else{
		$updated=$updated.'<tr><td colspan="5">자료가 없습니다.</td></tr>';
	}

//////////////// 추가 할 수 있는 기능 추가 
		$updated=$updated.'<tr>
						<td height="30px;"></td>
						<td><input type="text" id="two" name="two" style="text-align:center"></td>
						<td><input type="text" id="three" name="three" style="text-align:center"></td>
						<td><input type="text" id="four" name="four" style="text-align:center"></td>
						<td><input type="text" id="five" name="five" style="text-align:center"></td>
						<td><input type="button" onclick="xajax_itemUpd(\''.$table_name.'\',\''.$colName[0].'\',\''.$colName[1].'\',\''.$colName[2].'\',\''.$colName[3].'\',\''.$colName[4].'\',0,xajax.$(\'two\').value,xajax.$(\'three\').value,xajax.$(\'four\').value,xajax.$(\'five\').value);" value="추가"></td>
					</tr>';
/////////////// 2011-03-31

		$updated=$table.$thead.$updated.'</table>';
	$msg="접속자 관리 화면입니다.";
	$objResponse->assign('msgDiv', 'innerHTML', $msg);
	$objResponse->assign('content', 'innerHTML', $updated);
	return $objResponse;
}
function itemUpd($table_name,$first,$second,$third,$fourth,$fifth,$one,$two,$three,$four,$five){
	global $connect;
	$objResponse = new xajaxResponse();
	if($one){
		$updSql="UPDATE  `".$table_name."` SET `".$second."` =  '".$two."',  `".$third."` =  '".$three."', `".$fourth."` =  '".$four."', `".$fifth."` =  '".$five."' WHERE  `".$first."` =".$one;
	}else{
		$reg_date=date("Ymd",time());
		$updSql="INSERT INTO  `".$table_name."` (  `".$first."` ,  `".$second."` ,  `".$third."` ,  `".$fourth."` ,  `".$fifth."`) VALUES ('',  '".$two."', '".$three."',  '".$four."',  '".$five."');";
	}
    $saveQue = mysql_query($updSql, $connect);
	if($saveQue){
		$objResponse->call($table_name);
	}else{
		$msg=$updSql;
		$objResponse->assign('msgDiv', 'innerHTML', $msg);
	}
	return $objResponse;
}
function itemDel($table_name,$no)
{
	global $connect;
	$objResponse = new xajaxResponse();
	$query="delete from `".$table_name."` where `no`=".$no;
	$execQue = mysql_query($query, $connect);

	$objResponse->call($table_name);
	return $objResponse;
}
function ckd($val){
	if($val=="Y")
		return "checked";
}
function acl(){
	global $connect;
	$objResponse = new xajaxResponse();
	$selSql="SELECT * FROM `toto_acl` ORDER BY `reg_date` ASC";
	$result = mysql_query($selSql, $connect);
	$total = mysql_num_rows($result); // 총 레코드 수
	$num=0;
	
	$table='<center><table border="1" style="text-align:center;width:99%;background-color:#FFFFFF;border-color:#CA2F32;" cellspacing="0" cellpadding="0">';
	$thead='<tr>
				<td height="30px;">순번</td>
				<td>접속 아이디</td>
				<td>비밀번호</td>
				<td>열람 페이지</td>
				<td>등록일</td>
				<td>허용 ip</td>
				<td>피부과</td>
				<td>코스메틱</td>
				<td>전체열람권한</td>
				<td></td>
			</tr>';
	if($total){
		while($total--){
			$num++;
			$rows = mysql_fetch_row($result);
			// 열람 페이지 2011-04-16
			$pgOut="";
			$aclPgl=explode("|",$rows[3]);
			for($aclFor=0;$aclFor<count($aclPgl);$aclFor++){
				$pgSql="SELECT `name` FROM  `toto_page` WHERE  `file` = '".$aclPgl[$aclFor]."'";
				$pgRes = mysql_query($pgSql, $connect);
				$pgRow = mysql_fetch_row($pgRes);
				$pgOut=$pgOut." ".$pgRow[0];
			}
			// 열람 페이지
			$updated=$updated.'<tr>
						<td><a href="#" onclick="xajax_aclMod('.$rows[0].');">'.$num.'</a></td>
						<td>'.$rows[1].'</td>
						<td>'.$rows[2].'</td>
						<td>'.$pgOut.'</td>
						<td>'.$rows[4].'</td>
						<td>'.$rows[5].'</td>
						<td><input type="checkbox" '.ckd($rows[6]).' style="width:20px;" disabled></td>
						<td><input type="checkbox" '.ckd($rows[7]).' style="width:20px;" disabled></td>
						<td><input type="checkbox" '.ckd($rows[8]).' style="width:20px;" disabled></td>
						<td><input type="button" onclick="xajax_aclDel('.$rows[0].');" value="삭제"></td>
					</tr>';
		}
	}else{
		$updated=$updated.'<tr><td colspan="5">자료가 없습니다.</td></tr>';
	}
		$updated=$table.$thead.$updated.'</table>';
		$updated=$updated.'<input type="button" onclick="xajax_aclMod(0);" value="추가">';
	$msg="접속자 관리 화면입니다.";
	$objResponse->assign('msgDiv', 'innerHTML', $msg);
	$objResponse->assign('content', 'innerHTML', $updated);
	return $objResponse;
}
function aclMod($no){
	global $connect;
	$objResponse = new xajaxResponse();
	$selSql="SELECT * FROM `toto_acl` where `no`=".$no;
	$result = mysql_query($selSql, $connect);
	$total = mysql_num_rows($result); // 총 레코드 수
	
	$table='<center><table border="1" style="text-align:center;width:99%;background-color:#FFFFFF;border-color:#CA2F32;" cellspacing="0" cellpadding="0">';
	$thead='<tr>
				<td></td>
				<td>접속 아이디</td>
				<td>비밀번호</td>
				<td>열람 페이지</td>
				<td>등록일</td>
				<td>허용 ip</td>
				<td>피부과</td>
				<td>코스메틱</td>
				<td>전체열람권한</td>
				<td></td>
			</tr>';
	if($total){
		$rows = mysql_fetch_row($result);
	}
	
	$aclPgl=explode("|",$rows[3]);
	$pgSql="SELECT `name`,`file` FROM  `toto_page`";
	$pgRes = mysql_query($pgSql, $connect);
	$pgOut="";
	while($pgRow = mysql_fetch_row($pgRes)){
		$file=explode(".",$pgRow[1]);
		if(++$br%5==0)
			$pgOut=$pgOut."<br />";
		$isVal=0;
		for($aclFor=0;$aclFor<count($aclPgl);$aclFor++){
				if($pgRow[1]==$aclPgl[$aclFor])
					$isVal++;
				if($isVal)
					$checked="checked";
				else
					$checked="";
		}

		$pgOut=$pgOut." ".$pgRow[0]."<input type='checkbox' id='".$file[0]."' name='".$file[0]."' value='".$pgRow[1]."' ".$checked.">";
	}

		$updated=$updated.'<tr><form name="f">
					<td><input type="hidden" id="no" name="no" value="'.$rows[0].'">---</td>
					<td><input type="text" id="user_id" name="user_id" value="'.$rows[1].'" style="width:50px;"></td>
					<td><input type="text" id="user_pw" name="user_pw" value="'.$rows[2].'" style="width:50px;"></td>
					<td>'.$pgOut.'</td>
					<td><input type="text" id="reg_date" name="reg_date" value="'.$rows[4].'" style="width:100px;"></td>
					<td><input type="text" id="user_ip" name="user_ip" value="'.$rows[5].'" style="width:250px;"></td>
					<td><input type="checkbox" id="view_skin" name="view_skin" value="'.$rows[6].'" style="width:20px;" '.ckd($rows[6]).' ></td>
					<td><input type="checkbox" id="view_cos" name="view_cos" value="'.$rows[7].'" style="width:20px;" '.ckd($rows[7]).' ></td>
					<td><input type="checkbox" id="view_all" name="view_all" value="'.$rows[8].'" style="width:20px;" '.ckd($rows[8]).' ></td>
					<td><input type="button" onclick="checkForm();" value="입력"></td></form>
				</tr>';
		$updated=$table.$thead.$updated.'</table>';
	$msg="접속자 추가(수정) 화면입니다.";
	$objResponse->assign('msgDiv', 'innerHTML', $msg);
	$objResponse->assign('content', 'innerHTML', $updated);
	return $objResponse;
}

function aclUpd($no,$user_id,$user_pw,$view_pg,$user_ip,$view_skin,$view_cos,$view_all){
	global $connect;
	$objResponse = new xajaxResponse();
	if($no!=0){
		$updSql="UPDATE  `toto_acl` SET `user_id` =  '".$user_id."', `user_pw` =  '".$user_pw."',  `view_pg` =  '".$view_pg."', `user_ip` =  '".$user_ip."', `view_skin` =  '".$view_skin."', `view_cos` =  '".$view_cos."', `view_all` =  '".$view_all."' WHERE  `no` =".$no;
	}else{
		$reg_date=date("Ymd",time());
		$updSql="INSERT INTO  `toto_acl` (  `no` ,  `user_id` ,  `user_pw` ,  `view_pg` ,  `reg_date` ,  `user_ip` ,  `view_skin` ,  `view_cos` ,  `view_all`) VALUES ('',  '".$user_id."',  '".$user_pw."',  '".$view_pg."',  '".$reg_date."',  '".$user_ip."',  '".$view_skin."',  '".$view_cos."',  '".$view_all."');";
	}
   $saveQue = mysql_query($updSql, $connect);

	if($no){
		$selSql="SELECT * FROM `toto_acl` where `no`=".$no;
		$result = mysql_query($selSql, $connect);
		$total = mysql_num_rows($result); // 총 레코드 수
		$num=0;
		$table='<center><table border="1" style="text-align:center;width:99%;background-color:#FFFFFF;border-color:#CA2F32;" cellspacing="0" cellpadding="0">';
		$thead='<tr>
					<td></td>
					<td>접속 아이디</td>
					<td>비밀번호</td>
					<td>열람 페이지</td>
					<td>등록일</td>
					<td>허용 ip</td>
					<td>피부과</td>
					<td>코스메틱</td>
					<td>열람권한</td>
					<td></td>
				</tr>';
		if($total){
			$rows = mysql_fetch_row($result);
		}
			$updated=$updated.'<tr><form name="f">
						<td><input type="hidden" id="no" name="no" value="'.$rows[0].'">---</td>
						<td><input type="text" id="user_id" name="user_id" value="'.$rows[1].'" style="width:50px;"></td>
						<td><input type="text" id="user_pw" name="user_pw" value="'.$rows[2].'" style="width:50px;"></td>
						<td><input type="text" id="view_pg" name="view_pg" value="'.$rows[3].'" style="width:450px;"></td>
						<td><input type="text" id="reg_date" name="reg_date" value="'.$rows[4].'" style="width:100px;"></td>
						<td><input type="text" id="user_ip" name="user_ip" value="'.$rows[5].'" style="width:250px;"></td>
						<td><input type="text" id="view_skin" name="view_skin" value="'.$rows[6].'" style="width:20px;"></td>
						<td><input type="text" id="view_cos" name="view_cos" value="'.$rows[7].'" style="width:20px;"></td>
						<td><input type="text" id="view_all" name="view_all" value="'.$rows[8].'" style="width:20px;"></td>
						<td><input type="button" onclick="checkForm();" value="수정"></td></form>
					</tr>';

			$updated=$table.$thead.$updated.'</table>';
		if($saveQue){
			$msg="추가(수정) 되었습니다.";
			$objResponse->assign('msgDiv', 'innerHTML', $msg);
			$objResponse->call('xajax_acl()');
		}
	}else{
		$objResponse->call('xajax_acl()');
	}
	return $objResponse;
}
function aclDel($no)
{
	global $connect;
	$objResponse = new xajaxResponse();
	$query="delete from `toto_acl` where `no`=".$no;
	$execQue = mysql_query($query, $connect);

	$objResponse->call('xajax_acl()');
	return $objResponse;
}
$reqInquiry =& $xajax->registerFunction('inquiry');
$reqInquiry =& $xajax->registerFunction('itemView');
$reqInquiry =& $xajax->registerFunction('itemUpd');
$reqInquiry =& $xajax->registerFunction('itemDel');
$reqInquiry =& $xajax->registerFunction('acl');
$reqInquiry =& $xajax->registerFunction('aclMod');
$reqInquiry =& $xajax->registerFunction('aclUpd');
$reqInquiry =& $xajax->registerFunction('aclDel');
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