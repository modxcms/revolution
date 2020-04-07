<?php
/**
 * Handles resolving of modActionField objects
 *
 * @var modX $modx
 * @var xPDOTransport $transport
 */

use MODX\Revolution\modActionField;

$success= true;

$xmlFile = MODX_CORE_PATH.'model/schema/modx.action.fields.schema.xml';
if (!file_exists($xmlFile)) return false;

$xml = @file_get_contents($xmlFile);
if (empty($xml)) return false;

$xml = @simplexml_load_string($xml);

if (empty($modx)) $modx =& $transport->xpdo;

$actionFields = $modx->getCollection(modActionField::class);
foreach ($actionFields as $actionField) {
    $actionField->remove();
}

foreach ($xml->action as $action) {
    $tabIdx = 0;
    foreach ($action->tab as $tab) {
        $tabName = (string)$tab['name'];
        if ($tabName != 'modx-resource-content') {
            $tabObj = $modx->getObject(modActionField::class, [
                'action' => (string)$action['controller'],
                'name' => $tabName,
                'type' => 'tab',
            ]);
            if (!$tabObj) {
                $tabObj = $modx->newObject(modActionField::class);
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
            $fieldObj = $modx->getObject(modActionField::class, [
                'action' => (string)$action['controller'],
                'name' => (string)$field['name'],
                'type' => 'field',
            ]);
            if (!$fieldObj) {
                $fieldObj = $modx->newObject(modActionField::class);
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
