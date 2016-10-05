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
   if ($_POST['active'] == 'N' && $_POST['committee'] >0 ) {
     $error['ctte'] = 'A member cannot be made inactive while still holding a committee position';
	 }
  }
  
if ($_POST && !isset($error)) {
$_POST['datejoined'] = $datejoined;

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "editMember")) {
  $updateSQL = sprintf("UPDATE members SET firstname=%s, familyname=%s, address1=%s, address2=%s, city=%s, postcode=%s, phone=%s, email=%s, photo=%s, datejoined=%s, category=%s, committee=%s, profile=%s, active=%s WHERE mem_ID=%s",
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
                       GetSQLValueString($_POST['active'], "text"),
                       GetSQLValueString($_POST['mem_ID'], "int"));

  mysql_select_db($database_gcConn, $gcConn);
  $Result1 = mysql_query($updateSQL, $gcConn) or die(mysql_error());

  $updateGoTo = "menu.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
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

$colname_rstGetDets = "1";
if (isset($_GET['mem_ID'])) {
  $colname_rstGetDets = (get_magic_quotes_gpc()) ? $_GET['mem_ID'] : addslashes($_GET['mem_ID']);
}
mysql_select_db($database_gcConn, $gcConn);
$query_rstGetDets = sprintf("SELECT * FROM members WHERE mem_ID = %s", $colname_rstGetDets);
$rstGetDets = mysql_query($query_rstGetDets, $gcConn) or die(mysql_error());
$row_rstGetDets = mysql_fetch_assoc($rstGetDets);
$totalRows_rstGetDets = mysql_num_rows($rstGetDets);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Garden Club Admin - Edit Member's Details</title>
<link href="../externals/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
 <?php
if (isset($error)) {
  foreach($error as $message) {
    echo '<p class="warning">'.$message.'</p>';
	}
  }
?>
 <h1>Edit Member's Details </h1>
<form name="editMember" id="editMember" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="600">
    <tr>
      <td>First name: </td>
      <td><input value="<?php if (isset($_POST['firstname'])) { echo $_POST['firstname'];}else{echo $row_rstGetDets['firstname'];} ?>" name="firstname" type="text" class="mediumbox" id="firstname" /></td>
    </tr>
    <tr>
      <td>Family name: </td>
      <td><input value="<?php if (isset($_POST['familyname'])) { echo $_POST['familyname'];}else{echo $row_rstGetDets['familyname'];} ?>" name="familyname" type="text" class="mediumbox" id="familyname" /></td>
    </tr>
    <tr>
      <td>Address 1: </td>
      <td><input value="<?php if (isset($_POST['address1'])) { echo $_POST['address1'];}else{echo $row_rstGetDets['address1'];} ?>" name="address1" type="text" class="mediumbox" id="address1" /></td>
    </tr>
    <tr>
      <td>Address 2: </td>
      <td><input value="<?php if (isset($_POST['address2'])) { echo $_POST['address2'];}else{echo $row_rstGetDets['address2'];} ?>" name="address2" type="text" class="mediumbox" id="address2" /></td>
    </tr>
    <tr>
      <td>City:</td>
      <td><input value="<?php if (isset($_POST['city'])) { echo $_POST['city'];}else{echo $row_rstGetDets['city'];} ?>" name="city" type="text" class="mediumbox" id="city" /></td>
    </tr>
    <tr>
      <td>Postcode:</td>
      <td><input value="<?php if (isset($_POST['postcode'])) { echo $_POST['postcode'];}else{echo $row_rstGetDets['postcode'];} ?>" name="postcode" type="text" class="mediumbox" id="postcode" /></td>
    </tr>
    <tr>
      <td>Phone:</td>
      <td><input value="<?php if (isset($_POST['phone'])) { echo $_POST['phone'];}else{echo $row_rstGetDets['phone'];} ?>" name="phone" type="text" class="mediumbox" id="phone" /></td>
    </tr>
    <tr>
      <td>Email:</td>
      <td><input value="<?php if (isset($_POST['email'])) { echo $_POST['email'];}else{echo $row_rstGetDets['email'];} ?>" name="email" type="text" class="mediumbox" id="email" /></td>
    </tr>
    <tr>
      <td>Photo:</td>
      <td><input value="<?php if (isset($_POST['photo'])) { echo $_POST['photo'];}else{echo $row_rstGetDets['photo'];} ?>" name="photo" type="text" class="mediumbox" id="photo" /></td>
    </tr>
    <tr>
      <td>Date of joining: </td>
      <td><input value="<?php if (isset($_POST['datejoined'])) { echo $_POST['datejoined'];}else{echo mysqlToUSDate($row_rstGetDets['datejoined']);} ?>" name="datejoined" type="text" class="mediumbox" id="datejoined" /></td>
    </tr>
    <tr>
      <td>Membership category: </td>
      <td><select name="category" id="category">
        <?php
do {  
?>
        <option value="<?php echo $row_rstMemCat['cat_ID']?>"<?php if (!(strcmp($row_rstMemCat['cat_ID'], $row_rstGetDets['category']))) {echo 'selected="selected"';} ?>><?php echo $row_rstMemCat['cat_type']?></option>
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
        <option value="0" <?php if (!(strcmp(0, $row_rstGetDets['committee']))) {echo 'selected="selected"';} ?>>Not on committee</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rstCtte['ctte_ID']?>"<?php if (!(strcmp($row_rstCtte['ctte_ID'], $row_rstGetDets['committee']))) {echo 'selected="selected"';} ?>><?php echo $row_rstCtte['position']?></option>
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
      <td><textarea name="profile" id="profile"><?php if (isset($_POST['profile'])) {echo $_POST['profile'];}else{ echo $row_rstGetDets['profile'];} ?></textarea></td>
    </tr>
    <tr>
      <td>Active</td>
      <td>Yes 
        <input <?php if (!(strcmp($row_rstGetDets['active'],"Y"))) {echo 'checked="checked"';} ?> name="active" type="radio" value="Y" /> 
      No 
      <input <?php if (!(strcmp($row_rstGetDets['active'],"N"))) {echo 'checked="checked"';} ?> name="active" type="radio" value="N" /></td>
    </tr>
    <tr>
      <td><input name="mem_ID" type="hidden" id="mem_ID" value="<?php echo $row_rstGetDets['mem_ID']; ?>" /></td>
      <td><input type="submit" name="Submit" value="Update membership details" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="editMember">
</form>
<p><a href="menu.php">Admin menu</a></p>
</body>
</html>
<?php
mysql_free_result($rstMemCat);

mysql_free_result($rstCtte);

mysql_free_result($rstGetDets);

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

// MySQL date conversion functions
function mysqlToUSDate($date) {
  $d = explode('-',$date);
  $d = "$d[1]/$d[2]/$d[0]"; // MM/DD/YYYY format
  return $d;
  }
function mysqlToEuroDate($date) {
  $d = explode('-',$date);
  $d = "$d[2]/$d[1]/$d[0]"; // DD/MM/YYYY format
  return $d;
  }
?>
