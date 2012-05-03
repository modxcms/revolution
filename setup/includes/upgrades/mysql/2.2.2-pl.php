<?php
/**
 * Specific upgrades for Revolution 2.2.2-pl
 *
 * @var modX $modx
 * @package setup
 * @subpackage upgrades
 */
/* handle new class creation */
$classes = array(
);
if (!empty($classes)) {
    $this->createTable($classes);
}
unset($classes);

/* [#7646] Increase size of session.id field to handle up to 255 chars */
$class = 'modSession';
$table = $modx->getTableName('modSession');
$description = $this->install->lexicon('modify_column',array('column' => 'id', 'old' => 'varchar(40)', 'new' => 'varchar(255)', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'alterField'), array($class, 'id'));
