<?   
require("../include/common.inc.php");
require("../include/json.php");
$db = new db_test;
$db1= new db_test;

header('Content-Type:text/html;charset=GB2312'); 


$value = strtolower($value);

$arr_chars = preg_split('//', $value, -1, PREG_SPLIT_NO_EMPTY);
for($i=0;$i<count($arr_chars);$i++){
	if(ereg("[0-9]",$arr_chars[$i])){
	  $str_int .= $arr_chars[$i];
  }
}

for($i=0;$i<count($arr_chars);$i++){
	if(ereg("[a-z]",$arr_chars[$i])){
	  $strchars .= $arr_chars[$i];
  }
}

$query = "select * from tb_supplier where fd_supp_no like '%".$str_int."%' and fd_supp_no like '%".$strchars."%' ";
$db->query($query);

$count=$db->nf();

if($count==0){
	$returnflag = 0;
	$suppid   = "";     //��Ӧ��id��
  $suppname = "";     //��Ӧ������
  $suppno  = "";      //��Ӧ�̱��
  $suppcode = "";        //��Ӧ�̴���
}elseif($count==1){
  $query = "select * from tb_supplier where fd_supp_no like '%".$str_int."%' and fd_supp_no like '%".$strchars."%'";
  $db->query($query);
  if($db->nf()){
  	$db->next_record();
    $suppid   = $db->f(fd_supp_id);          //��Ӧ��id��
    $suppname = $db->f(fd_supp_allname);     //��Ӧ������
    $suppno   = $db->f(fd_supp_no);          //��Ӧ�̱��
    $suppcode = $db->f(fd_supp_code);        //��Ӧ�̴���
    $returnflag = 1;
  }
  
}else{
  $returnflag = 2;
  $suppid   = "";    //��Ӧ��id��
  $suppname = "";    //��Ӧ������
  $suppno  = "";     //��Ӧ�̱��
  $suppcode = "";    //��Ӧ�̴���
}

echo $returnflag."&".$suppid."&".$suppno."&".$suppname."&".$suppcode;
?>