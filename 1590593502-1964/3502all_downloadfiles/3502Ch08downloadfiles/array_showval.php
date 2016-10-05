<?php
function showVal($variable, $varDesc='') {
  if ($_GET && $_GET['debug']) {
  $varDesc = !empty($varDesc) ? " of $varDesc" : $varDesc;
  if (is_array($variable) && count($variable) > 0) {
    echo '<p>Contents'.$varDesc.' - array with elements:<br />';
	foreach ($variable as $key => $value) {
	  echo $key.' => '.$value.'<br />';
	  }
	echo '</p>';
  }
  elseif (is_array($variable) && count($variable) == 0) {
    echo '<p>Contents'.$varDesc.': empty array</p>';
    }
  else {
    echo '<p>Contents'.$varDesc.": $variable</p>";
	}
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Array test</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>

<body>
<?php
$person = array();
$names = array('Rachel','Allan','David','Chris');
$superGlobs = array('get' => $_GET, 'post' => $_POST);
 


showVal($names);
array_walk($superGlobs, 'showVal');
?>
</body>
</html>
