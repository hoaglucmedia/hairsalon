<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>8_5</title>
    <link rel="stylesheet" type="text/css" href="css/style.css" />
</head>

<body>
    <table width="90%" border="1" align="center">
        <tr>
            <td colspan="3">
                <?php include "include/header.php"?>
            </td>
        </tr>
        <tr>
            <td width="29%" valign="top">
                <div class='boxleft'>
                    <?php include "include/category.php"?>
                </div>
                <div class='boxleft'>
                    <?php include "include/publisher.php"?>
                </div>
            </td>
            <td width="42%" valign="top">
                <!-- Nội dung chính -->
                <?php
                    if (! empty($content)) {
                        echo $content;
                    }
                ?>
            </td>
            <td width="29%" valign="top">
                <div class="news">
                    <?php include "include/news.php"; ?>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
    </table>
</body>

</html>