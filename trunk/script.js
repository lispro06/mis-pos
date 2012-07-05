	/* <![CDATA[ */
function CheckCont(){//입력 확인 후, 저장 함수 호출
	var sc=document.getElementById("slit_code");
	var ic=document.getElementById("insu_code");
	var rc=document.getElementById("rmst_code");
	var rd=document.getElementById("rmdy_doct");
	var cs=document.getElementById("customer");
	if(sc.value=="" || ic.value=="" || rc.value=="" || rd.value=="" || cs.value==""){
		alert("입력하지 않은 항목이 있습니다.");
	}else{
		xajax_savePay(xajax.$("no").value,xajax.$("sale_code").value, xajax.$("slit_code").value, xajax.$("insu_code").value, xajax.$("rmst_code").value, xajax.$("rmdy_doct").value, xajax.$("customer").value, xajax.$("cash").value, xajax.$("cash_r").value, xajax.$("card").value, xajax.$("nopay").value, xajax.$("sum").value, xajax.$("etc").value,xajax.$("date_input").value)	}
}
		/* ]]> */

function pp(){
	var objDiv = document.getElementById("content");//scroll을 최 하단으로 이동시킨다.
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
function ttop(){
	window.location.href='#';
}
function sFocus(){
	var objDiv = document.getElementById("slit_code");
	var objDiv2 = document.getElementById("sum");
	objDiv.focus();
	objDiv2.innerHTML="";
}
function bottom(){
	var objDiv = document.getElementById("content");//scroll을 최 하단으로 이동시킨다.
	objDiv.scrollTop = objDiv.scrollHeight;
}
function comma(number){
	var slit_code=document.getElementById("slit_code").value;
	number=number+'';//number to string
	number=number.replace(/-/g,"");

	var nl=number.length;
	var no='';
	if(nl>6){
		no=number.substr(0,nl-6)+","+number.substr(nl-6,3)+","+number.substr(nl-3,3);
	}else if(nl>3){
		no=number.substr(0,nl-3)+","+number.substr(nl-3,3);
	}else if(nl){
		no=number;
	}
	if(no!=''){
		if(slit_code=="2003" || slit_code=="03. 환불")
			no='-' + no;
	}
	return no;
}
function SumAmount(num){//합계 출력 함수
	var slit_code=document.getElementById("slit_code").value;

	var cash=document.getElementById("cash").value;
	var cash_r=document.getElementById("cash_r").value;
	var card=document.getElementById("card").value;
	var nopay=document.getElementById("nopay").value;
	var summ=document.getElementById("sum");

	var nv=num.value;
	var cn=nv.replace(/,/g,"");
	num.value=comma(cn);//금액에 comma 추가

	cash=cash.replace(/,/g,"");
	cash_r=cash_r.replace(/,/g,"");
	nopay=nopay.replace(/,/g,"");
	card=card.replace(/,/g,"");
	var sums = Number(cash)+Number(cash_r)+Number(nopay)+Number(card);
    summ.innerHTML=comma(sums);//금액에 comma 추가
}
function CheckEnter(val) {//enter key 입력시 다음 입력 상자로 이동
	doc = document.getElementById("fr");
	if (event.keyCode == 13) {
		if(val.name=="slit_code" || val.name=="insu_code" || val.name=="rmst_code" || val.name=="rmdy_doct" || val.name=="customer"){
			if(val.value==""){
				alert("내용을 입력 하세요");
			}else{
				for(var i = 0; i< doc.elements.length-1; i++) {
					if (doc.elements[i].name == val.name) {
						for(var j = i; j< doc.elements.length-1; j++) {
							doc.elements[j+1].focus();
							return;
						}
					}
				}
			}
		}else{
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