<?php
/**
 * Handles resolving of modActionField objects
 */
$success= true;

$xmlFile = MODX_CORE_PATH.'model/schema/modx.action.fields.schema.xml';
if (!file_exists($xmlFile)) return false;

$xml = @file_get_contents($xmlFile);
if (empty($xml)) return false;

$xml = @simplexml_load_string($xml);

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

foreach ($xml->action as $action) {
    $actionObj = $modx->getObject('modAction',array(
        'controller' => (string)$action['controller'],
        'namespace' => 'core',
    ));
    if (!$actionObj) continue;
    $tabIdx = 0;

    foreach ($action->tab as $tab) {
        $tabName = (string)$tab['name'];
        if ($tabName != 'modx-resource-content') {
            $tabObj = $modx->getObject('modActionField',array(
                'action' => $actionObj->get('id'),
                'name' => $tabName,
                'type' => 'tab',
            ));
            if (!$tabObj) {
                $tabObj = $modx->newObject('modActionField');
                $tabObj->fromArray(array(
                    'action' => $actionObj->get('id'),
                    'name' => $tabName,
                    'type' => 'tab',
                    'tab' => '',
                    'form' => (string)$action['form'],
                    'other' => !empty($tab['other']) ? (string)$tab['other'] : '',
                    'rank' => $tabIdx,
                ));
                $success = $tabObj->save();
            }
        }

        $fieldIdx = 0;
        foreach ($tab->field as $field) {
            $fieldObj = $modx->getObject('modActionField',array(
                'action' => $actionObj->get('id'),
                'name' => (string)$field['name'],
                'type' => 'field',
            ));
            if (!$fieldObj) {
                $fieldObj = $modx->newObject('modActionField');
                $fieldObj->fromArray(array(
                    'action' => $actionObj->get('id'),
                    'name' => (string)$field['name'],
                    'type' => 'field',
                    'tab' => (string)$tab['name'],
                    'form' => (string)$action['form'],
                    'other' => !empty($tab['other']) ? (string)$tab['other'] : '',
                    'rank' => $fieldIdx,
                ));
                $success = $fieldObj->save();
            }
            $fieldIdx++;
        }

        $tabIdx++;
    }
}

return $success;