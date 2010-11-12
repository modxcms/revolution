<?php
/**
 * Handles resolving of modActionField objects
 */
$success= false;

$xmlFile = MODX_CORE_PATH.'model/schema/modx.action.fields.schema.xml';
if (!file_exists($xmlFile)) return false;

$xml = @file_get_contents($xmlFile);
if (empty($xml)) return false;

$xml = simplexml_load_string($xml);

if (empty($modx)) $modx =& $transport->xpdo;

$c = $modx->newQuery('modActionField');
$c->innerJoin('modAction','Action');
$c->where(array(
    'Action.namespace' => 'core',
));
$actionFields = $modx->getCollection('modActionField',$c);
foreach ($actionFields as $actionField) {
    $actionField->remove();
}

$actionFields = array();
foreach ($xml->action as $action) {
    $actionObj = $modx->getObject('modAction',array(
        'controller' => (string)$action['controller'],
        'namespace' => 'core',
    ));
    if (!$actionObj) continue;
    $tabIdx = 0;

    foreach ($action->tab as $tab) {
        $tabObj = $modx->getObject('modActionField',array(
            'action' => $actionObj->get('id'),
            'name' => (string)$tab['name'],
            'type' => 'tab',
        ));
        if (!$tabObj) {
            $tabObj = $modx->newObject('modActionField');
            $tabObj->fromArray(array(
                'action' => $actionObj->get('id'),
                'name' => (string)$tab['name'],
                'type' => 'tab',
                'tab' => '',
                'form' => (string)$action['form'],
                'other' => !empty($tab['other']) ? (string)$tab['other'] : '',
                'rank' => $tabIdx,
            ));
        }
        $tabObj->save();
        $actionFields[] = $tabObj;

        $fieldIdx = 0;
        foreach ($tab->field as $field) {
            $actionField = $modx->newObject('modActionField');
            $actionField->fromArray(array(
                'action' => $actionObj->get('id'),
                'name' => (string)$field['name'],
                'type' => 'field',
                'tab' => (string)$tab['name'],
                'form' => (string)$action['form'],
                'other' => !empty($tab['other']) ? (string)$tab['other'] : '',
                'rank' => $fieldIdx,
            ));
            $actionField->save();
            $actionFields[] = $actionField;
            $fieldIdx++;
        }

        $tabIdx++;
    }
}

return $success;