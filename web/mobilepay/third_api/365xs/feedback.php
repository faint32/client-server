<?php
require_once ("../../include/config.inc.php");

require_once ("../../class/Logger.php");
Logger::configure("../../class/Logger.ini");

$logger = Logger::getLogger('365xs');
$now = time();
$logger->info("��ʼ���������첽���ص�����($now) : " . print_r($_SERVER, true));
$logger->info("��ʼ���������첽���ص�����($now) : " . print_r($_GET, true));
echo "true";

require_once ("mobilerecharge.php");

recharge365xs :: Feedback($_GET, "");