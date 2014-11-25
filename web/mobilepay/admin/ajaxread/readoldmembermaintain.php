<?
 header("Content-type: text/html; charset=gbk"); 
require ("../include/common.inc.php");

$db = new DB_erptest;
$db2 = new DB_erptest;
$db3 = new DB_erptest;


if($sorttype=="desc"){
	$$sort="▼";
}else{
  $$sort="▲";
}

$contect = "<table  width='100%' border='1' cellspacing='0' cellpadding='0'  bordercolor='#d1d1d1' style='border-collapse:collapse;'>";
$contect .="<tr   align='center' height='30' bgcolor='#8caae7' style='color:#fff;font-weight:bold;position:relative;'>";
$contect .="<td nowrap width='40' >序号</td>";
$contect .="<td nowrap width='270'>企业名称</td>";
$contect .="<td nowrap >联系人</td>";
$contect .="<td  nowrap >联系电话</td>";
$contect .="<td  nowrap >城市</td>";
$contect .="<td  nowrap style='cursor:hand' onclick=loadmemberbuy('resort','joindate',document.getElementById('nowpage').value,document.getElementById('pagecount').value)>加入日期<span id=joindate>$joindate</span></td>";
$contect .="<td nowrap style='cursor:hand' onclick=loadmemberbuy('resort','lasthf',document.getElementById('nowpage').value,document.getElementById('pagecount').value)>最后回访日期<span id=lasthf>$lasthf</span></td>";
$contect .="<td nowrap >最近回访摘要</td>";
$contect .="<td nowrap>操作区</td>";
$contect .="</tr>";


if(!empty($atcompany)){
	$atcompany=	iconv('utf-8','gbk',unescape($atcompany));
	$querywhere .= " and fd_webcus_name like '%$atcompany%'";
}

if(!empty($atcity)){
	$atcity=	iconv('utf-8','gbk',unescape($atcity));
	$querywhere .= " and (fd_provinces_name like '%$atcity%' or fd_city_name like '%$atcity%')";
}


	

$beginpage=($page-1)*$pagecount;
	//会员资料
	$query = "select fd_webcus_id,fd_webcus_allname,
 fd_city_name , fd_webcus_newtime as joindate  ,fd_provinces_name ,
 fd_webcus_lasthfdate as lasthf,fd_webcus_lasthfcontect
	from tb_webcustomer 
	left join tb_city on fd_city_code = fd_webcus_city
	left join tb_provinces on fd_provinces_code = fd_webcus_provinces
	where fd_webcus_erpcusid <> 0 $querywhere order by $sort $sorttype limit $beginpage,$pagecount
	";
	$db->query($query);
	if($db->nf()){
		$v=0;
		
		while($db->next_record()){
		$cusid = $db->f(fd_webcus_id);
		if($v==0){
			$vv = "where fd_webcus_id = '$cusid'";
		}else{
			$vv .= " or fd_webcus_id = '$cusid'";
		}
		$arr_memname[$cusid]=$db->f(fd_webcus_allname);
		$arr_linkman[$cusid]=@implode("<br>",$arr_linkman_t[$cusid]);
		$arr_phone[$cusid]=@implode("<br>",$arr_linkphone_t[$cusid]);
		$arr_city[$cusid]=$db->f(fd_provinces_name).$db->f(fd_city_name);
		$arr_joindate[$cusid]=$db->f(joindate);
		$arr_lasthf[$cusid]=$db->f(lasthf);
		$arr_memhf[$cusid] = $db->f(fd_webcus_lasthfcontect);
		$v++;
	}
}

  //联系人
  $query ="select * from tb_cuslinkman
  left join tb_webcustomer on fd_webcus_id = fd_clm_cusid
   $vv";
 
  $db->query($query);
  if($db->nf()){
  	while($db->next_record()){
  		$cusid = $db->f(fd_clm_cusid);
  		$arr_linkman[$cusid][]=$db->f(fd_clm_name);
  		$arr_linkphone[$cusid][]=$db->f(fd_clm_mobile);
  	}
   }



if($sort=="joindate"){
if($sorttype=="desc"){
	@arsort($arr_joindate);
}else{
  @asort($arr_joindate);
}
	$arr_show = 	$arr_joindate;
  }


  if($sort=="lasthf"){
  if($sorttype=="desc"){
  @arsort($arr_lasthf);
}else{
  @asort($arr_lasthf);
}
	$arr_show = 	$arr_lasthf;
  }


	if($sort=="joindate"){
if($sorttype=="desc"){
	@arsort($arr_joindate);
}else{
  @asort($arr_joindate);
}
	$arr_show = 	$arr_joindate;
  }

  if($sort=="lasthf"){
  if($sorttype=="desc"){
  @arsort($arr_lasthf);
}else{
  @asort($arr_lasthf);
}
	$arr_show = 	$arr_lasthf;
  }


//输出
$s=$beginpage+1;
$k=0;
while(list($memid,$val)=@each($arr_show)){

 $memname = $arr_memname[$memid];
 $linkman = @implode("<br>",$arr_linkman[$memid]);
 $phone = @implode("<br>",$arr_linkphone[$memid]);
 $city = $arr_city[$memid];
 $joindate = $arr_joindate[$memid];
 $lasthf = $arr_lasthf[$memid];

 $memhf = cut_str($arr_memhf[$memid],50,0,"gb2312");
 
 
  $cz = "<a href='oldmembermaintaindetail.php?memid=".$memid."' target='_blank' style='color:#0000ff'>回访</a>"; 	


if($lasthf == "0000-00-00 00:00:00")$lasthf="<font color='red'>未回访</font>";


if($bgcolor=="" or $bgcolor=="#efefef"){
	$bgcolor = "#ffffff";
}else{
	$bgcolor = "#efefef";
}

$contect .="<tr align='center' height='30' bgcolor='".$bgcolor."'>";
$contect .="<td nowrap>".$s."</td>";
$contect .="<td nowrap>".$memname."</td>";
$contect .="<td nowrap>".$linkman."</td>";
$contect .="<td nowrap>".$phone."</td>";
$contect .="<td nowrap>".$city."</td>";
$contect .="<td nowrap>".$joindate."</td>";
$contect .="<td width='100'>".$lasthf."</td>";
$contect .="<td width='200' align='left' style='padding-left:7px;padding-top:7px;;padding-bottom:7px'>".$memhf."</td>";
$contect .="<td nowrap>".$cz."</td>";
$contect .="</tr>";


$s++;
$k++;
}
if($k==0){

	$contect .="<td nowrap colspan=32 height=50>没有任何数据……</td>";

}

	$query = "select fd_webcus_id,fd_webcus_allname,
 fd_city_name , fd_webcus_newtime ,fd_provinces_name ,
 fd_webcus_lasthfdate as lasthf
	from tb_webcustomer 
	left join tb_city on fd_city_code = fd_webcus_city
	left join tb_provinces on fd_provinces_code = fd_webcus_provinces
	where fd_webcus_erpcusid <> 0 $querywhere";
	$db->query($query);
	$zpage = ceil($db->nf()/$pagecount);
if($zpage<1)$zpage=1;

if($act=="init"){
	$arr_name="";
$arr_id="";

$arr_id=array("15","30","50","100","200");	
$arr_name=array("-15-","-30-","-50-","-100-","-200-");	

$pc = makeselect($arr_name,$pagecount,$arr_id);
	
$showpages.="</select>";
$showpage = "<table width='100%' border='0' cellspacing='0' cellpadding='0' >";
$showpage .="<tr align='center' height='30' bgcolor='#e4e4e4' style='color:#000;'>";
$showpage .="<td align='left' style='padding-left:10px'>每页记录数：<select id='pagecount' onchange=loadmemberbuy('cc',document.getElementById('sort').value,1,this.value,'','')>".$pc."</select></td>";
$showpage .="<td nowrap align='right' style='padding-right:10px'>第<span id=nowpage2></span>页 <a id=b1  href='#none'>首&nbsp;&nbsp;页</a> <a id=b2 href='#none'>上一页</a> <a id=b3 href='#none'>下一页</a>  <a id=b4 href='#none'>末&nbsp;&nbsp;页</a></td>";
$showpage .="</tr>";
$showpage .="</table>";
}

if($contect<>""){
$contect .="</table>";

}
echo $contect."@@".$showpage."@@".$zpage;


function unescape($str){
        preg_match_all("/%u[0-9A-Za-z]{4}|%.{2}|[0-9a-zA-Z.+-_]+/",$str,$matches); //prt($matches);
        $ar = &$matches[0];
        $c = "";
        foreach($ar as $val){
                if (substr($val,0,1)!="%"){ //如果是字母数字+-_.的ascii码
                        $c .=$val;
                }elseif(substr($val,1,1)!="u"){ //如果是非字母数字+-_.的ascii码
                        $x = hexdec(substr($val,1,2));
                        $c .=chr($x);
                }else{ //如果是大于0xFF的码
                        $val = intval(substr($val,2),16);
                        if($val <= 0x7F){        // 0000-007F
                                $c .= chr($val);
                        }elseif($val <= 0x800) { // 0080-0800
                                $c .= chr(0xC0 | ($val / 64));
                                $c .= chr(0x80 | ($val % 64));
                        }else{                // 0800-FFFF
                                $c .= chr(0xE0 | (($val / 64) / 64));
                                $c .= chr(0x80 | (($val / 64) % 64));
                                $c .= chr(0x80 | ($val % 64));
                        }
                }
        }
        return $c;
} 
 
function cut_str($string,$sublen,$start=0,$code='UTF-8'){ 
	if($code=='UTF-8') { 
		 $pa="/[x01-x7f]|[xc2-xdf][x80-xbf]|xe0[xa0-xbf][x80-xbf]|[xe1-xef][x80-xbf][x80-xbf]|xf0[x90-xbf][x80-xbf][x80-xbf]|[xf1-xf7][x80-xbf][x80-xbf][x80-xbf]/";  
		 preg_match_all($pa,$string,$t_string);  
		 if(count($t_string[0])-$start>$sublen) return join('',array_slice($t_string[0],$start,$sublen))."..."; 
		  return join('',array_slice($t_string[0],$start,$sublen)); 
		 } else {  $start=$start*2;  $sublen=$sublen*2;  $strlen=strlen($string);  
		 	$tmpstr='';  for($i=0;$i<$strlen;$i++)  {  
		 		 if($i>=$start&&$i<($start+$sublen))   {    
		 		 	if(ord(substr($string,$i,1))>129)    {    
		 		 		 $tmpstr.=substr($string,$i,2);    }    
		 		 		 else    {    
		 		 		 	 $tmpstr.=substr($string,$i,1);    }   }  
		 		 		 	  if(ord(substr($string,$i,1))>129) $i++;  }  
		 		 		 	  if(strlen($tmpstr)<$strlen ) $tmpstr.="...";  return $tmpstr;
		 		 		 	   }} 
?>