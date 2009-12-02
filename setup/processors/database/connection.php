<?php
/**
 * @package setup
 */
$settings = $_POST;
unset($settings['action']);
$install->settings->store($settings);
$mode = $install->settings->get('installmode');

if ($mode == modInstall::MODE_UPGRADE_REVO) {
    $xpdo = $install->getConnection();
} else {
    $xpdo = $install->_connect($install->settings->get('database_type')
     . ':host=' . $install->settings->get('database_server')
     //. ';dbname=' . trim($install->settings->get('dbase'), '`')
     //. ';charset=' . $install->settings->get('database_connection_charset')
     //. ';collation=' . $install->settings->get('database_collation')
     ,$install->settings->get('database_user')
     ,$install->settings->get('database_password')
     ,$install->settings->get('table_prefix'));
}

if (!($xpdo instanceof xPDO)) { $this->error->failure($err); }

if (!is_object($xpdo) || !($xpdo instanceof xPDO)) {
    $this->error->failure($install->lexicon['xpdo_err_ins']);
}
if (!$xpdo->connect()) {
    /* allow this to pass for new installs only; will attempt to create during installation */
    if ($mode != modInstall::MODE_NEW) {
        $this->error->failure($install->lexicon['db_err_connect_upgrade']);
    }
}


$data = array();
/* get default collation/charset */
if (!$stmt = @$xpdo->query("SHOW SESSION VARIABLES LIKE 'collation_database'")) {
    $stmt = @$xpdo->query("SHOW SESSION VARIABLES LIKE 'collation_server'");
}
if ($stmt && $stmt instanceof PDOStatement) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $dbCollation = $row['Value'];
    $stmt->closeCursor();
}
$data['collation'] = $install->settings->get('database_collation',$dbCollation);
if (empty($data['collation'])) {
    $data['collation'] = 'utf8_general_ci';
}
unset($stmt);

/* get list of collations */
$stmt = @$xpdo->query('SHOW COLLATION');
if ($stmt && $stmt instanceof PDOStatement) {
    $data['collations'] = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $col = array();
        $col['selected'] = $row['Collation']==$data['collation'] ? ' selected="selected"' : '';
        $col['value'] = $row['Collation'];
        $col['name'] = $row['Collation'];
        $data['collations'][] = $col;
    }
    $stmt->closeCursor();
}
unset($stmt);

/* set default charset */
$dbCharset = substr($data['collation'], 0, strpos($data['collation'], '_'));
$data['charset'] = $install->settings->get('database_charset',$dbCharset);
$data['connection_charset'] = $install->settings->get('database_connection_charset',$data['charset']);

/* get charsets */
$stmt = $xpdo->query('SHOW CHARSET');
if ($stmt && $stmt instanceof PDOStatement) {
    $data['charsets'] = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $col = array();
        $col['selected'] = $row['Charset']==$data['charset'] ? ' selected="selected"' : '';
        $col['value'] = $row['Charset'];
        $col['name'] = $row['Charset'];
        $data['charsets'][] = $col;
    }
    $stmt->closeCursor();
}

$install->settings->store(array(
    'database_charset' => $data['charset'],
    'database_connection_charset' => $data['connection_charset'],
    'database_collation' => $data['collation'],
));

$this->error->success($install->lexicon['db_success'],$data);