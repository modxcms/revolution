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

/* migrate display_params to output_properties, change deprecated  */
$tvs = $modx->getCollection('modTemplateVar');
foreach ($tvs as $tv) {
    $changed = false;
    $op = $tv->get('output_properties');
    $params = $tv->getDisplayParams();
    if (empty($op) && !empty($params)) {
        $tv->set('output_properties',$params);
        $op = $params;
        $changed = true;
    }
    switch ($tv->get('type')) {
        case 'textareamini':
            $tv->set('type','textarea');
            $changed = true;
            break;
        case 'textbox':
            $tv->set('type','text');
            $changed = true;
            break;
        case 'dropdown':
            $tv->set('type','listbox');
            $changed = true;
            break;
        case 'htmlarea':
            $tv->set('type','richtext');
            $changed = true;
            break;
        case 'resourcelist': /* migrate parents of 2.0.x of resourcelist TV to input props */
            $elements = $tv->get('elements');
            if (!empty($elements)) {
                $elements = explode('||',$elements);
                $ip = $tv->get('input_properties');
                if (!is_array($ip)) $ip = array();
                $ip['parents'] = implode(',',$elements);
                $tv->set('input_properties',$elements);
                $changed = true;
            }
            break;
            
    }

    if ($changed) {
        $tv->save();
    }
}

/* and drop display_params field from modTemplateVar */
$sql = "ALTER TABLE {$table} DROP COLUMN `display_params`";
$this->install->xpdo->exec($sql);