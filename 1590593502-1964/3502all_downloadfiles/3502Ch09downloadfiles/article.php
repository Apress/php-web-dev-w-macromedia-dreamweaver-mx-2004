<?php require_once('Connections/gcConn.php'); ?>
<?php
$colname_rstArticle = "1";
if (isset($_GET['art_ID'])) {
  $colname_rstArticle = (get_magic_quotes_gpc()) ? $_GET['art_ID'] : addslashes($_GET['art_ID']);
}
mysql_select_db($database_gcConn, $gcConn);
$query_rstArticle = sprintf("SELECT articles.title, DATE_FORMAT(articles.pub_date,'%%M %%e, %%Y') AS pub_date, articles.picture, articles.caption, articles.article, members.firstname, members.familyname, members.committee, committee.`position` FROM articles, members LEFT JOIN committee ON members.committee = committee.ctte_ID WHERE members.mem_ID = articles.author AND %s = articles.art_ID", $colname_rstArticle);
$rstArticle = mysql_query($query_rstArticle, $gcConn) or die(mysql_error());
$row_rstArticle = mysql_fetch_assoc($rstArticle);
$totalRows_rstArticle = mysql_num_rows($rstArticle);

// Custom function to get image size
if (isset($row_rstArticle['picture']) && file_exists('images/'.$row_rstArticle['picture'])) {
  $image_info = getimagesize('images/'.$row_rstArticle['picture']);
  }
$dims = isset($image_info) ? $image_info[3] : '';
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
<li><a href="members.php">Members</a></li>
<li><a href="committee.php">Committee</a></li>
<li><a href="contact.php">Contact</a></li>
</ul>
</div>
<div id="maincontent">

  <h1><?php echo $row_rstArticle['title']; ?></h1>
  <p class="author"><?php echo $row_rstArticle['firstname']; ?> <?php echo $row_rstArticle['familyname']; ?><?php if ( $row_rstArticle['committee'] > 0) { echo ', '.$row_rstArticle['position'];} ?></p>
  <p><?php echo $row_rstArticle['pub_date']; ?></p>
  <p><?php if ($dims) { ?><span id="artimage"><img src="<?php echo 'images/'.$row_rstArticle['picture']; ?>" alt="<?php echo $row_rstArticle['caption']; ?>" <?php echo $dims; ?> /><br />
  <?php echo $row_rstArticle['caption']; ?></span><?php } echo nl2br($row_rstArticle['article']); ?></p>
</div>
</body>
</html>
<?php
mysql_free_result($rstArticle);
?>
