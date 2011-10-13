<?php
/**
 * Gets a list of resources.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @return array An array of modResources
 *
 * @package modx
 * @subpackage processors.resource
 */
class modResourceGetListProcessor extends modProcessor {
    public function getLanguageTopics() {
        return array('resource');
    }

    public function initialize() {
        $this->setDefaultProperties(array(
            'limit' => 10,
            'start' => 0,
            'sort' => 'pagetitle',
            'dir' => 'ASC',
        ));
        return true;
    }

    public function process() {
        $data = $this->getResources();
        if (empty($data)) return $this->failure();
        
        $list = array();
        $charset = $this->modx->getOption('modx_charset',null,'UTF-8');
        /** @var modResource $resource */
        foreach ($data['results'] as $resource) {
            if ($resource->checkPolicy('list')) {
                $resourceArray = $resource->toArray();
                $resourceArray['pagetitle'] = htmlentities($resourceArray['pagetitle'],ENT_COMPAT,$charset);
                $list[] = $resourceArray;
            }
        }
        return $this->outputArray($list,$data['total']);
    }

    /**
     * @return array
     */
    public function getResources() {
        $data = array();

        /* setup default properties */
        $limit = $this->getProperty('limit',10);
        $isLimit = !empty($limit);

        /* query for resources */
        $c = $this->modx->newQuery('modResource');
        $data['total'] = $this->modx->getCount('modResource',$c);

        $c->sortby($this->getProperty('sort'),$this->getProperty('dir'));
        if ($isLimit) $c->limit($limit,$this->getProperty('start'));
        $data['results'] = $this->modx->getIterator('modResource',$c);
        return $data;
    }
}
return 'modResourceGetListProcessor';