<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Array test</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>

<body>
<?php
$names = array('Rachel','Allan','David','Chris');
foreach ($names as $name) {
  echo $name .= ' is great<br />';
  }
echo '<br />';
for ($i = 0; $i < count($names); $i++) {
  echo $names[$i] .= ' is great<br />';
  }
print_r($names);
?>
</body>
</html>
