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
mysql_select_db($database_gcConn, $gcConn);
$query_rstArticles = "SELECT articles.art_ID, articles.title, articles.pub_date, articles.kill_article, articles.picture, members.firstname, members.familyname FROM articles, members WHERE articles.author = members.mem_ID ORDER BY articles.pub_date DESC";
$rstArticles = mysql_query($query_rstArticles, $gcConn) or die(mysql_error());
$row_rstArticles = mysql_fetch_assoc($rstArticles);
$totalRows_rstArticles = mysql_num_rows($rstArticles);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Garden Club Admin - List All Articles</title>
<link href="../externals/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>List All Articles</h1>
<p><a href="menu.php">Admin menu </a></p>
<table width="600">
  <tr>
    <th scope="col">Date</th>
    <th scope="col">Title</th>
    <th scope="col">Author</th>
    <th scope="col">Picture</th>
    <th scope="col">Killed</th>
    <th scope="col">&nbsp;</th>
  </tr>
  <?php do { ?>
  <tr>
    <td><?php echo mysqlToUSDate($row_rstArticles['pub_date']); ?></td>
    <td><?php echo $row_rstArticles['title']; ?></td>
    <td><?php echo $row_rstArticles['firstname']; ?> <?php echo $row_rstArticles['familyname']; ?></td>
    <td><?php echo $row_rstArticles['picture']; ?></td>
    <td><?php echo $row_rstArticles['kill_article']; ?></td>
    <td><a href="editarticle.php?art_ID=<?php echo $row_rstArticles['art_ID']; ?>">edit</a></td>
  </tr>
  <?php } while ($row_rstArticles = mysql_fetch_assoc($rstArticles)); ?>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rstArticles);

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
