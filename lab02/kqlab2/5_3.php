<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
        $a = 2;
        $b = 3;
        $c = 1;

        echo "Phương trình: {$a}x² + {$b}x + {$c} = 0<br>";
        echo "a=$a,b=$b,c=$c <br>";

        $delta = $b * $b - 4 * $a * $c;
        echo "Δ = b² - 4ac = $delta<br>";

        if ($delta < 0) {
            echo "Phương trình vô nghiệm.";
        } elseif ($delta == 0) {
            $x = -$b / (2 * $a);
            echo "Phương trình có nghiệm kép: x = $x";
        } else {
            $x1 = (-$b + sqrt($delta)) / (2 * $a);
            $x2 = (-$b - sqrt($delta)) / (2 * $a);
            echo "Phương trình có hai nghiệm phân biệt:<br>";
            echo "x₁ = $x1<br>";
            echo "x₂ = $x2";
        }
    ?>
</body>

</html>