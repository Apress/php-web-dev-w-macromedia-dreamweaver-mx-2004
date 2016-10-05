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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO cd (cd_artist, cd_name) VALUES (%s, %s)",
                       GetSQLValueString($_POST['cd_artist'], "int"),
                       GetSQLValueString($_POST['cd_name'], "text"));

  mysql_select_db($database_Apress, $Apress);
  $Result1 = mysql_query($insertSQL, $Apress) or die(mysql_error());
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
<title>Artist Information</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
<h1><?php echo $row_rstArtist['artist_name']; ?></h1>
<p><a href="edit_artist.php?artist=<?php echo $row_rstArtist['artist_pk']; ?>">Edit this artists information</a> </p>
<?php if ($totalRows_rstCDS > 0) { // Show if recordset not empty ?>
<table width="400" border="0" cellspacing="4" cellpadding="4">
  <caption align="left">
  CD Information
  </caption>
  <tr>
    <td><strong>CD Title </strong></td>
    <td>&nbsp;</td>
  </tr>
  <?php do { ?>
  <tr>
    <td><?php echo $row_rstCDS['cd_name']; ?></td>
    <td><a href="cd.php?cd=<?php echo $row_rstCDS['cd_pk']; ?>">edit</a></td>
  </tr>
  <?php } while ($row_rstCDS = mysql_fetch_assoc($rstCDS)); ?>
</table>
<?php } // Show if recordset not empty ?>
<?php if ($totalRows_rstCDS == 0) { // Show if recordset empty ?>
<p> <strong>No CDs in database for this artist </strong> </p>
<?php } // Show if recordset empty ?>
<form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
CD Title:
<input name="cd_name" type="text" id="cd_name">
  <input name="Submit" type="submit" onClick="MM_validateForm('cd_name','','R');return document.MM_returnValue" value="Add">
  <input name="cd_artist" type="hidden" id="cd_artist" value="<?php echo $row_rstArtist['artist_pk']; ?>">
  <input type="hidden" name="MM_insert" value="form1">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rstArtist);

mysql_free_result($rstCDS);
?>
