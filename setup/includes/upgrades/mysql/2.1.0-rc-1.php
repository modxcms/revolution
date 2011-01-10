<?php
/**
 * Specific upgrades for Revolution 2.1.0
 *
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


/* add input_properties, output_properties fields to modTemplateVar */
$class = 'modTemplateVar';
$table = $modx->getTableName($class);

$description = $this->install->lexicon('add_column',array('column' => 'input_properties','table' => $table));
$sql = "ALTER TABLE {$table} ADD `input_properties` TEXT NULL AFTER `properties`";
$this->processResults($class,$description,$sql);

$description = $this->install->lexicon('add_column',array('column' => 'output_properties','table' => $table));
$sql = "ALTER TABLE {$table} ADD `output_properties` TEXT NULL AFTER `input_properties`";
$this->processResults($class,$description,$sql);

/* migrate display_params to output_properties */
$tvs = $modx->getCollection('modTemplateVar');
foreach ($tvs as $tv) {
    $op = $tv->get('output_properties');
    $params = $tv->getDisplayParams();
    if (empty($op) && !empty($params)) {
        $tv->set('output_properties',$params);
        $tv->save();
    }
}

/* and drop display_params field from modTemplateVar */
$sql = "ALTER TABLE {$table} DROP COLUMN `display_params`";
$this->install->xpdo->exec($sql);
