<?
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=utf-8'); 
require ("global.session.php");
require ("get.variable.inc.php");
require ("config.inc.php");
require ('phplib.inc.php');
require ("template.inc.php");
require ("function.inc.php");
require ("pageft.php");
require ("AutogetFileimg.php");   //图片管理文件
require ("json.php");
$dir_file = $_SERVER['SCRIPT_NAME'];
$filename = explode(".",basename($dir_file));
$db_common = new DB_test;

//echo $login_authorlobinum;
function cut_str($string, $sublen, $start = 0, $code = 'UTF-8') 
{ 
if($code == 'UTF-8') 
{ 
$pa ="/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/"; 
preg_match_all($pa, $string, $t_string); if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen)); 
return join('', array_slice($t_string[0], $start, $sublen)); 
} 
else 
{ 
$start = $start*2; 
$sublen = $sublen*2; 
$strlen = strlen($string); 
$tmpstr = ''; for($i=0; $i<$strlen; $i++) 
{ 
if($i>=$start && $i<($start+$sublen)) 
{ 
if(ord(substr($string, $i, 1))>129) 
{ 
$tmpstr.= substr($string, $i, 2); 
} 
else 
{ 
$tmpstr.= substr($string, $i, 1); 
} 
} 
if(ord(substr($string, $i, 1))>129) $i++; 
} 
if(strlen($tmpstr)<$strlen ) $tmpstr.= ""; 
return $tmpstr; 
} 
} 
?>