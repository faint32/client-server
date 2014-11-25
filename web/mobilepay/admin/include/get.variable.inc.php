<?
/*$HTTP_POST_VARS = &$_POST;
$HTTP_GET_VARS = &$_GET;
$HTTP_COOKIE_VARS = &$_COOKIE;
$HTTP_SESSION_VARS = &$_SESSION;
if (getenv('EDISPLAY_PATCH')) {
    if (!isset($HTTP_GET_VARS)) $HTTP_GET_VARS = $_GET;
    if (!isset($HTTP_POST_VARS)) $HTTP_POST_VARS = $_POST;
    if (!isset($HTTP_COOKIE_VARS)) $HTTP_COOKIE_VARS = $_COOKIE;
}
*/
   
 if (sizeof($_POST) > 0)
 $HTTP_POST_VARS = $_POST;
 elseif (sizeof($_GET) > 0)
 $HTTP_GET_VARS = $_GET;
                                              
if(isset($HTTP_POST_VARS))
{
   while(list($key,$val) = each($HTTP_POST_VARS))
    {
     $$key = $val;
   }
}   //post                                                       
                                                           
if(isset($HTTP_GET_VARS))
{
 while (list($key,$val) = each($HTTP_GET_VARS))
{
   $$key = $val;
  }
}    //get
    
                                      
if(isset($HTTP_SESSION_VARS))                              
{                                                          
while (list($key,$val) = each($HTTP_SESSION_VARS))
{
$$key = $val;
  }
}   //session

if(isset($HTTP_SERVER_VARS))                              
{
while (list($key,$val) = each($HTTP_SERVER_VARS))
{
$$key = $val;                                           
  }
}   //server
                                                                                                                   
if(isset($HTTP_COOKIE_VARS))                              
{
while (list($key,$val) = each($HTTP_COOKIE_VARS))
{
$$key = $val;                                           
  }
}   //cookie

?>