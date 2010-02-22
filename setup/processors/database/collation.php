<?php
/**
 * @package setup
 */
$settings = $_POST;
$settings['database_charset'] = $settings['database_connection_charset'];
unset($settings['action']);
$install->settings->store($settings);
$mode = $install->settings->get('installmode');

$data = array();

/* get an instance of xPDO using the install settings */
$xpdo = $install->getConnection($mode);
$dbExists = false;
if (!is_object($xpdo) || !($xpdo instanceof xPDO)) {
    $this->error->failure($install->lexicon['xpdo_err_ins']);
}

/* try to get a connection to the actual database */
$dbExists = $xpdo->connect();
if (!$dbExists) {
    if ($mode != modInstall::MODE_NEW) {
        $this->error->failure($install->lexicon['db_err_connect_upgrade']);
    } elseif ($xpdo->getManager()) {
        /* otherwise try to create the database */
        $dbExists = $xpdo->manager->createSourceContainer(
            array(
                'dbname' => trim($install->settings->get('dbase'), '`')
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
            $this->error->failure($install->lexicon['db_err_create_database']);
        } else {
            $xpdo = $install->getConnection($mode);
        }
    } else {
        $this->error->failure($install->lexicon['db_err_connect_server']);
    }
}
if (!is_object($xpdo) || !($xpdo instanceof xPDO) || !$xpdo->connect()) {
    $this->error->failure($install->lexicon['db_err_connect']);
}

/* test table prefix */
$count = null;
$stmt = $xpdo->query('SELECT COUNT(*) AS ct FROM `'.trim($install->settings->get('dbase'), '`').'`.`'.$install->settings->get('table_prefix').'site_content`');
if ($stmt) {
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $count = (integer) $row['ct'];
    }
    $stmt->closeCursor();
}
if ($mode == modInstall::MODE_NEW && $count !== null) {
    $this->error->failure($install->lexicon['test_table_prefix_inuse']);
} elseif ($mode == modInstall::MODE_UPGRADE_REVO && $count === null) {
    $this->error->failure($install->lexicon['test_table_prefix_nf']);
}

$this->error->success($install->lexicon['db_success'], $data);