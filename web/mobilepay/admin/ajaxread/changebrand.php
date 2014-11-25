<?
header('Content-Type:text/html;charset=GB2312'); 

require ("../include/common.inc.php");

$db   = new DB_test;

$query = "select * from tb_procatalog
left join tb_produre on fd_produre_catalog = fd_proca_id
where fd_produre_trademarkid = '$brandid' group by fd_produre_catalog
";

$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$arr_id[] = $db->f(fd_proca_id);
		$arr_name[] = $db->f(fd_proca_catname);
	}
}
$setprocaid = makeselect($arr_name,$setprocaid,$arr_id);

$arr_id = "";
$arr_name = "";

echo $setprocaid;
?>