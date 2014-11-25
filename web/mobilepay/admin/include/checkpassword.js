function checkPWD( me ){
	if (me == "") {
		_( "chkpswd" ).className = "PSW_P_0";
		_("chkpswdcnt").innerHTML = "";
	} else if (me.length < 6) {
		_( "chkpswd" ).className = "PSW_P_1";
		_("chkpswdcnt").innerHTML = "太短";
	} else if( ! isPassword( me ) || !/^[^%&]*$/.test( me )) {
		_( "chkpswd" ).className = "PSW_P_0";
		_("chkpswdcnt").innerHTML = "";
	}
	else {
		var csint = checkStrong(me);
		switch (csint) {
		case 1:
			_("chkpswdcnt").innerHTML = "很弱";
			_( "chkpswd" ).className = "PSW_P_"+(csint + 1);
			break;
		case 2:
			_("chkpswdcnt").innerHTML = "一般";
			_( "chkpswd" ).className = "PSW_P_"+(csint + 1);
			break;
		case 3:		
			_("chkpswdcnt").innerHTML = "很强";
			_( "chkpswd" ).className = "PSW_P_"+(csint + 1);
			break;
		}
	}
}
function isPassword( str ){
	if (str.length < 6 || str.length > 16) return false;
	var len;
	var i;
	len = 0;
	for (i=0;i<str.length;i++){
		if (str.charCodeAt(i)>255) return false;
	}
	return true;
}

function _(id) {
	return document.getElementById(id);
}

function checkPWDlong ( me ){
	if(me.length<10 || me.length>20){
		alert("密码长度10～20位，由英文字母a～z(区分大小写)，数字0～9，特殊字符组成");
		me = "";
	}
}


function CharMode(iN){ 
	if (iN>=48 && iN <=57) //数字 
	return 1; 
	if (iN>=65 && iN <=90) //大写字母 
	return 2; 
	if (iN>=97 && iN <=122) //小写 
	return 4; 
	else 
	return 8; //特殊字符 
} 

//bitTotal函数 
//计算出当前密码当中一共有多少种模式 
function bitTotal(num){ 
	modes=0; 
	for (i=0;i<4;i++){ 
		if (num & 1) modes++; 
		num>>>=1; 
	} 
	return modes; 
} 

//checkStrong函数 
//返回密码的强度级别 
function checkStrong(sPW){ 
	Modes=0; 
	for (i=0;i<sPW.length;i++){ 
		//测试每一个字符的类别并统计一共有多少种模式. 
		Modes|=CharMode(sPW.charCodeAt(i)); 
	} 
	return bitTotal(Modes);
}
