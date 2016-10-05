<?php require_once('Connections/gcConn.php'); ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rstMembers = 2;
$pageNum_rstMembers = 0;
if (isset($_GET['pageNum_rstMembers'])) {
  $pageNum_rstMembers = $_GET['pageNum_rstMembers'];
}
$startRow_rstMembers = $pageNum_rstMembers * $maxRows_rstMembers;

mysql_select_db($database_gcConn, $gcConn);
$query_rstMembers = "SELECT members.mem_ID, members.firstname, members.familyname, category.cat_type FROM members, category WHERE members.category = category.cat_ID AND members.active = 'Y' ORDER BY members.familyname";
$query_limit_rstMembers = sprintf("%s LIMIT %d, %d", $query_rstMembers, $startRow_rstMembers, $maxRows_rstMembers);
$rstMembers = mysql_query($query_limit_rstMembers, $gcConn) or die(mysql_error());
$row_rstMembers = mysql_fetch_assoc($rstMembers);

if (isset($_GET['totalRows_rstMembers'])) {
  $totalRows_rstMembers = $_GET['totalRows_rstMembers'];
} else {
  $all_rstMembers = mysql_query($query_rstMembers);
  $totalRows_rstMembers = mysql_num_rows($all_rstMembers);
}
$totalPages_rstMembers = ceil($totalRows_rstMembers/$maxRows_rstMembers)-1;

$queryString_rstMembers = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rstMembers") == false && 
        stristr($param, "totalRows_rstMembers") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rstMembers = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rstMembers = sprintf("&totalRows_rstMembers=%d%s", $totalRows_rstMembers, $queryString_rstMembers);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Roundtree Garden Club</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="externals/main.css" rel="stylesheet" type="text/css" />
<meta http-equiv="imagetoolbar" content="no" />
</head>
<body>
<div id="topimage"><img src="images/topimage.jpg"
alt="Roundtree Garden Club" width="400" height="150" /></div>
<div id="mainnav">
<ul>
<li><a href="index.php">Home</a></li>
<li><a href="members.php" id="thispage">Members</a></li>
<li><a href="committee.php">Committee</a></li>
<li><a href="contact.php">Contact</a></li>
</ul>
</div>
<div id="maincontent">
<!-- content goes here -->
<h1>Current members</h1>
<h3>Click the member's name to see individual details</h3>
<table width="500">
  <tr>
    <th scope="col">Name</th>
    <th scope="col">Membership Category </th>
  </tr>
  <?php do { ?>
  <tr>
      <td><a href="memberdets.php?mem_ID=<?php echo $row_rstMembers['mem_ID']; ?>"><?php echo $row_rstMembers['firstname']; ?> <?php echo $row_rstMembers['familyname']; ?></a></td>
      <td><?php echo $row_rstMembers['cat_type']; ?></td>
  </tr>
  <?php } while ($row_rstMembers = mysql_fetch_assoc($rstMembers)); ?>
</table>


<table border="0" width="50%" align="center">
  <tr>
    <td width="23%" align="center"><?php if ($pageNum_rstMembers > 0) { // Show if not first page ?>
      <a href="<?php printf("%s?pageNum_rstMembers=%d%s", $currentPage, 0, $queryString_rstMembers); ?>">First</a>
      <?php } // Show if not first page ?>
    </td>
    <td width="31%" align="center"><?php if ($pageNum_rstMembers > 0) { // Show if not first page ?>
      <a href="<?php printf("%s?pageNum_rstMembers=%d%s", $currentPage, max(0, $pageNum_rstMembers - 1), $queryString_rstMembers); ?>">Previous</a>
      <?php } // Show if not first page ?>
    </td>
    <td width="23%" align="center"><?php if ($pageNum_rstMembers < $totalPages_rstMembers) { // Show if not last page ?>
      <a href="<?php printf("%s?pageNum_rstMembers=%d%s", $currentPage, min($totalPages_rstMembers, $pageNum_rstMembers + 1), $queryString_rstMembers); ?>">Next</a>
      <?php } // Show if not last page ?>
    </td>
    <td width="23%" align="center"><?php if ($pageNum_rstMembers < $totalPages_rstMembers) { // Show if not last page ?>
      <a href="<?php printf("%s?pageNum_rstMembers=%d%s", $currentPage, $totalPages_rstMembers, $queryString_rstMembers); ?>">Last</a>
      <?php } // Show if not last page ?>
    </td>
  </tr>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($rstMembers);
?>
