<?php
/**
 * @package modx
 * @subpackage processors.element
 */
if (!$modx->hasPermission('view_propertyset')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('element','propertyset');
$o = '';

$element = $modx->getObject($scriptProperties['classKey'],$scriptProperties['pk']);
if ($element == null) return $modx->lexicon('element_err_nf');

$properties = $element->get('properties');

if (!empty($scriptProperties['propertySet'])) {
    $set = $modx->getObject('modPropertySet',$scriptProperties['propertySet']);
    if ($set == null) return $modx->error->failure($modx->lexicon('propertyset_err_nf'));

    $setProperties = $set->get('properties');
    $properties = array_merge($properties,$setProperties);
}

$o = '';
$props = array();
foreach ($properties as $k => $property) {
    $xtype = 'textfield';
    if (is_array($property) && !empty($property['lexicon'])) {
        if (strpos($property['lexicon'],':') !== false) {
            $modx->lexicon->load('en:'.$property['lexicon']);
        } else {
            $modx->lexicon->load('en:core:'.$property['lexicon']);
        }
        $modx->lexicon->load($property['lexicon']);
    }
    $desc = $modx->lexicon($property['desc']);

    if (is_array($property)) {
        $v = $property['value'];
        $xtype = $property['type'];
    } else { $v = $property; }


    $listener = array(
        'fn' => 'function() { Ext.getCmp(\'modx-window-insert-element\').changeProp(\''.$k.'\'); }',
    );
    switch ($xtype) {
        case 'list':
        case 'combo':
            $data = array();
            foreach ($property['options'] as $option) {
                $data[] = array($option['name'],$option['value']);
            }
            $props[] = array(
                'xtype' => 'combo',
                'fieldLabel' => $k,
                'description' => $desc,
                'name' => $k,
                'value' => $v,
                'id' => 'modx-iprop-'.$k,
                'listeners' => array('select' => $listener),
                'hiddenName' => $k,
                'displayField' => 'd',
                'valueField' => 'v',
                'mode' => 'local',
                'editable' => false,
                'forceSelection' => true,
                'typeAhead' => false,
                'triggerAction' => 'all',
                'store' => $data,
            );
            break;
        case 'boolean':
        case 'modx-combo-boolean':
        case 'combo-boolean':
            $props[] = array(
                'xtype' => 'modx-combo-boolean',
                'fieldLabel' => $k,
                'description' => $desc,
                'name' => $k,
                'value' => $v,
                'id' => 'modx-iprop-'.$k,
                'listeners' => array('select' => $listener),
            );
            break;
        case 'date':
        case 'datefield':
            $props[] = array(
                'xtype' => 'datefield',
                'fieldLabel' => $k,
                'description' => $desc,
                'name' => $k,
                'value' => $v,
                'width' => 175,
                'id' => 'modx-iprop-'.$k,
                'listeners' => array('change' => $listener),
            );
            break;
        case 'textarea':
            $props[] = array(
                'xtype' => 'textarea',
                'fieldLabel' => $k,
                'description' => $desc,
                'name' => $k,
                'value' => $v,
                'width' => 300,
                'grow' => true,
                'id' => 'modx-iprop-'.$k,
                'listeners' => array('change' => $listener),
            );
            break;
        default:
            $props[] = array(
                'xtype' => 'textfield',
                'fieldLabel' => $k,
                'description' => $desc,
                'name' => $k,
                'value' => $v,
                'width' => 300,
                'id' => 'modx-iprop-'.$k,
                'listeners' => array('change' => $listener),
            );
            break;
    }
}

return $this->toJSON($props);