<?php
/**
 * save resource form data for reload
 */
class modResourceReloadProcessor extends modProcessor {

    /** @var modRegister registry */
    private $reg;

    /**
     * initialization tasks before processing
     *
     * @return bool|string true to continue with processing, otherwise err msg
     */
    public function initialize() {
        $return = true;
        $modx =& $this->modx;
        if(!isset($modx->registry)) {
            if(!$modx->getService('registry', 'registry.modRegistry')) {
                $return = 'Could not instantiate registry service.';
            }
        }
        $modx->registry->addRegister('resource_reload', 'registry.modDbRegister', array('directory' => 'resource_reload'));
        $this->reg = $modx->registry->resource_reload;
        if(!$this->reg->connect()) {
            $return = 'Could not connect to resource_reload queue.';
        }
        return $return;
    }

    public function process() {
        $return = '';
        $scriptProperties = $this->getProperties();
        $modx = $this->modx;
        
        $topic = '/resourcereload/';
        $this->reg->subscribe($topic);

        if(array_key_exists('create-resource-token', $scriptProperties) && !empty($scriptProperties['create-resource-token'])) {
            if(array_key_exists('id', $scriptProperties) && is_numeric($scriptProperties['id']) && intval($scriptProperties['id']) > 0) {
                $return = $modx->error->success('', array(
                    'id'=> $scriptProperties['id']
                    ,'reload'=> $scriptProperties['create-resource-token']
                    ,'action'=> 'resource/update'
                ));
            } else {
                $return = $modx->error->success('', array(
                    'reload'=> $scriptProperties['create-resource-token']
                    ,'action'=> 'resource/create'
                ));
            }
            $this->reg->send($topic, array($scriptProperties['create-resource-token']=> serialize($scriptProperties)), array('ttl' => 300,'delay' => -time()));
        } else {
            return $modx->error->failure($modx->lexicon('resource_err_save'));
        }

        $this->reg->unsubscribe($topic);

        return $return;
    }
}
return 'modResourceReloadProcessor';