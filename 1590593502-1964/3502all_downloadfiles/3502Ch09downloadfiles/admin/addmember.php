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

if ($_POST) {
  $datejoined = getTheDate();
  if (strpos($datejoined, 'Error') === 0) {
    $error['date'] = $datejoined;
    }
  }

if ($_POST && !isset($error)) {
$_POST['datejoined'] = $datejoined;

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addNewMember")) {
  $insertSQL = sprintf("INSERT INTO members (firstname, familyname, address1, address2, city, postcode, phone, email, photo, datejoined, category, committee, profile, active) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['firstname'], "text"),
                       GetSQLValueString($_POST['familyname'], "text"),
                       GetSQLValueString($_POST['address1'], "text"),
                       GetSQLValueString($_POST['address2'], "text"),
                       GetSQLValueString($_POST['city'], "text"),
                       GetSQLValueString($_POST['postcode'], "text"),
                       GetSQLValueString($_POST['phone'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['photo'], "text"),
                       GetSQLValueString($_POST['datejoined'], "date"),
                       GetSQLValueString($_POST['category'], "int"),
                       GetSQLValueString($_POST['committee'], "int"),
                       GetSQLValueString($_POST['profile'], "text"),
                       GetSQLValueString($_POST['active'], "text"));

  mysql_select_db($database_gcConn, $gcConn);
  $Result1 = mysql_query($insertSQL, $gcConn) or die(mysql_error());

  $insertGoTo = "menu.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
}
mysql_select_db($database_gcConn, $gcConn);
$query_rstMemCat = "SELECT * FROM category";
$rstMemCat = mysql_query($query_rstMemCat, $gcConn) or die(mysql_error());
$row_rstMemCat = mysql_fetch_assoc($rstMemCat);
$totalRows_rstMemCat = mysql_num_rows($rstMemCat);

mysql_select_db($database_gcConn, $gcConn);
$query_rstCtte = "SELECT * FROM committee";
$rstCtte = mysql_query($query_rstCtte, $gcConn) or die(mysql_error());
$row_rstCtte = mysql_fetch_assoc($rstCtte);
$totalRows_rstCtte = mysql_num_rows($rstCtte);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Garden Club Admin - Add New Member</title>
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
  foreach($error as $message) {
    echo '<p class="warning">'.$message.'</p>';
	}
  }
?>
<h1>Add New Member</h1>
<form action="<?php echo $editFormAction; ?>" method="POST" name="addNewMember" id="addNewMember" onsubmit="MM_validateForm('firstname','','R','familyname','','R','address1','','R','city','','R','postcode','','R','email','','NisEmail');return document.MM_returnValue">
  <table width="600">
    <tr>
      <td>First name: </td>
      <td><input value="<?php if (isset($_POST['firstname'])) echo $_POST['firstname']; ?>" name="firstname" type="text" class="mediumbox" id="firstname" /></td>
    </tr>
    <tr>
      <td>Family name: </td>
      <td><input value="<?php if (isset($_POST['familyname'])) echo $_POST['familyname']; ?>" name="familyname" type="text" class="mediumbox" id="familyname" /></td>
    </tr>
    <tr>
      <td>Address 1: </td>
      <td><input value="<?php if (isset($_POST['address1'])) echo $_POST['address1']; ?>" name="address1" type="text" class="mediumbox" id="address1" /></td>
    </tr>
    <tr>
      <td>Address 2: </td>
      <td><input value="<?php if (isset($_POST['address2'])) echo $_POST['address2']; ?>" name="address2" type="text" class="mediumbox" id="address2" /></td>
    </tr>
    <tr>
      <td>City:</td>
      <td><input value="<?php if (isset($_POST['city'])) echo $_POST['city']; ?>" name="city" type="text" class="mediumbox" id="city" /></td>
    </tr>
    <tr>
      <td>Postcode:</td>
      <td><input value="<?php if (isset($_POST['postcode'])) echo $_POST['postcode']; ?>" name="postcode" type="text" class="mediumbox" id="postcode" /></td>
    </tr>
    <tr>
      <td>Phone:</td>
      <td><input value="<?php if (isset($_POST['phone'])) echo $_POST['phone']; ?>" name="phone" type="text" class="mediumbox" id="phone" /></td>
    </tr>
    <tr>
      <td>Email:</td>
      <td><input value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" name="email" type="text" class="mediumbox" id="email" /></td>
    </tr>
    <tr>
      <td>Photo:</td>
      <td><input value="<?php if (isset($_POST['photo'])) echo $_POST['photo']; ?>" name="photo" type="text" class="mediumbox" id="photo" /></td>
    </tr>
    <tr>
      <td>Date of joining: </td>
      <td><input value="<?php if (isset($_POST['datejoined'])) echo $_POST['datejoined']; ?>" name="datejoined" type="text" class="mediumbox" id="datejoined" /></td>
    </tr>
    <tr>
      <td>Membership category: </td>
      <td><select name="category" id="category">
        <?php
do {  
?>
        <option value="<?php echo $row_rstMemCat['cat_ID']?>"<?php if (!(strcmp($row_rstMemCat['cat_type'], "Regular"))) {echo 'selected="selected"';} ?>><?php echo $row_rstMemCat['cat_type']?></option>
        <?php
} while ($row_rstMemCat = mysql_fetch_assoc($rstMemCat));
  $rows = mysql_num_rows($rstMemCat);
  if($rows > 0) {
      mysql_data_seek($rstMemCat, 0);
	  $row_rstMemCat = mysql_fetch_assoc($rstMemCat);
  }
?>
      </select></td>
    </tr>
    <tr>
      <td>Committee position: </td>
      <td><select name="committee" id="committee">
        <option value="0">Not on committee</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rstCtte['ctte_ID']?>"><?php echo $row_rstCtte['position']?></option>
        <?php
} while ($row_rstCtte = mysql_fetch_assoc($rstCtte));
  $rows = mysql_num_rows($rstCtte);
  if($rows > 0) {
      mysql_data_seek($rstCtte, 0);
	  $row_rstCtte = mysql_fetch_assoc($rstCtte);
  }
?>
      </select></td>
    </tr>
    <tr>
      <td>Profile:</td>
      <td><textarea name="profile" id="profile"><?php if (isset($_POST['profile'])) echo $_POST['profile']; ?></textarea></td>
    </tr>
    <tr>
      <td>Active</td>
      <td>Yes 
      <input name="active" type="radio" value="Y" checked="checked" /> 
      No 
      <input name="active" type="radio" value="N" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" value="Enter membership details" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="addNewMember">
</form>
<p><a href="menu.php">Admin menu</a>  </p>
</body>
</html>
<?php
mysql_free_result($rstMemCat);

mysql_free_result($rstCtte);

function getTheDate() {
  $retVal = ''; // make sure return value empty
  // if date entered, assign to shorter variable for simplicity and process
  if (!empty($_POST['datejoined'])) {
    $d = $_POST['datejoined'];
    if (strpos($d,'/') === false) { // check if slashes used
      $retVal = 'Error: Date should be in MM/DD/YYYY format';
      }
    else { // if slashes, split into array
      $d = explode('/',$d);
      // remove any leading zeros
	  // following two lines for US style
      $month = intval($d[0]);
      $dayNum = intval($d[1]);
	  // if European style required, uncomment the next two lines
	  // $dayNum = intval($d[0]);
	  // $month = intval($d[1]);
      $year = $d[2];

    if (!checkdate($month,$dayNum,$year)) { // check validity of date
      $retVal = 'Error: Date not valid';
      }
    else { // if OK, format for MySQL
      $retVal = $year.'-'.$month.'-'.$dayNum;
      }
    }
  }
  else { // if no date set, use today's date
    $retVal = date('Y-m-d');
    }
  return $retVal; // return formatted date or error message
}
?>
