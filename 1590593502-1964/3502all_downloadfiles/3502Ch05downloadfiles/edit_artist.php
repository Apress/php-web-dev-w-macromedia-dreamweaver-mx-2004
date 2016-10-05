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
  $updateSQL = sprintf("UPDATE artist SET artist_name=%s WHERE artist_pk=%s",
                       GetSQLValueString($_POST['artist_name'], "text"),
                       GetSQLValueString($_POST['artist_pk'], "int"));

  mysql_select_db($database_Apress, $Apress);
  $Result1 = mysql_query($updateSQL, $Apress) or die(mysql_error());

  $updateGoTo = "artist.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST['artist_pk'])) && ($_POST['artist_pk'] != "") && (isset($_POST['delete_me']))) {
  $deleteSQL = sprintf("DELETE FROM artist WHERE artist_pk=%s",
                       GetSQLValueString($_POST['artist_pk'], "int"));

  mysql_select_db($database_Apress, $Apress);
  $Result1 = mysql_query($deleteSQL, $Apress) or die(mysql_error());

  $deleteGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

$colname_rstArtist = "1";
if (isset($_GET['artist'])) {
  $colname_rstArtist = (get_magic_quotes_gpc()) ? $_GET['artist'] : addslashes($_GET['artist']);
}
mysql_select_db($database_Apress, $Apress);
$query_rstArtist = sprintf("SELECT * FROM artist WHERE artist_pk = %s", $colname_rstArtist);
$rstArtist = mysql_query($query_rstArtist, $Apress) or die(mysql_error());
$row_rstArtist = mysql_fetch_assoc($rstArtist);
$totalRows_rstArtist = mysql_num_rows($rstArtist);

$colname_rstCDS = "1";
if (isset($_GET['artist'])) {
  $colname_rstCDS = (get_magic_quotes_gpc()) ? $_GET['artist'] : addslashes($_GET['artist']);
}
mysql_select_db($database_Apress, $Apress);
$query_rstCDS = sprintf("SELECT * FROM cd WHERE cd_artist = %s", $colname_rstCDS);
$rstCDS = mysql_query($query_rstCDS, $Apress) or die(mysql_error());
$row_rstCDS = mysql_fetch_assoc($rstCDS);
$totalRows_rstCDS = mysql_num_rows($rstCDS);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Edit Artist</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<p><strong>Editing information for <?php echo $row_rstArtist['artist_name']; ?></strong></p>
<form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <input name="artist_name" type="text" id="artist_name" value="<?php echo $row_rstArtist['artist_name']; ?>">
  <input type="submit" name="Submit" value="Update">
  <input name="artist_pk" type="hidden" id="artist_pk" value="<?php echo $row_rstArtist['artist_pk']; ?>">
  <input type="hidden" name="MM_update" value="form1">
</form>
<?php if ($totalRows_rstCDS == 0) { // Show if recordset empty ?>
<form name="form2" method="post" action="">
  <input name="artist_pk" type="hidden" id="artist_pk" value="<?php echo $row_rstArtist['artist_pk']; ?>">
  <input name="delete_me" type="checkbox" id="delete_me" value="checkbox">
  <input type="submit" name="Submit2" value="Delete">
</form>
<?php } // Show if recordset empty ?>
<p>&nbsp; </p>
</body>
</html>
<?php
mysql_free_result($rstArtist);

mysql_free_result($rstCDS);
?>
