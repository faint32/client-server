/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
 {
	 config.font_names='宋体/宋体;黑体/黑体;仿宋/仿宋_GB2312;楷体/楷体_GB2312;隶书/隶书;幼圆/幼圆;微软雅黑/微软雅黑;'+ config.font_names;

     // Define changes to default configuration here. For example:
    // config.language = 'zh-cn';
     config.skin = 'v2';
	 var hosturlpost = window.location.port;
	 var hosturl = window.location.hostname;
	 if(parseInt(hosturlpost)!=80)
	 {
		 hosturl = hosturl+":"+hosturlpost;
	 }
	 config.filebrowserImageUploadUrl = 'http://'+hosturl+'/mssale/msadmin/upload.php?type=img'; 
     config.filebrowserFlashUploadUrl = 'http://'+hosturl+'/mssale/msadmin/upload.php?type=flash'; 

     // config.uiColor = '#AADC6E';
   
    // config.extraPlugins = 'addpic';
 
 };