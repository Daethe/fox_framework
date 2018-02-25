<?php
use \Fox\App\App;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Light framework</title>
		<?= App::getAssets()->CSS()->dump() ?>
    </head>
    <body>
        <?= $content ?>
		<?= App::getAssets()->JS()->dump() ?>
    </body>
</html>