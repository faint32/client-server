<?
$thismenucode = "2k110";
require ("../include/common.inc.php");

$db = new db_test ( );

$gourl = "tb_dept_b.php";
$gotourl = $gourl . $tempurl;
require ("../include/alledit.1.php");

switch ($action) {
	case "new" : //������¼
		$query = "select * from tb_dept where fd_dept_no='$no'";
		$db->query ( $query );
		if ($db->nf () > 0) {
			$error = "�˲��ű���Ѿ����ڣ����������룡";
		} else {
			$query = "insert into tb_dept (
	     	                    fd_dept_no    ,  fd_dept_name         , fd_dept_address          ,   
	     	                    fd_dept_phone ,  fd_dept_functionary  , fd_dept_memo             ,
	     	                    fd_dept_fid                       
	     	                    )values(
	     	                    '$no'            ,  '$name'                 ,   '$address'       ,      
	     	                    '$phone'         ,  '$staffid'              ,   '$memo'          ,
	     	                    '$fid'          
	     	                    )";
			$db->query ( $query );
			Header ( "Location: $gotourl" );
		}
		break;
	
	case "delete" : //ɾ����¼
		$query = "delete  from tb_dept where fd_dept_id='$id'";
		$db->query ( $query );
		require ("../include/alledit.2.php");
		Header ( "Location: $gotourl");
		break;
	case "edit" : //�༭��¼
		$query = "select * from tb_dept where fd_dept_id<>'$id' 
	              and fd_dept_no ='$no'";
		$db->query ( $query );
		if ($db->nf () > 0) {
			$error = "������ű�Ŵ���,���������";
		} else {
			$query = "update tb_dept set 
	      	                 fd_dept_no='$no'                    ,  fd_dept_name='$name'     , 
	      	                 fd_dept_address='$address'          ,  fd_dept_phone='$phone'   , 
	      	                 fd_dept_functionary='$staffid'      ,  fd_dept_memo='$memo'     ,
	      	                 fd_dept_fid='$fid'                     
	                         where fd_dept_id ='$id' ";
			$db->query ( $query );
			require ("../include/alledit.2.php");
			Header ( "Location: $gotourl" );
		}
		
		break;
	default :
		break;
}

$t = new Template ( ".", "keep" );
$t->set_file ( "dept", "dept.html" );

if (empty ( $id )) {
	$action = "new";
} else { // �༭
	$query = "select * from tb_dept
              left join tb_staffer on fd_sta_id = fd_dept_functionary 
               where fd_dept_id ='$id'";
	$db->query ( $query );
	if ($db->nf ()) { //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
		$db->next_record (); //��ȡ��¼���� 
		$id = $db->f ( fd_dept_id ); //id              
		$no = $db->f ( fd_dept_no ); //���  
		$name = $db->f ( fd_dept_name ); //������              
		$address = $db->f ( fd_dept_address ); //��ַ
		$phone = $db->f ( fd_dept_phone ); //��ϵ�绰
		$staffid = $db->f ( fd_dept_functionary ); //������
		$memo = $db->f ( fd_dept_memo ); //˵��
		$staffname = $db->f ( fd_sta_name );
		$fid = $db->f ( fd_dept_fid );
		$action = "edit";
	}
}

//�������������˵�
if (! empty ( $id )) {
	$query = "select * from tb_dept where fd_dept_id != '$id' and fd_dept_fid != '$id'";
} else {
	$query = "select * from tb_dept ";
}

$db->query ( $query );
if ($db->nf ()) {
	while ( $db->next_record () ) {
		$arr_dept_easyname [] = $db->f ( fd_dept_name );
		$arr_dept_id [] = $db->f ( fd_dept_id );
	}
}

$fid = makeselect ( $arr_dept_easyname, $fid, $arr_dept_id );

$t->set_var ( "id", $id );
$t->set_var ( "no", $no );
$t->set_var ( "name", $name );
$t->set_var ( "address", $address );
$t->set_var ( "phone", $phone );
$t->set_var ( "functionary", $functionary );
$t->set_var ( "memo", $memo );
$t->set_var ( "staffname", $staffname );
$t->set_var ( "staffid", $staffid );
$t->set_var ( "fid", $fid );

$t->set_var ( "action", $action );
$t->set_var ( "gotourl", $gotourl ); // ת�õĵ�ַ
$t->set_var ( "error", $error );
// �ж�Ȩ�� 
include ("../include/checkqx.inc.php");
$t->set_var ( "skin", $loginskin );
$t->pparse ( "out", "dept" ); //����������


//��֤���ź���
function checkdeptid($fid) {
	$db1 = new db_test ( );
	$query = "select * from tb_dept where fd_dept_id != '$fid'";
	$db1->query ( $query );
	if ($db1->nf ()) {
		$db1->next_record ();
		$check_id = $db1->f ( fd_dept_fid );
		checkdeptid ( $check_id );
	}
	return $check_id;
}

//����ѡ��˵��ĺ���


?>