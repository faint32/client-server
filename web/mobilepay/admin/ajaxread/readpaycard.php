<?   
require("../include/common.inc.php");
header('Content-Type:text/html;charset=GB2312'); 
$db=new db_test;

if($tmpid)
{
	$query="select * from tb_salelist_tmp where fd_tmpsale_id='$tmpid'";
		$db->query($query);
		if($db->nf())
		{
			while($db->next_record())
			{
				$tmp_paycardid=$db->f(fd_tmpsale_paycardid);
			}
		}
	
	if($ischoose)
	{
		
		
		if($tmp_paycardid){$tmp_paycardid .=",".$paycardid;}else{$tmp_paycardid .=$paycardid;}
		
		
		$arr_tmp_paycardid=explode(",",$tmp_paycardid);
		
		$arr_tmp_paycardid=array_unique($arr_tmp_paycardid);
		
		$tmp_paycardid=implode(",",$arr_tmp_paycardid);
		
	}else{
		
		
		
		$arr_tmp_paycardid=explode(",",$tmp_paycardid);
		if($type=="one")
		{
				foreach($arr_tmp_paycardid as $value)
			{
				if($paycardid!=$value)
				{
					$arr_newtmp_paycardid[]=$value;
				}
			}
			$tmp_paycardid=implode(",",$arr_newtmp_paycardid);
		}
		if($type=="all")
		{
			$arr_paycardid=explode(",",$paycardid);

			//找出不同的值
			$arr_newtmp_paycardid=array_merge(array_diff($arr_paycardid,$arr_tmp_paycardid),array_diff($arr_tmp_paycardid,$arr_paycardid));		
			$tmp_paycardid=implode(",",$arr_newtmp_paycardid);
		}
		
		
	}
	$query="update tb_salelist_tmp set fd_tmpsale_paycardid='$tmp_paycardid' where fd_tmpsale_id='$tmpid'";
	$db->query($query);
}else{
		
		$query="insert into tb_salelist_tmp(fd_tmpsale_paycardid)values('$paycardid') ";
		$db->query($query);
		$tmpid = $db->insert_id();    //取出刚插入的记录的主关键值的id
		
		
}

$query="select * from tb_salelist_tmp where fd_tmpsale_id='$tmpid'";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
		$tmp_paycardid=$db->f(fd_tmpsale_paycardid);
		
	}
}
if($tmp_paycardid)
{
	$arr_count=explode(",",$tmp_paycardid);
	$count=count($arr_count);
}else{
	$count=0;
}
$g_tmp_paycardid=$tmpid;
echo $tmpid."@@".$count."@@".$tmp_paycardid;


?>