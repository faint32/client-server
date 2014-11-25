<?
class pop3
{
  var $hostname="";                // POP������ 
  var $port=110;                   // ������POP3�˿ڣ�һ����110�Ŷ˿�
  var $timeout=5;                  // �������������ʱʱ�䡡 
  var $connection=0;               // ���������������� 
  var $state="DISCONNECTED";       // ���浱ǰ��״̬�� 
  var $debug=0;                    // ��Ϊ��ʶ���Ƿ��ڵ���״̬���ǵĻ������������Ϣ 
  var $err_str='';                 // ����������ﱣ�������Ϣ 
  var $err_no;                     // ����������ﱣ�������� 
  var $resp;                       // ��ʱ�������������Ӧ��Ϣ 
  var $apop;                       // ָʾ��Ҫʹ�ü��ܷ�ʽ����������֤��һ�����������Ҫ 
  var $messages;                   // �ʼ��� 
  var $size;                       // ���ʼ����ܴ�С 
  var $mail_list;                  // һ�����飬��������ʼ��Ĵ�С�������ʼ������������ 
  var $head=array();               // �ʼ�ͷ�����ݣ����� 
  var $body=array();               // �ʼ�������ݣ�����;
  //��.��Ȼ�������е���Щ��������ͨ������һ���򵥵�˵����������ȫ�˽����ʹ�ã������Ҿ������˵�������ʵ���е�һЩ��Ҫ������ 
  //��Ϥ��������̵�����һ���ͻ�֪�������������Ĺ��캯�����ڳ�ʼ�������ʱ�����Ը����⼸��������Ĳ�����pop3�������ĵ�ַ���˿ںţ������ӷ�����ʱ�����ʱʱ�䡣һ����˵��ֻ��Ҫ����POP3�������ĵ�ַ�����ˡ� 
  Function pop3($server="192.100.100.1",$port=110,$time_out=5){
	   $this->hostname=$server; 
     $this->port=$port; 
     $this->timeout=$time_out; 
     return true; 
  } 
 //�÷�������Ҫ�κβ����Ϳɽ�����POP3��������sock���ӡ��÷������õ�����һ�����еķ���$this->getresp();��������������������� 
  Function open(){ 
     if($this->hostname==""){
     	  $this->err_str="��Ч��������!!"; 
     	  return false ;
     }
    if($this->debug) 
        echo "���ڴ򿪡�$this->hostname,$this->port,&$err_no, &$err_str, $this->timeout<BR>"; 
    if (!$this->connection=fsockopen($this->hostname,$this->port,&$err_no, &$err_str, $this->timeout)) 
    { 
        $this->err_str="���ӵ�POP������ʧ�ܣ�������Ϣ��".$err_str."����ţ�".$err_no; 
        return false; 
    }else{
        $this->getresp(); 
        if($this->debug)
           $this->outdebug($this->resp); 
        if (substr($this->resp,0,3)!="+OK"){
        	$this->err_str="������������Ч����Ϣ��".$this->resp."����POP�������Ƿ���ȷ"; 
          return false; 
        } 
        $this->state="AUTHORIZATION";
        return true; 
    } 
  } 
  //�������ȡ�÷������˵ķ�����Ϣ�����м򵥵Ĵ���ȥ�����Ļس����з�����������Ϣ������resp����ڲ������С���������ں���Ķ�������ж����õ������⣬���и�С����Ҳ�ں���Ķ���������õ��� 
   Function getresp(){ 
        for($this->resp="";;){   //for($this->resp="";;){ 
             if(feof($this->connection)) return false; 
             $this->resp.=fgets($this->connection,100); 
             $length=strlen($this->resp); 
             if($length>=2 && substr($this->resp,$length-2,2)=="\r\n"){ 
                  $this->resp=strtok($this->resp,"\r\n"); 
                  return true; 
             } 
        } 
    } 
     //�������þ��ǰѵ�����Ϣ$message��ʾ����������һЩ�����ַ�����ת���Լ�����β����<br>��ǩ��������Ϊ��ʹ������ĵ�����Ϣ�����Ķ��ͷ����� 
    //���������������sock����֮�󣬾�Ҫ��������������ص������ˣ���μ��������������Ի��Ĺ��̣�������ԡ�POP�Ի��ķ������Կ�����ÿ�ζ��Ƿ���һ�����Ȼ�����������һ���Ļ�Ӧ����������ִ���ǶԵģ���Ӧһ������+OK��ͷ��������һЩ������Ϣ�����ԣ����ǿ�����һ��ͨ����������ķ���: 
    Function outdebug($message){ 
        echo htmlspecialchars($message)."<br>\n"; 
    } 
   //����������Խ�����������: $command--> ���͸�������������; $return_lenth,$return_code ��ָ���ӷ������ķ�����ȡ�೤��ֵ��Ϊ����صı�ʶ�Լ������ʶ����ȷֵ��ʲô������һ���pop������˵������������ķ��ص�һ���ַ�Ϊ"+"���������Ϊ��������ȷִ���ˡ�Ҳ������ǰ���ᵽ���������ַ�"+OK"��Ϊ�жϵı�ʶ�� 
    //������ܵļ�����������԰���ǰ����ȡ�ż��ĶԻ�ȥ��⣬��Ϊ�йص������Ѿ���ǰ������˵�����������ķ���������ϸ��˵������ο����е�ע�ͣ�
    Function command($command,$return_lenth=1,$return_code='+'){ 
         if ($this->connection==0){ 
              $this->err_str="û�����ӵ��κη�������������������"; 
              return false; 
         } 
         if ($this->debug) $this->outdebug(">>> $command"); 
         if (!fputs($this->connection,"$command\r\n")){ 
              $this->err_str="�޷���������".$command; 
              return false; 
         }else{ 
             $this->getresp(); 
             if($this->debug) $this->outdebug($this->resp); 
             if (substr($this->resp,0,$return_lenth)!=$return_code){ 
                 $this->err_str=$command." ���������������Ч:".$this->resp; 
                 return false; 
             }else 
                 return true; 
             } 
    } 
     
    Function Login($user,$password){ //�����û��������룬��¼��������
        if($this->state!="AUTHORIZATION"){ 
           $this->err_str="��û�����ӵ���������״̬����"; 
           return false; 
        } 
        if (!$this->apop) //�������Ƿ����APOP�û���֤ 
        { 
            if (!$this->command("USER $user",3,"+OK")) return false; 
            if (!$this->command("PASS $password",3,"+OK")) return false; 
        }else{   //echo $this->resp=strtok($this->resp,"\r\n"); 
            if (!$this->command("APOP $user ".md5($this->greeting.$password),3,"+OK")) return false; 
        } 
        $this->state="TRANSACTION"; // �û���֤ͨ�������봫��ģʽ 
        return true; 
    } 
    Function stat() // ��Ӧ��stat���ȡ���ܵ��ʼ������ܵĴ�С 
    { 
        if($this->state!="TRANSACTION"){ 
        	 $this->err_str="��û�����ӵ���������û�гɹ���¼"; 
           return false; 
        } 
        if (!$this->command("STAT",3,"+OK")) 
             return false; 
        else{ 
             $this->resp=strtok($this->resp," "); 
             $this->messages=strtok(" "); // ȡ���ʼ����� 
             $this->size=strtok(" "); //ȡ���ܵ��ֽڴ�С 
             return true; 
       } 
    } 
    Function listmail($mess=null,$uni_id=null) //��Ӧ����LIST���ȡ��ÿ���ʼ��Ĵ�С����š�һ����˵�õ�����List������ָ����$uni_id ����ʹ��UIDL������ص���ÿ���ʼ��ı�ʶ������ʵ�ϣ������ʶ��һ����û��ʲô�õġ�ȡ�õĸ����ʼ��Ĵ�С���ص�����ڲ�����mail_list�����ά����� 
    { 
       if($this->state!="TRANSACTION"){ 
            $this->err_str="��û�����ӵ���������û�гɹ���¼"; 
            return false; 
       } 
       if ($uni_id) 
           $command="UIDL "; 
       else 
           $command="LIST "; 
       if ($mess) 
           $command.=$mess; 
       if (!$this->command($command,3,"+OK")){ 
       	   //echo $this->err_str; 
           return false; 
       }else{ 
           $i=0; 
           $this->mail_list=array(); 
           $this->getresp(); 
           while ($this->resp!="."){
           	  $i++; 
              if ($this->debug){ 
                  $this->outdebug($this->resp); 
              } 
              if ($uni_id){ 
                  $this->mail_list[$i][num]=strtok($this->resp," "); 
                  $this->mail_list[$i][size]=strtok(" "); 
              }else{ 
                  $this->mail_list[$i]["num"]=intval(strtok($this->resp," ")); 
                  $this->mail_list[$i]["size"]=intval(strtok(" ")); 
              } 
              $this->getresp(); 
          } 
          return true; 
       } 
    } 
    function getmail($num=1,$line=-1)// ȡ���ʼ������ݣ�$num���ʼ�����ţ�$line��ָ����ȡ�����ĵĶ����С���Щʱ�����ʼ��Ƚϴ������ֻ���Ȳ鿴�ʼ�������ʱ�Ǳ���ָ�������ġ�Ĭ��ֵ$line=-1����ȡ�����е��ʼ����ݣ�ȡ�õ����ݴ�ŵ��ڲ�����$head��$body����������������ÿһ��Ԫ�ض�Ӧ�����ʼ�Դ�����һ�С�
    { 
        if($this->state!="TRANSACTION") { 
            $this->err_str="������ȡ�ż�����û�����ӵ���������û�гɹ���¼"; 
            return false; 
        } 
        if ($line<0) 
           $command="RETR $num"; 
        else 
           $command="TOP $num $line"; 
        if (!$this->command("$command",3,"+OK")) 
           return false; 
        else{ 
           $this->getresp(); 
           $is_head=true; 
          while ($this->resp!=".") // . �����ʼ������ı�ʶ 
          { 
             if ($this->debug) 
                 $this->outdebug($this->resp); 
             if (substr($this->resp,0,1)==".") 
                 $this->resp=substr($this->resp,1,strlen($this->resp)-1); 
             if (trim($this->resp)=="") // �ʼ�ͷ�����Ĳ��ֵ���һ������ 
                 $is_head=false; 
             if ($is_head) 
                 $this->head[]=$this->resp; 
             else 
                 $this->body[]=$this->resp; 
             $this->getresp(); 
           } 
         return true; 
      } 
   } // end function 

    function dele($num) // ɾ��ָ����ŵ��ʼ���$num �Ƿ������ϵ��ʼ���� 
    { 
       if($this->state!="TRANSACTION"){ 
           $this->err_str="����ɾ��Զ���ż�����û�����ӵ���������û�гɹ���¼"; 
           return false; 
       } 
       if (!$num){ 
          $this->err_str="ɾ���Ĳ�������"; 
          return false; 
       } 
       if ($this->command("DELE $num ",3,"+OK")) 
          return true; 
       else 
          return false; 
    } 
     //ͨ�����ϼ��������������Ѿ�����ʵ���ʼ��Ĳ鿴����ȡ��ɾ���Ĳ������������������Ҫ�˳������ر�������������ӣ������������������� 
     Function Close(){ 
        if($this->connection!=0) { 
           if($this->state=="TRANSACTION") 
               $this->command("QUIT",3,"+OK"); 
           fclose($this->connection); 
           $this->connection=0; 
           $this->state="DISCONNECTED"; 

        } 
     }
} 
?> 
