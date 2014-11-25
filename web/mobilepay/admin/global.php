<?php 
$path = $_SERVER['DOCUMENT_ROOT'].'/webinf_pear'; // 你自定义的 PEAR 路径 
set_include_path(get_include_path() . PATH_SEPARATOR . $path); // 设置 PHP 环境变量路径为除 php.ini 默认的以外, 再加上你自定义的 PEAR 路径 
?>