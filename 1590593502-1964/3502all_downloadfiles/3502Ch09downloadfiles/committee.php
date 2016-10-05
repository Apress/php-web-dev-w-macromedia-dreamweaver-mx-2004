<?php require_once('Connections/gcConn.php'); ?>
<?php
mysql_select_db($database_gcConn, $gcConn);
$query_rstChair = "SELECT members.mem_ID, members.firstname, members.familyname FROM members, committee WHERE members.committee = committee.ctte_ID AND committee.`position` = 'Chair'";
$rstChair = mysql_query($query_rstChair, $gcConn) or die(mysql_error());
$row_rstChair = mysql_fetch_assoc($rstChair);
$totalRows_rstChair = mysql_num_rows($rstChair);

mysql_select_db($database_gcConn, $gcConn);
$query_rstTreasurer = "SELECT members.mem_ID, members.firstname, members.familyname FROM members, committee WHERE members.committee = committee.ctte_ID AND committee.`position` = 'Treasurer'";
$rstTreasurer = mysql_query($query_rstTreasurer, $gcConn) or die(mysql_error());
$row_rstTreasurer = mysql_fetch_assoc($rstTreasurer);
$totalRows_rstTreasurer = mysql_num_rows($rstTreasurer);

mysql_select_db($database_gcConn, $gcConn);
$query_rstSecretary = "SELECT members.mem_ID, members.firstname, members.familyname FROM members, committee WHERE members.committee = committee.ctte_ID AND committee.`position` = 'Secretary'";
$rstSecretary = mysql_query($query_rstSecretary, $gcConn) or die(mysql_error());
$row_rstSecretary = mysql_fetch_assoc($rstSecretary);
$totalRows_rstSecretary = mysql_num_rows($rstSecretary);

mysql_select_db($database_gcConn, $gcConn);
$query_rstShowOrg = "SELECT members.mem_ID, members.firstname, members.familyname FROM members, committee WHERE members.committee = committee.ctte_ID AND committee.`position` = 'Show Organizer'";
$rstShowOrg = mysql_query($query_rstShowOrg, $gcConn) or die(mysql_error());
$row_rstShowOrg = mysql_fetch_assoc($rstShowOrg);
$totalRows_rstShowOrg = mysql_num_rows($rstShowOrg);

mysql_select_db($database_gcConn, $gcConn);
$query_rstCommittee = "SELECT members.mem_ID, members.firstname, members.familyname FROM members, committee WHERE members.committee = committee.ctte_ID AND committee.`position` = 'Committee Member'";
$rstCommittee = mysql_query($query_rstCommittee, $gcConn) or die(mysql_error());
$row_rstCommittee = mysql_fetch_assoc($rstCommittee);
$totalRows_rstCommittee = mysql_num_rows($rstCommittee);
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
<li><a href="committee.php" id="thispage">Committee</a></li>
<li><a href="contact.php">Contact</a></li>
</ul>
</div>
<div id="maincontent">
<h1>Club Committee</h1>
<table width="500">
  <tr>
    <td><a href="memberdets.php?mem_ID=<?php echo $row_rstChair['mem_ID']; ?>"><?php echo $row_rstChair['firstname']; ?> <?php echo $row_rstChair['familyname']; ?></a></td>
    <td>Chair</td>
  </tr>
  <tr>
    <td><a href="memberdets.php?mem_ID=<?php echo $row_rstTreasurer['mem_ID']; ?>"><?php echo $row_rstTreasurer['firstname']; ?> <?php echo $row_rstTreasurer['familyname']; ?></a></td>
    <td>Treasurer</td>
  </tr>
  <tr>
    <td><a href="memberdets.php?mem_ID=<?php echo $row_rstSecretary['mem_ID']; ?>"><?php echo $row_rstSecretary['firstname']; ?> <?php echo $row_rstSecretary['familyname']; ?></a></td>
    <td>Secretary</td>
  </tr>
  <tr>
    <td><a href="memberdets.php?mem_ID=<?php echo $row_rstShowOrg['mem_ID']; ?>"><?php echo $row_rstShowOrg['firstname']; ?> <?php echo $row_rstShowOrg['familyname']; ?></a></td>
    <td>Show Organizer</td>
  </tr>
  <?php do { ?>
  <tr>
    <td><a href="memberdets.php?mem_ID=<?php echo $row_rstCommittee['mem_ID']; ?>"><?php echo $row_rstCommittee['firstname']; ?> <?php echo $row_rstCommittee['familyname']; ?></a></td>
    <td>&nbsp;</td>
  </tr>
  <?php } while ($row_rstChair = mysql_fetch_assoc($rstChair)); ?>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($rstChair);

mysql_free_result($rstTreasurer);

mysql_free_result($rstSecretary);

mysql_free_result($rstShowOrg);

mysql_free_result($rstCommittee);
?>
