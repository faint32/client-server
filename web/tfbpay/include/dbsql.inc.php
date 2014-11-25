<?php
/*
 * Session Management for PHP3
 *
 * Copyright (c) 1998-2000 NetUSE AG
 *                    Boris Erdmann, Kristian Koehntopp
 *
 * $Id: DB_Sql.inc,v 1.13 2005/02/19 15:20:18 layne_weathers Exp $
 *
 */

class DB_Sql {
	
	/* public: connection parameters */
	var $Host = "";
	var $Database = "";
	var $User = "";
	var $Password = "";
	
	/* public: configuration parameters */
	var $Auto_Free = 1; ## Set to 1 for automatic mysql_free_result()
	var $Debug = 0; ## Set to 1 for debugging messages.
	var $Halt_On_Error = "yes"; ## "yes" (halt with message), "no" (ignore errors quietly), "report" (ignore error, but spit a warning)
	var $PConnect = 0; ## Set to 1 to use persistent database connections
	var $Seq_Table = "db_sequence";
	
	/* public: result array and current row number */
	var $Record = array ();
	var $Row;
	
	/* public: current error number and error text */
	var $Errno = 0;
	var $Error = "";
	
	/* public: this is an api revision, not a CVS revision. */
	var $type = "mysql";
	var $revision = "1.2";
	
	/* private: link and query handles */
	var $Link_ID = 0;
	var $Query_ID = 0;
	
	var $locked = false; ## set to true while we have a lock
	

	/* public: constructor */
	function DB_Sql($query = "") {
		if ($query) {
			$this->query ( $query );
		}
	}
	
	/* public: some trivial reporting */
	function link_id() {
		return $this->Link_ID;
	}
	
	function query_id() {
		return $this->Query_ID;
	}
	
	/* public: connection management */
	function connect($Database = "", $Host = "", $User = "", $Password = "") {
		/* Handle defaults */
		if ("" == $Database)
			$Database = $this->Database;
		if ("" == $Host)
			$Host = $this->Host;
		if ("" == $User)
			$User = $this->User;
		if ("" == $Password)
			$Password = $this->Password;
			
		/* establish connection, select database */
		if (0 == $this->Link_ID) {
			
			if (! $this->PConnect) {
				$this->Link_ID = @mysql_connect ( $Host, $User, $Password );
				mysql_query ( "set names 'GBK'" );
			} else {
				$this->Link_ID = @mysql_pconnect ( $Host, $User, $Password );
				mysql_query ( "set names 'GBK'" );
			}
			if (! $this->Link_ID) {
				$this->connect_failed ( "connect ($Host, $User, \$Password) failed" );
				return 0;
			}
			
			if (! @mysql_select_db ( $Database, $this->Link_ID )) {
				$this->connect_failed ( "cannot use database " . $Database );
				return 0;
			}
		}
		
		return $this->Link_ID;
	}
	
	function connect_failed($message) {
		$this->Halt_On_Error = "yes";
		$this->halt ( $message );
	}
	
	/* public: discard the query result */
	function free() {
		@mysql_free_result ( $this->Query_ID );
		$this->Query_ID = 0;
	}
	
	/* public: perform a query */
	function query($Query_String) {
		/* No empty queries, please, since PHP4 chokes on them. */
		if ($Query_String == "")
      /* The empty query string is passed on from the constructor,
       * when calling the class without a query, e.g. in situations
       * like these: '$db = new DB_Sql_Subclass;'
       */
      return 0;
		
		if (! $this->connect ()) {
			return 0; /* we already complained in connect() about that. */
		}
		;
		
		# New query, discard previous result.
		if ($this->Query_ID) {
			$this->free ();
		}
		
		if ($this->Debug)
			printf ( "Debug: query = %s<br>\n", $Query_String );
		
		$this->Query_ID = @mysql_query ( $Query_String, $this->Link_ID );
		$this->Row = 0;
		$this->Errno = mysql_errno ();
		$this->Error = mysql_error ();
		if (! $this->Query_ID) {
			$this->halt ( "Invalid SQL: " . $Query_String );
		}
		
		# Will return nada if it fails. That's fine.
		return $this->Query_ID;
	}
	
	/* public: walk result set */
	function next_record($type = MYSQL_BOTH) {
		if (! $this->Query_ID) {
			$this->halt ( "next_record called with no query pending." );
			return 0;
		}
		
		$this->Record = @mysql_fetch_array ( $this->Query_ID, $type );
		$this->Row += 1;
		$this->Errno = mysql_errno ();
		$this->Error = mysql_error ();
		
		$stat = is_array ( $this->Record );
		if (! $stat && $this->Auto_Free) {
			$this->free ();
		}
		return $stat;
	}
	
	/* public: position in result set */
	function seek($pos = 0) {
		$status = @mysql_data_seek ( $this->Query_ID, $pos );
		if ($status)
			$this->Row = $pos;
		else {
			$this->halt ( "seek($pos) failed: result has " . $this->num_rows () . " rows." );
			
			/* half assed attempt to save the day, 
       * but do not consider this documented or even
       * desireable behaviour.
       */
			@mysql_data_seek ( $this->Query_ID, $this->num_rows () );
			$this->Row = $this->num_rows ();
			return 0;
		}
		
		return 1;
	}
	
	/* public: table locking */
	function lock($table, $mode = "write") {
		$query = "lock tables ";
		if (is_array ( $table )) {
			while ( list ( $key, $value ) = each ( $table ) ) {
				// text keys are "read", "read local", "write", "low priority write"
				if (is_int ( $key ))
					$key = $mode;
				if (strpos ( $value, "," )) {
					$query .= str_replace ( ",", " $key, ", $value ) . " $key, ";
				} else {
					$query .= "$value $key, ";
				}
			}
			$query = substr ( $query, 0, - 2 );
		} elseif (strpos ( $table, "," )) {
			$query .= str_replace ( ",", " $mode, ", $table ) . " $mode";
		} else {
			$query .= "$table $mode";
		}
		if (! $this->query ( $query )) {
			$this->halt ( "lock() failed." );
			return false;
		}
		$this->locked = true;
		return true;
	}
	
	function unlock() {
		
		// set before unlock to avoid potential loop
		$this->locked = false;
		
		if (! $this->query ( "unlock tables" )) {
			$this->halt ( "unlock() failed." );
			return false;
		}
		return true;
	}
	
	/* public: evaluate the result (size, width) */
	function affected_rows() {
		return @mysql_affected_rows ( $this->Link_ID );
	}
	
	function num_rows() {
		return @mysql_num_rows ( $this->Query_ID );
	}
	
	function num_fields() {
		return @mysql_num_fields ( $this->Query_ID );
	}
	
	/* public: shorthand notation */
	function nf() {
		return $this->num_rows ();
	}
	
	function np() {
		print $this->num_rows ();
	}
	
	function f($Name) {
		if (isset ( $this->Record [$Name] )) {
			return $this->Record [$Name];
		}
	}
	
	function p($Name) {
		if (isset ( $this->Record [$Name] )) {
			print $this->Record [$Name];
		}
	}
	
	/* public: sequence numbers */
	function nextid($seq_name) {
		/* if no current lock, lock sequence table */
		if (! $this->locked) {
			if ($this->lock ( $this->Seq_Table )) {
				$locked = true;
			} else {
				$this->halt ( "cannot lock " . $this->Seq_Table . " - has it been created?" );
				return 0;
			}
		}
		
		/* get sequence number and increment */
		$q = sprintf ( "select nextid from %s where seq_name = '%s'", $this->Seq_Table, $seq_name );
		if (! $this->query ( $q )) {
			$this->halt ( 'query failed in nextid: ' . $q );
			return 0;
		}
		
		/* No current value, make one */
		if (! $this->next_record ()) {
			$currentid = 0;
			$q = sprintf ( "insert into %s values('%s', %s)", $this->Seq_Table, $seq_name, $currentid );
			if (! $this->query ( $q )) {
				$this->halt ( 'query failed in nextid: ' . $q );
				return 0;
			}
		} else {
			$currentid = $this->f ( "nextid" );
		}
		$nextid = $currentid + 1;
		$q = sprintf ( "update %s set nextid = '%s' where seq_name = '%s'", $this->Seq_Table, $nextid, $seq_name );
		if (! $this->query ( $q )) {
			$this->halt ( 'query failed in nextid: ' . $q );
			return 0;
		}
		
		/* if nextid() locked the sequence table, unlock it */
		if ($locked) {
			$this->unlock ();
		}
		
		return $nextid;
	}
	//�ó�insert �� ID ��
	function insert_id() {
		return @mysql_insert_id ( $this->Link_ID );
	}
	
	//�г���ݿ����б�
	function listtables() {
		$this->connect ();
		return @mysql_list_tables ( $this->Database, $this->Link_ID );
	}
	
	//�г���ݿ����б�
	function listfields($tablename) {
		return @mysql_list_fields ( $this->Database, $tablename, $this->Link_ID );
	}
	/* public: return table metadata */
	function metadata($table = "", $full = false) {
		
		$count = 0;
		$id = 0;
		$res = array ();
		
		/*
     * Due to compatibility problems with Table we changed the behavior
     * of metadata();
     * depending on $full, metadata returns the following values:
     *
     * - full is false (default):
     * $result[]:
     *   [0]["table"]  table name
     *   [0]["name"]   field name
     *   [0]["type"]   field type
     *   [0]["len"]    field length
     *   [0]["flags"]  field flags
     *
     * - full is true
     * $result[]:
     *   ["num_fields"] number of metadata records
     *   [0]["table"]  table name
     *   [0]["name"]   field name
     *   [0]["type"]   field type
     *   [0]["len"]    field length
     *   [0]["flags"]  field flags
     *   ["meta"][field name]  index of field named "field name"
     *   This last one could be used if you have a field name, but no index.
     *   Test:  if (isset($result['meta']['myfield'])) { ...
     */
		
		// if no $table specified, assume that we are working with a query
		// result
		if ($table) {
			$this->connect ();
			$id = @mysql_list_fields ( $this->Database, $table );
			if (! $id) {
				$this->halt ( "Metadata query failed." );
				return false;
			}
		} else {
			$id = $this->Query_ID;
			if (! $id) {
				$this->halt ( "No query specified." );
				return false;
			}
		}
		
		$count = @mysql_num_fields ( $id );
		
		// made this IF due to performance (one if is faster than $count if's)
		if (! $full) {
			for($i = 0; $i < $count; $i ++) {
				$res [$i] ["table"] = @mysql_field_table ( $id, $i );
				$res [$i] ["name"] = @mysql_field_name ( $id, $i );
				$res [$i] ["type"] = @mysql_field_type ( $id, $i );
				$res [$i] ["len"] = @mysql_field_len ( $id, $i );
				$res [$i] ["flags"] = @mysql_field_flags ( $id, $i );
			}
		} else { // full
			$res ["num_fields"] = $count;
			
			for($i = 0; $i < $count; $i ++) {
				$res [$i] ["table"] = @mysql_field_table ( $id, $i );
				$res [$i] ["name"] = @mysql_field_name ( $id, $i );
				$res [$i] ["type"] = @mysql_field_type ( $id, $i );
				$res [$i] ["len"] = @mysql_field_len ( $id, $i );
				$res [$i] ["flags"] = @mysql_field_flags ( $id, $i );
				$res ["meta"] [$res [$i] ["name"]] = $i;
			}
		}
		
		// free the result only if we were called on a table
		if ($table) {
			@mysql_free_result ( $id );
		}
		return $res;
	}
	
	/* public: find available table names */
	function table_names() {
		$this->connect ();
		$h = @mysql_query ( "show tables", $this->Link_ID );
		$i = 0;
		while ( $info = @mysql_fetch_row ( $h ) ) {
			$return [$i] ["table_name"] = $info [0];
			$return [$i] ["tablespace_name"] = $this->Database;
			$return [$i] ["database"] = $this->Database;
			$i ++;
		}
		
		@mysql_free_result ( $h );
		return $return;
	}
	/* private: error handling */
	function getData($n = '', $key = '') {
		$arr = array ();
		if ($n) {
			while ( $row = mysql_fetch_assoc ( $this->Query_ID ) ) {
				$arr [$key] [] = $row;
			}
		} else {
			if ($this->nf () == 1) {
				$arr [$key] [] = mysql_fetch_assoc ( $this->Query_ID );
			} else {
				if ($this->nf ()) {
					while ( $row = mysql_fetch_assoc ( $this->Query_ID ) ) {
						$arr [$key] [] = $row;
					}
				}
			}
		}
		return $arr;
	}
	
	function getFiledData($n = '') {
		$arr = array ();
		if ($n) {
			while ( $row = mysql_fetch_assoc ( $this->Query_ID ) ) {
				$arr [] = $row;
			}
		} else {
			if ($this->nf () == 1) {
				$arr = mysql_fetch_assoc ( $this->Query_ID );
			} else {
				if ($this->nf ()) {
					while ( $row = mysql_fetch_assoc ( $this->Query_ID ) ) {
						$arr [] = $row;
					}
				}
			}
		}
		return $arr;
	}
	/* private: error handling */
	function halt($msg) {
		$this->Error = @mysql_error ( $this->Link_ID );
		$this->Errno = @mysql_errno ( $this->Link_ID );
		
		if ($this->locked) {
			$this->unlock ();
		}
		
		if ($this->Halt_On_Error == "no")
			return;
		
		$this->haltmsg ( $msg );
		
		if ($this->Halt_On_Error != "report")
			die ( "Session halted." );
	}
	
	function haltmsg($msg) {
		printf ( "<p><b>Database error:</b> %s<br>\n", $msg );
		printf ( "<b>MySQL Error</b>: %s (%s)</p>\n", $this->Errno, $this->Error );
	}
	//---------------------------------- 
 // 模块: 自定义函数 
 // 功能: 部分实用的数据库处理方法 
 // 作者: heiyeluren 
 // 时间: 2005-12-26 
 //---------------------------------- 

 /** 
  * 方法: execute($sql) 
  * 功能: 执行一条SQL语句，主要针对没有结果集返回的SQL 
  * 参数: $sql 需要执行的SQL语句，例如：execute("DELETE FROM table1 WHERE id = '1'") 
  * 返回: 更新成功返回True，失败返回False 
  */ 
 function execute($sql) 
 { 
  if (empty($sql)) 
  { 
   $this->error("Invalid parameter"); 
  } 
  if (!$this->query($sql)) 
  { 
   return false; 
  } 
  return true; 
 } 

 /** 
  * 方法: get_all($sql) 
  * 功能: 获取SQL执行的所有记录 
  * 参数: $sql  需要执行的SQL,例如: get_all("SELECT * FROM Table1") 
  * 返回: 返回包含所有查询结果的二维数组 
  */ 
 function get_all($sql) 
 { 
  $this->query($sql); 
  $result_array = array(); 
  while($this->next_record()) 
  { 

   $result_array[] = $this->Record; 
  } 
  if (count($result_array)<=0) 
  { 
   return 0; 
  } 
  return $result_array; 
 } 

 /** 
  * 方法: get_one($sql) 
  * 功能: 获取SQL执行的一条记录 
  * 参数: $sql 需要执行的SQL,例如: get_one("SELECT * FROM Table1 WHERE id = '1'") 
  * 返回: 返回包含一条查询结果的一维数组 
  */ 
 function get_one($sql) 
 { 
  $this->query($sql); 
  if (!$this->next_record()) 
  { 
   return 0; 
  } 
  return $this->Record; 
 } 

 /** 
  * 方法: get_limit($sql, $limit) 
  * 功能: 获取SQL执行的指定数量的记录 
  * 参数:  
  * $sql  需要执行的SQL,例如: SELECT * FROM Table1 
  * $limit 需要限制的记录数 
  * 例如  需要获取10条记录, get_limit("SELECT * FROM Table1", 10); 
  * 
  * 返回: 返回包含所有查询结果的二维数组 
  */ 
 function get_limit($sql, $limit) 
 { 
  $this->query($sql); 
  $result_array = array(); 
  for ($i=0; $i<$limit&&$this->next_record(); $i++) 
  { 
   $result_array[] = $this->Record; 
  } 
  if (count($result_array) <= 0) 
  { 
   return 0; 
  } 
  return $result_array; 
 } 

 /** 
  * 方法: limit_query($sql, $start=0, $offset=20, $order="") 
  * 功能: 为分页的获取SQL执行的指定数量的记录 
  * 参数:  
  * $sql  需要执行的SQL,例如: SELECT * FROM Table1 
  * $start 记录的开始数, 缺省为0 
  * $offset 记录的偏移量，缺省为20 
  * $order 排序方式，缺省为空，例如：ORDER BY id DESC 
  * 例如  需要获取从0到10的记录并且按照ID号倒排, get_limit("SELECT * FROM Table1", 0, 10, "ORDER BY id DESC"); 
  * 
  * 返回: 返回包含所有查询结果的二维数组 
  */ 
 function limit_query($sql, $start=0, $offset=20, $order="") 
 { 
  $sql = $sql ." $order  LIMIT $start,$offset"; 
  $this->query($sql); 
  $result = array(); 
  while($this->next_record()) 
  { 
   $result[] = $this->Record; 
  } 
  if (count($result) <=0 ) 
  { 
   return 0; 
  } 
  return $result; 
 } 

 /** 
  * 方法: count($table,$field="*", $where="") 
  * 功能: 统计表中数据总数 
  * 参数:  
  * $table 需要统计的表名 
  * $field 需要统计的字段，默认为* 
  * $where 条件语句，缺省为空 
  * 例如  按照ID统计所有年龄小于20岁的用户, count("user_table", "id", "user_age < 20") 
  * 
  * 返回: 返回统计结果的数字 
  */ 
 function count($table,$field="*", $where="") 
 { 
  $sql = (empty($where) ? "SELECT COUNT($field) FROM $table" : "SELECT COUNT($field) FROM $table WHERE $where"); 
  $result = $this->get_one($sql); 
  if (!is_array($result)) 
  { 
   return 0; 
  } 
  return $result[0]; 
 } 

 /** 
  * 方法: insert($table,$dataArray) 
  * 功能: 插入一条记录到表里 
  * 参数:  
  * $table  需要插入的表名 
  * $dataArray 需要插入字段和值的数组，键为字段名，值为字段值，例如：array("user_name"=>"张三", "user_age"=>"20岁"); 
  * 例如   比如插入用户张三，年龄为20, insert("users", array("user_name"=>"张三", "user_age"=>"20岁")) 
  * 
  * 返回: 插入记录成功返回True，失败返回False 
  */ 
 function insert($table,$dataArray) 
 { 
  if (!is_array($dataArray) || count($dataArray)<=0) 
  { 
   $this->error("Invalid parameter"); 
  } 
  while(list($key,$val) = each($dataArray)) 
  { 
   $field .= "$key,"; 
   $value .= "'$val',"; 
  } 
  $field = substr($field, 0, -1); 
  $value = substr($value, 0, -1); 
  $sql = "INSERT INTO $table ($field) VALUES ($value)"; 
  if (!$this->query($sql)) 
  { 
   return false; 
  } 
  return true; 
 } 

 /** 
  * 方法: update($talbe, $dataArray, $where) 
  * 功能: 更新一条记录 
  * 参数:  
  * $table  需要更新的表名 
  * $dataArray 需要更新字段和值的数组，键为字段名，值为字段值，例如：array("user_name"=>"张三", "user_age"=>"20岁"); 
  * $where  条件语句 
  * 例如   比如更新姓名为张三的用户为李四，年龄为21 
  *    update("users",  array("user_name"=>"张三", "user_age"=>"20岁"),  "user_name='张三'") 
  * 
  * 返回: 更新成功返回True，失败返回False 
  */ 
 function update($talbe, $dataArray, $where) 
 { 
  if (!is_array($dataArray) || count($dataArray)<=0) 
  { 
   $this->error("Invalid parameter"); 
  } 
  while(list($key,$val) = each($dataArray)) 
  { 
   $value .= "$key = '$val',"; 
  } 
  $value = substr($value, 0, -1); 
  $sql = "UPDATE $talbe SET $value WHERE $where"; 
  if (!$this->query($sql)) 
  { 
   return false; 
  } 
  return true; 
 } 

 /** 
  * 方法: delete($table, $where) 
  * 功能: 删除一条记录 
  * 参数:  
  * $table  需要删除记录的表名 
  * $where  需要删除记录的条件语句 
  * 例如   比如要删除用户名为张三的用户，delete("users",  "user_name='张三'") 
  * 
  * 返回: 更新成功返回True，失败返回False 
  */ 
 function delete($table, $where) 
 { 
  if (empty($where)) 
  { 
   $this->error("Invalid parameter"); 
  } 
  $sql = "DELETE FROM $table WHERE $where"; 
  if (!$this->query($sql)) 
  { 
   return false; 
  } 
  return true; 
 } 

 /** 
  * 方法: error($msg="") 
  * 功能: 显示错误信息后中止脚本 
  * 参数: $msg 需要显示的错误信息 
  * 返回: 无返回 
  */ 
 function error($msg="") 
 { 
  echo "<strong>Error</strong>: $msg\n<br>\n"; 
  exit(); 
 } 

 /** 
  * 方法：get_insert_id() 
  * 功能：获取最后插入的ID 
  * 参数: 无参数 
  * 返回：关闭成功返回ID，失败返回0 
  */ 
 function get_insert_id() 
 { 
  return mysql_insert_id($this->Link_ID); 
 } 

 /** 
  * 方法：close() 
  * 功能：关闭当前数据库连接 
  * 参数: 无参数 
  * 返回：关闭成功返回true，失败返回false 
  */ 
 function close() 
 { 
  return mysql_close($this->Link_ID); 
 }  
}
$_php_major_version = substr ( phpversion (), 0, 1 );
if ((4 > $_php_major_version) or ! class_exists ( "DB_Sql" )) {
	class DB_Sql extends DB_Sql {
		function DB_Sql($query = "") {
			$this->DB_Sql ( $query );
		}
	}
}
unset ( $_php_major_version );
?>
