<?

//$thismenucode = "9101"; 
require ("../include/common.inc.php");
$db = new DB_test;
$gourl = "tb_menu_b.php" ;
$gotourl = $gourl.$tempurl ;



switch ($action){
    case "delete":   // ��¼ɾ��
       $query = "delete from tb_menu where fd_menu_id = '$id'";
       $db->query($query);
       Header("Location: $gotourl");       
	break;
    case "new":   // ���б༭��Ҫ�ṩ����
       $query = "select * from tb_menu where fd_menu_name ='$name' ";
	     $db->query($query);
       if($db->nf()>0){
	         $error = "�˲˵����Ѿ����ڣ����������룡";
       }else{
           $query="INSERT INTO tb_menu(
 		               fd_menu_name      , fd_menu_code       , fd_menu_upcode ,
 		               fd_menu_jpg      , fd_menu_hz , fd_menu_url ,
 		               fd_menu_sno    , fd_menu_active   
 		               )values(
  		             '$name'     ,  '$code'     , '$upcode' , 
  		             '$jpg'     ,  '$hz' , '$url'  ,
  		             '$sno'   ,  '$active'     
  		             )";
	   $db->query($query);
	   Header("Location: $gotourl");	
	}
	break;
    case "edit":   // �޸ļ�¼
    	   $query = "update tb_menu set
 	    	          fd_menu_name = '$name'  , fd_menu_upcode ='$upcode'   ,
 	    	          fd_menu_jpg  ='$jpg'  , fd_menu_url = '$url'   ,
 	    	          fd_menu_sno='$sno' , fd_menu_active ='$chgpass' ,
 	    	          fd_menu_hz = '$hz'  ,fd_menu_code = '$code'
  	    	        where fd_menu_id='$id' ";
  	     $db->query($query);
	       Header("Location: $gotourl");
    	   break;   
    default:
        break;
}

$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("menu","menu.html"); 


if (!isset($id)){		// ����
   $sno = 0 ;
   $chgpass = 1 ;
   $checkterm = 1;
   $action = "new";
   
}else{			// �༭
   $query = "select * from tb_menu 
             where fd_menu_id = '$id'";
   $db->query($query);
	if($db->nf()){                                //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
	   $db->next_record();                           //��ȡ��¼���� 
       	   $name         = $db->f(fd_menu_name) ;            //�û����� 
       	   $code       = $db->f(fd_menu_code);           //�˵����� 
       	   $upcode     = $db->f(fd_menu_upcode) ;           //�ϼ��˵�����
       	   $jpg         = $db->f(fd_menu_jpg) ;            //�˵�ͼƬ·��      
       	   $sno       = $db->f(fd_menu_sno) ;          //�˵�sno      	       	   
       	   $hz     = $db->f(fd_menu_hz);       //�˵�hz               
       	   $active      = $db->f(fd_menu_active);          //�˵��ļ���״̬
       	   $url        = $db->f(fd_menu_url);          //�˵���url
       	   $action = "edit";
       	}
}

 //����chgpassѡ��
       	   $arr_active = array("��","��") ;
       	   $arr_valueactive = array("1","0") ;
       	   $active = makeselect($arr_active,$active,$arr_valueactive);
		           	   
$t->set_var("id",$id); 
$t->set_var("name",$name);
$t->set_var("code",$code);
$t->set_var("upcode",$upcode); 
$t->set_var("jpg",$jpg);
$t->set_var("sno",$sno);                      
$t->set_var("url",$url);                     
$t->set_var("hz",$hz);
$t->set_var("active",$active);
$t->set_var("action",$action);
$t->set_var("gotourl",$gotourl);  // ת�õĵ�ַ

// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);
$t->pparse("out", "menu");    # ������ҳ��



?>