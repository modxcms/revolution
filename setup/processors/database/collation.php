<?php
/**
 * @package setup
 */
$settings = $_POST;
$settings['database_charset'] = $settings['database_connection_charset'];
unset($settings['action']);
$install->settings->store($settings);
$mode = $install->settings->get('installmode');
/* if advanced upgrade, get old settings */
if($mode === modInstall::MODE_UPGRADE_REVO_ADVANCED) {
    $settings = array_merge($settings, $install->request->getConfig($mode));
}

$data = array();
$errors = array();

/* get an instance of xPDO using the install settings */
$xpdo = $install->getConnection($mode);
$dbExists = false;
if (!is_object($xpdo) || !($xpdo instanceof xPDO)) {
    $this->error->failure($install->lexicon('xpdo_err_ins'));
}

$xpdo->setLogTarget(array(
    'target' => 'ARRAY'
    ,'options' => array('var' => & $errors)
));

/* try to get a connection to the actual database */
$dbExists = $xpdo->connect();
if (!$dbExists) {
    if ($mode != modInstall::MODE_NEW) {
        $this->error->failure($install->lexicon('db_err_connect_upgrade'), $errors);
    } elseif ($xpdo->getManager()) {
        /* otherwise try to create the database */
        $dbExists = $xpdo->manager->createSourceContainer(
            array(
                'dbname' => $install->settings->get('dbase')
                ,'host' => $install->settings->get('database_server')
            )
            ,$install->settings->get('database_user')
            ,$install->settings->get('database_password')
            ,array(
                'charset' => $install->settings->get('database_connection_charset')
                ,'collation' => $install->settings->get('database_collation')
            )
        );
        if (!$dbExists) {
            $this->error->failure($install->lexicon('db_err_create_database'), $errors);
        } else {
            $xpdo = $install->getConnection($mode);
            if (!is_object($xpdo) || !($xpdo instanceof xPDO)) {
                $this->error->failure($install->lexicon('xpdo_err_ins'), $errors);
            }
            $xpdo->setLogTarget(array(
                'target' => 'ARRAY'
                ,'options' => array('var' => & $errors)
            ));
        }
    } else {
        $this->error->failure($install->lexicon('db_err_connect_server'), $errors);
    }
}
if (!$xpdo->connect()) {
    $this->error->failure($install->lexicon('db_err_connect'), $errors);
}

/* test table prefix */
$count = null;
$database = $install->settings->get('dbase');
$prefix = $install->settings->get('table_prefix');
$stmt = $xpdo->query($install->driver->testTablePrefix($database,$prefix));
if ($stmt) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $count = (integer) $row['ct'];
    }
    $stmt->closeCursor();
}
if ($mode == modInstall::MODE_NEW && $count !== null) {
    $this->error->failure($install->lexicon('test_table_prefix_inuse'), $errors);
} elseif (($mode == modInstall::MODE_UPGRADE_REVO || $mode == modInstall::MODE_UPGRADE_REVO_ADVANCED) && $count === null) {
    $this->error->failure($install->lexicon('test_table_prefix_nf'), $errors);
}

$this->error->success($install->lexicon('db_success'), $data);