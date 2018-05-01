<?php
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

require_once (dirname(__DIR__).'/getlist.class.php');
/**
 * Grabs a list of templates.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 20.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @return array An array of modTemplate
 *
 * @package modx
 * @subpackage processors.element.template
 */
class modTemplateGetListProcessor extends modElementGetListProcessor {
    public $classKey = 'modTemplate';
    public $languageTopics = array('template','category');
    public $defaultSortField = 'templatename';
    public $permission = 'view_template';

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c = parent::prepareQueryBeforeCount($c);
        
        $query = $this->getProperty('query');
        
        if (!empty($query)) {
            $c->where(array(
                'templatename:LIKE' => '%' . $query . '%'
            ));
        }
        
        $c->sortby('category_name');
        $c->sortby('templatename');
        
        return $c;
    }

    public function beforeIteration(array $list) {
        if ($this->getProperty('combo', false) && !$this->getProperty('query', false)) {
            $list[] = array(
                'id'            => 0,
                'templatename'  => $this->modx->lexicon('template_empty'),
                'description'   => '',
                'category_name' => '',
                'time'          => time()
            );
        }
        
        return $list;
    }

    public function prepareRow(xPDOObject $object) {
        $preview = $object->getPreviewUrl();
        
        if (!empty($preview)) {
            $imageQuery = http_build_query(array(
                'src'           => $preview,
                'w'             => 335,
                'h'             => 236,
                'HTTP_MODAUTH'  => $this->modx->user->getUserToken($this->modx->context->get('key')),
                'zc'            => 1
            ));
            
            $preview = $this->modx->getOption('connectors_url', MODX_CONNECTORS_URL) . 'system/phpthumb.php?' . urldecode($imageQuery);
        }


        $array  = array(
            'id'            => $object->get('id'),
            'templatename'  => $object->get('templatename'),
            'description'   => $object->get('description'),
            'category_name' => $object->get('category_name'),
            'preview'       => $preview,
            'time'          => time()
        );
        
        return $array;
    }
}

return 'modTemplateGetListProcessor';
