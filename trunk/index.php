<!--@if($logged_info=="")-->
	<script>
		if(document.location.pathname=="/modules/hospital/"){
			document.location.replace("./sunap.php");
		}else{
			document.location.replace("./?mid=sunap&act=dispMemberLoginForm");
		}
	</script>
<!--@else-->
	<!--@if($logged_info->is_admin=="Y" || $logged_info->group_list[3]=="정회원")-->
		<script>
			document.location.replace("./modules/hospital/sunap.php");
		</script>
	<!--@else-->
		권한이 없습니다.
	<!--@end-->
<!--@end-->