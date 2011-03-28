<?php
/**
 * Get a list of setting areas
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.system.settings
 */
if (!$modx->hasPermission('settings')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('setting');

/* setup default properties */
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$namespace = $modx->getOption('namespace',$scriptProperties,'');

/* build query */
$c = $modx->newQuery('modSystemSetting');
$c->setClassAlias('settingsArea');
$c->leftJoin('modSystemSetting', 'settingsCount', array(
    'settingsArea.' . $modx->escape('key') . ' = settingsCount.' . $modx->escape('key')
));
$c->select(array(
    'settingsArea.' . $modx->escape('area'),
    'settingsArea.' . $modx->escape('namespace'),
    'COUNT(settingsCount.' . $modx->escape('key') . ') AS num_settings'
));
if (!empty($namespace)) {
    $c->where(array(
        'settingsArea.namespace' => $namespace,
    ));
}
$c->groupby('settingsArea.' . $modx->escape('area') . ', settingsArea.' . $modx->escape('namespace'));
$c->sortby($modx->escape('area'),$dir);

$list = array();
if($c->prepare() && $c->stmt->execute()) {
    while($r = $c->stmt->fetch(PDO::FETCH_NUM)) {
        $area = $r[0];
        $name = $area;
        $namespace = $r[1];
        $count = $r[2];
        if ($namespace != 'core') {
            $modx->lexicon->load($namespace.':default');
        }
        $lex = 'area_'.$name;
        if ($modx->lexicon->exists($lex)) {
            $name = $modx->lexicon($lex);
        }
        $list[] = array(
            'd' => "$name ({$count})",
            'v' => $area,
        );
    }
}
return $this->outputArray($list);