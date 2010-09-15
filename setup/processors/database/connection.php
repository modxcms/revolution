<?php
/**
 * @package setup
 */
$settings = $_POST;
unset($settings['action']);
$install->settings->store($settings);
$mode = $install->settings->get('installmode');

/* ensure driver and PDO for driver is installed */
if (!$install->driver->verifyExtension()) {
    $this->error->failure($install->lexicon($install->settings->get('database_type').'_err_ext'));
}
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
                $install->settings->get('database_type') . ':host=' . $install->settings->get('database_server')
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

$dbCollation = 'utf8_general_ci';
if ($dbExists) {
    /* get actual collation of the database */
    $stmt = $xpdo->query($install->driver->getCollation());
    if ($stmt && $stmt instanceof PDOStatement) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $dbCollation = $row['Value'];
        $stmt->closeCursor();
    }
    unset($stmt);
}


$data['collation'] = $install->settings->get('database_collation', $dbCollation);
/* get list of collations */
$stmt = $xpdo->query($install->driver->getCollations());
if ($stmt && $stmt instanceof PDOStatement) {
    $collations = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $col = array();
        $col['selected'] = ($row['Collation']==$data['collation'] ? ' selected="selected"' : '');
        $col['value'] = $row['Collation'];
        $col['name'] = $row['Collation'];
        $collations[$row['Collation']] = $col;
    }
    $stmt->closeCursor();
    ksort($collations);
    $data['collations'] = array_values($collations);
} else {
    $this->error->failure($install->lexicon('db_err_show_collations'), $errors);
}
unset($stmt);

/* set default charset */
$dbCharset = substr($data['collation'], 0, strpos($data['collation'], '_'));
$data['charset'] = $install->settings->get('database_charset', $dbCharset);
$data['connection_charset'] = $install->settings->get('database_connection_charset', $data['charset']);

/* get charsets */
$stmt = $xpdo->query($install->driver->getCharsets());
if ($stmt && $stmt instanceof PDOStatement) {
    $charsets = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $col = array();
        $col['selected'] = $row['Charset']==$data['charset'] ? ' selected="selected"' : '';
        $col['value'] = $row['Charset'];
        $col['name'] = $row['Charset'];
        $charsets[$row['Charset']] = $col;
    }
    $stmt->closeCursor();
    ksort($charsets);
    $data['charsets'] = array_values($charsets);
} else {
    $this->error->failure($install->lexicon('db_err_show_charsets'), $errors);
}
unset($stmt);

$install->settings->store(array(
    'database_charset' => $data['charset'],
    'database_connection_charset' => $data['connection_charset'],
    'database_collation' => $data['collation'],
));

$this->error->success($install->lexicon('db_success'), $data);