<?
function creatpro($orderid){
    $db     = new DB_test;
    $db1    = new DB_test;
    $dberp  = new DB_erp;	
    
    //生成没有的条码
    $query = "select * from web_orderdetail
              left join tb_produre on fd_produre_id = fd_orderdetail_productid  
              left join tb_kgweight  on fd_kgweight_id = fd_produre_kgweight  
              where fd_orderdetail_orderid = '$orderid'";
    $db->query($query);
    if($db->nf()){
    	while($db->next_record()){
    		   $commtypeid     = $db->f(fd_produre_catalog);      //商品类别
           $trademarkid    = $db->f(fd_produre_trademarkid);  //品牌
           $kgname         = $db->f(fd_kgweight_name);        //克重
           $unit           = "令";                            //单位
           $long           = $db->f(fd_orderdetail_fqlong);   //长
           $width          = $db->f(fd_orderdetail_fqwidth);  //宽
		       $level			     = $db->f(fd_produre_level); 	      //等级    
		       $detailid			 = $db->f(fd_orderdetail_id);
		       
		       //正度1092*787，大度1194*889
		       if($long == 1092 && $width == 787){//正度
		       	 $spec = 1; 
		       }else if($long == 1194 && $width == 889){//大度
		       	 $spec = 2;
		       }else{
		         $spec = 8; 
		       }
		       
		       $query = "select * from tb_kgweight where fd_kgweight_name = '$kgname'";
		       $db1->query($query);
		       if($db1->nf()){
		         $db1->next_record();
		         $kgweightid	= $db1->f(fd_kgweight_id); 
		       }
		       
		       $query = "select * from tb_normalunit where fd_unit_name = '令'";
		       $db1->query($query);
		       if($db1->nf()){
		         $db1->next_record();
		         $unitid	= $db1->f(fd_unit_id); 
		       }
		       
		       $query = "select * from  tb_trademark where fd_trademark_id = '".$trademarkid."'";
           $db1->query($query);
           if($db1->nf()){
           	 $db1->next_record();
           	 $tmpsuppid = $db1->f(fd_trademark_supid);
           }
                        		             		             
		       $isnopro = 0;
		       
		       //类别
		       $query = "select * from tb_procatalog where fd_proca_id = '$commtypeid'";
		       $db1->query($query);
		       if($db1->nf()){
   	         $db1->next_record();   	                  
   	         $catname = $db1->f(fd_proca_catname);  
   	         $catname = str_replace("(卷筒)","",$catname); 
   	         
   	         $query = "select * from tb_procatalog where fd_proca_catname = '$catname'";
		         $db1->query($query);
   	         if($db1->nf()){  
   	         	 $db1->next_record();   	                   
   	           $commtypeid = $db1->f(fd_proca_id);  
   	           $pycode = $db1->f(fd_proca_pycode); 
   	         }
   	           	                  	                 
		       }
		       
		       
		       $query = "select * from tb_produre 
		                 left join tb_kgweight on fd_kgweight_id = fd_produre_kgweight 
		                 where fd_produre_catalog  = '$commtypeid' 
		                 and fd_produre_trademarkid = '$trademarkid'
		                 and fd_kgweight_name = '$kgname'
		                 and fd_produre_long = '$long'
		                 and fd_produre_width = '$width'
		                 and fd_produre_spec = '$spec'
		                 and fd_produre_level = '$level'
		                ";
		       $db1->query($query);
		       if(!$db1->nf()){
		       	 $isnopro = 1;		             	 	             	 
		       }else{
		         $db1->next_record();
		         $icommid = $db1->f(fd_produre_id);
		         $barcode = $db1->f(fd_produre_barcode);
		         $proname = $db1->f(fd_produre_name);
		       }
		       		             
		       if($isnopro == 1 && $spec == 8){
		         //组合条码
		         if($kgname < 10){
		         	 $barcode = "000".$kgname;
		         }else if($kgname < 100 && $kgname >= 10){
		         	 $barcode = "00".$kgname;
		         }else if($kgname < 1000 && $kgname >= 100){
		         	 $barcode = "0".$kgname;
		         }else if($kgname < 10000 && $kgname >= 1000){
		         	 $barcode = $kgname;
		         }
		          
		         if($width < 10){
		         	 $barcode .= "000".$width;
		         }else if($width < 100 && $width >= 10){
		         	 $barcode .= "00".$width;
		         }else if($width < 1000 && $width >= 100){
		         	 $barcode .= "0".$width;
		         }else if($width < 10000 && $width >= 1000){
		         	 $barcode .= $width;
		         }
		         
		         
		         if($long < 10){
		         	 $barcode .= "000".$long;
		         }else if($long < 100 && $long >= 10){
		         	 $barcode .= "00".$long;
		         }else if($long < 1000 && $long >= 100){
		         	 $barcode .= "0".$long;
		         }else if($long < 10000 && $long >= 1000){
		         	 $barcode .= $long;
		         }
		         
		         //令/包
		         $barcode .= "02000";
		         
		         //品牌
		         $query = "select * from tb_trademark where fd_trademark_id = '$trademarkid'";
		         $db1->query($query);
		         if($db1->nf()){
   	           $db1->next_record();
   	           $code = $db1->f(fd_trademark_code);   
   	           $trademarkname = $db1->f(fd_trademark_name);   
   	           $barcode .= $code;
		         }
		         
		         //类别
		         $barcode .= $pycode;
		         
		         //等级
		         $query = "select * from tb_productlevel  where fd_productlevel_id  = '$level'";
		         $db1->query($query);
		         if($db1->nf()){
   	           $db1->next_record();
   	           $levelname = $db1->f(fd_productlevel_name); 
   	           
   	           if($levelname == "A"){
   	           	 $barcode .= "a";
   	           }else if($levelname == "B"){
   	             $barcode .= "b";
   	           }else if($levelname == "C"){
   	             $barcode .= "c";
   	           }else if($levelname == "D"){
   	             $barcode .= "d";
   	           }else if($levelname == "E"){
   	             $barcode .= "e";
   	           }else if($levelname == "F"){
   	             $barcode .= "f";
   	           }
   	           
		         }
		         
		         $colorid = 4 ;
		         
		         //$spec = 8;
		         
		         $relation1 = 500;
		         
		         $relation2 = 2;
		         
		         $relation3 = ($long*$width*$kgname*$relation1)/1000000000;
		         
		         $relation4 = 2;
		         
		         //正度1092*787，大度1194*889
		         if($long == 1092 && $width == 787){//正度
		         	 $proname = $kgname."克".$trademarkname."正"; 
		         }else if($long == 1194 && $width == 889){//大度
		         	 $proname = $kgname."克".$trademarkname."大";   
		         }else{
		           $proname = $kgname."克".$trademarkname.$width."*".$long;   
		         }
		          		          
		         $query="INSERT INTO tb_produre (
 		                 fd_produre_barcode      ,   fd_produre_name        ,  fd_produre_catalog      ,
 		                 fd_produre_unit         ,   fd_produre_spec        ,  fd_produre_colorid      ,
 		                 fd_produre_level        ,   fd_produre_kgweight    ,  fd_produre_long         ,
 		                 fd_produre_width        ,   fd_produre_relation1   ,  fd_produre_relation2    ,
 		                 fd_produre_trademarkid  ,   fd_produre_quality     ,  fd_produre_relation4    ,
 		                 fd_produre_relation3    ,   fd_produre_suppid
  	                 )VALUES (
  	                 '$barcode'                ,   '$proname'               ,  '$commtypeid'       ,
  	                 '$unitid'                 ,   '$spec'                  ,  '$colorid '         , 
  	                 '$level'                  ,   '$kgweightid'            ,  '$long '            ,        
  	                 '$width'                  ,   '$relation1'             ,  '$relation2'        , 
  	                 '$trademarkid'            ,   '$quality'               ,  '$relation4'        ,
  	                 '$relation3'              ,   '$tmpsuppid'            
  	                 )";                       
		          $dberp->query($query);
		          $icommid = $dberp->insert_id();
		          		          
		          $query="INSERT INTO tb_produre (
 		                 fd_produre_barcode      ,   fd_produre_name        ,  fd_produre_catalog      ,
 		                 fd_produre_unit         ,   fd_produre_spec        ,  fd_produre_colorid      ,
 		                 fd_produre_level        ,   fd_produre_kgweight    ,  fd_produre_long         ,
 		                 fd_produre_width        ,   fd_produre_relation1   ,  fd_produre_relation2    ,
 		                 fd_produre_trademarkid  ,   fd_produre_quality     ,  fd_produre_relation4    ,
 		                 fd_produre_relation3    ,   fd_produre_suppid      ,  fd_produre_id
  	                 )VALUES (
  	                 '$barcode'                ,   '$proname'               ,  '$commtypeid'       ,
  	                 '$unitid'                 ,   '$spec'                  ,  '$colorid '         , 
  	                 '$level'                  ,   '$kgweightid'            ,  '$long '            ,        
  	                 '$width'                  ,   '$relation1'             ,  '$relation2'        , 
  	                 '$trademarkid'            ,   '$quality'               ,  '$relation4'        ,
  	                 '$relation3'              ,   '$tmpsuppid'             ,  '$icommid'       
  	                 )";                       
		          $db1->query($query); 		          		                  
		       } 
		       
		       $query = "update web_orderdetail set 
		                 fd_orderdetail_icommid = '$icommid' , fd_orderdetail_icommbar = '$barcode' ,  fd_orderdetail_icommname = '$proname'
		                 where fd_orderdetail_id = '$detailid'
		                ";
		       $db1->query($query); 	
		       
    	}
    }
}


?>