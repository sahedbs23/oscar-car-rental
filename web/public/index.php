<?php

include '../app/vendor/autoload.php';
//$foo = new App\Oscar\Foo();
$app = new App\Oscar\Services\VehicleImportService();
echo $app
    ->readFiles()
    ->toJson();
exit();

?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Docker <?php echo $foo->getName(); ?></title>
    </head>
    <body>
        <h1>Docker <?php echo $foo->getName(); ?></h1>
    <?php echo  __DIR__; ?>
    </body>
</html>
