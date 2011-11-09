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
		exit;
	}
    mysql_query("set session character_set_connection=utf8;");
    mysql_query("set session character_set_results=utf8;");
    mysql_query("set session character_set_client=utf8;");
		if($_POST['sd']){//시작 날짜 입력
			$sql="INSERT INTO `toto_closedsale` VALUES (1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', '', 'Y', 'Y', '".$_POST['sd']."', '".time()."');";
			$tabRes = mysql_query($sql, $connect);
		}

		if(!table_if('toto_acl')){
			$sql="CREATE TABLE `toto_acl` (
			  `no` int(11) NOT NULL auto_increment,
			  `user_id` varchar(20) NOT NULL,
			  `user_pw` varchar(20) NOT NULL,
			  `view_pg` varchar(200) NOT NULL,
			  `reg_date` varchar(8) NOT NULL,
			  `user_ip` varchar(200) NOT NULL,
			  `view_skin` varchar(1) NOT NULL,
			  `view_cos` varchar(1) NOT NULL,
			  `view_all` varchar(1) NOT NULL,
			  PRIMARY KEY  (`no`),
			  KEY `no` (`no`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_acl` VALUES (1, 'admin', 'admin', 'index.php|pay.php|exp.php|sum.php|daily.php|static.php|month.php|view.php|report.php', '20110517', '*', 'Y', 'Y', 'Y');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="CREATE TABLE `toto_balancedoctor` (
			  `no` int(11) NOT NULL auto_increment,
			  `rmdy_doct` varchar(4) NOT NULL,
			  `bfor_mony` bigint(20) NOT NULL,
			  `aftr_mony` bigint(20) NOT NULL,
			  `gner_cont` int(11) NOT NULL,
			  `insu_cont` int(11) NOT NULL,
			  `reg_date` int(8) NOT NULL,
			  `date` int(11) NOT NULL,
			  PRIMARY KEY  (`no`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;";
			$tabRes = mysql_query($sql, $connect); 
			$sql="CREATE TABLE `toto_bankbook` (
			  `bkbk_seqn` int(10) NOT NULL auto_increment,
			  `bkbk_idid` int(11) NOT NULL,
			  `incm_mony` bigint(20) NOT NULL,
			  `exps_mony` bigint(20) NOT NULL,
			  `tday_mony` bigint(20) NOT NULL,
			  `reg_date` varchar(8) NOT NULL,
			  `datetime` varchar(10) NOT NULL,
			  PRIMARY KEY  (`bkbk_seqn`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;";
			$tabRes = mysql_query($sql, $connect); 
			$sql="CREATE TABLE `toto_bankbookinfo` (
			  `no` int(4) NOT NULL auto_increment,
			  `bkbk_name` varchar(100) NOT NULL,
			  `sort_numb` int(4) NOT NULL,
			  `use_flag` varchar(2) NOT NULL,
			  `bank` varchar(100) NOT NULL,
			  PRIMARY KEY  (`no`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;";
			$tabRes = mysql_query($sql, $connect); 
			$sql="CREATE TABLE `toto_closedsale` (
			  `no` int(11) NOT NULL auto_increment,
			  `d_afdy_mony` bigint(20) NOT NULL,
			  `d_cash_mony` bigint(20) NOT NULL,
			  `d_cash_insu_mony` bigint(20) NOT NULL,
			  `d_cscd_mony` bigint(20) NOT NULL,
			  `d_cscd_insu_mony` bigint(20) NOT NULL,
			  `d_card_mony` bigint(20) NOT NULL,
			  `d_card_insu_mony` bigint(20) NOT NULL,
			  `d_yet__mony` bigint(20) NOT NULL,
			  `d_refd_mony` bigint(20) NOT NULL,
			  `d_doct_mony` bigint(20) NOT NULL,
			  `d_cout_mony` bigint(20) NOT NULL,
			  `d_bout_mony` bigint(20) NOT NULL,
			  `d_card_legal` bigint(20) NOT NULL,
			  `d_card_indiv` bigint(20) NOT NULL,
			  `c_afdy_mony` bigint(20) NOT NULL,
			  `c_cash_mony` bigint(20) NOT NULL,
			  `c_cscd_mony` bigint(20) NOT NULL,
			  `c_card_mony` bigint(20) NOT NULL,
			  `c_yet__mony` bigint(20) NOT NULL,
			  `c_refd_mony` bigint(20) NOT NULL,
			  `c_doct_mony` bigint(20) NOT NULL,
			  `c_cout_mony` bigint(20) NOT NULL,
			  `c_bout_mony` bigint(20) NOT NULL,
			  `c_card_legal` bigint(20) NOT NULL,
			  `c_card_indiv` bigint(20) NOT NULL,
			  `dmsv_flag` varchar(1) NOT NULL,
			  `cssv_flag` varchar(1) NOT NULL,
			  `bdsv_flag` varchar(1) NOT NULL,
			  `ask__chck` varchar(1) NOT NULL,
			  `clos_chck` varchar(1) NOT NULL,
			  `reg_date` varchar(8) NOT NULL,
			  `date` varchar(11) NOT NULL,
			  PRIMARY KEY  (`no`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;";
			$tabRes = mysql_query($sql, $connect); 
			$sql="CREATE TABLE `toto_code` (
			  `no` int(11) NOT NULL auto_increment,
			  `code` varchar(10) NOT NULL,
			  `name` varchar(10) NOT NULL,
			  `cate` varchar(10) NOT NULL,
			  PRIMARY KEY  (`no`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_code` VALUES (1, '2001', '매출', 'slit_code');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_code` VALUES (2, '2002', '외상입금', 'slit_code');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_code` VALUES (3, '2003', '환불', 'slit_code');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_code` VALUES (4, '0101', '보험', 'insu_code');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_code` VALUES (5, '0102', '일반', 'insu_code');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_code` VALUES (6, '0', '현금', 'exps_gubn');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_code` VALUES (7, '1', '통장', 'exps_gubn');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_code` VALUES (8, '2', '카드(법인)', 'exps_gubn');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_code` VALUES (9, '3', '카드(개인)', 'exps_gubn');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="CREATE TABLE `toto_doctor` (
			  `no` int(11) NOT NULL auto_increment,
			  `doct_numb` varchar(4) NOT NULL,
			  `doct_name` varchar(50) NOT NULL,
			  `hosp_name` varchar(20) NOT NULL,
			  `etc` varchar(100) NOT NULL,
			  PRIMARY KEY  (`no`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;";
			$tabRes = mysql_query($sql, $connect); 
			$sql="CREATE TABLE `toto_exp` (
			  `no` int(100) NOT NULL auto_increment,
			  `exps_code` varchar(4) NOT NULL,
			  `exps_gubn` varchar(1) NOT NULL,
			  `exps_cate` varchar(4) NOT NULL,
			  `exps_cust` varchar(100) NOT NULL,
			  `exps_caus` varchar(100) NOT NULL,
			  `cash_mony` bigint(20) NOT NULL,
			  `reg_date` int(8) NOT NULL,
			  `date` int(11) NOT NULL,
			  `etc` text NOT NULL,
			  `reg_user` varchar(20) NOT NULL,
			  PRIMARY KEY  (`no`),
			  UNIQUE KEY `no` (`no`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;";
			$tabRes = mysql_query($sql, $connect); 
			$sql="CREATE TABLE `toto_exp` (
			  `no` int(100) NOT NULL auto_increment,
			  `exps_code` varchar(4) NOT NULL,
			  `exps_gubn` varchar(1) NOT NULL,
			  `exps_cate` varchar(4) NOT NULL,
			  `exps_cust` varchar(100) NOT NULL,
			  `exps_caus` varchar(100) NOT NULL,
			  `cash_mony` bigint(20) NOT NULL,
			  `reg_date` int(8) NOT NULL,
			  `date` int(11) NOT NULL,
			  `etc` text NOT NULL,
			  `reg_user` varchar(20) NOT NULL,
			  PRIMARY KEY  (`no`),
			  UNIQUE KEY `no` (`no`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;";
			$tabRes = mysql_query($sql, $connect); 
			$sql="CREATE TABLE `toto_expc` (
			  `no` int(11) NOT NULL auto_increment,
			  `exps_cate` varchar(4) NOT NULL,
			  `exps_sort` varchar(50) NOT NULL,
			  `exps_name` varchar(100) NOT NULL,
			  `exps_code` varchar(4) NOT NULL,
			  PRIMARY KEY  (`no`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8  AUTO_INCREMENT=9;";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_expc` VALUES (1, '1001', '광고판촉비', '광고선전비', '4000');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_expc` VALUES (2, '1101', '기타경비', '도서인쇄비', '4000');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_expc` VALUES (3, '1102', '기타경비', '비품', '4000');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_expc` VALUES (4, '1104', '기타경비', '소모품비', '4000');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_expc` VALUES (5, '1106', '기타경비', '여비교통비', '4000');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_expc` VALUES (6, '1107', '기타경비', '차량유지비', '4000');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_expc` VALUES (7, '1108', '기타경비', '협회비', '4000');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_expc` VALUES (8, '1109', '기타경비', '잡비', '4000');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_expc` VALUES (9, '1501', '의약품', '의료소모품', '4000');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="CREATE TABLE `toto_log` (
			  `no` int(11) NOT NULL auto_increment,
			  `user_id` varchar(20) NOT NULL,
			  `user_ip` varchar(20) NOT NULL,
			  `view_pg` varchar(20) NOT NULL,
			  `reg_time` varchar(10) NOT NULL,
			  `reg_date` varchar(8) NOT NULL,
			  PRIMARY KEY  (`no`),
			  KEY `no` (`no`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;";
			$tabRes = mysql_query($sql, $connect); 
			$sql="CREATE TABLE `toto_page` (
			  `no` int(11) NOT NULL auto_increment,
			  `name` varchar(20) NOT NULL,
			  `file` varchar(20) NOT NULL,
			  `reg_date` varchar(10) NOT NULL,
			  `etc` text NOT NULL,
			  PRIMARY KEY  (`no`)
			) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ;";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_page` VALUES (1, '수납', 'pay.php', '20110415', '');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_page` VALUES (2, '지출', 'exp.php', '20110415', '');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_page` VALUES (3, '원장별정산', 'sum.php', '20110415', '');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_page` VALUES (4, '일일정산', 'daily.php', '20110415', '');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_page` VALUES (5, '일별지출', 'static.php', '20110415', '');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_page` VALUES (6, '월별지출', 'month.php', '20110415', '');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_page` VALUES (7, '검토', 'view.php', '20110415', '');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_page` VALUES (8, '보고서', 'report.php', '20110415', '');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="CREATE TABLE `toto_pay` (
			  `no` int(100) NOT NULL auto_increment,
			  `sale_code` varchar(4) NOT NULL,
			  `slit_code` varchar(4) NOT NULL,
			  `insu_code` varchar(4) NOT NULL,
			  `rmst_code` varchar(4) NOT NULL,
			  `rmdy_doct` varchar(4) NOT NULL,
			  `cust_cnum` int(11) NOT NULL,
			  `cust_name` varchar(30) NOT NULL,
			  `cash_mony` bigint(20) NOT NULL,
			  `cscd_mony` bigint(20) NOT NULL,
			  `card_mony` bigint(20) NOT NULL,
			  `yet__mony` bigint(20) NOT NULL,
			  `slit_desc` bigint(20) NOT NULL,
			  `sort_numb` bigint(20) NOT NULL,
			  `reg_date` int(8) NOT NULL,
			  `date` int(11) NOT NULL,
			  `etc` text NOT NULL,
			  `reg_user` varchar(20) NOT NULL,
			  PRIMARY KEY  (`no`),
			  UNIQUE KEY `no` (`no`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;";
			$tabRes = mysql_query($sql, $connect); 
			$sql="CREATE TABLE `toto_payc` (
			  `no` int(11) NOT NULL auto_increment,
			  `rmst_code` varchar(4) NOT NULL,
			  `rmst_name` varchar(50) NOT NULL,
			  `sale_code` varchar(4) NOT NULL,
			  `etc` varchar(100) NOT NULL,
			  PRIMARY KEY  (`no`)
			) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ;";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_payc` VALUES (1, '3001', '레이저(점제거, 검버섯수술)', '', '');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_payc` VALUES (2, '3002', '기미제거', '', '');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_payc` VALUES (3, '3003', '스킨스케일링', '', '');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_payc` VALUES (4, '3004', '여드름치료', '', '');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_payc` VALUES (5, '3005', '주름살제거', '', '');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_payc` VALUES (6, '3006', '크리스탈필링', '', '');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_payc` VALUES (7, '3007', '모발(털제거)', '', '');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_payc` VALUES (8, '3008', '액취증수술', '', '');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_payc` VALUES (9, '3009', '피부박피', '', '');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_payc` VALUES (10, '3010', '기타', '', '');";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_payc` VALUES (11, '3011', '보험', '', '');";
			$tabRes = mysql_query($sql, $connect); 
         
      chmod("./logo", 777);//logo 디렉터리 권한 설정
                  
			if($tabRes){ 
				$datetime=time();
				$reg_date=date("Ymd",$datetime);
				echo "초기 설정이 완료되었습니다. <br />";
				echo "시작일 지정 후 관리자(admin/admin)로 접속하세요.<br />";
				echo "<form method='post'><input type='text' name='sd' id='sd' value='".$reg_date."'>";
				echo "<input type='submit' value='시작일 지정'>(현재 : 오늘(시작일 부터 마감이 시작되어야 합니다.)</form>";
				exit;
			}
		}
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
<?php
		}else{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>수납관리 시스템</title>
  <link rel="shortcut icon" href="./logo/favicon.ico">
</head> 
			<div style="text-align:center;position:relative;top:100px;">
			<center>
			<table style="text-align:center;" width="500">
			<tr>
           <td colspan="2"><img src="./logo/logins.gif"></td>
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