<?
class decode_mail{ 
    var $from_name;
    var $to_name;
    var $mail_time;
    var $from_mail;
    var $to_mail; 
    var $reply_to;
    var $cc_to;
    var $subject;
    // �������ʼ�ͷ���ֵ���Ϣ�� 
    var $body; 
    // �����õ����������ݣ�Ϊһ�����顣 
    var $body_type; // �������� 
    var $tem_num=0; 
    var $get_content_num=0; 
    var $body_temp=array(); 
    var $body_code_type; 
    var $boundary; 
    // ������һЩ�������õ���һЩȫ���Ե���ʱ���������ڡ�PHP�����������õķ�װ������ֻ�ܷ������ﶨ�� 
    var $err_str;  // ������Ϣ 
    var $debug=0; // ���Ա�� 
    var $month_num=array("Jan"=>1,"Feb"=>2,"Mar"=>3,"Apr"=>4,"May"=>5,"Jun"=>6,"Jul"=>7, 
    "Aug"=>8,"Sep"=>9,"Oct"=>10,"Nov"=>11,"Dec"=>12); // ��Ӣ���·�ת�������ֱ�ʾ���·� 
    
    function decode($head=null,$body=null,$content_num=-1) // ���õ���������$head �� $body ���������飬$content_num ��ʾ���ǵ������ж�����ֵ�ʱ��ֻȡ��ָ�����ֵ����������Ч�ʣ�Ĭ��Ϊ��-1 ,��ʾ����ȫ�����ݣ��������ɹ����� �������� true 
    { 
       if(!$head and !$body){ 
          $this->err_str="û��ָ���ʼ���ͷ�����ݣ�!"; 
          return false; 
       } 
       if(gettype($head)=="array") { 
           $have_decode=true; 
           $this->decode_head($head); 
       } 
       if(gettype($body)=="array") { 
           $this->get_content_num=$content_num; 
           $this->body_temp=$body; 
           $have_decode=true; 
           $this->decode_body(); 
           unset($this->body_temp); 
       } 
       if(!$have_decode){ 
           $this->err_str="���ݵĲ������ԣ��÷���new decode_mail(head,body) ����������������"; 
           return false; 
       } 
    } 
    function decode_head($head) // �ʼ�ͷ���� �Ľ��룬ȡ���ʼ�ͷ������������� 
    { 
       $i=0; 
       $this->from_name=$this->to_name=$this->mail_time=$this->from_mail=$this->to_mail=$this->reply_to=$this->cc_to=$this->subject=""; 
       $this->body_type=$this->boundary=$this->body_code_type="";
       //echo "<br>============2".$this->body_type."======================<br>"; 
       while($head[$i]){ 
            if (strpos($head[$i],"=?")) 
               $head[$i]=$this->decode_mime($head[$i]);  //����б�������ݣ�����н��룬���뺯�������������ܵġ�decode_mime()
            $pos=strpos($head[$i],":");
            $summ=substr($head[$i],0,$pos); 
            $content=substr($head[$i],$pos+1);  //���ʼ�ͷ��Ϣ�ı�ʶ�����ݷֿ� 
            //echo "===================".$summ."===".$content."================<br>";
            if ($this->debug) echo $summ.":----:".$content."<BR>"; 
            switch (strtoupper($summ)){ 
               case "FROM": // �����˵�ַ������������û��������ֻ�е�ַ��Ϣ�� 
                    if ($left_tag_pos=strpos($content,"<")) { 
                        $mail_lenth=strrpos($content,">")-$left_tag_pos-1; 
                        $this->from_name=substr($content,0,$left_tag_pos); 
                        $this->from_mail=substr($content,$left_tag_pos+1,$mail_lenth); 
                        if (trim($this->from_name)=="") 
                            $this->from_name=$this->from_mail; 
                        else 
                            if (ereg("[\"|\'']([^\''\"]+)[\''|\"]",$this->from_name,$reg)) 
                                 $this->from_name=$reg[1]; 
                    }else{ 
                        $this->from_name=$content; 
                        $this->from_mail=$content; 
                        //û�з����˵��ʼ���ַ 
                    } 
               break; 
               case "TO": //�ռ��˵�ַ������������ û�������� 
                    if ($left_tag_pos=strpos($content,"<")) { 
                         $mail_lenth=strrpos($content,">")-$left_tag_pos-1; 
                         $this->to_name=substr($content,0,$left_tag_pos); 
                         $this->to_mail=substr($content,$left_tag_pos+1,$mail_lenth); 
                         if (trim($this->to_name)=="") 
                             $this->to_name=$this->to_mail; 
                         else 
                             if (ereg("[\"|\'']([^\''\"]+)[\''|\"]",$this->to_name,$reg)) 
                                 $this->to_name=$reg[1]; 
                    }else{ 
                         $this->to_name=$content; 
                         $this->to_mail=$content; 
                         //û�зֿ��ռ��˵��ʼ���ַ 
                    } 
                break; 
                case "DATE" : //�������ڣ�Ϊ�˴����㣬���ﷵ�ص���һ�� Unix ʱ����������� date("Y-m-d",$this->mail_time)�����õ�һ���ʽ������ 
                   $content=trim($content); 
                   $day=strtok($content," "); 
                   $day=substr($day,0,strlen($day)-1); 
                   $date=strtok(" "); 
                   $month=$this->month_num[strtok(" ")]; 
                   $year=strtok(" "); 
                   $time=strtok(" "); 
                   $time=split(":",$time); 
                   $this->mail_time=mktime($time[0],$time[1],$time[2],$month,$date,$year); 
                break; 
                case "SUBJECT": //�ʼ����� 
                   $this->subject=$content; 
                break; 
                case "REPLY_TO":// �ظ���ַ(����û��) 
                   if (ereg("<([^>]+)>",$content,$reg)) 
                        $this->reply_to=$reg[1]; 
                   else 
                        $this->reply_to=$content; 
                break; 
                //Multipart/mixed; boundary="Boundary-=_meXssAWZoCojICSIfsNoUNKWmNjv"
                case "CONTENT-TYPE": // �����ʼ��� Content���ͣ� eregi("([^;]*);",$content,$reg); 
                   //$this->body_type=trim($reg[1]); 
                   $this->body_type=trim($content); 
                   //echo "<br>============1".$this->body_type."======================<br>";
                   if (eregi("multipart",$content)) // ����ǡ�multipart ���ͣ�ȡ�á��ָ��� 
                   { 
                       while (!eregi("boundary=\"(.*)\"",$head[$i],$reg) and $head[$i]) 
                             $i++; 
                       $this->boundary=$reg[1]; 
                   }else //����һ����������ͣ�ֱ��ȡ������뷽�� 
                   { 
                       while (!eregi("charset=[\"|\''](.*)[\''|\"]",$head[$i],$reg)) 
                            $i++; 
                       $this->body_char_set=$reg[1]; 
                       while (!eregi("Content-Transfer-Encoding:(.*)",$head[$i],$reg)) 
                            $i++; 
                       $this->body_code_type=trim($reg[1]); 
                   } 
               break; 
               case "CC":  //���͵����� 
                   if (ereg("<([^>]+)>",$content,$reg)) 
                      $this->cc_to=$reg[1]; 
                   else 
                      $this->cc_to=$content; 
              default: 
                  break; 
          } // end switch 
          $i++; 
        } // end while 
        if (trim($this->reply_to)=="")  //���û��ָ���ظ���ַ����ظ���ַΪ�����˵�ַ 
           $this->reply_to=$this->from_mail; 
     }// end function define 
     function decode_body()   //���ĵĽ��룬�����õ��˲����ʼ�ͷ��������������Ϣ 
     { 
        $i=0; 
        //echo "<br>============3".$this->body_type."======================<br>";
        if (!eregi("multipart",$this->body_type))   //��������Ǹ������ͣ�����ֱ�ӽ��� 
        { 
            $tem_body=implode($this->body_temp,"\r\n"); 
             //echo "<br>-----------".$this->body_code_type."-----------------<br>";
              
            switch (strtolower($this->body_code_type))  // body_code_type �����ĵı��뷽ʽ�����ʼ�ͷ��Ϣ��ȡ�� 
            {
            	 case "base64": 
                  $tem_body=base64_decode($tem_body); 
               break; 
               case "quoted-printable": 
                  $tem_body=quoted_printable_decode($tem_body); 
               break; 
            }
             //echo "<br>-----------".$tem_body."-----------------<br>"; 
            $this->tem_num=0; 
            $this->body=array(); 
            $this->body[$this->tem_num][content_id]=""; 
            $this->body[$this->tem_num][type]=$this->body_type; 
            switch (strtolower($this->body_type)){ 
                case "text/html": 
                   $this->body[$this->tem_num][name]="���ı�����"; 
                break; 
                case "text/plain": 
                   $this->body[$this->tem_num][name]="�ı�����"; 
                break; 
                default: 
                   $this->body[$this->tem_num][name]="δ֪����"; 
            } 
            $this->body[$this->tem_num][size]=strlen($tem_body); 
            //echo "<br>-----------".$tem_body."-----------------<br>";
            $this->body[$this->tem_num][content]=$tem_body; 
            unset($tem_body); 
            //echo "=========serer====<br>";
       }else //������Ǹ������͵� 
       { 
            $this->body=array(); 
            $this->tem_num=0; 
            //echo "=========serer====<br>";
            //$tem_body=implode($this->body_temp,"\r\n");
            $this->decode_mult($this->body_type,$this->boundary,0);   //���ø������͵Ľ��뷽�� 
       } 
     } 
     function decode_mult($type,$boundary,$begin_row)   // �÷����õݹ�ķ���ʵ�֡����������ʼ����ĵĽ��룬�ʼ�Դ�ļ�ȡ���� body_temp ���飬����ʱ�����ø������͵����͡��ָ��������ڡ�body_temp �����еĿ�ʼָ�� 
     { 
         $i=$begin_row; 
         //$lines=count($this->body_temp); 
         $lines=1;
         
         while ($i<$lines) // ����һ�����ֵĽ�����ʶ�� 
         { 
         	  //echo "==========".$this->body_temp[$i]."--------<br>";
            while (!eregi($boundary,$this->body_temp[$i]))//�ҵ�һ����ʼ��ʶ 
               $i++;
            if (eregi($boundary."--",$this->body_temp[$i])) { 
               return $i; 
            } 
            //---------------2005-11-4-------------ԭ������"while (eregi(",�޸ĺ���������£�--------------------
            while (eregi("Content-Type:([^;]*);",$this->body_temp[$i],$reg ) and $this->body_temp[$i]) 
               $i++; 
            $sub_type=trim($reg[1]); // ȡ����һ�����ֵ� ������milt or text .... 
           if (eregi("multipart",$sub_type))// ���Ӳ��������ж�����ֵģ� 
           { 
               while (!eregi("boundary=\"([^\"]*)\"",$this->body_temp[$i],$reg) and $this->body_temp[$i]) 
                   $i++; 
               $sub_boundary=$reg[1];// �Ӳ��ֵķָ����� 
               $i++; 
               $last_row=$this->decode_mult($sub_type,$sub_boundary,$i); 
               $i=$last_row; 
           }else{ 
               $comm=""; 
               while (trim($this->body_temp[$i])!=""){ 
                  if (strpos($this->body_temp[$i],"=?")) 
                      $this->body_temp[$i]=$this->decode_mime($this->body_temp[$i]); 
                  if (eregi("Content-Transfer-Encoding:(.*)",$this->body_temp[$i],$reg)) 
                      $code_type=strtolower(trim($reg[1])); // ���뷽ʽ 
                      $comm.=$this->body_temp[$i]."\r\n"; 
                      $i++; 
                  } // comm �Ǳ����˵������ 
                  if (eregi("name=[\"]([^\"]*)[\"]",$comm,$reg)) 
                      $name=$reg[1]; 
                  if (eregi("Content-Disposition:(.*);",$comm,$reg)) 
                      $disp=$reg[1]; 
                  if (eregi("charset=[\"|\''](.*)[\''|\"]",$comm,$reg)) 
                      $char_set=$reg[1]; 
                  if (eregi("Content-ID:[ ]*\<(.*)\>",$comm,$reg)) // ͼƬ�ı�ʶ���� 
                      $content_id=$reg[1]; 
                  $this->body[$this->tem_num][type]=$sub_type; 
                  $this->body[$this->tem_num][content_id]=$content_id; 
                  $this->body[$this->tem_num][char_set]=$char_set; 
                  if ($name) 
                       $this->body[$this->tem_num][name]=$name; 
                  else 
                       switch (strtolower($sub_type)){ 
                           case "text/html": 
                                $this->body[$this->tem_num][name]="���ı�����"; 
                           break; 
                           case "text/plain": 
                                $this->body[$this->tem_num][name]="�ı�����"; 
                           break; 
                           default: 
                                $this->body[$this->tem_num][name]="δ֪����"; 

                        }
                    // ��һ�п�ʼȡ������ 
                  if ($this->get_content_num==-1 or $this->get_content_num==$this->tem_num) // �ж���������Ƿ�����Ҫ�ġ�-1 ��ʾȫ�� 
                  { 
                      $content=""; 
                      while (!ereg($boundary,$this->body_temp[$i])){ 
                            //$content[]=$this->body_temp[$i]; 
                            $content.=$this->body_temp[$i]."\r\n"; 
                            $i++; 
                      } 
                      //$content=implode("\r\n",$content); 
                      switch ($code_type){ 
                          case "base64": 
                             $content=base64_decode($content); 
                          break; 
                          case "quoted-printable": 
                             $content=str_replace("\n","\r\n",quoted_printable_decode($content)); 
                          break; 
                      } 
                      $this->body[$this->tem_num][size]=strlen($content); 
                      //echo "<br>-----------".$content."-----------------<br>";
                      $this->body[$this->tem_num][content]=$content; 
                   }else{ 
                      while (!ereg($boundary,$this->body_temp[$i])) 
                          $i++; 
                   } 
                   $this->tem_num++; 
          }// end else 
       } // end while; 
    } // end function define 
    /*function decode_mime($string) { 
       //decode_mime ���������и����������Թ��� 
    }*/
    function decode_mime($string){ 
        $pos = strpos($string, '=?');
        if (!is_int($pos)){ 
             return $string; 
        } 
        $preceding = substr($string, 0, $pos); // save any preceding text 
        $search = substr($string, $pos+2); /* the mime header spec says this is the longest a single encoded word can be */ 
        $d1 = strpos($search, '?'); 
        if (!is_int($d1)) { 
           return $string; 
        } 
        $charset = substr($string, $pos+2, $d1); //ȡ���ַ����Ķ��岿�� 
        $search = substr($search, $d1+1); //�ַ��������Ժ�Ĳ��֣�>$search; 
        $d2 = strpos($search, '?'); 
        if (!is_int($d2)) { 
            return $string; 
        } 
        $encoding = substr($search, 0, $d2); ////����?��֮��Ĳ��ֱ��뷽ʽ�����񡡻򡡣⡡ 
        $search = substr($search, $d2+1); 
        $end = strpos($search, '?='); //$d2+1 �� $end ֮���Ǳ����ˡ������ݣ�=> $endcoded_text; 
        if (!is_int($end)) { 
            return $string; 
        } 
        $encoded_text = substr($search, 0, $end); 
        $rest = substr($string, (strlen($preceding . $charset . $encoding . $encoded_text)+6)); //+6 ��ǰ��ȥ���ġ�=????=�������ַ� 
        switch ($encoding) { 
           case "Q": 
           case "q": 
//$encoded_text = str_replace("_","%20",$encoded_text);
//$encoded_text = str_replace("=","%",$encoded_text);
//$decoded = urldecode($encoded_text); 
           $decoded=quoted_printable_decode($encoded_text); 
           if (strtolower($charset) == "windows-1251"){ 
               $decoded = convert_cyr_string($decoded, 'w', 'k'); 
           } 
           break; 
           case "B": 
           case "b": 
           $decoded = base64_decode($encoded_text); 
           if (strtolower($charset) == 'windows-1251'){ 
              $decoded = convert_cyr_string($decoded, 'w', 'k'); 
           } 
           //echo "������:base64_decode<br>";
           break; 
           default: 
              $decoded = "=?" . $charset . "?" . $encoding . "?" . $encoded_text . "?="; 
           break; 
         } 
         return $preceding . $decoded . $this->decode_mime($rest); 
     }  
} // end class define 
?>