<?php
	include_once("menu.php");
	include_once("views.php");
	$cTime=time();
	$setTime=date("Y-m-d",$cTime);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<html>
<head>
	<title>수납관리 프로그램 - 검토</title>
<link rel="shortcut icon" href="favicon.ico">
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery-1.4.1.js"></script>
<script type="text/javascript" src="jquery-ui.js"></script>
<script>
	/* <![CDATA[ */
		window.onload = function() {
			xajax_endView("0");
		}
</script>
</head>
<?php
	// output the xajax javascript. This must be called between the head tags
	$xajax->printJavascript();
?>
<body>
<div id="menu" name="menu">
<?php
 echo $menu;
?>
<div class="exterior">
<div class="tabA">
		<table border="0" style="text-align:center;width:100%;background-color:<?php echo $extColor;?>"><tr>
		<td class="noline"></td>
		<td class="noline"></td>
		<td class="noline"><span style="font-size:12px;">구분</span></td>
		<td class="noline">
			<select id="end_gubn" name="end_gubn" class="clist" onchange="xajax_endView(xajax.$('end_gubn').value);">
				<option value="0">마감요청</option>
				<option value="1">마감완료</option>
				<option value="2">작성중</option>
			</select>
		</td>
		<td class="noline"><input type="button" onclick="xajax_endView(xajax.$('end_gubn').value);" value="조회"></td>
		<td class="noline"></td>
		<td class="noline"></td>
		<td class="noline"></td>
		<td class="noline"></td>
		<td class="noline"></td>
		<td class="noline"></td>
		<td><input type="text" id="selDate" name="selDate" style="border:0px;background-color:<?echo $extColor;?>" disabled></td>
		<td class="noline"></td>
		</tr></table>
		<div id="wlist" name="wlist" style="overflow-y:auto; height:100px; width:99%; padding:4px;display:block;">
		</div>
		<p></p>
		<div name="divtable" id="divtable">
		</div>
		<form name="form1" action="">
		  <div style="width:99%" align="right">
		  <input type="button" name="end" id="end" value="일마감" disabled=TRUE onclick="xajax_endBt(xajax.$('selDate').value);" >
		  </div>
		</form>
	</div>
</div>
</body>
</html>