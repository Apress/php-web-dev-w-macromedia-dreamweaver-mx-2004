<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Divide by zero</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>

<body>
<form name="form1" id="form1" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  Divide 
  <input name="num1" type="text" id="num1" /> 
  by 
  <input name="num2" type="text" id="num2" />
  <input type="submit" name="Submit" value="Calculate" />
</form>
<?php
if ($_GET) {
  if ($_GET['num2'] != 0) {
    echo '<p>Answer: '.$_GET['num1']/$_GET['num2'].'</p>';
	}
  else {
    echo '<p>Division by zero is impossible</p>';
	}
  }
?>
</body>
</html>
