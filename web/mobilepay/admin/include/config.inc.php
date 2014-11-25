<?
require ("../include/dbsql.inc.php");

class DB_test extends DB_Sql {
  var $Host     = "localhost";              
  var $Database = "db_mobilepay";   
  var $User     = "root";              
  var $Password = "root";
  var $output = "re";  
  }

class DB_shop extends DB_Sql {
	var $Host = "183.62.48.42";
	var $Database = "webshop";
	var $User = "shopuser";
	var $Password = "111111";
	var $output = "re"; 
}
class DB_file extends DB_Sql {
	var $Host = "localhost";
	var $Database = "db_mobilepay";
	var $User = "root";
	var $Password = "";
	var $output = "re"; 
}


class erp_test extends DB_Sql {
  var $Host     = "123.103.62.228";              
  var $Database = "zbsysbk";   
  var $User     = "erp";              
  var $Password = "123456"; 
}
?>