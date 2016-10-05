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
  $pub_date = getTheDate();
  if (strpos($pub_date, 'Error') === 0) {
    $error['date'] = $pub_date;
    }
  if (empty($_POST['picture']) xor empty($_POST['caption'])) {
    $error['missing'] = 'Picture and caption must either be both empty or both completed';
	}
  // Uncomment the next conditional statement if you want to limit the length of the article
  // Also set the maximum number of permitted characters in $articleMax
  $articleMax = 500;
  /*
  if (strlen($_POST['article']) > $articleMax) {
    $error['toolong'] = "The maximum number of characters in the article is $articleMax";
	}
  */
  }

if ($_POST && !isset($error)) {
$_POST['pub_date'] = $pub_date;


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addArticle")) {
  $insertSQL = sprintf("INSERT INTO articles (title, author, pub_date, kill_article, picture, caption, article) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['author'], "int"),
                       GetSQLValueString($_POST['pub_date'], "date"),
                       GetSQLValueString($_POST['kill_article'], "text"),
                       GetSQLValueString($_POST['picture'], "text"),
                       GetSQLValueString($_POST['caption'], "text"),
                       GetSQLValueString($_POST['article'], "text"));

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
$query_rstAuthors = "SELECT members.mem_ID, CONCAT(members.firstname,' ',UPPER( members.familyname)) AS name FROM members WHERE members.active = 'Y' ORDER BY members.familyname";
$rstAuthors = mysql_query($query_rstAuthors, $gcConn) or die(mysql_error());
$row_rstAuthors = mysql_fetch_assoc($rstAuthors);
$totalRows_rstAuthors = mysql_num_rows($rstAuthors);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Garden Club Admin - Add a new article</title>
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
<h1>Add new article</h1>
<form action="<?php echo $editFormAction; ?>" method="POST" name="addArticle" id="addArticle" onsubmit="MM_validateForm('title','','R','article','','R');return document.MM_returnValue">
  <table width="600">
    <tr>
      <td>Title:</td>
      <td><input value="<?php if (isset($_POST['title'])) echo $_POST['title']; ?>" name="title" type="text" class="widebox" id="title" /></td>
    </tr>
    <tr>
      <td>Author:</td>
      <td><select name="author" id="author">
        <?php
do {  
?>
        <option value="<?php echo $row_rstAuthors['mem_ID']?>"><?php echo $row_rstAuthors['name']?></option>
        <?php
} while ($row_rstAuthors = mysql_fetch_assoc($rstAuthors));
  $rows = mysql_num_rows($rstAuthors);
  if($rows > 0) {
      mysql_data_seek($rstAuthors, 0);
	  $row_rstAuthors = mysql_fetch_assoc($rstAuthors);
  }
?>
            </select></td>
    </tr>
    <tr>
      <td>Publication date: </td>
      <td><input value="<?php if (isset($_POST['pub_date'])) echo $_POST['pub_date']; ?>" name="pub_date" type="text" id="pub_date" /></td>
    </tr>
    <tr>
      <td>Kill article: </td>
      <td>No 
      <input name="kill_article" type="radio" value="N" checked="checked" /> 
      Yes 
      <input name="kill_article" type="radio" value="Y" /></td>
    </tr>
    <tr>
      <td>Picture:</td>
      <td><select name="picture" id="picture">
<option value="">Select an image</option>
<?php
// Execute code if images folder can be opened, or fail silently
if ($imageFolder = @opendir("../images/")) {
  // Create an array of image types
  $imageTypes = array('jpg','jpeg','gif','png');
  // Traverse images folder, and add filename to $img array if an image
  while (($imageFile = readdir($imageFolder)) !== false) {
    $fileInfo = pathinfo($imageFile);
    if (in_array($fileInfo['extension'],$imageTypes)) {
      $img[] = $imageFile;
      }
    }
  // Close the stream from the images folder
  closedir($imageFolder);
  // Check the $img array is not empty
  if ($img) {
    // Sort in natural, case-insensitive order, and populate menu
    natcasesort($img);
    foreach ($img as $image) {
      echo "<option value='$image'>$image</option>\n";
      }
    }
  }
?>
</select></td>
    </tr>
    <tr>
      <td>Caption:</td>
      <td><input value="<?php if (isset($_POST['caption'])) echo $_POST['caption']; ?>" name="caption" type="text" class="widebox" id="caption" /></td>
    </tr>
    <tr>
      <td>Article:</td>
      <td><textarea name="article" id="article"><?php if (isset($_POST['article'])) echo $_POST['article']; ?></textarea></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" value="Submit" /></td>
    </tr>
  </table>
    <input type="hidden" name="MM_insert" value="addArticle">
</form>
<p><a href="menu.php">Admin menu</a>  </p>
</body>
</html>
<?php
mysql_free_result($rstAuthors);

function getTheDate() {
  $retVal = ''; // make sure return value empty
  // if date entered, assign to shorter variable for simplicity and process
  if (!empty($_POST['pub_date'])) {
    $d = $_POST['pub_date'];
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
