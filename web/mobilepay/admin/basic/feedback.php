<?
$thismenucode = "7n002";
require ("../include/common.inc.php");
$db = new db_test ( );
$gourl = "tb_feedback_b.php";
$gotourl = $gourl . $tempurl;
require ("../include/alledit.1.php");
if(!empty($isreplycontent)){
			$isreply=1;
		}else{
			$isreply=0;
			$isreplytime="";
		}
switch ($action) {
	case "delete" : //删除记录
		$query = "delete  from tb_feedback where fd_feedback_id='$listid'";
		$db->query ( $query );
		require ("../include/alledit.2.php");
		Header ( "Location: $gotourl" );
		
		break;
	case "new":
		$query="insert into tb_feedback(
							  	fd_feedback_authorid    ,  fd_feedback_content  ,  
							  	fd_feedback_linkman   ,  fd_feedback_datetime      ,
								fd_feedback_isreply  ,  fd_feedback_isreplytime,
								fd_feedback_isreplycontent   					
							   )values(
							    '$authorid'  ,  '$content' ,  
								'$linkman' ,  '$datetime'   ,  
								'$isreply',  '$isreplytime'	,
								'$isreplycontent'             
							   )";
		$db->query($query);
		require ("../include/alledit.2.php");
		Header ( "Location: $gotourl" );
		break;
	case "edit" : //编辑记录
		$query = "update tb_feedback set 
						 fd_feedback_content='$content'     ,	fd_feedback_datetime='$datetime'	  , 
						 fd_feedback_isreply='$isreply'		,	fd_feedback_isreplytime='$isreplytime' ,
						 fd_feedback_isreplycontent='$isreplycontent'
						 where fd_feedback_id ='$listid'	";
		$db->query ( $query );
		require ("../include/alledit.2.php");
		Header ( "Location: $gotourl" );
		break;
	default :
		break;
}

$t = new Template ( ".", "keep" );
$t->set_file ( "feedback", "feedback.html" );

if (! empty ( $listid )) {
	// 编辑
	$query = "update tb_feedback set fd_feedback_isread=1,fd_feedback_isreadtime=now() where fd_feedback_id ='$listid'";
	$db->query($query);
	$query = "select * from tb_feedback
			  left join tb_author on fd_author_id = fd_feedback_authorid 
              where fd_feedback_id ='$listid'
             ";
	$db->query ( $query );
	if ($db->nf ()) { //判断查询到的记录是否为空
		$db->next_record (); //读取记录数据 
		$truename = $db->f ( fd_author_truename ); //编号  
		$content = $db->f ( fd_feedback_content ); //内容             
		$linkman = $db->f ( fd_feedback_linkman );
		$datetime = $db->f ( fd_feedback_datetime );
		$isread = $db->f ( fd_feedback_isread );
		$isreadtime = $db->f ( fd_feedback_isreadtime );
		$isreply = $db->f ( fd_feedback_isreply );
		$isreplytime = $db->f ( fd_feedback_isreplytime );
		$isreplycontent = $db->f ( fd_feedback_isreplycontent );
		
		if ($isread) {
			$checked1 = "checked";
		} else {
			$checked2 = "checked";
		}
		if ($isreply) {
			$checked3 = "checked";
		} else {
			$checked4 = "checked";
		}
		$iuputclass="input visiabled";
		$action = "edit";
	}
}else{
	$iuputclass="input";
	$action="new";
}

$t->set_var ( "authorid", $authorid );
$t->set_var ( "truename", $truename );
$t->set_var ( "content", $content );
$t->set_var ( "linkman", $linkman );
$t->set_var ( "listid", $listid );
$t->set_var ( "datetime", $datetime );
$t->set_var ( "isread", $isread );
$t->set_var ( "isreadtime", $isreadtime );
$t->set_var ( "isreply", $isreply );
$t->set_var ( "isreplytime", $isreplytime );
$t->set_var ( "isreplycontent", $isreplycontent );
$t->set_var ( "iuputclass", $iuputclass );
$t->set_var ( "checked1", $checked1 );
$t->set_var ( "checked2", $checked2 );
$t->set_var ( "checked3", $checked3 );
$t->set_var ( "checked4", $checked4 );

$t->set_var ( "action", $action );
$t->set_var ( "gotourl", $gotourl ); // 转用的地址
$t->set_var ( "error", $error );
// 判断权限 

include ("../include/checkqx.inc.php");
$t->set_var ( "skin", $loginskin );
$t->pparse ( "out", "feedback" );

?>