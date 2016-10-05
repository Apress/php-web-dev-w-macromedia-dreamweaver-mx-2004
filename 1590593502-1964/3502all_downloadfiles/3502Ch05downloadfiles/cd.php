<?php require_once('Connections/Apress.php'); ?>
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE cd SET cd_name=%s WHERE cd_pk=%s",
                       GetSQLValueString($_POST['cd_name'], "text"),
                       GetSQLValueString($_POST['cd_pk'], "int"));
  mysql_select_db($database_Apress, $Apress);
  $Result1 = mysql_query($updateSQL, $Apress) or die(mysql_error());

  $updateGoTo = "artist.php?artist=".$_POST['artist']."";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST['cd_pk'])) && ($_POST['cd_pk'] != "") && (isset($_POST['delete_me']))) {
  $deleteSQL = sprintf("DELETE FROM cd WHERE cd_pk=%s",
                       GetSQLValueString($_POST['cd_pk'], "int"));

  mysql_select_db($database_Apress, $Apress);
  $Result1 = mysql_query($deleteSQL, $Apress) or die(mysql_error());

  $deleteGoTo = "artist.php?artist=".$_POST['artist']."";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

$colname_rstCD = "1";
if (isset($_GET['cd'])) {
  $colname_rstCD = (get_magic_quotes_gpc()) ? $_GET['cd'] : addslashes($_GET['cd']);
}
mysql_select_db($database_Apress, $Apress);
$query_rstCD = sprintf("SELECT artist.artist_pk, artist.artist_name, cd.cd_pk, cd.cd_name FROM cd, artist WHERE cd_pk = %s AND cd.cd_artist = artist.artist_pk", $colname_rstCD);
$rstCD = mysql_query($query_rstCD, $Apress) or die(mysql_error());
$row_rstCD = mysql_fetch_assoc($rstCD);
$totalRows_rstCD = mysql_num_rows($rstCD);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>CD Information</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<p><strong>Editing CD <?php echo $row_rstCD['cd_name']; ?> by <?php echo $row_rstCD['artist_name']; ?></strong>
</p>
<form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <input name="cd_name" type="text" id="cd_name" value="<?php echo $row_rstCD['cd_name']; ?>">
  <input type="submit" name="Submit" value="Update">
  <input name="cd_pk" type="hidden" id="cd_pk" value="<?php echo $row_rstCD['cd_pk']; ?>">
  <input name="artist" type="hidden" id="artist" value="<?php echo $row_rstCD['artist_pk']; ?>">
  <input type="hidden" name="MM_update" value="form1">
</form>
<form name="form2" method="post" action="">
  <input name="cd_pk" type="hidden" id="cd_pk" value="<?php echo $row_rstCD['cd_pk']; ?>">
  <input name="delete_me" type="checkbox" id="delete_me" value="checkbox">
  <input type="submit" name="Submit2" value="Delete">
  <input name="artist" type="hidden" id="artist" value="<?php echo $row_rstCD['artist_pk']; ?>">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rstCD);
?>
