<?   
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=gbk');
require("../include/common.inc.php");
require("../include/json.php");


$dbshop=new db_shop;
$db=new db_test;
$shopnavname=u2g(unescape($shopnavname));
switch($act)
{
	case "trad":
	
	$query="select * from tb_shop where fd_shop_navname='$shopnavname' and  fd_shop_id<> '$shopid'";
	$dbshop->query($query);
	if($dbshop->nf())
	{
	echo "�ֿ����Ѵ��ڣ�"."@@"."n";

	}else{
	
	
		$query1="select * from tb_sendcenter where fd_sdcr_name='$shopnavname'";
		$db->query($query1);
		if($db->nf())
		{
			echo "�ֿ����Ѵ��ڣ�"."@@"."n";
		}else{
			echo "����"."@@"."y";
		}
	

	}
	break;
	case "sp":
	$query="select * from tb_shop where fd_shop_navname='$shopnavname' and  fd_shop_id<> '$shopid'";
	$dbshop->query($query);
	if($dbshop->nf())
	{
	echo "�ֿ����Ѵ��ڣ�"."@@"."n";

	}else{
	
		$query1="select * from tb_sendcenter where fd_sdcr_name='$shopnavname'";
		$db->query($query1);
		if($db->nf())
		{
			echo "�ֿ����Ѵ��ڣ�"."@@"."n";
		}else{
			echo "����"."@@"."y";
		}
	}
	break;
}

function unescape($str){
        preg_match_all("/%u[0-9A-Za-z]{4}|%.{2}|[0-9a-zA-Z.+-_]+/",$str,$matches); //prt($matches);
        $ar = &$matches[0];
        $c = "";
        foreach($ar as $val){
                if (substr($val,0,1)!="%"){ //�������ĸ����+-_.��ascii��
                        $c .=$val;
                }elseif(substr($val,1,1)!="u"){ //����Ƿ���ĸ����+-_.��ascii��
                        $x = hexdec(substr($val,1,2));
                        $c .=chr($x);
                }else{ //����Ǵ���0xFF����
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
?>