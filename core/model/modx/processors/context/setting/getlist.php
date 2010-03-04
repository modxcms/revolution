<?php
/**
 * Grabs a list of context settings for a context
 *
 * @param string $context_key The context from which to grab
 * @param string $key (optional) A filter key by which to search settings
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 20.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.context.setting
 */
if (!$modx->hasPermission('settings')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('setting');

if (!isset($scriptProperties['start'])) $scriptProperties['start'] = 0;
if (!isset($scriptProperties['limit'])) $scriptProperties['limit'] = 10;
if (!isset($scriptProperties['sort'])) $scriptProperties['sort'] = 'key';
if (!isset($scriptProperties['dir'])) $scriptProperties['dir'] = 'ASC';

$wa = array(
    'context_key' => $scriptProperties['context_key'],
);
if (!$context = $modx->getObject('modContext', $scriptProperties['context_key'])) return $modx->error->failure($modx->lexicon('setting_err_nf'));
if (!$context->checkPolicy('view')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (isset($scriptProperties['key']) && $scriptProperties['key'] != '') {
    $wa['key:LIKE'] = '%'.$scriptProperties['key'].'%';
}

$c = $modx->newQuery('modContextSetting');
$c->where($wa);
$c->sortby('`'.$scriptProperties['sort'].'`',$scriptProperties['dir']);
$c->limit($scriptProperties['limit'],$scriptProperties['start']);
$settings = $modx->getCollection('modContextSetting',$c);

$cc = $modx->newQuery('modContextSetting');
$cc->where($wa);
$count = $modx->getCount('modContextSetting',$cc);

$ss = array();
foreach ($settings as $setting) {
    $sa = $setting->toArray();

    $k = 'setting_'.$sa['key'];

    /* if 3rd party setting, load proper text */
    $modx->lexicon->load($setting->get('namespace').':default');

    if ($modx->lexicon->exists('area_'.$setting->get('area'))) {
        $sa['area_text'] = $modx->lexicon('area_'.$setting->get('area'));
    } else $sa['area_text'] = $sa['area'];

    $sa['description'] = $modx->lexicon->exists($k.'_desc')
        ? $modx->lexicon($k.'_desc')
        : '';
    $sa['name'] = $modx->lexicon->exists($k)
        ? $modx->lexicon($k)
        : $sa['key'];
    $sa['menu'] = array(
        array(
            'text' => $modx->lexicon('setting_update'),
            'handler' => array(
                'xtype' => 'modx-window-context-setting-update',
                'record' => $sa,
            ),
        ),
        '-',
        array(
            'text' => $modx->lexicon('setting_remove'),
            'handler' => 'this.remove.createDelegate(this,["setting_remove_confirm"])',
        ),
    );
    $ss[] = $sa;
}
return $this->outputArray($ss,$count);