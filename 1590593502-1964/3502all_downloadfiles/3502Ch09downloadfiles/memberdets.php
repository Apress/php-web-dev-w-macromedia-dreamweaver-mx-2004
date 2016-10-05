<?php require_once('Connections/gcConn.php'); ?>
<?php
$colname_rstMemberDets = "1";
if (isset($_GET['mem_ID'])) {
  $colname_rstMemberDets = (get_magic_quotes_gpc()) ? $_GET['mem_ID'] : addslashes($_GET['mem_ID']);
}
mysql_select_db($database_gcConn, $gcConn);
$query_rstMemberDets = sprintf("SELECT members.firstname, members.familyname, members.photo, YEAR(members.datejoined) AS yearjoined, members.profile, members.committee, category.cat_type, committee.`position`, articles.art_ID, articles.title FROM members, category LEFT JOIN committee ON members.committee = committee.ctte_ID LEFT JOIN articles ON members.mem_ID = articles.author WHERE %s = mem_ID AND members.category = category.cat_ID", $colname_rstMemberDets);
$rstMemberDets = mysql_query($query_rstMemberDets, $gcConn) or die(mysql_error());
$row_rstMemberDets = mysql_fetch_assoc($rstMemberDets);
$totalRows_rstMemberDets = mysql_num_rows($rstMemberDets);

$dims = getDims($row_rstMemberDets['photo']);
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
<h1><?php
// Execute following block only if an image has been found
// Code builds <img> tag with member's name as alt text,
//  and inserts image dimensions
if($dims) { ?>
<span id="artimage"><img src="<?php echo 'images/'.$row_rstMemberDets['photo']; ?>"
alt="<?php echo $row_rstMemberDets['firstname'].' '.$row_rstMemberDets['familyname']; ?>"
<?php echo $dims; ?>/></span>
<?php } // End of conditional statement
echo $row_rstMemberDets['firstname']; ?>
 <?php echo $row_rstMemberDets['familyname']; ?></h1>
<?php 
// Begin conditional statement to determine whether to display committee position
// "Not on committee" returns a value of 0, which is the same as "false"
// The code executes only if a value greater than 0 is returned
if ($row_rstMemberDets['committee'])
  {echo '<p class="ctte">'.$row_rstMemberDets['position'].'</p>';
// End of conditional statement block
  } ?>
<p>Member since <?php echo $row_rstMemberDets['yearjoined']; ?><br />
Membership category: <?php echo $row_rstMemberDets['cat_type']; ?></p>
<p><?php echo nl2br($row_rstMemberDets['profile']); ?></p>
<?php
// Conditional statement to establish whether any articles written
// Next code block executed only if article titles found
if (!empty($row_rstMemberDets['title'])) { ?>
<p>Articles by <?php echo $row_rstMemberDets['firstname']; ?>
<?php echo $row_rstMemberDets['familyname']; ?></p>
<ul>
<?php
// Beginning of repeat region server behavior code of links to article.php
// The article's primary key is used as a parameter
// and the article title as the link text
// Look no tables! This is ideal content for a bulleted list
// (the <ul> tags are outside the repeat region)
do { 
?><li><a href="article.php?art_ID=<?php echo $row_rstMemberDets['art_ID']; ?>">
<?php echo $row_rstMemberDets['title']; ?></a></li>
<?php } while ($row_rstMemberDets = mysql_fetch_assoc($rstMemberDets)); ?>
</ul>
<?php } // End of conditional statement controlling display of article titles and links?>
</div>
</body>
</html>
<?php
mysql_free_result($rstMemberDets);

// Custom function to get image details
function getDims($image,$folder='images/') {
  if (isset($image) && file_exists($folder.$image)) {
    $image_info = getimagesize($folder.$image);
    }
  $retVal = isset($image_info) ? $image_info[3] : '';
  return $retVal;
  }
?>
