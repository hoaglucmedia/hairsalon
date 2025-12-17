<?php
$arr = array();
$r = array("id" => "sp1", "name " => "Sản phẩm 1 ");
$arr[] = $r;
$r = array("id" => "sp2", "name " => "Sản phẩm 2 ");
$arr[] = $r;
$r = array("id" => "sp3", "name " => "Sản phẩm 3 ");
$arr[] = $r;
function showArray2($arr)
{
    echo "<table border=1 cellspacing=0>
    <tr>
    <th>STT</th>
    <th>MaSanPham</th>
    <th>TenSanPham</th>
    </tr>
    ";
    $count=0;
    foreach ($arr as $v) {
        echo "<tr>
        <td>".++$count."</td>
        <td>".$v["id"]."</td>
        <td>".$v["name "]."</td>
        </tr>";
    }
    echo "</table>";
}
showArray2($arr);
?>
