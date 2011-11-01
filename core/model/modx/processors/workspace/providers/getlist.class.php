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
class modProviderGetListProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('providers');
    }
    public function getLanguageTopics() {
        return array('workspace');
    }
    public function initialize() {
        $this->setDefaultProperties(array(
            'start' => 0,
            'limit' => 10,
            'sort' => 'name',
            'dir' => 'ASC',
            'combo' => false,
        ));
        return true;
    }
    public function process() {
        $data = $this->getData();

        $list = array();
        $isCombo = $this->getProperty('combo',false);
        if ($isCombo) {
            $list[] = array('id' => 0,'name' => $this->modx->lexicon('none'));
        }
        /** @var modTransportProvider $provider */
        foreach ($data['results'] as $provider) {
            $providerArray = $provider->toArray();
            if (!$isCombo) {
                $providerArray['menu'] = array(
                    array(
                        'text' => $this->modx->lexicon('provider_update'),
                        'handler' => array( 'xtype' => 'modx-window-provider-update' ),
                    ),
                    '-',
                    array(
                        'text' => $this->modx->lexicon('provider_remove'),
                        'handler' => 'this.remove.createDelegate(this,["provider_confirm_remove"])',
                    )
                );
            }
            $list[] = $providerArray;
        }

        return $this->outputArray($list,$data['total']);
    }

    /**
     * Get the modTransportProvider objects
     * 
     * @return array
     */
    public function getData() {
        $data = array();
        $limit = intval($this->getProperty('limit'));

        $c = $this->modx->newQuery('transport.modTransportProvider');
        $data['total'] = $this->modx->getCount('transport.modTransportProvider',$c);

        $c->sortby($this->getProperty('sort'),$this->getProperty('dir'));
        if ($limit > 0) {
            $c->limit($limit,$this->getProperty('start'));
        }

        $data['results'] = $this->modx->getCollection('transport.modTransportProvider',$c);
        return $data;
    }
}
return 'modProviderGetListProcessor';