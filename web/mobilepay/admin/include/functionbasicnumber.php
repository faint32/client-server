<?
function basicnumber_view($listtype){
	$db  = new DB_test;
  
	$query = "select fd_bcnr_design,fd_bcnr_count from tb_basicnumber where fd_bcnr_typeid = '$listtype'";
	$db->query($query);
	if($db->nf()){
		$db->next_record();
		$tmp_basicnumber = $db->f(fd_bcnr_design);
    $ltctcount = $db->f(fd_bcnr_count)+1;
	  	  
	  if($ltctcount<10){
	  	$endltctcount = "000".$ltctcount;
	  }elseif($ltctcount<10000 and $ltctcount>=1000){
	    $endltctcount = $ltctcount;
	  }elseif($ltctcount<1000 and $ltctcount>=100){
	    $endltctcount = "0".$ltctcount;
	  }elseif($ltctcount<100 and $ltctcount>=10){
	    $endltctcount = "00".$ltctcount;
	  }else{
	    $endltctcount = $ltctcount;
	  }
    
    $listno = str_replace("{list_number}", $endltctcount , $tmp_basicnumber);
	}
	return $listno;
}


function basicnumber_update($listtype){
	$db  = new DB_test;

	$query = "select * from tb_basicnumber where fd_bcnr_typeid = '$listtype'";
	$db->query($query);
	if($db->nf()){
		$db->next_record();
		$tmp_basicnumber = $db->f(fd_bcnr_design);
    $ltctcount = $db->f(fd_bcnr_count)+1;
	   
	  	  	$query = "update tb_basicnumber set
	              fd_bcnr_count = fd_bcnr_count+1
	              where fd_bcnr_typeid = '$listtype' ";
	    $db->query($query); 
	   
	  if($ltctcount<10){
	  	$endltctcount = "000".$ltctcount;
	  }elseif($ltctcount<10000 and $ltctcount>=1000){
	    $endltctcount = $ltctcount;
	  }elseif($ltctcount<1000 and $ltctcount>=100){
	    $endltctcount = "0".$ltctcount;
	  }elseif($ltctcount<100 and $ltctcount>=10){
	    $endltctcount = "00".$ltctcount;
	  }else{
	    $endltctcount = $ltctcount;
	  }
	     
    $listno = str_replace("{list_number}", $endltctcount , $tmp_basicnumber);
	}
	return $listno;
}

?>