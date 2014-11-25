<?php 
header('Content-Type:text/html;charset=utf-8');
require_once("../lib/upmp_service.php");

  $file="bank".date('y-m-dH-i-s').".txt"; 
  $filehandle=fopen($file, "w"); 
  fwrite($filehandle,$nvp."<br>结果：<br>".$transStatus); 
  fclose($filehandle);
if (UpmpService::verifySignature($_POST)){// 服务器签名验证成功
	//请在这里加上商户的业务逻辑程序代码
	//获取通知返回参数，可参考接口文档中通知参数列表(以下仅供参考)
	
	$transStatus = $_POST['transStatus'];// 交易状态
	
	 

	if (""!=$transStatus && "00"==$transStatus){
		// 交易处理成功
	}else {
	}
	echo "success";
}else {// 服务器签名验证失败
	echo "fail";
}
 
?>