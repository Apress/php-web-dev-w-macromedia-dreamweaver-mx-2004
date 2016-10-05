<?php
$pattern = '/^\w[-.\w]*@([-a-z0-9]+\.)+[a-z]{2,4}$/i';
if ($_POST && array_key_exists('sendCom',$_POST)) {
  $nomessage='';
  $error=array();
  $message='';
  $email='';
  $con_email='';
// Check each field and build errors array if problems found
if (isset($_POST['comments']) && !empty($_POST['comments'])) {
  $message=strip_tags($_POST['comments']);
  }
else {
  $nomessage = 'You have not entered any comments';
  }
if (isset($_POST['email']) && !empty($_POST['email'])) {
  $email=trim($_POST['email']);
  }
else {
  $error['email'] = 'You have not given a return email address';
}
if (isset($_POST['con_email']) && !empty($_POST['con_email'])) {
  $con_email=trim($_POST['con_email']);
  if($email != $con_email) $error['nomatch'] = 'Your emails don\'t match';
  }
else {
  $error['confirm'] = 'Please confirm your email address';
  }
if ($email && $con_email) {
  if ($email == $con_email) {
    if (!preg_match($pattern,$email)) $error['invalid'] = 'That appears to be an invalid email address';
    }
  }
  //Create the variables for the email
$to = 'me@example.com';
$subject = 'Message from Garden Club website';
$additional_headers = "From: website@example.com\n"
                                      ."Reply-To: $email";

// If no errors, send email and redirect to acknowledgment page
if (!$nomessage && !$error) {
  mail($to,$subject,$message,$additional_headers);
  header('Location: thanks.php');
  exit();
  }
}
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
<li><a href="contact.php" id="thispage">Contact</a></li>
</ul>
</div>
<div id="maincontent">
<?php
// Display error message if errors have been found in submission
if (isset($nomessage) || isset($error)) {
?>
<div id="sorry"><p>Sorry, we are unable to process your request
because of errors. Please check the points highlighted in bold (red)
text, and resubmit.</p></div>
<?php
  }
?>
  <h1>Send us your comments</h1>
  <form name="feedback" id="feedback" method="post" action="<?php $_SERVER['PHP_SELF']?>">
    <table>
      <tr>
        <td>Comments</td>
        <td
		<?php if (isset($nomessage) && !empty($nomessage)) {
		  echo 'class="error">'.$nomessage; } else { ?>
		  >
		  <?php } ?>
		  </td>
      </tr>
      <tr>
        <td colspan="2"><textarea name="comments" id="comments"><?php if (isset($_POST['comments'])) echo $_POST['comments']; ?></textarea></td>
      </tr>
	  <?php 
	  if (isset($error)) { // Display error messages. Otherwise skip table row.
	  ?>
      <tr>
        <td>&nbsp;</td>
        <td class="error">
		<?php // Loop through error messages and display
		foreach ($error as $key => $value) {
		  echo $value.'<br />';
		  }
		?>
		</td>
      </tr>
	  <?php } ?>
      <tr>
        <td>Your email: </td>
        <td><input name="email" type="text" class="mediumbox" id="email" /></td>
      </tr>
      <tr>
        <td>Confirm email: </td>
        <td><input name="con_email" type="text" class="mediumbox" id="con_email" /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input name="sendCom" type="submit" id="sendCom" value="Send comments" /></td>
      </tr>
    </table>
  </form>
  </div>
</body>
</html>
