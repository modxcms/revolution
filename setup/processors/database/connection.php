<?php
/**
 * @package setup
 * @var modInstall $install
 */
if (!isset($_POST['database'])) $_POST['database'] = $_POST['dbase'];
$settings = $_POST;
unset($settings['action']);
$install->settings->store($settings);
$mode = $install->settings->get('installmode');
/* if advanced upgrade, get old settings */
if($mode === modInstall::MODE_UPGRADE_REVO_ADVANCED) {
    $settings = array_merge($settings, $install->request->getConfig($mode));
}

$install->loadDriver();
/* PDO for driver is installed */
//if (!$install->driver->verifyExtension()) {
//    $this->error->failure($install->lexicon($install->settings->get('database_type').'_err_ext'));
//}
if (!$install->driver->verifyPDOExtension()) {
    $this->error->failure($install->lexicon($install->settings->get('database_type').'_err_pdo'));
}

/* get an instance of xPDO using the install settings */
$xpdo = $install->getConnection($mode);

$errors = array();
$dbExists = false;
if (!is_object($xpdo) || !($xpdo instanceof xPDO)) {
    if (is_bool($xpdo)) {
        $this->error->failure($install->lexicon('xpdo_err_ins'));
    } else {
        $this->error->failure($xpdo);
    }
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
    } else {
        /* otherwise try to connect to the server without the database */
        $xpdo = $install->_connect(
                $install->settings->get('server_dsn')
                ,$install->settings->get('database_user')
                ,$install->settings->get('database_password')
                ,$install->settings->get('table_prefix')
        );
        if (!is_object($xpdo) || !($xpdo instanceof xPDO)) {
            $this->error->failure($install->lexicon('xpdo_err_ins'), $errors);
        }
        $xpdo->setLogTarget(array(
            'target' => 'ARRAY'
            ,'options' => array('var' => & $errors)
        ));
        if (!$xpdo->connect()) {
            $this->error->failure($install->lexicon('db_err_connect_server'), $errors);
        }
    }
}

$data = array();

/* verify database versions */
$server = $install->driver->verifyServerVersion();
$client = $install->driver->verifyClientVersion();
$data['server_version'] = $server['version'];
$data['server_version_msg'] = $server['message'];
$data['server_version_result'] = $server['result'];
$data['client_version'] = $client['version'];
$data['client_version_msg'] = $client['message'];
$data['client_version_result'] = $client['result'];
if ($server['result'] == 'failure') {
    $this->error->failure($server['message'],$data);
}
if ($client['result'] == 'failure') {
    $this->error->failure($client['message'],$data);
}

/* get current/default collation */
$dbCollation = $install->driver->getCollation();
$data['collation'] = $install->settings->get('database_collation', $dbCollation);

/* get list of collations */
$dbCollations = $install->driver->getCollations($data['collation']);
if ($dbCollations === null) {
    $this->error->failure($install->lexicon('db_err_show_collations'), $data);
}
$data['collations'] = array_values($dbCollations);

/* set default charset based on collation */
$data['charset'] = $install->driver->getCharset($data['collation']);
$data['connection_charset'] = $install->settings->get('database_connection_charset', $data['charset']);

/* get charsets */
$dbCharsets = $install->driver->getCharsets();
if ($dbCharsets === null) {
    $this->error->failure($install->lexicon('db_err_show_charsets'), $data);
}
$data['charsets'] = array_values($dbCharsets);

$install->settings->store(array(
    'database_charset' => $data['charset'],
    'database_connection_charset' => $data['connection_charset'],
    'database_collation' => $data['collation'],
));

$this->error->success($install->lexicon('db_success'), $data);