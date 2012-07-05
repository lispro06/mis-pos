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
      $sql="INSERT INTO `toto_bankbookinfo` (`no`, `bkbk_name`, `sort_numb`, `use_flag`, `bank`) VALUES (1, '피부과1', 1, '01', 'G'), (2, '피부과2', 2, '01', 'G'), (3, '코스메틱1', 1, '02', 'C'), (4, '코스메틱2', 10, '02', 'C');";
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
       $sql="CREATE TABLE IF NOT EXISTS `toto_expc` (
  `no` int(11) NOT NULL auto_increment,
  `exps_cate` varchar(4) NOT NULL,
  `exps_sort` varchar(50) NOT NULL,
  `exps_name` varchar(100) NOT NULL,
  `exps_code` varchar(4) NOT NULL,
  PRIMARY KEY  (`no`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=74";
			$tabRes = mysql_query($sql, $connect); 
			$sql="INSERT INTO `toto_expc` (`no`, `exps_cate`, `exps_sort`, `exps_name`, `exps_code`) VALUES
(1, '0000', '토토다우드', '토토다우드', '4000'),
(2, '1000', '광고판촉비', '광고판촉비', '4000'),
(3, '1001', '광고선전비', '광고선전비', '4000'),
(4, '1002', '판매수수료', '판매수수료', '4000'),
(5, '1003', '판매촉진비', '판매촉진비', '4000'),
(6, '1100', '기타경비', '기타경비', '4000'),
(7, '1101', '도서인쇄비', '도서인쇄비', '4000'),
(8, '1102', '비품', '비품', '4000'),
(9, '1103', '사무용품비', '사무용품비', '4000'),
(10, '1104', '소모품비', '소모품비', '4000'),
(11, '1105', '수선비', '수선비', '4000'),
(12, '1106', '여비교통비', '여비교통비', '4000'),
(14, '1107', '차량유지비', '차량유지비', '4000'),
(15, '1108', '협회비', '협회비', '4000'),
(16, '1109', '잡비', '잡비', '4000'),
(17, '1110', '기부금', '기부금', '4000'),
(18, '1200', '보증금', '보증금', '4000'),
(19, '1201', '건물보증금', '건물보증금', '4000'),
(20, '1202', '의료기기보증금등', '의료기기보증금등', '4000'),
(21, '1300', '시설비', '시설비', '4000'),
(22, '1301', '인테리어비', '인테리어비', '4000'),
(23, '1400', '운송비', '운송비', '4000'),
(24, '1401', '퀵서비스_ 택배요금등', '퀵서비스_ 택배요금등', '4000'),
(25, '1500', '의약품', '의약품', '4000'),
(26, '1501', '의료소모품', '의료소모품', '4000'),
(27, '1502', '의약품비', '의약품비', '4000'),
(28, '1503', '화장품', '화장품', '4000'),
(29, '1600', '인건비', '인건비', '4000'),
(30, '1601', '대표원장사용액(봉직의인센티브)', '대표원장사용액(봉직의인센티브)', '4000'),
(31, '1602', '봉직의 급여', '봉직의 급여', '4000'),
(32, '1603', '상여금', '상여금', '4000'),
(33, '1604', '잡급', '잡급', '4000'),
(34, '1605', '제수당', '제수당', '4000'),
(35, '1606', '직원급여', '직원급여', '4000'),
(36, '1607', '퇴직급여', '퇴직급여', '4000'),
(37, '1700', '인건비성 경비', '인건비성 경비', '4000'),
(38, '1701', '교육훈련비', '교육훈련비', '4000'),
(39, '1702', '보험료', '보험료', '4000'),
(40, '1703', '복리후생비', '복리후생비', '4000'),
(41, '1704', '세금과공과금', '세금과공과금', '4000'),
(42, '1705', '퇴직급여충당금', '퇴직급여충당금', '4000'),
(43, '1706', '학회비', '학회비', '4000'),
(44, '1800', '자본인출금', '자본인출금', '4000'),
(45, '1801', '대표원장인출액', '대표원장인출액', '4000'),
(46, '1802', '전도금', '전도금', '4000'),
(47, '1900', '접대비', '접대비', '4000'),
(48, '1901', '접대비', '접대비', '4000'),
(49, '1902', '미지급금', '미지급금', '4000'),
(50, '2000', '지급수수료', '지급수수료', '4000'),
(51, '2001', '기타 지급수수료', '기타 지급수수료', '4000'),
(52, '2002', '법률.회계자문수수료', '법률.회계자문수수료', '4000'),
(53, '2003', '의료기기 리스료', '의료기기 리스료', '4000'),
(54, '2004', '차량리스료', '차량리스료', '4000'),
(55, '2005', '카드수수료', '카드수수료', '4000'),
(56, '2006', '컨설팅수수료', '컨설팅수수료', '4000'),
(57, '2007', '의료기기', '의료기기', '4000'),
(58, '2008', '이자비용', '이자비용', '4000'),
(59, '2100', '지급임차료 및 관리비', '지급임차료 및 관리비', '4000'),
(60, '2101', '건물관리비', '건물관리비', '4000'),
(61, '2102', '수도광열비', '수도광열비', '4000'),
(62, '2103', '전력비', '전력비', '4000'),
(63, '2104', '지급임차_건물관리비', '지급임차_건물관리비', '4000'),
(64, '2200', '통신비', '통신비', '4000'),
(65, '2201', '인터넷 통신료', '인터넷 통신료', '4000'),
(66, '2202', '전화요금(SMS등)', '전화요금(SMS등)', '4000'),
(67, '2203', '통신비', '통신비', '4000'),
(68, '2300', '기타', '기타', '4000'),
(69, '2301', '기타', '기타', '4000'),
(70, '2302', '자산', '자산', '4000'),
(71, '2303', '자본금', '자본금', '4000'),
(72, '2401', '카드', '하나BC', '4000'),
(73, '2402', '카드', '현대비자', '4000'),
(73, '2501', '코스메틱', '화장품', '4001'),
(73, '2502', '코스메틱', '잡비', '4001'),
(73, '2503', '코스메틱', '가공품', '4001');";
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