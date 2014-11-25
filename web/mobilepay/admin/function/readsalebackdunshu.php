<?

function readsalebackdunshu($year,$month,$memid){
    $db     = new DB_test;
	  $dberp  = new DB_erp2;	
	  
	  $query  = "select * from tb_organmem where fd_organmem_id = '$memid'";
	  $db->query($query); 
	  $db->next_record();
	  $cusid = $db->f(fd_organmem_cusid);  
	  
	  $month  = $month+0;
	  $year   = $year+0;
	  
	  $query = "select 
              sum(fd_sebk_alldunshu) as allquantity,
              fd_sebk_cusid
              from tb_saleback 
              left join tb_salelist on fd_sebk_salelistid = fd_selt_id
              where year(fd_selt_date) = '$year' and month(fd_sebk_date) = '$month' and fd_sebk_state = 1
              and fd_sebk_iskickback = 0 and fd_sebk_organid = 1 and fd_sebk_cusid = '$cusid'
              group by fd_sebk_cusid
             ";
    $dberp->query($query);
    $dberp->next_record();
    $dunshu = $dberp->f(allquantity);
    
    return $dunshu;
}


?>