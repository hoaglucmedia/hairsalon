<pre><?php
$a = array(1, -3, 5); //mảng có 3 phần tử
$b = array("a"=>2, "b"=>4, "c"=>-6);//mảng có 3 phần tử.Các index của mảng là chuỗi
?>

<br> Nôi dung mảng a (key-value) 
<?php
$dem=0;
foreach($a as $key=>$value)
{
	if($value>=0)$dem++;
	echo "($key - $value )";	
}
echo"<br>so phan tu duong trong mang la : $dem phan tu";
echo "<hr> ";
?>

<br /> Nội dung mảng b: (key - value):
<?php

$c=array();
foreach($b as $k=>$v)
{
	if($v>=0)
	$c[$k]=$v;	
	echo "($k - $v )";
}
echo "<br>  Mang c <br>";
print_r($c);
?>
<br />Hiển thị nội dung mảng b ra dạng bảng:
<table border=1>
	<tr><td>STT</td><td>Key</td><td>Value</td></tr>
    <?php
	$i=0;
	foreach($b as $k=>$v)
	{	$i++;
		echo "<tr><td>$i</td>";
		echo "<td>$k</td>";
		echo "<td>$v</td></tr>";
	}
	?>
</table>