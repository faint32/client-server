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
   echo "共有".$rec->messages."封信件，共".$rec->size."字节大小<br>"; 
if ($rec->messages>0) 
{ 
   if (!$rec->listmail()) die($rec->err_str); 
       echo "有以下信件：<br>"; 
   for ($i=1;$i<=count($rec->mail_list);$i++) 
   { 
       echo "信件".$rec->mail_list[$i][num]."大小：".$rec->mail_list[$i][size]."<BR>"; 
       $rec->getmail($i);
       echo htmlspecialchars($rec->head[$i])."<br>\n";  
       echo htmlspecialchars($rec->body[$i])."<br>\n";
   } 
   /*$rec->getmail(1); 
   echo "邮件头的内容："; 
   //for ($i=0;$ihead;$i++) 
   echo htmlspecialchars($rec->head[1])."<br>\n"; 
   echo "邮件正文　：<BR>"; 
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
echo "共有".$rec->messages."封信件，共".$rec->size."字节大小<br>";
if ($rec->messages>0) 
{ 
    if (!$rec->listmail()) die($rec->err_str); 
         echo "以下是信件内容：<br>"; 
    for ($i=1;$i<=count($rec->mail_list);$i++){ 
         echo "信件".$rec->mail_list[$i][num].",大小：".$rec->mail_list[$i][size]."<BR>"; 
         $rec->getmail($rec->mail_list[$i][num]); 
         $decoder->decode($rec->head,$rec->body); 
         echo "<h3>邮件头的内容：</h3><br>"; 
         echo $decoder->from_name."(".$decoder->from_mail.") 于".date("Y-m-d H:i:s",$decoder->mail_time)." 发给".$decoder->to_name."(".$decoder->to_mail.")"; 
         echo "\n<br>抄送："; 
         if ($decoder->cc_to) echo $decoder->cc_to;
         else echo "无"; 
         echo "\n<br>主题：".$decoder->subject; 
         echo "\n<br>回复到:".$decoder->reply_to; 
         echo "<h3>邮件正文　：</h3><BR>"; 
         echo "正文类型：".$decoder->body_type; 
         echo "<br>正文各内容："; 
         for ($j=0;$j<count($decoder->body);$j++){ 
             echo "\n<br>类型：".$decoder->body[$j][type]; 
             echo "\n<br>名称：".$decoder->body[$j][name]; 
             echo "\n<br>大小:".$decoder->body[$j][size]; 
             echo "\n<br>content_id:".$decoder->body[$j][content_id]; 
             echo "\n<br>正文字符集".$decoder->body[$j][char_set]; 
             echo "<pre>"; 
             echo "正文内容:".$decoder->body[$j][content]; 
             echo "</pre>"; 
         } 
         //$rec->dele($i); 
    } 
} 
$rec->close(); 
?>