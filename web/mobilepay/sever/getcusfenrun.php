<?php
class getcusfenrun {

    public $timeout; // 默认的超时为300s
    public $ErrorReponse; // 出错处理
    public $debug = false; // 是否在Debug模式，为true的时候会打印出请求内容和相同的头部

    function __construct() {

       // $this->ErrorReponse = new ErrorReponse();
        $this->timeout = 3000; // 默认的超时为300s

    }
    public static function  getcusmonthallfenrun($cusid,$month ='',$appmnuno ='')
    {
        $db=new db_test;
        if($appmnuno!="")
        {

        }
        if($month!="")
        {
            $querywhere1 = " and (fd_frlist_paydate) like '%$month%'";
        }
        $query = "select sum(fd_frlist_paymoney) as paymoney,sum(fd_frlist_payfee) as payfee,sum(fd_frlist_cusfee) as cusfenrun,fd_frlist_cusid
             from tb_cus_fenrunglist
               where fd_frlist_payrq = '00'
              $querywhere1 and fd_frlist_cusid = '$cusid'   group by fd_frlist_cusid
              ";

        if($db->execute($query))
        {
            $arr_money = $db->get_one($query);
        }
        return $arr_money;
    }

    public function  getcauthoractivecardnum($cusid,$month)
    {
        $db=new db_test;
        $query = "select count(1) as activecardnum  from tb_paycard
              where fd_paycard_cusid = '$cusid'
             and (fd_paycard_firstusedate) like '%$month%'
             group by fd_paycard_cusid";
        // echo $query;
        if($db->execute($query))
        {
            $arr_money = $db->get_one($query);
        }
        return $arr_money['activecardnum'];
    }
    public function  getcusordernum($cusid,$month)
    {
        $db=new db_test;

        $query = "select sum(fd_selt_allquantity) salecount
             from tb_salelist
             where fd_selt_cusid = '$cusid' and fd_selt_state != '-1'
             and (fd_selt_date) like '%$month%'
             group by fd_selt_cusid
          ";
        // echo $query;
        if($db->execute($query))
        {
            $arr_money = $db->get_one($query);
        }

        return $arr_money['salecount'];

    }
    public function getareaauthornum($cusid,$month)
    {
        $db=new db_test;
        $query = "select count(*) as  activecardnum from tb_author
              where fd_author_bdagentid = '$cusid'
             and (fd_author_bdagenttime) like '%$month%'
             group by fd_author_bdagentid";
        // echo $query;
        if($db->execute($query))
        {
            $arr_money = $db->get_one($query);
        }
        return $arr_money['activecardnum'];
    }
    /*获取代理商品和价格*/
    public static function getagentprodure($cusid)
    {
        $db = new DB_test();
        $query = "select fd_cus_fenrunid  as fenrunid from tb_customer where fd_cus_id = '$cusid'  ";
        if($db->execute($query))
        {
            $arr_cus = $db->get_one($query);
            $fenrunid = $arr_cus['fenrunid'];
        }
        $query = "select fd_fenrun_agentprodureid as agentprodureid,fd_fenrun_agentprodure as agentprodure,fd_fenrun_agentprice as agentprice,
                    fd_fenrun_limitmaxnum as limitmaxnum,fd_fenrun_limitminnum as limitminnum
                    from tb_cus_fenrunset where fd_fenrun_id = '$fenrunid'  ";
        if($db->execute($query))
        {
            $arr_fenrun = $db->get_one($query);
            $arr_fenrun = auto_charset($arr_fenrun, 'gbk', 'utf-8');
        }
        return $arr_fenrun;
    }
    /*
     * 获取功能;
     */
    public static function get_cusfenrun($cusid,$appmenuno,$paymoney,$payfee,$feelirun=0)
    {
        $db = new DB_test();


        $query = "select * from tb_appmenu where fd_appmnu_no = '$appmenuno'";
        if ($db->execute($query)) {
            $arr_menuval = $db->get_one($query);
            $appmenuid = $arr_menuval['fd_appmnu_id'];
        }else
        {
            $appmenuid = 0;//所有功能 ；
        }

        $query = "select fd_cus_fenrunid  as fenrunid from tb_customer where fd_cus_id = '$cusid'  ";
        if($db->execute($query))
        {
            $arr_cus = $db->get_one($query);
            $fenrunid = $arr_cus['fenrunid'];
        }
        $query = "select * from tb_cus_fenrunset where fd_fenrun_id = '$fenrunid'  ";
        if($db->execute($query))
        {
            $arr_fenrun = $db->get_one($query);
        }

        $query = "select * from tb_cus_fenrunsetappmenu where fd_frmset_fenrunid = '$fenrunid' group by fd_frmset_appmnuid  ";
        if($db->execute($query))
        {
            $arr_fenrunappmenu = $db->get_all_key($query,fd_frmset_appmnuid);
        }
        $arr_lirunset = $arr_fenrunappmenu[$appmenuid]; //获取到功能分润设置

        if(is_array($arr_lirunset))
        {
            $jsmoneytype   =$arr_lirunset['fd_frmset_jsmoneytype'];
            switch($jsmoneytype)  //金额方式
            {
                case "paymoney":
                    $paycardmoney = $paymoney;  //交易金额
                    break;
                case "payfee":
                    $paycardmoney = $payfee;
                    break;
                case "feelirun":
                    $paycardmoney = $feelirun;
                    break;
            }
            $mode    = $arr_lirunset['fd_frmset_mode'];//润分方式 固定还是%
            $fixfee = $arr_lirunset['fd_frmset_fee'];//固定分润金额
            $minfee = $arr_lirunset['fd_frmset_minfee'];//最小分润金额
            $maxfee = $arr_lirunset['fd_frmset_maxfee'];//最大分润金额
            $fee    = $arr_lirunset['fd_frmset_sqfee'];//浮动费率
            $fee    = substr($fee, 0, -1);

            switch ($mode) //费率计算方式
            {
                case "fix" :
                    $return_feemoney = $fixfee;
                    break;
                default :
                    $return_feemoney = round(($fee / 100 * $paycardmoney), 2);
                    $return_feemoney = $return_feemoney < $minfee ? $minfee : $return_feemoney;
                    $return_feemoney = $return_feemoney > $maxfee ? $maxfee : $return_feemoney;
                    break;
            }



        }else
        {
            $return_feemoney = 0; //为设置分润方案
        }

        return $return_feemoney;
    }
}
?>