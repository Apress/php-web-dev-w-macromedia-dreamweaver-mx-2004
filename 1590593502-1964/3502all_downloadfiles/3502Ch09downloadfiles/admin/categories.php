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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "CtteAdd")) {
  $insertSQL = sprintf("INSERT INTO committee (`position`) VALUES (%s)",
                       GetSQLValueString($_POST['position'], "text"));

  mysql_select_db($database_gcConn, $gcConn);
  $Result1 = mysql_query($insertSQL, $gcConn) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "AddCat")) {
  $insertSQL = sprintf("INSERT INTO category (cat_type) VALUES (%s)",
                       GetSQLValueString($_POST['cat_type'], "text"));

  mysql_select_db($database_gcConn, $gcConn);
  $Result1 = mysql_query($insertSQL, $gcConn) or die(mysql_error());
}

if ((isset($_POST['ctte_ID'])) && ($_POST['ctte_ID'] != "")) {
  $deleteSQL = sprintf("DELETE FROM committee WHERE ctte_ID=%s",
                       GetSQLValueString($_POST['ctte_ID'], "int"));

  mysql_select_db($database_gcConn, $gcConn);
  $Result1 = mysql_query($deleteSQL, $gcConn) or die(mysql_error());
}

if ((isset($_POST['cat_ID'])) && ($_POST['cat_ID'] != "")) {
  $deleteSQL = sprintf("DELETE FROM category WHERE cat_ID=%s",
                       GetSQLValueString($_POST['cat_ID'], "int"));

  mysql_select_db($database_gcConn, $gcConn);
  $Result1 = mysql_query($deleteSQL, $gcConn) or die(mysql_error());
}

mysql_select_db($database_gcConn, $gcConn);
$query_rstCtteList = "SELECT DISTINCT committee.ctte_ID, committee.`position`, members.committee FROM committee LEFT JOIN members ON committee.ctte_ID = members.committee";
$rstCtteList = mysql_query($query_rstCtteList, $gcConn) or die(mysql_error());
$row_rstCtteList = mysql_fetch_assoc($rstCtteList);
$totalRows_rstCtteList = mysql_num_rows($rstCtteList);

mysql_select_db($database_gcConn, $gcConn);
$query_rstCatList = "SELECT DISTINCT category.cat_ID, category.cat_type, members.category FROM category LEFT JOIN members ON category.cat_ID = members.category ORDER BY category.cat_ID";
$rstCatList = mysql_query($query_rstCatList, $gcConn) or die(mysql_error());
$row_rstCatList = mysql_fetch_assoc($rstCatList);
$totalRows_rstCatList = mysql_num_rows($rstCatList);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Committee and membership categories</title>
<link href="../externals/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>

<h1>Category control</h1>
<h2>Committee</h2>
<form name="CtteAdd" id="CtteAdd" method="POST" action="<?php echo $editFormAction; ?>">
  <p>Position 
    <input name="position" type="text" id="position" />
    <input type="submit" name="Submit" value="Add" />
  </p>
    <input type="hidden" name="MM_insert" value="CtteAdd">
</form>

<form name="CtteDel" id="CtteDel" method="post" action="">
  <table width="400">
    <?php do { ?>
    <tr>
      <td class="radbut">
	  <?php if ($row_rstCtteList['ctte_ID']!= $row_rstCtteList['committee']) { ?>
	  <input name="ctte_ID" type="radio" value="<?php echo $row_rstCtteList['ctte_ID']; ?>" />
	  <?php } else { echo '&nbsp;'; } ?>
	  </td>
      <td><?php echo $row_rstCtteList['position']; ?></td>
    </tr>
    <?php } while ($row_rstCtteList = mysql_fetch_assoc($rstCtteList)); ?>
  </table>
  <p>
    <input type="submit" name="Submit" value="Delete selected position" />
  </p>
</form>
<h2>Membership categories</h2>
<form name="AddCat" id="AddCat" method="POST" action="<?php echo $editFormAction; ?>">
  <p>Category 
    <input name="cat_type" type="text" id="cat_type" />
    <input type="submit" name="Submit" value="Add" />
</p>
    <input type="hidden" name="MM_insert" value="AddCat">
</form>

<form name="CatDel" id="CatDel" method="post" action="">
  <table width="400">
    <?php do { ?>
    <tr>
      <td class="radbut">
	  <?php if ($row_rstCatList['cat_ID'] != $row_rstCatList['category']) { ?>
	  <input name="cat_ID" type="radio" value="<?php echo $row_rstCatList['cat_ID']; ?>" />
	  <?php } else { echo '&nbsp;'; } ?>
	  </td>
      <td><?php echo $row_rstCatList['cat_type']; ?></td>
    </tr>
    <?php } while ($row_rstCatList = mysql_fetch_assoc($rstCatList)); ?>
  </table>
  <p>
    <input type="submit" name="Submit" value="Delete selected category" />
</p>
</form>

<p><a href="menu.php">Admin menu</a></p>
</body>
</html>
<?php
mysql_free_result($rstCtteList);

mysql_free_result($rstCatList);
?>
