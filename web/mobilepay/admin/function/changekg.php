<?
//计算商品数量的吨数
function changekg($relation3 , $unit , $quantity){
	switch($unit){
		case "令":
		     $kg   = $quantity * $relation3;  //一令有多小千克
		     $dunquantity = $kg/1000;
		     break;
		case "千克":
		     $dunquantity = $quantity/1000;
		     break;
		case "吨":
		     $dunquantity = $quantity;
		     break;
	}
  return $dunquantity ;
}

//计算令数
function changeling($relation1 , $relation2 , $relation3 , $unit , $quantity){
	switch($unit){
		case "张":
		     $ling = $quantity / $relation1;  //一张有多小令
		     $str_ling = $ling;
		     break;
		case "令":
		     $str_ling = $quantity;
		     break;
		case "件":
		     $ling = $quantity * $relation2;  //计算出一件有多小令
		     $str_ling = $ling;
		     break;
		case "千克":
		     $ling   = $quantity / $relation3;  //计算出一千克有多小令
		     $str_ling = $ling;
		     break;
		case "吨":
		     if($relation3==0){
		     	 $ling   = 0;  //计算出一吨有多小令
		     }else{
		       $ling   = 1000*$quantity / $relation3;  //计算出一吨有多小令
		     }
		     
		     $str_ling = $ling;
		     break;
	}
  return $str_ling ;
}

//计算件数
function changejian($relation1 , $relation2 , $relation3 , $unit , $quantity){
	switch($unit){
		case "张":
		     $str_jian = $quantity / $relation1 / $relation2;  //一张有多小件
		     break;
		case "令":
		     if($relation2==0){
		     }else{
		      $str_jian = $quantity / $relation2;  //一令有多小件
		     }
		     break;
		case "件":
		     $str_jian = $quantity;
		     break;
		case "千克":
		     if($relation3==0 || $relation2==0){
		     }else{
		      $str_jian   = $quantity / $relation3 / $relation2;  //计算出一吨有多小件
		     }
		     break;
		case "吨":
		     if($relation3==0 || $relation2==0){
		     }else{
		      $str_jian   = 1000*$quantity / $relation3 / $relation2;  //计算出一吨有多小件
		     }
		     break;
	}
  return $str_jian ;
}
?>