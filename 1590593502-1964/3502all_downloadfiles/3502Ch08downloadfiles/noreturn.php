<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>No return</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>

<body>
<?php
function salesTax($price,$taxRate) {
  $taxInc = $price + ($price*$taxRate/100);
  /**********************************************************************
  A common beginner's mistake is to forget to return the result of a 
  custom-built function. Uncomment the next line, and the page will work.
  ***********************************************************************/
  // return $taxInc;
  }
echo salesTax(500,8).'<br />';
echo salesTax(8,500).'<br />';
?>
</body>
</html>
