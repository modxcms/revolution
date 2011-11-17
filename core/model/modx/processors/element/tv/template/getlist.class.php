<?php
/**
 * Grabs a list of templates associated with the TV
 *
 * @param integer $tv The ID of the TV
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 20.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.element.template.tv
 */
class modElementTvTemplateGetList extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('view_tv');
    }
    public function getLanguageTopics() {
        return array('tv');
    }
    public function initialize() {
        $this->setDefaultProperties(array(
            'start' => 0,
            'limit' => 20,
            'sort' => 'templatename',
            'dir' => 'ASC',
            'tv' => false,
        ));
        return true;
    }

    public function process() {
        $data = $this->getData();

        $list = array();
        /** @var modTemplate $template */
        foreach ($data['results'] as $template) {
            $templateArray = $this->prepareRow($template);
            if (!empty($templateArray)) {
                $list[] = $templateArray;
            }
        }

        return $this->outputArray($list,$data['total']);
    }

    /**
     * Get the Template objects
     * @return array
     */
    public function getData() {
        $data = array();
        $limit = $this->getProperty('limit');
        $isLimit = !empty($limit);

        /* query for templates */
        $c = $this->modx->newQuery('modTemplate');
        $data['total'] = $this->modx->getCount('modTemplate',$c);
        
        $c->leftJoin('modTemplateVarTemplate','TemplateVarTemplates',array(
            'modTemplate.id = TemplateVarTemplates.templateid',
            'TemplateVarTemplates.tmplvarid' => $this->getProperty('tv'),
        ));
        
        $c->select($this->modx->getSelectColumns('modTemplate','modTemplate'));
        $c->select($this->modx->getSelectColumns('modTemplateVarTemplate','TemplateVarTemplates','',array('tmplvarid')));
        $c->sortby($this->getProperty('sort'),$this->getProperty('dir'));
        if ($isLimit) $c->limit($limit,$this->getProperty('start'));
        $data['results'] = $this->modx->getCollection('modTemplate',$c);

        return $data;
    }

    /**
     * Prepare object for iteration
     *
     * @param modTemplate $template
     * @return array
     */
    public function prepareRow(modTemplate $template) {
        $templateArray = $template->toArray();
        $templateArray['access'] = $template->get('tmplvarid');
        $templateArray['access'] = empty($templateArray['access']) ? false : true;
        unset($templateArray['content']);
        return $templateArray;
    }
}
return 'modElementTvTemplateGetList';