function checkPWD( me ){
	if (me == "") {
		_( "chkpswd" ).className = "PSW_P_0";
		_("chkpswdcnt").innerHTML = "";
	} else if (me.length < 6) {
		_( "chkpswd" ).className = "PSW_P_1";
		_("chkpswdcnt").innerHTML = "̫��";
	} else if( ! isPassword( me ) || !/^[^%&]*$/.test( me )) {
		_( "chkpswd" ).className = "PSW_P_0";
		_("chkpswdcnt").innerHTML = "";
	}
	else {
		var csint = checkStrong(me);
		switch (csint) {
		case 1:
			_("chkpswdcnt").innerHTML = "����";
			_( "chkpswd" ).className = "PSW_P_"+(csint + 1);
			break;
		case 2:
			_("chkpswdcnt").innerHTML = "һ��";
			_( "chkpswd" ).className = "PSW_P_"+(csint + 1);
			break;
		case 3:		
			_("chkpswdcnt").innerHTML = "��ǿ";
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
		alert("���볤��10��20λ����Ӣ����ĸa��z(���ִ�Сд)������0��9�������ַ����");
		me = "";
	}
}


function CharMode(iN){ 
	if (iN>=48 && iN <=57) //���� 
	return 1; 
	if (iN>=65 && iN <=90) //��д��ĸ 
	return 2; 
	if (iN>=97 && iN <=122) //Сд 
	return 4; 
	else 
	return 8; //�����ַ� 
} 

//bitTotal���� 
//�������ǰ���뵱��һ���ж�����ģʽ 
function bitTotal(num){ 
	modes=0; 
	for (i=0;i<4;i++){ 
		if (num & 1) modes++; 
		num>>>=1; 
	} 
	return modes; 
} 

//checkStrong���� 
//���������ǿ�ȼ��� 
function checkStrong(sPW){ 
	Modes=0; 
	for (i=0;i<sPW.length;i++){ 
		//����ÿһ���ַ������ͳ��һ���ж�����ģʽ. 
		Modes|=CharMode(sPW.charCodeAt(i)); 
	} 
	return bitTotal(Modes);
}
