<?php require_once('Connections/gcConn.php'); ?>
<?php
$maxRows_rstArtSumm = 6;
$pageNum_rstArtSumm = 0;
if (isset($_GET['pageNum_rstArtSumm'])) {
  $pageNum_rstArtSumm = $_GET['pageNum_rstArtSumm'];
}
$startRow_rstArtSumm = $pageNum_rstArtSumm * $maxRows_rstArtSumm;

mysql_select_db($database_gcConn, $gcConn);
$query_rstArtSumm = "SELECT articles.art_ID, articles.title, articles.article, members.firstname, members.familyname FROM articles, members WHERE articles.author = members.mem_ID AND kill_article = 'N' ORDER BY articles.pub_date";
$query_limit_rstArtSumm = sprintf("%s LIMIT %d, %d", $query_rstArtSumm, $startRow_rstArtSumm, $maxRows_rstArtSumm);
$rstArtSumm = mysql_query($query_limit_rstArtSumm, $gcConn) or die(mysql_error());
$row_rstArtSumm = mysql_fetch_assoc($rstArtSumm);

if (isset($_GET['totalRows_rstArtSumm'])) {
  $totalRows_rstArtSumm = $_GET['totalRows_rstArtSumm'];
} else {
  $all_rstArtSumm = mysql_query($query_rstArtSumm);
  $totalRows_rstArtSumm = mysql_num_rows($all_rstArtSumm);
}
$totalPages_rstArtSumm = ceil($totalRows_rstArtSumm/$maxRows_rstArtSumm)-1;
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
<li><a href="index.php" id="thispage">Home</a></li>
<li><a href="members.php">Members</a></li>
<li><a href="committee.php">Committee</a></li>
<li><a href="contact.php">Contact</a></li>
</ul>
</div>
<div id="maincontent">
<h1>Roundtree Garden News</h1>
<h3>Tips and Advice from Members</h3>
<?php do { ?>
<h2><?php echo $row_rstArtSumm['title']; ?></h2>
<p class="author"><?php echo $row_rstArtSumm['firstname']; ?> <?php echo $row_rstArtSumm['familyname']; ?></p>
<p><?php echo extractFirst($row_rstArtSumm['article']); ?> <a href="article.php?art_ID=<?php echo $row_rstArtSumm['art_ID']; ?>">more</a> </p>
<?php } while ($row_rstArtSumm = mysql_fetch_assoc($rstArtSumm)); ?>
</div>
</body>
</html>
<?php
mysql_free_result($rstArtSumm);

function extractFirst($text,$num=2) {
  // Create array of sentences using period as the divider
  $sentences = explode('.',$text);  
  $extract = '';  
  if (count($sentences) > 1) {
    // Rebuild, using number defined by $num, and adding period back in.
    for ($i=0;$i<$num;$i++) {
      $extract .= $sentences[$i].'.'; 
      }
    }
  else {
    $extract = $sentences[0].'.';
    }
  return $extract;
  }
?>
