<?
$thismenucode = "2n101";
require ("../include/common.inc.php");

$db  = new DB_test;
$db1 = new DB_test;
$db2 = new DB_test;


$gourl = "moneyqj.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
//echo $toaction;
switch($toaction){
	case "del":   //ɾ��ϸ�ڱ�����
      		  $query="delete from tb_moneyqj where fd_moneyqj_id = '$vid'";
      	    $db->query($query); 
   Header("Location:$gotourl");
	  break;
	case "save":  //��������

	    if(empty($id)){
	    	$query = "insert into tb_moneyqj (
	    	fd_moneyqj_start , fd_moneyqj_end ,fd_moneyqj_type
	    	)values(
	    	'$start' ,  '$end' ,'$moneytype'
	    	)
	    	";
	    	$db->query($query);
	    	
	    }else{
	    	$query = "update tb_moneyqj set fd_moneyqj_start = '$start'  , fd_moneyqj_end = '$end'
	    	          where fd_moneyqj_id = '$id'  ";
	   
	    		$db->query($query);
	    }
	
		Header("Location:$gotourl");
	  break;
	  
	default:
	
	  $action="";
	  break;
}

$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("template","moneyqj.html"); 

if (empty($listid))
{		// ����
   $toaction = "new";
}else{
  
}


if(!empty($vid)){
	  $query = "select* from tb_moneyqj where fd_moneyqj_id = '$vid'";
                             
   $db->query($query);
   if($db->nf()){
   	   $db->next_record();
   	   $id         = $db->f(fd_moneyqj_id);            //id��  
       $start      = $db->f(fd_moneyqj_start)+0;            //���ݱ��
       $end        = $db->f(fd_moneyqj_end)+0;            //���ݱ��
	   $moneytype  = $db->f(fd_moneyqj_type)+0;            //���ݱ��
	  $disabled="disabled=disabled";
   }
	

}

$arr_code=array(0,1,2,3);
$arr_name=array("��ѡ��","���ÿ�����","ת�˻��","�Ŵ�����");
$moneytype=makeselect($arr_name,$moneytype,$arr_code);

$arr_searchname=array("��ʾȫ��","���ÿ�����","ת�˻��","�Ŵ�����");
if($searchcontent)
{
	$searchquery="and fd_moneyqj_type='$searchcontent'";
}
$searchcontent=makeselect($arr_searchname,$searchcontent,$arr_code);
//��ʾ�б�
$t->set_block("template", "prolist"  , "prolists"); 
$query = "select * from tb_moneyqj where 1 $searchquery"; 
$db->query($query);
$count=0;//��¼��
if($db->nf()){
	while($db->next_record()){		
		   $vid       = $db->f(fd_moneyqj_id);
		   $vstart   = $db->f(fd_moneyqj_start)+0;  
		   $vend   = $db->f(fd_moneyqj_end)+0; 
			$vmoneytype  = $db->f(fd_moneyqj_type);  		   
		   $count++;
		   
		   $trid  = "tr".$count;
		   $imgid = "img".$count;
		   
		   if($s==1){            
          $bgcolor="#F1F4F9";  
          $s=0;                
        }else{                
          $bgcolor="#ffffff";  
          $s=1;                
        }   
		
		   $vmoneytype=$arr_name[$vmoneytype];
		   $t->set_var(array("trid"         => $trid          ,
                         "imgid"        => $imgid         ,
                         "vid"          => $vid           ,
                         "vstart"    => $vstart     ,
                         "vend"    => $vend     ,
						 "vmoneytype"    => $vmoneytype     ,
                           "bgcolor"      => $bgcolor,
                        
				          ));
		  $t->parse("prolists", "prolist", true);	
	}
}else{

		  $t->parse("prolists", "", true);	
}      

$t->set_var("searchcontent"       , $searchcontent       );      //����id 
$t->set_var("listid"       , $listid       );      //����id 
$t->set_var("id"           , $id           );      //id 
$t->set_var("end"    , $end           );      //id 
$t->set_var("start"    , $start           ); 
$t->set_var("error"    , $error           );
$t->set_var("moneytype"    , $moneytype           );
$t->set_var("disabled"    , $disabled           );
$t->set_var("toaction"    , $toaction           );  
$t->set_var("gotourl"    , $gotourl           ); 


// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "template");    # ������ҳ��



?>

