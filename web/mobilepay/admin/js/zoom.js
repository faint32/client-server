/*Author:AngusYoung	2009-6-22*/
function zoom(ev){
	var eEv=ev?ev:window.event;oAdiv.style.left=document.documentElement.scrollLeft+eEv.clientX-(oAdiv.offsetWidth/2)+'px';
	if(pageLeft(oAdiv)-GetCss(oAdiv,'borderLeftWidth')<pageLeft(oVdiv)+GetCss(oVdiv,'borderLeftWidth')){
		oAdiv.style.left=pageLeft(oVdiv)+GetCss(oVdiv,'borderLeftWidth')-GetCss(oAdiv,'borderLeftWidth')+'px';
	}
	
	if(pageLeft(oAdiv)+oAdiv.offsetWidth-GetCss(oAdiv,'borderRightWidth')>pageLeft(oVdiv)+oVdiv.offsetWidth-GetCss(oVdiv,'borderRightWidth')){
		oAdiv.style.left=(pageLeft(oVdiv)+oVdiv.offsetWidth)-GetCss(oVdiv,'borderRightWidth')-oAdiv.offsetWidth+GetCss(oAdiv,'borderRightWidth')+'px';}
		oAdiv.style.top=document.documentElement.scrollTop+eEv.clientY-(oAdiv.offsetHeight/2)+'px';
		if(pageTop(oAdiv)-GetCss(oAdiv,'borderTopWidth')<pageTop(oVdiv)+GetCss(oVdiv,'borderTopWidth')){
			oAdiv.style.top=pageTop(oVdiv)+GetCss(oVdiv,'borderTopWidth')-GetCss(oAdiv,'borderTopWidth')+'px';
		}
		
		if(pageTop(oAdiv)+oAdiv.offsetHeight-GetCss(oAdiv,'borderBottomWidth')>pageTop(oVdiv)+oVdiv.offsetHeight-GetCss(oVdiv,'borderBottomWidth')){oAdiv.style.top=(pageTop(oVdiv)+oVdiv.offsetHeight)-GetCss(oVdiv,'borderBottomWidth')-oAdiv.offsetHeight+GetCss(oAdiv,'borderBottomWidth')+'px';
		}
		
		var nBcL=(pageLeft(oAdiv)+GetCss(oAdiv,'borderLeftWidth')-pageLeft(oVdiv)-GetCss(oVdiv,'borderLeftWidth'))/(oVdiv.offsetWidth-GetCss(oVdiv,'borderLeftWidth')-GetCss(oVdiv,'borderRightWidth'));
		
		var nBcT=(pageTop(oAdiv)-pageTop(oVdiv))/(oVdiv.offsetHeight-GetCss(oVdiv,'borderTopWidth')-GetCss(oVdiv,'borderBottomWidth'));
		oZdiv.scrollLeft=oBimg.offsetWidth*nBcL;
		oZdiv.scrollTop=oBimg.offsetHeight*nBcT;
		}
		function init(nZoom){
			oAdiv.style.width=(oVdiv.offsetWidth-GetCss(oVdiv,'borderLeftWidth')-GetCss(oVdiv,'borderRightWidth'))/nZoom+'px';
			oAdiv.style.height=(oVdiv.offsetHeight-GetCss(oVdiv,'borderTopWidth')-GetCss(oVdiv,'borderBottomWidth'))/nZoom+'px';
			oAdiv.style.display='block';oZdiv.style.position='absolute';
			oZdiv.style.overflow='hidden';
			oZdiv.style.border='#00f solid 2px';
			oZdiv.style.left=pageLeft(oVdiv)+oVdiv.offsetWidth+10+'px';
			oZdiv.style.top=pageTop(oVdiv)+'px';
			oZdiv.style.width=(oAdiv.offsetWidth-2)*nZoom+'px';
			oZdiv.style.height=(oAdiv.offsetHeight-2)*nZoom+'px';
			oZdiv.style.zIndex='1001';
			oZdiv.style.display='block';
			oBimg.style.width=oVdiv.getElementsByTagName('img')[0].offsetWidth*nZoom+'px';
			oBimg.style.height=oVdiv.getElementsByTagName('img')[0].offsetHeight*nZoom+'px';
			if(document.all){
				zoom();
				}
			}
			
			function hide(){
				oAdiv.style.display='none';
				oZdiv.style.display='none';
			}
			function pageLeft(oObj){
				var nPosition=oObj.offsetLeft;
				if(oObj.offsetParent!=null){
					nPosition+=pageLeft(oObj.offsetParent);
				}
				return nPosition;
			}
			
			function pageTop(oObj){
				var nPosition=oObj.offsetTop;
				if(oObj.offsetParent!=null){
					nPosition+=pageTop(oObj.offsetParent);
				}
				return nPosition;
			}
			
			function GetCss(oObj,cAttrib){
				var AttValue=oObj.currentStyle?parseInt(oObj.currentStyle[cAttrib],10):parseInt(document.defaultView.getComputedStyle(oObj,null)[cAttrib],10);
				return isNaN(AttValue)?0:AttValue;}
				
				function GetElement(viewDiv,areaDiv,smallImg,zoomDiv,bigImg){
					oVdiv=document.getElementById(viewDiv);
					oZdiv=document.getElementById(zoomDiv);
					oAdiv=document.getElementById(areaDiv);
					oBimg=document.getElementById(bigImg);
					oSimg=document.getElementById(smallImg);
					oVdiv.style.width=oSimg.offsetWidth+'px';
					oVdiv.style.height=oSimg.offsetHeight+'px';
					oAdiv.style.cssText='display:none;position:absolute;z-index:1000;filter:alpha(opacity=40);opacity:0.4;background-color:#ff0;border:#00f solid 1px;';
					}
					
					if(document.all){
						window.attachEvent('onload',GetElement);
					}else{
						window.addEventListener('load',GetElement,false);
					}
					