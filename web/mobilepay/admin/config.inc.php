<?
require ("../include/dbsql.inc.php");

class DB_test extends DB_Sql {
  var $Host     = "localhost";              
  var $Database = "db_mobilepay";   
  var $User     = "root";              
  var $Password = "root"; 
 // var $output = "re";  
  }

class DB_shop extends DB_Sql {
	var $Host = "localhost";
	var $Database = "db_mobilepay";
	var $User = "root";
	var $Password = "root";
	//var $output = "re"; 
}
class DB_file extends DB_Sql {
	var $Host = "localhost";
	var $Database = "db_mobilepay";
	var $User = "root";
	var $Password = "root";
	//var $output = "re"; 
}
?>