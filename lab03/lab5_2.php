<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>lab5_2</title>
</head>

<body>
    <?php
    function kiemtrachuoidx($chuoi = "asdsa")
    {
        $chuoinguoc = strrev($chuoi);
        if ($chuoi === $chuoinguoc) return true;
        else return false;
    }
    if (kiemtrachuoidx("abcba"))
        echo "true";
    else
        echo "false"
    ?>
</body>

</html>