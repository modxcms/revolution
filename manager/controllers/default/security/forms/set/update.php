<?php
/**
 * Loads form customization set editing panel
 *
 * @package modx
 * @subpackage manager.security.forms
 */
if (!$modx->hasPermission('customize_forms')) return $modx->error->failure($modx->lexicon('access_denied'));

/* get profile */
if (empty($_REQUEST['id'])) return $modx->error->failure($modx->lexicon('set_err_ns'));
$c = $modx->newQuery('modFormCustomizationSet');
$c->leftJoin('modTemplate','Template');
$c->select(array(
    'modFormCustomizationSet.*',
    'Action.controller',
    'Template.templatename',
));
$c->innerJoin('modAction','Action');
$c->where(array(
    'id' => $_REQUEST['id'],
));
$set = $modx->getObject('modFormCustomizationSet',$c);
if (empty($set)) return $modx->error->failure($modx->lexicon('set_err_nfs',array('id' => $_REQUEST['id'])));

$setArray = $set->toArray();
$setData = $set->getData();

/* format fields */
$setArray['fields'] = array();
foreach ($setData['fields'] as $field) {
    $setArray['fields'][] = array(
        $field['id'],
        (int)$field['action'],
        $field['name'],
        $field['tab'],
        (int)$field['tab_rank'],
        $field['other'],
        (int)$field['rank'],
        (boolean)$field['visible'],
        $field['label'],
        $field['default_value'],
    );
}

/* format tabs */
$setArray['tabs'] = array();
foreach ($setData['tabs'] as $tab) {
    $setArray['tabs'][] = array(
        (int)$tab['id'],
        (int)$tab['action'],
        $tab['name'],
        $tab['form'],
        $tab['other'],
        (int)$tab['rank'],
        (boolean)$tab['visible'],
        $tab['label'],
        $tab['type'],
        'core',
    );
}

/* format tvs */
$setArray['tvs'] = array();
foreach ($setData['tvs'] as $tv) {
    $setArray['tvs'][] = array(
        (int)$tv['id'],
        $tv['name'],
        $tv['tab'],
        (int)$tv['rank'],
        (boolean)$tv['visible'],
        $tv['label'],
        $tv['default_value'],
        !empty($tv['category_name']) ? $tv['category_name'] : $modx->lexicon('none'),
        htmlspecialchars($tv['default_text'],null,$modx->getOption('modx_charset',null,'UTF-8')),
    );
}

if (empty($setArray['template'])) $setArray['template'] = 0;

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/fc/modx.fc.common.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/fc/modx.panel.fcset.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/fc/set/update.js');
$modx->regClientStartupHTMLBlock('<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-fc-set-update"
        ,set: "'.$set->get('id').'"
        ,record: '.$modx->toJSON($setArray).'
    });
});
// ]]>
</script>');

$modx->smarty->assign('_pagetitle',$modx->lexicon('form_customization'));
$this->checkFormCustomizationRules();
return $modx->smarty->fetch('security/forms/set.tpl');