
function confirm_entry()
{
	input_box=confirm("저장되었습니다. 일일마감요청을 하시겠습니까?");
	if (input_box==true){ 
	// Output when OK is clicked
		xajax_endBt(xajax.$('date_input').value);
	}

	else{
	// Output when Cancel is clicked
	}

}

function b_input(num,seq,skincos){
	var nv=num.value;
	var cn=nv.replace(/,/g,"");
	var varFore=skincos+"_fore_"+seq;
	var varIn=skincos+"_in_"+seq;
	var varOut=skincos+"_out_"+seq;
	var varSum=skincos+"_sum_"+seq;
	num.value=comma(cn);//금액에 comma 추가
	fv=xajax.$(varFore).value;//전일 잔액
	iv=document.getElementById(varIn).value;//입금액
	ov=document.getElementById(varOut).value;//출금액
	f=fv.replace(/,/g,"");
	i=iv.replace(/,/g,"");
	o=ov.replace(/,/g,"");
	f=ret_zero(f);
	i=ret_zero(i);
	o=ret_zero(o);
	res=parseInt(f)+parseInt(i)-parseInt(o);//수입-지출
	if(res>0)
		document.getElementById(varSum).value=comma(res);
	else
		document.getElementById(varSum).value=comma2(res);
	cal_sum();
}
function ret_zero(val){
	if(val)
		return val;
	else
		return 0;
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
function comma2(number){
	number=number+'';//number to string
	var nl=number.length;
	var no=1;
	if(nl>7){
		no=number.substr(0,nl-6)+","+number.substr(nl-6,3)+","+number.substr(nl-3,3);
	}else if(nl>4){
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
			if (doc.elements[i].name == val.name){
				for(var j = i; j< doc.elements.length-1; j++) {
					vn=doc.elements[j+1].name;
					vns=vn.split("_");
					if ("in" == vns[1] || "out" == vns[1]) {
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