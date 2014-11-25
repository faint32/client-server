<?

/*
*
*	文件操作
*
*/

Class fileOperation{
	
	var $yearPath; //年文件夹
	var $monthPath; //月文件夹
	var $dayPath;  //天文件夹
	var $filePath; //路径
	var $filename = ''; //文件名
	var $hand; //指针
	var $fileconten="";//文件内容
	
//初始文件
function fileOperation($name=null,$isnew=1,$filename='',$root='../logs/'){
	
		if($isnew == 1){
			//初始目录文件夹
			$times = time();
			$this->yearPath = date("Y",$times);
			$this->monthPath = date("m",$times);
			$this->dayPath = date("d",$times);
			$this->filePath = $root.$this->yearPath.$this->monthPath.$this->dayPath;
			//初始聊天记录文件
			$this->filename = $this->filePath.$name.'.txt';			
		}else{
			$this->filename = $filename;
		}
}
	
	//创建文件
	function createFile(){
	
/* 		//创建目录文件夹
		if(!file_exists($this->filePath)){
			$this->create_folders($this->filePath);
			chmod($this->filePath, 0777);
		} */
		//创建日志文件
		if(!file_exists($this->filename)){
			$handle = fopen($this->filename,'a');
			if($handle){
				fwrite($handle,'');
			}
			fclose($handle);
			chmod($this->filename, 0777);
		}


	}	
	//读取文件
	function readFile($file,$hand=0){
	
		if(is_readable($file)){
			if(!($handle = fopen($file,'r'))){
				return false;
			}else{
				fseek($handle,$hand);
				$str = fread($handle,filesize($file));
				fclose($handle);
				return $str;
			}
		}
		return false;
	
	}
	
	//写入文件
	function writeFile(){

		if(is_writable($this->filename)){
			if(!($handle = fopen($this->filename,'a'))){
				return false;
			}
			
			if(fwrite($handle,$this->fileconten) === false){
				return false;
				}			
			fclose($handle);
			return true;
		}
		return false;
		
	}
	
	
	
	//创建目录
	function create_folders($path){

		return is_dir($path) or ($this->create_folders(dirname($path)) and mkdir($path, 0777)); 

	}

}


?>