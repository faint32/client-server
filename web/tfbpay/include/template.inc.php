<?php
//
// +----------------------------------------------------------------------+
// | PHP version > 4.3.4 & 5.x                                            |
// +----------------------------------------------------------------------+
// | Copyright (c) 2006-2007 toplee.com                                   |
// +----------------------------------------------------------------------+
// | 本文件包含PEAR的PHPLIB模板类扩展功能类定义                           |
// | 本类包含对PEAR的PHPLIB Template类继承，并实现cache、utf8等支持       |
// +----------------------------------------------------------------------+
// | Authors: Michael Lee <webmaster@toplee.com>                          |
// +----------------------------------------------------------------------+
//
// $Id: MyTemplate.class.php,v 1.0 2006/08/28
//


/**
 * 使用帮助：
 * 实例化类     $TPL = new MyTemplate(array $tpl_config)
 * 调用模板     $TPL->setFile($handle, $filename="")
 * 设置block    $TPL->setBlock($parent, $handle, $name="")
 * 解析变量     $TPL->setVar($varname, $value="", $append=false)
 * 解析页面     $TPL->parse($target, $handle, $append=false)
 * 输出页面     $TPL->p($varname) = echo $TPL->get($varname);
 * 检查cache    $TPL->cacheCheck()
 * 写入cache    $TPL->cache($data)
 * 输出cache    $TPL->pCache()
 *
 * 实例化模板类时配置选项$tpl_config格式和说明
 * $tpl_config = array(
 *  'debug'     => false,   //是否显示debug信息
 *  'root'      => 'tpl',   //模板存放路径，目前是相对路径，末尾不包含/
 *  'unknowns'  => 'remove',//模板中未解释的标记是否保留输出
 *  'cache'     => array(
 *      'cache'     => true,        //是否打开cache支持
 *      'root'      => 'tpl_cache/',//cache存放路径，目前支持相对路进，末尾包含/
 *      'hash'      => 3,           //cache缓存文件散列目录级数，比如 a/3/d 
 *      'life_time' => 10,          //cache文件默认失效时间，单位秒
 *      'file_ext'  => 'tpl.html',  //cache文件扩展名
 *      ),
 *  );
 */

class MyTemplate extends Template {
	var $cache = array (); //和cache相关的配置信息，在tpl_config里面设置
	

	var $cache_dir = ''; //当前请求对应的cache存放目录，相对路径末尾包含 /
	var $cache_md5 = ''; //根据script_path得到的md5值，用于路径和cache文件名
	var $cache_file = ''; //包含完整路径的cache文件
	var $cache_ini = ''; //包含网站路径的cache配置文件
	

	var $root_path = ''; //从当前路径开始相对于网站根目录的路径信息
	var $script_path = ''; //当前请求页面的绝对路径，如/test.php?a=b，支持POST
	var $php_self = ''; //当前请求页面的绝对路径，如/test.php
	

	/**
	 * $cache_parse = array(
	 *          'md5'       => '',
	 *          'tpl_count' => 2,
	 *          0   => array(
	 *                  'tpl' => 'aa.tpl',
	 *                  'md5' => 'md51',
	 *                  ),
	 *          1   => array(
	 *                  'tpl' => 'bb.tpl',
	 *                  'md5' => 'md52',
	 *                  ),
	 *          );
	 */
	var $cache_parse = array ();
	
	/**
	 * @Purpose:构造函数
	 * @Param array $tpl_config
	 * @Author Michael Lee <lijl@lesoo.com>
	 * @Return: NULL
	 */
	function MyTemplate($tpl_config = "") {
		$debug = isset ( $tpl_config ['debug'] ) ? $tpl_config ['debug'] : false;
		$root = isset ( $tpl_config ['root'] ) ? $tpl_config ['root'] : ".";
		$unknowns = isset ( $tpl_config ['unknowns'] ) ? $tpl_config ['unknowns'] : 'remove';
		$this->cache = $tpl_config ['cache'];
		
		$this->template ( $root, $unknowns );
		$this->debug = $debug;
		
		//仅在打开了cache支持的配置情况下才启用和cache相关的功能
		if ($this->cache ['cache']) {
			//初始化得到一些当前访问请求对应的路径信息
			$this->_getScriptPath ();
			$this->_getRootpath ();
			$this->_getPhpSelf ();
			
			//取得当前请求对应的cache_dir和cache_md5
			$this->_getCacheFile ();
		}
		
		if ($this->debug) {
			if ($this->cache ['cache'])
				echo '<font color=red>Current Cache md5:' . $this->cache_md5 . '<br></font>';
			else
				echo '<font color=red>Cache Disabled! Just use PHPLIB!<br></font>';
		}
	}
	
	/**
	 * 把当前访问请求结果页面写入cache
	 * 首先要得到当前页面根据php_self得到的md5_file值
	 * 然后根据$this->file数组得到各个模板文件名和路径，分别得到他们的md5_file值
	 * 把以上值写入到cache的ini配置文件，同时把cache页面内容写入cache文件
	 *
	 * @access public
	 * @param string $data
	 * @return true
	 */
	function cache($data) {
		if (! $this->cache ['cache'])
			return;
		
		$ini = "";
		$file = $this->php_self;
		//为了支持cli-cgi模式的php运行环境
		if (substr ( $file, 0, 1 ) == "/")
			$file = $this->root_path . $file;
		$md5 = md5_file ( $file );
		$ini .= "md5 = $md5\r\n";
		
		$count = count ( $this->file );
		$ini .= "tpl_count = $count\r\n";
		
		$i = 0;
		foreach ( $this->file as $k => $v ) {
			$ini .= "[$i]\r\n";
			$ini .= "tpl = \"$v\"\r\n";
			$ini .= "md5 = \"" . md5_file ( $v ) . "\"\r\n";
			$i ++;
		}
		//echo $ini; exit;  //for debug
		

		$this->_mkdir2 ( $this->cache_dir );
		if (! $this->_writeToFile ( $this->cache_ini, $ini ))
			return false;
		if (! $this->_writeToFile ( $this->cache_file, $data ))
			return false;
		
		return true;
	}
	
	/**
	 * 检查当前请求相关的cache是否可用
	 * 1.$this->cache['cache']是否为true 2.是否存在 3.是否过期
	 * 4.是否当前php程序文件有改变 5.是否相关的tpl文件有变动
	 *
	 * @access public
	 * @return bool true/false
	 */
	function checkCache() {
		//1.判断当前是否允许cache
		if (! $this->cache ['cache']) {
			if (DEBUG)
				echo 'cache disabled';
			return FALSE;
		}
		
		//2.判断当前cache_file是否存在
		if (! file_exists ( $this->cache_file ) || ! file_exists ( $this->cache_ini )) {
			if (DEBUG)
				echo 'cache not exists';
			return FALSE;
		}
		
		//3.判断是否过期
		$now = time ();
		$orig = filemtime ( $this->cache_file );
		$chk = $now - $orig;
		if ($chk > $this->cache ['life_time']) {
			if (DEBUG)
				echo 'cache expired';
			return FALSE;
		}
		
		//解析cache的ini配置文件
		$this->_parseCacheIni ();
		
		//判断当前php文件是否有改变
		//取得当前php文件的md5_file值
		$php_self = $this->root_path . substr ( $this->php_self, 1 );
		$md5_script = @md5_file ( $php_self );
		if ($md5_script != $this->cache_parse ['md5']) {
			if ($this->debug)
				echo 'md5 bad';
			return false;
		}
		
		//判断相关的tpl文件是否有变化，方法和前面类似
		for($i = 0; $i < $this->cache_parse ['tpl_count']; $i ++) {
			$tpl_file = $this->cache_parse [$i] ['tpl'];
			$tpl_md5 = $this->cache_parse [$i] ['md5'];
			$tpl_md5_cur = @md5_file ( $tpl_file );
			if ($tpl_md5_cur != $tpl_md5) {
				if ($this->debug)
					echo 'tpl md5 bad: ' . $tpl_file . $tpl_md5_cur . '#' . $tpl_md5;
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * 取得cache，用于页面输出
	 * 去掉cache前面的配置行
	 *
	 * @access public
	 * @return true
	 */
	function pCache() {
		@readfile ( $this->cache_file );
		return true;
	}
	
	/**
	 * 删除cache
	 *
	 * @access public
	 * @return NULL
	 */
	function rmCache() {
		@unlink ( $this->cache_file );
		@unlink ( $this->cache_ini );
		return;
	}
	
	/**
	 * 得到cache文件名，包括路径信息，如./cache/ab/cd/ef/abcdef.....tpl.html
	 *
	 * @access private
	 */
	function _getCacheFile() {
		if (empty ( $this->script_path ))
			$this->_getScriptPath ();
		$md5_string = md5 ( $this->script_path );
		$path = "";
		for($i = 0; $i < $this->cache ['hash']; $i ++)
			$path .= substr ( $md5_string, $i, 1 ) . '/';
		
		$path = $this->cache ['root'] . $path;
		$this->cache_dir = $path;
		$this->cache_md5 = $md5_string;
		$this->cache_file = $path . $md5_string . '.' . $this->cache ['file_ext'];
		$this->cache_ini = $path . $md5_string . '.ini';
	}
	
	/**
	 * 解析cache文件的头四行，得到下列信息
	 * $this->cache_file_parse['php_self_md5'] 当前php程序的原始md5值
	 * $this->cache_file_parse['tpls_count']和['tpls']
	 *
	 * @access private
	 */
	function _parseCacheIni() {
		$this->cache_parse = @parse_ini_file ( $this->cache_ini, true );
		return true;
	}
	
	/**
	 * 把内容写入指定文件
	 *
	 * @access private
	 */
	function _writeToFile($file, $content, $mode = 'w') {
		$oldmask = umask ( 0 );
		$fp = fopen ( $file, $mode );
		if (! $fp)
			return false;
		@fwrite ( $fp, $content );
		@fclose ( $fp );
		@umask ( $oldmask );
		return true;
	}
	
	/**
	 * 创建多级目录
	 * 
	 * @access private
	 */
	function _mkdir2($dir) {
		$dir = @preg_replace ( "/\\\/", "/", $dir );
		$dir = @preg_replace ( "/\/{2,}/", "/", $dir );
		$dir = @explode ( '/', $dir );
		
		$path = "";
		for($i = 0; $i < count ( $dir ); $i ++) {
			$path .= $dir [$i] . '/';
			if (! is_dir ( $path ))
				@mkdir ( $path, 0700 );
		}
		return true;
	}
	
	/**
	 * 取得当前请求的完整路径，用于标示当前cache的唯一性
	 * 目前使用public_setting.inc.php里面取得的SCRIPT_PATH值
	 * 注意：如果web请求通过POST方式进行的提交，那么可能造成结果页面cache有问题
	 * 处理POST的方法，就是在SCRIPT_PATH的基础上，判断$_POST变量是否为空
	 * 如果不为空，则进行serialize()处理，保证post的唯一性
	 *
	 * @access private
	 */
	function _getScriptPath() {
		global $_SERVER, $_POST, $_ENV;
		if ($_ENV ['REQUEST_URI'] or $_SERVER ['REQUEST_URI']) {
			$sp = $_SERVER ['REQUEST_URI'] ? $_SERVER ['REQUEST_URI'] : $_ENV ['REQUEST_URI'];
		} else {
			if ($_ENV ['PATH_INFO'] or $_SERVER ['PATH_INFO']) {
				$sp = $_SERVER ['PATH_INFO'] ? $_SERVER ['PATH_INFO'] : $_ENV ['PATH_INFO'];
			} else if ($_ENV ['REDIRECT_URL'] or $_SERVER ['REDIRECT_URL']) {
				$sp = $_SERVER ['REDIRECT_URL'] ? $_SERVER ['REDIRECT_URL'] : $_ENV ['REDIRECT_URL'];
			} else {
				$sp = $_SERVER ['PHP_SELF'] ? $_SERVER ['PHP_SELF'] : $_ENV ['PHP_SELF'];
			}
			
			if ($_ENV ['QUERY_STRING'] or $_SERVER ['QUERY_STRING']) {
				$sp .= '?' . ($_SERVER ['QUERY_STRING'] ? $_SERVER ['QUERY_STRING'] : $_ENV ['QUERY_STRING']);
			}
		}
		
		$sp = preg_replace ( "/\/{2,}/", "/", $sp );
		$find = array ('"', '<', '>' );
		$replace = array ('&quot;', '&lt;', '&gt;' );
		$sp = str_replace ( $find, $replace, $sp );
		$sp = $this->xss_clean ( $sp );
		
		if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
			$p = @serialize ( $_POST );
			$sp .= "?$p";
		}
		return $sp;
	}
	
	/**
	 * 取得当前请求的脚本文件绝对路径
	 * 用于得到当前文件的md5值，判断当前文件是否被修改过
	 * 当前使用public_setting.inc.php里面得到的PHP_SELF值
	 *
	 * @access private
	 */
	function _getPhpSelf() {
		global $_ENV, $_SERVER;
		
		if ($_ENV ['PHP_SELF'] or $_SERVER ['PHP_SELF'])
			$p = $_ENV ['PHP_SELF'] ? $_ENV ['PHP_SELF'] : $_SERVER ['PHP_SELF'];
		elseif ($_ENV ['SCRIPT_NAME'] or $_SERVER ['SCRIPT_NAME'])
			$p = $_ENV ['SCRIPT_NAME'] ? $_ENV ['SCRIPT_NAME'] : $_SERVER ['SCRIPT_NAME'];
		else
			$p = preg_replace ( '#(\?.*)#', '', $this->script_path );
		
		return $p;
	}
	
	/**
	 * 取得当前请求的脚本文件相对于根目录的相对路径，如 ../../
	 * 当前使用public_setting.inc.php里面得到的ROOT_PATH值
	 *
	 * @access private
	 */
	function _getRootPath() {
		$a = @explode ( "/", $this->script_path );
		$c = @count ( $a );
		$p = "";
		for($i = 0; $i < $c - 2; $i ++)
			$p = "../" . $p;
		
		if ($p == "")
			$p = "./";
		
		return $p;
	}
function xss_clean($data)
 {
 // Fix &entity\n;
 $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
 $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
 $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
 $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
  
 // Remove any attribute starting with "on" or xmlns
 $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
  
 // Remove javascript: and vbscript: protocols
 $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
 $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
 $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
  
 // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
 $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
 $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
 $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);
  
 // Remove namespaced elements (we do not need them)
 $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
 do
 {
         // Remove really unwanted tags
         $old_data = $data;
         $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
 }
 while ($old_data !== $data);
  
 // we are done...
 return $data;
 }
}
?>