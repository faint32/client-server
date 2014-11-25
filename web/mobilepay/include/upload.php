<?php
	function upload($path,$type,$size='2000000',$arrpictype) 
	{	//图片格式
		
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
					alert("文件格式只能为html,htm,pdf,doc,docx,txt,xls,xlsx,mht,rtf");
					</script>');
				
				return $returnvalue = 1;
			}
				
			//判定图片大小
			if($_FILES['upload']['size']> $size)
			{
				echo('<script>
					alert("文件大于2M!");
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
				echo('<script>alert("图片格式只能为jpeg,jpg,gif,pjpeg");</script>');
				$returnvalue = 1;
				
			}
			//判定图片大小
			if($_FILES['upload']['size']> $size)
			{
				echo('<script>alert("图片大于2M!");</script>');
				$returnvalue = 1;
				
			}
		}
		//
		
		if($returnvalue!=1)
		{
		if(!file_exists($path))
		{
			mkdir($path); //生存时间文件
		}
		$exten=explode('.',$_FILES['upload']['name']);//explode()将字符串以某种符号转化为数组
		$picname=date("Y").date("m").date("d").date("H").date("i").date("s").mt_rand(111,999).'.'.$exten[1];//time()返回时间戳， mt_rand()随机数
		move_uploaded_file($_FILES['upload']['tmp_name'],$path.'/'.$picname);//move_uploaded_file()移动图片文件
		$picname=$path."/".$picname;//更新图片名称
		return $picname;
		}else
		{
			return $returnvalue;
		} 
	}
?>