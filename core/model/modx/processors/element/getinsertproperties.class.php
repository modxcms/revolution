<?php
/**
 * @package modx
 * @subpackage processors.element
 */
class modElementGetInsertProperties extends modProcessor {
    /** @var modElement $element */
    public $element;
    
    public function checkPermissions() {
        return $this->modx->hasPermission('element_tree');
    }
    public function getLanguageTopics() {
        return array('element','propertyset');
    }

    public function initialize() {
        $this->setDefaultProperties(array(
            'classKey' => 'modSnippet',
            'pk' => false,
        ));

        $this->element = $this->modx->getObject($this->getProperty('classKey'),$this->getProperty('pk'));
        if (empty($this->element)) return $this->modx->lexicon('element_err_nf');
        return true;
    }

    public function process() {
        $properties = $this->getElementProperties();
        $list = array();
        if (!empty($properties) && is_array($properties)) {
            foreach ($properties as $key => $property) {
                $propertyArray = $this->prepareProperty($key,$property);
                if (!empty($propertyArray)) {
                    $list[] = $propertyArray;
                }
            }
        }

        return $this->toJSON($list);
    }

    /**
     * Get the properties for the element
     * @return array
     */
    public function getElementProperties() {
        $properties = $this->element->get('properties');
        $propertySet = $this->getProperty('propertySet');
        
        if (!empty($propertySet)) {
            /** @var modPropertySet $set */
            $set = $this->modx->getObject('modPropertySet',$propertySet);
            if ($set) {
                $setProperties = $set->get('properties');
                if (is_array($setProperties) && !empty($setProperties)) {
                    $properties = array_merge($properties,$setProperties);
                }
            }
        }
        return $properties;
    }

    /**
     * Prepare the property array for property insertion
     * 
     * @param string $key
     * @param array $property
     * @return array
     */
    public function prepareProperty($key,array $property) {
        $xtype = 'textfield';
        $desc = $property['desc_trans'];
        if (!empty($property['lexicon'])) {
            $this->modx->lexicon->load($property['lexicon']);
        }

        if (is_array($property)) {
            $v = $property['value'];
            $xtype = $property['type'];
        } else { $v = $property; }

        $propertyArray = array();
        $listener = array(
            'fn' => 'function() { Ext.getCmp(\'modx-window-insert-element\').changeProp(\''.$key.'\'); }',
        );
        switch ($xtype) {
            case 'list':
            case 'combo':
                $data = array();
                foreach ($property['options'] as $option) {
                    if (strtoupper(substr(trim($option['value']), 0, 5)) === '@EVAL') {
                        $modx = $this->modx; // create a reference to the MODX instance for snippets using $modx
                        $evaloptions = eval(trim(substr($option['value'], 5)));
                        $evaloptions = !is_array($evaloptions) ? explode('||', $evaloptions) : $evaloptions;
                        
                        foreach ($evaloptions as $evaloption) {
                            $evaloption = explode('==', $evaloption);
                            $data[] = array($evaloption[1], $evaloption[0]);
                        }
                    } else {
                        if (empty($property['text']) && !empty($property['name'])) $property['text'] = $property['name'];
                        $text = !empty($property['lexicon']) ? $this->modx->lexicon($option['text']) : $option['text'];
                        $data[] = array($option['value'],$text);
                    }
                }
                $propertyArray = array(
                    'xtype' => 'combo',
                    'fieldLabel' => $key,
                    'description' => $desc,
                    'name' => $key,
                    'value' => $v,
                    'width' => 300,
                    'id' => 'modx-iprop-'.$key,
                    'listeners' => array('select' => $listener),
                    'hiddenName' => $key,
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
                $propertyArray = array(
                    'xtype' => 'modx-combo-boolean',
                    'fieldLabel' => $key,
                    'description' => $desc,
                    'name' => $key,
                    'value' => $v,
                    'width' => 300,
                    'id' => 'modx-iprop-'.$key,
                    'listeners' => array('select' => $listener),
                );
                break;
            case 'date':
            case 'datefield':
                $propertyArray = array(
                    'xtype' => 'xdatetime',
                    'fieldLabel' => $key,
                    'description' => $desc,
                    'name' => $key,
                    'value' => $v,
                    'width' => 300,
                    'id' => 'modx-iprop-'.$key,
                    'listeners' => array('change' => $listener),
                );
                break;
            case 'numberfield':
                $propertyArray = array(
                    'xtype' => 'numberfield',
                    'fieldLabel' => $key,
                    'description' => $desc,
                    'name' => $key,
                    'value' => $v,
                    'width' => 300,
                    'id' => 'modx-iprop-'.$key,
                    'listeners' => array('change' => $listener),
                );
                break;
            case 'textarea':
                $propertyArray = array(
                    'xtype' => 'textarea',
                    'fieldLabel' => $key,
                    'description' => $desc,
                    'name' => $key,
                    'value' => $v,
                    'width' => 300,
                    'grow' => true,
                    'id' => 'modx-iprop-'.$key,
                    'listeners' => array('change' => $listener),
                );
                break;
            case 'file':
                $resid = $this->getProperty('resourceId');
                if (!empty($resid)) {
                    $resobj = $this->modx->getObject('modResource', array('id' => $this->getProperty('resourceId')));
                    $ctx = $resobj->get('context_key');
                    $this->modx->switchContext($ctx);
                    $sourceid = $this->modx->getOption('default_media_source', null, 1);
                    /** @var modMediaSource $source */
                    $this->modx->loadClass('sources.modMediaSource');
                    $source = modMediaSource::getDefaultSource($this->modx,$sourceid);
                    $source->initialize();
                }

                $listenerfile = array(
                    'fn' => 'function() { 
                                console.log(Ext.getCmp(\'tvbrowser'.$key.'\').getValue());
                                Ext.getCmp(\'tvbrowser'.$key.'\').setValue(this.config.relativePath + Ext.getCmp(\'tvbrowser'.$key.'\').getValue());
                                Ext.getCmp(\'modx-window-insert-element\').changeProp(\''.$key.'\');
                            }',
                );

                $propertyArray = array(
                    'xtype' => 'modx-panel-tv-file',
                    'source' => !empty($sourceid) ? $sourceid : 1,
                    'relativePath' => !empty($source) ? stristr($source->getBaseUrl(), '/') : null,
                    'wctx' => !empty($ctx) ? $ctx : 'web',
                    'tv' => $key,
                    'fieldLabel' => $key,
                    'description' => $desc,
                    'name' => $key,
                    'value' => $v,
                    'width' => 300,
                    'id' => 'modx-iprop-'.$key,
                    'listeners' => array('change' => $listenerfile, 'select' => $listenerfile),
                );
                $this->modx->switchContext('mgr');
                break;
            default:
                $propertyArray = array(
                    'xtype' => 'textfield',
                    'fieldLabel' => $key,
                    'description' => $desc,
                    'name' => $key,
                    'value' => $v,
                    'width' => 300,
                    'id' => 'modx-iprop-'.$key,
                    'listeners' => array('change' => $listener),
                );
                break;
        }
        return $propertyArray;
    }
    
}
return 'modElementGetInsertProperties';