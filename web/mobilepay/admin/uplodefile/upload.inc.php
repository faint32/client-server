<?php
	function upload($path,$type,$size='2000000',$arrpictype) 
	{	//ͼƬ��ʽ
		
		if($arrpictype=="1")
		{
		
				$arr=array(
				'text/html',
				'text/html',
				'application/pdf',
				'application/msword',
				'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				'text/plain',
				'application/vnd.ms-excel',
				'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
				'application/mht',
				'application/rtf'
			);
			
			if(! in_array($type,$arr))
			{
				
				echo('<script>
					alert("�ļ���ʽֻ��Ϊhtml,htm,pdf,doc,docx,txt,xls,xlsx,mht,rtf");
					</script>');
				
				return $returnvalue = 1;
			}
				
			//�ж�ͼƬ��С
			if($_FILES['upload']['size']> $size)
			{
				echo('<script>
					alert("�ļ�����2M!");
					</script>');
				$returnvalue = 1;	
			}
		}else{
			$arr=array(
				'image/jpeg',
				'image/jpg',
				'image/gif',
				'image/pjpeg'
			);
			
			if(! in_array($type,$arr))
			{
				echo('<script>alert("ͼƬ��ʽֻ��Ϊjpeg,jpg,gif,pjpeg");</script>');
				$returnvalue = 1;
				
			}
			//�ж�ͼƬ��С
			if($_FILES['upload']['size']> $size)
			{
				echo('<script>alert("ͼƬ����2M!");</script>');
				$returnvalue = 1;
				
			}
		}
		//
		
		if($returnvalue!=1)
		{
		if(!file_exists($path))
		{
			mkdir($path); //����ʱ���ļ�
		}
		$exten=explode('.',$_FILES['upload']['name']);//explode()���ַ�����ĳ�ַ���ת��Ϊ����
		$picname=date("Y").date("m").date("d").date("H").date("i").date("s").mt_rand(111,999).'.'.$exten[1];//time()����ʱ����� mt_rand()�����
		move_uploaded_file($_FILES['upload']['tmp_name'],$path.'/'.$picname);//move_uploaded_file()�ƶ�ͼƬ�ļ�
		$picname=$path."/".$picname;//����ͼƬ����
		return $picname;
		}else
		{
			return $returnvalue;
		} 
	}
?>