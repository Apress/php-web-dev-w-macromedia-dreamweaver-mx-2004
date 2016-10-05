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
  $insertSQL = sprintf("INSERT INTO artist (artist_name) VALUES (%s)",
                       GetSQLValueString($_POST['artist_name'], "text"));

  mysql_select_db($database_Apress, $Apress);
  $Result1 = mysql_query($insertSQL, $Apress) or die(mysql_error());
}

mysql_select_db($database_Apress, $Apress);
$query_rstCDS = "SELECT cd_pk FROM cd";
$rstCDS = mysql_query($query_rstCDS, $Apress) or die(mysql_error());
$row_rstCDS = mysql_fetch_assoc($rstCDS);
$totalRows_rstCDS = mysql_num_rows($rstCDS);

mysql_select_db($database_Apress, $Apress);
$query_rstArtists = "SELECT * FROM artist";
$rstArtists = mysql_query($query_rstArtists, $Apress) or die(mysql_error());
$row_rstArtists = mysql_fetch_assoc($rstArtists);
$totalRows_rstArtists = mysql_num_rows($rstArtists);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>My CD Collection</title>
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
<h1>My CD Collection</h1>
<p>&nbsp;<?php echo $totalRows_rstCDS ?> CDs from <?php echo $totalRows_rstArtists ?> artists </p>
<table width="400" border="0" cellspacing="4" cellpadding="4" summary="A list of all the artists within our database">
  <caption align="left">
  Artists in Database
  </caption>
  <tr>
    <td><strong>Artist Name </strong></td>
    <td>&nbsp;</td>
  </tr>
  <?php do { ?>
  <tr>
    <td><?php echo $row_rstArtists['artist_name']; ?></td>
    <td><a href="artist.php?artist=<?php echo $row_rstArtists['artist_pk']; ?>">view</a></td>
  </tr>
  <?php } while ($row_rstArtists = mysql_fetch_assoc($rstArtists)); ?>
</table>
<p>&nbsp;</p>
<h2>Add a new artist </h2>
<form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  Artist: 
  <input name="artist_name" type="text" id="artist_name">
  <input name="Submit" type="submit" onClick="MM_validateForm('artist_name','','R');return document.MM_returnValue" value="Add">
  <input type="hidden" name="MM_insert" value="form1">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rstCDS);

mysql_free_result($rstArtists);
?>
