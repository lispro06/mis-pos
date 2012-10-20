<?php
require ('./xajax_core/xajax.inc.php');
$xajax = new xajax();

/*
	- enable deubgging if desired
	- set the javascript uri (location of xajax js files)
*/
//$xajax->configure('debug', true);
$xajax->configure('javascript URI', './');



/*
	Function: save
	
	save is_table
*/
function save($date_input)
{
	
	global $connect;
	$objResponse = new xajaxResponse();
	$setTime=$date_input;
	$date_input=str_replace("-","",$date_input);// '-' 제거
	session_register("reg_date");
	$_SESSION['session_reg_date']=$date_input;
// 날짜 계산 부분
	$start_date=$setTime;
	$m=substr($start_date,5,2);
	$end_date=date("Ymd", mktime(0,0,0,($m+1),0,substr($start_date,0,4)));
	$start_date=date("Ymd", mktime(0,0,0,$m,1,substr($start_date,0,4)));
	$year=substr($start_date,0,4);

//slit_code 가 2001이면 매출, 2002이면 외상입금, 2003이면 환불
//환불은 -값이므로 slit_code에 의한 조건문은 포함되지 않음

//// 일반(비보험) 계산
			$sql="select * from `toto_pay` where `sale_code`='1001' and `insu_code`='0102' and `reg_date` between '".$start_date."' and '".$end_date."' order by reg_date asc";
			$item_res = mysql_query($sql, $connect);
			$item_total = mysql_num_rows($item_res); // 사용하는 계정 항목의 총 수
	while($item_total--){
		$row = mysql_fetch_row($item_res);
		$cash_mony=$row[8]+$cash_mony;
		$cscd_mony=$row[9]+$cscd_mony;
		$card_mony=$row[10]+$card_mony;
		$yet__mony=$row[11]+$yet__mony;
	}

//// 보험 계산
			$isql="select * from `toto_pay` where `sale_code`='1001' and `insu_code`='0101' and `reg_date` between '".$start_date."' and '".$end_date."' order by reg_date asc";
			$item_res = mysql_query($isql, $connect);
			$item_total = mysql_num_rows($item_res); // 사용하는 계정 항목의 총 수
	while($item_total--){
		$irow = mysql_fetch_row($item_res);
		$icash_mony=$irow[8]+$icash_mony;
		$icscd_mony=$irow[9]+$icscd_mony;
		$icard_mony=$irow[10]+$icard_mony;
		$iyet__mony=$irow[11]+$iyet__mony;
	}

//// 코스메틱 계산
			$sql="select sum(cash_mony+cscd_mony+card_mony+yet__mony) from `toto_pay` where `sale_code`='1002' and `reg_date` between '".$start_date."' and '".$end_date."' order by reg_date asc";
			$item_res = mysql_query($sql, $connect);
			$cosrow = mysql_fetch_row($item_res);

			$sale_sums=$cash_mony+$icash_mony+$cscd_mony+$icscd_mony+$icard_mony+$card_mony+$yet__mony+$iyet__mony+$cosrow[0];//매출액

// 지출액 금액 구하기
	$selSql="SELECT * FROM `toto_is_code` WHERE `skin_cos` = '4000' AND `is_active` =  '1' ORDER BY  `no` ASC";
	$selSql="SELECT * FROM `toto_is_code` WHERE `is_active` =  '1' ORDER BY  `no` ASC";//피부과와 코스메틱 모두 출력
	$result = mysql_query($selSql, $connect); 
	$total = mysql_num_rows($result); // 사용하는 계정 항목의 총 수
	while($total--){
		$row = mysql_fetch_row($result);
		$rows=explode(",",$row[2]);// 그룹안에 포함된 모든 계정항목을 출력함
		//echo $row[2]."<br />";
		$sums=0;// 그룹 안의 계정항목 소계
		$items=0;// 각 그룹에 해당하는 계정과목으로 입력된 총 건 수
		$prop="";
		for($i=0;$i<count($rows);$i++){
			//echo "select * from `toto_exp` where `exps_code`='4000' and `exps_cate`='$rows[$i]' and `reg_date` between '".$start_date."' and '".$end_date."' order by reg_date asc";
			$sql="select * from `toto_exp` where `exps_code`='4000' and `exps_cate`='$rows[$i]' and `reg_date` between '".$start_date."' and '".$end_date."' order by reg_date asc";// 피부과만
			$sql="select * from `toto_exp` where `exps_cate`='$rows[$i]' and `reg_date` between '".$start_date."' and '".$end_date."' order by reg_date asc";// 피부과 및 코스메틱
			$res = mysql_query($sql, $connect);
			$t = mysql_num_rows($res); // 사용하는 계정 항목의 총 수
			for($j=0;$j<$t;$j++){//계정 과목에 포함 된 모든 레코드를 더하기 위한 반복문
				$r = mysql_fetch_row($res);
				$sums=$sums+$r[6];//그룹 안에 포함 된 계정 항목 금액의 합계만 구함
				$items++;
			}
		}
		if($total){
			$item_amt=$item_amt.$sums.";";
		}else
			$item_amt=$item_amt.$sums."";

			$exps_sums=$sums+$exps_sums;//판매비와 관리비 총액 더하기
	}

			//누적액 구하기
	if($m=="01"){
		$pre_m="12";
	}
	else{
		$pre_m=(intval($m)-1);
	}
	if($is_month<10){
		$pre_m="0".$pre_m;
	}
			$accu_sql="select `is_cumu_income`, `is_cumu_exps` from `toto_is_table` where `is_year`='".$year."' and `is_month`='".$pre_m."'";
			$pre_res = mysql_query($accu_sql, $connect);
			$prerow = mysql_fetch_row($pre_res);
			if($m=="01"){
				$accu_sale_sums=$sale_sums;
				$accu_exps_sums=$exps_sums;
			}else{
				$accu_sale_sums=$prerow[0]+$sale_sums;
				$accu_exps_sums=$prerow[1]+$exps_sums;
			}
			if($first_month){
				$accu_sale_sums=$sale_sums;
				$accu_exps_sums=$exps_sums;
			}

// 현재 월이 손익 계산 테이블에 있는지 확인
	$cSql="SELECT `no` FROM `toto_is_table` WHERE  `is_month` = '".$m."' AND `is_year` = '".$year."'";
	$c_res = mysql_query($cSql, $connect);
	$cRow = mysql_fetch_row($c_res);
	
	if($cRow){
		$query="DELETE FROM  `toto_is_table` where `no` = '".$cRow[0]."'";
		$delQue = mysql_query($query, $connect);
	}
		//$query="UPDATE  `toto_is_table` SET  `is_sale` =  '".$sale_sums."', `is_cash` =  '".($cash_mony+$cscd_mony)."', `is_insu_card` =  '".($icard_mony+0)."', `is_insu_cash` =  '".($icash_mony+$icscd_mony)."', `is_card` =  '".($card_mony+0)."', `is_cosmetic` =  '".($cosrow[0]+0)."', `is_exps` =  '".$exps_sums."', `is_exps_value` =  '".$item_amt."', `is_ebit` =  '".($sale_sums-$exps_sums)."', `is_tax` =  '0', `is_net` =  '0', `is_bank` =  '0', `is_balance` =  '0', `is_cumu_income` =  '0', `is_cumu_exps` =  '0', `is_cumu_ebit` =  '0', `is_reg_date` =  '".date(Ymd,time())."', `is_month` =  '".$m."', `is_year` =  '".$year."', `is_user` =  '".$_SESSION['sunap']."' WHERE  `toto_is_table`.`no` ='".$cRow[0]."'";
		//$msg=date("Y-M-D H:i:s",time());
		$objResponse->assign('tt', 'innerHTML', '수정되었습니다.');
	
	//데이터를 삭제하고 무조건 새로 저장
	$query="INSERT INTO `toto_is_table` (`no`, `is_sale`, `is_cash`, `is_insu_card`, `is_insu_cash`, `is_card`, `is_cosmetic`, `is_exps`, `is_exps_value`, `is_ebit`, `is_tax`, `is_net`, `is_bank`, `is_balance`, `is_cumu_income`, `is_cumu_exps`, `is_cumu_ebit`, `is_reg_date`, `is_month`, `is_year`, `is_user`) VALUES
('', ".$sale_sums.", ".($cash_mony+$cscd_mony+$yet__mony).", ".($icard_mony+0).", ".($icash_mony+$icscd_mony+$iyet__mony).", ".($card_mony+0).", ".($cosrow[0]+0).", ".$exps_sums.", '".$item_amt."', ".($sale_sums-$exps_sums).", 0, 0, 0, 0, ".$accu_sale_sums.", ".$accu_exps_sums.", ".($accu_sale_sums-$accu_exps_sums).", '".date(Ymd,time())."', '".$m."', '".$year."', '".$_SESSION['sunap']."');";

    $saveQue = mysql_query($query, $connect);;
//	$objResponse->assign('btn', 'disabled', "true");
//	$objResponse->assign('tt', 'innerHTML', $query);
	$objResponse->call('refresh_entry()');
	
	return $objResponse;
}

/*
	Function: saveAll
	
	save is_table All
*/
function saveAll($date_input,$curr_date)
{
	
	global $connect;
	$objResponse = new xajaxResponse();
	$setTime=$date_input;
	$date_input=str_replace("-","",$date_input);// '-' 제거
	session_register("reg_date");
	$_SESSION['session_reg_date']=$date_input;
// 날짜 계산 부분(현재달)
	$start_date=$setTime;
	$m=substr($start_date,5,2);
	$end_date=date("Ymd", mktime(0,0,0,($m+1),0,substr($start_date,0,4)));
	$start_date=date("Ymd", mktime(0,0,0,$m,1,substr($start_date,0,4)));
	$year=substr($start_date,0,4);
// 날짜 계산 부분(지난달)-누적 매출 구하기 위한 부분
	if($curr_date!=1){
		$pre_m=substr($curr_date,5,2);
		$pre_y=substr($curr_date,0,4);
	}else{
		$first_month=1;
	}

//slit_code 가 2001이면 매출, 2002이면 외상입금, 2003이면 환불
//환불은 -값이므로 slit_code에 의한 조건문은 포함되지 않음

//// 일반(비보험) 계산
			$sql="select * from `toto_pay` where `sale_code`='1001' and `insu_code`='0102' and `reg_date` between '".$start_date."' and '".$end_date."' order by reg_date asc";
			$item_res = mysql_query($sql, $connect);
			$item_total = mysql_num_rows($item_res); // 사용하는 계정 항목의 총 수
	while($item_total--){
		$row = mysql_fetch_row($item_res);
		$cash_mony=$row[8]+$cash_mony;
		$cscd_mony=$row[9]+$cscd_mony;
		$card_mony=$row[10]+$card_mony;
		$yet__mony=$row[11]+$yet__mony;
	}

//// 보험 계산
			$isql="select * from `toto_pay` where `sale_code`='1001' and `insu_code`='0101' and `reg_date` between '".$start_date."' and '".$end_date."' order by reg_date asc";
			$item_res = mysql_query($isql, $connect);
			$item_total = mysql_num_rows($item_res); // 사용하는 계정 항목의 총 수
	while($item_total--){
		$irow = mysql_fetch_row($item_res);
		$icash_mony=$irow[8]+$icash_mony;
		$icscd_mony=$irow[9]+$icscd_mony;
		$icard_mony=$irow[10]+$icard_mony;
		$iyet__mony=$irow[11]+$iyet__mony;
	}

//// 코스메틱 계산
			$sql="select sum(cash_mony+cscd_mony+card_mony+yet__mony) from `toto_pay` where `sale_code`='1002' and `reg_date` between '".$start_date."' and '".$end_date."' order by reg_date asc";
			$item_res = mysql_query($sql, $connect);
			$cosrow = mysql_fetch_row($item_res);

			$sale_sums=$cash_mony+$cscd_mony+$icash_mony+$icscd_mony+$icard_mony+$card_mony+$yet__mony+$iyet__mony+$cosrow[0];//매출액
			
// 지출액 금액 구하기
	$selSql="SELECT * FROM `toto_is_code` WHERE `skin_cos` = '4000' AND `is_active` =  '1' ORDER BY  `no` ASC";
	$selSql="SELECT * FROM `toto_is_code` WHERE `is_active` =  '1' ORDER BY  `no` ASC";//피부과와 코스메틱 모두 출력
	$result = mysql_query($selSql, $connect); 
	$total = mysql_num_rows($result); // 사용하는 계정 항목의 총 수
	while($total--){
		$row = mysql_fetch_row($result);
		$rows=explode(",",$row[2]);// 그룹안에 포함된 모든 계정항목을 출력함
		//echo $row[2]."<br />";
		$sums=0;// 그룹 안의 계정항목 소계
		$items=0;// 각 그룹에 해당하는 계정과목으로 입력된 총 건 수
		$prop="";
		for($i=0;$i<count($rows);$i++){
			//echo "select * from `toto_exp` where `exps_code`='4000' and `exps_cate`='$rows[$i]' and `reg_date` between '".$start_date."' and '".$end_date."' order by reg_date asc";
			$sql="select * from `toto_exp` where `exps_code`='4000' and `exps_cate`='$rows[$i]' and `reg_date` between '".$start_date."' and '".$end_date."' order by reg_date asc";// 피부과만
			$sql="select * from `toto_exp` where `exps_cate`='$rows[$i]' and `reg_date` between '".$start_date."' and '".$end_date."' order by reg_date asc";// 피부과 및 코스메틱
			$res = mysql_query($sql, $connect);
			$t = mysql_num_rows($res); // 사용하는 계정 항목의 총 수
			for($j=0;$j<$t;$j++){//계정 과목에 포함 된 모든 레코드를 더하기 위한 반복문
				$r = mysql_fetch_row($res);
				$sums=$sums+$r[6];//그룹 안에 포함 된 계정 항목 금액의 합계만 구함
				$items++;
			}
		}
		if($total){
			$item_amt=$item_amt.$sums.";";
		}else
			$item_amt=$item_amt.$sums."";

			$exps_sums=$sums+$exps_sums;//판매비와 관리비 총액 더하기
	}

			//누적액 구하기
			$accu_sql="select `is_cumu_income`, `is_cumu_exps` from `toto_is_table` where `is_year`='".$pre_y."' and `is_month`='".$pre_m."'";
			$pre_res = mysql_query($accu_sql, $connect);
			$prerow = mysql_fetch_row($pre_res);
			if($m=="01"){
				$accu_sale_sums=$sale_sums;
				$accu_exps_sums=$exps_sums;
			}else{
				$accu_sale_sums=$prerow[0]+$sale_sums;
				$accu_exps_sums=$prerow[1]+$exps_sums;
			}
			if($first_month){
				$accu_sale_sums=$sale_sums;
				$accu_exps_sums=$exps_sums;
			}

// 현재 월이 손익 계산 테이블에 있는지 확인
	$cSql="SELECT `no` FROM `toto_is_table` WHERE  `is_month` = '".$m."' AND `is_year` = '".$year."'";
	$c_res = mysql_query($cSql, $connect);
	$cRow = mysql_fetch_row($c_res);
	
	if($cRow){
		$query="DELETE FROM  `toto_is_table` where `no` = '".$cRow[0]."'";
		$delQue = mysql_query($query, $connect);
	}
		//$query="UPDATE  `toto_is_table` SET  `is_sale` =  '".$sale_sums."', `is_cash` =  '".($cash_mony+$cscd_mony)."', `is_insu_card` =  '".($icard_mony+0)."', `is_insu_cash` =  '".($icash_mony+$icscd_mony)."', `is_card` =  '".($card_mony+0)."', `is_cosmetic` =  '".($cosrow[0]+0)."', `is_exps` =  '".$exps_sums."', `is_exps_value` =  '".$item_amt."', `is_ebit` =  '".($sale_sums-$exps_sums)."', `is_tax` =  '0', `is_net` =  '0', `is_bank` =  '0', `is_balance` =  '0', `is_cumu_income` =  '0', `is_cumu_exps` =  '0', `is_cumu_ebit` =  '0', `is_reg_date` =  '".date(Ymd,time())."', `is_month` =  '".$m."', `is_year` =  '".$year."', `is_user` =  '".$_SESSION['sunap']."' WHERE  `toto_is_table`.`no` ='".$cRow[0]."'";
		//$msg=date("Y-M-D H:i:s",time());
		$objResponse->assign('tt', 'innerHTML', '수정되었습니다. '.$m);
	
	//데이터를 삭제하고 무조건 새로 저장
	$query="INSERT INTO `toto_is_table` (`no`, `is_sale`, `is_cash`, `is_insu_card`, `is_insu_cash`, `is_card`, `is_cosmetic`, `is_exps`, `is_exps_value`, `is_ebit`, `is_tax`, `is_net`, `is_bank`, `is_balance`, `is_cumu_income`, `is_cumu_exps`, `is_cumu_ebit`, `is_reg_date`, `is_month`, `is_year`, `is_user`) VALUES
('', ".$sale_sums.", ".($cash_mony+$cscd_mony+$yet__mony).", ".($icard_mony+0).", ".($icash_mony+$icscd_mony+$iyet__mony).", ".($card_mony+0).", ".($cosrow[0]+0).", ".$exps_sums.", '".$item_amt."', ".($sale_sums-$exps_sums).", 0, 0, 0, 0, ".$accu_sale_sums.", ".$accu_exps_sums.", ".($accu_sale_sums-$accu_exps_sums).", '".date(Ymd,time())."', '".$m."', '".$year."', '".$_SESSION['sunap']."');";

    $saveQue = mysql_query($query, $connect);;
//	$objResponse->assign('btn', 'disabled', "true");
//	$objResponse->assign('tt', 'innerHTML', $query);
//	$objResponse->call('refresh_entry()');
	
	return $objResponse;
}
/*
	Function: inquiry
	
	inquiry
*/
function inquiry($date_input)
{
	global $connect;
	$objResponse = new xajaxResponse();
	if($date_input!=""){
		$setTime=$date_input;
		$date_input=str_replace("-","",$date_input);// '-' 제거
		session_register("reg_date");
		$_SESSION['session_reg_date']=$date_input;
	}else{
//전체 저장을 위해 최초 저장된 레코드의 날짜 구함.
		$que="SELECT min(reg_date)  FROM `toto_pay`";
		$result = mysql_query($que, $connect);
		$row=mysql_fetch_array($result);
		$m=substr($row[0],4,2);
		$first_date=date("Ymd", mktime(0,0,0,$m,1,substr($row[0],0,4)));//최초 저장달의 1일
		$is_not_first_day=0;
		while(date("Ym",$next_date)<date("Ym",time())){
			$curr_date=mktime(0,0,0,($m-1),1,substr($row[0],0,4));
			$next_date=mktime(0,0,0,$m++,1,substr($row[0],0,4));
			//$str=$str.date("Ym",$next_date);//새롭게 업데이트 된 월들의 스트링
			if($is_not_first_day){
				saveAll(date("Y-m-d",$next_date),date("Y-m-d",$curr_date));
			}else{
				saveAll(date("Y-m-d",$next_date),1);
			}
			$is_not_first_day=1;
		}
		
		$date_input=$_SESSION['session_reg_date'];//session에 저장된 날짜를 이용함.
		$setTime=substr($date_input,0,4);
		$setTime=$setTime."-".substr($date_input,4,2);
		$setTime=$setTime."-".substr($date_input,6,2);
	}
// 날짜 계산 부분
	$start_date=$setTime;
	$m=substr($start_date,5,2);
	$limit_m=$m;//금년 출력에서 이전 달까지만 출력하기 위한 변수로 활용
	$end_date=date("Ymd", mktime(0,0,0,($m+1),0,substr($start_date,0,4)));
	$start_date=date("Ymd", mktime(0,0,0,$m,1,substr($start_date,0,4)));
	$pre_end_date=mktime(0,0,0,($m+1),0,substr($start_date,0,4));//이전 달을 구하기 위한 준비
	$pre_start_date=mktime(0,0,0,$m,1,substr($start_date,0,4));//이전 달을 구하기 위한 준비
	$start_month=date("Y-m", mktime(0,0,0,$m,1,substr($start_date,0,4)));
	$start_year=substr($start_date,0,4);
	$date_input=str_replace("-","",$setTime);// '-' 제거

//항목 코드 배열 생성
	$selSql="SELECT * FROM `toto_is_code` WHERE `is_active` =  '1' ORDER BY  `no` ASC";//피부과와 코스메틱 모두 출력
	$result = mysql_query($selSql, $connect); 
	$total = mysql_num_rows($result); // 사용하는 계정 항목의 총 수
	while($total--){
		$is_row[]=mysql_fetch_array($result);// 항목 코드 번호를 알기 위한 fetch
	}


	//table의 시작
	$updated=$divSize.'<center><table border="1" style="text-align:center;background-color:#FFFFFF;border-color:#CA2F32;" cellspacing="0" cellpadding="1"><tr><td>';

	//메인 리스트로 항목 이름을 담고 있는 컬럼이다.
	$mlist="<div style='text-align:left;border: 1px solid #CA2F32;'>\n	 <ul style='width:120px;text-align:left;border: 0px solid #CA2F32;float:left;list-style-type:none;height:980px;'>";
	//저장된 손익계산표를 출력하는 table
	$slist="<div style='text-align:left;border: 1px solid #CA2F32;overflow:scroll;overflow-y:hidden;width:800px;height:1000px;'>\n	 \n<div name='mainDiv' id='mainDiv' style='width:4400px;'>";
	$mlist=$mlist."<li style='border: 1px solid #CA2F32;background-color:#FFE3E2;text-align:center;'><b>과목</b></li>";
	
//전전년 출력

	$selSqlc="SELECT * FROM `toto_is_table` where `is_year` = ". ($start_year-2);
	$is_result = mysql_query($selSqlc, $connect);
	$close = mysql_num_rows($is_result);
	for($cnt=0;$cnt<$close;$cnt++){
		$rows[$cnt] = mysql_fetch_row($is_result);
		//서브 리스트로 손익계산 테이블을 출력한다.
		$slist=$slist."<ul style='width:70px;border: 0px solid #CA2F32;text-align:right;float:left;position:relative;left:-".(40+$cnt*10)."px;z-index:".(100-$cnt)."'>";
		//저장된 월별 매출액의 출력
		$slist=$slist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;background-color:#FFE3E2;text-align:center;list-style-type:none;' type='none'><b>".$rows[$cnt][19]."년 ".$rows[$cnt][18]."월</b></li>";
		for($cnt2=1;$cnt2<8;$cnt2++){
			$slist=$slist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($rows[$cnt][$cnt2])."원</li>";
		}
		$is_list=explode(";",$rows[$cnt][8]);//항목별 매출액이 든 컬럼
		for($cnt3=0;$cnt3<count($is_list);$cnt3++){
			if($is_list[$cnt3]>0){
				$year=$rows[$cnt][19];
				$m=$rows[$cnt][18];
				$item_end_date=date("Ymd", mktime(0,0,0,($m+1),0,$year));
				$item_start_date=date("Ymd", mktime(0,0,0,$m,1,$year));
				$slist=$slist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'><a href='#' onclick=\"ajax_showTooltip(window.event,'ajax-tooltip/is_detail.php?code=".$is_row[$cnt3][2]."&sdate=".$item_start_date."&edate=".$item_end_date."',this);return false\" >".comma($is_list[$cnt3])."원</a></li>";
			}else{
				$slist=$slist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($is_list[$cnt3])."원</li>";
			}
		}
		//영업이익
			$slist=$slist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($rows[$cnt][9])."원</li>";
		for($cnt2=1;$cnt2<8;$cnt2++){//이하 영역
			$slist=$slist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($rows[$cnt][$cnt2+9])."원</li>";
		}
			$slist=$slist."</ul>";
	}
	
//전년 출력

	$selSqlc="SELECT * FROM `toto_is_table` where `is_year` = '". ($start_year-1)."' order by `is_month` asc;";
	$is_result = mysql_query($selSqlc, $connect);
	$close = mysql_num_rows($is_result);

	for($cnt=0;$cnt<$close;$cnt++){
		$rows[$cnt] = mysql_fetch_row($is_result);
		//서브 리스트로 손익계산 테이블을 출력한다.
		$slist=$slist."<ul style='width:70px;border: 0px solid #CA2F32;text-align:right;float:left;position:relative;left:-".(40+$cnt*10)."px;z-index:".(100-$cnt)."'>";
		//저장된 월별 매출액의 출력
		$slist=$slist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;background-color:#FFE3E2;text-align:center;list-style-type:none;' type='none'><b>".$rows[$cnt][19]."년 ".$rows[$cnt][18]."월</b></li>";
		for($cnt2=1;$cnt2<8;$cnt2++){
			$slist=$slist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($rows[$cnt][$cnt2])."원</li>";
		}
		$is_list=explode(";",$rows[$cnt][8]);//항목별 매출액이 든 컬럼
		for($cnt3=0;$cnt3<count($is_list);$cnt3++){
			if($is_list[$cnt3]>0){
				$year=$rows[$cnt][19];
				$m=$rows[$cnt][18];
				$item_end_date=date("Ymd", mktime(0,0,0,($m+1),0,$year));
				$item_start_date=date("Ymd", mktime(0,0,0,$m,1,$year));
				$slist=$slist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'><a href='#' onclick=\"ajax_showTooltip(window.event,'ajax-tooltip/is_detail.php?code=".$is_row[$cnt3][2]."&sdate=".$item_start_date."&edate=".$item_end_date."',this);return false\" >".comma($is_list[$cnt3])."원</a></li>";
			}else{
				$slist=$slist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($is_list[$cnt3])."원</li>";
			}
		}
		//영업이익
			$slist=$slist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($rows[$cnt][9])."원</li>";
		for($cnt2=1;$cnt2<8;$cnt2++){//이하 영역
			$slist=$slist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($rows[$cnt][$cnt2+9])."원</li>";
		}
			$slist=$slist."</ul>";
	}

//금년 출력

	$selSqlc="SELECT * FROM `toto_is_table` where `is_year` = '". $start_year."' and `is_month` between '01' and '".$limit_m."' order by `is_month` asc";
	$is_result = mysql_query($selSqlc, $connect);
	$close = mysql_num_rows($is_result)-1;//선택 달 이전 까지만 출력하도록 한다.

	//현재 월(月)리스트로 저장을 위한 작업을 위해 출력한다.
	$clist="<ul style='width:70px;border: 0px solid #CA2F32;text-align:right;float:left;position:relative;left:-50px;z-index:100'>";
	//전월 대비 리스트
	$plist="<ul style='width:70px;border: 0px solid #CA2F32;text-align:right;float:left;position:relative;left:-60px;z-index:99'>";
	//전년 대비 리스트
	$yplist="<ul style='width:70px;border: 0px solid #CA2F32;text-align:right;float:left;position:relative;left:-70px;z-index:98'>";
	//금년 합계 리스트
	$sumlist="<ul style='width:70px;border: 0px solid #CA2F32;text-align:right;float:left;position:relative;left:-80px;z-index:97'>";
	//비중 리스트
	$ratelist="<ul style='width:70px;border: 0px solid #CA2F32;text-align:right;float:left;position:relative;left:-90px;z-index:96'>";
	
	$selSql="SELECT * FROM `toto_is_code` WHERE `is_active` =  '1' ORDER BY  `no` ASC";//피부과와 코스메틱 모두 출력
	$result = mysql_query($selSql, $connect); 
	$total = mysql_num_rows($result); // 사용하는 계정 항목의 총 수
	while($total--){
		$is_row[]=mysql_fetch_array($result);// 항목 코드 번호를 알기 위한 fetch
	}
	for($cnt=0;$cnt<$close;$cnt++){
		$rows[$cnt] = mysql_fetch_row($is_result);
		//서브 리스트로 손익계산 테이블을 출력한다.
		$slist=$slist."<ul style='width:70px;border: 0px solid #CA2F32;text-align:right;float:left;position:relative;left:-".(40+$cnt*10)."px;z-index:".(100-$cnt)."'>";
		//저장된 월별 매출액의 출력
		$slist=$slist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;background-color:#FFE3E2;text-align:center;list-style-type:none;' type='none'><b>".$rows[$cnt][19]."년 ".$rows[$cnt][18]."월</b></li>";
		for($cnt2=1;$cnt2<8;$cnt2++){
			$slist=$slist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($rows[$cnt][$cnt2])."원</li>";
		}
		$is_list=explode(";",$rows[$cnt][8]);//항목별 매출액이 든 컬럼
		for($cnt3=0;$cnt3<count($is_list);$cnt3++){
			if($is_list[$cnt3]>0){
				$year=$rows[$cnt][19];
				$m=$rows[$cnt][18];
				$item_end_date=date("Ymd", mktime(0,0,0,($m+1),0,$year));
				$item_start_date=date("Ymd", mktime(0,0,0,$m,1,$year));
				$slist=$slist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'><a href='#' onclick=\"ajax_showTooltip(window.event,'ajax-tooltip/is_detail.php?code=".$is_row[$cnt3][2]."&sdate=".$item_start_date."&edate=".$item_end_date."',this);return false\" >".comma($is_list[$cnt3])."원</a></li>";
			}else{
				$slist=$slist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($is_list[$cnt3])."원</li>";
			}
				$sum_item_row[$cnt3]=$sum_item_row[$cnt3]+$is_list[$cnt3];//항목별 합계를 구하는 배열
		}
		//영업이익
			$slist=$slist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($rows[$cnt][9])."원</li>";
		for($cnt2=1;$cnt2<8;$cnt2++){//이하 영역
			$slist=$slist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($rows[$cnt][$cnt2+9])."원</li>";
		}
			$slist=$slist."</ul>";
	}
// 선택된 월의 값을 데이타베이스의 저장된 값으로 가져온다.
			$rows[++$cnt] = mysql_fetch_row($is_result);

			$cash_mony=$rows[$cnt][2];//현금
			$icard_mony=$rows[$cnt][3];//보험카드
			$icash_mony=$rows[$cnt][4];//보험현금
			$card_mony=$rows[$cnt][5];//카드
			$cosmetic_sale=$rows[$cnt][6];//코스메틱
			$exps_sums=$rows[$cnt][7];//판매비와 관리비
			$current_item=$rows[$cnt][8];//항목별 지출
			$ebit_mony=$rows[$cnt][9];//영업 이익
			$accu_sale_sums=$rows[$cnt][14];//누적 매출
			$accu_exps_sums=$rows[$cnt][15];//누적 지출
			$accu_ebit_sums=$rows[$cnt][16];//누적 영업이익

			$sale_sums=$rows[$cnt][1];//매출액
//////////////////////////////////////// 전 월 /////////////////////////////////////////////////	
	$selSqlc="SELECT * FROM `toto_is_table` where `is_year` = '".$start_year."' and `is_month`='".$m."';";
	$is_result = mysql_query($selSqlc, $connect);
	$close = mysql_num_rows($is_result);
	
		$rows[$cnt] = mysql_fetch_row($is_result);
		//서브 리스트로 손익계산 테이블을 출력한다.

		for($cnt2=1;$cnt2<8;$cnt2++){
			$p_arr[]=$rows[$cnt][$cnt2];
		}
		$p_list=explode(";",$rows[$cnt][8]);//항목별 매출액이 든 컬럼


		$psale_sums=$p_arr[0];
		$pcash_mony=$p_arr[1];
		$picard_mony=$p_arr[2];
		$picash_mony=$p_arr[3];
		$pcard_mony=$p_arr[4];
		$pcosm_mony=$p_arr[5];
		$pexps_sums=$p_arr[6];
		$p_eibt=$rows[$cnt][9];//전월 영업이익
		$p_tax=$rows[$cnt][10];//전월 세금
		$p_net=$rows[$cnt][11];//전월 당기 순이익
		$p_bank=$rows[$cnt][12];//전월 통장 현금 보유
		$p_balance=$rows[$cnt][13];//전월 보유 잔액
		$p_cumu_incom=$rows[$cnt][14];//전월 당기 순이익
		$p_cumu_exps=$rows[$cnt][16];//전월 통장 현금 보유
		$p_cumu_ebit=$rows[$cnt][17];//전월 보유 잔액

//////////////////////////////////////// 전 월 /////////////////////////////////////////////////



//////////////////////////////////////// 전 년 /////////////////////////////////////////////////	
	
	if($m=="12"){
		$is_month=1;
		$start_year++;
	}
	else{
		$is_month=(intval($m)+1);
	}
	if($is_month<10){
		$is_month="0".$is_month;
	}
	$selSqlc="SELECT * FROM `toto_is_table` where `is_year` = '".($start_year-1)."' and `is_month`='".$is_month."';";
	$is_result = mysql_query($selSqlc, $connect);
	$close = mysql_num_rows($is_result);
	
		$rows[$cnt] = mysql_fetch_row($is_result);
		//서브 리스트로 손익계산 테이블을 출력한다.

		for($cnt2=1;$cnt2<8;$cnt2++){
			$yp_arr[]=$rows[$cnt][$cnt2];
		}
		$yp_list=explode(";",$rows[$cnt][8]);//항목별 매출액이 든 컬럼


		$ypsale_sums=$yp_arr[0];
		$ypcash_mony=$yp_arr[1];
		$ypicard_mony=$yp_arr[2];
		$ypicash_mony=$yp_arr[3];
		$ypcard_mony=$yp_arr[4];
		$ypcosm_mony=$yp_arr[5];
		$ypexps_sums=$yp_arr[6];
		$yp_eibt=$rows[$cnt][9];

//////////////////////////////////////// 전 년 /////////////////////////////////////////////////


	$mlist=$mlist."<li style='border: 1px solid #CA2F32;background-color:#FFFFFF;' type='none'><b>Ⅰ. 매출액</b></li>	";
	// 현재 년월
	$clist=$clist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;background-color:#FFE3E2' type='none'><b>".str_replace("-","년 ",$start_month)."월</b></li>";
	// 전월대비
	$plist=$plist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;background-color:#FFFFFF' type='none'><b>전월 대비</b></li>";
	//전년 대비
	$yplist=$yplist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;background-color:#FFFFFF' type='none'><b>전년 대비</b><input text id='focusBox' style='width:0px;height:1px;'></li>";
	//금년 합계
	$sumlist=$sumlist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;background-color:#FFFFFF' type='none'><b>".substr($start_month,0,4)."년 합계</b></li>";
	//비중
	$ratelist=$ratelist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;background-color:#FFFFFF' type='none'><b>비중</b></li>";

			//금년 합계
			$sum_sql="select sum(`is_sale`), sum(`is_cash`), sum(`is_insu_card`), sum(`is_insu_cash`), sum(`is_card`), sum(`is_cosmetic`), sum(`is_exps`), sum(`is_ebit`) from `toto_is_table` where `is_year`='".$start_year."' and `is_month` between '01' and '".$m."'";
			$sum_res = mysql_query($sum_sql, $connect);
			$sum_row = mysql_fetch_row($sum_res);

	// 매출액

	$clist=$clist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($sale_sums)."원</li>";
	$plist=$plist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".(100 * round(($sale_sums-$psale_sums) /$psale_sums, 2))."%</li>";
	$yplist=$yplist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".(100*round(($sale_sums-$ypsale_sums)/$ypsale_sums, 2))."%</li>";
	$sumlist=$sumlist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($sum_row[0])."원</li>";
	$ratelist=$ratelist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".(100*round($sale_sums/$sum_row[0],2))."%</li>";
	//현금
	$mlist=$mlist."<li style='border: 1px solid #CA2F32;' type='none'>현금</li>";
	$yplist=$yplist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".(100*round(($cash_mony-$ypcash_mony) / $ypcash_mony, 2))."%</li>";
	$plist=$plist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".(100*round(($cash_mony-$pcash_mony)/$pcash_mony,2))."%</li>";
	$clist=$clist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($cash_mony)."원</li>";
	$sumlist=$sumlist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($sum_row[1])."원</li>";
	$ratelist=$ratelist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".(100*round($cash_mony/$sum_row[1],2))."%</li>";
	//보험카드
	$mlist=$mlist."<li style='border: 1px solid #CA2F32;' type='none'>보험카드</li>";
	$yplist=$yplist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".(100*round(($icard_mony-$ypicard_mony)/$ypicard_mony,2))."%</li>";
	$plist=$plist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".(100*round(($icard_mony-$picard_mony)/$picard_mony,2))."%</li>";
	$clist=$clist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($icard_mony)."원</li>";
	$sumlist=$sumlist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($sum_row[2])."원</li>";
	$ratelist=$ratelist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".(100*round($icard_mony/$sum_row[2],2))."%</li>";
	//보험현금
	$mlist=$mlist."<li style='border: 1px solid #CA2F32;' type='none'>보험현금</li>";
	$plist=$plist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".(100*round(($icash_mony-$picash_mony)/$picash_mony,2))."%</li>";
	$yplist=$yplist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".(100*round(($icash_mony-$ypicash_mony)/$ypicash_mony,2))."%</li>";
	$clist=$clist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($icash_mony)."원</li>";
	$sumlist=$sumlist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($sum_row[3])."원</li>";
	$ratelist=$ratelist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".(100*round($icash_mony/$sum_row[3],2))."%</li>";
	//카드
	$mlist=$mlist."<li style='border: 1px solid #CA2F32;' type='none'>카드</li>";
	$yplist=$yplist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".(100*round(($card_mony-$ypcard_mony)/$ypcard_mony,2))."%</li>";
	$plist=$plist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".(100*round(($card_mony-$pcard_mony)/$pcard_mony,2))."%</li>";
	$clist=$clist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($card_mony)."원</li>";
	$sumlist=$sumlist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($sum_row[4])."원</li>";
	$ratelist=$ratelist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".(100*round($card_mony/$sum_row[4],2))."%</li>";
	//코스메틱
	$mlist=$mlist."<li style='border: 1px solid #CA2F32;' type='none'>코스메틱</li>";
	$yplist=$yplist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".(100*round(($cosmetic_sale-$ypcosm_mony)/$ypcosm_mony,2))."%</li>";
	$plist=$plist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".(100*round(($cosmetic_sale-$pcosm_mony)/$pcosm_mony,2))."%</li>";
	$clist=$clist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($cosmetic_sale)."원</li>";
	$sumlist=$sumlist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($sum_row[5])."원</li>";
	$ratelist=$ratelist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".(100*round($cosmetic_sale/$sum_row[5],2))."%</li>";
	

// 등록된 항목 중 사용하는 항목(is_active=1)만 출력하기 위한 쿼리
	$selSql="SELECT * FROM `toto_is_code` WHERE `skin_cos` = '4000' AND `is_active` =  '1' ORDER BY  `no` ASC";
	$selSql="SELECT * FROM `toto_is_code` WHERE `is_active` =  '1' ORDER BY  `no` ASC";//피부과와 코스메틱 모두 출력
	$iscnt=0;
	$result = mysql_query($selSql, $connect); 
	$total = mysql_num_rows($result); // 사용하는 계정 항목의 총 수

	$citems = explode(";",$current_item);
	$mlist=$mlist."<li style='border: 1px solid #CA2F32;' type='none'><b>Ⅳ. 판매비와 관리비</b></li>";
	while($total--){
		$row = mysql_fetch_row($result);
		$sums=$citems[$iscnt];
			$mlist=$mlist."<li style='border: 1px solid #CA2F32;' type='none'>".$row[1]."</li>";
			//항목 값 출력
			if($citems[$iscnt]){//항목이 있으면, 세부항목을 볼 수 있도록 tooltip을 제공한다.
				$clist_sale=$clist_sale."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'><a href='#' onclick=\"ajax_showTooltip(window.event,'ajax-tooltip/is_detail.php?code=".$row[2]."&sdate=".$start_date."&edate=".$end_date."',this);return false\" >".comma($sums)."원</a></li>";
			}else{
				$clist_sale=$clist_sale."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($sums)."원</li>";
			}

			// 금년 합계와 비중 리스트 추가 2012-10-04
				$sum_item_row[$iscnt]=$sum_item_row[$iscnt]+$sums;//항목별 합계를 구하는 배열
				$sumlist=$sumlist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($sum_item_row[$iscnt])."원</li>";
			if($sum_item_row[$iscnt]==0){
				$ratelist=$ratelist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>-%</li>";
			}else{
				$ratelist=$ratelist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".(100*round($sums/$sum_item_row[$iscnt],2))."%</li>";
			}

			//전월 대비와 전년 대비에서 div by 0 예외 처리
			if($yp_list[$iscnt]==0){
				$yplist_sale=$yplist_sale."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>-%</li>";
			}else{
				$yplist_sale=$yplist_sale."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".(100*round(($sums-$yp_list[$iscnt])/$yp_list[$iscnt],2))."%</li>";
			}
			if($p_list[$iscnt]==0){
				$plist_sale=$plist_sale."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>-%</li>";
			}else{
				$plist_sale=$plist_sale."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".(100*round(($sums-$p_list[$iscnt])/$p_list[$iscnt],2))."%</li>";
			}
			$iscnt++;//항목 array 용 cnt
	}
	//판매비와 관리비
	$clist=$clist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($exps_sums)."원</li>".$clist_sale;
	$yplist=$yplist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>-%</li>".$yplist_sale;
	$plist=$plist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>-%</li>".$plist_sale;
	$sumlist=$sumlist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($sum_row[6])."원</li>";
	$ratelist=$ratelist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".(100*round($exps_sums/$sum_row[6],2))."%</li>";
	//영업이익
	$mlist=$mlist."<li style='border: 1px solid #CA2F32;' type='none'><b>Ⅴ.영업 이익</b></li>";
	$plist=$plist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>-%</li>";
	$yplist=$yplist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>-%</li>";
	$clist=$clist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($ebit_mony)."원</li>";
	$sumlist=$sumlist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($sum_row[7])."원</li>";
	$ratelist=$ratelist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".(100*round($ebit_mony/$sum_row[7],2))."%</li>";
	//세금
	$mlist=$mlist."<li style='border: 1px solid #CA2F32;' type='none'><b>Ⅸ.세금</b></li>";
	$clist=$clist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."-"."원</li>";
	$plist=$plist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."-"."</li>";
	$yplist=$yplist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."&nbsp;"."</li>";
	$sumlist=$sumlist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."-"."</li>";
	$ratelist=$ratelist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."&nbsp;"."</li>";
	//당기 순이익
	$mlist=$mlist."<li style='border: 1px solid #CA2F32;' type='none'><b>Ⅹ.당기 순이익</b></li>";
	$yplist=$yplist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."-"."</li>";
	$plist=$plist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."-"."</li>";
	$clist=$clist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."-"."원</li>";
	$sumlist=$sumlist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."-"."</li>";
	$ratelist=$ratelist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."&nbsp;"."</li>";
	//통장 현금 보유
	$mlist=$mlist."<li style='border: 1px solid #CA2F32;' type='none'><b>통장 현금 보유</b></li>";
	$yplist=$yplist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."-"."</li>";
	$plist=$plist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."-"."</li>";
	$clist=$clist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."-"."원</li>";
	$sumlist=$sumlist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."-"."</li>";
	$ratelist=$ratelist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."&nbsp;"."</li>";
	//보유 잔액
	$mlist=$mlist."<li style='border: 1px solid #CA2F32;' type='none'><b>보유잔액</b></li>";
	$yplist=$yplist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."-"."</li>";
	$plist=$plist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."-"."</li>";
	$clist=$clist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."-"."원</li>";
	$sumlist=$sumlist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."-"."</li>";
	$ratelist=$ratelist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."&nbsp;"."</li>";
	//누적 매출
	$mlist=$mlist."<li style='border: 1px solid #CA2F32;' type='none'><b>누적매출</b></li>";
	$yplist=$yplist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."-"."</li>";
	$plist=$plist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."-"."</li>";
	$clist=$clist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($accu_sale_sums)."원</li>";
	$sumlist=$sumlist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."-"."</li>";
	$ratelist=$ratelist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."&nbsp;"."</li>";
	//누적 지출
	$mlist=$mlist."<li style='border: 1px solid #CA2F32;' type='none'><b>누적지출</b></li>";
	$yplist=$yplist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."-"."</li>";
	$plist=$plist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."-"."</li>";
	$clist=$clist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($accu_exps_sums)."원</li>";
	$sumlist=$sumlist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."-"."</li>";
	$ratelist=$ratelist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."&nbsp;"."</li>";
	//누적 영업 이익
	$mlist=$mlist."<li style='border: 1px solid #CA2F32;' type='none'><b>누적영업이익</b></li>";
	$yplist=$yplist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."-"."</li>";
	$plist=$plist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."-"."</li>";
	$clist=$clist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>".comma($accu_ebit_sums)."원</li>";
	$sumlist=$sumlist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."-"."</li>";
	$ratelist=$ratelist."<li style='width:100px;text-align:left;border: 1px solid #CA2F32;text-align:right;list-style-type:none;' type='none'>"."&nbsp;"."</li>";
	$clist=$clist."</ul>";
	$plist=$plist."</ul>";
	$yplist=$yplist."</ul>";
	$sumlist=$sumlist."</ul>";
	$ratelist=$ratelist."</ul>";

	$mlist=$updated.$mlist."</ul></div></td><td>\n	".$slist.$clist.$plist.$yplist.$sumlist.$ratelist."	\n</div>	\n</div>\n";
	$updated=$mlist."</td></tr></table>";

	$msg=$_SESSION['sunap']."님은 ".$setTime."일자 자료입력 중입니다.";
	$objResponse->assign('msgDiv', 'innerHTML', $msg);
	$objResponse->assign('divtable', 'innerHTML', $updated);
	$objResponse->call('scrollDiv()');

	return $objResponse;
}


$reqInquiry =& $xajax->registerFunction('inquiry');
$reqSave =& $xajax->registerFunction('save');
$reqSaveAll =& $xajax->registerFunction('saveAll');

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