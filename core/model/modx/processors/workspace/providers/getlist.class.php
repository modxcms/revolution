<?php
/**
 * Gets a list of providers
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.workspace.providers
 */
class modProviderGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'transport.modTransportProvider';
    public $languageTopics = array('workspace');
    public $permission = 'providers';

    public function initialize() {
        $initialized = parent::initialize();
        $this->setDefaultProperties(array(
            'combo' => false,
        ));
        return $initialized;
    }

    public function getSortClassKey() {
        return 'modTransportProvider';
    }

    public function beforeIteration(array $list) {
        $isCombo = $this->getProperty('combo',false);
        if ($isCombo) {
            $list[] = array('id' => 0,'name' => $this->modx->lexicon('none'));
        }
        return $list;
    }

    public function prepareRow(xPDOObject $object) {
        $objectArray = $object->toArray();
        if (!$this->getProperty('combo',false)) {
            $objectArray['menu'] = array(
                array(
                    'text' => $this->modx->lexicon('provider_update'),
                    'handler' => array( 'xtype' => 'modx-window-provider-update' ),
                ),
                '-',
                array(
                    'text' => $this->modx->lexicon('provider_remove'),
                    'handler' => 'this.remove.createDelegate(this,["provider_confirm_remove", "workspace/providers/remove"])',
                )
            );
        }
        return $objectArray;
    }
}
return 'modProviderGetListProcessor';
