<?php

header("Content-Type: text/html; charset=UTF-8");
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

define('__ZBXE__', true);
 require_once('../../config/config.inc.php');
 $oContext = &Context::getInstance();
 $oContext->init();
    
 $logged_info = Context::get('logged_info'); 
 $id = $logged_info->user_id;

//access control list 를 위한 코드 2011-03-17
	$addr=explode("/",$_SERVER['PHP_SELF']);
	$index=count($addr)-1;
	$cLoc=$addr[$index];
	
	$aclSql="select * from `toto_acl` where `user_id`='".$_SESSION['sunap']."'";
	$aclRes = mysql_query($aclSql, $connect); 
	$aclRow = mysql_fetch_row($aclRes);
	if($aclRow[6]=="Y")
		$acc['skin']=1;
	else
		$acc['skin']=0;
	if($aclRow[7]=="Y")
		$acc['cos']=1;
	else
		$acc['cos']=0;
	if($aclRow[8]=="Y")
		$acc['all']=1;
	else
		$acc['all']=0;

	$aclPgl=explode("|",$aclRow[3]);
	$num=count($aclPgl);
	$aclCnt=0;	// aclCnt가 1이면 현재 페이지가 aclPgl에 있다고 할 수 있다.
	$viewMenu=0;	// 검토, 보고서 페이지 보기 권한을 체크한다.
	$payView=0;	// 수납 페이지 열람 권한 체크
	$expView=0; // 지출 페이지 열람 권한 체크
	$sumView=0;	// 일정산 페이지 열람 권한 체크
	for($aclFor=0;$aclFor<$num;$aclFor++){
		if($aclPgl[$aclFor]=="view.php"){
			$viewMenu++;
		}
		if($aclPgl[$aclFor]==$cLoc){
			$aclCnt++;
		}
		if($aclPgl[$aclFor]=="pay.php"){
			$payView=1;
		}
		if($aclPgl[$aclFor]=="exp.php"){
			$expView=1;
		}
		if($aclPgl[$aclFor]=="sum.php"){
			$sumView=1;
		}
	}
// ip check 2011-04-16
if($aclRow[5]=="*"){
	$aclIp=1;
}else{
	$aclIpl=explode("|",$aclRow[5]);
	$aclIp=0;
	for($aclFor=0;$aclFor<count($aclIpl);$aclFor++){
		if($aclIpl[$aclFor]==$_SERVER['REMOTE_ADDR']){
			$aclIp++;
		}
	}
}

		if($aclPgl[1]=="pay.php"){
			$firstUrl="./".$aclPgl[1]."?sale_code=".(1002-$acc['skin']);
		}else if($aclPgl[1]=="exp.php"){
			$firstUrl="./".$aclPgl[1]."?exps_code=".(4001-$acc['skin']);
		}

	//해당기능을 사용 가능한지 체크한다.2011-03-29
if($_SESSION['sunap']!="admin"){
	if($_GET["sale_code"]==1001){
		if(!$acc['skin'])
			$aclCnt=0;
	}
	if($_GET["sale_code"]==1002){
		if(!$acc['cos'])
			$aclCnt=0;
	}
	if($_GET["exps_code"]==4000){
		if(!$acc['skin'])
			$aclCnt=0;
	}
	if($_GET["exps_code"]==4001){
		if(!$acc['cos'])
			$aclCnt=0;
	}
}else{
	$aclCnt=1;
}
if($logged_info->is_admin=="Y" || $logged_info->group_list[3]=="정회원"){
	if($_SESSION['sunap'] && $aclCnt && $aclIp){
		$logout="<td style='text-align:center;width:70px;'><br /><a href='./sunap.php'>로그아웃</a></td>";
	}else{
		header("Location:./sunap.php");
	}
}else{
	$host='Location:http://'.$_SERVER['SERVER_NAME'].'/?mid=sunap&act=dispMemberLoginForm';
	header($host);
}
	$docSql="select * from t_doctor order by `no` asc";
	$docRes = mysql_query($docSql, $connect); 
	$docTot = mysql_num_rows($docRes); // 총 레코드 수
	$docRow = mysql_fetch_row($docRes);
	$hospital = $docRow[3]; //병원 이름 2011-04-09

$msgDiv='<div id="msgDiv" name="msgDiv" style="background-color:#FFFF99;position:absolute;left:300px;top:65px;z-index:1;height:20px;width:600px;text-align:center;vertical-align:middle;display:inline;"></div>';
	$tableD='<center><table border="1" style="text-align:center;width:99%;background-color:#FFFFFF;border-color:#CA2F32;" cellspacing="0" cellpadding="0">';
	$extColor="#F0575A";
	$tabColor="#FFE3E2";
	$thColor="#783723";
	$style['exp.php']="text-align:center;";
	$style['sum.php']="text-align:center;";
	$style[$cLoc]='font-weight:bolder;text-align:center;"';//선택된 메뉴 색 지정

$menuS='<table style="width:100%;table-layout:fixed;">
 <tr>
 <td><a href='.$firstUrl.'><img src="pearl.gif" border="0"></a></td>';
	if($viewMenu){
		$menuA='<td style="text-align:center;width:60px;'.$style['view.php'].'"><br /><a href="view.php">검토</a></td><td style="width:5px;"><br />|</td><td style="text-align:center;width:60px;'.$style['report.php'].'"><br /><a href="report.php">보고서</a></td><td style="width:5px;"><br />|</td>';

		if($_SESSION['sunap']=="admin")
			$menuA=$menuA.'<td style="text-align:center;width:60px;'.$style['admin.php'].'"><br /><a href="admin.php">관리</a></td><td style="width:5px;"><br />|</td>';
	}
if($payView){
	$payExp=$payExp.'<td style="width:80px;'.$style['pay.php'].'"><br /><a href="pay.php?sale_code='.(1002-$acc['skin']).'">수입부 입력</a></td><td style="width:5px;"><br />|</td>';
}
if($expView){
	$payExp=$payExp.'<td style="width:80px;'.$style['exp.php'].'"><br /><a href="exp.php?exps_code='.(4001-$acc['skin']).'">지출부 입력</a></td><td style="width:5px;"><br />|</td>';
}
if($sumView){
	$payExp=$payExp.'<td style="width:60px;'.$style['sum.php'].$style['daily.php'].'"><br /><a href="sum.php">일정산<a></td><td></td>';
}
if($acc['all'])
	$payExp=$payExp.'<td style="width:80px;'.$style['static.php'].$style['month.php'].'"><br /><a href="static.php?exps_code='.(4001-$acc['skin']).'">지출부 집계</a></td><td style="width:5px;"><br />|</td>';

$menu=$msgDiv.$menuS.$payExp.$menuA.$logout.'</tr></table><HR>'.$de;
$menu2=$menuS.'<td style="width:60%;"></td>'.$menuA.$logout.'</tr></table><HR>';


//comma를 보여주는 함수
function comma($number){
	if($number<0){
		$sine=1;
		$number=$number*(-1);
	}
	$nl=strlen($number);
	if($nl>6){
		$no=substr($number,0,$nl-6).",".substr($number,$nl-6,3).",".substr($number,-3);
	}
	else if($nl>3){
		$no=substr($number,0,$nl-3).",".substr($number,-3);
	}else if($nl>0){
		$no=$number;
	}else{
		$no=0;
	}
	if($sine){
		$no="-".$no;
	}
	return $no;
}
?>