<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>lab4_1</title>
</head>
<body>
    <?php 
    $tong=0;
    
    for($i=2;$i<=100;$i++)
    {
        if($i%2==0) $tong+=$i;
    }
    echo "tong la $tong "
    ?>
</body>
</html>