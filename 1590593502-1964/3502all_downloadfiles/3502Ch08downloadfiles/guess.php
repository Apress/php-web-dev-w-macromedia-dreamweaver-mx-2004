<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Always a winner</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>

<body>
<form name="form1" id="form1" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  Guess the secret number 
  <input type="text" name="guess" />
  <input type="submit" name="Submit" value="Try" />
</form>
<?php
/*************************************************************
The following line doesn't check the value of $_GET['guess'],
but RESETS it to 5 every time. To check for the value, use the 
equality operator (==) instead of the assignment operator (=).
**************************************************************/
if ($_GET && $_GET['guess'] = 5) {
  echo 'Bingo, correct!';
  }
else {
  echo 'Bad luck, try again';
  }
?>
</body>
</html>
