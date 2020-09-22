<?= "<?php\n" ?>

$config = [
    'defaultRoute' => '<?= strtolower($moduleClass) ?>/index',
    'params' => require __DIR__ . '/params.php',
];

return $config;