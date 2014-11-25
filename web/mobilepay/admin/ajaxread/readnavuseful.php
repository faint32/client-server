<?   
require("../include/common.inc.php");
header('Content-Type:text/html;charset=GB2312'); 
$db=new db_test;
if($procaid<>"")
{
$arr_tradid = explode(",",$procaid);
for($i=0;$i<count($arr_tradid);$i++)
{   
$arr_procaid[$arr_tradid[$i]] =$arr_tradid[$i];
}
}
$query = "select * from tb_procatalog order by fd_proca_catname";
   $db->query($query);
   if($db->nf()){
   	   while($db->next_record())
	   {
   	   $id        = $db->f(fd_proca_id);                 //idºÅ  
       $name = $db->f(fd_proca_catname);      //µ¥¾Ý±àºÅ
     
		if($id==$arr_procaid[$id])
		{
			$checked = "checked";
			$otherval.='<INPUT title='.$name.' onclick=\'copyItem("previewItem","previewItem");same(this);\' name=arr_content[] value='.$id.' CHECKED type=checkbox>'.$name.'';
		}else
		{
			$checked = "";
		}
		$showcheck.='<font style="width:120px;"><input type="checkbox" '.$checked.' title="'.$id.'" name="arr_content[]" onclick="addPreItem()" 
		value="'.$name.'">'.$name.'</font>';
	
	}
}
echo $showcheck.'@@'.$otherval;


?>
