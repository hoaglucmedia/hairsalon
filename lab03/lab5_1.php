<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>lab5_1</title>
</head>
<body>
    <?php
    include ("function.php");
    function xuatNSNT($n)
    {
        echo"Day cac so nguyen to la";
        for($i=2;$i<=$n;$i++)
        {
            if(kiemtranguyento($i)==true)
            {
                echo "$i;";
            }
        }

    }
    xuatNSNT(23);
    ?>
   
</body>
</html>