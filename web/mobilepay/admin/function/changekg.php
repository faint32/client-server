<?
//������Ʒ�����Ķ���
function changekg($relation3 , $unit , $quantity){
	switch($unit){
		case "��":
		     $kg   = $quantity * $relation3;  //һ���ж�Сǧ��
		     $dunquantity = $kg/1000;
		     break;
		case "ǧ��":
		     $dunquantity = $quantity/1000;
		     break;
		case "��":
		     $dunquantity = $quantity;
		     break;
	}
  return $dunquantity ;
}

//��������
function changeling($relation1 , $relation2 , $relation3 , $unit , $quantity){
	switch($unit){
		case "��":
		     $ling = $quantity / $relation1;  //һ���ж�С��
		     $str_ling = $ling;
		     break;
		case "��":
		     $str_ling = $quantity;
		     break;
		case "��":
		     $ling = $quantity * $relation2;  //�����һ���ж�С��
		     $str_ling = $ling;
		     break;
		case "ǧ��":
		     $ling   = $quantity / $relation3;  //�����һǧ���ж�С��
		     $str_ling = $ling;
		     break;
		case "��":
		     if($relation3==0){
		     	 $ling   = 0;  //�����һ���ж�С��
		     }else{
		       $ling   = 1000*$quantity / $relation3;  //�����һ���ж�С��
		     }
		     
		     $str_ling = $ling;
		     break;
	}
  return $str_ling ;
}

//�������
function changejian($relation1 , $relation2 , $relation3 , $unit , $quantity){
	switch($unit){
		case "��":
		     $str_jian = $quantity / $relation1 / $relation2;  //һ���ж�С��
		     break;
		case "��":
		     if($relation2==0){
		     }else{
		      $str_jian = $quantity / $relation2;  //һ���ж�С��
		     }
		     break;
		case "��":
		     $str_jian = $quantity;
		     break;
		case "ǧ��":
		     if($relation3==0 || $relation2==0){
		     }else{
		      $str_jian   = $quantity / $relation3 / $relation2;  //�����һ���ж�С��
		     }
		     break;
		case "��":
		     if($relation3==0 || $relation2==0){
		     }else{
		      $str_jian   = 1000*$quantity / $relation3 / $relation2;  //�����һ���ж�С��
		     }
		     break;
	}
  return $str_jian ;
}
?>