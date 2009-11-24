<?php
/**
 * @package setup
 */
$settings = $_POST;
unset($settings['action']);
$install->settings->store($settings);
$mode = $install->settings->get('installmode');

$xpdo = $install->_connect($install->settings->get('database_type')
 . ':host=' . $install->settings->get('database_server')
 . ';dbname=' . trim($install->settings->get('dbase'), '`')
 . ';charset=' . $install->settings->get('database_connection_charset')
 . ';collation=' . $install->settings->get('database_collation')
 ,$install->settings->get('database_user')
 ,$install->settings->get('database_password')
 ,$install->settings->get('table_prefix'));

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

/* get default collation */
if (!$stmt = @$xpdo->query("SHOW SESSION VARIABLES LIKE 'collation_database'")) {
    $stmt = @$xpdo->query("SHOW SESSION VARIABLES LIKE 'collation_server'");
}
if ($stmt && $stmt instanceof PDOStatement) {
    $data['collation'] = array();
    $row = $stmt->fetch();
    $data['collation'] = $row[1];
    $stmt->closeCursor();
}
if (empty($data['collation'])) {
    $data['collation'] = 'utf8_general_ci';
}

/* get list of collations */
$stmt = $xpdo->query('SHOW COLLATION');
if ($stmt && $stmt instanceof PDOStatement) {
    $data['collations'] = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $col = array();
        $col['selected'] = $row['Collation']==$data['collation'] ? ' selected="selected"' : '';
        $col['value'] = $row['Collation'];
        $col['name'] = $row['Collation'];
        $data['collations'][] = $col;
    }
}

/* set default charset */
$data['charset'] = substr($data['collation'], 0, strpos($data['collation'], '_'));
$data['connection_charset'] = $data['charset'];

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
}

$install->settings->store(array(
    'database_charset' => $data['charset'],
    'database_connection_charset' => $data['charset'],
    'database_collation' => $data['collation'],
));

$this->error->success($install->lexicon['db_success'],$data);