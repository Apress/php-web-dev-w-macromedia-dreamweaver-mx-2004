<?php require_once('Connections/Apress.php'); ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rstCD = 10;
$pageNum_rstCD = 0;
if (isset($_GET['pageNum_rstCD'])) {
  $pageNum_rstCD = $_GET['pageNum_rstCD'];
}
$startRow_rstCD = $pageNum_rstCD * $maxRows_rstCD;

mysql_select_db($database_Apress, $Apress);
$query_rstCD = "SELECT artist.artist_pk, artist.artist_name, cd.cd_pk, cd.cd_name FROM cd, artist WHERE cd.cd_artist = artist.artist_pk ORDER BY artist.artist_name, cd.cd_name";
$query_limit_rstCD = sprintf("%s LIMIT %d, %d", $query_rstCD, $startRow_rstCD, $maxRows_rstCD);
$rstCD = mysql_query($query_limit_rstCD, $Apress) or die(mysql_error());
$row_rstCD = mysql_fetch_assoc($rstCD);

if (isset($_GET['totalRows_rstCD'])) {
  $totalRows_rstCD = $_GET['totalRows_rstCD'];
} else {
  $all_rstCD = mysql_query($query_rstCD);
  $totalRows_rstCD = mysql_num_rows($all_rstCD);
}
$totalPages_rstCD = ceil($totalRows_rstCD/$maxRows_rstCD)-1;

$queryString_rstCD = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rstCD") == false && 
        stristr($param, "totalRows_rstCD") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rstCD = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rstCD = sprintf("&totalRows_rstCD=%d%s", $totalRows_rstCD, $queryString_rstCD);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>All CDs</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<table width="400" border="0" cellspacing="4" cellpadding="4" summary="All CD Titles, ordered by Artist, Title">
  <caption align="left">
  All My CDs
  </caption>
  <tr>
    <td><strong>Artist</strong></td>
    <td><strong>Title</strong></td>
  </tr>
  <?php do { ?>
  <tr>
    <td><?php echo $row_rstCD['artist_name']; ?></td>
    <td><?php echo $row_rstCD['cd_name']; ?></td>
  </tr>
  <?php } while ($row_rstCD = mysql_fetch_assoc($rstCD)); ?>
  <tr>
    <td><?php if ($pageNum_rstCD > 0) { // Show if not first page ?>
      <a href="<?php printf("%s?pageNum_rstCD=%d%s", $currentPage, 0, $queryString_rstCD); ?>">|&lt;</a> <a href="<?php printf("%s?pageNum_rstCD=%d%s", $currentPage, max(0, $pageNum_rstCD - 1), $queryString_rstCD); ?>">&lt;&lt;</a>
      <?php } // Show if not first page ?> </td>
    <td><div align="right">
      <?php if ($pageNum_rstCD < $totalPages_rstCD) { // Show if not last page ?>
      <a href="<?php printf("%s?pageNum_rstCD=%d%s", $currentPage, min($totalPages_rstCD, $pageNum_rstCD + 1), $queryString_rstCD); ?>">&gt;&gt;</a> <a href="<?php printf("%s?pageNum_rstCD=%d%s", $currentPage, $totalPages_rstCD, $queryString_rstCD); ?>">&gt;|</a>
      <?php } // Show if not last page ?> </div></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rstCD);
?>
