<?
require ("../include/common.inc.php");

$db = new DB_test;
$t = new Template(".", "keep"); //调用一个模版


            
/*			$apmgkey =  "fd_agpm_no".$key;
				$arr_insert[][$apmgkey] = "77777";
		
			foreach($arr_insert as $dataArray)
			{
			$db->insert('tb_agentpaymoneylist',$dataArray);
			}
			exit;
*/
$query="select 
	fd_couponsale_bkntno as bkntno ,
	fd_couponsale_money as paymoney,
	fd_couponsale_payrq as payrq ,
	fd_couponsale_paycardid as paycardid,
	fd_couponsale_authorid as authorid
	from tb_couponsale";

$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
		
		$bkntno  = $db->f(bkntno);
		$arr_coupval[$bkntno]['paymoney'] = $db->f(paymoney);
		$arr_coupval[$bkntno]['payrq'] = $db->f(payrq);
		$arr_coupval[$bkntno]['paycardid'] = $db->f(paycardid);
		$arr_coupval[$bkntno]['authorid'] = $db->f(authorid);
		$arr_coupval[$bkntno]['bkntno'] = $db->f(bkntno);
		$arr_coupbkntno[] = $db->f(bkntno);
	}
}

/*$query="select fd_couponsale_bkntno as bkntno ,fd_couponsale_money as paymoney,fd_couponsale_payrq as payrq  from tb_couponsale limit 0,10 ";
$arr_all =$db->get_all($query);


foreach ($arr_all as $key =>  $val )
{
	
   $arr_newall[$arr_all[$key]['bkntno']] = $val;
		
	
}*/


foreach ($arr_coupbkntno as  $val )
{
        
		if(checkagpminfo::checkagpmbkntno($val))
		{
					
					foreach($arr_coupval[$val] as $key => $value)
					{
							
						$query = "update tb_agentpaymoneylist set fd_agpm_".$key." = '$value' where fd_agpm_bkntno = '$val'";
						$db->query($query);
						//echo $query."</br>";
					}
		
		}else{
			foreach($arr_coupval[$val] as $key => $value)
			{
				$apmgkey =  "fd_agpm_".$key;
				$arr_insert[$i][$apmgkey] = $value;
			}
			foreach($arr_insert as $dataArray)
			{
			$db->insert('tb_agentpaymoneylist',$dataArray);
			}
			//$query = "insert into  tb_agentpaymoneylist set fd_agpm_".$key." = '$value'";
			//$db->query($query);
		}
	 $i++;
	
}
class checkagpminfo
{
	
	public static function checkagpmbkntno($bkntno)
	{
		$db = new DB_test;
		$query="select fd_agpm_bkntno as bkntno  from tb_agentpaymoneylist where fd_agpm_bkntno = '$bkntno'";
		//$query="select fd_agpm_bkntno as bkntno  from tb_agentpaymoneylist where fd_agpm_bkntno = '201308212125170033372'";
		$db->query($query);
		if($db->nf()){
			return "1";
			}else{return "0";}
		//return $db->execute($query); // 0 1 
		
	}
	
}
	

// 判断权限 
//include ("../include/checkqx.inc.php");

//$//t->set_var("skin", $loginskin);
//$//t->pparse("out", "setmealmanagement"); # 最后输出页面
?>