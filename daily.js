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
var d_afdy_mony;//변수 공유 불가로 전역변수 사용
function SumAmount(sum,num){//합계 출력 함수
	var nv=num.value;
	var cn=nv.replace(/,/g,"");
	num.value=comma(cn);//금액에 comma 추가
	dv=document.getElementById("d_doct_mony").value;
	cv=document.getElementById("c_doct_mony").value;
	av=document.getElementById("c_afdy_mony").value;
	d=dv.replace(/,/g,"");
	c=cv.replace(/,/g,"");
	a=av.replace(/,/g,"");
	document.getElementById("doct_mony").innerHTML=comma(parseInt(d)+parseInt(c));
	document.getElementById("d_afdy_mony").value=comma(sum-cn);//전체합계-인출금
	document.getElementById("afdy_mony").innerHTML=comma(sum-cn+parseInt(a));//차일 이월 합계
	d_afdy_mony=sum-cn;//아래 SA2에서 사용됨.
}
function SumAmount2(sum,num){//합계 출력 함수
	var nv=num.value;
	var cn=nv.replace(/,/g,"");
	num.value=comma(cn);//금액에 comma 추가
	dv=document.getElementById("d_doct_mony").value;
	cv=document.getElementById("c_doct_mony").value;
	d=dv.replace(/,/g,"");
	c=cv.replace(/,/g,"");
	document.getElementById("doct_mony").innerHTML=comma(parseInt(d)+parseInt(c));
	document.getElementById("c_afdy_mony").value=comma(sum-cn);//전체합계-인출금
	document.getElementById("afdy_mony").innerHTML=comma(d_afdy_mony+sum-cn);//차일 이월 합계
}
function CheckEnter(val) {//enter key 입력시 다음 입력 상자로 이동
      doc = document.getElementById("fr");
   if (event.keyCode == 13) {
      for(var i = 0; i< doc.elements.length-1; i++) {
         if (doc.elements[i].name == val.name) {
            for(var j = i; j< doc.elements.length-1; j++) {
                  doc.elements[j+1].focus();
				  if(doc.elements[j+1].type == "select-one"){
					  doc.elements[j+1].size = doc.elements[j+1].options.length;
				   }
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