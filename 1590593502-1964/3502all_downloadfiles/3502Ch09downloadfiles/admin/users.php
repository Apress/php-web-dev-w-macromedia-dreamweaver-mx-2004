<?php require_once('../Connections/gcConn.php'); ?>
<?php
session_start();
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if (isset($_POST['username'])) {
mysql_select_db($database_gcConn, $gcConn);
$query_rstUname = "SELECT admin_ID FROM `admin` WHERE username = '$_POST[username]'";
$rstUname = mysql_query($query_rstUname, $gcConn) or die(mysql_error());
$row_rstUname = mysql_fetch_assoc($rstUname);
$totalRows_rstUname = mysql_num_rows($rstUname);
if ($totalRows_rstUname > 0) {
  $error['uname'] = 'That username is already in use. Please choose another.';
  }
}

if (isset($_POST['pwd']) && isset($_POST['con_pwd'])) {
  if ($_POST['pwd'] != $_POST['con_pwd']) {
    $error['pwd'] = 'Your passwords don\'t match.';
    }
  else {
    $_POST['pwd'] = md5($_POST['pwd']);
	}
  }

if (!isset($error)) {

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addUser")) {
  $insertSQL = sprintf("INSERT INTO admin (realname, username, pwd) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['realname'], "text"),
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['pwd'], "text"));

  mysql_select_db($database_gcConn, $gcConn);
  $Result1 = mysql_query($insertSQL, $gcConn) or die(mysql_error());
}
unset($_POST['realname']);
}

if ((isset($_POST['admin_ID'])) && ($_POST['admin_ID'] != "")) {
  $deleteSQL = sprintf("DELETE FROM admin WHERE admin_ID=%s",
                       GetSQLValueString($_POST['admin_ID'], "int"));

  mysql_select_db($database_gcConn, $gcConn);
  $Result1 = mysql_query($deleteSQL, $gcConn) or die(mysql_error());
}

mysql_select_db($database_gcConn, $gcConn);
$query_rstAdmin = "SELECT * FROM `admin` ORDER BY realname ASC";
$rstAdmin = mysql_query($query_rstAdmin, $gcConn) or die(mysql_error());
$row_rstAdmin = mysql_fetch_assoc($rstAdmin);
$totalRows_rstAdmin = mysql_num_rows($rstAdmin);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Garden Club Admin - User setup</title>
<link href="../externals/admin.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
  } if (errors) alert('The following error(s) occurred:\n'+errors);
  document.MM_returnValue = (errors == '');
}
//-->
</script>
</head>

<body>
<?php
if (isset($error)) {
  foreach ($error as $message) {
    echo '<p class="warning">'.$message.'</p>';
	}
  }
?>
<h1>Admin user setup</h1>
<form action="<?php echo $editFormAction; ?>" method="POST" name="addUser" id="addUser" onsubmit="MM_validateForm('realname','','R','username','','R','pwd','','R','con_pwd','','R');return document.MM_returnValue">
  <table width="400">
    <tr>
      <td>Name:</td>
      <td><input value="<?php if (isset($_POST['realname'])) echo $_POST['realname']; ?>" name="realname" type="text" id="realname" /></td>
    </tr>
    <tr>
      <td>Username:</td>
      <td><input name="username" type="text" id="username" /></td>
    </tr>
    <tr>
      <td>Password:</td>
      <td><input name="pwd" type="password" id="pwd" /></td>
    </tr>
    <tr>
      <td>Confirm password: </td>
      <td><input name="con_pwd" type="password" id="con_pwd" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" value="Add new administrator" /></td>
    </tr>
  </table>
    <input type="hidden" name="MM_insert" value="addUser">
</form>
<form name="delUser" id="delUser" method="post" action="">
  <table width="400">
    <?php do { ?>
    <tr>
      <td class="radbut"><input name="admin_ID" type="radio" value="<?php echo $row_rstAdmin['admin_ID']; ?>" /></td>
      <td><?php echo $row_rstAdmin['realname']; ?></td>
    </tr>
    <?php } while ($row_rstAdmin = mysql_fetch_assoc($rstAdmin)); ?>
  </table>
  <p>
    <input type="submit" name="Submit" value="Delete selected administrator" />
  </p>
</form>
<p><a href="menu.php">Admin menu</a>  </p>
</body>
</html>
<?php
if (isset($rstUname)) mysql_free_result($rstUname);

mysql_free_result($rstAdmin);
?>
