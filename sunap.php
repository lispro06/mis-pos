<?php

header("Content-Type: text/html; charset=UTF-8");

    define('__ZBXE__', true);

	include("../../files/config/db.config.php");
	$dbname=$db_info->db_userid;
	$dbpass=$db_info->db_password;

	function table_if($tableName)
	{ 
		global $connect,$dbname;
		
		$sql ="SHOW TABLES WHERE Tables_in_" . $dbname . " = '" . $tableName . "'";
		$rs = mysql_query($sql);

		if(!mysql_fetch_array($rs))
			return FALSE;
		else
			return TRUE;
	}
	$connect = mysql_connect("localhost", $dbname, $dbpass); 
	$result=mysql_select_db($dbname, $connect);
	if ( !$connect ) { 
		echo " 데이터베이스에 연결할 수 없습니다."; 
	}else{
		if(!table_if ('toto_acl')){
			$sql=file_get_contents("sql.txt");
			$tabRes = mysql_query($sql, $connect); 
		}
	}
    mysql_query("set session character_set_connection=utf8;");
    mysql_query("set session character_set_results=utf8;");
    mysql_query("set session character_set_client=utf8;");

	require_once('../../config/config.inc.php');
	$oContext = &Context::getInstance();
	$oContext->init();
	$logged_info = Context::get('logged_info');

	if($logged_info->is_admin=="Y" || $logged_info->group_list[3]=="정회원"){
		$_SESSION['sunap']="";
	// 인증 방식을 db에서 id, pw 비교로 교체 2011-03-17
	$aclSql="select * from `toto_acl` where `user_id`='".$_POST['idtxt']."' and `user_pw`='".$_POST['pdtxt']."'";
	$aclRes = mysql_query($aclSql, $connect); 
	$aclRow = mysql_fetch_row($aclRes);

		if($aclRow[1]){
			session_register("sunap");
			$_SESSION['sunap']=$aclRow[1];
			$aclPgl=explode("|",$aclRow[3]);
			
			if($aclRow[6]=="Y")
				$acc['skin']=1;
			else
				$acc['skin']=0;
		if($aclPgl[1]=="pay.php"){
			$firstUrl="./".$aclPgl[1]."?sale_code=".(1002-$acc['skin']);
		}else if($aclPgl[1]=="exp.php"){
			$firstUrl="./".$aclPgl[1]."?exps_code=".(4001-$acc['skin']);
		}
?>
			<script>
				document.location.replace("<?php echo $firstUrl;?>");
			</script>
<?
		}else{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>수납관리 시스템</title>
<link rel="shortcut icon" href="favicon.ico">
</head>
			<div style="text-align:center;position:relative;top:100px;">
			<center>
			<table style="text-align:center;" width="500">
			<tr>
			<td colspan="2"><img src="logins.jpg"></td>
			</tr>
			<tr>
			<td align="center"><br />
				<form method="post" name="fr" id="fr">
				<div style="align:center;vertical-align:bottom;height:200px;width:500px;border:3px solid #e6e6e6;"><br /><br />
				<table>
					<tr>
						<td><span style="bolder;">아이디</span></td>
						<td><input type="text" name="idtxt" id="idtxt" tabindex="1" onKeyDown="javascript:if(event.keyCode == 13) { idInput(this); return false;}" style="width:150px;border: 3px solid #e6e6e6;" value="<?php echo $_POST['idtxt']?>" maxlength="20"></td>
						<td rowspan=2><input type="image" tabindex="3" src="lb.jpg" value="로그인" onClick="submit();"></td>
					</tr>
						<tr>
							<td>비밀번호</td>
							<td><input type="password" name="pdtxt" id="pdtxt" tabindex="2" onKeyDown="javascript:if(event.keyCode == 13) { pwInput(this); return false;}" style="width:150px;border: 3px solid #e6e6e6;" value="<?php echo $_POST['pdtxt']?>" maxlength="20"></td>
						</tr>
					<tr>
						<td colspan="3"><br />
						<img src="only.jpg">
						</td>
					</tr>
				</table>
				</div>
			</td>
			<form>
			</tr>
			</table>
			</center>
			</div>
			<script>
			document.getElementById("idtxt").focus();
			function idInput(obj){
				if(obj.value==""){
					alert("아이디를 입력하세요");
					obj.focus();
				}else{
					document.getElementById("pdtxt").focus();
				}
			}
			function pwInput(obj){
				if(obj.value==""){
					alert("비밀번호를 입력하세요");
					obj.focus();
				}else{
					submit();
				}
				return false;
			}
			</script>
</html>
<?php
		}
	}else{
		$host='Location:http://'.$_SERVER['SERVER_NAME'].'/?mid=sunap&act=dispMemberLoginForm';
		header($host);
	}
?>