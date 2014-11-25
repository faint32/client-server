var Browser = new Object();
Browser.isIE = window.ActiveXObject ? true : false;
Browser.isFirefox = (navigator.userAgent.toLowerCase().indexOf("firefox")!=-1);
Browser.isMozilla = (typeof(document.implementation) != 'undefined') && (typeof(document.implementation.createDocument) != 'undefined') && (typeof(HTMLDocument)!='undefined');
Browser.isOpera = (navigator.userAgent.toLowerCase().indexOf("opera")!=-1);

if (Browser.isMozilla || Browser.isFirefox) { 
	


	HTMLElement.prototype.removeNode = function() {
		this.parentNode.removeChild(this);
	};


	HTMLElement.prototype.insertAdjacentElement = function (sWhere, sElement) {
		switch (String(sWhere).toLowerCase()) {
			case "beforebegin":
				this.parentNode.insertBefore(sElement, this);
				break;

			case "afterbegin":
				this.insertBefore(sElement, this.childNodes[0]);
				break;

			case "beforeend":
				this.appendChild(sElement);
				break;

			case "afterend":
				this.parentNode.insertBefore(sElement, this.nextSibling);
				break;
		}
	};

}


/*=====================================================*/

function $(id){
	return document.getElementById(id);
}

function isValid(obj){
	
	if (obj==null || typeof(obj)=="undefined" ){
		return false;
	}else{
		return true;
	}
}


function startsWith(str, start, iCase,iShift) {
	if(isValid(iCase) && iCase ) {
		str = str.toUpperCase();
		start = start.toUpperCase();
	}
	if (isValid(iShift) && iShift){
		str=ignoreSHIFT(str);
		start=ignoreSHIFT(start);
	}
	return str.indexOf(start) == 0;
	
}

function ignoreSHIFT(str){
	var mapping={
		"!" : "1",
		"@" : "2",
		"#" : "3",
		"$" : "4",
		"%" : "5",
		"^" : "6",
		"&" : "7",
		"*" : "8",
		"(" : "9",
		")" : "0",
		"~" : "`",
		"_" : "-",
		"+" : "=",
		"{" : "[",
		"}" : "]",
		":" : ";",
		">" : ".",
		"<" : ",",
		"?" : "/",
		"\"" : "'",
		"|" : "\\"
	};
	var newStr="";
	/* 考虑用正则表达式 */
	for (var i=0;i<str.length ;i++ ){
		var c=str.charAt(i);
		for (var k in mapping){
			if (c==k){
				c=mapping[k];
				break;
			}
		}
		newStr+=c;
	}	
	return newStr;
}



function getPosLeft(elm) {
	var left = elm.offsetLeft;
	while((elm = elm.offsetParent) != null)	{
		left += elm.offsetLeft;
	}
	return left;
}


function getPosTop(elm) {
	var top = elm.offsetTop;
	while((elm = elm.offsetParent) != null)	{
		top += elm.offsetTop;
	}
	return top;
}

function getFirstChildElement(obj){
	return getChildElement(obj,0);
}

function getChildElement(obj,idx){
	if (Browser.isIE){
		return obj.childNodes[idx];
	}
	return getChildElements(obj)[idx];
}

function getChildElements(obj) {
	if (Browser.isIE){
		return obj.childNodes;
	}
		var tmp = [];
		var j = 0;
		var n;
		for (var i = 0; i < obj.childNodes.length; i++) {
			n = obj.childNodes[i];
			if (n.nodeType == 1) {
				tmp[j++] = n;
			}
		}
		return tmp;
}

function accesskey()
{
document.getElementById('btper').accessKey="←";
document.getElementById('btfirst').accessKey="↑";
document.getElementById('btnext').accessKey="→";
document.getElementById('btlast').accessKey="↓";
}
