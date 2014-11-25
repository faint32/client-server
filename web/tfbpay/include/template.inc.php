<?php
//
// +----------------------------------------------------------------------+
// | PHP version > 4.3.4 & 5.x                                            |
// +----------------------------------------------------------------------+
// | Copyright (c) 2006-2007 toplee.com                                   |
// +----------------------------------------------------------------------+
// | ���ļ�����PEAR��PHPLIBģ������չ�����ඨ��                           |
// | ���������PEAR��PHPLIB Template��̳У���ʵ��cache��utf8��֧��       |
// +----------------------------------------------------------------------+
// | Authors: Michael Lee <webmaster@toplee.com>                          |
// +----------------------------------------------------------------------+
//
// $Id: MyTemplate.class.php,v 1.0 2006/08/28
//


/**
 * ʹ�ð�����
 * ʵ������     $TPL = new MyTemplate(array $tpl_config)
 * ����ģ��     $TPL->setFile($handle, $filename="")
 * ����block    $TPL->setBlock($parent, $handle, $name="")
 * ��������     $TPL->setVar($varname, $value="", $append=false)
 * ����ҳ��     $TPL->parse($target, $handle, $append=false)
 * ���ҳ��     $TPL->p($varname) = echo $TPL->get($varname);
 * ���cache    $TPL->cacheCheck()
 * д��cache    $TPL->cache($data)
 * ���cache    $TPL->pCache()
 *
 * ʵ����ģ����ʱ����ѡ��$tpl_config��ʽ��˵��
 * $tpl_config = array(
 *  'debug'     => false,   //�Ƿ���ʾdebug��Ϣ
 *  'root'      => 'tpl',   //ģ����·����Ŀǰ�����·����ĩβ������/
 *  'unknowns'  => 'remove',//ģ����δ���͵ı���Ƿ������
 *  'cache'     => array(
 *      'cache'     => true,        //�Ƿ��cache֧��
 *      'root'      => 'tpl_cache/',//cache���·����Ŀǰ֧�����·����ĩβ����/
 *      'hash'      => 3,           //cache�����ļ�ɢ��Ŀ¼���������� a/3/d 
 *      'life_time' => 10,          //cache�ļ�Ĭ��ʧЧʱ�䣬��λ��
 *      'file_ext'  => 'tpl.html',  //cache�ļ���չ��
 *      ),
 *  );
 */

class MyTemplate extends Template {
	var $cache = array (); //��cache��ص�������Ϣ����tpl_config��������
	

	var $cache_dir = ''; //��ǰ�����Ӧ��cache���Ŀ¼�����·��ĩβ���� /
	var $cache_md5 = ''; //����script_path�õ���md5ֵ������·����cache�ļ���
	var $cache_file = ''; //��������·����cache�ļ�
	var $cache_ini = ''; //������վ·����cache�����ļ�
	

	var $root_path = ''; //�ӵ�ǰ·����ʼ�������վ��Ŀ¼��·����Ϣ
	var $script_path = ''; //��ǰ����ҳ��ľ���·������/test.php?a=b��֧��POST
	var $php_self = ''; //��ǰ����ҳ��ľ���·������/test.php
	

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
	 * @Purpose:���캯��
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
		
		//���ڴ���cache֧�ֵ���������²����ú�cache��صĹ���
		if ($this->cache ['cache']) {
			//��ʼ���õ�һЩ��ǰ���������Ӧ��·����Ϣ
			$this->_getScriptPath ();
			$this->_getRootpath ();
			$this->_getPhpSelf ();
			
			//ȡ�õ�ǰ�����Ӧ��cache_dir��cache_md5
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
	 * �ѵ�ǰ����������ҳ��д��cache
	 * ����Ҫ�õ���ǰҳ�����php_self�õ���md5_fileֵ
	 * Ȼ�����$this->file����õ�����ģ���ļ�����·�����ֱ�õ����ǵ�md5_fileֵ
	 * ������ֵд�뵽cache��ini�����ļ���ͬʱ��cacheҳ������д��cache�ļ�
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
		//Ϊ��֧��cli-cgiģʽ��php���л���
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
	 * ��鵱ǰ������ص�cache�Ƿ����
	 * 1.$this->cache['cache']�Ƿ�Ϊtrue 2.�Ƿ���� 3.�Ƿ����
	 * 4.�Ƿ�ǰphp�����ļ��иı� 5.�Ƿ���ص�tpl�ļ��б䶯
	 *
	 * @access public
	 * @return bool true/false
	 */
	function checkCache() {
		//1.�жϵ�ǰ�Ƿ�����cache
		if (! $this->cache ['cache']) {
			if (DEBUG)
				echo 'cache disabled';
			return FALSE;
		}
		
		//2.�жϵ�ǰcache_file�Ƿ����
		if (! file_exists ( $this->cache_file ) || ! file_exists ( $this->cache_ini )) {
			if (DEBUG)
				echo 'cache not exists';
			return FALSE;
		}
		
		//3.�ж��Ƿ����
		$now = time ();
		$orig = filemtime ( $this->cache_file );
		$chk = $now - $orig;
		if ($chk > $this->cache ['life_time']) {
			if (DEBUG)
				echo 'cache expired';
			return FALSE;
		}
		
		//����cache��ini�����ļ�
		$this->_parseCacheIni ();
		
		//�жϵ�ǰphp�ļ��Ƿ��иı�
		//ȡ�õ�ǰphp�ļ���md5_fileֵ
		$php_self = $this->root_path . substr ( $this->php_self, 1 );
		$md5_script = @md5_file ( $php_self );
		if ($md5_script != $this->cache_parse ['md5']) {
			if ($this->debug)
				echo 'md5 bad';
			return false;
		}
		
		//�ж���ص�tpl�ļ��Ƿ��б仯��������ǰ������
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
	 * ȡ��cache������ҳ�����
	 * ȥ��cacheǰ���������
	 *
	 * @access public
	 * @return true
	 */
	function pCache() {
		@readfile ( $this->cache_file );
		return true;
	}
	
	/**
	 * ɾ��cache
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
	 * �õ�cache�ļ���������·����Ϣ����./cache/ab/cd/ef/abcdef.....tpl.html
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
	 * ����cache�ļ���ͷ���У��õ�������Ϣ
	 * $this->cache_file_parse['php_self_md5'] ��ǰphp�����ԭʼmd5ֵ
	 * $this->cache_file_parse['tpls_count']��['tpls']
	 *
	 * @access private
	 */
	function _parseCacheIni() {
		$this->cache_parse = @parse_ini_file ( $this->cache_ini, true );
		return true;
	}
	
	/**
	 * ������д��ָ���ļ�
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
	 * �����༶Ŀ¼
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
	 * ȡ�õ�ǰ���������·�������ڱ�ʾ��ǰcache��Ψһ��
	 * Ŀǰʹ��public_setting.inc.php����ȡ�õ�SCRIPT_PATHֵ
	 * ע�⣺���web����ͨ��POST��ʽ���е��ύ����ô������ɽ��ҳ��cache������
	 * ����POST�ķ�����������SCRIPT_PATH�Ļ����ϣ��ж�$_POST�����Ƿ�Ϊ��
	 * �����Ϊ�գ������serialize()������֤post��Ψһ��
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
	 * ȡ�õ�ǰ����Ľű��ļ�����·��
	 * ���ڵõ���ǰ�ļ���md5ֵ���жϵ�ǰ�ļ��Ƿ��޸Ĺ�
	 * ��ǰʹ��public_setting.inc.php����õ���PHP_SELFֵ
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
	 * ȡ�õ�ǰ����Ľű��ļ�����ڸ�Ŀ¼�����·������ ../../
	 * ��ǰʹ��public_setting.inc.php����õ���ROOT_PATHֵ
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