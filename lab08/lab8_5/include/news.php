<?php
    $pubs = $db->select("select * from news ");
    foreach ($pubs as $r) {
    ?>
<div>
    <a href="index.php?mod=news&ac=detail&id=<?php echo $r["id"]; ?>">
        <?php echo $r["title"]; ?></a>
</div>
<?php
    }

?>