<?php
/**
 * Handles resolving of modActionField objects
 *
 * @var modX $modx
 * @var xPDOTransport $transport
 */
$success = true;

$xmlFile = MODX_CORE_PATH . 'model/schema/modx.action.fields.schema.xml';
if (!file_exists($xmlFile)) return false;

$xml = @file_get_contents($xmlFile);
if (empty($xml)) return false;

$xml = @simplexml_load_string($xml);

if (empty($modx)) $modx =& $transport->xpdo;

$actionFields = $modx->getCollection('MODX\modActionField');
foreach ($actionFields as $actionField) {
    $actionField->remove();
}

foreach ($xml->action as $action) {
    $tabIdx = 0;
    foreach ($action->tab as $tab) {
        $tabName = (string)$tab['name'];
        if ($tabName != 'modx-resource-content') {
            $tabObj = $modx->getObject('modActionField', [
                'action' => (string)$action['controller'],
                'name' => $tabName,
                'type' => 'tab',
            ]);
            if (!$tabObj) {
                $tabObj = $modx->newObject('MODX\modActionField');
                $tabObj->fromArray([
                    'action' => (string)$action['controller'],
                    'name' => $tabName,
                    'type' => 'tab',
                    'tab' => '',
                    'form' => (string)$action['form'],
                    'other' => !empty($tab['other']) ? (string)$tab['other'] : '',
                    'rank' => $tabIdx,
                ]);
                $success = $tabObj->save();
            }
        }

        $fieldIdx = 0;
        foreach ($tab->field as $field) {
            $fieldObj = $modx->getObject('MODX\modActionField', [
                'action' => (string)$action['controller'],
                'name' => (string)$field['name'],
                'type' => 'field',
            ]);
            if (!$fieldObj) {
                $fieldObj = $modx->newObject('MODX\modActionField');
                $fieldObj->fromArray([
                    'action' => (string)$action['controller'],
                    'name' => (string)$field['name'],
                    'type' => 'field',
                    'tab' => (string)$tab['name'],
                    'form' => (string)$action['form'],
                    'other' => !empty($tab['other']) ? (string)$tab['other'] : '',
                    'rank' => $fieldIdx,
                ]);
                $success = $fieldObj->save();
            }
            $fieldIdx++;
        }

        $tabIdx++;
    }
}

return $success;