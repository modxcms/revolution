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

        foreach ($scriptProperties as $key => &$value) {
            $matched = preg_match("/^tv(\d+)$/i", $key, $matches);
            if ($matched && !empty($matches[1])) {
                $tv = $this->modx->getObject('modTemplateVar', $matches[1]);
                if ($tv) {
                    /* validation for different types */
                    switch ($tv->get('type')) {
                        case 'url':
                            $prefix = $this->getProperty($key.'_prefix','');
                            if ($prefix != '--') {
                                $value = str_replace(array('ftp://','http://'),'', $value);
                                $value = $prefix.$value;
                            }
                            break;
                        case 'date':
                            $value = empty($value) ? '' : strftime('%Y-%m-%d %H:%M:%S',strtotime($value));
                            break;
                        /* ensure tag types trim whitespace from tags */
                        case 'tag':
                        case 'autotag':
                            $tags = explode(',',$value);
                            $newTags = array();
                            foreach ($tags as $tag) {
                                $newTags[] = trim($tag);
                            }
                            $value = implode(',',$newTags);
                            break;
                        default:
                            /* handles checkboxes & multiple selects elements */
                            if (is_array($value)) {
                                $featureInsert = array();
                                while (list($featureValue, $featureItem) = each($value)) {
                                    if(empty($featureItem)) { continue; }
                                    $featureInsert[count($featureInsert)] = $featureItem;
                                }
                                $value = implode('||',$featureInsert);
                            }
                            break;
                    }
                }
            }
        }

        if(array_key_exists('create-resource-token', $scriptProperties) && !empty($scriptProperties['create-resource-token'])) {
            $topic = '/resourcereload/';
            $this->reg->subscribe($topic);

            if(array_key_exists('id', $scriptProperties) && is_numeric($scriptProperties['id']) && intval($scriptProperties['id']) > 0) {
                $return = $modx->error->success('', array(
                    'id'=> $scriptProperties['id']
                    ,'reload'=> $scriptProperties['create-resource-token']
                    ,'action'=> 'resource/update'
                    ,'class_key' => $scriptProperties['class_key']
                ));
            } else {
                $return = $modx->error->success('', array(
                    'reload'=> $scriptProperties['create-resource-token']
                    ,'action'=> 'resource/create'
                    ,'class_key' => $scriptProperties['class_key']
                ));
            }
            $this->reg->send($topic, array($scriptProperties['create-resource-token'] => $scriptProperties), array('ttl' => 300,'delay' => -time()));
            $this->reg->unsubscribe($topic);
        } else {
            return $modx->error->failure($modx->lexicon('resource_err_save'));
        }
        return $return;
    }
}
return 'modResourceReloadProcessor';