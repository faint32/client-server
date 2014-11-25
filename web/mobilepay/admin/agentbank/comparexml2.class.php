<?php
/*
* 本类是一个XML文件的两个对比
*/
class CompareXml
{
	public $filename1 = '';//参考文件
	public $filename2 = '';//对比文件

	public function __construct( $filename1 , $filename2 )
	{
		$this->filename1 = $filename1;
		$this->filename2 = $filename2;
	}

	public function comparexml()//对比XML文件的内容
	{
		$result = array();
		$reference = $this->getxml( $this->filename1 );
		$contrast = $this->getxml( $this->filename2 );
		$result = array_diff_key( $reference , $contrast );
		// $countlist = count($reference);
		// for ($i=0; $i < $countlist; $i++)
		// {
		// 	if (!in_array( $reference[$i] , $contrast ) )
		// 	{
		// 		$result[] = $reference[$i];
		// 	}
		// }
		echo "缺少结点：<pre>";
		var_dump( $result );
		echo "</pre>";
	}

	public function getxml( $xml )//取得XML里面的内容
	{
		// global $result_array;
		$arr = simplexml_load_string($xml);
		$arrkey = $this->xml2array($arr);
		array_multisort($arrkey,SORT_ASC,SORT_STRING);
		return $arrkey;
		// $arr = $this->xml_to_array( $xml );
		// $result_array = NULL;
		// $arrkey = $this->array_multiToSingle( $arr , true );
		// return $arrkey;
	}

	function xml2array($xmlobject)//将XML对象转为数组
	{
		if ($xmlobject)
		{
			foreach ((array)$xmlobject as $k=>$v)
			{
				$data[$k] = !is_string($v) ? $this->xml2array($v) : $v;
			}
			return $data;
		}
	}

	// public function xml_to_array($xml)//xml转为数组
	// {
	// 	$reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
	// 	if(preg_match_all($reg, $xml, $matches))
	// 	{
	// 		$count = count($matches[0]);
	// 		$arr = array();
	// 		for($i = 0; $i < $count; $i++)
	// 		{
	// 			$key = $matches[1][$i];
	// 			$val = $this->xml_to_array( $matches[2][$i] );//递归
	// 			if(array_key_exists($key, $arr))
	// 			{
	// 				if(is_array($arr[$key]))
	// 				{
	// 					if(!array_key_exists(0,$arr[$key]))
	// 					{
	// 						$arr[$key] = array($arr[$key]);
	// 					}
	// 				}else{
	// 					$arr[$key] = array($arr[$key]);
	// 				}
	// 				$arr[$key][] = $val;
	// 			}
	// 			else
	// 			{
	// 				$arr[$key] = $val;
	// 			}
	// 		}
	// 		return $arr;
	// 	}
	// 	else
	// 	{
	// 		return $xml;
	// 	}
	// }

	// public function array_multiToSingle( $array , $clearRepeated = false )//将多维数组合并为一维数组，并且取出数组的键
	// {
	// 	global $result_array;
	// 	if ( !isset($array) || !is_array($array) || empty($array) )
	// 	{
	// 		return false;
	// 	}

	// 	if( !in_array( $clearRepeated , array('true','false','') ) )
	// 	{
	// 		return false;
	// 	}

	// 	foreach ( $array as $key => $value )
	// 	{
	// 		if ( is_array( $value ) )
	// 		{
	// 			$this->array_multiToSingle( $value );
	// 		}
	// 		else
	// 		{
	// 			$result_array[] = $key;
	// 		}
	// 	}

	// 	if ( $clearRepeated )
	// 	{
	// 		$result_array = array_unique( $result_array );
	// 	}
	// 	return $result_array;
	// }
}
$original = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?><operation_request><msgheader version="1.0"><req_key>20110601140000</req_key><req_time>20110601140000</req_time><req_seq>21</req_seq><auth_seq>20110601140000123</auth_seq><mac>3D8633D90FAF576D</mac><channelinfo><authorid>25</authorid><api_name>ApiPayinfo</api_name><api_name_func>readCreditCardglist</api_name_func></channelinfo></msgheader><msgbody><paytype>pay</paytype><msgstart>1</msgstart><msgdisplay>30</msgdisplay></msgbody></operation_request>
XML;
$parallel = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?><GZELINK><INFO><TRX_CODE>100002</TRX_CODE><VERSION>04</VERSION><DATA_TYPE>2</DATA_TYPE><LEVEL>0</LEVEL><USER_NAME>operator</USER_NAME><USER_PASS>1</USER_PASS><REQ_SN>1251793671140</REQ_SN></INFO></GZELINK>
XML;
$test = new CompareXml( $original , $parallel );
$test->comparexml();
?>