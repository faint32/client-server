<?

/*
*
*	�ļ�����
*
*/

Class fileOperation{
	
	var $yearPath; //���ļ���
	var $monthPath; //���ļ���
	var $dayPath;  //���ļ���
	var $filePath; //·��
	var $filename = ''; //�ļ���
	var $hand; //ָ��
	var $fileconten="";//�ļ�����
	
//��ʼ�ļ�
function fileOperation($name=null,$isnew=1,$filename='',$root='../logs/'){
	
		if($isnew == 1){
			//��ʼĿ¼�ļ���
			$times = time();
			$this->yearPath = date("Y",$times);
			$this->monthPath = date("m",$times);
			$this->dayPath = date("d",$times);
			$this->filePath = $root.$this->yearPath.$this->monthPath.$this->dayPath;
			//��ʼ�����¼�ļ�
			$this->filename = $this->filePath.$name.'.txt';			
		}else{
			$this->filename = $filename;
		}
}
	
	//�����ļ�
	function createFile(){
	
/* 		//����Ŀ¼�ļ���
		if(!file_exists($this->filePath)){
			$this->create_folders($this->filePath);
			chmod($this->filePath, 0777);
		} */
		//������־�ļ�
		if(!file_exists($this->filename)){
			$handle = fopen($this->filename,'a');
			if($handle){
				fwrite($handle,'');
			}
			fclose($handle);
			chmod($this->filename, 0777);
		}


	}	
	//��ȡ�ļ�
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
	
	//д���ļ�
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
	
	
	
	//����Ŀ¼
	function create_folders($path){

		return is_dir($path) or ($this->create_folders(dirname($path)) and mkdir($path, 0777)); 

	}

}


?>