<?php
function showNav($page) {
$button = array('index.php' => 'Home',
           'members.php' => 'Members',
		   'committee.php' => 'Committee',
		   'contact.php' => 'Contact');
?>
<div id="mainnav">
  <ul>
<?php
  foreach ($button as $link => $label) {
    echo '<li><a href="'.$link.'"';
	if ($link == $page) {
	  echo 'id="thispage" ';
	  }
	echo ">$label</a></li>\n";
	}
?>
  </ul>
</div>
<?php
}
?>