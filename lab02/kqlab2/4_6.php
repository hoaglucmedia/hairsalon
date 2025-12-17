<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>lab 2_5</title>
</head>

<body>
<?php
	include("lab2_5a.php");
	include("lab2_5b.php");
	include("lab2_5b.php");
	if(isset($x))
		echo "Giá trị của x là: $x";
	else
		echo "Biến x không tồn tại";
	echo "<br><i>x=30 vi x ban dau la 10 include 5b thanh x= x+10 = 20
	<br> roi sau khi dong 12 inclue 5b x+10=20+10=30</i>";
?>
</body>
</html>