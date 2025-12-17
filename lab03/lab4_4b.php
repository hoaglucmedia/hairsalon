<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lab 4_4b</title>
<style>
	#banco{border:solid; padding:15px; background:#E8E8E8}
	#banco .cellBlack{width:50px; height:50px; background:black; float:left; }
	#banco .cellWhite{width:50px; height:50px; background:white; float:left}
	.clear{clear:both}
</style>
</head>

<body>
<?php
/*
bảng cửu chương $n, màu nền $color
- Input: $n là một số nguyên dương (1->10)
		 $color: Tên màu nền.Mặc định là green
- Output: Bảng cửu chương, được xuât trong hàm
*/
function BCC($n, $color1, $color2, $colorHead)
{

	$output = "<table>";
	$output .= "<tr><td bgcolor='$colorHead'colspan='3'>Bảng cửu chương <?php echo $n;?></td></tr>";
	for ($i = 1; $i <= 10; $i++) {
		if ($i % 2 == 0) {
			$output .= "<tr bgcolor='$color2'>";
		} else {
			$output .= "<tr bgcolor='$color1'>";
		}
		$kq = $n * $i;
		$output .= "<td> $n</td>
				            <td>$i</td>
				            <td>$kq </td> </tr>";
	}

	$output .= "</table>";
	return $output;
}
/*
Hàm in ra bàn cờ vua với màu các ô thay đổi và được định nghĩa trong css: cellBlack, cellWhite
- Input: $size: kích thước bàn cờ: là 1 số nguyên dương (mặc định là 8)
- Output: bàn cờ HTML 

*/
function BanCo($size =8)
{
	?>
	<div id="banco">
		<?php
		for($i=1; $i<= $size; $i++)
		{
			for($j=1; $j<= $size; $j++)
			{
				$classCss = (($i+$j) %2)==0?"cellWhite":"cellBlack";
				echo "<div class='$classCss'> $i - $j</div>";
				
			}
			echo "<div class='clear' />";
			
		}
	?>
	</div>
	<?php

}
echo Bcc(6,"red","blue","green");	

Banco();
?>
</body>
</html>