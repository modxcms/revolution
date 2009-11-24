<?php
/**
 * @package setup
 */
$settings = $_POST;
unset($settings['action']);
$install->settings->store($settings);
$mode = $install->settings->get('installmode');

$xpdo = $install->getConnection($mode);
if (!($xpdo instanceof xPDO)) { $this->error->failure($xpdo); }

if (!$xpdo->connect()) {
    /* allow this to pass for new installs only; will attempt to create during installation */
    if ($mode != modInstall::MODE_NEW) {
        $this->error->failure($install->lexicon['db_err_connect_upgrade']);
    }
}

$data = array();
if ($mode == modInstall::MODE_NEW) {
    $manager = $xpdo->getManager();

    $dbname = trim($install->settings->get('dbase'), '`');
    $stmt = $xpdo->query("SELECT SCHEMA_NAME FROM information_schema.schemata WHERE schema_name = '{$dbname}'");
    $created = false;
    if ($stmt) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $created = $row['SCHEMA_NAME'];
    }
    $stmt->closeCursor();

    if (!$created) {
        $created = $manager->createSourceContainer(array(
            'dbname' => $dbname,
            'host' => $install->settings->get('database_server'),
        ),
        $install->settings->get('database_user'),
        $install->settings->get('database_password'),
        array(
            'charset' => $install->settings->get('database_charset'),
            'collation' => $install->settings->get('database_collation'),
        ));
    }

    if (!$created) {
        $this->error->failure($install->lexicon['db_err_create_database']);
    }

    /* now create system settings table */
    $xpdo->addPackage('modx',MODX_CORE_PATH.'model/');
    $created = $manager->createObjectContainer('modSystemSetting');
}

/* test table prefix */
$stmt = $xpdo->query('SELECT COUNT(*) AS ct FROM '.$install->settings->get('dbase').'.`'.$install->settings->get('table_prefix').'site_content`');

if ($mode == modInstall::MODE_NEW && $stmt) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row['ct'] > 0) {
        $this->error->failure($install->lexicon['test_table_prefix_inuse']);
    }
} else if (!$stmt) {
    // this doesnt work either, ha. Needs to check to make sure DB exists.
   // $this->error->failure('MODx could not find your database. Please check your connection information and try again.');
}

$this->error->success($install->lexicon['db_success'],$data);