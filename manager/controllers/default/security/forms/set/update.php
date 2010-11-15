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
$c->select(array(
    'modActionField.*',
    'Tab.rank AS tab_rank',
));
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
        'name' => $field->get('name'),
    ));
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
        (int)$field->get('action'),
        $field->get('name'),
        $field->get('tab'),
        (int)$field->get('tab_rank'),
        $field->get('other'),
        (int)$field->get('rank'),
        $visible ? true : false,
        $label,
        $defaultValue,
    );
}
$setArray['fields'] = $data;

/* get TVs */
$data = array();
if ($set->get('template')) {
    $c = $modx->newQuery('modTemplateVar');
    $c->leftJoin('modCategory','Category');
    $c->innerJoin('modTemplateVarTemplate','TemplateVarTemplates');
    $c->select(array(
        'modTemplateVar.*',
        'Category.category AS category_name',
    ));
    $c->where(array(
        'TemplateVarTemplates.templateid' => $set->get('template'),
    ));
    $c->sortby('Category.category','ASC');
    $c->sortby('TemplateVarTemplates.rank','ASC');
    $tvs = $modx->getCollection('modTemplateVar',$c);
    
} else {
    $c = $modx->newQuery('modTemplateVar');
    $c->leftJoin('modCategory','Category');
    $c->select(array(
        'modTemplateVar.*',
        'Category.category AS category_name',
    ));
    $c->sortby('Category.category','ASC');
    $c->sortby('modTemplateVar.name','ASC');
    $tvs = $modx->getCollection('modTemplateVar',$c);
}
foreach ($tvs as $tv) {
    $c = $modx->newQuery('modActionDom');
    $c->where(array(
        'set' => $set->get('id'),
    ));
    $c->andCondition(array(
        'name:=' => 'tv'.$tv->get('id'),
        'OR:value:=' => 'tv'.$tv->get('id'),
    ),null,2);
    $rules = $modx->getCollection('modActionDom',$c);

    $visible = true;
    $label = '';
    $defaultValue = $tv->get('default_text');
    $tab = 'modx-panel-resource-tv';
    $rank = '';
    foreach ($rules as $rule) {
        switch ($rule->get('rule')) {
            case 'tvVisible':
                if ($rule->get('value') == 0) {
                    $visible = false;
                }
                break;
            case 'tvDefault':
            case 'tvDefaultValue':
                $defaultValue = $rule->get('value');
                break;
            case 'tvTitle':
            case 'tvLabel':
                $label = $rule->get('value');
                break;
            case 'tvMove':
                $tab = $rule->get('value');
                $rank = ((int)$rule->get('rank'))-10;
                if ($rank < 0) $rank = 0;
                break;
        }
    }
    
    $data[] = array(
        $tv->get('id'),
        $tv->get('name'),
        $tab,
        $rank,
        $visible,
        $label,
        $defaultValue,
        $tv->get('category_name') != '' ? $tv->get('category_name') : $modx->lexicon('none'),
        htmlspecialchars($tv->get('default_text'),null,$modx->getOption('modx_charset',null,'UTF-8')),
    );
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
    $c = $modx->newQuery('modActionDom');
    $c->where(array(
        'set' => $set->get('id'),
        'name' => $tab->get('name'),
    ));
    $rules = $modx->getCollection('modActionDom',$c);

    $visible = true;
    $label = '';
    foreach ($rules as $rule) {
        switch ($rule->get('rule')) {
            case 'tabVisible':
                if ($rule->get('value') == 0) {
                    $visible = false;
                }
                break;
            case 'tabLabel':
            case 'tabTitle':
                $label = $rule->get('value');
                break;
        }
    }

    $data[] = array(
        $tab->get('id'),
        $tab->get('action'),
        $tab->get('name'),
        $tab->get('form'),
        $tab->get('other'),
        $tab->get('rank'),
        $visible,
        $label,
        '',
    );
}
$setArray['tabs'] = $data;

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

$this->checkFormCustomizationRules();
return $modx->smarty->fetch('security/forms/set.tpl');