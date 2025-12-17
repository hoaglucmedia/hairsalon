<?php

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
function kiemtranguyento($x) //Kiểm tra 1 số có nguyên tố hay không
    {
        if ($x < 2)
            return false;
        if ($x == 2)
            return true;
        // for($i=2;$i<=sqrt($x);$i++)
        // 	if($x%$i==0)
        // 		return false;
        $i = 2;
        while ($i <= sqrt($x)) {
            if ($x % $i == 0)
                return false;
            else
                $i++;
        }
        return true;
    }

?>