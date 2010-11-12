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
$c->select(array(
    'modFormCustomizationSet.*',
    'Action.controller',
));
$c->innerJoin('modAction','Action');
$c->where(array(
    'id' => $_REQUEST['id'],
));
$set = $modx->getObject('modFormCustomizationSet',$c);
if (empty($set)) return $modx->error->failure($modx->lexicon('set_err_nfs',array('id' => $_REQUEST['id'])));

$setArray = $set->toArray();

/* get fields */
$c = $modx->newQuery('modActionField');
$c->innerJoin('modActionField','Tab','Tab.name = modActionField.tab');
$c->where(array(
    'action' => $set->get('action'),
    'type' => 'field',
));
$c->sortby('Tab.rank','ASC');
$c->sortby('modActionField.rank','ASC');
$fields = $modx->getCollection('modActionField',$c);

$data = array();
foreach ($fields as $field) {
    $c = $modx->newQuery('modActionDom');
    $c->where(array(
        'set' => $set->get('id'),
    ));
    $c->where('"'.$field->get('name').'" IN (modActionDom.name)');
    $rules = $modx->getCollection('modActionDom',$c);
    $visible = true;
    $label = '';
    $defaultValue = '';
    foreach ($rules as $rule) {
        switch ($rule->get('rule')) {
            case 'fieldVisible':
                if ($rule->get('value') == 0) {
                    $visible = false;
                }
                break;
            case 'fieldDefault':
                $defaultValue = $rule->get('value');
                break;
            case 'fieldTitle':
            case 'fieldLabel':
                $label = $rule->get('value');
                break;
        }
    }

    $data[] = array(
        $field->get('id'),
        $field->get('action'),
        $field->get('name'),
        $field->get('tab'),
        $field->get('other'),
        $field->get('rank'),
        $visible ? true : false,
        $label,
        $defaultValue,
    );
}
$setArray['fields'] = $data;

/* get TVs */
$data = array();
if ($set->get('template')) {
    $tvs = $modx->getCollection('modTemplateVar',$set->get('template'));
    foreach ($tvs as $tv) {
        $tab = $tv->get('tab');
        $data[] = array(
            $tv->get('id'),
            $tv->get('name'),
            !empty($tab) ? $tab : 'modx-panel-resource-tv',
            $tv->get('rank'),
            1,//$field->get('visible'),
            '',//$field->get('label'),
            '',//$field->get('default_value'),
        );
    }
}
$setArray['tvs'] = $data;


/* get tabs */
$c = $modx->newQuery('modActionField');
$c->where(array(
    'action' => $set->get('action'),
    'type' => 'tab',
));
$c->sortby('rank','ASC');
$tabs = $modx->getCollection('modActionField',$c);

$data = array();
foreach ($tabs as $tab) {
    $data[] = array(
        $tab->get('id'),
        $tab->get('action'),
        $tab->get('name'),
        $tab->get('form'),
        $tab->get('other'),
        $tab->get('rank'),
        1,//$field->get('visible'),
        '',//$field->get('label'),
        '',//$field->get('default_value'),
    );
}
$setArray['tabs'] = $data;


/* get tree nodes */
/*
$tree = array();

$c = $modx->newQuery('modActionField');
$c->where(array(
    'action' => $set->get('action'),
    'type' => 'tab',
));
$c->sortby('rank','ASC');
$tabs = $modx->getCollection('modActionField',$c);

foreach ($tabs as $tab) {
    $c = $modx->newQuery('modActionField');
    $c->where(array(
        'action' => $set->get('action'),
        'type' => 'field',
        'tab' => $tab->get('name'),
    ));
    $fields = $modx->getCollection('modActionField',$c);
    $children = array();
    foreach ($fields as $field) {
        $children[] = array(
            'text' => $field->get('name'),
            'leaf' => true,
            'cls' => 'fc-field',
        );
    }

    $tree[] = array(
        'text' => $tab->get('name'),
        'leaf' => false,
        'expanded' => true,
        'cls' => 'fc-tab',
        'children' => $children,
    );
}
$setArray['tree'] = $tree;*/

/* register JS scripts */
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

$this->checkFormCustomizationRules();
return $modx->smarty->fetch('security/forms/set.tpl');