<? 
include("../include/pop3.inc.php"); 
/*
$host="pop3.163.com"; 
$user="ljznba@163.com"; 
$pass="07506237195"; 
$rec=new pop3($host,110,5); 
if (!$rec->open()) die($rec->err_str); 
   echo "open "; 
if(!$rec->login($user,$pass))
   die($rec->err_str); 
echo "login"; 
if (!$rec->stat())
   die($rec->err_str); 
   echo "����".$rec->messages."���ż�����".$rec->size."�ֽڴ�С<br>"; 
if ($rec->messages>0) 
{ 
   if (!$rec->listmail()) die($rec->err_str); 
       echo "�������ż���<br>"; 
   for ($i=1;$i<=count($rec->mail_list);$i++) 
   { 
       echo "�ż�".$rec->mail_list[$i][num]."��С��".$rec->mail_list[$i][size]."<BR>"; 
       $rec->getmail($i);
       echo htmlspecialchars($rec->head[$i])."<br>\n";  
       echo htmlspecialchars($rec->body[$i])."<br>\n";
   } 
   /*$rec->getmail(1); 
   echo "�ʼ�ͷ�����ݣ�"; 
   //for ($i=0;$ihead;$i++) 
   echo htmlspecialchars($rec->head[1])."<br>\n"; 
   echo "�ʼ����ġ���<BR>"; 
   //for ($i=0;$ibody;$i++) 
   //echo htmlspecialchars($rec->body[$i])."<br>\n"; 
   */   
/*} 
$rec->close(); 
*/
//include("pop3.inc.php"); 
include("../include/mime.inc.php"); 
$host="pop3.163.com"; 
$user="ljznba@163.com"; 
$pass="07506237195";
$rec=new pop3($host,110,5); 
$decoder=new decode_mail(); 
if(!$rec->open()) die($rec->err_str); 
if(!$rec->login($user,$pass)) die($rec->err_str); 
if(!$rec->stat())
die($rec->err_str);
echo "����".$rec->messages."���ż�����".$rec->size."�ֽڴ�С<br>";
if ($rec->messages>0) 
{ 
    if (!$rec->listmail()) die($rec->err_str); 
         echo "�������ż����ݣ�<br>"; 
    for ($i=1;$i<=count($rec->mail_list);$i++){ 
         echo "�ż�".$rec->mail_list[$i][num].",��С��".$rec->mail_list[$i][size]."<BR>"; 
         $rec->getmail($rec->mail_list[$i][num]); 
         $decoder->decode($rec->head,$rec->body); 
         echo "<h3>�ʼ�ͷ�����ݣ�</h3><br>"; 
         echo $decoder->from_name."(".$decoder->from_mail.") ��".date("Y-m-d H:i:s",$decoder->mail_time)." ����".$decoder->to_name."(".$decoder->to_mail.")"; 
         echo "\n<br>���ͣ�"; 
         if ($decoder->cc_to) echo $decoder->cc_to;
         else echo "��"; 
         echo "\n<br>���⣺".$decoder->subject; 
         echo "\n<br>�ظ���:".$decoder->reply_to; 
         echo "<h3>�ʼ����ġ���</h3><BR>"; 
         echo "�������ͣ�".$decoder->body_type; 
         echo "<br>���ĸ����ݣ�"; 
         for ($j=0;$j<count($decoder->body);$j++){ 
             echo "\n<br>���ͣ�".$decoder->body[$j][type]; 
             echo "\n<br>���ƣ�".$decoder->body[$j][name]; 
             echo "\n<br>��С:".$decoder->body[$j][size]; 
             echo "\n<br>content_id:".$decoder->body[$j][content_id]; 
             echo "\n<br>�����ַ���".$decoder->body[$j][char_set]; 
             echo "<pre>"; 
             echo "��������:".$decoder->body[$j][content]; 
             echo "</pre>"; 
         } 
         //$rec->dele($i); 
    } 
} 
$rec->close(); 
?>