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
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rstMemList = 20;
$pageNum_rstMemList = 0;
if (isset($_GET['pageNum_rstMemList'])) {
  $pageNum_rstMemList = $_GET['pageNum_rstMemList'];
}
$startRow_rstMemList = $pageNum_rstMemList * $maxRows_rstMemList;

mysql_select_db($database_gcConn, $gcConn);
$query_rstMemList = "SELECT mem_ID, firstname, familyname FROM members ORDER BY familyname ASC";
$query_limit_rstMemList = sprintf("%s LIMIT %d, %d", $query_rstMemList, $startRow_rstMemList, $maxRows_rstMemList);
$rstMemList = mysql_query($query_limit_rstMemList, $gcConn) or die(mysql_error());
$row_rstMemList = mysql_fetch_assoc($rstMemList);

if (isset($_GET['totalRows_rstMemList'])) {
  $totalRows_rstMemList = $_GET['totalRows_rstMemList'];
} else {
  $all_rstMemList = mysql_query($query_rstMemList);
  $totalRows_rstMemList = mysql_num_rows($all_rstMemList);
}
$totalPages_rstMemList = ceil($totalRows_rstMemList/$maxRows_rstMemList)-1;

$queryString_rstMemList = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rstMemList") == false && 
        stristr($param, "totalRows_rstMemList") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rstMemList = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rstMemList = sprintf("&totalRows_rstMemList=%d%s", $totalRows_rstMemList, $queryString_rstMemList);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Garden Club Administration - List of Members</title>
<link href="../externals/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>

<h1>List of All Members</h1>
<p><a href="menu.php">Admin menu </a></p>
<table width="400">
  <?php do { ?>
  <tr>
    <td><?php echo $row_rstMemList['firstname']; ?> <?php echo strtoupper($row_rstMemList['familyname']); ?></td>
    <td><a href="editmember.php?mem_ID=<?php echo $row_rstMemList['mem_ID']; ?>">edit</a></td>
  </tr>
  <?php } while ($row_rstMemList = mysql_fetch_assoc($rstMemList)); ?>
</table>
<p>
<table border="0" width="50%" align="center">
  <tr>
    <td width="23%" align="center"><?php if ($pageNum_rstMemList > 0) { // Show if not first page ?>
      <a href="<?php printf("%s?pageNum_rstMemList=%d%s", $currentPage, 0, $queryString_rstMemList); ?>">First</a>
      <?php } // Show if not first page ?>
    </td>
    <td width="31%" align="center"><?php if ($pageNum_rstMemList > 0) { // Show if not first page ?>
      <a href="<?php printf("%s?pageNum_rstMemList=%d%s", $currentPage, max(0, $pageNum_rstMemList - 1), $queryString_rstMemList); ?>">Previous</a>
      <?php } // Show if not first page ?>
    </td>
    <td width="23%" align="center"><?php if ($pageNum_rstMemList < $totalPages_rstMemList) { // Show if not last page ?>
      <a href="<?php printf("%s?pageNum_rstMemList=%d%s", $currentPage, min($totalPages_rstMemList, $pageNum_rstMemList + 1), $queryString_rstMemList); ?>">Next</a>
      <?php } // Show if not last page ?>
    </td>
    <td width="23%" align="center"><?php if ($pageNum_rstMemList < $totalPages_rstMemList) { // Show if not last page ?>
      <a href="<?php printf("%s?pageNum_rstMemList=%d%s", $currentPage, $totalPages_rstMemList, $queryString_rstMemList); ?>">Last</a>
      <?php } // Show if not last page ?>
    </td>
  </tr>
</table>
</p>
</body>
</html>
<?php
mysql_free_result($rstMemList);
?>
