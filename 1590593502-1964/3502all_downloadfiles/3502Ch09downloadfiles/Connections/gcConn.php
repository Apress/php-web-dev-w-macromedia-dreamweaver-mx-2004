<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_gcConn = "localhost";
$database_gcConn = "gardenclub";
$username_gcConn = "apress";
$password_gcConn = "apress";
$gcConn = mysql_pconnect($hostname_gcConn, $username_gcConn, $password_gcConn) or trigger_error(mysql_error(),E_USER_ERROR); 
?>