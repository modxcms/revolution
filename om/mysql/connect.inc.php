<?php
if (!empty($this->config['charset'])) {
    $sql = '';
    $method = 'SET NAMES';
    if (!empty($this->config['connection_method'])) {
        $method = $this->config['connection_method'];
    }
    $sql.= "{$method} '{$this->config['charset']}'";
    if ($method == 'SET NAMES' && !empty($this->config['collation'])) {
        $sql.= " COLLATE '{$this->config['collation']}'";
    }
    $this->pdo->exec($sql);
}