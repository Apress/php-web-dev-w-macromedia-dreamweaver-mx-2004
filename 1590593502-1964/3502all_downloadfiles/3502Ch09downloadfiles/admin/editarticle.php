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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "editArticle")) {
  $updateSQL = sprintf("UPDATE articles SET title=%s, author=%s, pub_date=%s, kill_article=%s, picture=%s, caption=%s, article=%s WHERE art_ID=%s",
                       GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['author'], "int"),
                       GetSQLValueString($_POST['pub_date'], "date"),
                       GetSQLValueString($_POST['kill_article'], "text"),
                       GetSQLValueString($_POST['picture'], "text"),
                       GetSQLValueString($_POST['caption'], "text"),
                       GetSQLValueString($_POST['article'], "text"),
                       GetSQLValueString($_POST['art_ID'], "int"));

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
$query_rstAuthors = "SELECT members.mem_ID, CONCAT(members.firstname,' ',UPPER( members.familyname)) AS name FROM members WHERE members.active = 'Y' ORDER BY members.familyname";
$rstAuthors = mysql_query($query_rstAuthors, $gcConn) or die(mysql_error());
$row_rstAuthors = mysql_fetch_assoc($rstAuthors);
$totalRows_rstAuthors = mysql_num_rows($rstAuthors);

$colname_rstGetArticle = "1";
if (isset($_GET['art_ID'])) {
  $colname_rstGetArticle = (get_magic_quotes_gpc()) ? $_GET['art_ID'] : addslashes($_GET['art_ID']);
}
mysql_select_db($database_gcConn, $gcConn);
$query_rstGetArticle = sprintf("SELECT articles.art_ID, articles.title, members.mem_ID, members.firstname, members.familyname, articles.pub_date, articles.kill_article, articles.picture, articles.caption, articles.article FROM articles, members WHERE articles.art_ID = %s AND articles.author = members.mem_ID", $colname_rstGetArticle);
$rstGetArticle = mysql_query($query_rstGetArticle, $gcConn) or die(mysql_error());
$row_rstGetArticle = mysql_fetch_assoc($rstGetArticle);
$totalRows_rstGetArticle = mysql_num_rows($rstGetArticle);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Garden Club Admin -Edit article</title>
<link href="../externals/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php
if (isset($error)) {
  foreach ($error as $message) {
    echo '<p class="warning">'.$message.'</p>';
	}
  }
?>
<h1>Edit article</h1>
<form name="editArticle" id="editArticle" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="600">
    <tr>
      <td>Title:</td>
      <td><input value="<?php if (isset($_POST['title'])) { echo $_POST['title'];}else{echo $row_rstGetArticle['title'];} ?>" name="title" type="text" class="widebox" id="title" /></td>
    </tr>
    <tr>
      <td>Author:</td>
      <td><select name="author" id="author">
        <?php
do {  
?>
        <option value="<?php echo $row_rstAuthors['mem_ID']?>"<?php if (!(strcmp($row_rstAuthors['mem_ID'], $row_rstGetArticle['mem_ID']))) {echo 'selected="selected"';} ?>><?php echo $row_rstAuthors['name']?></option>
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
      <td><input value="<?php if (isset($_POST['pub_date'])) { echo $_POST['pub_date'];}else{echo mysqlToUSDate($row_rstGetArticle['pub_date']);} ?>" name="pub_date" type="text" id="pub_date" /></td>
    </tr>
    <tr>
      <td>Kill article: </td>
      <td>No 
      <input <?php if (!(strcmp($row_rstGetArticle['kill_article'],"N"))) {echo 'checked="checked"';} ?> name="kill_article" type="radio" value="N" /> 
      Yes 
      <input <?php if (!(strcmp($row_rstGetArticle['kill_article'],"Y"))) {echo 'checked="checked"';} ?> name="kill_article" type="radio" value="Y" /></td>
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
      echo "<option value='$image'";
	  if ($row_rstGetArticle['picture'] == $image) {
	    echo 'selected="selected"';
		}
	  echo ">$image</option>\n";
      }
    }
  }
?>
</select></td>
    </tr>
    <tr>
      <td>Caption:</td>
      <td><input value="<?php if (isset($_POST['caption'])) { echo $_POST['caption'];}else{echo $row_rstGetArticle['caption'];} ?>" name="caption" type="text" class="widebox" id="caption" /></td>
    </tr>
    <tr>
      <td>Article:</td>
      <td><textarea name="article" id="article"><?php if (isset($_POST['article'])) {echo $_POST['article'];}else{ echo $row_rstGetArticle['article'];} ?></textarea></td>
    </tr>
    <tr>
      <td><input name="art_ID" type="hidden" id="art_ID" value="<?php echo $row_rstGetArticle['art_ID']; ?>" /></td>
      <td><input type="submit" name="Submit" value="Update article" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="editArticle">
</form>
<p><a href="menu.php">Admin menu</a></p>
</body>
</html>
<?php
mysql_free_result($rstAuthors);

mysql_free_result($rstGetArticle);

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
