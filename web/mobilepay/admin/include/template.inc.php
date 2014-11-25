<?php
/*
* PHPlibģ��7.4���İ�(����֮�������λָ��)
* (C) Copyright 1999-2000 NetUSE GmbH
* Kristian Koehntopp
* ����Ⱥע����2004��6��,QQ:9537075 TEL:13787877670
* Email:mylovepzq@163.com
*/


/*�����Ƕ�����Template*/
class Template
{
/* ��������ˣ���������� */
var $classname = "Template";
var $debug = false; //�Ƿ����
var $root = ".";//rootΪģ���ļ��Ĵ��Ŀ¼
var $file = array(); //���������е�ģ���ļ�����ģ����������
var $varkeys = array(); //����ı�Ԫ�صļ���
var $varvals = array(); //����ı�Ԫ�ص�ֵ
var $unknowns = "remove"; 
/* "remove" => ɾ��δ����ı��� "comment" => ��δ����ı������ע�� "keep" => ����δ����ı��� */
var $halt_on_error = "yes";
/* "yes" => �˳� "report" => ������󣬼�������* "no" => ���Դ���*/
var $last_error = "";
/* ��һ�εĴ��󱣴������� */
/* public: ���캯��
* root: ģ��Ŀ¼
* unknowns: ��δ���δ֪�ı���(���ߣ���������Ϊ{name})
*/


/*�����Ƕ��庯��Template*/
function Template($root = ".", $unknowns = "remove") 
{
if ($this->debug & 4) 
{
echo "<p><b>ģ��:</b> root = $root, unknowns = $unknowns</p>\n";
}
$this->set_root($root);//Ĭ�Ͻ��ļ�Ŀ¼����Ϊ��ͬ��Ŀ¼
$this->set_unknowns($unknowns);//unknownsĬ������Ϊ"remove"
}


/*�����Ǻ���set_root*/
function set_root($root)
{
if ($this->debug & 4) 
{
echo "<p><b>���ø�Ŀ¼:</b> root = $root</p>\n";
}
if (!is_dir($root))
{
$this->halt("���ø�Ŀ¼: $root ����һ����Ч��Ŀ¼.");
return false;
}
$this->root = $root;
return true;
}


//�����Ǻ���set_unknowns,����δ֪�����Ĵ���
function set_unknowns($unknowns = "remove")
{
if ($this->debug & 4)
{
echo "<p><b>δ֪��:</b> δ֪ = $unknowns</p>\n";
}
$this->unknowns = $unknowns;
}




/*�����Ǻ���set_file.......................................................*/
//�÷���������file�и���$varname�ṩ�ļ�������ֵ
function set_file($varname, $filename = "")
{
if (!is_array($varname))//���varname������
{
if ($this->debug & 4)
{
echo "<p><b>�����ļ�:</b> (with scalar) varname = $varname, filename = $filename</p>\n";
}
if ($filename == "")//����ļ���Ϊ��,�������
{
$this->halt("�����ļ�:������ $varname �ļ����ǿյ�.");
return false;
}
$this->file[$varname] = $this->filename($filename);
} 
else
{
reset($varname);//��varname�ļ�����Ϊfile����ļ���
//��������Ӧ��ֵ��Ϊfile�����ֵ
while(list($v, $f) = each($varname))
{
if ($this->debug & 4)
{
echo "<p><b>set_file:</b> (with array) varname = $v, filename = $f</p>\n";
}
if ($f == "")
{
$this->halt("set_file: For varname $v filename is empty.");
return false;
}
$this->file[$v] = $this->filename($f);
}
}
return true;
}



//�÷���ȡ��ĳ����ģ���ļ��е�һ����ģ��
//������Ϊ��������
//��������һ��ģ�����ȡ��֮
/* public: set_file(array $filelist)
* comment: ���ö��ģ���ļ�
* filelist: ��������ļ���������
* public: set_file(string $handle, string $filename)
* comment: ����һ��ģ���ļ�
* handle: �ļ��ľ��
* filename: ģ���ļ���
*/
function set_block($parent, $varname, $name = "") {
if ($this->debug & 4) {
echo "<p><b>set_block:</b> parent = $parent, varname = $varname, name = $name</p>\n";
}
if (!$this->loadfile($parent)) {
$this->halt("set_block: unable to load $parent.");
return false;
}
if ($name == "") {
$name = $varname;//���û��ָ��ģ�������ֵ������ģ������Ϊģ�������
}

$str = $this->get_var($parent);
$reg = "/[ \t]*<!--\s+BEGIN $varname\s+-->\s*?\n?(\s*.*?\n?)\s*<!--\s+END $varname\s+-->\s*?\n?/sm";
preg_match_all($reg, $str, $m);
$str = preg_replace($reg, "{" . "$name}", $str);
$this->set_var($varname, $m[1][0]);
$this->set_var($parent, $str);
return true;
}



//�÷�����Varname��varkeys����������µļ�һֵ��
/* public: set_var(array $values)
* values: (��������ֵ)����
* public: set_var(string $varname, string $value)
* varname: ��������ı�����
* value: ������ֵ
*/
function set_var($varname, $value = "", $append = false) {
if (!is_array($varname))//�����������
{
if (!empty($varname)) //����ǿյ�
{
if ($this->debug & 1) {
printf("<b>set_var:</b> (with scalar) <b>%s</b> = '%s'<br>\n", $varname, htmlentities($value));
}
$this->varkeys[$varname] = "/".$this->varname($varname)."/";
if ($append && isset($this->varvals[$varname])) {
$this->varvals[$varname] .= $value;
} else {
$this->varvals[$varname] = $value;
}
}
} else {
reset($varname);
while(list($k, $v) = each($varname)) {
if (!empty($k)) {
if ($this->debug & 1) {
printf("<b>set_var:</b> (with array) <b>%s</b> = '%s'<br>\n", $k, htmlentities($v));
}
$this->varkeys[$k] = "/".$this->varname($k)."/";
if ($append && isset($this->varvals[$k])) {
$this->varvals[$k] .= $v;
} else {
$this->varvals[$k] = $v;
}
}
}
}
}


//���庯��clear_var
function clear_var($varname) {
if (!is_array($varname))//���varname��������
{
if (!empty($varname)) //����ǿյ�
{
if ($this->debug & 1) {
printf("<b>clear_var:</b> (with scalar) <b>%s</b><br>\n", $varname);
}
$this->set_var($varname, "");
}
} else {
reset($varname);
while(list($k, $v) = each($varname)) {
if (!empty($v)) {
if ($this->debug & 1) {
printf("<b>clear_var:</b> (with array) <b>%s</b><br>\n", $v);
}
$this->set_var($v, "");
}
}
}
}




/*�����Ǻ���unset_var,ɾ�������Ķ���*/
function unset_var($varname) {
if (!is_array($varname)) {
if (!empty($varname)) {
if ($this->debug & 1) {
printf("<b>unset_var:</b> (with scalar) <b>%s</b><br>\n", $varname);
}
unset($this->varkeys[$varname]);
unset($this->varvals[$varname]);
}
} else {
reset($varname);
while(list($k, $v) = each($varname)) {
if (!empty($v)) {
if ($this->debug & 1) {
printf("<b>unset_var:</b> (with array) <b>%s</b><br>\n", $v);
}
unset($this->varkeys[$v]);
unset($this->varvals[$v]);
}
}
}
}




//��ģ���ļ��еı仯�����滻��ȷ�����ݵĲ���,ʵ�����ݺ���ʾ�ķ���
function subst($varname) {
$varvals_quoted = array();
if ($this->debug & 4) {
echo "<p><b>subst:</b> varname = $varname</p>\n";
}
if (!$this->loadfile($varname)) //װ��ģ���ļ�,��������ֹͣ
{
$this->halt("subst: unable to load $varname.");
return false;
}

reset($this->varvals);
while(list($k, $v) = each($this->varvals)) {
$varvals_quoted[$k] = preg_replace(array('/\\\\/', '/\$/'), array('\\\\\\', '\\\\$'), $v);
}

//�����ļ����ݵ��ַ����в������ж���֪��ֵ�����滻�����ؽ��
$str = $this->get_var($varname);
$str = preg_replace($this->varkeys, $varvals_quoted, $str);
return $str;
}



//ͬsubst,ֻ��ֱ��������
function psubst($varname) {
if ($this->debug & 4) {
echo "<p><b>psubst:</b> varname = $varname</p>\n";
}
print $this->subst($varname);

return false;
}



//��varname�����һ�������ļ��е���������滻
//�����targetΪ��ֵ��varvals���������л�׷�ӵ����
//����ֵ��sub��ͬ
function parse($target, $varname, $append = false) {
if (!is_array($varname)) {
if ($this->debug & 4) {
echo "<p><b>parse:</b> (with scalar) target = $target, varname = $varname, append = $append</p>\n";
}
$str = $this->subst($varname);
if ($append) {
$this->set_var($target, $this->get_var($target) . $str);
} else {
$this->set_var($target, $str);
}
} else {
reset($varname);
while(list($i, $v) = each($varname)) {
if ($this->debug & 4) {
echo "<p><b>parse:</b> (with array) target = $target, i = $i, varname = $v, append = $append</p>\n";
}
$str = $this->subst($v);
if ($append) {
$this->set_var($target, $this->get_var($target) . $str);
} else {
$this->set_var($target, $str);
}
}
}

if ($this->debug & 4) {
echo "<p><b>parse:</b> completed</p>\n";
}
return $str;
}



//ͬparse����,ֻ�Ǹ÷�����������
function pparse($target, $varname, $append = false) {
if ($this->debug & 4) {
echo "<p><b>pparse:</b> passing parameters to parse...</p>\n";
}
print $this->finish($this->parse($target, $varname, $append));
return false;
}



//�������еļ�һֵ���е�ֵ����ɵ�����
function get_vars() {
if ($this->debug & 4) {
echo "<p><b>get_vars:</b> constructing array of vars...</p>\n";
}
reset($this->varkeys);
while(list($k, $v) = each($this->varkeys)) {
$result[$k] = $this->get_var($k);
}
return $result;
}



//���ݼ������ض�Ӧ�ļ�һֵ�ڶ�Ӧ��ֵ
function get_var($varname) {
if (!is_array($varname)) //�����������
{
if (isset($this->varvals[$varname])) //�������������
{
$str = $this->varvals[$varname];
} else {
$str = "";
}
if ($this->debug & 2) {
printf ("<b>get_var</b> (with scalar) <b>%s</b> = '%s'<br>\n", $varname, htmlentities($str));
}
return $str;
} else {
reset($varname);
while(list($k, $v) = each($varname)) {
if (isset($this->varvals[$v])) {
$str = $this->varvals[$v];
} else {
$str = "";
}
if ($this->debug & 2) {
printf ("<b>get_var:</b> (with array) <b>%s</b> = '%s'<br>\n", $v, htmlentities($str));
}
$result[$v] = $str;
}
return $result;
}
}




//��������ļ�ʧ��,���ش���ֹͣ
function get_undefined($varname) {
if ($this->debug & 4) {
echo "<p><b>get_undefined:</b> varname = $varname</p>\n";
}
if (!$this->loadfile($varname)) {
$this->halt("get_undefined: unable to load $varname.");
return false;
}

preg_match_all("/{([^ \t\r\n}]+)}/", $this->get_var($varname), $m);
$m = $m[1];
//����޷��ҵ�ƥ����ı�,���ش���
if (!is_array($m)) {
return false;
}
//������ҵ��������еķǿ��ַ�,����ֵ��Ϊ��ֵ,���һ���µ�����
reset($m);
while(list($k, $v) = each($m)) {
if (!isset($this->varkeys[$v])) {
if ($this->debug & 4) {
echo "<p><b>get_undefined:</b> undefined: $v</p>\n";
}
$result[$v] = $v;
}
}
//���Ǹ����鲻Ϊ�վͷ��ظ�����,����ͷ��ش���
if (count($result)) {
return $result;
} else {
return false;
}
}


//��ɶ�str�����Ĵ�����,�����������unknowns��ȷ����ģ�����޷�����Ķ�̬���ֵĴ�����
function finish($str) {
switch ($this->unknowns) {
case "keep": //���ֲ���
break;

case "remove": //ɾ�����еķǿ��Ʒ�
$str = preg_replace('/{[^ \t\r\n}]+}/', "", $str);
break;

case "comment"://���������е�HTMLע��
$str = preg_replace('/{([^ \t\r\n}]+)}/', "<!-- Template variable \\1 undefined -->", $str);
break;
}

return $str;
}



//������������ڽ�������е�ֵ��������
function p($varname) {
print $this->finish($this->get_var($varname));
}


//������������Ӧ�������е�ֵ����󷵻�
function get($varname) {
return $this->finish($this->get_var($varname));
}



//��鲢����������ļ���

function filename($filename) {
if ($this->debug & 4) {
echo "<p><b>filename:</b> filename = $filename</p>\n";
}
if (substr($filename, 0, 1) != "/") 
//����ļ���������б�ܿ�ͷ,���ʾ�����·��,���䲹��Ϊ�����ľ���·�� 
{
$filename = $this->root."/".$filename;
}
//����ļ�������
if (!file_exists($filename)) {
$this->halt("filename: file $filename does not exist.");
}
return $filename;//�����ļ���
}



//�Ա��������д���,��������ʽ�е������ַ���Ϊת���ַ�,���ڱ��������˼��ϴ�����
function varname($varname) {
return preg_quote("{".$varname."}");
}


//�÷�������varname�����ļ�����һֵ����
function loadfile($varname) {
if ($this->debug & 4) {
echo "<p><b>loadfile:</b> varname = $varname</p>\n";
}

if (!isset($this->file[$varname])) //���û��ָ���ͷ��Ӵ���
{
// $varname does not reference a file so return
if ($this->debug & 4) {
echo "<p><b>loadfile:</b> varname $varname does not reference a file</p>\n";
}
return true;
}

if (isset($this->varvals[$varname]))//����Ѿ�������varnameΪ�������ļ�,ֱ�ӷ�����ֵ
{
if ($this->debug & 4) {
echo "<p><b>loadfile:</b> varname $varname is already loaded</p>\n";
}
return true;
}
$filename = $this->file[$varname];//�����Ч��ȡ����Ӧ���ļ���
$str = implode("", @file($filename));//���ļ���ÿһ�����ӳ�һ���ַ���
if (empty($str)) //�ַ�����˵���ļ��ջ��߲�����,���ش���
{
$this->halt("loadfile: While loading $varname, $filename does not exist or is empty.");
return false;
}
if ($this->debug & 4) {
printf("<b>loadfile:</b> loaded $filename into $varname<br>\n");
}
$this->set_var($varname, $str);//����ļ���Ϊ��,��$varname��Ϊ���,strΪ������
//���ֵ��������µļ�ֵ

return true;
}



//������ʾ����ֹ��������
function halt($msg) {
$this->last_error = $msg;

if ($this->halt_on_error != "no") {
$this->haltmsg($msg);
}

if ($this->halt_on_error == "yes") {
die("<b>��ֹ.</b>");
}

return false;
}


//������ʾ
function haltmsg($msg) {
printf("<b>ģ�����:</b> %s<br>\n", $msg);
}

}
?>