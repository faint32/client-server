<?php
class getcusfenrun {

    public $timeout; // Ĭ�ϵĳ�ʱΪ300s
    public $ErrorReponse; // ������
    public $debug = false; // �Ƿ���Debugģʽ��Ϊtrue��ʱ����ӡ���������ݺ���ͬ��ͷ��

    function __construct() {

        // $this->ErrorReponse = new ErrorReponse();
        $this->timeout = 3000; // Ĭ�ϵĳ�ʱΪ300s
    }

    /*��ȡ������Ʒ�ͼ۸�*/
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
     * ��ȡ����;
     */
    public static function get_cusfenrun($cusid,$appmenuno,$paymoney,$payfee,$feelirun=0,$paydate='',$payprice='',$paynum='')
    {
        $db = new DB_test();

        $query = "select * from tb_appmenu where fd_appmnu_no = '$appmenuno'";
        if ($db->execute($query)) {
            $arr_menuval = $db->get_one($query);
            $appmenuid = $arr_menuval['fd_appmnu_id'];
        }else
        {
            $appmenuid = 0;//���й��� ��
        }

        $query = "select fd_cus_fenrunid  as fenrunid
       from tb_customer where fd_cus_id = '$cusid' and fd_cus_state = 9 and
        fd_cus_active = '1'   ";

        if($db->execute($query))
        {
            $arr_cus = $db->get_one($query);
            $fenrunid   = $arr_cus['fenrunid'];

        }else
        {
            return 0;
            exit;
        }

        $query = "select *,fd_fenrun_orderbuycz as orderbuycz,fd_fenrun_agentprice as agentprice
        from tb_cus_fenrunset where fd_fenrun_id = '$fenrunid'  ";
        if($db->execute($query))
        {
            $arr_fenrun = $db->get_one($query);
        }

        $agentprice = $arr_fenrun['agentprice'];
        $orderbuycz = $arr_fenrun['orderbuycz'];
        if($appmenuno=='orderbuy')  //�ڹ�ˢ����
        {
            $cusfee = ($payprice-$orderbuycz-$agentprice)*$paynum;
            return $cusfee;
            exit;
        }

        if($arr_fenrun['fd_fenrun_lirunset']=='all')  //���й���ͳһ�������
          {
              $appmenuid = 0;
          }

        $query = "select * from tb_cus_fenrunsetappmenu where fd_frmset_fenrunid = '$fenrunid' group by fd_frmset_appmnuid  ";
        if($db->execute($query))
        {
            $arr_fenrunappmenu = $db->get_all_key($query,"fd_frmset_appmnuid");
        }


        $arr_lirunset = $arr_fenrunappmenu[$appmenuid]; //��ȡ�����ܷ�������

        if(is_array($arr_lirunset))
        {
            $jsmoneytype   =$arr_lirunset['fd_frmset_jsmoneytype'];
            switch($jsmoneytype)  //��ʽ
            {
                case "paymoney":
                    $paycardmoney = $paymoney;  //���׽��
                    break;
                case "payfee":
                    $paycardmoney = $payfee;
                    break;
                case "feelirun":
                    $paycardmoney = $feelirun;
                    break;
            }
            $mode    = $arr_lirunset['fd_frmset_mode'];//��ַ�ʽ �̶�����%
            $fixfee = $arr_lirunset['fd_frmset_fee'];//�̶�������
            $minfee = $arr_lirunset['fd_frmset_minfee'];//��С������
            $maxfee = $arr_lirunset['fd_frmset_maxfee'];//��������
            $fee    = $arr_lirunset['fd_frmset_sqfee'];//��������
            $fee    = substr($fee, 0, -1);

            switch ($mode) //���ʼ��㷽ʽ
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
            $return_feemoney = 0; //Ϊ���÷��󷽰�
        }

        return $return_feemoney;
    }
}
?>