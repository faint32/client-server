<?php 
$path = $_SERVER['DOCUMENT_ROOT'].'/webinf_pear'; // ���Զ���� PEAR ·�� 
set_include_path(get_include_path() . PATH_SEPARATOR . $path); // ���� PHP ��������·��Ϊ�� php.ini Ĭ�ϵ�����, �ټ������Զ���� PEAR ·�� 
?>