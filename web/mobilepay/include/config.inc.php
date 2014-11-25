<?
require_once (dirname(__FILE__) . "/" . "../include/dbsql.inc.php");

class dB_Error {
	function __construct($err_info) {
		$retcode = "700";  //数据库错误
		$arr_msg['msgbody']['result'] = 'failure';
		$arr_msg['msgbody']['message'] = $err_info;
		$returnvalue[] = $arr_msg;
	    $return =  TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
	    exit;
	}
}

class DB_test extends DB_Sql {
  var $Host     = "localhost";              
  var $Database = "db_mobilepay";   
  var $User     = "root";              
  var $Password = "root";   
 // var $output = "dB_Error";
}

class DB_file extends DB_Sql {
  var $Host     = "localhost";              
  var $Database = "db_mobilepay";   
  var $User     = "root";              
  var $Password = "";   
  // var $output = "dB_Error";
}

class DB_shop extends DB_Sql {
  var $Host     = "localhost";              
  var $Database = "webshop";   
  var $User     = "root";              
  var $Password = ""; 
  // var $output = "dB_Error";
}

class DB_mssale extends DB_Sql {
	var $Host = "183.62.48.42";
	var $Database = "webshop";
	var $User = "shopuser";
	var $Password = "111111";
	//var $output = "dB_Error";
}

class mssale_test extends DB_Sql {
  var $Host     = "www.tfbpay.cn";

  var $Database = "msweb";

  var $User     = "mrskyzkulapika";

  var $Password = "";
   var $output = "re";
}

class erp_test extends DB_Sql {
  var $Host     = "114.113.225.89";
  var $Database = "zbsysbk";
  var $User     = "root";
  var $Password = "linvin";
   var $output = "re";
}

?>