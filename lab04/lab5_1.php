<?php 
function showArray($arr)
{
    echo "<table border=1 cellspacing=0>
    <tr>
    <th>Index</th>
    <th>Value</th>
    </tr>
    ";
    foreach ($arr as $k=>$v)
    {
       echo "<tr><td>$k</td><td>$v</td></tr>";
    }
    echo "</table>";
}
$b = array();
for($i=0;$i<=10;$i++)
{
    $b[]=rand(1,1000);
}
showArray($b);
?>