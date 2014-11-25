<?php
if (!function_exists('json_encode'))   
{   
  function json_encode($a=false)   
  {   
    if (is_null($a)) return 'null';   
    if ($a === false) return 'false';   
    if ($a === true) return 'true';   
    if (is_scalar($a))   
    {   
      if (is_float($a))   
      {   
        // Always use "." for floats.   
        return floatval(str_replace(",", ".", strval($a)));   
      }   
  
      if (is_string($a))   
      {   
        static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));   
        return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';   
      }   
      else  
        return $a;   
    }   
    $isList = true;   
    for ($i = 0, reset($a); $i < count($a); $i++, next($a))   
    {   
      if (key($a) !== $i)   
      {   
        $isList = false;   
        break;   
      }   
    }   
    $result = array();   
    if ($isList)   
    {   
      foreach ($a as $v) $result[] = json_encode($v);   
      return '[' . join(',', $result) . ']';   
    }   
    else  
    {   
      foreach ($a as $k => $v) 
	  {
		  if(is_int(json_encode($k)))
		  {
			 $j_k='"'.json_encode($k).'"' ;
		  }else
		  {
			$j_k= json_encode($k) ;  
		  }
		  $result[] = $j_k.':'.json_encode($v);   
	  }
	  return '{' . join(',', $result) . '}';   
    }   
  }   
} 
function g2u($str)
{
	$value=iconv("gbk", "UTF-8", $str);
	return addslashes($value);
} 

function u2g($str)
{
	$value=iconv( "UTF-8","gbk", $str);
	return addslashes($value);
}
 function auto_charset($fContents, $from='gbk', $to='utf-8') {
        $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
        $to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
        if (strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents))) {
            //��������ͬ���߷��ַ������ת��
            return $fContents;
        }
        if (is_string($fContents)) {
            if (function_exists('mb_convert_encoding')) {
            	if($from=='gbk')
            	{
            		//echo "dfd";
					return stripslashes(mb_convert_encoding($fContents, $to, $from));
            	}else
            	{
                return addslashes(mb_convert_encoding($fContents, $to, $from));
            	}
            	
            } elseif (function_exists('iconv')) {
            	if($from=='gbk')
            	{
            	//	echo "dfd";
					return stripslashes(iconv($from, $to, $fContents));
            	}else
            	{
                return addslashes(iconv($from, $to, $fContents));
            	}
            } else {
            	if($from=='gbk')
            	{
            		
                return stripslashes($fContents);
            	}else
            	{
            			
            	 return addslashes($fContents);
            	}
            	
            }
        } elseif (is_array($fContents)) {
            foreach ($fContents as $key => $val) {
                $_key = auto_charset($key, $from, $to);
                $fContents[$_key] = auto_charset($val, $from, $to);
                if ($key != $_key)
                    unset($fContents[$key]);
            }
            return $fContents;
        }
        else {
            return $fContents;
        }
    }
?>