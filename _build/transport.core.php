<?php
/**
 * Builds the MODx core transport package.
 *
 * @package modx
 * @subpackage build
 */
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
unset($mtime);
/* get rid of time limit */
set_time_limit(0);

error_reporting(E_ALL); ini_set('display_errors',true);

/* override with your own defines here (see build.config.sample.php) */
$f = dirname(__FILE__) . '/build.config.php';
if (file_exists($f)) {
    @require_once $f;
} else {
    die('build.config.php was not found. Please make sure you have created one using the template of build.config.sample.php.');
}
unset($f);

if (!defined('MODX_CORE_PATH'))
    define('MODX_CORE_PATH', dirname(dirname(__FILE__)) . '/core/');
require_once MODX_CORE_PATH . 'xpdo/xpdo.class.php';

/* define the MODX path constants necessary for core installation */
if (!defined('MODX_BASE_PATH'))
    define('MODX_BASE_PATH', dirname(dirname(__FILE__)) . '/');
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

/* instantiate xpdo instance */
$xpdo = new xPDO(XPDO_DSN, XPDO_DB_USER, XPDO_DB_PASS,
    array (
        xPDO::OPT_TABLE_PREFIX => XPDO_TABLE_PREFIX,
        xPDO::OPT_CACHE_PATH => MODX_CORE_PATH . 'cache/',
    ),
    array (
        PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
        PDO::ATTR_PERSISTENT => false,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
    )
);
$cacheManager= $xpdo->getCacheManager();
$xpdo->setLogLevel(xPDO::LOG_LEVEL_INFO);
$xpdo->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');

$xpdo->loadClass('transport.xPDOTransport', XPDO_CORE_PATH, true, true);
$packageDirectory = MODX_CORE_PATH . 'packages/';

$xpdo->log(xPDO::LOG_LEVEL_INFO,'Beginning build script processes...'); flush();

/* remove pre-existing package files and directory */
if (file_exists($packageDirectory . 'core.transport.zip')) {
    @unlink($packageDirectory . 'core.transport.zip');
}
if (file_exists($packageDirectory . 'core') && is_dir($packageDirectory . 'core')) {
    $cacheManager->deleteTree($packageDirectory . 'core',array(
        'deleteTop' => true,
        'skipDirs' => false,
        'extensions' => '*',
    ));
}
if (!file_exists($packageDirectory . 'core') && !file_exists($packageDirectory . 'core.transport.zip')) {
    $xpdo->log(xPDO::LOG_LEVEL_INFO,'Removed pre-existing core/ and core.transport.zip.'); flush();
} else {
    $xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not remove core/ and core.transport.zip before starting build.'); flush();
}

/* create core transport package */
$package = new xPDOTransport($xpdo, 'core', $packageDirectory);
unset($packageDirectory);

$xpdo->setPackage('modx', MODX_CORE_PATH . 'model/');
$xpdo->loadClass('modAccess');
$xpdo->loadClass('modAccessibleObject');
$xpdo->loadClass('modAccessibleSimpleObject');
$xpdo->loadClass('modPrincipal');

$xpdo->log(xPDO::LOG_LEVEL_INFO,'Core transport package created.'); flush();

/* core namespace */
$namespace = $xpdo->newObject('modNamespace');
$namespace->set('name','core');
$namespace->set('path','{core_path}');
$package->put($namespace,array(
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => true,
));
unset($namespace);
$xpdo->log(xPDO::LOG_LEVEL_INFO,'Core Namespace packaged.'); flush();

/* modWorkspace */
$collection = array ();
$collection['1'] = $xpdo->newObject('modWorkspace');
$collection['1']->fromArray(array (
    'id' => 1,
    'name' => 'Default MODx workspace',
    'active' => 1,
), '', true, true);
$attributes = array (
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => false,
);
foreach ($collection as $c) {
    $package->put($c, $attributes);
}
unset ($collection, $c, $attributes);

$xpdo->log(xPDO::LOG_LEVEL_INFO,'Default workspace packaged.'); flush();

/* modxcms.com extras provisioner */
$collection = array ();
$collection['1'] = $xpdo->newObject('transport.modTransportProvider');
$collection['1']->fromArray(array (
    'id' => 1,
    'name' => 'modxcms.com',
    'description' => 'The official MODx transport facility for 3rd party components.',
    'service_url' => 'http://rest.modxcms.com/extras/',
    'created' => strftime('%Y-%m-%d %H:%M:%S'),
), '', true, true);
$attributes = array (
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::UNIQUE_KEY => array ('name'),
);
foreach ($collection as $c) {
    $package->put($c, $attributes);
}
unset ($collection, $c, $attributes);

$xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged in modxcms.com provisioner reference.'); flush();

/* modAction */
$collection = include dirname(__FILE__).'/data/transport.core.actions.php';
if (!empty($collection)) {
    $attributes = array (
        xPDOTransport::PRESERVE_KEYS => false,
        xPDOTransport::UPDATE_OBJECT => true,
        xPDOTransport::UNIQUE_KEY => array ('namespace','controller'),
    );
    foreach ($collection as $c) {
        $package->put($c, $attributes);
    }

    $xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($collection).' modActions.'); flush();
    unset ($collection, $c, $attributes);
} else {
    $xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not load modActions.'); flush();
}

/* modMenu */
$collection = include dirname(__FILE__).'/data/transport.core.menus.php';
if (!empty($collection)) {
    $attributes = array (
        xPDOTransport::PRESERVE_KEYS => true,
        xPDOTransport::UPDATE_OBJECT => true,
        xPDOTransport::UNIQUE_KEY => 'text',
        xPDOTransport::RELATED_OBJECTS => true,
        xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array(
            'Action' => array (
                xPDOTransport::PRESERVE_KEYS => false,
                xPDOTransport::UPDATE_OBJECT => true,
                xPDOTransport::UNIQUE_KEY => array ('namespace','controller'),
                xPDOTransport::RELATED_OBJECTS => true,
                xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array(
                    'Children' => array(
                        xPDOTransport::PRESERVE_KEYS => false,
                        xPDOTransport::UPDATE_OBJECT => true,
                        xPDOTransport::UNIQUE_KEY => array ('namespace','controller'),
                    ),
                ),
            ),
            'Children' => array(
                xPDOTransport::PRESERVE_KEYS => true,
                xPDOTransport::UPDATE_OBJECT => true,
                xPDOTransport::UNIQUE_KEY => 'text',
                xPDOTransport::RELATED_OBJECTS => true,
                xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array(
                    'Action' => array (
                        xPDOTransport::PRESERVE_KEYS => false,
                        xPDOTransport::UPDATE_OBJECT => true,
                        xPDOTransport::UNIQUE_KEY => array ('namespace','controller'),
                        xPDOTransport::RELATED_OBJECTS => true,
                        xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array(
                            'Children' => array(
                                xPDOTransport::PRESERVE_KEYS => false,
                                xPDOTransport::UPDATE_OBJECT => true,
                                xPDOTransport::UNIQUE_KEY => array ('namespace','controller'),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    );
    foreach ($collection as $c) {
        $package->put($c, $attributes);
    }

    $xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($collection).' modMenus.'); flush();
    unset ($collection, $c, $attributes);
} else {
    $xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not load modMenus.'); flush();
}

/* modContentTypes */
$collection = array ();
include dirname(__FILE__).'/data/transport.core.content_types.php';
$attributes = array (
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => false,
);
foreach ($collection as $c) {
    $package->put($c, $attributes);
}
unset ($collection, $c, $attributes);

$xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged all default modContentTypes.'); flush();

/* modContext = web */
$c = $xpdo->newObject('modContext');
$c->fromArray(array (
    'key' => 'web',
    'description' => 'The default front-end context for your web site.',
), '', true, true);
$attributes = array (
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => false
);
$attributes['resolve'][] = array (
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'index.php',
    'target' => "return MODX_BASE_PATH;",
);
$attributes['resolve'][] = array (
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'ht.access',
    'target' => "return MODX_BASE_PATH;",
);
$attributes['resolve'][] = array (
    'type' => 'php',
    'source' => dirname(__FILE__) . '/resolvers/resolve.core.php',
    'target' => "return MODX_BASE_PATH . 'config.core.php';",
);
$package->put($c, $attributes);
unset ($c, $attributes);

$xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged in web context.'); flush();

/* modContext = mgr */
$c = $xpdo->newObject('modContext');
$c->fromArray(array (
    'key' => 'mgr',
    'description' => 'The default manager or administration context for content management activity.',
), '', true, true);
$attributes = array (
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => false
);
$attributes['resolve'][] = array (
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'manager/assets',
    'target' => "return MODX_MANAGER_PATH;",
);
$attributes['resolve'][] = array (
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'manager/controllers',
    'target' => "return MODX_MANAGER_PATH;",
);
$attributes['resolve'][] = array (
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'manager/templates',
    'target' => "return MODX_MANAGER_PATH;",
);
$attributes['resolve'][] = array (
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'manager/ht.access',
    'target' => "return MODX_MANAGER_PATH;",
);
$attributes['resolve'][] = array (
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'manager/index.php',
    'target' => "return MODX_MANAGER_PATH;",
);
$attributes['resolve'][] = array (
    'type' => 'php',
    'source' => dirname(__FILE__) . '/resolvers/resolve.core.php',
    'target' => "return MODX_MANAGER_PATH . 'config.core.php';",
);
$package->put($c, $attributes);
unset ($c, $attributes);

$xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged in mgr context.'); flush();

/* connector file transport */
$attributes = array (
    'vehicle_class' => 'xPDOFileVehicle',
);
$files[] = array (
    'source' => MODX_BASE_PATH . 'connectors/browser',
    'target' => "return MODX_CONNECTORS_PATH;",
);
$files[] = array (
    'source' => MODX_BASE_PATH . 'connectors/context',
    'target' => "return MODX_CONNECTORS_PATH;",
);
$files[] = array (
    'source' => MODX_BASE_PATH . 'connectors/element',
    'target' => "return MODX_CONNECTORS_PATH;",
);
$files[] = array (
    'source' => MODX_BASE_PATH . 'connectors/layout',
    'target' => "return MODX_CONNECTORS_PATH;",
);
$files[] = array (
    'source' => MODX_BASE_PATH . 'connectors/resource',
    'target' => "return MODX_CONNECTORS_PATH;",
);
$files[] = array (
    'source' => MODX_BASE_PATH . 'connectors/security',
    'target' => "return MODX_CONNECTORS_PATH;",
);
$files[] = array (
    'source' => MODX_BASE_PATH . 'connectors/system',
    'target' => "return MODX_CONNECTORS_PATH;",
);
$files[] = array (
    'source' => MODX_BASE_PATH . 'connectors/workspace',
    'target' => "return MODX_CONNECTORS_PATH;",
);
$files[] = array (
    'source' => MODX_BASE_PATH . 'connectors/lang.js.php',
    'target' => "return MODX_CONNECTORS_PATH;",
);
foreach ($files as $fileset) {
    $package->put($fileset, $attributes);
}
unset ($files, $fileset);
$fileset = array (
    'source' => MODX_BASE_PATH . 'connectors/index.php',
    'target' => "return MODX_CONNECTORS_PATH;",
);
$attributes['resolve'][] = array (
    'type' => 'php',
    'source' => dirname(__FILE__) . '/resolvers/resolve.core.php',
    'target' => "return MODX_CONNECTORS_PATH . 'config.core.php';",
);
$package->put($fileset, $attributes);
unset ($fileset, $attributes);

$xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged in connectors.'); flush();

/* modEvent collection */
$events = include dirname(__FILE__).'/data/transport.core.events.php';
if (is_array($events) && !empty($events)) {
    $attributes = array (
        xPDOTransport::PRESERVE_KEYS => false,
        xPDOTransport::UPDATE_OBJECT => true,
        xPDOTransport::UNIQUE_KEY => array ('name'),
    );
    foreach ($events as $evt) {
        $package->put($evt, $attributes);
    }
    $xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($events).' default events.'); flush();
} else {
    $xpdo->log(xPDO::LOG_LEVEL_FATAL,'Could not find default events!'); flush();
}
unset ($events, $evt, $attributes);

/* modSystemSetting collection */
$settings = require_once dirname(__FILE__).'/data/transport.core.system_settings.php';
if (!is_array($settings) || empty($settings)) { $xpdo->log(xPDO::LOG_LEVEL_FATAL,'Could not package in settings!'); flush(); }
$attributes= array (
    xPDOTransport::PRESERVE_KEYS => true
);
foreach ($settings as $setting) {
    switch ($setting->get('key')) {
        case 'filemanager_path' :
        case 'rb_base_dir' :
        case 'rb_base_url' :
        case 'site_id' :
            $attributes[xPDOTransport::UPDATE_OBJECT]= true;
            $setting->set('value', '');
            break;
        case 'session_cookie_path' :
            $attributes[xPDOTransport::UPDATE_OBJECT]= true;
            $setting->set('value', '/');
            break;
        default :
            $attributes[xPDOTransport::UPDATE_OBJECT]= false;
            break;
    }
    $package->put($setting, $attributes);
}

$xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($settings).' default system settings.'); flush();
unset ($settings, $setting, $attributes);

/* modContextSetting collection */
$collection = array ();
include dirname(__FILE__).'/data/transport.core.context_settings.php';
$attributes= array(
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => false,
);
foreach ($collection as $c) {
    $package->put($c, $attributes);
}

$xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($collection).' default context settings.'); flush();
unset ($collection, $c, $attributes);

/* modUserGroup */
$collection = array ();
include dirname(__FILE__).'/data/transport.core.usergroups.php';
$attributes = array (
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => false,
);
foreach ($collection as $c) {
    $package->put($c, $attributes);
}

$xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($collection).' default user groups.'); flush();
unset ($collection, $c, $attributes);

/* modUserGroupRole */
$collection = array ();
include dirname(__FILE__).'/data/transport.core.usergrouproles.php';
$attributes = array (
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => false,
);
foreach ($collection as $c) {
    $package->put($c, $attributes);
}

$xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($collection).' default roles Member and SuperUser.'); flush();
unset ($collection, $c, $attributes);

/* modAccessPolicy */
$collection = array ();
include dirname(__FILE__).'/data/transport.core.accesspolicies.php';
$attributes = array (
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UNIQUE_KEY => array('name'),
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
        'Permissions' => array (
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => array ('policy','name'),
        )
    )
);
foreach ($collection as $c) {
    $package->put($c, $attributes);
}

$xpdo->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($collection).' default access policies.'); flush();
unset ($collection, $c, $attributes);

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