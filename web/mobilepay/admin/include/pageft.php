<?

//ȡ��ȫ�ֱ��� 
if ($brows_rows=="")
{
	$brows_rows = 0;
}

if($brows_rows=='0')
{
	$brows_rows=$loginbrowline;
}
elseif($brows_rows>=500)
{
	$brows_rows=500;
}

//���������ֶ�
if($sorttype == "" or $sorttype == "asc"){
	$sorttype = "desc";
}else
if($sorttype == "desc"){
	$sorttype = "asc";
}
if(!empty($sorts)){
	$order = " order by $sorts $sorttype";
}

//$querywhere .=" and fd_mat_ssgs = '$loginczqx'";

if(!function_exists(pageft)){ 
function pageft($totle,$displaypg=20,$url=''){
error_reporting(0);
//���弸��ȫ�ֱ����� 
//$page������һҳ�룻
//$firstcount�������ݿ⣩��ѯ����ʼ�
//$pagenav��ҳ�浼�������룬�����ڲ���û�н��������
//$_SERVER����ȡ��ҳURL��$_SERVER["REQUEST_URI"]�������롣
global $page,$firstcount,$pagenav,$_SERVER;

//Ϊʹ�����ⲿ���Է�������ġ�$displaypg��������Ҳ��Ϊȫ�ֱ�����ע��һ���������¶���Ϊȫ�ֱ�����ԭֵ�����ǣ���������������¸�ֵ��
$GLOBALS["displaypg"]=$displaypg;

if(!$page) $page=1;

//���$urlʹ��Ĭ�ϣ�����ֵ����ֵΪ��ҳURL��
if(!$url){ $url=$_SERVER["REQUEST_URI"];}

//URL������
$parse_url=parse_url($url);
$url_query=$parse_url["query"]; //����ȡ��URL�Ĳ�ѯ�ִ�
if($url_query){

$url_query=ereg_replace("(^|&)page=$page","",$url_query);

//��������URL�Ĳ�ѯ�ִ��滻ԭ����URL�Ĳ�ѯ�ִ���
$url=str_replace($parse_url["query"],$url_query,$url);

//��URL���page��ѯ��Ϣ��������ֵ�� 
if($url_query) $url.="&page"; else $url.="page";
}else {
$url.="?page";
}
//ҳ����㣺
$lastpg=ceil($totle/$displaypg); //����һҳ��Ҳ����ҳ��
$page=min($lastpg,$page);
$prepg=$page-1; //��һҳ
$nextpg=($page==$lastpg ? 0 : $page+1); //��һҳ
$firstcount=($page-1)*$displaypg;

////��ʼ��ҳ���������룺
//$pagenav="�� <B>".($totle?($firstcount+1):0)."</B>-<B>".min($firstcount+$displaypg,$totle)."</B> ����¼���� $totle ��,ÿҳ<input type='text' class='input' size=3 name='brows_rows' value='{brows_rows}' onblur='pressbrowse(this.value);' onFocus='this.select()'   title='����ؼ��ֺ�,��ENTER��������ѯ�����в�ѯ!'> ��  ";


$pagenav="�� $totle ��/�� $lastpg ҳ,ÿҳ<input type='text' class='page_other' size=3 name='brows_rows' value='{brows_rows}' onblur='pressbrowse(this.value);' onFocus='this.select()'   title='����ؼ��ֺ�,��ENTER��������ѯ�����в�ѯ!'> ��  ";

//���ֻ��һҳ������������
if($lastpg<=1) return false;

$pagenav.="<a href='$url=1' id='btfirst'>��ҳ</a> ";
if($prepg) $pagenav.=" <a href='$url=$prepg' id='btper' >��ҳ</a> "; else $pagenav.=" ��ҳ ";
if($nextpg) $pagenav.=" <a href='$url=$nextpg' id='btnext'>��ҳ</a> "; else $pagenav.=" ��ҳ ";
$pagenav.=" <a href='$url=$lastpg' id='btlast'>βҳ</a> ";

//������ת�б�ѭ���г�����ҳ�룺
$pagenav.="������ <select name='topage' size='1' onchange='window.location=\"$url=\"+this.value' >\n";
for($i=1;$i<=$lastpg;$i++){
if($i==$page) $pagenav.="<option value='$i' selected class='input'>$i</option>\n";
else $pagenav.="<option value='$i'>$i</option>\n";
}
$pagenav.="</select> ҳ";
}
}
?>
