<?   

require ("../include/common.inc.php");

session_unregister("find_whatdofind"); 
session_unregister("find_howdofind");  
session_unregister("find_findwhat"); 

session_unregister("session_analyseorganid"); 
session_unregister("session_begindate");  
session_unregister("session_enddate"); 

unset($find_whatdofind);
unset($find_howdofind);
unset($find_findwhat);

unset($session_analyseorganid);
unset($session_begindate);
unset($session_enddate);

?>