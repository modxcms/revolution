<?php

use xPDO\xPDO;

$tstart = microtime(true);
set_time_limit(0);

require_once dirname(__FILE__) . '/build.config.php';

require MODX_CORE_PATH . 'vendor/autoload.php';

$sources = [
    'model' => MODX_CORE_PATH,
    'schema' => MODX_CORE_PATH . 'model/schema/',
    'schema_file' => 'modx.mysql.schema.xml'
];

/* instantiate xpdo instance */
$xpdo = new xPDO(XPDO_DSN, XPDO_DB_USER, XPDO_DB_PASS,
    [
        xPDO::OPT_TABLE_PREFIX => XPDO_TABLE_PREFIX,
        xPDO::OPT_CACHE_PATH => MODX_CORE_PATH . 'cache/'
    ],
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
    ]
);

$manager = $xpdo->getManager();
$generator = $manager->getGenerator();

$result = $generator->parseSchema($sources['schema'].$sources['schema_file'], $sources['model']);

$tend = microtime(true);
$totalTime = $tend - $tstart;
$totalTime = sprintf("%2.4f s", $totalTime);

echo "\nDone.\nExecution time: {$totalTime}\n";
