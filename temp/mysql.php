<?php
include (__DIR__.'\mysql_shim.php');
$path_to_root_dir['sitedir']='';
$hostname='localhost';
//$username='root';
//$password='';
//$basename='raspisanie';

//beget
$username='b91651m6_base';
$password='b91651m6A031183';
$basename='b91651m6_base';

mysql_connect($hostname, $username, $password) or die (mysql_error());
mysql_select_db($basename) or die (mysql_error());
mysql_query("set character_set_client	='utf8'");
mysql_query("set character_set_results	='utf8'");
mysql_query("set collation_connection	='utf8_general_ci'");
?>