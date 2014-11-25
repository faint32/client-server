<?php
/*
*	日志处理类
*	用法：
*	require_once("Logger.php");												// 引入logger类
*	Logger::configure("Logger.ini");										// 加载配置文件
*	$logger = Logger::getLogger('type');									// 返回logger对象,参数type是日志的类型，找不到的使用DEFAULT日志
*	$logger->debug("测试数据");
*	$logger->info("测试数据");
*	$logger->warn("测试数据");
*	$logger->error("测试数据");
*	$logger->fatal("测试数据");
*/
class Logger {
	//日志级别
	private static $LEVEL_LIST = array('EMERG' =>0,'ALERT'=>1,'CRIT'=>2,'ERR'=>3,'WARNING'=>4,'NOTICE'=>5,'INFO'=>6,'DEBUG'=>7);
	//默认配置
	private $TYPE = 'FILE';
	private $LOG_LEVEL = 4;
	private $DESTINATION = '/var/www/log/debug.log';
	private $LOG_FILE_SIZE = 52428800;
	private $MAX_BACKUP_INDEX = 5;
	private $CALLER = 'DXLOG';
	
	public static $CONFIG = array();
	
	public function debug($message, $caller = null) {
		$this->logLevel($message, 'DEBUG', $caller);
	}

	public function info($message, $caller = null) {
		$this->logLevel($message, 'INFO', $caller);
	}

	public function warn($message, $caller = null) {
		$this->logLevel($message, 'WARNING', $caller);
	}

	public function error($message, $caller = null) {
		$this->logLevel($message, 'ERR', $caller);
	}
	
	public function fatal($message, $caller = null) {
		$this->logLevel($message, 'EMERG', $caller);
	}
	//写日志
	private function logLevel($message, $level, $caller){
		$info_level = self::$LEVEL_LIST[$level];
		if(!empty($message) && $info_level<=$this->LOG_LEVEL){
			$now = date('[Y-m-d H:i:s]');
			$caller = $caller?$caller:$this->CALLER;
			if($this->TYPE == 'FILE'){
				$msg = "{$now} {$caller} {$level}: {$message}\r\n";
				if(is_file($this->DESTINATION) && floor($this->LOG_FILE_SIZE) <= filesize($this->DESTINATION)){
					$this->rollOver();
				}
				error_log($msg,3,$this->DESTINATION,'');
			}else{
				$msg = "{$now} {$level}: {$message}";
				openlog($caller,LOG_ODELAY, LOG_USER);
				syslog($info_level, $msg);
				closelog();
			}
		}
	}
	//日志文件整体向后移动，并删除最旧的一个。
	private function rollOver() {
		if($this->MAX_BACKUP_INDEX > 0) {
			$fileName = $this->DESTINATION;
			$file = $fileName . '.' . $this->MAX_BACKUP_INDEX;
			if(is_writable($file))
				unlink($file);
			for($i = $this->MAX_BACKUP_INDEX - 1; $i >= 1; $i--) {
				$file = $fileName . "." . $i;
				if(is_readable($file)) {
					$target = $fileName . '.' . ($i + 1);
					rename($file, $target);
				}
			}
			$target = $fileName . ".1";
			$file = $fileName;
			rename($file, $target);
		}
	}
	//创建日志目录
	private function mk_dir($path,$mode = 0755){
       if (!file_exists($path)){
           $this->mk_dir(dirname($path), $mode);
           mkdir($path, $mode);
       }
   }
	//应用配置并返回logger对象
	public static function getLogger($name = null) {
		static $objarry = array();
		if(isset($objarry[$name]))
			 return $objarry[$name];
		$obj = new self();
		$config = self::$CONFIG[$name]?array_merge(self::$CONFIG['DEFAULT'],self::$CONFIG[$name]):self::$CONFIG['DEFAULT'];
		if($config){
			$config['TYPE']=='SYSLOG'?$obj->TYPE ='SYSLOG':$obj->TYPE ='FILE';
			if($config['LOG_LEVEL'] && array_key_exists($config['LOG_LEVEL'],self::$LEVEL_LIST))
				$obj->LOG_LEVEL = self::$LEVEL_LIST[$config['LOG_LEVEL']];
			if($config['DESTINATION'])
				$obj->DESTINATION = $config['DESTINATION'];
			if(intval($config['LOG_FILE_SIZE']))
				$obj->LOG_FILE_SIZE = intval($config['LOG_FILE_SIZE']);
			if(intval($config['MAX_BACKUP_INDEX']))
				$obj->MAX_BACKUP_INDEX = intval($config['MAX_BACKUP_INDEX']);
		}
		if($name)
			$obj->CALLER = $name;
		$obj->mk_dir(dirname($obj->DESTINATION));
		$objarry[$name] =  $obj;
		return $objarry[$name];
	}
	//加载配置
	public static function configure($configurationFile = null) {
		if(empty($configurationFile)) return;
		static $cofarry = array();
		if(!isset($cofarry[$configurationFile])){
			if(is_file($configurationFile)){
				$extend = pathinfo($configurationFile);
				$extend = strtolower($extend["extension"]);
				if($extend=='ini'){
					self::$CONFIG = parse_ini_file($configurationFile,true);
				}
			}
			$cofarry[$configurationFile] =  true;
		}
	}
}