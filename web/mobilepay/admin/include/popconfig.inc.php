<?
require ("../include/dbsql.inc.php");

class DB_test extends DB_Sql {
  var $Host     = "localhost";              
  var $Database = "webshop";   
  var $User     = "shopuser";              
  var $Password = "111111";            
}

class DB_msweb extends DB_Sql {
  var $Host     = "localhost";              
  var $Database = "msweb";   
  var $User     = "root";              
  var $Password = "linvin";            
}     
 
class DB_erp extends DB_Sql {
  var $Host     = "114.113.225.89";              
  var $Database = "zbsysbk";   
  var $User     = "root";              
  var $Password = "linvin";            
}
?>