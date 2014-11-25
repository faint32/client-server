<?php
header('Content-Type:text/html;charset=utf-8');
/**********************
*文件管理类
*开发：抄袭版
*交流qq：297495363
*可以加我QQ交流
************************/
error_reporting(E_ERROR);
  class files{

	  var $extend = array();

	  function __construct(){
	  
	      $this->extend = array('txt','php','js','html','htm');
	  
	  }
  
      function dirbox($url){
	  
	      $handler = opendir($url);
          while( ($filename = readdir($handler)) !== false ) 
          {
     
              if($filename != "." && $filename != ".." && $filename != "Thumbs.db" && $filename != $this->name($_SERVER['PHP_SELF']))

              {    
				  
				   if($this->type($url."/".$filename)=='dir'){

                       $left[]  = $filename;

				   }else{
				   
				       $right[] = $filename;
				   
				   }

              }

          }

          closedir($handler);

		  $content = @array_merge($left,$right);

		  return $content;
	  
	  }

	  function showfile($url){
	  
	      return @file_get_contents($url);
	  
	  }

	  function wfile($file,$content){
	  
	      return file_put_contents($file,$content);
	  
	  }

	  function type($file){
	  
	      return filetype($file);
	  
	  }

	  function ftime($file){
	  
	      return date("Y-m-d H:i:s",filemtime($file));
	   
	  }

	  function isarray($array){
	  
	      if(is_array($array)){
		  
		      return implode($array);
		  
		  }else{
		  
		      return $array;
		  
		  }
	  
	  }

	  function name($url){

		  $url1 = explode("/",$url);
	  
	      return array_pop($url1);
	  
	  }

	  function back($url){

		  $url1 = explode("/",$url);

		  if(preg_match("/",$url)){

		      $back = preg_replace("/\/".$url1[(count($url1)-1)]."$/i","",$url);		  
	  
	          return "<a href='?dir=".$back."'>返回上一级目录</a>";

		  }else{
		  
		      return "";
		  
		  }
	  
	  }

	  function del(){
	  
	      if(isset($_GET['del']) && !empty($_GET['del'])){
		      
			  if(file_exists($_GET['del'])){

				  if($this->type($_GET['del'])=='file'){

		              @unlink($_GET['del']);

				  }else{
				  
				      @rmdir($_GET['del']);
				  
				  }

			  }
		  
		  }
	  
	  }

	  function post(){
	  
	      if(isset($_POST['submit'])){
		  
		      $url     = $_POST['url'];
			  $content = $_POST['content'];

			  $this->wfile($url,$content);

			  echo "<script>alert('编缉成功！');location.href='".$_SERVER['HTTP_REFERER']."'</script>";
		  
		  }
	  
	  }

	  function fdir($url){
	  
	      if($this->type($url)=='file'){

			  $tend = pathinfo($url);

			  if(in_array($tend["extension"],$this->extend)){
		  
		          return "<a href='?edit=".$url."'>编缉</a>";

			  }else{
			  
			      return "<a href='".$url."' target='_blank'>查看</a>";
			  
			  }
		  
		  }else{
		  
		      return "<a href='?dir=".$url."'>打开</a>";
		  
		  }
	  
	  }

	  function table($res,$url=''){
	  
	      return '<div style="width:80%;line-height:30px;font-size:12px;">
                    <ul style="padding:0;margin:0;list-style:none;">
					  <li style="list-style:none;line-height:30px;">'.$this->back($url).'</li>
                      <li style="list-style:none;">
	                    <b style="float:left;width:5%;text-align:center;">序号</b>
	                    <span style="float:left;width:27%;text-align:center;">文件名称</span>
	                    <span style="float:left;width:27%;text-align:center;">文件地址</span>
	                    <span style="float:left;width:27%;text-align:center;">修改时间</span>
	                    <font style="float:left;width:13%;text-align:center;">操作</font>
	                  </li>
					  '.$res.'
                    </ul>
                  </div>';
	  
	  }

	  function edit($content,$url=''){
	  
	      return '<FORM METHOD=POST ACTION="">'.$this->back($url).'<br /><INPUT TYPE="hidden" NAME="url" value="'.$url.'"><TEXTAREA NAME="content" style="width:70%;height:500px;">'.$content.'</TEXTAREA><br /><INPUT TYPE="submit" name="submit" value="修改"></FORM>';
	  
	  }

	  function show($array,$url){
	  
	      for($i=0;$i<count($array);$i++){
		  
		      $imp[] = '<li style="list-style:none;">
	                    <b style="float:left;width:5%;text-align:center;">'.($i+1).'</b>
	                    <span style="float:left;width:27%;text-align:center;">'.$array[$i].'</span>
	                    <span style="float:left;width:27%;text-align:center;">---------</span>
	                    <span style="float:left;width:27%;text-align:center;">'.$this->ftime($url."/".$array[$i]).'</span>
	                    <font style="float:left;width:13%;text-align:center;">'.$this->fdir($url."/".$array[$i]).'｜<a href="?del='.$url."/".$array[$i].'">删除</a></font>
	                    </li>';
		  
		  }

		  return $this->isarray($imp);
	  
	  }

	  function content($url){

		  $this->del();
		  $this->post();

          if(isset($_GET['dir']) && !empty($_GET['dir'])){
		  
		      $file = $this->dirbox($_GET['dir']);
			  $content = $this->table($this->show($file,$_GET['dir']),$_GET['dir']);
		  
		  }elseif(isset($_GET['edit']) && !empty($_GET['edit'])){
		  
		      $content = $this->edit($this->showfile($_GET['edit']),$_GET['edit']);
		  
		  }else{
		  
		    $file = $this->dirbox($url);
		   	rsort($file);
			  $content = $this->table($this->show($file,$url));
		  
		  }	  

		  print $content;
	  
	  }
	  function sortfile($files)
	  {
	  	
 			asort($files);//按时间排序
 			
 			return $files;

	  }
  
  }


$c = new files();
$c->content($_SERVER['DOCUMENT_ROOT']."/tfb_log/");
?>

