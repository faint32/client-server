

var MyListTableManager=function(){
	var Me = this;

	Me.MyListTableColl={};
	Me.currentMyListTable=null;



Me.MIN_WIDTH=25;
Me.separateLine=null;
Me.resizeBar=null;
Me.listCol=null;

Me.tableResizing=false;


Me.currentMainTable=null;
Me.currentMainTableBox=null;
Me.currentCol=null;
Me.currentMinWidth=Me.MIN_WIDTH;
Me.currentColLeft=-1;


	Me.addNewMyListTable=function(mid){
		Me.addMyListTable(new MyListTable($(mid),mid));
	};

	Me.addMyListTable=function(mylisttable){
		mylisttable.initMe(Me);
		Me.MyListTableColl[mylisttable.MyListTableId]=mylisttable;
		if (Me.currentMyListTable==null){
			Me.currentMyListTable=mylisttable;
			Me.currentMainTable=Me.currentMyListTable.mainTable;
		}

	};
	
	Me.setCurrentMyListTable=function(mid){
		Me.currentMyListTable=Me.MyListTableColl[mid];
		Me.currentMainTable=Me.currentMyListTable.mainTable;
	};

	Me.doDeal=function(){
		if (Me.tableResizing) {	return false;	}
		if (Me.currentCol!=null) {	return false;	}
	};

	Me.doResizeStart=function(){
		var barObj=event.srcElement;

		var islock=barObj.parentNode.getAttribute("isLock");
			if ( isValid(islock) && (islock=="true" || islock=="1") ){
				return;
			}

		var minWidth=barObj.parentNode.getAttribute("minWidth");

		if ( isValid(minWidth) && parseInt(minWidth)>0){
			Me.currentMinWidth=minWidth;
		}


			/*================================================*/
			Me.currentCol=barObj.parentNode;
			Me.currentMainTable=Me.currentCol.parentNode.parentNode.parentNode;
			Me.currentMainTableBox=Me.currentMainTable.parentNode;
			Me.currentColLeft=getPosLeft(Me.currentCol);
			/*================================================*/

			document.body.style.cursor="E-resize";

		Me.separateLine.style.height=Me.currentMainTable.offsetHeight;
		Me.separateLine.style.top=getPosTop(Me.currentMainTable);
		/*	Me.separateLine.style.left=document.body.scrollLeft+event.x;	*/
		Me.separateLine.style.left=(getPosLeft(Me.currentCol)+Me.currentCol.offsetWidth)+"px";

		Me.separateLine.style.display="block";
		Me.tableResizing=true;
	};

	Me.doResize=function(){

		if (!Me.tableResizing || Me.currentCol==null)	{ return;	}
			var cColRight=Me.currentColLeft+Me.currentCol.offsetWidth;
			var dX=document.body.scrollLeft+event.x-cColRight;
			var newWidth=Me.currentCol.offsetWidth+dX;

			if ( dX>0 || newWidth>=Me.currentMinWidth ){

				var newTWidth=Me.currentMainTable.offsetWidth+dX+"px";
				Me.currentMainTable.style.width=newTWidth;

				Me.currentCol.style.width=newWidth+"px";
					
				if (Me.currentMyListTable.hasTableBox && Me.currentMyListTable.changeTableBox){
					Me.currentMainTableBox.style.width=newTWidth;
				}

				
			}
			Me.separateLine.style.left=(Me.currentColLeft+Me.currentCol.offsetWidth)+"px";
	};

	Me.doResizeEnd=function(){
		barObj=event.srcElement;
			Me.tableResizing=false;
			Me.currentCol=null;
			Me.currentColLeft=-1;
			/*
			mainTable=null;
			mainTableBox=null;
			*/

		Me.currentMinWidth=Me.currentMyListTable.MIN_WIDTH;
		Me.separateLine.style.display="none";
		document.body.style.cursor="auto";
	};


	Me.showAllTable=function(){
		Me.currentMainTable.style.tableLayout="auto";
	};

	Me.showPartTable=function(){
		Me.currentMainTable.style.tableLayout="fixed";
		cols=Me.currentMyListTable.tableColsGroup.childNodes;
		for (var k=0;k<cols.length;k++){
			cols[k].style.display="block";
		}
	};



	Me.hideCol=function(colidx){
		obj=event.srcElement;
		if (obj.tagName=="TD"){
			if (!isValid(colidx)){
				colidx=obj.cellIndex;
			}
		}else{
			obj=obj.parentElement;
			if (!isValid(colidx)){
				colidx=obj.cellIndex;
			}
		}
		col=Me.currentMyListTable.tableColsGroup.childNodes[colidx];

		/*	应该加入对lock功能的支持 计划中.....	*/

		if (Me.currentMainTable.style.width=="" || Me.currentMainTable.style.width==null || parseInt(Me.currentMainTable.style.width)<=2 ){
			Me.currentMainTable.style.width=Me.currentMainTable.offsetWidth+"px";
		}
		orgW=parseInt(Me.currentMainTable.style.width);
		orgH=Me.currentMainTable.offsetHeight;
		if (col.style.display=="none"){
			col.style.display="block";
		}else{
			col.style.display="none";
		}
		Me.currentMainTable.style.width=orgW+"px";
		Me.currentMainTable.style.height=orgH+"px";

		/*
		tableObj.style.borderCollapse="separate";
		tableObj.style.borderCollapse="collapse";
		tableObj.refresh();
		*/

	};

/************ 更好的页面排序 计划中.... ****************
下面代码来自 crm 1.5 相应模块 主要缺点：1只用于xml+xslt 2 重新覆盖了整个列表table( 这样不太好:( )

	Me.sort=function(divName,orderVal) { 
		
		var stylesheet=document.XSLDocument; 
		var source=document.XMLDocument; 
		var sortField=document.XSLDocument.selectSingleNode("//xsl:sort/@select");
		var sortOrder = document.XSLDocument.selectSingleNode("//xsl:sort/@order");

		if (sortField.value==orderVal)//仅改变排列顺序
		{
			if (sortOrder.value=='ascending')
				sortOrder.value='descending';
			else
				sortOrder.value='ascending';
		}
		else
		{
			sortField.value=orderVal;
			sortOrder.value='ascending';
		}

		eval(divName).innerHTML=source.documentElement.transformNode(stylesheet); 
	}
********************************/

Me.reset=function(){
	for(var key in Me.MyListTableColl){
		var listTable=Me.MyListTableColl[key];
		listTable.reset(Me);
	}
};

Me.initDefaultStyle=function(){

	Me.separateLine.style.position="absolute";
	Me.separateLine.style.zIndex="10";
	Me.separateLine.style.left="0px";
	Me.separateLine.style.top="0px";
	Me.separateLine.style.width="1px";
	Me.separateLine.style.height="10px";
	Me.separateLine.style.backgroundColor="#D3E1FF";
	Me.separateLine.style.display="none";


	Me.resizeBar.style.styleFloat ="right";
	Me.resizeBar.style.marginRight="0px";
	Me.resizeBar.style.paddingRight="0px";
	Me.resizeBar.style.width="2px";
	Me.resizeBar.style.height="100%";
	Me.resizeBar.style.cursor="E-resize";
	Me.resizeBar.style.backgroundColor="#D3E1FF";

};

	initMe();

	function initMe(){
		Me.separateLine=document.createElement("DIV");
		Me.separateLine.className="separateLine";

		Me.resizeBar=document.createElement("SPAN");
		Me.resizeBar.className="resizebar";
		Me.resizeBar.title="左右拖动调整大小";


		Me.listCol=document.createElement("COL");
		Me.listCol.style.display="block";

		/*	如果以下document 的事件已经绑定到其他函数上 请自行写包装方法	*/
		/*	想办法找更好的解决方案 计划中............	*/
		document.onmousemove=Me.doResize;
		document.onmouseup=Me.doResizeEnd;
		document.onselectstart=Me.doDeal;
		document.body.appendChild(Me.separateLine);
	}




};


/*================================================*/

var MyListTable=function(tableObj,mid){
	var Me = this;
	Me.MyListTableId=mid;
	Me.MIN_WIDTH=-1;
	Me.mainTable=tableObj;

	Me.mainTableBox=Me.mainTable.parentNode;
	Me.mainTableBoxWidthOrg=-1;
	Me.hasTableBox=false;

	if (isValid(Me.mainTableBox)){
		Me.mainTableBoxWidthOrg=Me.mainTableBox.offsetWidth;
		Me.hasTableBox=true;
	}

	Me.changeTableBox=true;
	
	Me.tableColsGroup=null;

	Me.tableLH=null;
	Me.colNum=null;

	
	
	Me.reset=function(myListTableManager){

		Me.mainTable=$(Me.MyListTableId);
		Me.mainTableBox=Me.mainTable.parentNode;
		if (isValid(Me.mainTableBox) && Me.mainTableBoxWidthOrg>=0){
			Me.mainTableBox.style.width= Me.mainTableBoxWidthOrg+"px";
			Me.hasTableBox=true;
		}
		

		Me.tableColsGroup=null;

		/*
		Me.tableLH=null;
		Me.colNum=null;

		Me.initMe(myListTableManager);

		Me.mainTable.refresh();
		*/
	};

	Me.initMe=function(myListTableManager){
		var tableHeadIndex_Lv1=0;
		var tableHeadIndex_Lv2=0;

		if ( !isValid(Me.tableLH)) {
			Me.tableLH=Me.mainTable.childNodes[tableHeadIndex_Lv1].childNodes[tableHeadIndex_Lv2];
		}

		if ( !isValid(Me.colNum)) {
		
			Me.colNum=Me.tableLH.childNodes.length - 1;
		}
		
		var minWidth=Me.mainTable.getAttribute("minColWidth");

		if ( isValid(minWidth) && parseInt(minWidth)>0){
			Me.MIN_WIDTH=minWidth;
		}else if (Me.MIN_WIDTH<=0){
			Me.MIN_WIDTH=myListTableManager.MIN_WIDTH;
		}


		

		for (var cn=0;cn<Me.colNum ;cn++ ){
			var rb=myListTableManager.resizeBar.cloneNode(true);
			rb.onmousedown=myListTableManager.doResizeStart;
			rb.onmouseup=myListTableManager.doResizeEnd;
			Me.tableLH.childNodes[cn].insertAdjacentElement("afterBegin",rb);
			/*
			Me.tableLH.childNodes[cn].innerText;
			Me.tableLH.childNodes[cn].appendChild(rb);
			*/
		}


		Me.tableColsGroup=document.createElement("COLGROUP");
		for (var cn=0;cn<Me.colNum ;cn++ ){
			Me.tableColsGroup.appendChild(myListTableManager.listCol.cloneNode(true));
		}

		Me.mainTable.style.tableLayout="fixed";
		Me.mainTable.insertAdjacentElement("afterBegin",Me.tableColsGroup);
		/*
		Me.mainTable.appendChild(Me.tableColsGroup);
		*/

	};

};



function InitAjax()
{
　var ajax=false; 
　try { 
　　ajax = new ActiveXObject("Msxml2.XMLHTTP"); 
　} catch (e) { 
　　try { 
　　　ajax = new ActiveXObject("Microsoft.XMLHTTP"); 
　　} catch (E) { 
　　　ajax = false; 
　　} 
　} 
　if (!ajax && typeof XMLHttpRequest!='undefined') { 
　　ajax = new XMLHttpRequest(); 
　} 
　return ajax;
}
function pressbrowse(value)
{
if(event.keyCode==13) 
{
 
  document.all.save2.click();
 
}
}
