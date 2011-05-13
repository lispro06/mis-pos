	/* <![CDATA[ */
function CheckCont(){//고객명 입력 확인 후, 저장 함수 호출
	var cs=document.getElementById("cash_mony");
	if(cs.value==""){
		alert("입력 오류");
		sFocus();
	}else{
		xajax_savePay(xajax.$("no").value,xajax.$("exps_code").value, xajax.$("exps_gubn").value, xajax.$("exps_cate").value, xajax.$("exps_cust").value, xajax.$("exps_caus").value,xajax.$("cash_mony").value,xajax.$("date_input").value,xajax.$("etc").value)
	}
}
		/* ]]> */

function pp(){
	var objDiv = document.getElementById("content");
	objDiv.scrollTop = objDiv.scrollHeight;
	objDiv.style.display="block";
	document.getElementById("pv").style.display="block";
}
function previewH(){
	prediv = document.getElementById("pdiv");
	prediv.style.display = "none";
}
function preview(){
	prediv = document.getElementById("pdiv");
	prediv.style.display = "block";
}
function top(){
	window.scrollTo(0,0);
}
function sFocus(){
	var objDiv = document.getElementById("exps_gubn");
	objDiv.focus();
}
function bottom(){
	var objDiv = document.getElementById("content");//scroll을 최 하단으로 이동시킨다.
	objDiv.scrollTop = objDiv.scrollHeight;
}
function comma(number){
	number=number+'';//number to string
	var nl=number.length;
	var no=1;
	if(nl>6){
		no=number.substr(0,nl-6)+","+number.substr(nl-6,3)+","+number.substr(nl-3,3);
	}else if(nl>3){
		no=number.substr(0,nl-3)+","+number.substr(nl-3,3);
	}else{
		no=number;
	}
	return no;
}
function SumAmount(num){//합계 출력 함수
	var nv=num.value;
	var cn=nv.replace(/,/g,"");
	num.value=comma(cn);//금액에 comma 추가
}
function CheckEnter(val) {//enter key 입력시 다음 입력 상자로 이동
      doc = document.getElementById("fr");
   if (event.keyCode == 13) {
      for(var i = 0; i< doc.elements.length-1; i++) {
         if (doc.elements[i].name == val.name) {
            for(var j = i; j< doc.elements.length-1; j++) {
                doc.elements[j+1].focus();
                return;
            }
         }
      }
   }
}
// 숫자만 입력 받도록 제한 '+'->,000 
    function Keycode(e,val){
        var code = (window.event) ? event.keyCode : e.which; //IE : FF - Chrome both
		if (code == 43) val.value=val.value+"000";
        if (code > 32 && code < 48) nAllow(e);
        if (code > 57 && code < 65) nAllow(e);
        if (code > 90 && code < 127) nAllow(e);
    }
    function nAllow(e){
        if(navigator.appName!="Netscape"){ //for not returning keycode value
            event.returnValue = false;  //IE ,  - Chrome both
        }else{
            e.preventDefault(); //FF ,  - Chrome both
        }        
    }