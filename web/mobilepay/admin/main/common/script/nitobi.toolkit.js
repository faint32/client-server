if(typeof (nitobi)=="undefined"){
nitobi=function(){
};
}
if(false){
nitobi.lang=function(){
};
}
if(typeof (nitobi.lang)=="undefined"){
nitobi.lang={};
}
nitobi.lang.defineNs=function(_1){
var _2=_1.split(".");
var _3="";
var _4="";
for(var i=0;i<_2.length;i++){
_3+=_4+_2[i];
_4=".";
if(eval("typeof("+_3+")")=="undefined"){
eval(_3+"={}");
}
}
};
nitobi.lang.extend=function(_6,_7){
function inheritance(){
}
inheritance.prototype=_7.prototype;
_6.prototype=new inheritance();
_6.prototype.constructor=_6;
_6.baseConstructor=_7;
if(_7.base){
_7.prototype.base=_7.base;
}
_6.base=_7.prototype;
};
nitobi.lang.implement=function(_8,_9){
if(typeof (_9)=="undefined"||_9==null){
var _a="nitobi.lang.implement argument interface_ is null or undefined.  The most likely cause of this is that a js file has not been included, or has been included in the wrong order.";
nitobi.lang.throwError(_a);
}
for(var _b in _9.prototype){
if(typeof (_8.prototype[_b])=="undefined"||_8.prototype[_b]==null){
_8.prototype[_b]=_9.prototype[_b];
}
}
};
nitobi.lang.isDefined=function(a){
return (typeof (a)!="undefined");
};
nitobi.lang.getBool=function(a){
if(null==a){
return null;
}
if(typeof (a)=="boolean"){
return a;
}
return a.toLowerCase()=="true";
};
nitobi.lang.type={XMLNODE:0,HTMLNODE:1,ARRAY:2,XMLDOC:3};
nitobi.lang.typeOf=function(_e){
var t=typeof (_e);
if(t=="object"){
if(_e.blur){
return nitobi.lang.type.HTMLNODE;
}
if(_e.nodeName&&_e.nodeName.toLowerCase()==="#document"){
return nitobi.lang.type.XMLDOC;
}
if(_e.nodeName){
return nitobi.lang.type.XMLNODE;
}
if(_e instanceof Array){
return nitobi.lang.type.ARRAY;
}
}
return t;
};
nitobi.lang.toBool=function(_10,_11){
if(typeof (_11)!="undefined"){
if((typeof (_10)=="undefined")||(_10=="")||(_10==null)){
_10=_11;
}
}
_10=_10.toString()||"";
_10=_10.toUpperCase();
if((_10=="Y")||(_10=="1")||(_10=="TRUE")){
return true;
}else{
return false;
}
};
nitobi.lang.boolToStr=function(_12){
if(typeof (_12)=="boolean"){
if(_12){
return "1";
}else{
return "0";
}
}else{
return _12;
}
};
nitobi.lang.close=function(_13,_14,_15){
if(null==_15){
return function(){
return _14.apply(_13,arguments);
};
}else{
return function(){
return _14.apply(_13,_15);
};
}
};
nitobi.lang.forEach=function(arr,_17){
var len=arr.length;
for(var i=0;i<len;i++){
_17.call(this,arr[i],i);
}
_17=null;
};
nitobi.lang.throwError=function(_1a,_1b){
var msg=_1a;
if(_1b!=null){
msg+="\n - because "+nitobi.lang.getErrorDescription(_1b);
}
throw msg;
};
nitobi.lang.getErrorDescription=function(_1d){
var _1e=(typeof (_1d.description)=="undefined")?_1d:_1d.description;
return _1e;
};
nitobi.lang.newObject=function(_1f,_20,_21){
var a=_20;
if(null==_21){
_21=0;
}
var e="new "+_1f+"(";
var _24="";
for(var i=_21;i<a.length;i++){
e+=_24+"a["+i+"]";
_24=",";
}
e+=")";
return eval(e);
};
nitobi.lang.getLastFunctionArgs=function(_26,_27){
var a=new Array(_26.length-_27);
for(var i=_27;i<_26.length;i++){
a[i-_27]=_26[i];
}
return a;
};
nitobi.lang.getFirstHashKey=function(_2a){
for(var x in _2a){
return x;
}
};
nitobi.lang.getFirstFunction=function(obj){
for(var x in obj){
if(obj[x]!=null&&typeof (obj[x])=="function"&&typeof (obj[x].prototype)!="undefined"){
return {name:x,value:obj[x]};
}
}
return null;
};
nitobi.lang.dispose=function(_2e,_2f){
try{
var _30=_2f.length;
for(var i=0;i<_30;i++){
if(_2f[i].dispose){
_2f[i].dispose();
}
if(typeof (_2f[i])=="function"){
_2f[i].call(_2e);
}
_2f[i]=null;
}
}
catch(e){
}
};
nitobi.lang.parseNumber=function(val){
var num=parseInt(val);
return (isNaN(num)?0:num);
};
nitobi.lang.numToAlpha=function(num){
if(typeof (nitobi.lang.numAlphaCache[num])==="string"){
return nitobi.lang.numAlphaCache[num];
}
var ck1=num%26;
var ck2=Math.floor(num/26);
var _37=(ck2>0?String.fromCharCode(96+ck2):"")+String.fromCharCode(97+ck1);
nitobi.lang.alphaNumCache[_37]=num;
nitobi.lang.numAlphaCache[num]=_37;
return _37;
};
nitobi.lang.alphaToNum=function(_38){
if(typeof (nitobi.lang.alphaNumCache[_38])==="number"){
return nitobi.lang.alphaNumCache[_38];
}
var j=0;
var num=0;
for(var i=_38.length-1;i>=0;i--){
num+=(_38.charCodeAt(i)-96)*Math.pow(26,j++);
}
num=num-1;
nitobi.lang.alphaNumCache[_38]=num;
nitobi.lang.numAlphaCache[num]=_38;
return num;
};
nitobi.lang.alphaNumCache={};
nitobi.lang.numAlphaCache={};
nitobi.lang.toArray=function(obj,_3d){
return Array.prototype.splice.call(obj,_3d||0);
};
nitobi.lang.merge=function(_3e,_3f){
var r={};
for(var i=0;i<arguments.length;i++){
var a=arguments[i];
for(var x in arguments[i]){
r[x]=a[x];
}
}
return r;
};
nitobi.lang.noop=function(){
};
nitobi.lang.defineNs("nitobi");
nitobi.Object=function(){
this.disposal=new Array();
};
nitobi.Object.prototype.setValues=function(_44){
for(var _45 in _44){
if(this[_45]!=null){
if(this[_45].subscribe!=null){
}else{
this[_45]=_44[_45];
}
}else{
if(this[_45] instanceof Function){
this[_45](_44[_45]);
}else{
if(this["set"+_45] instanceof Function){
this["set"+_45](_44[_45]);
}else{
this[_45]=_44[_45];
}
}
}
}
};
nitobi.Object.prototype.dispose=function(){
if(this.disposing){
return;
}
this.disposing=true;
var _46=this.disposal.length;
for(var i=0;i<_46;i++){
if(disposal[i] instanceof Function){
disposal[i].call(context);
}
disposal[i]=null;
}
for(var _48 in this){
if(this[_48].dispose instanceof Function){
this[_48].dispose.call(this[_48]);
}
this[_48]=null;
}
};
if(false){
nitobi.base=function(){
};
}
nitobi.lang.defineNs("nitobi.base");
nitobi.base.uid=1;
nitobi.base.getUid=function(){
return "ntb__"+(nitobi.base.uid++);
};
nitobi.lang.defineNs("nitobi.browser");
if(false){
nitobi.browser=function(){
};
}
nitobi.browser.UNKNOWN=true;
nitobi.browser.IE=false;
nitobi.browser.IE6=false;
nitobi.browser.IE7=false;
nitobi.browser.MOZ=false;
nitobi.browser.SAFARI=false;
nitobi.browser.OPERA=false;
nitobi.browser.XHR_ENABLED;
nitobi.browser.detect=function(){
var _49=[{string:navigator.vendor,subString:"Apple",identity:"Safari"},{prop:window.opera,identity:"Opera"},{string:navigator.vendor,subString:"iCab",identity:"iCab"},{string:navigator.vendor,subString:"KDE",identity:"Konqueror"},{string:navigator.userAgent,subString:"Firefox",identity:"Firefox"},{string:navigator.userAgent,subString:"Netscape",identity:"Netscape"},{string:navigator.userAgent,subString:"MSIE",identity:"Explorer",versionSearch:"MSIE"},{string:navigator.userAgent,subString:"Gecko",identity:"Mozilla",versionSearch:"rv"},{string:navigator.userAgent,subString:"Mozilla",identity:"Netscape",versionSearch:"Mozilla"}];
var _4a="Unknown";
for(var i=0;i<_49.length;i++){
var _4c=_49[i].string;
var _4d=_49[i].prop;
if(_4c){
if(_4c.indexOf(_49[i].subString)!=-1){
_4a=_49[i].identity;
break;
}
}else{
if(_4d){
_4a=_49[i].identity;
break;
}
}
}
nitobi.browser.IE=(_4a=="Explorer");
nitobi.browser.IE6=(nitobi.browser.IE&&!window.XMLHttpRequest);
nitobi.browser.IE7=(nitobi.browser.IE&&window.XMLHttpRequest);
nitobi.browser.MOZ=(_4a=="Netscape"||_4a=="Firefox");
nitobi.browser.SAFARI=(_4a=="Safari");
nitobi.browser.OPERA=(_4a=="Opera");
nitobi.browser.XHR_ENABLED=nitobi.browser.OPERA||nitobi.browser.SAFARI||nitobi.browser.MOZ||nitobi.browser.IE;
nitobi.browser.UNKNOWN=!(nitobi.browser.IE||nitobi.browser.MOZ||nitobi.browser.SAFARI);
};
nitobi.browser.detect();
if(nitobi.browser.IE6){
try{
document.execCommand("BackgroundImageCache",false,true);
}
catch(e){
}
}
nitobi.lang.defineNs("nitobi.browser");
nitobi.browser.Cookies=function(){
};
nitobi.lang.extend(nitobi.browser.Cookies,nitobi.Object);
nitobi.browser.Cookies.get=function(id){
var _4f,end;
if(document.cookie.length>0){
_4f=document.cookie.indexOf(id+"=");
if(_4f!=-1){
_4f+=id.length+1;
end=document.cookie.indexOf(";",_4f);
if(end==-1){
end=document.cookie.length;
}
return unescape(document.cookie.substring(_4f,end));
}
}
return null;
};
nitobi.browser.Cookies.set=function(id,_52,_53){
var _54=new Date();
_54.setTime(_54.getTime()+(_53*24*3600*1000));
document.cookie=id+"="+escape(_52)+((_53==null)?"":"; expires="+_54.toGMTString());
};
nitobi.browser.Cookies.remove=function(id){
if(nitobi.browser.Cookies.get(id)){
document.cookie=id+"="+"; expires=Thu, 01-Jan-70 00:00:01 GMT";
}
};
nitobi.lang.defineNs("nitobi.xml");
nitobi.xml=function(){
};
nitobi.xml.nsPrefix="ntb:";
nitobi.xml.nsDecl="xmlns:ntb=\"http://www.nitobi.com\"";
if(nitobi.browser.IE){
var inUse=false;
nitobi.xml.XslTemplate=new ActiveXObject("MSXML2.XSLTemplate.3.0");
}
if(nitobi.browser.MOZ){
nitobi.xml.Serializer=new XMLSerializer();
nitobi.xml.DOMParser=new DOMParser();
}
if(!nitobi.browser.IE&&!nitobi.browser.MOZ){
alert("EBA XML library is not supported in your browser");
}
nitobi.xml.getChildNodes=function(_56){
if(nitobi.browser.IE){
return _56.childNodes;
}else{
return _56.selectNodes("./*");
}
};
nitobi.xml.indexOfChildNode=function(_57,_58){
var _59=nitobi.xml.getChildNodes(_57);
for(var i=0;i<_59.length;i++){
if(_59[i]==_58){
return i;
}
}
return -1;
};
nitobi.xml.createXmlDoc=function(str){
var doc=null;
if(nitobi.browser.IE){
doc=new ActiveXObject("Msxml2.DOMDocument.3.0");
doc.setProperty("SelectionNamespaces","xmlns:ntb='http://www.nitobi.com'");
}else{
if(nitobi.browser.MOZ){
doc=document.implementation.createDocument("","",null);
}
}
if(str!=null){
doc=nitobi.xml.loadXml(doc,str);
}
return doc;
};
nitobi.xml.loadXml=function(doc,xml,_5f){
doc.async=false;
if(nitobi.browser.IE){
doc.loadXML(xml);
}else{
var _60=nitobi.xml.DOMParser.parseFromString(xml,"text/xml");
if(_5f){
while(doc.hasChildNodes()){
doc.removeChild(doc.firstChild);
}
for(var i=0;i<_60.childNodes.length;i++){
doc.appendChild(doc.importNode(_60.childNodes[i],true));
}
}else{
doc=_60;
}
_60=null;
}
return doc;
};
nitobi.xml.hasParseError=function(_62){
if(nitobi.browser.IE){
return (_62.parseError!=0);
}else{
if(_62.documentElement==null){
return true;
}
var _63=_62.documentElement;
if((_63.tagName=="parserError")||(_63.namespaceURI=="http://www.mozilla.org/newlayout/xml/parsererror.xml")){
return true;
}
return false;
}
};
nitobi.xml.getParseErrorReason=function(_64){
if(!nitobi.xml.hasParseError(_64)){
return "";
}
if(nitobi.browser.IE){
return (_64.parseError.reason);
}else{
return (new XMLSerializer().serializeToString(_64));
}
};
nitobi.xml.createXslDoc=function(xsl){
var doc=null;
if(nitobi.browser.IE){
doc=new ActiveXObject("MSXML2.FreeThreadedDOMDocument.3.0");
}else{
if(nitobi.browser.MOZ){
doc=nitobi.xml.createXmlDoc();
}
}
doc=nitobi.xml.loadXml(doc,xsl||"<xsl:stylesheet version=\"1.0\" xmlns:xsl=\"http://www.w3.org/1999/XSL/Transform\" xmlns:ntb=\"http://www.nitobi.com\" />");
return doc;
};
nitobi.xml.createXslProcessor=function(xsl){
var _68=null;
var xt=null;
if(typeof (xsl)!="string"){
xsl=nitobi.xml.serialize(xsl);
}
if(nitobi.browser.IE){
_68=new ActiveXObject("MSXML2.FreeThreadedDOMDocument.3.0");
xt=new ActiveXObject("MSXML2.XSLTemplate.3.0");
_68.async=false;
_68.loadXML(xsl);
xt.stylesheet=_68;
return xt.createProcessor();
}else{
_68=nitobi.xml.createXmlDoc(xsl);
xt=new XSLTProcessor();
xt.importStylesheet(_68);
xt.stylesheet=_68;
return xt;
}
};
nitobi.xml.parseHtml=function(_6a){
if(typeof (_6a)=="string"){
_6a=document.getElementById(_6a);
}
var _6b=nitobi.html.getOuterHtml(_6a);
var _6c="";
if(nitobi.browser.IE){
var _6d=new RegExp("(\\s+.[^=]*)='(.*?)'","g");
_6b=_6b.replace(_6d,function(m,_1,_2){
return _1+"=\""+_2.replace(/"/g,"&quot;")+"\"";
});
_6c=(_6b.substring(_6b.indexOf("/>")+2).replace(/(\s+.[^\=]*)\=\s*([^\"^\s^\>]+)/g,"$1=\"$2\" ")).replace(/\n/gi,"").replace(/(.*?:.*?\s)/i,"$1  ");
var _71=new RegExp("=\"([^\"]*)(<)(.*?)\"","gi");
var _72=new RegExp("=\"([^\"]*)(>)(.*?)\"","gi");
while(true){
_6c=_6c.replace(_71,"=\"$1&lt;$3\" ");
_6c=_6c.replace(_72,"=\"$1&gt;$3\" ");
var x=(_71.test(_6c));
if(!_71.test(_6c)){
break;
}
}
}else{
if(nitobi.browser.MOZ){
_6c=_6b.replace(/(\s+.[^\=]*)\=\s*([^\"^\s^\>]+)/g,"$1=\"$2\" ").replace(/\n/gi,"").replace(/\>\s*\</gi,"><").replace(/(.*?:.*?\s)/i,"$1  ");
_6c=_6c.replace(/\&/g,"&amp;");
_6c=_6c.replace(/\&amp;gt;/g,"&gt;").replace(/\&amp;lt;/g,"&lt;").replace(/\&amp;apos;/g,"&apos;").replace(/\&amp;quot;/g,"&quot;").replace(/\&amp;amp;/g,"&amp;");
}
}
if(_6c.indexOf("xmlns:ntb=\"http://www.nitobi.com\"")<1){
_6c=_6c.replace(/\<(.*?)(\s|\>|\\)/,"<$1 xmlns:ntb=\"http://www.nitobi.com\"$2");
}
_6c=_6c.replace(/\&nbsp\;/gi," ");
return nitobi.xml.createXmlDoc(_6c);
};
nitobi.xml.transform=function(xml,xsl,_76){
if(xsl.documentElement){
xsl=nitobi.xml.createXslProcessor(xsl);
}
if(nitobi.browser.IE){
xsl.input=xml;
xsl.transform();
return xsl.output;
}else{
var doc=xsl.transformToDocument(xml);
var _78=doc.documentElement;
if(_78&&_78.nodeName.indexOf("ntb:")==0){
_78.setAttributeNS("http://www.w3.org/2000/xmlns/","xmlns:ntb","http://www.nitobi.com");
}
return doc;
}
};
nitobi.xml.transformToString=function(xml,xsl,_7b){
var _7c=nitobi.xml.transform(xml,xsl,"text");
if(nitobi.browser.MOZ){
if(_7b=="xml"){
_7c=nitobi.xml.Serializer.serializeToString(_7c);
}else{
if(_7c.documentElement.childNodes[0]==null){
nitobi.lang.throwError("The transformToString fn could not find any valid output");
}
if(_7c.documentElement.childNodes[0].data!=null){
_7c=_7c.documentElement.childNodes[0].data;
}else{
if(_7c.documentElement.childNodes[0].textContent!=null){
_7c=_7c.documentElement.childNodes[0].textContent;
}else{
nitobi.lang.throwError("The transformToString fn could not find any valid output");
}
}
}
}
return _7c;
};
nitobi.xml.transformToXml=function(xml,xsl){
var _7f=nitobi.xml.transform(xml,xsl,"xml");
if(nitobi.browser.IE){
_7f=nitobi.xml.createXmlDoc(_7f);
}else{
if(nitobi.browser.MOZ){
if(_7f.documentElement.nodeName=="transformiix:result"){
_7f=nitobi.xml.createXmlDoc(_7f.documentElement.firstChild.data);
}
}
}
return _7f;
};
nitobi.xml.serialize=function(xml){
if(nitobi.browser.IE){
return xml.xml;
}else{
return (new XMLSerializer()).serializeToString(xml);
}
};
nitobi.xml.createXmlHttp=function(){
if(nitobi.browser.IE){
var _81=null;
try{
_81=new ActiveXObject("Msxml2.XMLHTTP");
}
catch(e){
try{
_81=new ActiveXObject("Microsoft.XMLHTTP");
}
catch(ee){
}
}
return _81;
}else{
if(nitobi.browser.MOZ){
return new XMLHttpRequest();
}
}
};
function nitobiXmlDecodeXslt(xsl){
return xsl.replace(/x:c-/g,"xsl:choose").replace(/x\:wh\-/g,"xsl:when").replace(/x\:o\-/g,"xsl:otherwise").replace(/x\:n\-/g," name=\"").replace(/x\:s\-/g," select=\"").replace(/x\:va\-/g,"xsl:variable").replace(/x\:v\-/g,"xsl:value-of").replace(/x\:ct\-/g,"xsl:call-template").replace(/x\:w\-/g,"xsl:with-param").replace(/x\:p\-/g,"xsl:param").replace(/x\:t\-/g,"xsl:template").replace(/x\:at\-/g,"xsl:apply-templates").replace(/x\:a\-/g,"xsl:attribute");
}
if(nitobi.browser.MOZ){
Document.prototype.__defineGetter__("xml",function(){
return (new XMLSerializer()).serializeToString(this);
});
Node.prototype.__defineGetter__("xml",function(){
return (new XMLSerializer()).serializeToString(this);
});
XPathResult.prototype.__defineGetter__("length",function(){
return this.snapshotLength;
});
XSLTProcessor.prototype.addParameter=function(_83,_84,_85){
if(_84==null){
this.removeParameter(_85,_83);
}else{
this.setParameter(_85,_83,_84);
}
};
XMLDocument.prototype.selectNodes=function(_86,_87){
try{
var _88=this.evaluate(_86,(_87?_87:this),this.createNSResolver(this.documentElement),XPathResult.ORDERED_NODE_SNAPSHOT_TYPE,null);
var _89=new Array(_88.snapshotLength);
_89.expr=_86;
var j=0;
for(i=0;i<_88.snapshotLength;i++){
var _8b=_88.snapshotItem(i);
if(_8b.nodeType!=3){
_89[j++]=_8b;
}
}
return _89;
}
catch(e){
}
};
XMLDocument.prototype.selectSingleNode=function(_8c,_8d){
var ctx=_8d?_8d:null;
var _8f=_8c.match(/\[\d+\]/ig);
if(_8f!=null){
var x=_8f[_8f.length-1];
if(_8c.lastIndexOf(x)+x.length!=_8c.length){
_8c+="[1]";
}
}
var _91=this.selectNodes(_8c,ctx);
if(_91!=null&&_91.length>0){
return _91[0];
}else{
return null;
}
};
Element.prototype.selectNodes=function(_92){
var doc=this.ownerDocument;
return doc.selectNodes(_92,this);
};
Element.prototype.selectSingleNode=function(_94){
var doc=this.ownerDocument;
return doc.selectSingleNode(_94,this);
};
}
nitobi.xml.getLocalName=function(_96){
var _97=_96.indexOf(":");
if(_97==-1){
return _96;
}else{
return _96.substr(_97+1);
}
};
nitobi.xml.encode=function(str){
str+="";
str=str.replace(/&/g,"&amp;");
str=str.replace(/'/g,"&apos;");
str=str.replace(/\"/g,"&quot;");
str=str.replace(/</g,"&lt;");
str=str.replace(/>/g,"&gt;");
str=str.replace(/\n/g,"&#xa;");
return str;
};
nitobi.xml.constructValidXpathQuery=function(_99,_9a){
var _9b=_99.match(/(\"|\')/g);
if(_9b!=null){
var _9c="concat(";
var _9d="";
var _9e;
for(var i=0;i<_99.length;i++){
if(_99.substr(i,1)=="\""){
_9e="&apos;";
}else{
_9e="&quot;";
}
_9c+=_9d+_9e+nitobi.xml.encode(_99.substr(i,1))+_9e;
_9d=",";
}
_9c+=_9d+"&apos;&apos;";
_9c+=")";
_99=_9c;
}else{
var _a0=(_9a?"\"":"");
_99=_a0+nitobi.xml.encode(_99)+_a0;
}
return _99;
};
nitobi.lang.defineNs("nitobi.html");
nitobi.html.Url=function(){
};
nitobi.html.Url.setParameter=function(url,key,_a3){
var reg=new RegExp("(\\?|&)("+encodeURIComponent(key)+")=(.*?)(&|$)");
if(url.match(reg)){
return url.replace(reg,"$1$2="+encodeURIComponent(_a3)+"$4");
}
if(url.match(/\?/)){
url=url+"&";
}else{
url=url+"?";
}
return url+encodeURIComponent(key)+"="+encodeURIComponent(_a3);
};
nitobi.html.Url.removeParameter=function(url,key){
var reg=new RegExp("(\\?|&)("+encodeURIComponent(key)+")=(.*?)(&|$)");
return url.replace(reg,function(str,p1,p2,p3,p4,_ad,s){
if(((p1)=="?")&&(p4!="&")){
return "";
}else{
return p1;
}
});
};
nitobi.html.Url.normalize=function(url,_b0){
if(_b0){
if(_b0.indexOf("http://")==0||_b0.indexOf("/")==0){
return _b0;
}
}
var _b1=(url.match(/.*\//)||"")+"";
var lc=_b1.substr(_b1.length-1,1);
if(_b0){
return _b1+_b0;
}
return _b1;
};
nitobi.html.Url.randomize=function(url){
return nitobi.html.Url.setParameter(url,"ntb-random",(new Date).getTime());
};
nitobi.lang.defineNs("nitobi.html");
nitobi.html.Css=function(){
};
nitobi.html.Css.swapClass=function(_b4,_b5,_b6){
if(_b4.className){
var reg=new RegExp("(\\s|^)"+_b5+"(\\s|$)");
_b4.className=_b4.className.replace(reg,"$1"+_b6+"$2");
}
};
nitobi.html.Css.replaceOrAppend=function(_b8,_b9,_ba){
if(nitobi.html.Css.hasClass(_b8,_b9)){
nitobi.html.Css.swapClass(_b8,_b9,_ba);
}else{
nitobi.html.Css.addClass(_b8,_ba);
}
};
nitobi.html.Css.hasClass=function(_bb,_bc){
if(!_bc||_bc===""){
return false;
}
return (new RegExp("(\\s|^)"+_bc+"(\\s|$)")).test(_bb.className);
};
nitobi.html.Css.addClass=function(_bd,_be){
if(!nitobi.html.Css.hasClass(_bd,_be)){
_bd.className=_bd.className?_bd.className+" "+_be:_be;
}
};
nitobi.html.Css.removeClass=function(_bf,_c0){
if(nitobi.html.Css.hasClass(_bf,_c0)){
var reg=new RegExp("(\\s|^)"+_c0+"(\\s|$)");
_bf.className=_bf.className.replace(reg,"$2");
}
};
nitobi.html.Css.getRules=function(_c2){
var _c3=null;
if(typeof (_c2)=="number"){
_c3=document.styleSheets[_c2];
}else{
_c3=_c2;
}
if(_c3==null){
return null;
}
try{
if(_c3.cssRules){
return _c3.cssRules;
}
if(_c3.rules){
return _c3.rules;
}
}
catch(e){
}
return null;
};
nitobi.html.Css.getStyleSheetsByName=function(_c4){
var arr=new Array();
var ss=document.styleSheets;
var _c7=new RegExp(_c4.replace(".",".")+"($|\\?)");
for(var i=0;i<ss.length;i++){
arr=nitobi.html.Css._getStyleSheetsByName(_c7,ss[i],arr);
}
return arr;
};
nitobi.html.Css._getStyleSheetsByName=function(_c9,_ca,arr){
if(_c9.test(_ca.href)){
arr=arr.concat([_ca]);
}
var _cc=nitobi.html.Css.getRules(_ca);
if(_ca.imports){
for(var i=0;i<_ca.imports.length;i++){
arr=nitobi.html.Css._getStyleSheetsByName(_c9,_ca.imports[i],arr);
}
}else{
for(var i=0;i<_cc.length;i++){
var s=_cc[i].styleSheet;
if(s){
arr=nitobi.html.Css._getStyleSheetsByName(_c9,s,arr);
}
}
}
return arr;
};
nitobi.html.Css.imageCache={};
nitobi.html.Css.precacheImages=function(_cf){
if(!_cf){
var ss=document.styleSheets;
for(var i=0;i<ss.length;i++){
nitobi.html.Css.precacheImages(ss[i]);
}
return;
}
var _d2=/.*?url\((.*?)\).*?/;
rules=nitobi.html.Css.getRules(_cf);
var url=nitobi.html.Css.getPath(_cf);
for(var i=0;i<rules.length;i++){
var _d4=rules[i];
if(_d4.styleSheet){
nitobi.html.Css.precacheImages(_d4.styleSheet);
}else{
var s=_d4.style;
var _d6=s?s.backgroundImage:null;
if(_d6){
_d6=_d6.replace(_d2,"$1");
_d6=nitobi.html.Url.normalize(url,_d6);
if(!nitobi.html.Css.imageCache[_d6]){
var _d7=new Image();
_d7.src=_d6;
nitobi.html.Css.imageCache[_d6]=_d7;
}
}
}
}
if(_cf.imports){
for(var i=0;i<_cf.imports.length;i++){
nitobi.html.Css.precacheImages(_cf.imports[i]);
}
}
};
nitobi.html.Css.getPath=function(_d8){
var _d9=_d8.href;
_d9=nitobi.html.Url.normalize(_d9);
if(_d8.parentStyleSheet&&_d9.indexOf("/")!=0&&_d9.indexOf("http://")!=0){
_d9=nitobi.html.Css.getPath(_d8.parentStyleSheet)+_d9;
}
return _d9;
};
nitobi.html.Css.getSheetUrl=nitobi.html.Css.getPath;
nitobi.html.Css.findParentStylesheet=function(_da){
var _db=nitobi.html.Css.getRule(_da);
if(_db){
return _db.parentStyleSheet;
}
return null;
};
nitobi.html.Css.findInSheet=function(_dc,_dd,_de){
if(nitobi.browser.IE6&&typeof _de=="undefined"){
_de=0;
}else{
if(_de>4){
return null;
}
}
_de++;
var _df=nitobi.html.Css.getRules(_dd);
for(var _e0=0;_e0<_df.length;_e0++){
var _e1=_df[_e0];
if(_e1.styleSheet){
var _e2=nitobi.html.Css.findInSheet(_dc,_e1.styleSheet,_de);
if(_e2){
return _e2;
}
}else{
if(_e1.selectorText!=null&&_e1.selectorText.toLowerCase().indexOf(_dc)>-1){
if(nitobi.browser.IE){
_e1={selectorText:_e1.selectorText,style:_e1.style,readOnly:_e1.readOnly,parentStyleSheet:_dd};
}
return _e1;
}
}
}
if(_dd.href!=""&&_dd.imports){
for(var i=0;i<_dd.imports.length;i++){
var _e2=nitobi.html.Css.findInSheet(_dc,_dd.imports[i],_de);
if(_e2){
return _e2;
}
}
}
return null;
};
nitobi.html.Css.getClass=function(_e4){
_e4=_e4.toLowerCase();
if(_e4.indexOf(".")!==0){
_e4="."+_e4;
}
var _e5=nitobi.html.Css.getRule(_e4);
if(_e5!=null){
return _e5.style;
}
return null;
};
nitobi.html.Css.getStyleBySelector=function(_e6){
var _e7=nitobi.html.Css.getRule(_e6);
if(_e7!=null){
return _e7.style;
}
return null;
};
nitobi.html.Css.getRule=function(_e8){
_e8=_e8.toLowerCase();
if(_e8.indexOf(".")!==0){
_e8="."+_e8;
}
var _e9=document.styleSheets;
for(var ss=0;ss<_e9.length;ss++){
try{
var _eb=nitobi.html.Css.findInSheet(_e8,_e9[ss]);
if(_eb){
return _eb;
}
}
catch(err){
}
}
return null;
};
nitobi.html.Css.getClassStyle=function(_ec,_ed){
var _ee=nitobi.html.Css.getClass(_ec);
if(_ee!=null){
return _ee[_ed];
}else{
return null;
}
};
nitobi.html.Css.setStyle=function(el,_f0,_f1){
_f0=_f0.replace(/\-(\w)/g,function(_f2,p1){
return p1.toUpperCase();
});
el.style[_f0]=_f1;
};
nitobi.html.Css.getStyle=function(_f4,_f5){
var _f6="";
if(document.defaultView&&document.defaultView.getComputedStyle){
_f5=_f5.replace(/([A-Z])/g,function($1){
return "-"+$1.toLowerCase();
});
_f6=document.defaultView.getComputedStyle(_f4,"").getPropertyValue(_f5);
}else{
if(_f4.currentStyle){
_f5=_f5.replace(/\-(\w)/g,function(_f8,p1){
return p1.toUpperCase();
});
_f6=_f4.currentStyle[_f5];
}
}
return _f6;
};
nitobi.html.Css.setOpacities=function(_fa,_fb){
if(_fa.length){
for(var i=0;i<_fa.length;i++){
nitobi.html.Css.setOpacity(_fa[i],_fb);
}
}else{
nitobi.html.Css.setOpacity(_fa,_fb);
}
};
nitobi.html.Css.setOpacity=function(_fd,_fe){
var s=_fd.style;
if(_fe>100){
_fe=100;
}
if(_fe<0){
_fe=0;
}
if(s.filter!=null){
var _100=s.filter.match(/alpha\(opacity=[\d\.]*?\)/ig);
if(_100!=null&&_100.length>0){
s.filter=s.filter.replace(/alpha\(opacity=[\d\.]*?\)/ig,"alpha(opacity="+_fe+")");
}else{
s.filter+="alpha(opacity="+_fe+")";
}
}else{
s.opacity=(_fe/100);
}
};
nitobi.html.Css.getOpacity=function(_101){
if(_101==null){
nitobi.lang.throwError(nitobi.error.ArgExpected+" for nitobi.html.Css.getOpacity");
}
if(nitobi.browser.IE){
if(_101.style.filter==""){
return 100;
}
var s=_101.style.filter;
s.match(/opacity=([\d\.]*?)\)/ig);
if(RegExp.$1==""){
return 100;
}
return parseInt(RegExp.$1);
}else{
return Math.abs(_101.style.opacity?_101.style.opacity*100:100);
}
};
nitobi.html.Css.getCustomStyle=function(_103,_104){
if(nitobi.browser.IE){
return nitobi.html.getClassStyle(_103,_104);
}else{
var rule=nitobi.html.Css.getRule(_103);
var re=new RegExp("(.*?)({)(.*?)(})","gi");
var _107=rule.cssText.match(re);
re=new RegExp("("+_104+")(:)(.*?)(;)","gi");
_107=re.exec(RegExp.$3);
}
};
if(nitobi.browser.MOZ){
Document.prototype.createStyleSheet=function(){
var _108=this.createElement("style");
this.documentElement.childNodes[0].appendChild(_108);
return _108;
};
HTMLStyleElement.prototype.__defineSetter__("cssText",function(_109){
this.innerHTML=_109;
});
HTMLStyleElement.prototype.__defineGetter__("cssText",function(){
return this.innerHTML;
});
}
nitobi.lang.defineNs("nitobi.drawing");
nitobi.drawing.Point=function(x,y){
this.x=x;
this.y=y;
};
nitobi.drawing.Point.prototype.toString=function(){
return "("+this.x+","+this.y+")";
};
nitobi.drawing.rgb=function(r,g,b){
return "#"+((r*65536)+(g*256)+b).toString(16);
};
nitobi.drawing.align=function(_10f,_110,_111,oh,ow,oy,ox){
oh=oh||0;
ow=ow||0;
oy=oy||0;
ox=ox||0;
var a=_111;
var td,sd,tt,tb,tl,tr,th,tw,st,sb,sl,sr,sh,sw;
if(nitobi.browser.IE){
td=_110.getBoundingClientRect();
sd=_10f.getBoundingClientRect();
tt=td.top;
tb=td.bottom;
tl=td.left;
tr=td.right;
th=Math.abs(tb-tt);
tw=Math.abs(tr-tl);
st=sd.top;
sb=sd.bottom;
sl=sd.left;
sr=sd.right;
sh=Math.abs(sb-st);
sw=Math.abs(sr-sl);
}else{
if(nitobi.browser.MOZ){
td=document.getBoxObjectFor(_110);
sd=document.getBoxObjectFor(_10f);
tt=td.y;
tl=td.x;
tw=td.width;
th=td.height;
st=sd.y;
sl=sd.x;
sw=sd.width;
sh=sd.height;
}else{
td=nitobi.html.getCoords(_110);
sd=nitobi.html.getCoords(_10f);
tt=td.y;
tl=td.x;
tw=td.width;
th=td.height;
st=sd.y;
sl=sd.x;
sw=sd.width;
sh=sd.height;
}
}
var s=_10f.style;
if(a&268435456){
s.height=(th+oh)+"px";
}
if(a&16777216){
s.width=(tw+ow)+"px";
}
if(a&1048576){
s.top=(nitobi.html.getStyleTop(_10f)+tt-st+oy)+"px";
}
if(a&65536){
s.top=(nitobi.html.getStyleTop(_10f)+tt-st+th-sh+oy)+"px";
}
if(a&4096){
s.left=(nitobi.html.getStyleLeft(_10f)-sl+tl+ox)+"px";
}
if(a&256){
s.left=(nitobi.html.getStyleLeft(_10f)-sl+tl+tw-sw+ox)+"px";
}
if(a&16){
s.top=(nitobi.html.getStyleTop(_10f)+tt-st+oy+Math.floor((th-sh)/2))+"px";
}
if(a&1){
s.left=(nitobi.html.getStyleLeft(_10f)-sl+tl+ox+Math.floor((tw-sw)/2))+"px";
}
};
nitobi.drawing.align.SAMEHEIGHT=268435456;
nitobi.drawing.align.SAMEWIDTH=16777216;
nitobi.drawing.align.ALIGNTOP=1048576;
nitobi.drawing.align.ALIGNBOTTOM=65536;
nitobi.drawing.align.ALIGNLEFT=4096;
nitobi.drawing.align.ALIGNRIGHT=256;
nitobi.drawing.align.ALIGNMIDDLEVERT=16;
nitobi.drawing.align.ALIGNMIDDLEHORIZ=1;
nitobi.drawing.alignOuterBox=function(_126,_127,_128,oh,ow,oy,ox,show){
oh=oh||0;
ow=ow||0;
oy=oy||0;
ox=ox||0;
if(nitobi.browser.moz){
td=document.getBoxObjectFor(_127);
sd=document.getBoxObjectFor(_126);
var _12e=parseInt(document.defaultView.getComputedStyle(_127,"").getPropertyValue("border-left-width"));
var _12f=parseInt(document.defaultView.getComputedStyle(_127,"").getPropertyValue("border-top-width"));
var _130=parseInt(document.defaultView.getComputedStyle(_126,"").getPropertyValue("border-top-width"));
var _131=parseInt(document.defaultView.getComputedStyle(_126,"").getPropertyValue("border-bottom-width"));
var _132=parseInt(document.defaultView.getComputedStyle(_126,"").getPropertyValue("border-left-width"));
var _133=parseInt(document.defaultView.getComputedStyle(_126,"").getPropertyValue("border-right-width"));
oy=oy+_130-_12f;
ox=ox+_132-_12e;
}
nitobi.drawing.align(_126,_127,_128,oh,ow,oy,ox,show);
};
nitobi.lang.defineNs("nitobi.html");
if(false){
nitobi.html=function(){
};
}
nitobi.html.getDomNodeByPath=function(Node,Path){
if(nitobi.browser.IE){
}
var _136=Node;
var _137=Path.split("/");
var len=_137.length;
for(var i=0;i<len;i++){
if(_136.childNodes[Number(_137[i])]!=null){
_136=_136.childNodes[Number(_137[i])];
}else{
alert("Path expression failed."+Path);
}
var s="";
}
return _136;
};
nitobi.html.indexOfChildNode=function(_13b,_13c){
var _13d=_13b.childNodes;
for(var i=0;i<_13d.length;i++){
if(_13d[i]==_13c){
return i;
}
}
return -1;
};
nitobi.html.evalScriptBlocks=function(node){
for(var i=0;i<node.childNodes.length;i++){
var _141=node.childNodes[i];
if(_141.nodeName.toLowerCase()=="script"){
eval(_141.text);
}else{
nitobi.html.evalScriptBlocks(_141);
}
}
};
nitobi.html.position=function(node){
var pos=nitobi.html.getStyle($(node),"position");
if(pos=="static"){
node.style.position="relative";
}
};
nitobi.html.setOpacity=function(_144,_145){
var _146=_144.style;
_146.opacity=(_145/100);
_146.MozOpacity=(_145/100);
_146.KhtmlOpacity=(_145/100);
_146.filter="alpha(opacity="+_145+")";
};
nitobi.html.highlight=function(o,x){
if(o.createTextRange){
o.focus();
var r=document.selection.createRange().duplicate();
r.move("character",0-o.value.length);
r.move("character",x);
r.moveEnd("textedit",1);
r.select();
}else{
if(o.setSelectionRange){
o.setSelectionRange(x,o.value.length);
}
}
};
nitobi.html.setCursor=function(o,x){
if(o.createTextRange){
o.focus();
var r=document.selection.createRange().duplicate();
r.move("character",0-o.value.length);
r.move("character",x);
r.select();
}else{
if(o.setSelectionRange){
o.setSelectionRange(x,x);
}
}
};
nitobi.html.encode=function(str){
str+="";
str=str.replace(/&/g,"&amp;");
str=str.replace(/\"/g,"&quot;");
str=str.replace(/</g,"&lt;");
str=str.replace(/>/g,"&gt;");
str=str.replace(/\n/g,"<br>");
return str;
};
nitobi.html.getElement=function(_14e){
if(typeof (_14e)=="string"){
return document.getElementById(_14e);
}
return _14e;
};
if(typeof ($)=="undefined"){
$=nitobi.html.getElement;
}
if(typeof ($F)=="undefined"){
$F=function(id){
var _150=$(id);
if(_150!=null){
return _150.value;
}
return "";
};
}
nitobi.html.getTagName=function(elem){
if(nitobi.browser.IE&&elem.scopeName!=""){
return (elem.scopeName+":"+elem.nodeName).toLowerCase();
}else{
return elem.nodeName.toLowerCase();
}
};
nitobi.html.getStyleTop=function(elem){
return nitobi.lang.parseNumber(elem.style.top);
};
nitobi.html.getStyleLeft=function(elem){
return nitobi.lang.parseNumber(elem.style.left);
};
nitobi.html.getHeight=function(elem){
return elem.offsetHeight;
};
nitobi.html.getWidth=function(elem){
return elem.offsetWidth;
};
if(nitobi.browser.IE){
nitobi.html.getBox=function(elem){
var _157=nitobi.lang.parseNumber(nitobi.html.getStyle(document.body,"border-top-width"));
var _158=nitobi.lang.parseNumber(nitobi.html.getStyle(document.body,"border-left-width"));
var _159=nitobi.lang.parseNumber(document.body.scrollTop)-(_157==0?2:_157);
var _15a=nitobi.lang.parseNumber(document.body.scrollLeft)-(_158==0?2:_158);
var rect=elem.getBoundingClientRect();
return {top:rect.top+_159,left:rect.left+_15a,bottom:rect.bottom,right:rect.right,height:rect.bottom-rect.top,width:rect.right-rect.left};
};
}else{
nitobi.html.getBox=function(elem){
var _15d=0;
var _15e=0;
var _15f=elem.parentNode;
while(_15f.nodeType==1&&_15f!=document.body){
_15d+=nitobi.lang.parseNumber(_15f.scrollTop)-(nitobi.html.getStyle(_15f,"overflow")=="auto"?nitobi.lang.parseNumber(nitobi.html.getStyle(_15f,"border-top-width")):0);
_15e+=nitobi.lang.parseNumber(_15f.scrollLeft)-(nitobi.html.getStyle(_15f,"overflow")=="auto"?nitobi.lang.parseNumber(nitobi.html.getStyle(_15f,"border-left-width")):0);
_15f=_15f.parentNode;
}
var _160=elem.ownerDocument.getBoxObjectFor(elem);
var _161=nitobi.lang.parseNumber(nitobi.html.getStyle(elem,"border-left-width"));
var _162=nitobi.lang.parseNumber(nitobi.html.getStyle(elem,"border-right-width"));
var _163=nitobi.lang.parseNumber(nitobi.html.getStyle(elem,"border-top-width"));
var top=nitobi.lang.parseNumber(_160.y)-_15d-_163;
var left=nitobi.lang.parseNumber(_160.x)-_15e-_161;
var _166=left+nitobi.lang.parseNumber(_160.width);
var _167=top+_160.height;
var _168=nitobi.lang.parseNumber(_160.height);
var _169=nitobi.lang.parseNumber(_160.width);
return {top:top,left:left,bottom:_167,right:_166,height:_168,width:_169};
};
nitobi.html.getBox.cache={};
}
nitobi.html.getBox2=nitobi.html.getBox;
nitobi.html.getUniqueId=function(elem){
if(elem.uniqueID){
return elem.uniqueID;
}else{
var t=(new Date()).getTime();
elem.uniqueID=t;
return t;
}
};
nitobi.html.getChildNodeById=function(elem,_16d,_16e){
return nitobi.html.getChildNodeByAttribute(elem,"id",_16d,_16e);
};
nitobi.html.getChildNodeByAttribute=function(elem,_170,_171,_172){
for(var i=0;i<elem.childNodes.length;i++){
if(elem.nodeType!=3&&Boolean(elem.childNodes[i].getAttribute)){
if(elem.childNodes[i].getAttribute(_170)==_171){
return elem.childNodes[i];
}
}
}
if(_172){
for(var i=0;i<elem.childNodes.length;i++){
var _174=nitobi.html.getChildNodeByAttribute(elem.childNodes[i],_170,_171,_172);
if(_174!=null){
return _174;
}
}
}
return null;
};
nitobi.html.getParentNodeById=function(elem,_176){
return nitobi.html.getParentNodeByAtt(elem,"id",_176);
};
nitobi.html.getParentNodeByAtt=function(elem,att,_179){
while(elem.parentNode!=null){
if(elem.parentNode.getAttribute(att)==_179){
return elem.parentNode;
}
elem=elem.parentNode;
}
return null;
};
if(nitobi.browser.IE){
nitobi.html.getFirstChild=function(node){
return node.firstChild;
};
}else{
if(nitobi.browser.MOZ){
nitobi.html.getFirstChild=function(node){
var i=0;
while(i<node.childNodes.length&&node.childNodes[i].nodeType==3){
i++;
}
return node.childNodes[i];
};
}
}
nitobi.html.getScroll=function(){
var _17d,_17e=0;
if((nitobi.browser.OPERA==false)&&(document.documentElement.scrollTop>0)){
_17d=document.documentElement.scrollTop;
_17e=document.documentElement.scrollLeft;
}else{
_17d=document.body.scrollTop;
_17e=document.body.scrollLeft;
}
if(((_17d==0)&&(document.documentElement.scrollTop>0))||((_17e==0)&&(document.documentElement.scrollLeft>0))){
_17d=document.documentElement.scrollTop;
_17e=document.documentElement.scrollLeft;
}
return {"left":_17e,"top":_17d};
};
nitobi.html.getCoords=function(_17f){
var ew,eh;
try{
var _182=_17f;
ew=_17f.offsetWidth;
eh=_17f.offsetHeight;
for(var lx=0,ly=0;_17f!=null;lx+=_17f.offsetLeft,ly+=_17f.offsetTop,_17f=_17f.offsetParent){
}
for(;_182!=document.body;lx-=_182.scrollLeft,ly-=_182.scrollTop,_182=_182.parentNode){
}
}
catch(e){
}
return {"x":lx,"y":ly,"height":eh,"width":ew};
};
nitobi.html.align=nitobi.drawing.align;
nitobi.html.emptyElements={HR:true,BR:true,IMG:true,INPUT:true};
nitobi.html.specialElements={TEXTAREA:true};
nitobi.html.getOuterHtml=function(node){
if(nitobi.browser.IE){
return node.outerHTML;
}else{
var html="";
switch(node.nodeType){
case Node.ELEMENT_NODE:
html+="<";
html+=node.nodeName.toLowerCase();
if(!nitobi.html.specialElements[node.nodeName]){
for(var a=0;a<node.attributes.length;a++){
if(node.attributes[a].nodeName.toLowerCase()!="_moz-userdefined"){
html+=" "+node.attributes[a].nodeName.toLowerCase()+"=\""+node.attributes[a].nodeValue+"\"";
}
}
html+=">";
if(!nitobi.html.emptyElements[node.nodeName]){
html+=node.innerHTML;
html+="</"+node.nodeName.toLowerCase()+">";
}
}else{
switch(node.nodeName){
case "TEXTAREA":
for(var a=0;a<node.attributes.length;a++){
if(node.attributes[a].nodeName.toLowerCase()!="value"){
html+=" "+node.attributes[a].nodeName.toUpperCase()+"=\""+node.attributes[a].nodeValue+"\"";
}else{
var _188=node.attributes[a].nodeValue;
}
}
html+=">";
html+=_188;
html+="</"+node.nodeName+">";
break;
}
}
break;
case Node.TEXT_NODE:
html+=node.nodeValue;
break;
case Node.COMMENT_NODE:
html+="<!"+"--"+node.nodeValue+"--"+">";
break;
}
return html;
}
};
try{
Node.prototype.swapNode=function(node){
var _18a=this.nextSibling;
var _18b=this.parentNode;
node.parentNode.replaceChild(this,node);
_18b.insertBefore(node,_18a);
};
HTMLElement.prototype.getBoundingClientRect=function(_18c,_18d){
_18c=_18c||0;
_18d=_18d||0;
var td=document.getBoxObjectFor(this);
return {top:td.y-_18c,left:td.x-_18d,bottom:(td.y+td.height),right:(td.x+td.width)};
};
HTMLElement.prototype.getClientRects=function(_18f,_190){
_18f=_18f||0;
_190=_190||0;
var td=document.getBoxObjectFor(this);
return new Array({top:(td.y-_18f),left:(td.x-_190),bottom:(td.y+td.height-_18f),right:(td.x+td.width-_190)});
};
HTMLElement.prototype.insertAdjacentElement=function(pos,node){
switch(pos){
case "beforeBegin":
this.parentNode.insertBefore(node,this);
break;
case "afterBegin":
this.insertBefore(node,this.firstChild);
break;
case "beforeEnd":
this.appendChild(node);
break;
case "afterEnd":
if(this.nextSibling){
this.parentNode.insertBefore(node,this.nextSibling);
}else{
this.parentNode.appendChild(node);
}
break;
}
};
HTMLElement.prototype.insertAdjacentHTML=function(_194,_195,_196){
var df;
var r=this.ownerDocument.createRange();
switch(String(_194).toLowerCase()){
case "beforebegin":
r.setStartBefore(this);
df=r.createContextualFragment(_195);
this.parentNode.insertBefore(df,this);
break;
case "afterbegin":
r.selectNodeContents(this);
r.collapse(true);
df=r.createContextualFragment(_195);
this.insertBefore(df,this.firstChild);
break;
case "beforeend":
if(_196==true){
this.innerHTML=this.innerHTML+_195;
}else{
r.selectNodeContents(this);
r.collapse(false);
df=r.createContextualFragment(_195);
this.appendChild(df);
}
break;
case "afterend":
r.setStartAfter(this);
df=r.createContextualFragment(_195);
this.parentNode.insertBefore(df,this.nextSibling);
break;
}
};
HTMLElement.prototype.insertAdjacentText=function(pos,s){
var node=document.createTextNode(s);
this.insertAdjacentElement(pos,node);
};
}
catch(e){
}
nitobi.html.Event=function(){
};
nitobi.html.handlerId=0;
nitobi.html.elementId=0;
nitobi.html.elements=[];
nitobi.html.unload=[];
nitobi.html.unloadCalled=false;
nitobi.html.attachEvents=function(_19c,_19d,_19e){
var _19f=[];
for(var i=0;i<_19d.length;i++){
var e=_19d[i];
_19f.push(nitobi.html.attachEvent(_19c,e.type,e.handler,_19e,e.capture||false));
}
return _19f;
};
nitobi.html.attachEvent=function(_1a2,type,_1a4,_1a5,_1a6,_1a7){
if(!(_1a4 instanceof Function)){
nitobi.lang.throwError("Event handler needs to be a Function");
}
_1a2=$(_1a2);
if(type.toLowerCase()=="unload"&&_1a7!=true){
var _1a8=_1a4;
if(_1a5!=null){
_1a8=function(){
_1a4.call(_1a5);
};
}
return this.addUnload(_1a8);
}
var _1a9=this.handlerId++;
var _1aa=this.elementId++;
if(typeof (_1a4.ebaguid)!="undefined"){
_1a9=_1a4.ebaguid;
}else{
_1a4.ebaguid=_1a9;
}
if(typeof (_1a2.ebaguid)=="undefined"){
_1a2.ebaguid=_1aa;
nitobi.html.elements[_1aa]=_1a2;
}
if(typeof (_1a2.eba_events)=="undefined"){
_1a2.eba_events={};
}
if(_1a2.eba_events[type]==null){
_1a2.eba_events[type]={};
if(_1a2.attachEvent){
_1a2["eba_event_"+type]=function(){
nitobi.html.notify.call(_1a2,window.event);
};
_1a2.attachEvent("on"+type,_1a2["eba_event_"+type]);
if(_1a6){
_1a2.setCapture(true);
}
}else{
if(_1a2.addEventListener){
_1a2["eba_event_"+type]=function(){
nitobi.html.notify.call(_1a2,arguments[0]);
};
_1a2.addEventListener(type,_1a2["eba_event_"+type],_1a6);
}
}
}
_1a2.eba_events[type][_1a9]={handler:_1a4,context:_1a5};
return _1a9;
};
nitobi.html.notify=function(e){
if(!nitobi.browser.IE){
e.srcElement=e.target;
e.fromElement=e.relatedTarget;
e.toElement=e.relatedTarget;
}
e.eventSrc=_1ac;
nitobi.html.Event=e;
var _1ac=this;
for(var _1ad in _1ac.eba_events[e.type]){
var _1ae=_1ac.eba_events[e.type][_1ad];
if(typeof (_1ae.context)=="object"){
_1ae.handler.call(_1ae.context,e,_1ac);
}else{
_1ae.handler.call(_1ac,e,_1ac);
}
}
};
nitobi.html.detachEvents=function(_1af,_1b0){
for(var i=0;i<_1b0.length;i++){
var e=_1b0[i];
nitobi.html.detachEvent(_1af,e.type,e.handler);
}
};
nitobi.html.detachEvent=function(_1b3,type,_1b5){
_1b3=$(_1b3);
var _1b6=_1b5;
if(_1b5 instanceof Function){
_1b6=_1b5.ebaguid;
}
if(type=="unload"){
this.unload.splice(ebaguid,1);
}
if(_1b3.eba_events!=null&&_1b3.eba_events[type]!=null&&_1b3.eba_events[type][_1b6]!=null){
var _1b7=_1b3.eba_events[type];
_1b7[_1b6]=null;
delete _1b7[_1b6];
if(nitobi.collections.isHashEmpty(_1b7)){
this.m_detach(_1b3,type,_1b3["eba_event_"+type]);
_1b3["eba_event_"+type]=null;
_1b3.eba_events[type]=null;
_1b7=null;
if(_1b3.nodeType==1){
_1b3.removeAttribute("eba_event_"+type);
}
}
}
return true;
};
nitobi.html.m_detach=function(_1b8,type,_1ba){
if(_1ba!=null&&_1ba instanceof Function){
if(_1b8.detachEvent){
_1b8.detachEvent("on"+type,_1ba);
}else{
if(_1b8.removeEventListener){
_1b8.removeEventListener(type,_1ba,false);
}
}
_1b8["on"+type]=null;
if(type=="unload"){
for(var i=0;i<this.unload.length;i++){
this.unload[i].call(this);
this.unload[i]=null;
}
}
}
};
nitobi.html.detachAllEvents=function(){
for(var i=0;i<nitobi.html.elements.length;i++){
if(typeof (nitobi.html.elements[i])!="undefined"){
for(var _1bd in nitobi.html.elements[i].eba_events){
nitobi.html.m_detach(nitobi.html.elements[i],_1bd,nitobi.html.elements[i]["eba_event_"+_1bd]);
if(typeof (nitobi.html.elements[i])!="undefined"&&nitobi.html.elements[i].eba_events[_1bd]!=null){
for(var j=0;j<nitobi.html.elements[i].eba_events[_1bd].length;j++){
nitobi.html.elements[i].eba_events[_1bd][j]=null;
}
}
nitobi.html.elements[i]["eba_event_"+_1bd]=null;
}
}
}
nitobi.html.elements=null;
};
nitobi.html.addUnload=function(_1bf){
this.unload.push(_1bf);
return this.unload.length-1;
};
nitobi.html.cancelEvent=function(evt,v){
if(evt==null){
return;
}
if(nitobi.browser.MOZ){
evt.preventDefault();
evt.stopPropagation();
}else{
if(nitobi.browser.IE){
evt.cancelBubble=true;
evt.returnValue=false;
}
}
if(v!=null){
e.keyCode=v;
}
};
nitobi.html.getEventCoords=function(evt){
var _1c3={"x":evt.clientX,"y":evt.clientY};
if(nitobi.browser.IE){
_1c3.x+=document.documentElement.scrollLeft+document.body.scrollLeft;
_1c3.y+=document.documentElement.scrollTop+document.body.scrollTop;
}else{
_1c3.x+=window.scrollX;
_1c3.y+=window.scrollY;
}
return _1c3;
};
nitobi.html.getEvent=function(_1c4){
if(nitobi.browser.IE){
return window.event;
}else{
_1c4.srcElement=_1c4.target;
_1c4.fromElement=_1c4.relatedTarget;
_1c4.toElement=_1c4.relatedTarget;
return _1c4;
}
};
nitobi.html.createEvent=function(_1c5,_1c6,_1c7,_1c8){
if(nitobi.browser.IE){
_1c7.target.fireEvent("on"+_1c6);
}else{
var _1c9=document.createEvent(_1c5);
_1c9.initKeyEvent(_1c6,true,true,document.defaultView,_1c7.ctrlKey,_1c7.altKey,_1c7.shiftKey,_1c7.metaKey,_1c8.keyCode,_1c8.charCode);
_1c7.target.dispatchEvent(_1c9);
}
};
nitobi.html.unloadEventId=nitobi.html.attachEvent(window,"unload",nitobi.html.detachAllEvents,nitobi.html,false,true);
nitobi.lang.defineNs("nitobi.event");
nitobi.event=function(){
};
nitobi.event.keys={};
nitobi.event.guid=0;
nitobi.event.subscribe=function(key,_1cb){
nitobi.event.publish(key);
var guid=this.guid++;
this.keys[key].add(_1cb,guid);
return guid;
};
nitobi.event.unsubscribe=function(key,guid){
if(this.keys[key]==null){
return true;
}
this.keys[key].remove(guid);
};
nitobi.event.evaluate=function(func,_1d0){
var _1d1=true;
if(typeof func=="string"){
func=func.replace(/eventArgs/gi,"arguments[1]");
var _1d2=eval(func);
_1d1=(typeof (_1d2)=="undefined"?true:_1d2);
}
return _1d1;
};
nitobi.event.publish=function(key){
if(this.keys[key]==null){
this.keys[key]=new nitobi.event.Key();
}
};
nitobi.event.notify=function(key,_1d5){
if(this.keys[key]!=null){
return this.keys[key].notify(_1d5);
}else{
return true;
}
};
nitobi.event.dispose=function(){
for(var key in this.keys){
if(typeof (this.keys[key])=="function"){
this.keys[key].dispose();
}
}
this.keys=null;
};
nitobi.event.Key=function(){
this.handlers={};
};
nitobi.event.Key.prototype.add=function(_1d7,guid){
this.handlers[guid]=_1d7;
};
nitobi.event.Key.prototype.remove=function(guid){
this.handlers[guid]=null;
delete this.handlers[guid];
};
nitobi.event.Key.prototype.notify=function(_1da){
var fail=false;
for(var item in this.handlers){
var _1dd=this.handlers[item];
if(_1dd instanceof Function){
var rv=(_1dd.apply(this,arguments)==false);
fail=fail||rv;
}else{
}
}
return !fail;
};
nitobi.event.Key.prototype.dispose=function(){
for(var _1df in this.handlers){
this.handlers[_1df]=null;
}
};
nitobi.event.Args=function(src){
this.source=src;
};
nitobi.event.Args.prototype.callback=function(){
};
nitobi.html.cancelBubble=nitobi.html.cancelEvent;
nitobi.html.getCssRules=nitobi.html.Css.getRules;
nitobi.html.findParentStylesheet=nitobi.html.Css.findParentStylesheet;
nitobi.html.getClass=nitobi.html.Css.getClass;
nitobi.html.getStyle=nitobi.html.Css.getStyle;
nitobi.html.addClass=nitobi.html.Css.addClass;
nitobi.html.removeClass=nitobi.html.Css.removeClass;
nitobi.html.getClassStyle=nitobi.html.Css.getClassStyle;
nitobi.html.normalizeUrl=nitobi.html.Url.normalize;
nitobi.html.setUrlParameter=nitobi.html.Url.setParameter;
nitobi.lang.defineNs("nitobi.base.XmlNamespace");
nitobi.base.XmlNamespace.prefix="ntb";
nitobi.base.XmlNamespace.uri="http://www.nitobi.com";
nitobi.lang.defineNs("nitobi.collections");
if(false){
nitobi.collections=function(){
};
}
nitobi.collections.IEnumerable=function(){
this.list=new Array();
this.length=0;
};
nitobi.collections.IEnumerable.prototype.add=function(obj){
this.list[this.getLength()]=obj;
this.length++;
};
nitobi.collections.IEnumerable.prototype.insert=function(_1e2,obj){
this.list.splice(_1e2,0,obj);
this.length++;
};
nitobi.collections.IEnumerable.createNewArray=function(obj,_1e5){
var _1e6;
_1e5=_1e5||0;
if(obj.count){
_1e6=obj.count;
}
if(obj.length){
_1e6=obj.length;
}
var x=new Array(_1e6-_1e5);
for(var i=_1e5;i<_1e6;i++){
x[i-_1e5]=obj[i];
}
return x;
};
nitobi.collections.IEnumerable.prototype.get=function(_1e9){
if(_1e9<0||_1e9>=this.getLength()){
nitobi.lang.throwError(nitobi.error.OutOfBounds);
}
return this.list[_1e9];
};
nitobi.collections.IEnumerable.prototype.set=function(_1ea,_1eb){
if(_1ea<0||_1ea>=this.getLength()){
nitobi.lang.throwError(nitobi.error.OutOfBounds);
}
this.list[_1ea]=_1eb;
};
nitobi.collections.IEnumerable.prototype.indexOf=function(obj){
for(var i=0;i<this.getLength();i++){
if(this.list[i]===obj){
return i;
}
}
return -1;
};
nitobi.collections.IEnumerable.prototype.remove=function(_1ee){
var i;
if(typeof (_1ee)!="number"){
i=this.indexOf(_1ee);
}else{
i=_1ee;
}
if(-1==i||i<0||i>=this.getLength()){
nitobi.lang.throwError(nitobi.error.OutOfBounds);
}
this.list[i]=null;
this.list.splice(i,1);
this.length--;
};
nitobi.collections.IEnumerable.prototype.getLength=function(){
return this.length;
};
nitobi.collections.IEnumerable.prototype.each=function(func){
var l=this.length;
var list=this.list;
for(var i=0;i<l;i++){
func(list[i]);
}
};
nitobi.lang.defineNs("nitobi.base");
nitobi.base.ISerializable=function(_1f4,id,xml,_1f7){
nitobi.Object.call(this);
if(typeof (this.ISerializableInitialized)=="undefined"){
this.ISerializableInitialized=true;
}else{
return;
}
this.xmlNode=null;
this.setXmlNode(_1f4);
if(_1f4!=null){
this.profile=nitobi.base.Registry.getInstance().getCompleteProfile({idField:null,tagName:_1f4.nodeName});
}else{
this.profile=nitobi.base.Registry.getInstance().getProfileByInstance(this);
}
this.onDeserialize=new nitobi.base.Event();
this.onSetParentObject=new nitobi.base.Event();
this.factory=nitobi.base.Factory.getInstance();
this.objectHash={};
this.onCreateObject=new nitobi.base.Event();
if(_1f4!=null){
this.deserializeFromXmlNode(this.getXmlNode());
}else{
if(this.factory!=null&&this.profile.tagName!=null){
this.createByProfile(this.profile,this.getXmlNode());
}else{
if(xml!=null&&_1f4!=null){
this.createByXml(xml);
}
}
}
this.disposal.push(this.xmlNode);
};
nitobi.lang.extend(nitobi.base.ISerializable,nitobi.Object);
nitobi.base.ISerializable.guidMap={};
nitobi.base.ISerializable.prototype.ISerializableImplemented=true;
nitobi.base.ISerializable.prototype.getProfile=function(){
return this.profile;
};
nitobi.base.ISerializable.prototype.createByProfile=function(_1f8,_1f9){
if(_1f9==null){
var xml="<"+_1f8.tagName+" xmlns:"+nitobi.base.XmlNamespace.prefix+"=\""+nitobi.base.XmlNamespace.uri+"\" />";
var _1fb=nitobi.xml.createXmlDoc(xml);
this.setXmlNode(_1fb.firstChild);
this.deserializeFromXmlNode(this.xmlNode);
}else{
this.deserializeFromXmlNode(_1f9);
this.setXmlNode(_1f9);
}
};
nitobi.base.ISerializable.prototype.createByXml=function(xml){
this.deserializeFromXml(xml);
};
nitobi.base.ISerializable.prototype.getParentObject=function(){
return this.parentObj;
};
nitobi.base.ISerializable.prototype.setParentObject=function(_1fd){
this.parentObj=_1fd;
this.onSetParentObject.notify();
};
nitobi.base.ISerializable.prototype.addChildObject=function(_1fe){
this.addToCache(_1fe);
_1fe.setParentObject(this);
var _1ff=_1fe.getXmlNode();
if(!this.areGuidsGenerated(_1ff)){
_1ff=this.generateGuids(_1ff);
_1fe.setXmlNode(_1ff);
}
this.xmlNode.appendChild(_1ff);
};
nitobi.base.ISerializable.prototype.insertBeforeChildObject=function(obj,_201){
_201=_201?_201.getXmlNode():null;
this.addToCache(obj);
obj.setParentObject(this);
var _202=obj.getXmlNode();
if(!this.areGuidsGenerated(_202)){
_202=this.generateGuids(_202);
obj.setXmlNode(_202);
}
this.xmlNode.insertBefore(_202,_201);
};
nitobi.base.ISerializable.prototype.createElement=function(name){
var _204;
if(this.xmlNode==null||this.xmlNode.ownerDocument==null){
_204=nitobi.xml.createXmlDoc();
}else{
_204=this.xmlNode.ownerDocument;
}
if(nitobi.browser.IE){
return _204.createNode(1,name,nitobi.base.XmlNamespace.uri);
}else{
if(_204.createElementNS){
return _204.createElementNS(nitobi.base.XmlNamespace.uri,name);
}else{
nitobi.lang.throwError("Unable to create a new xml node on this browser.");
}
}
};
nitobi.base.ISerializable.prototype.deleteChildObject=function(id){
this.removeFromCache(id);
var e=this.getElement(id);
if(e!=null){
e.parentNode.removeChild(e);
}
};
nitobi.base.ISerializable.prototype.addToCache=function(obj){
this.objectHash[obj.getId()]=obj;
};
nitobi.base.ISerializable.prototype.removeFromCache=function(id){
this.objectHash[id]=null;
};
nitobi.base.ISerializable.prototype.inCache=function(id){
return (this.objectHash[id]!=null);
};
nitobi.base.ISerializable.prototype.flushCache=function(){
this.objectHash={};
};
nitobi.base.ISerializable.prototype.areGuidsGenerated=function(_20a){
if(_20a==null||_20a.ownerDocument==null){
return false;
}
if(nitobi.browser.IE){
var node=_20a.ownerDocument.documentElement;
if(node==null){
return false;
}else{
var id=node.getAttribute("id");
if(id==null||id==""){
return false;
}else{
return (nitobi.base.ISerializable.guidMap[id]!=null);
}
}
}else{
return (_20a.ownerDocument.generatedGuids==true);
}
};
nitobi.base.ISerializable.prototype.setGuidsGenerated=function(_20d,_20e){
if(_20d==null||_20d.ownerDocument==null){
return;
}
if(nitobi.browser.IE){
var node=_20d.ownerDocument.documentElement;
if(node!=null){
var id=node.getAttribute("id");
if(id!=null&&id!=""){
nitobi.base.ISerializable.guidMap[id]=true;
}
}
}else{
_20d.ownerDocument.generatedGuids=true;
}
};
nitobi.base.ISerializable.prototype.generateGuids=function(_211){
nitobi.base.uniqueIdGeneratorProc.addParameter("guid",nitobi.component.getUniqueId(),"");
var doc=nitobi.xml.transformToXml(_211,nitobi.base.uniqueIdGeneratorProc);
this.saveDocument=doc;
this.setGuidsGenerated(doc.documentElement,true);
return doc.documentElement;
};
nitobi.base.ISerializable.prototype.deserializeFromXmlNode=function(_213){
if(!this.areGuidsGenerated(_213)){
_213=this.generateGuids(_213);
}
this.setXmlNode(_213);
this.flushCache();
if(this.profile==null){
this.profile=nitobi.base.Registry.getInstance().getCompleteProfile({idField:null,tagName:_213.nodeName});
}
this.onDeserialize.notify();
};
nitobi.base.ISerializable.prototype.deserializeFromXml=function(xml){
var doc=nitobi.xml.createXmlDoc(xml);
var node=this.generateGuids(doc.firstChild);
this.setXmlNode(node);
this.onDeserialize.notify();
};
nitobi.base.ISerializable.prototype.getChildObject=function(id){
var obj=null;
obj=this.objectHash[id];
if(obj==null){
var _219=this.getElement(id);
if(_219==null){
return null;
}else{
obj=this.factory.createByNode(_219);
this.onCreateObject.notify(obj);
this.addToCache(obj);
}
obj.setParentObject(this);
}
return obj;
};
nitobi.base.ISerializable.prototype.getChildObjectById=function(id){
return this.getChildObject(id);
};
nitobi.base.ISerializable.prototype.getElement=function(id){
try{
var node=this.xmlNode.selectSingleNode("*[@id='"+id+"']");
return node;
}
catch(err){
nitobi.lang.throwError(nitobi.error.Unexpected,err);
}
};
nitobi.base.ISerializable.prototype.getFactory=function(){
return this.factory;
};
nitobi.base.ISerializable.prototype.setFactory=function(_21d){
this.factory=factory;
};
nitobi.base.ISerializable.prototype.getXmlNode=function(){
return this.xmlNode;
};
nitobi.base.ISerializable.prototype.setXmlNode=function(_21e){
if(nitobi.lang.typeOf(_21e)==nitobi.lang.type.XMLDOC&&_21e!=null){
this.ownerDocument=_21e;
_21e=nitobi.html.getFirstChild(_21e);
}else{
if(_21e!=null){
this.ownerDocument=_21e.ownerDocument;
}
}
if(_21e!=null&&nitobi.browser.MOZ&&_21e.ownerDocument==null){
nitobi.lang.throwError(nitobi.error.OrphanXmlNode+" ISerializable.setXmlNode");
}
this.xmlNode=_21e;
};
nitobi.base.ISerializable.prototype.serializeToXml=function(){
return nitobi.xml.serialize(this.xmlNode);
};
nitobi.base.ISerializable.prototype.getAttribute=function(name,_220){
if(this[name]!=null){
return this[name];
}
var _221=this.xmlNode.getAttribute(name);
return _221===null?_220:_221;
};
nitobi.base.ISerializable.prototype.setAttribute=function(name,_223){
this[name]=_223;
if(null!=_223){
this.xmlNode.setAttribute(name.toLowerCase(),_223);
}
};
nitobi.base.ISerializable.prototype.setIntAttribute=function(name,_225){
var n=parseInt(_225);
if(_225!=null&&(typeof (n)!="number"||isNaN(n))){
nitobi.lang.throwError(name+" is not an integer and therefore cannot be set. It's value was "+_225);
}
this.setAttribute(name,_225);
};
nitobi.base.ISerializable.prototype.getIntAttribute=function(name,_228){
var x=this.getAttribute(name,_228);
if(x==null||x==""){
return 0;
}
var tx=parseInt(x);
if(isNaN(tx)){
nitobi.lang.throwError("ISerializable attempting to get "+name+" which was supposed to be an int but was actually NaN");
}
return tx;
};
nitobi.base.ISerializable.prototype.setBoolAttribute=function(name,_22c){
_22c=nitobi.lang.getBool(_22c);
if(_22c!=null&&typeof (_22c)!="boolean"){
nitobi.lang.throwError(name+" is not an boolean and therefore cannot be set. It's value was "+_22c);
}
this.setAttribute(name,(_22c?"true":"false"));
};
nitobi.base.ISerializable.prototype.getBoolAttribute=function(name,_22e){
var x=this.getAttribute(name,_22e);
if(typeof (x)=="string"&&x==""){
return null;
}
var tx=nitobi.lang.getBool(x);
if(tx==null){
nitobi.lang.throwError("ISerializable attempting to get "+name+" which was supposed to be a bool but was actually "+x);
}
return tx;
};
nitobi.base.ISerializable.prototype.setDateAttribute=function(name,_232){
this.setAttribute(name,_232.toString());
};
nitobi.base.ISerializable.prototype.getDateAttribute=function(name){
return new Date(this.getAttribute(name));
};
nitobi.base.ISerializable.prototype.getId=function(){
return this.getAttribute("id");
};
nitobi.base.ISerializable.prototype.getChildObjectId=function(_234,_235){
var _236=(typeof (_234.className)=="string"?_234.tagName:_234.getXmlNode().nodeName);
var _237=_236;
if(_235){
_237+="[@instancename='"+_235+"']";
}
var node=this.getXmlNode().selectSingleNode(_237);
if(null==node){
return null;
}else{
return node.getAttribute("id");
}
};
nitobi.base.ISerializable.prototype.setObject=function(_239,_23a){
if(_239.ISerializableImplemented!=true){
nitobi.lang.throwError(nitobi.error.ExpectedInterfaceNotFound+" ISerializable");
}
var id=this.getChildObjectId(_239,_23a);
if(null!=id){
this.deleteChildObject(id);
}
if(_23a){
_239.setAttribute("instancename",_23a);
}
this.addChildObject(_239);
};
nitobi.base.ISerializable.prototype.getObject=function(_23c,_23d){
var id=this.getChildObjectId(_23c,_23d);
if(null==id){
return id;
}
return this.getChildObject(id);
};
nitobi.base.ISerializable.prototype.getObjectById=function(id){
return this.getChildObject(id);
};
nitobi.base.ISerializable.prototype.isDescendantExists=function(id){
var node=this.getXmlNode();
var _242=node.selectSingleNode("//*[@id='"+id+"']");
return (_242!=null);
};
nitobi.base.ISerializable.prototype.getPathToLeaf=function(id){
var node=this.getXmlNode();
var _245=node.selectSingleNode("//*[@id='"+id+"']");
if(nitobi.browser.IE){
_245.ownerDocument.setProperty("SelectionLanguage","XPath");
}
var _246=_245.selectNodes("./ancestor-or-self::*");
var _247=this.getId();
var _248=0;
for(var i=0;i<_246.length;i++){
if(_246[i].getAttribute("id")==_247){
_248=i+1;
break;
}
}
var arr=nitobi.collections.IEnumerable.createNewArray(_246,_248);
return arr.reverse();
};
nitobi.base.ISerializable.prototype.isDescendantInstantiated=function(id){
var node=this.getXmlNode();
var _24d=node.selectSingleNode("//*[@id='"+id+"']");
if(nitobi.browser.IE){
_24d.ownerDocument.setProperty("SelectionLanguage","XPath");
}
var _24e=_24d.selectNodes("ancestor::*");
var _24f=false;
var obj=this;
for(var i=0;i<_24e.length;i++){
if(_24f){
var _252=_24e[i].getAttribute("id");
instantiated=obj.inCache(_252);
if(!instantiated){
return false;
}
obj=this.getObjectById(_252);
}
if(_24e[i].getAttribute("id")==this.getId()){
_24f=true;
}
}
return obj.inCache(id);
};
nitobi.lang.defineNs("nitobi.base");
nitobi.base.Registry=function(){
this.classMap={};
this.tagMap={};
};
nitobi.base.Registry.instance=null;
nitobi.base.Registry.getInstance=function(){
if(nitobi.base.Registry.instance==null){
nitobi.base.Registry.instance=new nitobi.base.Registry();
}
return nitobi.base.Registry.instance;
};
nitobi.base.Registry.prototype.getProfileByClass=function(_253){
return this.classMap[_253];
};
nitobi.base.Registry.prototype.getProfileByInstance=function(_254){
var _255=nitobi.lang.getFirstFunction(_254);
var p=_255.value.prototype;
var _257=null;
var _258=0;
for(var _259 in this.classMap){
var _25a=this.classMap[_259].classObject;
var _25b=0;
while(_25a&&_254 instanceof _25a){
_25a=_25a.baseConstructor;
_25b++;
}
if(_25b>_258){
_258=_25b;
_257=_259;
}
}
if(_257){
return this.getProfileByClass(_257);
}else{
return null;
}
};
nitobi.base.Registry.prototype.getProfileByTag=function(_25c){
return this.tagMap[_25c];
};
nitobi.base.Registry.prototype.getCompleteProfile=function(_25d){
if(nitobi.lang.isDefined(_25d.className)&&_25d.className!=null){
return this.classMap[_25d.className];
}
if(nitobi.lang.isDefined(_25d.tagName)&&_25d.tagName!=null){
return this.tagMap[_25d.tagName];
}
nitobi.lang.throwError("A complete class profile could not be found. Insufficient information was provided.");
};
nitobi.base.Registry.prototype.register=function(_25e){
if(!nitobi.lang.isDefined(_25e.tagName)||null==_25e.tagName){
nitobi.lang.throwError("Illegal to register a class without a tagName.");
}
if(!nitobi.lang.isDefined(_25e.className)||null==_25e.className){
nitobi.lang.throwError("Illegal to register a class without a className.");
}
this.tagMap[_25e.tagName]=_25e;
this.classMap[_25e.className]=_25e;
};
nitobi.lang.defineNs("nitobi.base");
nitobi.base.Factory=function(){
this.registry=nitobi.base.Registry.getInstance();
};
nitobi.lang.extend(nitobi.base.Factory,nitobi.Object);
nitobi.base.Factory.instance=null;
nitobi.base.Factory.prototype.createByClass=function(_25f){
try{
return nitobi.lang.newObject(_25f,arguments,1);
}
catch(err){
nitobi.lang.throwError("The Factory (createByClass) could not create the class "+_25f+".",err);
}
};
nitobi.base.Factory.prototype.createByNode=function(_260){
try{
if(null==_260){
nitobi.lang.throwError(nitobi.error.ArgExpected);
}
if(nitobi.lang.typeOf(_260)==nitobi.lang.type.XMLDOC){
_260=nitobi.xml.getChildNodes(_260)[0];
}
var _261=this.registry.getProfileByTag(_260.nodeName).className;
var _262=_260.ownerDocument;
var _263=Array.prototype.slice.call(arguments,0);
var obj=nitobi.lang.newObject(_261,_263,0);
return obj;
}
catch(err){
nitobi.lang.throwError("The Factory (createByNode) could not create the class "+_261+".",err);
}
};
nitobi.base.Factory.prototype.createByProfile=function(_265){
try{
return nitobi.lang.newObject(_265.className,arguments,1);
}
catch(err){
nitobi.lang.throwError("The Factory (createByProfile) could not create the class "+_265.className+".",err);
}
};
nitobi.base.Factory.prototype.createByTag=function(_266){
try{
var _267=this.registry.getProfileByTag(_266).className;
var _268=Array.prototype.slice.call(arguments,0);
return nitobi.lang.newObject(_267,_268,1);
}
catch(err){
nitobi.lang.throwError("The Factory (createByTag) could not create the class "+_267+".",err);
}
};
nitobi.base.Factory.getInstance=function(){
if(nitobi.base.Factory.instance==null){
nitobi.base.Factory.instance=new nitobi.base.Factory();
}
return nitobi.base.Factory.instance;
};
nitobi.lang.defineNs("nitobi.base");
nitobi.base.Profile=function(_269,_26a,_26b,_26c,_26d){
this.className=_269;
this.classObject=eval(_269);
this.schema=_26a;
this.singleton=_26b;
this.tagName=_26c;
this.idField=_26d||"id";
};
nitobi.lang.defineNs("nitobi.base");
if(false){
nitobi.base=function(){
};
}
nitobi.base.Declaration=function(){
nitobi.base.Declaration.baseConstructor.call(this);
this.xmlDoc=null;
};
nitobi.lang.extend(nitobi.base.Declaration,nitobi.Object);
nitobi.base.Declaration.prototype.loadHtml=function(_26e){
try{
_26e=$(_26e);
this.xmlDoc=nitobi.xml.parseHtml(_26e);
return this.xmlDoc;
}
catch(err){
nitobi.lang.throwError(nitobi.error.DeclarationParseError,err);
}
};
nitobi.base.Declaration.prototype.getXmlDoc=function(){
return this.xmlDoc;
};
nitobi.base.Declaration.prototype.serializeToXml=function(){
return nitobi.xml.serialize(this.xmlDoc);
};
nitobi.lang.defineNs("nitobi.base");
nitobi.base.DateMath={DAY:"d",WEEK:"w",MONTH:"m",YEAR:"y",ONE_DAY_MS:86400000};
nitobi.base.DateMath._add=function(date,unit,_271){
if(unit==this.DAY){
date.setDate(date.getDate()+_271);
}else{
if(unit==this.WEEK){
date.setDate(date.getDate()+7*_271);
}else{
if(unit==this.MONTH){
date.setMonth(date.getMonth()+_271);
}else{
if(unit==this.YEAR){
date.setFullYear(date.getFullYear()+_271);
}
}
}
}
return date;
};
nitobi.base.DateMath.add=function(date,unit,_274){
return this._add(date,unit,_274);
};
nitobi.base.DateMath.subtract=function(date,unit,_277){
return this._add(date,unit,-1*_277);
};
nitobi.base.DateMath.after=function(date,_279){
return (date-_279)>0;
};
nitobi.base.DateMath.between=function(date,_27b,end){
return (date-_27b)>=0&&(end-date)>0;
};
nitobi.base.DateMath.before=function(date,_27e){
return (date-_27e)<0;
};
nitobi.base.DateMath.clone=function(date){
var n=new Date();
n.setFullYear(date.getFullYear());
n.setMonth(date.getMonth());
n.setDate(date.getDate());
n.setHours(date.getHours());
n.setMinutes(date.getMinutes());
n.setSeconds(date.getSeconds());
n.setMilliseconds(date.getMilliseconds());
return n;
};
nitobi.base.DateMath.isLeapYear=function(date){
var y=date.getFullYear();
var _1=String(y/4).indexOf(".")==-1;
var _2=String(y/100).indexOf(".")==-1;
var _3=String(y/400).indexOf(".")==-1;
return (_3)?true:(_1&&!_2)?true:false;
};
nitobi.base.DateMath.getMonthDays=function(date){
return [31,(this.isLeapYear(date))?29:28,31,30,31,30,31,31,30,31,30,31][date.getMonth()];
};
nitobi.base.DateMath.getMonthEnd=function(date){
return new Date(date.getFullYear(),date.getMonth(),this.getMonthDays(date));
};
nitobi.base.DateMath.getMonthStart=function(date){
return new Date(date.getFullYear(),date.getMonth(),1);
};
nitobi.base.DateMath.isToday=function(date){
var _28a=this.resetTime(new Date());
var end=this.add(this.clone(_28a),this.DAY,1);
return this.between(date,_28a,end);
};
nitobi.base.DateMath.parse=function(str){
};
nitobi.base.DateMath.getWeekNumber=function(date){
var _28e=this.getJanuary1st(date);
return Math.ceil(this.getNumberOfDays(_28e,date)/7);
};
nitobi.base.DateMath.getNumberOfDays=function(_28f,end){
var _291=end.getTime()-_28f.getTime();
return Math.ceil(_291/this.ONE_DAY_MS)+1;
};
nitobi.base.DateMath.getJanuary1st=function(date){
return new Date(date.getFullYear(),0,1);
};
nitobi.base.DateMath.resetTime=function(date){
date.setHours(0);
date.setMinutes(0);
date.setSeconds(0);
date.setMilliseconds(0);
return date;
};
nitobi.lang.defineNs("nitobi.base");
nitobi.base.Event=function(type){
this.type=type;
this.handlers={};
this.guid=0;
this.setEnabled(true);
};
nitobi.base.Event.prototype.subscribe=function(_295,_296,guid){
var func=_295;
if(typeof (_295)=="string"){
var s=_295;
s=s.replace(/eventArgs/g,"arguments[0]");
_295=nitobi.lang.close(_296,function(){
eval(s);
});
}
if(typeof _296=="object"&&_295 instanceof Function){
func=nitobi.lang.close(_296,_295);
}
guid=guid||func.observer_guid||_295.observer_guid||this.guid++;
func.observer_guid=guid;
_295.observer_guid=guid;
this.handlers[guid]=func;
return guid;
};
nitobi.base.Event.prototype.subscribeOnce=function(_29a,_29b){
var guid=null;
var _29d=this;
var _29e=function(){
_29a.apply(_29b||null,arguments);
_29d.unSubscribe(guid);
};
guid=this.subscribe(_29e);
return guid;
};
nitobi.base.Event.prototype.unSubscribe=function(guid){
if(guid instanceof Function){
guid=guid.observer_guid;
}
this.handlers[guid]=null;
delete this.handlers[guid];
};
nitobi.base.Event.prototype.notify=function(_2a0){
if(this.enabled){
if(arguments.length==0){
arguments=new Array();
arguments[0]=new nitobi.base.EventArgs(null,this);
arguments[0].event=this;
arguments[0].source=null;
}else{
if(typeof (arguments[0].event)!="undefined"&&arguments[0].event==null){
arguments[0].event=this;
}
}
var fail=false;
for(var item in this.handlers){
var _2a3=this.handlers[item];
if(_2a3 instanceof Function){
var rv=(_2a3.apply(this,arguments)==false);
fail=fail||rv;
}
}
return !fail;
}
return true;
};
nitobi.base.Event.prototype.dispose=function(){
for(var _2a5 in this.handlers){
this.handlers[_2a5]=null;
}
this.handlers={};
};
nitobi.base.Event.prototype.setEnabled=function(_2a6){
this.enabled=_2a6;
};
nitobi.base.Event.prototype.isEnabled=function(){
return this.enabled;
};
nitobi.lang.defineNs("nitobi.base");
nitobi.base.EventArgs=function(_2a7,_2a8){
this.source=_2a7;
this.event=_2a8||null;
};
nitobi.lang.defineNs("nitobi.collections");
nitobi.collections.IList=function(){
nitobi.base.ISerializable.call(this);
nitobi.collections.IEnumerable.call(this);
};
nitobi.lang.implement(nitobi.collections.IList,nitobi.base.ISerializable);
nitobi.lang.implement(nitobi.collections.IList,nitobi.collections.IEnumerable);
nitobi.collections.IList.prototype.IListImplemented=true;
nitobi.collections.IList.prototype.add=function(obj){
nitobi.collections.IEnumerable.prototype.add.call(this,obj);
if(obj.ISerializableImplemented==true&&obj.profile!=null){
this.addChildObject(obj);
}
};
nitobi.collections.IList.prototype.insert=function(_2aa,obj){
var _2ac=this.get(_2aa);
nitobi.collections.IEnumerable.prototype.insert.call(this,_2aa,obj);
if(obj.ISerializableImplemented==true&&obj.profile!=null){
this.insertBeforeChildObject(obj,_2ac);
}
};
nitobi.collections.IList.prototype.addToCache=function(obj,_2ae){
nitobi.base.ISerializable.prototype.addToCache.call(this,obj);
this.list[_2ae]=obj;
};
nitobi.collections.IList.prototype.removeFromCache=function(_2af){
nitobi.base.ISerializable.prototype.removeFromCache.call(this,this.list[_2af].getId());
};
nitobi.collections.IList.prototype.flushCache=function(){
nitobi.base.ISerializable.prototype.flushCache.call(this);
this.list=new Array();
};
nitobi.collections.IList.prototype.get=function(_2b0){
if(typeof (_2b0)=="object"){
return _2b0;
}
if(_2b0<0||_2b0>=this.getLength()){
nitobi.lang.throwError(nitobi.error.OutOfBounds);
}
var obj=null;
if(this.list[_2b0]!=null){
obj=this.list[_2b0];
}
if(obj==null){
var _2b2=nitobi.xml.getChildNodes(this.xmlNode)[_2b0];
if(_2b2==null){
return null;
}else{
obj=this.factory.createByNode(_2b2);
this.onCreateObject.notify(obj);
nitobi.collections.IList.prototype.addToCache.call(this,obj,_2b0);
}
obj.setParentObject(this);
}
return obj;
};
nitobi.collections.IList.prototype.getById=function(id){
var node=this.xmlNode.selectSingleNode("*[@id='"+id+"']");
var _2b5=nitobi.xml.indexOfChildNode(node.parentNode,node);
return this.get(_2b5);
};
nitobi.collections.IList.prototype.set=function(_2b6,_2b7){
if(_2b6<0||_2b6>=this.getLength()){
nitobi.lang.throwError(nitobi.error.OutOfBounds);
}
try{
if(_2b7.ISerializableImplemented==true){
var obj=this.get(_2b6);
if(obj.getXmlNode()!=_2b7.getXmlNode()){
var _2b9=this.xmlNode.insertBefore(_2b7.getXmlNode(),obj.getXmlNode());
this.xmlNode.removeChild(obj.getXmlNode());
obj.setXmlNode(_2b9);
}
}
_2b7.setParentObject(this);
nitobi.collections.IList.prototype.addToCache.call(this,_2b7,_2b6);
}
catch(err){
nitobi.lang.throwError(nitobi.error.Unexpected,err);
}
};
nitobi.collections.IList.prototype.remove=function(_2ba){
var i;
if(typeof (_2ba)!="number"){
i=this.indexOf(_2ba);
}else{
i=_2ba;
}
var obj=this.get(i);
nitobi.collections.IEnumerable.prototype.remove.call(this,_2ba);
this.xmlNode.removeChild(obj.getXmlNode());
};
nitobi.collections.IList.prototype.getLength=function(){
return nitobi.xml.getChildNodes(this.xmlNode).length;
};
nitobi.lang.defineNs("nitobi.collections");
nitobi.collections.List=function(_2bd){
nitobi.collections.List.baseConstructor.call(this);
nitobi.collections.IList.call(this);
};
nitobi.lang.extend(nitobi.collections.List,nitobi.Object);
nitobi.lang.implement(nitobi.collections.List,nitobi.collections.IList);
nitobi.base.Registry.getInstance().register(new nitobi.base.Profile("nitobi.collections.List",null,false,"ntb:list"));
nitobi.lang.defineNs("nitobi.collections");
nitobi.collections.isHashEmpty=function(hash){
var _2bf=true;
for(var item in hash){
if(hash[item]!=null&&hash[item]!=""){
_2bf=false;
break;
}
}
return _2bf;
};
nitobi.collections.hashLength=function(hash){
var _2c2=0;
for(var item in hash){
_2c2++;
}
return _2c2;
};
nitobi.collections.serialize=function(hash){
var s="";
for(var item in hash){
var _2c7=hash[item];
var type=typeof (_2c7);
if(type=="string"||type=="number"){
s+="'"+item+"':'"+_2c7+"',";
}
}
s=s.substring(0,s.length-1);
return "{"+s+"}";
};
if(false){
nitobi.ui=function(){
};
}
nitobi.lang.defineNs("nitobi.ui");
nitobi.ui.IStyleable=function(_2c9){
this.htmlNode=_2c9||null;
this.onBeforeSetStyle=new nitobi.base.Event();
this.onSetStyle=new nitobi.base.Event();
};
nitobi.ui.IStyleable.prototype.getHtmlNode=function(){
return this.htmlNode;
};
nitobi.ui.IStyleable.prototype.setHtmlNode=function(node){
this.htmlNode=node;
};
nitobi.ui.IStyleable.prototype.setStyle=function(name,_2cc){
if(this.onBeforeSetStyle.notify(new nitobi.ui.StyleEventArgs(this,this.onBeforeSetStyle,name,_2cc))&&this.getHtmlNode()!=null){
nitobi.html.Css.setStyle(this.getHtmlNode(),name,_2cc);
this.onSetStyle.notify(new nitobi.ui.StyleEventArgs(this,this.onSetStyle,name,_2cc));
}
};
nitobi.ui.IStyleable.prototype.getStyle=function(name){
return nitobi.html.Css.getStyle(this.getHtmlNode(),name);
};
nitobi.lang.defineNs("nitobi.ui");
nitobi.ui.StyleEventArgs=function(_2ce,_2cf,_2d0,_2d1){
nitobi.ui.ElementEventArgs.baseConstructor.apply(this,arguments);
this.property=_2d0||null;
this.value=_2d1||null;
};
nitobi.lang.extend(nitobi.ui.StyleEventArgs,nitobi.base.EventArgs);
nitobi.lang.defineNs("nitobi.ui");
nitobi.ui.IScrollable=function(_2d2){
this.scrollableElement=_2d2;
};
nitobi.ui.IScrollable.prototype.setScrollableElement=function(el){
this.scrollableElement=el;
};
nitobi.ui.IScrollable.prototype.getScrollableElement=function(){
return this.scrollableElement;
};
nitobi.ui.IScrollable.prototype.getScrollLeft=function(){
return this.scrollableElement.scrollLeft;
};
nitobi.ui.IScrollable.prototype.setScrollLeft=function(left){
this.scrollableElement.scrollLeft=left;
};
nitobi.ui.IScrollable.prototype.scrollLeft=function(_2d5){
_2d5=_2d5||25;
this.scrollableElement.scrollLeft-=_2d5;
};
nitobi.ui.IScrollable.prototype.scrollRight=function(_2d6){
_2d6=_2d6||25;
this.scrollableElement.scrollLeft+=_2d6;
};
nitobi.ui.IScrollable.prototype.isOverflowed=function(_2d7){
_2d7=_2d7||this.scrollableElement.childNodes[0];
return !(parseInt(nitobi.html.getBox(this.scrollableElement).width)>=parseInt(nitobi.html.getBox(_2d7).width));
};
nitobi.lang.defineNs("nitobi.ui");
if(false){
nitobi.ui=function(){
};
}
nitobi.ui.startDragOperation=function(_2d8,_2d9,_2da,_2db,_2dc,_2dd){
var ddo=new nitobi.ui.DragDrop(_2d8,_2d9,_2da,_2db,_2dc,_2dd);
_2d8.dragObject=ddo;
};
nitobi.ui.DragDrop=function(_2df,_2e0,_2e1,_2e2,_2e3,_2e4){
this.allowVertDrag=(_2e1!=null?_2e1:true);
this.allowHorizDrag=(_2e2!=null?_2e2:true);
this.context=_2e3;
this.onMouseUpEvent=_2e4;
if(nitobi.browser.IE){
this.surface=document.getElementById("ebadragdropsurface_");
if(this.surface==null){
this.surface=document.createElement("div");
this.surface.id="ebadragdropsurface_";
var _2e5=this.surface.style;
_2e5.filter="alpha(opacity=1)";
_2e5.backgroundColor="white";
_2e5.position="absolute";
_2e5.visibility="hidden";
_2e5.top="0";
_2e5.left="0";
_2e5.width="100";
_2e5.height="100";
_2e5.zIndex="899";
document.body.appendChild(this.surface);
}
}
if(_2df.nodeType==3){
alert("Text node not supported. Use parent element");
}
this.element=_2df;
this.zIndex=_2df.style.zIndex;
_2df.style.zIndex=900;
if(nitobi.browser.IE){
x=window.event.clientX+document.documentElement.scrollLeft+document.body.scrollLeft;
y=window.event.clientY+document.documentElement.scrollTop+document.body.scrollTop;
}else{
x=_2e0.clientX+window.scrollX;
y=_2e0.clientY+window.scrollY;
}
this.elementOriginTop=parseInt(_2df.style.top,10);
this.elementOriginLeft=parseInt(_2df.style.left,10);
if(isNaN(this.elementOriginLeft)){
this.elementOriginLeft=0;
}
if(isNaN(this.elementOriginTop)){
this.elementOriginTop=0;
}
this.originX=x;
this.originY=y;
var _2e6=this;
this.onMouseUpHandle=function(_2e7){
_2e6.onMouseUp.call(_2e6,_2e7);
};
this.onMouseMoveHandle=function(_2e8){
_2e6.onMouseMove.call(_2e6,_2e8);
};
if(nitobi.browser.IE){
document.attachEvent("onmouseup",this.onMouseUpHandle);
document.attachEvent("onmousemove",this.onMouseMoveHandle);
window.event.cancelBubble=true;
window.event.returnValue=false;
}else{
document.addEventListener("mouseup",this.onMouseUpHandle,true);
document.addEventListener("mousemove",this.onMouseMoveHandle,true);
_2e0.preventDefault();
}
};
nitobi.ui.DragDrop.prototype.onMouseMove=function(_2e9){
var x,y;
if(nitobi.browser.IE){
x=window.event.clientX+document.documentElement.scrollLeft+document.body.scrollLeft;
y=window.event.clientY+document.documentElement.scrollTop+document.body.scrollTop;
this.surface.style.visibility="visible";
this.surface.style.width=document.body.clientWidth;
this.surface.style.height=document.body.clientHeight;
}else{
x=_2e9.clientX+window.scrollX;
y=_2e9.clientY+window.scrollY;
}
if(this.allowHorizDrag){
this.element.style.left=(this.elementOriginLeft+x-this.originX)+"px";
}
if(this.allowVertDrag){
this.element.style.top=(this.elementOriginTop+y-this.originY)+"px";
}
this.x=x;
this.y=y;
if(nitobi.browser.IE){
window.event.cancelBubble=true;
window.event.returnValue=false;
}else{
_2e9.preventDefault();
}
};
nitobi.ui.DragDrop.prototype.onMouseUp=function(_2ec){
if(this.onMouseUpEvent!=null){
this.onMouseUpEvent.call(this.context,this.x,this.y);
}
if(nitobi.browser.IE){
document.detachEvent("onmousemove",this.onMouseMoveHandle);
document.detachEvent("onmouseup",this.onMouseUpHandle);
if(nitobi.browser.IE){
this.surface.style.visibility="hidden";
}
}else{
document.removeEventListener("mousemove",this.onMouseMoveHandle,true);
document.removeEventListener("mouseup",this.onMouseUpHandle,true);
}
this.element.style.zIndex=this.zIndex;
this.element.object=null;
this.element=null;
};
if(typeof (nitobi.ajax)=="undefined"){
nitobi.ajax=function(){
};
}
nitobi.ajax.createXmlHttp=function(){
if(nitobi.browser.IE){
var _2ed=null;
try{
_2ed=new ActiveXObject("Msxml2.XMLHTTP");
}
catch(e){
try{
_2ed=new ActiveXObject("Microsoft.XMLHTTP");
}
catch(ee){
}
}
return _2ed;
}else{
if(nitobi.browser.XHR_ENABLED){
return new XMLHttpRequest();
}
}
};
nitobi.lang.defineNs("nitobi.ajax");
nitobi.ajax.HttpRequest=function(){
this.handler="";
this.async=true;
this.responseType=null;
this.httpObj=nitobi.ajax.createXmlHttp();
this.onPostComplete=new nitobi.base.Event();
this.onGetComplete=new nitobi.base.Event();
this.onError=new nitobi.base.Event();
this.timeout=0;
this.timeoutId=null;
this.params=null;
this.data="";
this.completeCallback=null;
this.errorCallback=null;
this.status="complete";
};
nitobi.lang.extend(nitobi.ajax.HttpRequest,nitobi.Object);
nitobi.ajax.HttpRequestPool_MAXCONNECTIONS=64;
nitobi.ajax.HttpRequest.prototype.handleResponse=function(){
var _2ee=null;
var _2ef=null;
if(this.responseType=="xml"||((this.httpObj.responseXML!=null&&this.httpObj.responseXML.documentElement!=null)&&this.responseType!="text")){
_2ee=this.httpObj.responseXML;
}else{
_2ee=this.httpObj.responseText;
}
if(this.httpObj.status!=200){
this.onError.notify({"source":this,"status":this.httpObj.status,"message":"An error occured retrieving the data from the server. "+"Expected response type was '"+this.responseType+"'."});
}
return _2ee;
};
nitobi.ajax.HttpRequest.prototype.post=function(data){
this.data=data;
this.status="pending";
this.httpObj.open("POST",this.handler,this.async,"","");
if(this.async){
this.httpObj.onreadystatechange=nitobi.lang.close(this,this.postComplete);
}
if(this.responseType=="xml"){
this.httpObj.setRequestHeader("Content-Type","text/xml");
}else{
this.httpObj.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
}
this.httpObj.send(data);
if(!this.async){
return this.handleResponse();
}
};
nitobi.ajax.HttpRequest.prototype.postComplete=function(){
if(this.httpObj.readyState==4){
this.status="complete";
var _2f1={"response":this.handleResponse(),"params":this.params};
this.onPostComplete.notify(_2f1);
if(this.completeCallback){
this.completeCallback.call(this,_2f1);
}
}
};
nitobi.ajax.HttpRequest.prototype.postXml=function(_2f2){
this.setTimeout();
if(("undefined"==typeof (_2f2.documentElement))||(null==_2f2.documentElement)||("undefined"==typeof (_2f2.documentElement.childNodes))||(1>_2f2.documentElement.childNodes.length)){
ebaErrorReport("updategram is empty. No request sent. xmlData["+_2f2+"]\nxmlData.xml["+_2f2.xml+"]");
return;
}
if(null==_2f2.xml){
var _2f3=new XMLSerializer();
_2f2.xml=_2f3.serializeToString(_2f2);
}
var sync=this.post(_2f2.xml);
if(!this.async){
return sync;
}
};
nitobi.ajax.HttpRequest.prototype.get=function(){
this.setTimeout();
this.status="pending";
try{
this.httpObj.open("GET",this.handler,this.async);
}
catch(e){
throw (e);
return;
}
if(this.async){
this.httpObj.onreadystatechange=nitobi.lang.close(this,this.getComplete);
}
if(this.responseType=="xml"){
this.httpObj.setRequestHeader("Content-Type","text/xml");
}
this.httpObj.send(null);
if(!this.async){
return this.handleResponse();
}
};
nitobi.ajax.HttpRequest.prototype.setTimeout=function(){
if(this.timeout>0){
this.timeoutId=window.setTimeout(nitobi.lang.close(this,this.abort),this.timeout);
}
};
nitobi.ajax.HttpRequest.prototype.getComplete=function(){
if(this.httpObj.readyState==4){
this.status="complete";
var _2f5={"response":this.handleResponse(),"params":this.params,"status":this.httpObj.status,"statusText":this.httpObj.statusText};
this.onGetComplete.notify(_2f5);
if(this.completeCallback){
this.completeCallback.call(this,_2f5);
}
}
};
nitobi.ajax.HttpRequest.isError=function(code){
return (code>=400&&code<600);
};
nitobi.ajax.HttpRequest.prototype.abort=function(){
this.httpObj.onreadystatechange=function(){
};
this.httpObj.abort();
};
nitobi.ajax.HttpRequest.prototype.clear=function(){
this.handler="";
this.async=true;
this.onPostComplete.dispose();
this.onGetComplete.dispose();
this.params=null;
};
nitobi.ajax.HttpRequest.prototype.cacheBust=function(url){
var _2f8=url.split("?");
var _2f9="nitobi_cachebust="+(new Date().getTime());
if(_2f8.length==1){
url+="?"+_2f9;
}else{
url+="&"+_2f9;
}
return url;
};
nitobi.ajax.HttpRequestPool=function(_2fa){
this.inUse=new Array();
this.free=new Array();
this.max=_2fa||nitobi.ajax.HttpRequestPool_MAXCONNECTIONS;
this.locked=false;
this.context=null;
};
nitobi.ajax.HttpRequestPool.prototype.reserve=function(){
this.locked=true;
var _2fb;
if(this.free.length){
_2fb=this.free.pop();
_2fb.clear();
this.inUse.push(_2fb);
}else{
if(this.inUse.length<this.max){
try{
_2fb=new nitobi.ajax.HttpRequest();
}
catch(e){
_2fb=null;
}
this.inUse.push(_2fb);
}else{
throw "No request objects available";
}
}
this.locked=false;
return _2fb;
};
nitobi.ajax.HttpRequestPool.prototype.release=function(_2fc){
var _2fd=false;
this.locked=true;
if(null!=_2fc){
for(var i=0;i<this.inUse.length;i++){
if(_2fc==this.inUse[i]){
this.free.push(this.inUse[i]);
this.inUse.splice(i,1);
_2fd=true;
break;
}
}
}
this.locked=false;
return null;
};
nitobi.ajax.HttpRequestPool.prototype.dispose=function(){
for(var i=0;i<this.inUse.length;i++){
this.inUse[i].dispose();
}
this.inUse=null;
for(var j=0;j<this.free.length;j++){
this.free[i].dispose();
}
this.free=null;
};
nitobi.ajax.HttpRequestPool.instance=null;
nitobi.ajax.HttpRequestPool.getInstance=function(){
if(nitobi.ajax.HttpRequestPool.instance==null){
nitobi.ajax.HttpRequestPool.instance=new nitobi.ajax.HttpRequestPool();
}
return nitobi.ajax.HttpRequestPool.instance;
};
nitobi.lang.defineNs("nitobi.data");
nitobi.data.UrlConnector=function(url,_302){
this.url=url||null;
this.transformer=_302||null;
this.async=true;
};
nitobi.data.UrlConnector.prototype.get=function(_303,_304){
var _305=nitobi.data.UrlConnector.requestPool.reserve();
var _306=this.url;
for(var p in _303){
_306=nitobi.html.Url.setParameter(_306,p,_303[p]);
}
_305.handler=_306;
_305.async=this.async;
_305.responseType="xml";
_305.params={dataReadyCallback:_304};
_305.completeCallback=nitobi.lang.close(this,this.getComplete);
_305.get();
};
nitobi.data.UrlConnector.prototype.getComplete=function(_308){
if(_308.params.dataReadyCallback){
var _309=_308.response;
var _30a=_308.params.dataReadyCallback;
var _30b=_309;
if(this.transformer){
if(typeof (this.transformer)==="function"){
_30b=this.transformer.call(null,_309);
}else{
_30b=nitobi.xml.transform(_309,this.transformer,"xml");
}
}
if(_30a){
_30a.call(null,{result:_30b,response:_308.response});
}
}
};
nitobi.data.UrlConnector.requestPool=new nitobi.ajax.HttpRequestPool();
function ntbAssert(_30c,_30d,_30e,_30f){
}
nitobi.lang.defineNs("console");
nitobi.lang.defineNs("nitobi.debug");
if(typeof (console.log)=="undefined"){
console.log=function(s){
nitobi.debug.addDebugTools();
var t=$("nitobi.log");
t.value=s+"\n"+t.value;
};
console.evalCode=function(){
var _312=(eval($("nitobi.consoleEntry").value));
};
}
nitobi.debug.addDebugTools=function(){
var sId="nitobi_debug_panel";
var div=document.getElementById(sId);
var html="<table width=100%><tr><td width=50%><textarea style='width:100%' cols=125 rows=25 id='nitobi.log'></textarea></td><td width=50%><textarea style='width:100%' cols=125 rows=25 id='nitobi.consoleEntry'></textarea><br/><button onclick='console.evalCode()'>Eval</button></td></tr></table>";
if(div==null){
var div=document.createElement("div");
div.setAttribute("id",sId);
div.innerHTML=html;
document.body.appendChild(div);
}else{
if(div.innerHTML==""){
div.innerHTML=html;
}
}
};
nitobi.debug.assert=function(){
};
EBA_EM_ATTRIBUTE_ERROR=1;
EBA_XHR_RESPONSE_ERROR=2;
EBA_DEBUG="debug";
EBA_WARN="warn";
EBA_ERROR="error";
EBA_THROW="throw";
EBA_DEBUG_MODE=false;
EBA_ON_ERROR="";
EBA_LAST_ERROR="";
_ebaDebug=false;
NTB_EM_ATTRIBUTE_ERROR=1;
NTB_XHR_RESPONSE_ERROR=2;
NTB_DEBUG="debug";
NTB_WARN="warn";
NTB_ERROR="error";
NTB_THROW="throw";
NTB_DEBUG_MODE=false;
NTB_ON_ERROR="";
NTB_LAST_ERROR="";
_ebaDebug=false;
function _ntbAssert(_316,_317){
}
function ebaSetOnErrorEvent(_318){
nitobi.debug.setOnErrorEvent.apply(this,arguments);
}
nitobi.debug.setOnErrorEvent=function(_319){
NTB_ON_ERROR=_319;
};
function ebaReportError(_31a,_31b,_31c){
nitobi.debug.errorReport("dude stop calling this method it is now called nitobi.debug.errorReport","");
nitobi.debug.errorReport(_31a,_31b,_31c);
}
function ebaErrorReport(_31d,_31e,_31f){
nitobi.debug.errorReport.apply(this,arguments);
}
nitobi.debug.errorReport=function(_320,_321,_322){
_322=(_322)?_322:NTB_DEBUG;
if(NTB_DEBUG==_322&&!NTB_DEBUG_MODE){
return;
}
var _323=_320+"\nerror code    ["+_321+"]\nerror Severity["+_322+"]";
LastError=_323;
if(eval(NTB_ON_ERROR||"true")){
switch(_321){
case NTB_EM_ATTRIBUTE_ERROR:
confirm(_320);
break;
case NTB_XHR_RESPONSE_ERROR:
confirm(_320);
break;
default:
window.status=_320;
break;
}
}
if(NTB_THROW==_322){
throw (_323);
}
};
if(false){
nitobi.error=function(){
};
}
nitobi.lang.defineNs("nitobi.error");
nitobi.error.onError=new nitobi.base.Event();
if(nitobi){
if(nitobi.testframework){
if(nitobi.testframework.initEventError){
nitobi.testframework.initEventError();
}
}
}
nitobi.error.ErrorEventArgs=function(_324,_325,type){
nitobi.error.ErrorEventArgs.baseConstructor.call(this,_324);
this.description=_325;
this.type=type;
};
nitobi.lang.extend(nitobi.error.ErrorEventArgs,nitobi.base.EventArgs);
nitobi.error.isError=function(err,_328){
return (err.indexOf(_328)>-1);
};
nitobi.error.OutOfBounds="Array index out of bounds.";
nitobi.error.Unexpected="An unexpected error occurred.";
nitobi.error.ArgExpected="The argument is null and not optional.";
nitobi.error.BadArgType="The argument is not of the correct type.";
nitobi.error.BadArg="The argument is not a valid value.";
nitobi.error.XmlParseError="The XML did not parse correctly.";
nitobi.error.DeclarationParseError="The HTML declaration could not be parsed.";
nitobi.error.ExpectedInterfaceNotFound="The object does not support the properties or methods of the expected interface. Its class must implement the required interface.";
nitobi.error.NoHtmlNode="No HTML node found with id.";
nitobi.error.OrphanXmlNode="The XML node has no owner document.";
nitobi.error.HttpRequestError="The HTML page could not be loaded.";
nitobi.lang.defineNs("nitobi.html");
nitobi.html.IRenderer=function(_329){
this.setTemplate(_329);
this.parameters={};
};
nitobi.html.IRenderer.prototype.renderAfter=function(_32a,data){
_32a=$(_32a);
var _32c=_32a.parentNode;
_32a=_32a.nextSibling;
return this._renderBefore(_32c,_32a,data);
};
nitobi.html.IRenderer.prototype.renderBefore=function(_32d,data){
_32d=$(_32d);
return this._renderBefore(_32d.parentNode,_32d,data);
};
nitobi.html.IRenderer.prototype._renderBefore=function(_32f,_330,data){
var s=this.renderToString(data);
var _333=document.createElement("div");
_333.innerHTML=s;
var _334=new Array();
if(_333.childNodes){
var i=0;
while(_333.childNodes.length){
_334[i++]=_333.firstChild;
_32f.insertBefore(_333.firstChild,_330);
}
}else{
}
return _334;
};
nitobi.html.IRenderer.prototype.renderIn=function(_336,data){
_336=$(_336);
var s=this.renderToString(data);
_336.innerHTML=s;
return _336.childNodes;
};
nitobi.html.IRenderer.prototype.renderToString=function(data){
};
nitobi.html.IRenderer.prototype.setTemplate=function(_33a){
this.template=_33a;
};
nitobi.html.IRenderer.prototype.getTemplate=function(){
return this.template;
};
nitobi.html.IRenderer.prototype.setParameters=function(_33b){
for(var p in _33b){
this.parameters[p]=_33b[p];
}
};
nitobi.html.IRenderer.prototype.getParameters=function(){
return this.parameters;
};
nitobi.lang.defineNs("nitobi.html");
nitobi.html.XslRenderer=function(_33d){
nitobi.html.IRenderer.call(this,_33d);
};
nitobi.lang.implement(nitobi.html.XslRenderer,nitobi.html.IRenderer);
nitobi.html.XslRenderer.prototype.setTemplate=function(_33e){
if(typeof (_33e)==="string"){
_33e=nitobi.xml.createXslProcessor(_33e);
}
this.template=_33e;
};
nitobi.html.XslRenderer.prototype.renderToString=function(data){
if(typeof (data)==="string"){
data=nitobi.xml.createXmlDoc(data);
}
if(nitobi.lang.typeOf(data)===nitobi.lang.type.XMLNODE){
data=nitobi.xml.createXmlDoc(nitobi.xml.serialize(data));
}
var _340=this.getTemplate();
var _341=this.getParameters();
for(var p in _341){
_340.addParameter(p,_341[p],"");
}
var s=nitobi.xml.transformToString(data,_340,"xml");
for(var p in _341){
_340.addParameter(p,"","");
}
return s;
};
nitobi.lang.defineNs("nitobi.ui");
NTB_CSS_HIDE="nitobi-hide";
nitobi.ui.Element=function(id){
nitobi.ui.Element.baseConstructor.call(this);
nitobi.ui.IStyleable.call(this);
if(id!=null){
if(nitobi.lang.typeOf(id)==nitobi.lang.type.XMLNODE){
nitobi.base.ISerializable.call(this,id);
}else{
if($(id)!=null){
var decl=new nitobi.base.Declaration();
var _346=decl.loadHtml($(id));
var _347=$(id);
var _348=_347.parentNode;
var _349=_348.ownerDocument.createElement("ntb:component");
_348.insertBefore(_349,_347);
_348.removeChild(_347);
this.setContainer(_349);
nitobi.base.ISerializable.call(this,_346);
}else{
nitobi.base.ISerializable.call(this);
this.setId(id);
}
}
}else{
nitobi.base.ISerializable.call(this);
}
this.eventMap={};
this.onCreated=new nitobi.base.Event("created");
this.eventMap["created"]=this.onCreated;
this.onBeforeRender=new nitobi.base.Event("beforerender");
this.eventMap["beforerender"]=this.onBeforeRender;
this.onRender=new nitobi.base.Event("render");
this.eventMap["render"]=this.onRender;
this.onBeforeSetVisible=new nitobi.base.Event("beforesetvisible");
this.eventMap["beforesetvisible"]=this.onBeforeSetVisible;
this.onSetVisible=new nitobi.base.Event("setvisible");
this.eventMap["setvisible"]=this.onSetVisible;
this.onBeforePropagate=new nitobi.base.Event("beforepropagate");
this.onEventNotify=new nitobi.base.Event("eventnotify");
this.onBeforeEventNotify=new nitobi.base.Event("beforeeventnotify");
this.onBeforePropagateToChild=new nitobi.base.Event("beforepropogatetochild");
this.subscribeDeclarationEvents();
this.setEnabled(true);
this.renderer=new nitobi.html.XslRenderer();
};
nitobi.lang.extend(nitobi.ui.Element,nitobi.Object);
nitobi.lang.implement(nitobi.ui.Element,nitobi.base.ISerializable);
nitobi.lang.implement(nitobi.ui.Element,nitobi.ui.IStyleable);
nitobi.ui.Element.htmlNodeCache={};
nitobi.ui.Element.prototype.setHtmlNode=function(_34a){
var node=$(_34a);
this.htmlNode=node;
};
nitobi.ui.Element.prototype.getRootId=function(){
var _34c=this.getParentObject();
if(_34c==null){
return this.getId();
}else{
return _34c.getRootId();
}
};
nitobi.ui.Element.prototype.getId=function(){
return this.getAttribute("id");
};
nitobi.ui.Element.parseId=function(id){
var ids=id.split(".");
return {localName:ids[1],id:ids[0]};
};
nitobi.ui.Element.prototype.setId=function(id){
this.setAttribute("id",id);
};
nitobi.ui.Element.prototype.notify=function(_350,id,_352,_353){
try{
_350=nitobi.html.getEvent(_350);
if(_353!==false){
nitobi.html.cancelEvent(_350);
}
var _354=nitobi.ui.Element.parseId(id).id;
if(!this.isDescendantExists(_354)){
return false;
}
var _355=!(_354==this.getId());
var _356=new nitobi.ui.ElementEventArgs(this,null,id);
var _357=new nitobi.ui.EventNotificationEventArgs(this,null,id,_350);
_355=_355&&this.onBeforePropagate.notify(_357);
var _358=true;
if(_355){
if(_352==null){
_352=this.getPathToLeaf(_354);
}
var _359=this.onBeforeEventNotify.notify(_357);
var _35a=(_359?this.onEventNotify.notify(_357):true);
var _35b=_352.pop().getAttribute("id");
var _35c=this.getObjectById(_35b);
var _358=this.onBeforePropagateToChild.notify(_357);
if(_35c.notify&&_358&&_35a){
_358=_35c.notify(_350,id,_352,_353);
}
}else{
_358=this.onEventNotify.notify(_357);
}
var _35d=this.eventMap[_350.type];
if(_35d!=null&&_358){
_35d.notify(this.getEventArgs(_350,id));
}
return _358;
}
catch(err){
nitobi.lang.throwError(nitobi.error.Unexpected+" Element.notify encountered a problem.",err);
}
};
nitobi.ui.Element.prototype.getEventArgs=function(_35e,_35f){
var _360=new nitobi.ui.ElementEventArgs(this,null,_35f);
return _360;
};
nitobi.ui.Element.prototype.subscribeDeclarationEvents=function(){
for(var name in this.eventMap){
var ev=this.getAttribute("on"+name);
if(ev!=null&&ev!=""){
this.eventMap[name].subscribe(ev,this,name);
}
}
};
nitobi.ui.Element.prototype.getHtmlNode=function(name){
var id=this.getId();
id=(name!=null?id+"."+name:id);
var node=nitobi.ui.Element.htmlNodeCache[name];
if(node==null){
node=$(id);
nitobi.ui.Element.htmlNodeCache[id]=node;
}
return node;
};
nitobi.ui.Element.prototype.flushHtmlNodeCache=function(){
nitobi.ui.Element.htmlNodeCache={};
};
nitobi.ui.Element.prototype.hide=function(_366,_367){
this.setVisible(false,_366,_367);
};
nitobi.ui.Element.prototype.show=function(_368,_369){
this.setVisible(true,_368,_369);
};
nitobi.ui.Element.prototype.isVisible=function(){
return !nitobi.html.Css.hasClass(this.getHtmlNode(),NTB_CSS_HIDE);
};
nitobi.ui.Element.prototype.setVisible=function(_36a,_36b,_36c){
var _36d=this.getHtmlNode();
if(_36d&&this.isVisible()!=_36a&&this.onBeforeSetVisible.notify({source:this,event:this.onBeforeSetVisible,args:arguments})!==false){
if(this.effect){
this.effect.end();
}
if(_36a){
if(_36b){
var _36e=new _36b(_36d);
_36e.callback=nitobi.lang.close(this,this.handleSetVisible,[_36c]);
this.effect=_36e;
_36e.onFinish.subscribeOnce(nitobi.lang.close(this,function(){
this.effect=null;
}));
_36e.start();
}else{
nitobi.html.Css.removeClass(_36d,NTB_CSS_HIDE);
this.handleSetVisible(_36c);
}
}else{
if(_36b){
var _36e=new _36b(_36d);
_36e.callback=nitobi.lang.close(this,this.handleSetVisible,[_36c]);
this.effect=_36e;
_36e.onFinish.subscribeOnce(nitobi.lang.close(this,function(){
this.effect=null;
}));
_36e.start();
}else{
nitobi.html.Css.addClass(this.getHtmlNode(),NTB_CSS_HIDE);
this.handleSetVisible(_36c);
}
}
}
};
nitobi.ui.Element.prototype.handleSetVisible=function(_36f){
if(_36f){
_36f();
}
this.onSetVisible.notify(new nitobi.ui.ElementEventArgs(this,this.onSetVisible));
};
nitobi.ui.Element.prototype.setEnabled=function(_370){
this.enabled=_370;
};
nitobi.ui.Element.prototype.isEnabled=function(){
return this.enabled;
};
nitobi.ui.Element.prototype.render=function(_371,_372){
this.flushHtmlNodeCache();
_372=_372||this.getState();
_371=$(_371)||this.getContainer();
if(_371==null){
var _371=document.createElement("span");
document.body.appendChild(_371);
this.setContainer(_371);
}
this.htmlNode=this.renderer.renderIn(_371,_372)[0];
this.htmlNode.jsObject=this;
};
nitobi.ui.Element.prototype.getContainer=function(){
return this.container;
};
nitobi.ui.Element.prototype.setContainer=function(_373){
this.container=$(_373);
};
nitobi.ui.Element.prototype.getState=function(){
return this.getXmlNode();
};
nitobi.lang.defineNs("nitobi.ui");
nitobi.ui.ElementEventArgs=function(_374,_375,_376){
nitobi.ui.ElementEventArgs.baseConstructor.apply(this,arguments);
this.targetId=_376||null;
};
nitobi.lang.extend(nitobi.ui.ElementEventArgs,nitobi.base.EventArgs);
nitobi.lang.defineNs("nitobi.ui");
nitobi.ui.EventNotificationEventArgs=function(_377,_378,_379,_37a){
nitobi.ui.EventNotificationEventArgs.baseConstructor.apply(this,arguments);
this.htmlEvent=_37a||null;
};
nitobi.lang.extend(nitobi.ui.EventNotificationEventArgs,nitobi.ui.ElementEventArgs);
nitobi.lang.defineNs("nitobi.ui");
nitobi.ui.Container=function(id){
nitobi.ui.Container.baseConstructor.call(this,id);
nitobi.collections.IList.call(this);
};
nitobi.lang.extend(nitobi.ui.Container,nitobi.ui.Element);
nitobi.lang.implement(nitobi.ui.Container,nitobi.collections.IList);
nitobi.base.Registry.getInstance().register(new nitobi.base.Profile("nitobi.ui.Container",null,false,"ntb:container"));
nitobi.lang.defineNs("nitobi.ui");
NTB_CSS_SMALL="ntb-effects-small";
if(false){
nitobi.ui.Effects=function(){
};
}
nitobi.ui.Effects={};
nitobi.ui.Effects.shrink=function(_37c,_37d,_37e,_37f){
var rect=_37d.getClientRects()[0];
_37c.deltaHeight_Doctype=0-parseInt("0"+nitobi.html.getStyle(_37d,"border-top-width"))-parseInt("0"+nitobi.html.getStyle(_37d,"border-bottom-width"))-parseInt("0"+nitobi.html.getStyle(_37d,"padding-top"))-parseInt("0"+nitobi.html.getStyle(_37d,"padding-bottom"));
_37c.deltaWidth_Doctype=0-parseInt("0"+nitobi.html.getStyle(_37d,"border-left-width"))-parseInt("0"+nitobi.html.getStyle(_37d,"border-right-width"))-parseInt("0"+nitobi.html.getStyle(_37d,"padding-left"))-parseInt("0"+nitobi.html.getStyle(_37d,"padding-right"));
_37c.oldHeight=Math.abs(rect.top-rect.bottom)+_37c.deltaHeight_Doctype;
_37c.oldWidth=Math.abs(rect.right-rect.left)+_37c.deltaWidth_Doctype;
if(!(typeof (_37c.width)=="undefined")){
_37c.deltaWidth=Math.floor(Math.ceil(_37c.width-_37c.oldWidth)/(_37e/nitobi.ui.Effects.ANIMATION_INTERVAL));
}else{
_37c.width=_37c.oldWidth;
_37c.deltaWidth=0;
}
if(!(typeof (_37c.height)=="undefined")){
_37c.deltaHeight=Math.floor(Math.ceil(_37c.height-_37c.oldHeight)/(_37e/nitobi.ui.Effects.ANIMATION_INTERVAL));
}else{
_37c.height=_37c.oldHeight;
_37c.deltaHeight=0;
}
nitobi.ui.Effects.resize(_37c,_37d,_37e,_37f);
};
nitobi.ui.Effects.resize=function(_381,_382,_383,_384){
var rect=_382.getClientRects()[0];
var _386=Math.abs(rect.top-rect.bottom);
var _387=Math.max(_386+_381.deltaHeight+_381.deltaHeight_Doctype,0);
if(Math.abs(_386-_381.height)<Math.abs(_381.deltaHeight)){
_387=_381.height;
_381.deltaHeight=0;
}
var _388=Math.abs(rect.right-rect.left);
var _389=Math.max(_388+_381.deltaWidth+_381.deltaWidth_Doctype,0);
_389=(_389>=0)?_389:0;
if(Math.abs(_388-_381.width)<Math.abs(_381.deltaWidth)){
_389=_381.width;
_381.deltaWidth=0;
}
_383-=nitobi.ui.Effects.ANIMATION_INTERVAL;
if(_383>0){
window.setTimeout(nitobi.lang.closeLater(this,nitobi.ui.Effects.resize,[_381,_382,_383,_384]),nitobi.ui.Effects.ANIMATION_INTERVAL);
}
var _38a=function(){
_382.height=_387+"px";
_382.style.height=_387+"px";
_382.width=_389+"px";
_382.style.width=_389+"px";
if(_383<=0){
if(_384){
window.setTimeout(_384,0);
}
}
};
nitobi.ui.Effects.executeNextPulse.push(_38a);
};
nitobi.ui.Effects.executeNextPulse=new Array();
nitobi.ui.Effects.pulse=function(){
var p;
while(p=nitobi.ui.Effects.executeNextPulse.pop()){
p.call();
}
};
nitobi.ui.Effects.PULSE_INTERVAL=20;
nitobi.ui.Effects.ANIMATION_INTERVAL=40;
window.setInterval(nitobi.ui.Effects.pulse,nitobi.ui.Effects.PULSE_INTERVAL);
window.setTimeout(nitobi.ui.Effects.pulse,nitobi.ui.Effects.PULSE_INTERVAL);
nitobi.ui.Effects.fadeIntervalId={};
nitobi.ui.Effects.fadeIntervalTime=10;
nitobi.ui.Effects.cube=function(_38c){
return _38c*_38c*_38c;
};
nitobi.ui.Effects.cubeRoot=function(_38d){
var T=0;
var N=parseFloat(_38d);
if(N<0){
N=-N;
T=1;
}
var M=Math.sqrt(N);
var ctr=1;
while(ctr<101){
var M=M*N;
var M=Math.sqrt(Math.sqrt(M));
ctr++;
}
return M;
};
nitobi.ui.Effects.linear=function(_392){
return _392;
};
nitobi.ui.Effects.fade=function(_393,_394,time,_396,_397){
_397=_397||nitobi.ui.Effects.linear;
var _398=(new Date()).getTime()+time;
var id=nitobi.component.getUniqueId();
var _39a=(new Date()).getTime();
var el=_393;
if(_393.length){
el=_393[0];
}
var _39c=nitobi.html.Css.getOpacity(el);
var _39d=(_394-_39c<0?-1:0);
nitobi.ui.Effects.fadeIntervalId[id]=window.setInterval(function(){
nitobi.ui.Effects.stepFade(_393,_394,_39a,_398,id,_396,_397,_39d);
},nitobi.ui.Effects.fadeIntervalTime);
};
nitobi.ui.Effects.stepFade=function(_39e,_39f,_3a0,_3a1,id,_3a3,_3a4,_3a5){
var ct=(new Date()).getTime();
var _3a7=_3a1-_3a0;
var nct=((ct-_3a0)/(_3a1-_3a0));
if(nct<=0||nct>=1){
nitobi.html.Css.setOpacities(_39e,_39f);
window.clearInterval(nitobi.ui.Effects.fadeIntervalId[id]);
_3a3();
return;
}else{
nct=Math.abs(nct+_3a5);
}
var no=_3a4(nct);
nitobi.html.Css.setOpacities(_39e,no*100);
};
nitobi.lang.defineNs("nitobi.component");
if(false){
nitobi.component=function(){
};
}
nitobi.loadComponent=function(el){
var id=el;
try{
el=$(el);
if(el==null){
nitobi.lang.throwError("nitobi.loadComponent could not load the component because it could not be found on the page. The component may not have a declaration, node, or it may have a duplicated id. Id: "+id);
}
if(el.jsObject!=null){
return el.jsObject;
}
var _3ac;
var _3ad=nitobi.html.getTagName(el);
if(_3ad=="ntb:grid"){
_3ac=nitobi.initGrid(el.id);
}else{
if(_3ad==="ntb:combo"){
_3ac=nitobi.initCombo(el.id);
}else{
if(el.jsObject==null){
_3ac=nitobi.base.Factory.getInstance().createByTag(_3ad,el.id,nitobi.component.renderComponent);
if(_3ac.render&&!_3ac.onLoadCallback){
_3ac.render();
}
}else{
_3ac=el.jsObject;
}
}
}
return _3ac;
}
catch(err){
nitobi.lang.throwError(nitobi.error.Unexpected,err);
}
};
nitobi.component.renderComponent=function(_3ae){
_3ae.source.render();
};
nitobi.getComponent=function(id){
var el=$(id);
if(el==null){
return null;
}
return el.jsObject;
};
nitobi.component.uniqueId=0;
nitobi.component.getUniqueId=function(){
return "ntbcmp_"+(nitobi.component.uniqueId++);
};
nitobi.component.findNitobiComponents=function(_3b1,_3b2){
if(nitobi.component.isNitobiElement(_3b1)){
_3b2.push(_3b1);
return;
}
var _3b3=_3b1.childNodes;
for(var i=0;i<_3b3.length;i++){
nitobi.component.findNitobiComponents(_3b3[i],_3b2);
}
return;
};
nitobi.component.isNitobiElement=function(_3b5){
var _3b6=nitobi.html.getTagName(_3b5);
if(_3b6.substr(0,3)=="ntb"){
return true;
}else{
return false;
}
};
nitobi.component.loadComponentsFromNode=function(_3b7){
var _3b8=new Array();
nitobi.component.findNitobiComponents(_3b7,_3b8);
for(var i=0;i<_3b8.length;i++){
nitobi.loadComponent(_3b8[i].getAttribute("id"));
}
};
nitobi.lang.defineNs("nitobi.effects");
if(false){
nitobi.effects=function(){
};
}
nitobi.effects.Effect=function(_3ba,_3bb){
this.element=$(_3ba);
this.transition=_3bb.transition||nitobi.effects.Transition.sinoidal;
this.duration=_3bb.duration||1;
this.fps=_3bb.fps||50;
this.from=typeof (_3bb.from)==="number"?_3bb.from:0;
this.to=typeof (_3bb.from)==="number"?_3bb.to:1;
this.delay=_3bb.delay||0;
this.callback=typeof (_3bb.callback)==="function"?_3bb.callback:nitobi.lang.noop;
this.queue=_3bb.queue||nitobi.effects.EffectQueue.globalQueue;
this.onBeforeFinish=new nitobi.base.Event();
this.onFinish=new nitobi.base.Event();
this.onBeforeStart=new nitobi.base.Event();
};
nitobi.effects.Effect.prototype.start=function(){
var now=new Date().getTime();
this.startOn=now+this.delay*1000;
this.finishOn=this.startOn+this.duration*1000;
this.deltaTime=this.duration*1000;
this.totalFrames=this.duration*this.fps;
this.frame=0;
this.delta=this.from-this.to;
this.queue.add(this);
};
nitobi.effects.Effect.prototype.render=function(pos){
if(!this.running){
this.onBeforeStart.notify(new nitobi.base.EventArgs(this,this.onBeforeStart));
this.setup();
this.running=true;
}
this.update(this.transition(pos*this.delta+this.from));
};
nitobi.effects.Effect.prototype.step=function(now){
if(this.startOn<=now){
if(now>=this.finishOn){
this.end();
return;
}
var pos=(now-this.startOn)/(this.deltaTime);
var _3c0=Math.floor(pos*this.totalFrames);
if(this.frame<_3c0){
this.render(pos);
this.frame=_3c0;
}
}
};
nitobi.effects.Effect.prototype.setup=function(){
};
nitobi.effects.Effect.prototype.update=function(pos){
};
nitobi.effects.Effect.prototype.finish=function(){
};
nitobi.effects.Effect.prototype.end=function(){
this.onBeforeFinish.notify(new nitobi.base.EventArgs(this,this.onBeforeFinish));
this.cancel();
this.render(1);
this.running=false;
this.finish();
this.callback();
this.onFinish.notify(new nitobi.base.EventArgs(this,this.onAfterFinish));
};
nitobi.effects.Effect.prototype.cancel=function(){
this.queue.remove(this);
};
nitobi.effects.factory=function(_3c2,_3c3,etc){
var args=nitobi.lang.toArray(arguments,2);
return function(_3c6){
var f=function(){
_3c2.apply(this,[_3c6,_3c3].concat(args));
};
nitobi.lang.extend(f,_3c2);
return new f();
};
};
nitobi.effects.families={none:{show:null,hide:null}};
nitobi.lang.defineNs("nitobi.effects");
if(false){
nitobi.effects.Transition=function(){
};
}
nitobi.effects.Transition={};
nitobi.effects.Transition.sinoidal=function(x){
return (-Math.cos(x*Math.PI)/2)+0.5;
};
nitobi.effects.Transition.linear=function(x){
return x;
};
nitobi.effects.Transition.reverse=function(x){
return 1-x;
};
nitobi.lang.defineNs("nitobi.effects");
nitobi.effects.Scale=function(_3cb,_3cc,_3cd){
nitobi.effects.Scale.baseConstructor.call(this,_3cb,_3cc);
this.scaleX=typeof (_3cc.scaleX)=="boolean"?_3cc.scaleX:true;
this.scaleY=typeof (_3cc.scaleY)=="boolean"?_3cc.scaleY:true;
this.scaleFrom=typeof (_3cc.scaleFrom)=="number"?_3cc.scaleFrom:100;
this.scaleTo=_3cd;
};
nitobi.lang.extend(nitobi.effects.Scale,nitobi.effects.Effect);
nitobi.effects.Scale.prototype.setup=function(){
var _3ce=this.element.style;
this.originalStyle={"top":_3ce.top,"left":_3ce.left,"width":_3ce.width,"height":_3ce.height,"overflow":_3ce.overflow};
_3ce.overflow="hidden";
this.factor=(this.scaleTo-this.scaleFrom)/100;
this.dims=[this.element.scrollWidth,this.element.scrollHeight];
};
nitobi.effects.Scale.prototype.update=function(pos){
var _3d0=(this.scaleFrom/100)+(this.factor*pos);
this.setDimensions((_3d0*this.dims[0])||1,(_3d0*this.dims[1])||1);
};
nitobi.effects.Scale.prototype.setDimensions=function(x,y){
if(this.scaleX){
this.element.style.width=x+"px";
}
if(this.scaleY){
this.element.style.height=y+"px";
}
};
nitobi.lang.defineNs("nitobi.effects");
nitobi.effects.EffectQueue=function(){
nitobi.effects.EffectQueue.baseConstructor.call(this);
nitobi.collections.IEnumerable.call(this);
this.intervalId=0;
};
nitobi.lang.extend(nitobi.effects.EffectQueue,nitobi.Object);
nitobi.lang.implement(nitobi.effects.EffectQueue,nitobi.collections.IEnumerable);
nitobi.effects.EffectQueue.prototype.add=function(_3d3){
nitobi.collections.IEnumerable.prototype.add.call(this,_3d3);
if(!this.intervalId){
this.intervalId=window.setInterval(nitobi.lang.close(this,this.step),15);
}
};
nitobi.effects.EffectQueue.prototype.step=function(){
var now=new Date().getTime();
this.each(function(e){
e.step(now);
});
};
nitobi.effects.EffectQueue.globalQueue=new nitobi.effects.EffectQueue();
nitobi.lang.defineNs("nitobi.effects");
nitobi.effects.BlindUp=function(_3d6,_3d7){
_3d7=nitobi.lang.merge({scaleX:false,duration:Math.min(0.2*(_3d6.scrollHeight/100),0.5)},_3d7||{});
nitobi.effects.BlindUp.baseConstructor.call(this,_3d6,_3d7,0);
};
nitobi.lang.extend(nitobi.effects.BlindUp,nitobi.effects.Scale);
nitobi.effects.BlindUp.prototype.setup=function(){
nitobi.effects.BlindUp.base.setup.call(this);
};
nitobi.effects.BlindUp.prototype.finish=function(){
nitobi.effects.BlindUp.base.finish.call(this);
nitobi.html.Css.addClass(this.element,NTB_CSS_HIDE);
this.element.style.height="";
};
nitobi.effects.BlindDown=function(_3d8,_3d9){
nitobi.html.Css.swapClass(_3d8,NTB_CSS_HIDE,NTB_CSS_SMALL);
_3d9=nitobi.lang.merge({scaleX:false,scaleFrom:0,duration:Math.min(0.2*(_3d8.scrollHeight/100),0.5)},_3d9||{});
nitobi.effects.BlindDown.baseConstructor.call(this,_3d8,_3d9,100);
};
nitobi.lang.extend(nitobi.effects.BlindDown,nitobi.effects.Scale);
nitobi.effects.BlindDown.prototype.setup=function(){
nitobi.effects.BlindDown.base.setup.call(this);
this.element.style.height="1px";
nitobi.html.Css.removeClass(this.element,NTB_CSS_SMALL);
};
nitobi.effects.BlindDown.prototype.finish=function(){
nitobi.effects.BlindDown.base.finish.call(this);
this.element.style.height="";
};
nitobi.effects.families.blind={show:nitobi.effects.BlindDown,hide:nitobi.effects.BlindUp};
nitobi.lang.defineNs("nitobi.effects");
nitobi.effects.ShadeUp=function(_3da,_3db){
_3db=nitobi.lang.merge({scaleX:false,duration:Math.min(0.2*(_3da.scrollHeight/100),0.3)},_3db||{});
nitobi.effects.ShadeUp.baseConstructor.call(this,_3da,_3db,0);
};
nitobi.lang.extend(nitobi.effects.ShadeUp,nitobi.effects.Scale);
nitobi.effects.ShadeUp.prototype.setup=function(){
nitobi.effects.ShadeUp.base.setup.call(this);
var _3dc=nitobi.html.getFirstChild(this.element);
this.originalStyle.position=this.element.style.position;
nitobi.html.position(this.element);
if(_3dc){
var _3dd=_3dc.style;
this.fnodeStyle={position:_3dd.position,bottom:_3dd.bottom,left:_3dd.left};
this.fnode=_3dc;
_3dd.position="absolute";
_3dd.bottom="0px";
_3dd.left="0px";
}
};
nitobi.effects.ShadeUp.prototype.finish=function(){
nitobi.effects.ShadeUp.base.finish.call(this);
nitobi.html.Css.addClass(this.element,NTB_CSS_HIDE);
this.element.style.height="";
this.element.style.position=this.originalStyle.position;
this.element.style.overflow=this.originalStyle.overflow;
for(var x in this.fnodeStyle){
this.fnode.style[x]=this.fnodeStyle[x];
}
};
nitobi.effects.ShadeDown=function(_3df,_3e0){
nitobi.html.Css.swapClass(_3df,NTB_CSS_HIDE,NTB_CSS_SMALL);
_3e0=nitobi.lang.merge({scaleX:false,scaleFrom:0,duration:Math.min(0.2*(_3df.scrollHeight/100),0.3)},_3e0||{});
nitobi.effects.ShadeDown.baseConstructor.call(this,_3df,_3e0,100);
};
nitobi.lang.extend(nitobi.effects.ShadeDown,nitobi.effects.Scale);
nitobi.effects.ShadeDown.prototype.setup=function(){
nitobi.effects.ShadeDown.base.setup.call(this);
this.element.style.height="1px";
nitobi.html.Css.removeClass(this.element,NTB_CSS_SMALL);
var _3e1=nitobi.html.getFirstChild(this.element);
this.originalStyle.position=this.element.style.position;
nitobi.html.position(this.element);
if(_3e1){
var _3e2=_3e1.style;
this.fnodeStyle={position:_3e2.position,bottom:_3e2.bottom,right:_3e2.right};
this.fnode=_3e1;
_3e2.position="absolute";
_3e2.bottom="0px";
_3e2.left="0px";
}
};
nitobi.effects.ShadeDown.prototype.finish=function(){
nitobi.effects.ShadeDown.base.finish.call(this);
this.element.style.height="";
this.element.style.position=this.originalStyle.position;
this.element.style.overflow=this.originalStyle.overflow;
for(var x in this.fnodeStyle){
this.fnode.style[x]=this.fnodeStyle[x];
}
};
nitobi.effects.families.shade={show:nitobi.effects.ShadeDown,hide:nitobi.effects.ShadeUp};


var temp_ntb_uniqueIdGeneratorProc='<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:ntb="http://www.nitobi.com"> <xsl:output method="xml" /> <x:p-x:n-guid"x:s-0"/><x:t- match="/"> <x:at-/></x:t-><x:t- match="node()|@*"> <xsl:copy> <xsl:if test="not(@id)"> <x:a-x:n-id" ><x:v-x:s-generate-id(.)"/><x:v-x:s-position()"/><x:v-x:s-$guid"/></x:a-> </xsl:if> <x:at-x:s-./* | text() | @*"> </x:at-> </xsl:copy></x:t-> <x:t- match="text()"> <x:v-x:s-."/></x:t-></xsl:stylesheet>';
nitobi.lang.defineNs("nitobi.base");
nitobi.base.uniqueIdGeneratorProc = nitobi.xml.createXslProcessor(nitobiXmlDecodeXslt(temp_ntb_uniqueIdGeneratorProc));


