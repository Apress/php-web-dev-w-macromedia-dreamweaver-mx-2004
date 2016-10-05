<?php require_once('../Connections/gcConn.php'); ?>
<?php
// *** Validate request to login to this site.
session_start();

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($accesscheck)) {
  // Dreamweaver generated sessions code disabled
  /*
  $GLOBALS['PrevUrl'] = $accesscheck;
  session_register('PrevUrl');
  */
  // Replaced by session variable compatible with register_globals off
  $_SESSION['PrevUrl'] = $accesscheck;
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=md5($_POST['pwd']);
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "menu.php";
  $MM_redirectLoginFailed = "failure.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_gcConn, $gcConn);
  
  $LoginRS__query=sprintf("SELECT username, pwd FROM admin WHERE username='%s' AND pwd='%s'",
    get_magic_quotes_gpc() ? $loginUsername : addslashes($loginUsername), get_magic_quotes_gpc() ? $password : addslashes($password)); 
   
  $LoginRS = mysql_query($LoginRS__query, $gcConn) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	// Obsolete Dreamweaver code disabled
	/*
    //declare two session variables and assign them
    $GLOBALS['MM_Username'] = $loginUsername;
    $GLOBALS['MM_UserGroup'] = $loginStrGroup;	      

    //register the session variables
    session_register("MM_Username");
    session_register("MM_UserGroup");
	*/
	
  // Replaced by session variable compatible with register_globals off
	$_SESSION['MM_Username'] = $loginUsername;
	$_SESSION['MM_UserGroup'] = $loginStrGroup;

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Garden Club Admin - login</title>
<link href="../externals/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Garden Club Admin</h1>
<form ACTION="<?php echo $loginFormAction; ?>" name="adminLogin" id="adminLogin" method="POST">
  <table width="400">
    <tr>
      <td>Username:</td>
      <td><input name="username" type="text" id="username" /></td>
    </tr>
    <tr>
      <td>Password:</td>
      <td><input name="pwd" type="password" id="pwd" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" value="Login" /></td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
</body>
</html>
