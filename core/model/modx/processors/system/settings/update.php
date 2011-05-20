<?php
/**
 * Update a system setting
 *
 * @param string $key The key of the setting
 * @param string $value The value of the setting
 * @param string $xtype The xtype for the setting, for rendering purposes
 * @param string $area The area for the setting
 * @param string $namespace The namespace for the setting
 * @param string $name The lexicon name for the setting
 * @param string $description The lexicon description for the setting
 *
 * @package modx
 * @subpackage processors.system.settings
 */
if (!$modx->hasPermission('settings')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('setting','namespace');

/* verify namespace */
if (empty($scriptProperties['namespace'])) return $modx->error->failure($modx->lexicon('namespace_err_ns'));
$namespace = $modx->getObject('modNamespace',$scriptProperties['namespace']);
if ($namespace == null) return $modx->error->failure($modx->lexicon('namespace_err_nf'));

/* get setting */
if (empty($scriptProperties['key'])) return $modx->error->failure($modx->lexicon('setting_err_ns'));
$setting = $modx->getObject('modSystemSetting',array(
    'key' => $scriptProperties['key'],
));
if ($setting == null) return $modx->error->failure($modx->lexicon('setting_err_nf'));

/* value parsing */
if ($scriptProperties['xtype'] == 'combo-boolean' && !is_numeric($scriptProperties['value'])) {
    if (in_array($scriptProperties['value'], array('yes', 'Yes', $modx->lexicon('yes'), 'true', 'True'))) {
        $scriptProperties['value'] = 1;
    } else $scriptProperties['value'] = 0;
}

$setting->fromArray($scriptProperties,'',true);

$refreshURIs = false;
if ($setting->get('key') === 'friendly_urls' && $setting->isDirty('value') && $setting->get('value') == '1') {
    $refreshURIs = true;
}
if ($setting->get('key') === 'use_alias_path' && $setting->isDirty('value')) {
    $refreshURIs = true;
}
if ($setting->get('key') === 'container_suffix' && $setting->isDirty('value')) {
    $refreshURIs = true;
}

/* save setting */
if ($setting->save() === false) {
    $modx->error->checkValidation($setting);
    return $modx->error->failure($modx->lexicon('setting_err_save'));
}

/* update lexicon name/description */
$setting->updateTranslation('setting_'.$scriptProperties['key'],$scriptProperties['name'],array(
    'namespace' => $namespace->get('name'),
));
$setting->updateTranslation('setting_'.$scriptProperties['key'].'_desc',$scriptProperties['description'],array(
    'namespace' => $namespace->get('name'),
));

/* if friendly_urls is set on or use_alias_path changes, refreshURIs */
if ($refreshURIs) {
    $modx->setOption($setting->get('key'), $setting->get('value'));
    $modx->call('modResource', 'refreshURIs', array(&$modx));
}

$modx->reloadConfig();
$modx->cacheManager->deleteTree($modx->getOption('core_path',null,MODX_CORE_PATH).'cache/mgr/smarty/',array(
   'deleteTop' => false,
    'skipDirs' => false,
    'extensions' => array('.cache.php','.php'),
));

return $modx->error->success('',$setting);