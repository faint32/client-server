<?PHP
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=utf-8'); 
require ("../include/common.inc.php");
require ("../class/tfbrequest.class.php");

require ("../class/tfbxmlResponse.class.php");

$db  = new DB_test;	
$t = new Template(".","keep");
$t->set_file("demo"       , "entcy.html");   //���


//echo  "test".$interface_id."-------";
$interface_id = $interface_id;
//echo $interface_id;
if($interface_id<1){$interface_id="1";}
$query = "select * from web_test_interface where fd_interface_id ='$interface_id'";
$db->query($query);
if($db->nf()){
	
		while($db->next_record()){
		
			//$interface_id  = $db->f(fd_interface_id);
			$interface_no  = $db->f(fd_interface_no);
			$interface_name  = $db->f(fd_interface_name);
			$interface_url  = $db->f(fd_interface_apinamefunc);
			$apiname  = g2u($db->f(fd_interface_demo));
		}
	}
$checkenc = "checked";
	$checkdec ="";
switch($action)
{
 case "jisuan":
 if($type=='enc')
 {
    $checkenc = "checked";
	$checkdec ="";
	$TfbAuthRequest = new TfbAuthRequest();
    $resultcontent = $TfbAuthRequest->desEncryptStr($TestCode);
	//echo var_dump($TestCode);
 }
 else if($type=='dec')
 {
 	//echo "dec";
    $checkenc = "";
	$checkdec ="checked";
    $TfbAuthRequest = new TfbAuthRequest();
    $apiname = $TfbAuthRequest->DesDecryptStr($resultcontent);
     
 }
 $action="";
 break;
}
$query = "select * from web_test_interface where fd_interface_active = 1 order by fd_interface_sortorder,fd_interface_no asc";         
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$arr_dept_easyname[]  = $db->f(fd_interface_no).g2u($db->f(fd_interface_name));
		$arr_dept_id[]        = $db->f(fd_interface_id);
	}
} 
// echo $interface_id;

$interface_id= makeselect($arr_dept_easyname,$interface_id,$arr_dept_id);
	//���ѡ��˵��ĺ���
function makeselect($arritem,$hadselected,$arry){ 
  for($i=0;$i<count($arritem);$i++){
     if ($hadselected ==  $arry[$i]) {
       	 $x .= "<option selected value='$arry[$i]'>".$arritem[$i]."</option>" ;
     }else{
       	 $x .= "<option value='$arry[$i]'>".$arritem[$i]."</option>"  ;	   	
     }
   } 
   return $x ; 
}


$t->set_var('checkenc',$checkenc);
$t->set_var('checkdec',$checkdec);
$t->set_var('interface_id',$interface_id);
$t->set_var('resultcontent',$resultcontent);
$t->set_var('apiname',$apiname);
$t->set_var('shopinvoice_kdno',$shopinvoice_kdno);
$t->set_var('shopinvoice_kdname',$shopinvoice_kdname);
$t->set_var('sel_checkbox',$sel_checkbox);
$t->pparse("out", "demo");    # ������ҳ��

?>