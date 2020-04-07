<?php
/**
 * Builds the MODX core transport package.
 *
 * @package modx
 * @subpackage build
 */
$tstart = microtime(true);

/* get rid of time limit */
set_time_limit(0);

error_reporting(E_ALL | E_STRICT); ini_set('display_errors',true);

/* buildImage can be defined for running against a specific build image
    wc default means it is run against working copy */
$buildImage = 'wc';
if (!empty($argv) && $argc > 1) {
    $buildImage = $argv[1];
}
/* if buildImage is other than wc or blank, try to load a config file for
    distributions that uses the buildImage variable (see build.distrib.config.sample.php) */
$buildConfig = empty($buildImage) || $buildImage === 'wc'
        ? (dirname(__FILE__) . '/build.config.php')
        : (dirname(__FILE__) . '/build.distrib.config.php');
if (!empty($argv) && $argc > 2) {
    $buildConfig = realpath($argv[2]);
}

/* override with your own defines here (see build.config.sample.php) */
$included = false;
if (file_exists($buildConfig)) {
    $included = @include $buildConfig;
}
if (!$included)
    die($buildConfig . ' was not found. Please make sure you have created one using the template of build.config.sample.php.');

unset($included);

if (!defined('MODX_CORE_PATH'))
    define('MODX_CORE_PATH', dirname(__DIR__) . '/core/');

require MODX_CORE_PATH . 'vendor/autoload.php';

use MODX\Revolution\modContext;
use MODX\Revolution\modNamespace;
use MODX\Revolution\modWorkspace;
use MODX\Revolution\Transport\modTransportProvider;
use xPDO\Transport\xPDOFileVehicle;
use xPDO\xPDO;
use xPDO\Transport\xPDOTransport;

/* define the MODX path constants necessary for core installation */
if (!defined('MODX_BASE_PATH'))
    define('MODX_BASE_PATH', dirname(MODX_CORE_PATH) . '/');
if (!defined('MODX_MANAGER_PATH'))
    define('MODX_MANAGER_PATH', MODX_BASE_PATH . 'manager/');
if (!defined('MODX_CONNECTORS_PATH'))
    define('MODX_CONNECTORS_PATH', MODX_BASE_PATH . 'connectors/');
if (!defined('MODX_ASSETS_PATH'))
    define('MODX_ASSETS_PATH', MODX_BASE_PATH . 'assets/');

/* define the connection variables */
if (!defined('XPDO_DSN'))
    define('XPDO_DSN', 'mysql:host=localhost;dbname=modx;charset=utf8');
if (!defined('XPDO_DB_USER'))
    define('XPDO_DB_USER', 'root');
if (!defined('XPDO_DB_PASS'))
    define('XPDO_DB_PASS', '');
if (!defined('XPDO_TABLE_PREFIX'))
    define('XPDO_TABLE_PREFIX', 'modx_');

/* define the actual _build location for including build assets */
if (!defined('MODX_BUILD_DIR'))
    define('MODX_BUILD_DIR', MODX_BASE_PATH . '_build/');

/* get properties */
$properties = [];
$f = dirname(__FILE__) . '/build.properties.php';
$included = false;
if (file_exists($f)) {
    $included = @include $f;
}
if (!$included)
    die('build.properties.php was not found. Please make sure you have created one using the template of build.properties.sample.php.');

unset($f, $included);

/* instantiate xpdo instance */
$xpdo = new xPDO(XPDO_DSN, XPDO_DB_USER, XPDO_DB_PASS,
    [
        xPDO::OPT_TABLE_PREFIX => XPDO_TABLE_PREFIX,
        xPDO::OPT_CACHE_PATH => MODX_CORE_PATH . 'cache/',
    ],
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
    ]
);
$cacheManager= $xpdo->getCacheManager();
$xpdo->setLogLevel(xPDO::LOG_LEVEL_INFO);
$xpdo->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');

$packageDirectory = MODX_CORE_PATH . 'packages/';

$xpdo->log(xPDO::LOG_LEVEL_INFO,'Beginning build script processes...'); flush();

/* remove pre-existing package files and directory */
if (file_exists($packageDirectory . 'core.transport.zip')) {
    @unlink($packageDirectory . 'core.transport.zip');
}
if (file_exists($packageDirectory . 'core') && is_dir($packageDirectory . 'core')) {
    $cacheManager->deleteTree($packageDirectory . 'core', [
        'deleteTop' => true,
        'skipDirs' => false,
        'extensions' => [],
    ]);
}
if (!file_exists($packageDirectory . 'core') && !file_exists($packageDirectory . 'core.transport.zip')) {
    $xpdo->log(xPDO::LOG_LEVEL_INFO,'Removed pre-existing core/ and core.transport.zip.'); flush();
} else {
    $xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not remove core/ and core.transport.zip before starting build.'); flush();
}

/* create core transport package */
$package = new xPDOTransport($xpdo, 'core', $packageDirectory);
unset($packageDirectory);

$xpdo->log(xPDO::LOG_LEVEL_INFO,'Core transport package created.'); flush();

/* core namespace */
$namespace = $xpdo->newObject(modNamespace::class);
$namespace->set('name','core');
$namespace->set('path','{core_path}');
$namespace->set('assets_path','{assets_path}');
$package->put($namespace, [
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => true,
]);
unset($namespace);
$xpdo->log(xPDO::LOG_LEVEL_INFO,'Core Namespace packaged.'); flush();

/* modWorkspace */
$collection = [];
$collection['1'] = $xpdo->newObject(modWorkspace::class);
$collection['1']->fromArray([
    'id' => 1,
    'name' => 'Default MODX workspace',
    'path' => '{core_path}',
    'active' => 1,
], '', true, true);
$attributes = [
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => false,
];
foreach ($collection as $c) {
    $package->put($c, $attributes);
}
unset ($collection, $c, $attributes);

$xpdo->log(xPDO::LOG_LEVEL_INFO,'Default workspace packaged.'); flush();

/* modx.com extras provisioner */
$collection = [];
$collection['1'] = $xpdo->newObject(modTransportProvider::class);
$collection['1']->fromArray([
    'id' => 1,
    'name' => 'modx.com',
    'description' => 'The official MODX transport provider for 3rd party components.',
    'service_url' => 'https://rest.modx.com/extras/',
    'created' => strftime('%Y-%m-%d %H:%M:%S'),
], '', true, true);
$attributes = [
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::UNIQUE_KEY => ['name'],
];
foreach ($collection as $c) {
    $package->put($c, $attributes);
}
unset ($collection, $c, $attributes);

$xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged modx.com transport provider.'); flush();

/* modMenu */
$collection = include MODX_BUILD_DIR . 'data/transport.core.menus.php';
if (!empty($collection)) {
    $attributes = [
        xPDOTransport::PRESERVE_KEYS => true,
        xPDOTransport::UPDATE_OBJECT => true,
        xPDOTransport::UNIQUE_KEY => 'text',
        xPDOTransport::RELATED_OBJECTS => true,
        xPDOTransport::RELATED_OBJECT_ATTRIBUTES => [
            'Children' => [
                xPDOTransport::PRESERVE_KEYS => true,
                xPDOTransport::UPDATE_OBJECT => true,
                xPDOTransport::UNIQUE_KEY => 'text',
            ],
        ],
    ];
    foreach ($collection as $c) {
        $package->put($c, $attributes);
    }

    $xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($collection).' modMenus.'); flush();
    unset ($collection, $c, $attributes);
} else {
    $xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not load modMenus.'); flush();
}

/* modContentTypes */
$collection = [];
include MODX_BUILD_DIR . 'data/transport.core.content_types.php';
$attributes = [
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => false,
    xPDOTransport::UNIQUE_KEY => 'mime_type',
];
foreach ($collection as $c) {
    $package->put($c, $attributes);
}
unset ($collection, $c, $attributes);

$xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged all default modContentTypes.'); flush();

/* modEvent collection */
$events = include MODX_BUILD_DIR . 'data/transport.core.events.php';
if (is_array($events) && !empty($events)) {
    $attributes = [
        xPDOTransport::PRESERVE_KEYS => true,
        xPDOTransport::UPDATE_OBJECT => true,
    ];
    foreach ($events as $evt) {
        $package->put($evt, $attributes);
    }
    $xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($events).' default events.'); flush();
} else {
    $xpdo->log(xPDO::LOG_LEVEL_FATAL,'Could not find default events!'); flush();
}
unset ($events, $evt, $attributes);

/* modSystemSetting collection */
$settings = include MODX_BUILD_DIR . 'data/transport.core.system_settings.php';
if (!is_array($settings) || empty($settings)) { $xpdo->log(xPDO::LOG_LEVEL_FATAL,'Could not package in settings!'); flush(); }
$attributes= [
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => false,
];
foreach ($settings as $setting) {
    $package->put($setting, $attributes);
}

$xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($settings).' default system settings.'); flush();
unset ($settings, $setting, $attributes);

/* modContextSetting collection */
$collection = [];
include MODX_BUILD_DIR . 'data/transport.core.context_settings.php';
$attributes= [
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => false,
    xPDOTransport::UNIQUE_KEY => ['context_key', 'key']
];
foreach ($collection as $c) {
    $package->put($c, $attributes);
}

$xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($collection).' default context settings.'); flush();
unset ($collection, $c, $attributes);

/* modUserGroup */
$collection = [];
include MODX_BUILD_DIR . 'data/transport.core.usergroups.php';
$attributes = [
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => false,
];
foreach ($collection as $c) {
    $package->put($c, $attributes);
}

$xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($collection).' default user groups.'); flush();
unset ($collection, $c, $attributes);

/* modDashboard */
$collection = [];
include MODX_BUILD_DIR . 'data/transport.core.dashboards.php';
$attributes = [
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::UNIQUE_KEY => ['id'],
];
foreach ($collection as $c) {
    $package->put($c, $attributes);
}

$xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($collection).' default dashboards.'); flush();
unset ($collection, $c, $attributes);

/* modMediaSource */
$collection = [];
include MODX_BUILD_DIR . 'data/transport.core.media_sources.php';
$attributes = [
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => false,
    xPDOTransport::UNIQUE_KEY => ['id'],
];
foreach ($collection as $c) {
    $package->put($c, $attributes);
}

$xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($collection).' default media sources.'); flush();
unset ($collection, $c, $attributes);

/* modDashboardWidget */
$widgets = include MODX_BUILD_DIR . 'data/transport.core.dashboard_widgets.php';
if (is_array($widgets)) {
    $attributes = [
        xPDOTransport::PRESERVE_KEYS => false,
        xPDOTransport::UPDATE_OBJECT => false,
        xPDOTransport::UNIQUE_KEY => ['name'],
    ];
    $ct = count($widgets);
    $idx = 0;
    foreach ($widgets as $widget) {
        $idx++;
        if ($idx == $ct) {
            $attributes['resolve'][] = [
                'type' => 'php',
                'source' => MODX_BUILD_DIR . 'resolvers/resolve.dashboardwidgets.php',
            ];
        }
        $package->put($widget, $attributes);
    }
    $xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($widgets).' default dashboard widgets.'); flush();
} else {
    $xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not load dashboard widgets!'); flush();
}
unset ($widgets,$widget,$attributes,$ct,$idx);

/* modUserGroupRole */
$collection = [];
include MODX_BUILD_DIR . 'data/transport.core.usergrouproles.php';
$attributes = [
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => false,
];
foreach ($collection as $c) {
    $package->put($c, $attributes);
}

$xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($collection).' default roles Member and SuperUser.'); flush();
unset ($collection, $c, $attributes);

/* modAccessPolicyTemplateGroups */
$templateGroups = include MODX_BUILD_DIR . 'data/transport.core.accesspolicytemplategroups.php';
$attributes = [
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UNIQUE_KEY => ['name'],
    xPDOTransport::UPDATE_OBJECT => true,
];
if (is_array($templateGroups)) {
    foreach ($templateGroups as $templateGroup) {
        $package->put($templateGroup, $attributes);
    }
    $xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($templateGroups).' default Access Policy Template Groups.'); flush();
} else {
    $xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not package in Access Policy Template Groups.');
}
unset ($templateGroups, $templateGroup, $attributes);


/* modAccessPolicyTemplate */
$templates = include MODX_BUILD_DIR . 'data/transport.core.accesspolicytemplates.php';
$attributes = [
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UNIQUE_KEY => ['name'],
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => [
        'Permissions' => [
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => ['template','name'],
        ],
    ]
];
if (is_array($templates)) {
    $ct = count($templates);
    $idx = 0;
    foreach ($templates as $template) {
        $idx++;
        if ($idx == $ct) {
            $attributes['resolve'][] = [
                'type' => 'php',
                'source' => MODX_BUILD_DIR . 'resolvers/resolve.policytemplates.php',
            ];
        }
        $package->put($template, $attributes);
    }
    $xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($templates).' default Access Policy Templates.'); flush();
} else {
    $xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not package in Access Policy Templates.');
}
unset ($templates,$template,$idx,$ct,$attributes);

/* modAccessPolicy */
$policies = include MODX_BUILD_DIR . 'data/transport.core.accesspolicies.php';
$attributes = [
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UNIQUE_KEY => ['name'],
    xPDOTransport::UPDATE_OBJECT => true,
];
if (is_array($policies)) {
    $ct = count($policies);
    $idx = 0;
    foreach ($policies as $policy) {
        $idx++;
        if ($idx == $ct) {
            $attributes['resolve'][] = [
                'type' => 'php',
                'source' => MODX_BUILD_DIR . 'resolvers/resolve.policies.php',
            ];
        }
        $package->put($policy, $attributes);
    }
    $xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($policies).' default Access Policies.'); flush();
} else {
    $xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not package in Access Policies.');
}
unset ($policies,$policy,$idx,$ct,$attributes);



/* modContext = web */
$c = $xpdo->newObject(modContext::class);
$c->fromArray([
    'key' => 'web',
    'name' => 'Website',
    'description' => 'The default front-end context for your web site.',
], '', true, true);
$attributes = [
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => false
];
$attributes['resolve'][] = [
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'index.php',
    'target' => "return MODX_BASE_PATH;",
];
$attributes['resolve'][] = [
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'ht.access',
    'target' => "return MODX_BASE_PATH;",
];
$attributes['resolve'][] = [
    'type' => 'php',
    'source' => MODX_BUILD_DIR . 'resolvers/resolve.core.php',
    'target' => "return MODX_BASE_PATH . 'config.core.php';",
];
$package->put($c, $attributes);
unset ($c, $attributes);

$xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged in web context.'); flush();

/* modContext = mgr */
$c = $xpdo->newObject(modContext::class);
$c->fromArray([
    'key' => 'mgr',
    'name' => 'Manager',
    'description' => 'The default manager or administration context for content management activity.',
], '', true, true);
$attributes = [
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => false
];
$attributes['resolve'][] = [
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'manager/assets',
    'target' => "return MODX_MANAGER_PATH;",
];
$attributes['resolve'][] = [
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'manager/controllers',
    'target' => "return MODX_MANAGER_PATH;",
];
$attributes['resolve'][] = [
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'manager/templates',
    'target' => "return MODX_MANAGER_PATH;",
];
$attributes['resolve'][] = [
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'manager/ht.access',
    'target' => "return MODX_MANAGER_PATH;",
];
$attributes['resolve'][] = [
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'manager/index.php',
    'target' => "return MODX_MANAGER_PATH;",
];
$attributes['resolve'][] = [
    'type' => 'php',
    'source' => MODX_BUILD_DIR . 'resolvers/resolve.core.php',
    'target' => "return MODX_MANAGER_PATH . 'config.core.php';",
];
$package->put($c, $attributes);
unset ($c, $attributes);

$xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged in mgr context.'); flush();

/* connector file transport */
$attributes = [
    'vehicle_class' => xPDOFileVehicle::class,
];
$files[] = [
    'source' => MODX_BASE_PATH . 'connectors/system',
    'target' => "return MODX_CONNECTORS_PATH;",
];
$files[] = [
    'source' => MODX_BASE_PATH . 'connectors/lang.js.php',
    'target' => "return MODX_CONNECTORS_PATH;",
];
$files[] = [
    'source' => MODX_BASE_PATH . 'connectors/modx.config.js.php',
    'target' => "return MODX_CONNECTORS_PATH;",
];
foreach ($files as $fileset) {
    $package->put($fileset, $attributes);
}
unset ($files, $fileset);
$fileset = [
    'source' => MODX_BASE_PATH . 'connectors/index.php',
    'target' => "return MODX_CONNECTORS_PATH;",
];
$attributes['resolve'][] = [
    'type' => 'php',
    'source' => MODX_BUILD_DIR . 'resolvers/resolve.core.php',
    'target' => "return MODX_CONNECTORS_PATH . 'config.core.php';",
];
$attributes['resolve'][] = [
    'type' => 'php',
    'source' => MODX_BUILD_DIR . 'resolvers/resolve.actionfields.php',
];
$package->put($fileset, $attributes);
unset ($fileset, $attributes);

$xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged in connectors.'); flush();


/* zip up package */
$xpdo->log(xPDO::LOG_LEVEL_INFO,'Beginning to zip up transport package...'); flush();
if ($package->pack()) {
    $xpdo->log(xPDO::LOG_LEVEL_INFO,'Transport zip created. Build script finished.'); flush();
} else {
    $xpdo->log(xPDO::LOG_LEVEL_INFO,'Error creating transport zip!'); flush();
}

$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tend = $mtime;
$totalTime = ($tend - $tstart);
$totalTime = sprintf("%2.4f s", $totalTime);

echo "\nExecution time: {$totalTime}\n"; flush();
exit ();
