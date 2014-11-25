<?
$thismenucode = "2n802";
require ("../include/common.inc.php");
include ("../function/pageft.php");
$db = new DB_test ;
$db1 = new DB_test ;

$t = new Template('.', "keep");
$t->set_file("mobilerecharge_list","mobilerecharge_list.html");
$t->set_block("mobilerecharge_list", "HEADBXBK", "headbxbks");

$arr_state = array("请求交易","交易成功","交易取消","无效状态");
//$arr_state = auto_charset($arr_state, 'utf-8', 'gbk');

if ($msgstart < 0)
    $msgstart = 0;

$all_paymoney = 0;
$query = "select *,case
        when fd_mrclist_payrq ='01' then '".$arr_state[0]."'
        when fd_mrclist_payrq ='00' then '".$arr_state[1]."'" .
    "when fd_mrclist_payrq ='03' then '".$arr_state[2]."'
        else '".$arr_state[4]."' END  fd_mrclist_payrq,
        fd_mrclist_ofstate,
       case
        when fd_mrclist_ofstate ='1' then '<font color=blue>充值成功</font>'
        when fd_mrclist_ofstate ='-1' then '正在充值'
        else '<font color=red>充值失败</font>' END  ofstate
         from  tb_mobilerechargelist
          left join tb_author  on fd_author_id  = fd_mrclist_authorid
          left join tb_sendcenter  on fd_sdcr_id  = fd_mrclist_sdcrid
          where 1
			and  (fd_mrclist_payrq = '00') and fd_mrclist_sdcrid<100  order by fd_mrclist_id desc ";
$db->query($query);
//and fd_agpm_paytype <>'recharge'
$total = $db->num_rows($result);
pageft($total, $displaypg, $url);
if ($firstcount < 0) {
    $firstcount = 0;
}
$count =$firstcount;
$query = "$query limit $firstcount,$displaypg";
$rows = $db->num_rows();
if($db->execute($query)) {
    $arr_val = $db->get_all($query);
}
if(is_array($arr_val))
{
    foreach($arr_val as $key => $arr_val)   //遍历数据
    {
        $tcount++;
        if($arr_val['fd_mrclist_ofstate']!='1')
        {
            $arr_val['eidt_ofstate']='<a href="javascript:void(0);" name="'.$arr_val['fd_mrclist_authorid'].'" class="eidt_ofstate" rel="'.$arr_val['fd_mrclist_bkntno'].'">再次充值</a>';
        }else
        {
            $arr_val['eidt_ofstate']="";
        }

        $t->set_var($arr_val);
        $arr_allval['all_paymoney']  += $arr_val['fd_mrclist_paymoney'];
        $arr_allval['all_rechamoney']  += $arr_val['fd_mrclist_rechamoney'];
        $t->parse("headbxbks", "HEADBXBK", true);
    }
}else
{
    $arr_allval['tcount']      = 0;
    $arr_allval['all_paymoney']  += $arr_val['fd_mrclist_paymoney'];
    $arr_allval['all_rechamoney']  += $arr_val['fd_mrclist_rechamoney'];
    $t->parse("headbxbks", "", true);
}
$t->set_var($arr_allval);


$file = "mobilerecharge_list.php?1";


$ly=$ny=$year;
$last=$month-1;
if ($last==0){
    $last=12;
    $ly--;
}
$next=$month+1;
if ($next==13){
    $next=1;
    $ny++;
}

if($ly>=1901){
    $str_page .= "<a href=\"".$file."&year=".$ly."&month=".$last."\">&lt;&lt;上一月</a>&nbsp;&nbsp;&nbsp;\n";
}else{
    $str_page .= "";
}
if($ny<=2020){
    $str_page .= "<a href=\"".$file."&year=".$ny."&month=".$next."\">下一月&gt;&gt;</a>\n";
}

$allxjdunshu = number_format($allxjdunshu, 4, ".", "");

$t->set_var("str_page"       , $str_page       );

$t->set_var("year"           , $year           );
$t->set_var("month"           , $month           );

$t->set_var("pagenav",$pagenav);
$t->set_var("brows_rows",$brows_rows);
$t->set_var("skin",$loginskin);
$t->pparse("out", "mobilerecharge_list");    # 最后输出页面

?>